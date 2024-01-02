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

class PenaltyEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM'))
        ->subject('Penalty Details')
        ->view('email.penalty')->with([
            'data' => $this->data,
        ]);    
    }
}
