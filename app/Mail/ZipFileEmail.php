<?php
// ***********************************
// @author Syed, Umair, Aqsa
// @create_date 21-07-2023
// ***********************************
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ZipFileEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $batchNo;
    public $downloadLink;
    public $ccEmails;

    public function __construct($batchNo, $downloadLink, $ccEmails)
    {
        $this->batchNo = $batchNo;
        $this->downloadLink = $downloadLink;
        $this->ccEmails = $ccEmails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $batchNo = $this->batchNo;
        $downloadLink = $this->downloadLink;
        return $this->from(env('MAIL_FROM'))
            ->subject("$batchNo LOI Documents")
            ->cc($this->ccEmails)
            ->view('email.zip-email')
            ->with([
                'batchNo' => $batchNo,
                'downloadLink' => $downloadLink,
            ]);
    }
}
