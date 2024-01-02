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

class SendApplicantsNote extends Mailable
{
    use Queueable, SerializesModels;
    public $notes;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM'))
        ->subject('Blood Test Report')
        ->view('email.report-email')->with([
            'notes' => $this->notes,
        ]);       
    }
}
