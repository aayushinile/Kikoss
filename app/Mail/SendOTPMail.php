<?php
  
namespace App\Mail;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
  
class SendOTPMail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $mailData;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        //$mailData = $mailData;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailData = $this->mailData;
        return $this->subject('Mail from KIKOS')
                    ->view('admin.send-otp-mail',compact('mailData'));
    }
}