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

class ApplicantsMail extends Mailable
{
    use Queueable, SerializesModels;
    public $batch;
    public $applicants;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($batch, $applicants)
    {
        $this->batch = $batch;
        $this->applicants = $applicants;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM'))
        ->subject('Blood Test on'.' '.$this->batch->test_date)
        ->view('email.applicants-email')->with([
            'batch' => $this->batch,
            'applicants' => $this->applicants
        ])->attach(public_path('/email/attachments/Do’s-and-Don’ts.pdf'))
        ->attach(public_path('/email/attachments/J-Block-Clinic.pdf'))
        ->attach(public_path('/email/attachments/Wearing_Mask.pdf'));  
    }
}
