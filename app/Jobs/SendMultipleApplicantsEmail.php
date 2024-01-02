<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ApplicantsMail;
use Illuminate\Support\Facades\Mail;


class SendMultipleApplicantsEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $batch;
    protected $batch_applicants;
    protected $data;
    protected $admin_emails;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $batch , $batch_applicants , $admin_emails)
    {
        $this->email = $email;
        $this->batch = $batch;
        $this->admin_emails = $admin_emails;
        $this->batch_applicants = $batch_applicants;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $emails = array_map('trim', explode(',', $this->admin_emails));
        Mail::to($this->email)->cc($emails)->send(new ApplicantsMail($this->batch,  $this->batch_applicants));
    }
}
