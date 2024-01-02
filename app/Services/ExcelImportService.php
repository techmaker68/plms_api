<?php
 
namespace App\Services;
 
use App\Contracts\ExcelImportRepositoryContract;
use App\Contracts\ExcelImportServiceContract;
use App\Jobs\ExcelImportJob;
use Maatwebsite\Excel\Facades\Excel;
 
class ExcelImportService implements ExcelImportServiceContract
{
 
    protected $repository;
 
    public function __construct(ExcelImportRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
 
    public function downloadExcel($data)
    {
        $type = $data['type'];
        $filePath = public_path($type . '.xlsx');
        if (file_exists($filePath)) {
            $url = url('/') . '/' . $type . '.xlsx';
            return response()->json($url, 200);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
 
    public function importView($data)
    {
        $data = (object) $data;
        $file = $data->file;
        $check = '';
        $required_columns = [];
        $requiredData =  $this->filterRequiredColumns($data->type);
        $required_columns = $requiredData['required_columns'];
        $check = $requiredData['check'];
        if ($data->type == 'pax') {
            $header = Excel::toArray([], $file)[0][1];
        } else {
            $header = Excel::toArray([], $file)[0][0];
        }
        $header_lower = array_map('strtolower', $header);
        $missing_columns = array_diff($required_columns, $header_lower);
        if (!empty($missing_columns)) {
            return response()->json(['error' => 'Following columns are missing in file: ' . implode(', ', $missing_columns)], 400);
        }
        $rows = Excel::toArray([], $file);
        $rowsData = $rows[0];
        $index = 1;
        if ($data->type == 'pax') {
            $rowsData = array_slice($rowsData, 3);
            $index = 4;
        }
        if ($data->type == 'onetimepassport') {
            $rowsData = array_slice($rowsData, 2);
            $index = 3;
        }
        // Check if any row has missing rowsData
        foreach ($rowsData as $key => $value) {
            $row = array_combine($header_lower, $value);
            // Check if all columns are empty  
            if (!$check) {
                unset($rowsData[$key]); // remove the row from $rowsData
                continue; // skip this row
            }
            // Check if any required column is missing
            $missing_columns = [];
            foreach ($required_columns as $column) {
                if (empty($row[$column])) {
                    $missing_columns[] = $column;
                }
            }
            if (!empty($missing_columns)) {
                $error_message = 'Row ' . ($key + $index) . ' has empty ' . implode(', ', $missing_columns);
                return response()->json(['error' => $error_message], 400);
            }
            $duplicatedata = $this->checkDuplicates($data->type, $value, $key, $data->batch_no ?? '');
            if ($duplicatedata) {
                return response()->json(['error' => $duplicatedata], 400);
            }
        }
        $file = $data->file->store('files');
        ExcelImportJob::dispatch($file, $data->type, $data->batch_no ?? '');
        return response()->json(['message' => 'data uploaded successfully'], 200);
    }
 
 
    private function filterRequiredColumns($type)
    {
        if ($type == 'pax') {
            $required_columns = ['full name english', 'full name arabic', 'full name as in passport', 'passport no'];
            $check =  empty($row['full name english']) && empty($row['full name arabic']) && empty($row['full name as in passport']) && empty($row['passport no']);
        } else if ($type == 'passport') {
            $required_columns = ['badge no(14262)', 'full name as in passport (for loi use)', 'passport no (ad5170111)', 'passport type (personal/official/diplomatic)', 'date of issue (2023-09-14)', 'date of expiry (2023-09-14)', 'birthday (2023-09-14)', 'place of issue (pakistan/india/afghanistan)', 'nationality (pakistan/india/afghanistan)'];
            $check =  empty($row['badge no(14262)']) && empty($row['full name as in passport (for loi use)']) && empty($row['passport no (ad5170111)']) && empty($row['passport type (personal/official/diplomatic)'])  && empty($row['date of issue (2023-09-14)']) && empty($row['date of expiry (2023-09-14)']) && empty($row['birthday (2023-09-14)']) && empty($row['place of issue (pakistan/india/afghanistan)']) &&  empty($row['nationality (pakistan/india/afghanistan)']);
        } else if ($type == 'visa') {
            $required_columns = ['badge no (14262)',  'date of issue (2023-09-14 )', 'date of expiry (2023-09-14)', 'visa number (2019516)'];
            $check =  empty($row['badge no (14262)'])  && empty($row['type (visitor/12 months/6 months/3 months)']) && empty($row['date of issue (2023-09-14)'])  && empty($row['date of expiry (2023-09-14)']) && empty($row['visa number (2019516)']);
        } else if ($type == 'loi') {
            $required_columns = ['badge no', 'batch no', 'loi payment date (2023-09-14)', 'loi invoice no', 'loi deposit amount',  'approval status (approved/rejected/cancelled/giveup)'];
            $check =  empty($row['badge no']) && empty($row['batch no']) && empty($row['loi payment date (2023-09-14)']) && empty($row['loi invoice no'])  && empty($row['loi deposit amount']) && empty($row['approval status (approved/rejected/cancelled/giveup)']);
        } else if ($type == 'bloodapplicants') {
            $required_columns = ['badge no', 'batch no', 'appoint time (07:30:00)', 'appoint date (2020-01-01)', 'blood test type (hiv+hbs+m / hiv)',];
            $check =  empty($row['badge no']) && empty($row['batch no']) && empty($row['appoint time (07:30:00)']) && empty($row['appoint date (2020-01-01)'])  && empty($row['blood test type (hiv+hbs+m / hiv)']);
        } else if ($type == 'onetimepassport') {
            $required_columns = ['name as per passport', 'passport no.', 'nationality', 'email'];
            $check =  empty($row['name as per passport']) && empty($row['passport no.']) && empty($row['email']);
        }
        return [
            'required_columns' =>  $required_columns,
            'check' =>  $check,
        ];
    }
 
 
    private function checkDuplicates($type, $value, $key, $batch_no)
    {
        $error_message = null;
        if ($type == 'pax') {
            if ($value[0]  && (isset($value[0]) && !empty($value[0]))) {
                $badge_exists = $this->repository->findByBadgeNo($value[0]);
                $passport_exists = $this->repository->findByPassportNo($value[17]);
                if ($badge_exists) {
                    $error_message = 'Badge no ' . $value[0] . ' already exists. Please delete this row and upload again.';
                } else if ($passport_exists) {
                    $error_message = 'Passport no ' . $value[17] . ' already exists. Please delete this row and upload again.';
                }
            }
        }
        if ($key != 0) {
            if ($type == 'passport' || $type == 'visa' || $type == 'loi' || $type == 'bloodapplicants') {
                $badge_exists = $this->repository->findByBadgeNo($value[0]);
                if (!$badge_exists) {
                    $error_message = 'Badge number ' . $value[0] . ' does not exists / wrong badge number. Please fix this and upload again.';
                }
            }
            if ($type == 'visa') {
                $visa_no = $this->repository->findByVisaNo($value[4]);
                if ($visa_no) {
                    $error_message = 'Visa no ' . $value[0] . ' already exists. Please fix this and upload again.';
                }
            }
            if ($type == 'passport') {
                $passport = $this->repository->findByPassportNo($value[2]);
 
                if ($passport) {
                    $error_message = 'Passport no ' . $value[0] . ' already exists. Please fix this and upload again.';
                }
            }
            if ($type == 'loi') {
                $pax = $this->repository->findByBadgeNo($value[0]);
                $batch = $this->repository->findByBatchNo($value[1]);
                if ($batch) {
                    $loi = $this->repository->findByBatchAndPax($value[1], $pax->pax_id);
                    if ($loi) {
                        $error_message = 'Pax with badge number ' . $value[0] . ' already exist in this batch. Please fix and upload again.';
                    }
                } else {
                    $error_message = 'Batch no ' . $value[1] . ' does not exist. Please fix and upload again.';
                }
            }
            if ($type == 'bloodapplicants') {
                $pax = $this->repository->findByBadgeNo($value[0]);
                if ($pax) {
                    $applicant =  $this->repository->findByBatchAndPax($value[1], $pax->pax_id);
                    if ($applicant) {
                        $error_message = 'Pax with badge number ' . $value[0] . ' already exist in this batch. Please fix and upload again.';
                    }
                }
            }
        }
        if ($type == 'onetimepassport') {
            $email = $value[3];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // $email is not a valid email address.
                $error_message = 'Applicant with passport number ' . $value[1] . ' in batch ' . $batch_no . ' has in-valid email.';
            }
            $applicant =  $this->repository->findBypassportNoAndBatchNo($value[1], $batch_no);
            if ($applicant) {
                $error_message = 'Applicant with passport number ' . $value[1] . ' in batch ' . $batch_no . ' already exists. Please fix and upload again.';
            }
        }
        return  $error_message;
    }
}