<?php
// ***********************************
// @author Syed, Umair, Aqsa
// @create_date 21-07-2023
// ***********************************
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoiToApplicantsMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $content;
    public $files;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $files)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $mail = $this->from(env('MAIL_FROM'))
        ->subject($this->subject)
        ->view('email.loi-to-applicants')
        ->with(['content' => $this->content]);

        foreach ($this->files as $file) {
            $mail->attach(storage_path('app/public/' . $file));
        }
        return $mail;
        
    }
}
