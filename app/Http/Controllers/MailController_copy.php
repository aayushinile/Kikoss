<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
class MailController extends Controller
{
    public function send_otp()
    {
        $info = array(
            'name' => "Alex"
        );
        $mailData = [
            //'body' => 'You are receiving this email because you have registered on our NJMEP platform for submitting the Questionnaire',
            //'otp'  => 456203,
            'name'  => 'Nitesh'
        ];
        Mail::to('niteshkoy@gmail.com')->send(new RegisterMail($mailData));
        // Mail::send(['view' => 'admin.Register-mail'], $info, function ($message)
        // {
        //     $message->to('niteshkoy@gmail.com', 'W3SCHOOLS')
        //         ->subject('Basic test eMail from W3schools.');
        //     $message->from('kikosoahu@gmail.com', 'Alex');
        // });
        echo "Successfully sent the email";
    }

    public function html_mail()
    {
        $info = array(
            'name' => "Alex"
        );
        Mail::send('mail', $info, function ($message)
        {
            $message->to('alex@example.com', 'w3schools')
                ->subject('HTML test eMail from W3schools.');
            $message->from('karlosray@gmail.com', 'Alex');
        });
        echo "Successfully sent the email";
    }

    public function attached_mail()
    {
        $info = array(
            'name' => "Alex"
        );
        Mail::send('mail', $info, function ($message)
        {
            $message->to('alex@example.com', 'w3schools')
                ->subject('Test eMail with an attachment from W3schools.');
            $message->attach('D:\laravel_main\laravel\public\uploads\pic.jpg');
            $message->attach('D:\laravel_main\laravel\public\uploads\message_mail.txt');
            $message->from('karlosray@gmail.com', 'Alex');
        });
        echo "Successfully sent the email";
    }
}