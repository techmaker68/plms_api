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

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $batch_no;
    public $applicants;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($batch_no, $applicants)
    {
        $this->batch_no = $batch_no;
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
        ->subject('Test Email')
        ->view('email.test-email')->with([
            'batch_no' => $this->batch_no,
            'applicants' => $this->applicants
        ]);    
    }
}
