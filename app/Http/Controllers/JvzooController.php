<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DateTime; 
use DateInterval;
use Hash;
use Mail;
use App\Ipn;
use App\User;
use App\Membership;
use App\MemberDetail;
use App\Audit;
use App\Setup;

class JvzooController extends Controller
{
    
	public function index(Request $request) {
        $data = $request->all();

		if ($this->jvzIPNVerification($request) != 1){
            $message = "Oops! Something went wrong. Please try again.";
            return redirect('errors.jvzoo', compact('message'));
        }
            
		if (!isset($data['ccustemail']) || !isset($data['ctransreceipt'])) {
			$message = "Invalid request. Please try again.";
            return redirect('errors.jvzoo', compact('message'));
        }
        
        $fields = ["ctransreceipt", "ccustemail", "ccustname", "ctransvendor", "cproditem", "cprodtype", "ctransaction", "ctransamount", "ctranstime"];
        $str = "";
        
		foreach($fields as $val){
			${$val} = $data[$val];
        }
                
        //check customer_product_item if existing on membership
        $membership = Membership::where('jvzoo_product_id', $cproditem)->first();

        if(isset($membership)){
            //check transactions ctransreceipt and ctransaction
            $validateTransaction = Ipn::where('ctransreceipt', $ctransreceipt)->where('ctransaction', $ctransaction)->count();
            if($validateTransaction == 0){
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
                
                switch ($ctransaction) {
                    case 'SALE':
                        //insert new user with member details
                        $this->sale($data, $member_details);
                        // $mesage = 'Thanks for your Purchase!';
                        // return redirect()->route('jvzoo.thankyou', compact('message', 'ctransaction'));
                        break;
                    case 'BILL':
                        //set new expiration date for the use
                        $this->bill($data, $member_details);
                        // $mesage = 'Purchase updated. Thanks for your Purchase!';
                        // return redirect()->route('jvzoo.thankyou', compact('message', 'ctransaction'));
                        break;
                    case 'RFND':
                        //set expiration today
                        $this->refund($data, $member_details);
                        // $mesage = 'Refund successfully processed.';
                        // return redirect()->route('jvzoo.thankyou', compact('message', 'ctransaction'));
                        break;
                }
            }
        }
    }
    
	public function jvzIPNVerification($request) {
		$secretKey = env('JVZOO_KEY');
	    $pop = "";
	    $ipnFields = [];

        foreach ($_POST AS $key => $value) {
	        if ($key == "cverify") {
	            continue;
            }
            
	        $ipnFields[] = $key;
	    }

        sort($ipnFields);
        
        foreach ($ipnFields as $field) {
	        // if Magic Quotes are enabled $_POST[$field] will need to be
	        // un-escaped before being appended to $pop
	        $pop = $pop . $_POST[$field] . "|";
        }
        
	    $pop = $pop . $secretKey;
	    $calcedVerify = sha1(mb_convert_encoding($pop, "UTF-8"));
        $calcedVerify = strtoupper(substr($calcedVerify, 0, 8));
        
	    return $calcedVerify == $_POST["cverify"];
    }
    
    //purchase of a standard product 
    public function sale($data, $membership){
        //set login URL
        $url = route('login');
        
        $member = User::where('email', $data['ccustemail'])->first();
        // new member
        if(!isset($member)){
            $exiration_date = new DateTime();

            // if TRIAL period, the amount should be equal to the Trial Period Price...
            if($membership->trial_period !== null && $ctransamount >= $membership->trial_price){
                $exiration_date->add(new DateInterval('P'.$membership->trial_period.'D'));
            }
            // if no TRIAL period, checking the amount>=pack_price
            elseif($ctransamount >= $membership->trial_price){
                $exiration_date->add(new DateInterval('P'.$membership->frequency.'M'));
            }

            // the amount corresponds to the Product Price
            $dateDifference = date_diff(new DateTime(), $exiration_date)->days;
            if($dateDifference > 0){
                $password = randomPassword(6);
                $name_array = explode(" ", $data['ccustname']);
                $first_name = $name_array[0];
                $last_name = $name_array[1];

                $user = User::create([
                    'name' => $data['ccustname'],
                    'email' => $data['ccustemail'],
                    'password' => Hash::make($password),
                    'role_id' => 3,
                    'active' => 1
                ]);

                $user_details = UserDetail::create([
                    'user_id' => $user->id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                ]);

                $member_details = MemberDetail::create([
                    'user_id' => $user->id,
                    'membership_id' => $membership->id,
                    'expiry_date' =>  $exiration_date->format('Y-m-d H:i:s')
                ]);

                Audit::create([
                    'type' => 'signup',
                    'action' => 'register a new member',
                    'user_id' => $user->id
                ]);
                
                //send emails to admin, member, and autoresponders
                $sendMail = $this->sendMails($user, $password);
                print("Thanks for your Purchase, <b>". $data['ccustname'] ."</b>. Below is your login details to Sign In at <b>" . route('login') . "</b><br /><br />E-mail Address: <b>". $data['ccustemail'] ."</b><br />Password: <b>". $password ."</b><br /><br />You can change your password at any time under profile");
            }
        }else{
            // if amount corresponds to the Product Price
            if($ctransamount >= $membership->trial_price){
                $exiration_date = new DateTime(); 

                // if TRIAL period, the amount should be equal to the Trial Period Price...
                if($membership->trial_period !== null && $ctransamount >= $membership->trial_price){
                    $exiration_date->add(new DateInterval('P'.$membership->trial_period.'D'));
                }else{
                    $exiration_date = null;
                }

                $member->memberDetail->update([
                    'membership_id' => $membership->id,
                    'expiry_date' => $exiration_date->format('Y-m-d H:i:s')
                ]);
                print("Thanks for your Purchase, <b>". $data['ccustname'] ."</b>.You may sign in at <b>" . route('login') . "</b> with your current login credentials<br /><br />You can change your password at any time under profile");
            }
        }
    }

    //rebill for a recurring billing 
    public function bill($data, $membership){
        if($ctransamount >= $membership->trial_price){
            $member = User::where('email', $data['ccustemail'])->first();

            if(isset($member)){
                $exiration_date = $member->memberDetail->expiry_date !== null ? $member->memberDetail->expiry_date : new DateTime();
                $exiration_date->add(new DateInterval('P'.$membership->frequency.'M'));

                $member->memberDetail->update([
                    'expiry_date' => $exiration_date->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    //refunding of a standard or recurring billing product
    public function refund(){
        $member = User::where('email', $data['ccustemail'])->first();

        if(isset($member)){
            $exiration_date = new DateTime();

            $member->memberDetail->update([
                'expiry_date' => $exiration_date->format('Y-m-d H:i:s'),
            ]);
        }
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

    public function sendMails($user, $password){
        $site = Setup::where('key', 'site_name')->first();
        
        $this->sendToMember($user, $site, $password);
        $this->sendToAdmin($user, $site, $password);
        $this->sendToAutoResponder($user, $site);

        return true;
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
            $message->setBody($body, 'text/html');
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
            $message->setBody($body, 'text/html');
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
            $message->setBody($body, 'text/html');
        });
    }

    public function thankyou(){
        return view('jvzoo-thankyou');
    }

}
