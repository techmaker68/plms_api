<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ReminderEmailDaily;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSBloodTest;
use App\Models\PLMSSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class SendReminderEmailDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendreminderdaily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder email to users.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
            $current_batch = PLMSBloodTest::whereDate('test_date', Carbon::today())->first();
            if($current_batch){
                $batch_applicants = $current_batch->blood_applicant()->where('scheduled_status',1)->get();
                // $admin_emails = PLMSSetting::where('parameter', 'blood_test_email_admins')->value('value');
            // $dummy_emails = [
            //     "maqsatayyab99@gmail.com",
            //     "aqsa.ahsan15@gmail.com",
            //     "xidib84915@finghy.com",
            // ];
            // $applicants = [];
            
            // foreach ($dummy_emails as $email) {
            //     $applicants[] = (object) ['email' => $email];
            // }
                if($batch_applicants != null){
                    
                    foreach($batch_applicants as $applicant) {
                        $pax = $applicant->pax ?? null;
                        $email = $applicant->email != null ? $applicant->email :($pax->email != null ? $pax->email : null); 
                        if($email != null){
                            $url = 'https://pob.majnoon-ifms.com/BackEndApi/api/send_email';
                            $response = Http::get('https://pob.majnoon-ifms.com/BackEndApi/api/get_email_template', [
                                'template_id' => 1
                            ]);
                            $data = $response->json();

                            // Assuming you want to send some JSON data
                            $data = [
                                'subject' => $data['result']['list'][0]['subject'],
                                'body' => $data['result']['list'][0]['contents'],
                                'to_email' => $email,
                                'smtpguid' => '651BEB603640E142224',
                                'cc_email' => $admin_emails ?? '',
                            ];
                            $response = Http::post($url, $data);
                            // Mail::to($applicant->email)->send(new ReminderEmailDaily());
                        }
                        $this->info('Reminder emails sent successfully!');
                    }
                }

            }

    }
}
