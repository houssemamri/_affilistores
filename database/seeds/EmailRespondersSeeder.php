<?php

use Illuminate\Database\Seeder;
use App\EmailResponder;
class EmailRespondersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emailResponders = [
            (object)[
                'name' => 'Member Sign Up', 
                'description' => 'This message is sent to Member at Sign Up.',
                'from' => 'from@instantecomlab.com', 
                'reply' => 'reply@instantecomlab.com', 
                'to' => '%EMAIL%', 
                'subject' => '%SITENAME% Sign Up', 
                'body' => '<p>Hello %FNAME%,</p><p>Thanks for your Sign Up with %SITENAME%</p><p>Below is your Login Info.</p><p>E-mail Address: %EMAIL%</p><p>Password: %PASS%</p><p> </p><p>Once again, thanks for subscribing.</p><p> </p><p>--- Rod Beckwith %SITENAME% 13130 Hwy 9 Box 8008 Boulder Creek, CA 95006</p>'
            ],
            (object)[
                'name' => 'Admin on Member Sign Up', 
                'description' => 'This message is sent to Admin at Member Sign Up.',
                'from' => 'from@instantecomlab.com', 
                'reply' => 'reply@instantecomlab.com', 
                'to' => 'admin@instantecomlab.com', 
                'subject' => '%SITENAME% Sign Up',
                'body' => '<p>Hello,</p><p>A Member has just signed up with %SITENAME%</p><p>Below is their Personal Info.</p><p>First Name: %FNAME%</p><p>Last Name: %LNAME%</p><p>E-mail Address: %EMAIL%</p><p>Password: %PASS%</p><p> </p><p>--- Rod Beckwith %SITENAME%</p><p>13130 Hwy 9 Box 8008 Boulder Creek, CA 95006</p>'
            ],
            (object)[
                'name' => 'Autoresponder on Member Sign Up', 
                'description' => 'This message is sent to Autoresponder at Member Sign Up.',
                'from' => 'from@instantecomlab.com', 
                'reply' => 'reply@instantecomlab.com', 
                'to' => 'autoresponder@instantecomlab.com', 
                'subject' => 'Subscribe', 
                'body' => '<p>First Name: %FNAME%</p><p>Last Name: %LNAME%</p><p>E-mail Address: %EMAIL%</p>'
            ],
            (object)[
                'name' => 'Forgot Password Request', 
                'description' => 'This message is sent to Member at "Forgot Password" request.',
                'from' => 'from@instantecomlab.com', 
                'reply' => 'reply@instantecomlab.com', 
                'to' => '%EMAIL%', 
                'subject' => 'Forgot Password request for %SITENAME%', 
                'body' => '<p>Hello %FNAME%,</p><p>Your password for %SITENAME% has been changed at your request.</p><p>Below is your new Login Info.</p><p>E-mail Address: %EMAIL%</p><p>Password: %PASS%</p><p> </p><p>You can change your password at any time under Profile in Member at %SITEURL%</p><p>--- Rod Beckwith %SITENAME% 13130 Hwy 9 Box 8008 Boulder Creek, CA 95006</p>'
            ],
        ];

        foreach ($emailResponders as $emailResponder) {
            EmailResponder::create([
                'name' => $emailResponder->name, 
                'description' => $emailResponder->description,
                'from' => $emailResponder->from, 
                'reply' => $emailResponder->reply, 
                'to' => $emailResponder->to, 
                'subject' => $emailResponder->subject, 
                'body' => $emailResponder->body
            ]);
        }
    }
}
