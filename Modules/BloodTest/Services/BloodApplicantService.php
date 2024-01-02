<?php

namespace Modules\BloodTest\Services;

use App\Mail\PenaltyEmail;
use Illuminate\Support\Arr;
use App\Mail\NewAppointment;
use App\Traits\SequenceHandler;
use App\Services\BaseService;
use App\Mail\SendApplicantsNote;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Modules\BloodTest\Contracts\BloodApplicantServiceContract;
use Modules\BloodTest\Contracts\BloodApplicantRepositoryContract;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class BloodApplicantService extends BaseService implements BloodApplicantServiceContract
{
    use SequenceHandler;

    protected $adminEmail;
    /**
     * BloodTestService constructor.
     *
     * @param BloodApplicantRepositoryContract $repository
     */
    public function __construct(BloodApplicantRepositoryContract $repository)
    {
        parent::__construct($repository);
        $this->adminEmail = config('mail.blood_admin');
    }
    public function store(array $data): Model
    {
        return $this->repository->store($data);
    }

    public function getMaxSequenceNoForBatch(int $batch_no): int
    {
        $maxSequenceNo = $this->repository->getMaxSequenceNoForBatch($batch_no);
        return intval($maxSequenceNo + 1);
    }

    public function updateApplicantsInBulk($data)
    {
        unset($data['sort_by'], $data['order'], $data['method']);
    
        $idsArray = $data['applicant_ids'];
        $applicants = $this->repository->getApplicantsByIds($idsArray, ['pax', 'blood_test']);

        if ($applicants->isEmpty()) {
            return collect();
        }

        $taskPurposes = $data['task_purposes'] ?? [];
    
        $batch = $applicants->first()->blood_test;
    
        foreach ($applicants as $applicant) {
            if ($this->shouldUpdatePaxStatus($taskPurposes, $applicant->pax)) {
                $this->repository->setPaxStatus($applicant->pax, 2);
            }
    
            $updatedData = $this->prepareUpdatedData($applicant, $data);
            if ($this->hasChanges($applicant, $updatedData)) {
                $this->repository->updateApplicantsInBulk($applicant->id, $updatedData);
            }
        }
    
        if ($applicants->isNotEmpty()) {
            $batch->processBatch($batch->batch_no);
        }
    
        return $this->repository->getApplicantsByIds($idsArray);
    }    
    
    private function shouldUpdatePaxStatus($taskPurposes, $pax) {
        return $pax && 
               isset($taskPurposes['visa']) &&
               $taskPurposes['visa']['status'] === true &&
               $taskPurposes['visa']['values'] === 'Offboard';
    }
    
    private function prepareUpdatedData($applicant, $data) {
        $updatedData = [];
    
        $fieldsToUpdate = [
            'blood_test_types', 
            'arrival_date', 
            'departure_date', 
            'remarks', 
            'attendance', 
            'task_purposes', 
            'appoint_time', 
            'appoint_date', 
            'hiv_expire_date', 
            'hbs_expire_date', 
            'passport_submit_date', 
            'passport_return_date'
        ];
    
        foreach ($fieldsToUpdate as $field) {
            $updatedData[$field] = $data[$field] ?? $applicant->$field;
        }
    
        return $updatedData;
    }    
    
    private function hasChanges($applicant, $updatedData) {
        foreach ($updatedData as $key => $value) {
            if ($applicant->$key != $value) {
                return true;
            }
        }
        return false;
    }

    public function rescheduleApplicant($data)
    {
        $idsArray = Arr::wrap($data['id']);
    
        $applicants = $this->repository->getApplicantsByIds($idsArray);
        $batch_no = $this->repository->getBatchNoFromApplicants($idsArray);
        $batch = $this->repository->getBatchByNo($batch_no);
        $sequence_no = $this->getMaxSequenceNoForBatch($batch_no);
    
        $rescheduleData = [
            'applicants' => $applicants,
            'batch' => $batch,
            'sequence_no' => $sequence_no,
        ];
    
        return $this->repository->rescheduleApplicant($rescheduleData);
    }    

    public function renewAppointment($id, $data)
    {
        $applicant = $this->repository->getApplicantsByIds($id, ['pax'])->first();
    
        if ($applicant) {
            $this->updateAppointmentData($applicant, $data);
            $email = $this->getEmailFromApplicant($applicant);
            $this->sendAppointmentEmail($email, $data);
        }
    
        return ['message' => 'New appointment successfully updated'];
    }
    
    private function updateAppointmentData($applicant, $data)
    {
        $dateKey = 'new_appoint_date';
        $remarksKey = 'new_remarks';
        $applicant->$dateKey = $data[$dateKey] ?? $applicant->$dateKey;
        $applicant->$remarksKey = $data[$remarksKey] ?? $applicant->$remarksKey;
        $this->repository->saveApplicant($applicant);
    }
    
    private function getEmailFromApplicant($applicant)
    {
        $pax = $applicant->pax ?? null;
        return $pax ? $pax->email : ($applicant->email ?? null);
    }
    
    private function sendAppointmentEmail($email, $data)
    {
        if ($email) {
            $emailData = [
                'new_appoint_date' => $data['new_appoint_date'] ?? '',
                'new_remarks' => $data['new_remarks'] ?? '',
            ];
            Mail::to($email)->cc($this->adminEmail)->send(new NewAppointment($emailData));
        }
    }    

    public function addPenalty($id, $data)
    {
        $applicant = $this->repository->getApplicantsByIds($id)->first();
        if (!$applicant) {
            return ['message' => 'Applicant not found.'];
        }
    
        $type = $data['type'] ?? null;
        if ($type === 'blood' || $type === 'visa') {
            $this->updatePenaltyData($applicant, $data, $type);
            $this->sendPenaltyEmail($applicant, $type);
            return ['message' => 'Penalty Added Successfully'];
        } else {
            return ['message' => 'Invalid penalty type.'];
        }
    }
    
    private function updatePenaltyData($applicant, $data, $type)
    {
        $feeKey = $type === 'blood' ? 'penalty_fee' : 'visa_penalty_fee';
        $remarksKey = $type === 'blood' ? 'penalty_remarks' : 'visa_penalty_remarks';
        $applicant->$feeKey = $data['penalty_fee'] ?? $applicant->$feeKey;
        $applicant->$remarksKey = $data['penalty_remarks'] ?? $applicant->$remarksKey;
        $this->repository->saveApplicant($applicant);
    }
    
    private function sendPenaltyEmail($applicant, $type)
    {
        $pax = $applicant->pax ?? null;
        $email = $pax ? $pax->email : ($applicant->email ?? null);
        if ($email) {
            $emailData = [
                'penalty_fee' => $applicant->penalty_fee ?? '',
                'penalty_remarks' => $applicant->penalty_remarks ?? '',
                'visa_penalty_fee' => $applicant->visa_penalty_fee ?? '',
                'visa_penalty_remarks' => $applicant->visa_penalty_remarks ?? '',
            ];
            Mail::to($email)->cc($this->adminEmail)->send(new PenaltyEmail($emailData));
        }
    }
    
    public function sendApplicantsNote($data)
    {
        $ids = $data['ids'];
        $applicants = $this->repository->getApplicantsByIds($ids, ['pax']);
        $noEmailApplicants = [];
    
        if ($applicants->isEmpty()) {
            return 'No applicants found.';
        }
    
        foreach ($applicants as $applicant) {
            $pax = $applicant->pax ?? null;
            $email = $pax ? $pax->email : $applicant->email;
            $applicantName = $applicant->full_name ?? ($pax ? $pax->eng_full_name : null);
            if ($email) {
                $notes = $data['notes'];
                if(is_array($data['notes'])){
                    $notes = implode(',', $data['notes']);
                }
                $this->sendNoteEmail($email, $notes);
            } else {
                $noEmailApplicants[] = $applicantName;
            }
        }
    
        if (!empty($noEmailApplicants)) {
            return 'The email was sent to those with email addresses. Some applicants do not have valid emails: ' . implode(', ', $noEmailApplicants);
        }
    
        return 'Email Sent Successfully';
    }
    
    private function sendNoteEmail($email, $notes)
    {
        Mail::to($email)->cc($this->adminEmail)->send(new SendApplicantsNote($notes));
    }    
    public function getPaxDetail($id)
    {
        return  $this->repository->getPaxDetail($id);
    }

    public function getSubmissionInformation($batch_no)
    {
        return  $this->repository->submissionInformation($batch_no);
    }

    public function removeApplicants($data)
    {
        $ids = explode(',', $data['ids']);

        $batch_no = $this->repository->getBatchNoFromApplicants($ids);

        $this->repository->removeApplicants($ids);

        $blood_test = $this->repository->getBatchByNo($batch_no);

        $this->fixApplicantSequence($blood_test->blood_applicant);
    }

    public function getBloodTestHistory($bloodTest)
    {
        return  $this->repository->getLastBloodTest($bloodTest);
    }
    public function generateDoc($bloodApplicants, $submission_information)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $header = $section->addHeader();
        $header->addImage(
            public_path('assets/img/logo.png'),
            array(
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            )
        );

        $headerLink = $section->addTextRun(['alignment' => 'left']);
        $headerLink->addText('www.antonil.com', ['color' => '1F4E78']);
        $headerLink->addTextBreak();
        $headerLink->addText('_________________________________________________________________________________', ['color' => '1F4E78']);

        $paragraph1 = $section->addTextRun(['alignment' => 'right']);
        $paragraph1->addText('العدد ', array('rtl' => true, 'size' => 12));
        $paragraph1->addText(' :', array('rtl' => true, 'size' => 12));
        $paragraph1->addText($submission_information['batch_no'], array('rtl' => true, 'size' => 12));

        $paragraph2 = $section->addTextRun(['alignment' => 'right']);
        $paragraph2->addText('التاريخ ', array('rtl' => true, 'size' => 12));
        $paragraph2->addText(' :', array('rtl' => true, 'size' => 12));
        $paragraph2->addText($submission_information['submit_date'], array('rtl' => true, 'size' => 12));

        $paragraph3 = $section->addTextRun(['alignment' => 'center']);
        $paragraph3->addText('الى: دائرة صحة البصرة', array('rtl' => true, 'size' => 12));

        $paragraph4 = $section->addTextRun(['alignment' => 'center']);
        $paragraph4->addText('قسم الصحة العامة', array('rtl' => true, 'size' => 12));

        $paragraph5 = $section->addTextRun(['alignment' => 'center']);
        $paragraph5->addText('شعبة السيطرة على العوز المناعي', array('rtl' => true, 'size' => 12));

        $paragraph6 = $section->addTextRun(['alignment' => 'center']);
        $paragraph6->addText('م/ تأكيد', array('rtl' => true, 'size' => 12));

        $paragraph7 = $section->addTextRun(['alignment' => 'center']);
        $paragraph7->addText('نحن شركة انطونويل نؤيد لكم صحة اسماء و ارقام جوازاتهم المدرجة ادناه لموضفينا علما هم يعملون لدى شركتنا', array('rtl' => true, 'size' => 12));

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell(2000, ['shading' => ['fill' => 'A9A9A9']])->addText('#', array('bold' => true));
        $table->addCell(2000, ['shading' => ['fill' => 'A9A9A9']])->addText('Name in passport', array('bold' => true));
        $table->addCell(2000, ['shading' => ['fill' => 'A9A9A9']])->addText('Badge', array('bold' => true));
        $table->addCell(2000, ['shading' => ['fill' => 'A9A9A9']])->addText('Nationality', array('bold' => true));

        foreach ($bloodApplicants as $key => $applicant) {
            if ($applicant->pax) {
                $engFullName = $applicant->pax->eng_full_name ?? '';
                $passportNo = $applicant->pax->latestPassport->passport_no ?? '';
                $countryNameShortEn = $applicant->pax->countryResidence->country_name_short_en ?? '';
                $table->addRow();
                $table->addCell()->addText($key + 1);
                $table->addCell()->addText($engFullName);
                $table->addCell()->addText($passportNo);
                $table->addCell()->addText($countryNameShortEn);
            }
        }

        $footerParagraph = $section->addTextRun(['alignment' => 'center']);
        $footerParagraph->addText('مع الشكر و التقدير..... ', array('rtl' => true, 'size' => 12));
        $footerSIgnature = $section->addTextRun(['alignment' => 'left']);
        $footerSIgnature->addText('قسم لوجستيات الافراد', array('rtl' => true, 'size' => 12));

        $filename = 'ApplicantList.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filename);
        return response()->download($filename)->deleteFileAfterSend(true);
    }
    public function generatePDF($filteredApplicants, $submission_information)
    {
        $htmlContent = view('blood-test', ['bloodApplicants' => $filteredApplicants, 'submission_info' => $submission_information])->render();
        $mpdf = new \Mpdf\Mpdf([
            'orientation' =>  'L',
        ]);
        $mpdf->imageVars['logo'] = file_get_contents(public_path('/assets/img/logo.png'));
        $mpdf->WriteHTML($htmlContent);
        $pdfContent = $mpdf->Output('', 'S');
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="blood_test.pdf"');
    }
}
