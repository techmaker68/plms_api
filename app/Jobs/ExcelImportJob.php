<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Jobs;

use App\Imports\ImportBloodApplicants;
use App\Imports\ImportLoi;
use App\Imports\ImportOneTimePassports;
use App\Imports\ImportPassport;
use App\Imports\ImportPax;
use App\Imports\ImportVisa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    protected $filePath;
    protected $type;
    protected $batch_no;
    public function __construct($filePath, $type, $batch_no = 0)
    {
        $this->filePath = $filePath;
        $this->type = $type;
        $this->batch_no = $batch_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type == 'pax') {
            Excel::import(new ImportPax, $this->filePath);
        } else if ($this->type == 'passport') {
            Excel::import(new ImportPassport, $this->filePath);
        } else if ($this->type == 'visa') {
            Excel::import(new ImportVisa, $this->filePath);
        } else if ($this->type == 'loi') {
            Excel::import(new ImportLoi, $this->filePath);
        } else if ($this->type == 'bloodapplicants') {
            Excel::import(new ImportBloodApplicants, $this->filePath);
        } else if ($this->type == 'onetimepassport') {
            Excel::import(new ImportOneTimePassports($this->batch_no), $this->filePath,);
        }
    }
}
