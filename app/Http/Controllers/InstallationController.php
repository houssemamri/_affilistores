<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alaouy\Youtube\Facades\Youtube;
use Thujohn\Twitter\Facades\Twitter;
use DB;
use Session;
use Artisan;
use Mail;
use Hash;
use App\User;
use App\UserDetail;
use App\Setup;

class InstallationController extends Controller
{
    public function stepOne(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'site_name' => 'required',
                'database_name' => 'required',
                'database_username' => 'required',
                'database_password' => 'required'
            ]);

            $data =[
                'DB_DATABASE'   => $request->database_name,
                'DB_USERNAME'   => $request->database_username,
                'DB_PASSWORD'   => $request->database_password,
                'SESSION_DOMAIN' => $_SERVER['HTTP_HOST'],
                'APP_DOMAIN' => $_SERVER['HTTP_HOST'],
            ];

            if($this->changeEnvConfiguration($data)){
                $connection = $this->testConnection($request);

                if($connection){
                    $this->migrateAndSeed();

                    $siteName = Setup::where('key', 'site_name')->first();
                    $siteName->update([
                        'value' => $request->site_name
                    ]);

                    Session::flash('success', 'Connection to database successfully connected.');
                    return redirect()->route('installation.stepTwo');
                }else{
                    Session::flash('error', 'Could not connect to database. Please check configurations, check spelling and these are case sensitive.');
                    return redirect()->back();
                }
            }else{
                Session::flash('error', 'No changes in configuration');
                return redirect()->back();
            }
        }

        return view('installation.stepOne');
    }

    public function stepTwo(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'mail_host' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
            ]);

            $response = $this->validateSMTP($request);

            if($response->getData()->success){
                $data = [
                    'MAIL_HOST'   => $request->mail_host,
                    'MAIL_USERNAME'   => $request->mail_username,
                    'MAIL_PASSWORD'   => $request->mail_password,
                    'MAIL_FROM_ADDRESS'   => $request->mail_username,
                ];
                
                if($this->changeEnvConfiguration($data)){
                    Session::flash('success', 'Mail configurations successfully saved');
                    return redirect()->route('installation.stepTwoPointOne');
                }else{
                    Session::flash('error', 'No changes in configuration');
                    return redirect()->back()->withInput();
                }
            }else{
                Session::flash('error', $response->getData()->message);
                return redirect()->back()->withInput();
            }
        }

        return view('installation.stepTwo');
    }

    public function stepTwoPointOne(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'jvzoo_key' => 'required',
                'youtube_api_key' => 'required',
                'captcha_secret' => 'required',
                'captcha_site_key' => 'required'
            ]);
            
            if(!$this->validateYouTube($request->youtube_api_key)){
                Session::flash('error', 'Invalid YouTube Api Key.');
                return redirect()->back()->withInput();
            }

            $data = [
                'JVZOO_KEY' => $request->jvzoo_key,
                'YOUTUBE_API_KEY' => $request->youtube_api_key,
                'NOCAPTCHA_SECRET' => $request->captcha_secret,
                'NOCAPTCHA_SITEKEY' => $request->captcha_site_key
            ];
    
            if($this->changeEnvConfiguration($data)){
                $setup = Setup::where('key', 'jvzipn')->first();
                $setup->update([
                    'value' => $request->jvzoo_key
                ]);
    
                Session::flash('success', 'Configurations successfully saved');
                return redirect()->route('installation.stepTwoPointTwo');
            }else{
                Session::flash('error', 'No changes in configuration');
                return redirect()->back()->withInput();
            }
        }

        return view('installation.stepTwoPointOne');
    }

    public function stepTwoPointTwo(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'facebook_app_id' => 'required',
                'facebook_app_secret' => 'required',
                'twitter_consumer_key' => 'required',
                'twitter_consumer_secret' => 'required',
                'twitter_access_token' => 'required',
                'twitter_access_token_secret' => 'required'
            ]);
    
            if(!$this->validateTwitter($request)){
                Session::flash('error', 'Invalid TWitter Api Keys.');
                return redirect()->back()->withInput();
            }

            $data = [
                'FACEBOOK_APP_ID' => $request->facebook_app_id,
                'FACEBOOK_APP_SECRET' => $request->facebook_app_secret,
                'YOUTUBE_API_KEY' => $request->twitter_consumer_key,
                'TWITTER_CONSUMER_KEY' => $request->twitter_consumer_secret,
                'TWITTER_ACCESS_TOKEN' => $request->twitter_access_token,
                'TWITTER_ACCESS_TOKEN_SECRET' => $request->twitter_access_token_secret
            ];
    
            if($this->changeEnvConfiguration($data)){
                Session::flash('success', 'Facebook and Twitter Configurations successfully saved.');
                return redirect()->route('installation.stepThree');
            }else{
                Session::flash('error', 'No changes in configuration');
                return redirect()->back()->withInput();
            }
        }

        return view('installation.stepTwoPointTwo');
    }

    public function stepThree(Request $request){
        $admin = User::where('role_id', 1)->first();

        if(!isset($admin)){
            if($request->isMethod('POST')){
                $this->validate($request, [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:users',
                    'password' => 'required',
                    'confirm_password' => 'required',
                ]);
    
                if($request->password == $request->confirm_password){
                    $user = User::create([
                        'name' => $request->first_name .' '. $request->last_name, 
                        'email' => $request->email, 
                        'password' => Hash::make($request->password), 
                        'role_id' => 1, 
                        'active' => 1
                    ]);
    
                    UserDetail::create([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                    ]);
    
                    Session::flash('success', 'Administrator user successfully created. You may now login.');
                    return redirect()->route('admin.login');
                }else{
                    Session::flash('error', 'Password and confirm password did not match');
                    return redirect()->back()->withInput();
                }
            }
    
            return view('installation.stepThree');
        }else{
            Session::flash('login_error', 'Administrator account already created. Please login the credentials. Thank you');
            return redirect()->route('admin.login');
        }
    }

    public function validateSMTP($request){
        $success = false;

        try{
            $transport = new \Swift_SmtpTransport($request->mail_host, '465', 'ssl');
            $transport->setUsername($request->mail_username);
            $transport->setPassword($request->mail_password);

            // Assign a new SmtpTransport to SwiftMailer
            $mail = new \Swift_Mailer($transport);

            // Assign it to the Laravel Mailer
            Mail::setSwiftMailer($mail);
            // Send your message
            Mail::send([], [], function ($message) use ($request) {
                $message->from(trim($request->mail_username));
                $message->to($request->mail_username);
                $message->subject('Test');
                $message->setBody('<h2>This is a test email to validate your SMTP settings. Thank you</h2>', 'text/html');
            });

            $success = true;
            $msg = 'Valid SMTP Settings';
        }catch(\Exception $e){
            $msg = $e->getMessage();
        }

        return response()->json(['success' => $success, 'message' => $msg]);
    }

    public function changeEnvConfiguration($data){
        // 'DB_DATABASE'   => 'instante_temp_db',
        // 'DB_USERNAME'   => 'instante_tmpuser',
        // 'DB_PASSWORD'   => 'qweqwe123',
        // 'DB_PASSWORD'   => '%A6(FLQhwtUo',
        
        if(count($data) > 0){
            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');
            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);

            // Loop through given data
            foreach((array)$data as $key => $value){

                // Loop through .env-data
                foreach($env as $env_key => $env_value){

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);
            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);
            
            return true;
        } else {
            return false;
        }
    }

    public function testConnection($request){
        try {
            DB::connection()->getPdo();
            // var_dump('connected to ' . DB::connection()->getDatabaseName());
            if(DB::connection()->getDatabaseName() == $request->database_name){
                return true;
            }else{
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function migrateAndSeed(){

        $colname = 'Tables_in_' . env('DB_DATABASE');

        $tables = DB::select('SHOW TABLES');

        foreach($tables as $table) {

            $droplist[] = $table->$colname;

        }

        if(isset($droplist)){
            $droplist = implode(',', $droplist);

            DB::beginTransaction();
            //turn off referential integrity
            //DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement("DROP TABLE $droplist");
            //turn referential integrity back on
            //DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::commit();
        }

        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    public function validateYouTube($key){
        try{
            Youtube::setApiKey($key);

            $videoList = Youtube::searchVideos('Android');

            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    public function validateTwitter($request){
        try{
            $params = [ 'q' => 'android' ];
            Twitter::reconfig([
                'consumer_key' => $request->twitter_consumer_key, 
                'consumer_secret' => $request->twitter_consumer_secret, 
                'token' => $request->twitter_access_token, 
                'secret' => $request->twitter_access_token_secret
            ]);

            $results = Twitter::getSearch($params);

            return true;
        }catch(\Exception $e){
            return false;
        }
    }
}
