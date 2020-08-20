<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Response;
use DateTime; 
use DateInterval;
use Hash;
use Mail;
use App\Ipn;
use App\User;
use App\Membership;
use App\MemberDetail;
use App\UserDetail;
use App\Audit;
use App\Setup;
use App\Product;
use App\ProductCategory;
use App\EmailResponder;
use App\Site;

class GeneralApiController extends Controller
{
    public function getProducts($subdomain, $category){
        $productCategories = ProductCategory::where('category_id', $category)->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productCategories)->get();

        return Response::json(['success' => true, 'data' => $products]);
    }

    public function getProduct($subdomain, $id){
        $product = Product::where('id', $id)->first();

        return Response::json(['success' => true, 'data' => $product]);
    }

    public function jvzoo(Request $request) {
        activity()->log('Process: Start request');
        
        $data = $request->all();
        $jvzooIpnVerification = $this->jvzIPNVerification($request);
        activity()->log('Process: jvzoo ipn verification results: ' . $jvzooIpnVerification);

		if (!$jvzooIpnVerification){
            $message = "Oops! Something went wrong. Please try again.";
            activity()->log('Error: Jvzoo IPN Verification');
            return redirect('errors.jvzoo', compact('message'));
        }
            
		if (!isset($data['ccustemail']) || !isset($data['ctransreceipt'])) {
			$message = "Invalid request. Please try again.";
            activity()->log('Error: Jvzoo [ccustemail] and [ctransreceipt]');
            return redirect('errors.jvzoo', compact('message'));
        }
        
        $fields = ["ctransreceipt", "ccustemail", "ccustname", "ctransvendor", "cproditem", "cprodtype", "ctransaction", "ctransamount", "ctranstime"];
        $str = "";
        
        activity()->log('Process: Mapping post fields');
		foreach($fields as $val){
			${$val} = $data[$val];
        }
                
        //check customer_product_item if existing on membership
        $membership = Membership::where('jvzoo_product_id', $cproditem)->first();
        activity()->log('Process: Check customer_product_item if existing on membership');

        if(isset($membership)){
            //check transactions ctransreceipt and ctransaction
            activity()->log('Process: Check transactions ctransreceipt and ctransaction');
            $validateTransaction = Ipn::where('ctransreceipt', $ctransreceipt)->where('ctransaction', $ctransaction)->count();
            if($validateTransaction == 0){
                activity()->log('Process: IPN create');
                $ipn = Ipn::create([
                    "ctransreceipt" => $ctransreceipt, 
                    "ccustemail" => $ccustemail, 
                    "ccustname" => $ccustname, 
                    "ctransvendor" => $ctransvendor, 
                    "cproditem" => $cproditem, 
                    "cprodtype" => $cprodtype, 
                    "ctransaction" => $ctransaction, 
                    "ctransamount" => $ctransamount, 
                    "ctranstime" => $ctranstime
                ]);

                activity()->log('Process: Switch Transactions');
                
                switch ($ctransaction) {
                    case 'SALE':
                        //insert new user with member details
                        activity()->log('Process: Transaction Sale');
                        $this->sale($data, $membership);
                        // $mesage = 'Thanks for your Purchase!';
                        // return redirect()->route('jvzoo.thankyou', compact('message', 'ctransaction'));
                        break;
                    case 'BILL':
                        //set new expiration date for the use
                        activity()->log('Process: Transaction Bill');
                        $this->bill($data, $membership);
                        // $mesage = 'Purchase updated. Thanks for your Purchase!';
                        // return redirect()->route('jvzoo.thankyou', compact('message', 'ctransaction'));
                        break;
                    case 'RFND':
                        //set expiration today
                        activity()->log('Process: Transaction Refund');
                        $this->refund($data, $membership);
                        // $mesage = 'Refund successfully processed.';
                        // return redirect()->route('jvzoo.thankyou', compact('message', 'ctransaction'));
                        break;
                }
            }
        }
    }

	public function jvzIPNVerification($request) {
        $secretKey = Setup::where('key', 'jvzipn')->first()->value;
	    $pop = "";
	    $ipnFields = [];
        activity()->log('Process: ' . $secretKey);
        activity()->log('Process post values: ' . json_encode($_POST));

        foreach ($_POST AS $key => $value) {
	        if ($key == "cverify") {
	            continue;
            }
            
	        $ipnFields[] = $key;
	    }

        sort($ipnFields);
        activity()->log('Process: pop '. json_encode($ipnFields));
        
        foreach ($ipnFields as $field) {
	        // if Magic Quotes are enabled $_POST[$field] will need to be
	        // un-escaped before being appended to $pop
	        $pop = $pop . $_POST[$field] . "|";
        }
        
        activity()->log('Process: pop '. $pop);
        $pop = $pop . $secretKey;
        // $pop = $pop;

        if ('UTF-8' != mb_detect_encoding($pop)) {
            $pop = mb_convert_encoding($pop, "UTF-8");
        }

        $calcedVerify = sha1($pop);
        activity()->log('Process: calculated ' .  $calcedVerify );
        
        $calcedVerify = strtoupper(substr($calcedVerify,0,8));
        $result = ($calcedVerify == $_POST["cverify"]) ? 'true' : 'false';
        
        activity()->log('Process: ' .  $calcedVerify .'---'. $_POST["cverify"] . ' result');
        activity()->log('Process: verification result ' . $result);
        
	    return $calcedVerify == $_POST["cverify"];
    }
    
    //purchase of a standard product 
    public function sale($data, $membership){
        //set login URL
        $url = route('login');
        
        $member = User::where('email', $data['ccustemail'])->first();
        activity()->log('Process: Check new member');
        
        // new member
        if(!isset($member)){
            activity()->log('Process: New member');
            
            $exiration_date = new DateTime();

            $exiration_date->add(new DateInterval('P'.$membership->frequency.'M'));
            activity()->log('Process: set expiration date' . $exiration_date->format('Y-m-d H:i:s'));
            
            $password = $this->randomPassword(6);
            $name_array = $this->getFirstLastName($data['ccustname']); 
            $first_name = $name_array['first_name'];
            $last_name = $name_array['last_name'];

            activity()->log('Process: add user');
            $user = User::create([
                'name' => $data['ccustname'],
                'email' => $data['ccustemail'],
                'password' => Hash::make($password),
                'role_id' => 3,
                'active' => 1
            ]);
            activity()->log('Process: add user details');
            $user_details = UserDetail::create([
                'user_id' => $user->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
            ]);
            activity()->log('Process: add member details');
            $member_details = MemberDetail::create([
                'user_id' => $user->id,
                'membership_id' => $membership->id,
                'expiry_date' => $membership->frequency == '120' ? null : $exiration_date->format('Y-m-d H:i:s')
            ]);
            activity()->log('Process: log new signup');
            Audit::create([
                'type' => 'signup',
                'action' => 'register a new member',
                'user_id' => $user->id
            ]);
            activity()->log('Process: send emails');
            //send emails to admin, member, and autoresponders
            $sendMail = $this->sendMails($user, $password);
            print("Thanks for your Purchase, <b>". $data['ccustname'] ."</b>. Below is your login details to Sign In at <b>" . route('login') . "</b><br /><br />E-mail Address: <b>". $data['ccustemail'] ."</b><br />Password: <b>". $password ."</b><br /><br />You can change your password at any time under profile");
        }else{
            activity()->log('Process: Old member update membership');
            
            $exiration_date = new DateTime(); 
            $exiration_date->add(new DateInterval('P'.$membership->frequency.'M'));
            activity()->log('Process: old membership ' . json_encode($member->memberDetail->membership));

            activity()->log('Process: update membership');
            $member->memberDetail->update([
                'membership_id' => $membership->id,
                'expiry_date' => $membership->frequency == '120' ? null : $exiration_date->format('Y-m-d H:i:s')
            ]);
            activity()->log('Process: member: ' . json_encode($member) . ' ---new memberhsip: ' . json_encode($membership));

            print("Thanks for your Purchase, <b>". $data['ccustname'] ."</b>. You have successfully upgraded your membership to ". $membership->title .". You may sign in at <b>" . route('login') . "</b> with your current login credentials<br /><br />You can change your password at any time under profile");
        }
    }

    //rebill for a recurring billing 
    public function bill($data, $membership){
        if($ctransamount >= $membership->trial_price){
            $member = User::where('email', $data['ccustemail'])->first();

            if(isset($member)){
                // if($data['ctransamount'] >= $membership->product_price){
                    $exiration_date = $member->memberDetail->expiry_date !== null ? $member->memberDetail->expiry_date : new DateTime();
                    $exiration_date->add(new DateInterval('P'.$membership->frequency.'M'));
    
                    $member->memberDetail->update([
                        'expiry_date' => $membership->frequency == '120' ? null : $exiration_date->format('Y-m-d H:i:s')
                    ]);
                // }
            }
        }
    }

    //refunding of a standard or recurring billing product
    public function refund($data, $membership){
        $member = User::where('email', $data['ccustemail'])->first();

        if(isset($member)){
            $exiration_date = new DateTime();

            $member->memberDetail->update([
                'expiry_date' => $exiration_date->format('Y-m-d H:i:s'),
            ]);

            $member->update(['active' => 0]);
        }
    }

    public function getFirstLastName($name){
        $name_array = explode(" ", $name);
        $result = [];
        if(count($name_array) == 2){
            $result['first_name'] = $name_array[0];
            $result['last_name'] = $name_array[1];
        }elseif(count($name_array) > 2){
            $result['last_name'] = $name_array[count($name_array) - 1];
            unset($name_array[count($name_array) - 1]);
            $result['first_name'] = implode(' ', $name_array);
        }else{
            $result['first_name'] = $name_array[0];
            $result['last_name'] = '';
        }

        return $result;
    }

    public function randomPassword($length) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $password = []; 
        $alphaLength = strlen($alphabet) - 1;
       
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $password[] = $alphabet[$n];
        }

        return implode($password);
    }

    
    public function testSendMail(){
        // $users = User::where('id', '4')->get();
        // for ($i=0; $i < 50; $i++) { 
        
        //     foreach ($users as $user) {
        //         $password = $this->randomPassword(6);
    
        //         $this->sendMails($user, $password);
        //     }
        // }
        return "Thanks for your Purchase, <b> asdsad </b>. You have successfully upgraded your membership to asds You may sign in at <b>" . route('login') . "</b> with your current login credentials<br /><br />You can change your password at any time under profile";
        
    }

    public function sendMails($user, $password){
        $site = Setup::where('key', 'site_name')->first();
        
        if($this->validCredentials()){
            $this->addEmailtoCampaign($user->email);
        }

        $this->sendToMember($user, $site, $password);
        $this->sendToAdmin($user, $site, $password);
        $this->sendToAutoResponder($user, $site);

        return true;
    }

    public function validCredentials(){
        $settings = Setup::whereIn('id', ['6', '7', '11'])->get();

        foreach ($settings as $setting) {
            if($setting->value == ""){
                return false;
                break;
            }
        }

        return true;
    }

    public function addEmailtoCampaign($email){
        $settings = Setup::whereIn('id', ['6', '7', '11'])->get();

        try{
            $client = new Client();

            $response = $client->request('POST', 'https://api.getresponse.com/v3/contacts', [
                'headers' => [
                    'X-Auth-Token' => 'api-key ' . trim($settings->where('key', 'api_key')->first()->value),
                    'Content-Type'     => 'application/json',
                ], 
                'json' => [
                    'email' => $email,
                    'campaign' => [
                        'campaignId' => $settings->where('key', 'campaign_id')->first()->value
                    ]
                ]
            ]);
            
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function sendToMember($user, $site, $password){
        $email = EmailResponder::find(1);
        $from = $email->from;
        $to = $user->email;
        $body = $email->body;

        $subject = str_replace('%SITENAME%', $site->value , $email->subject);
        $body = str_replace('%FNAME%', $user->name , $body);
        $body = str_replace('%SITENAME%', $site->value , $body);
        $body = str_replace('%EMAIL%', $user->email , $body);
        $body = str_replace('%PASS%', $password , $body);

        Mail::send([], [], function ($message) use ($from, $to, $body, $subject, $site, $user) {
            $message->from(env('MAIL_USERNAME'), $site->value);
            $message->to($to, $user->name);
            $message->subject($subject);
            $message->setBody(html_entity_decode($body), 'text/html');
        });
    }

    public function sendToAdmin($user, $site, $password){
        $email = EmailResponder::find(2);
        $from = $email->from;
        $to = $email->to;
        $body = $email->body;

        $subject = str_replace('%SITENAME%', $site->value , $email->subject);
        $body = str_replace('%SITENAME%', $site->value , $body);
        $body = str_replace('%FNAME%', $user->detail->first_name , $body);
        $body = str_replace('%LNAME%', $user->detail->last_name , $body);
        $body = str_replace('%EMAIL%', $user->email , $body);
        $body = str_replace('%PASS%', $password , $body);

        Mail::send([], [], function ($message) use ($from, $to, $body, $subject, $site, $user) {
            $message->from(env('MAIL_USERNAME'), $site->value);
            $message->to($to, $user->name);
            $message->subject($subject);
            $message->setBody(html_entity_decode($body), 'text/html');
        });
    }

    public function sendToAutoResponder($user, $site){
        $email = EmailResponder::find(3);
        $from = $email->from;
        $to = $email->to;
        $body = $email->body;
        
        $subject = str_replace('%SITENAME%', $site->value , $email->subject);
        $body = str_replace('%FNAME%', $user->detail->first_name , $body);
        $body = str_replace('%LNAME%', $user->detail->last_name , $body);
        $body = str_replace('%EMAIL%', $user->email , $body);

        Mail::send([], [], function ($message) use ($from, $to, $body, $subject, $site, $user) {
            $message->from(env('MAIL_USERNAME'), $site->value);
            $message->to($to, $user->name);
            $message->subject($subject);
            $message->setBody(html_entity_decode($body), 'text/html');
        });
    }

    public function thankyou(){
        return view('jvzoo-thankyou');
    }

    public function spinContent(Request $request){
        $url = 'http://thebestspinner.com/api.php';
      
        # Build the data array for authenticating.
        
        $data = [];
        $data['action'] = 'authenticate';
        $data['format'] = 'php'; # You can also specify 'xml' as the format.
        
        # The user credentials should change for each UAW user with a TBS account.
        
        $data['username'] = env('THEBEST_SPINNER_USERNAME');
        $data['password'] = env('THEBEST_SPINNER_PASWORD');
        
        # Authenticate and get back the session id.
        # You only need to authenticate once per session.
        # A session is good for 24 hours.
        $output = unserialize($this->curlPost($url, $data, $info));
        
        if($output['success'] == 'true'){
            # Success.
            $session = $output['session'];
            
            $reWriteSentence = $this->rewriteSentences($session, $request->text, $url);

            if($reWriteSentence['success'] == 'true'){
                $randomSpin = $this->randomSpin($session, $reWriteSentence['output'], $url);

                return response()->json(['success' => 'true', 'output' => $randomSpin['output']]);
            }else{
                return response()->json(['success' => 'false', 'error' => $reWriteSentence['error']]);
            }
        } else {
            return response()->json(['success' => 'false', 'error' => $output['error']]);
        }
    }

    public function rewriteSentences($session, $text, $url){
        $data = [];
        $data['session'] = $session;
        $data['action'] = 'identifySynonyms';
        $data['text'] = $text;
        $data['format'] = 'php'; # You can also specify 'xml' as the format.
        
        # Post to API and get back results.
        $reWriteSentence = $this->curlPost($url, $data, $info);
        return unserialize($reWriteSentence);
    }

    public function randomSpin($session, $text, $url){
        $data = [];
        $data['session'] = $session;
        $data['action'] = 'randomSpin';
        $data['text'] = $text;
        $data['format'] = 'php'; # You can also specify 'xml' as the format.
        
        # Post to API and get back results.
        $spinText = $this->curlPost($url, $data, $info);
        return unserialize($spinText);
    }

    public function curlPost($url, $data, &$info){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->curlPostData($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        $html = trim(curl_exec($ch));
        curl_close($ch);
      
        return $html;
      }
      
    public function curlPostData($data){
        $fdata = "";

        foreach($data as $key => $val){
          $fdata .= "$key=" . urlencode($val) . "&";
        }
      
        return $fdata;
    }
}
