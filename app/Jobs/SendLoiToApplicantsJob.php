<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\LoiToApplicantsMail;
use Illuminate\Support\Facades\Mail;

class SendLoiToApplicantsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recipients;
    public $subject;
    public $content;
    public $files;

    public function __construct($recipients, $subject, $content, $files)
    {
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->content = $content;
        $this->files = $files;
    }

    public function handle()
    {
        // Use the Mail facade to send the email
        Mail::to($this->recipients['to'])
            ->bcc($this->recipients['bcc'])
            ->cc($this->recipients['cc'])
            ->send(new LoiToApplicantsMail($this->subject, $this->content, $this->files));
    }
}
