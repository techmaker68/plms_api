<?php

namespace Modules\Loi\Services;

use App\Jobs\PdfEmailJob;
use Carbon\Carbon;
use App\Services\BaseService;
use App\Traits\SequenceHandler;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Jobs\SendLoiToApplicantsJob;
use App\Models\PLMSSetting;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Loi\Contracts\PLMSLoiServiceContract;
use Modules\Loi\Contracts\PLMSLoiRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Loi\Contracts\PLMSLoiApplicantRepositoryContract;
use Modules\Loi\Entities\PLMSLoi;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class PLMSLoiService extends BaseService implements PLMSLoiServiceContract
{
    use SequenceHandler;

    protected $loiApplicantRepository;
    private $phpWord;
    private $section;
    private $cellStyle;
    private $cellStyle2;
    private $fontStyle;
    private $paragraphStyle;
    /**
     * PLMSLoiService constructor.
     *
     * @param PLMSLoiRepositoryContract $repository
     * @param PLMSLoiApplicantRepositoryContract $loiApplicantRepository
     */
    public function __construct(PLMSLoiRepositoryContract $repository, PLMSLoiApplicantRepositoryContract $loiApplicantRepository)
    {
        parent::__construct($repository);
        $this->loiApplicantRepository = $loiApplicantRepository;
        $this->phpWord = new PhpWord();
        $this->section = $this->phpWord->addSection();
        $this->initializeStyles();
    }

    /**
     * Calculate and return a new Batch No.
     *
     * @return int
     */
    public function calculateBatchNo(): int
    {
        $maxBatchNo = $this->repository->getMaxBatchNo();
        return intval($maxBatchNo + 1);
    }

    public function getDefaultDataForLoiStorage(): array
    {
        return $this->repository->getDefaultDataForLoiStorage();
    }

    public function getMaxSequenceNo(): int
    {
        $maxSequenceNo = $this->loiApplicantRepository->getMaxSequenceNo();
        return intval($maxSequenceNo + 1);
    }

    public function getMaxSequenceNoForBatch(int $batchNo): int
    {
        $maxSequenceNo = $this->loiApplicantRepository->getMaxSequenceNoForBatch($batchNo);
        return intval($maxSequenceNo + 1);
    }

    public function destroyLoiWithRelations(int $id)
    {
        $loi = $this->show($id);
        return $this->repository->deleteLoiWithApplicants($loi);
    }

    public function loiApplicantall(array $data, ?array $withRelations = []): Collection
    {
        return $this->loiApplicantRepository->all($data, $withRelations);
    }

    public function loiApplicantindex(array $data, ?array $withRelations = []): LengthAwarePaginator
    {
        return $this->loiApplicantRepository->index($data, $withRelations);
    }

    public function loiApplicantStore(array $data): Model
    {
        return $this->loiApplicantRepository->store($data);
    }

    public function loiApplicantShow(int $id): array
    {
        $currentApplicant = $this->loiApplicantRepository->show($id);
        $currentApplicant = $this->loadApplicantRelationships($currentApplicant);
        $previousApplicant = $this->loiApplicantRepository->showPreviousLoi($currentApplicant);

        return ['current' => $currentApplicant, 'previous' => $previousApplicant];
    }

    public function loiApplicantUpdate(int $id, array $data): Model
    {
        return $this->loiApplicantRepository->update($id, $data);
    }

    public function loiApplicantDestroy(int $id): ?bool
    {
        return $this->loiApplicantRepository->destroy($id);
    }

    public function renewLOI(int $batchNo): Model
    {
        $oldLoi = $this->repository->getOldBatchRecord($batchNo);
        $newBatchNo = $this->calculateBatchNo();
        $newLOI = $this->repository->createNewBatchFromOld($oldLoi, $newBatchNo);
        $now = Carbon::now();

        $processedPaxIds = [];
        $applicantData = [];

        foreach ($oldLoi->applicants as $applicant) {
            if (!in_array($applicant->pax_id, $processedPaxIds)) {
                $processedPaxIds[] = $applicant->pax_id;
                $applicantData[] = [
                    'pax_id' => $applicant->pax_id,
                    'batch_no' => $newBatchNo,
                    'status' => $applicant->status,
                    'loi_payment_date' => $applicant->loi_payment_date,
                    'deposit_amount' => $applicant->deposit_amount,
                    'loi_payment_receipt_no' => $applicant->loi_payment_receipt_no,
                    'remarks' => $applicant->remarks,
                    'sequence_no' => $applicant->sequence_no,
                    'loi_photo_copy' => $applicant->loi_photo_copy,
                    'loi_issue_date' => $applicant->loi_issue_date,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        $this->loiApplicantRepository->insert($applicantData);

        return $newLOI->load('applicants');
    }

    public function saveLoiApplicantsBulk(array $data)
    {
        $batchNo = $data['batch_no'];
        $paxIds = is_array($data['pax_ids']) ? $data['pax_ids'] : explode(',', $data['pax_ids']);

        $paddedPaxIds = array_map(function ($paxId) {
            return str_pad($paxId, 6, '0', STR_PAD_LEFT);
        }, $paxIds);

        $existingPaxIds = $this->loiApplicantRepository->getPaxIdsByBatchNo($batchNo);
        $newPaxIds = array_diff($paddedPaxIds, $existingPaxIds);

        if (empty($newPaxIds)) {
            return $this->loiApplicantRepository->getApplicantsByBatchNo($batchNo);
        }

        $maxSequenceNo = $this->getMaxSequenceNoForBatch($batchNo);
        $now = Carbon::now();

        $bulkInsertions = collect($newPaxIds)->map(function ($paxId) use ($batchNo, &$maxSequenceNo, $now) {
            $lastApplicant =  $this->loiApplicantRepository->getLastApplicantByPaxId($paxId);
            return [
                'pax_id' => $paxId,
                'batch_no' => $batchNo,
                'sequence_no' => $maxSequenceNo++,
                'loi_payment_date' =>  $lastApplicant['loi_payment_date'],
                'deposit_amount' => $lastApplicant['deposit_amount'],
                'loi_payment_receipt_no' => $lastApplicant['loi_payment_receipt_no'],
                'payment_letter_copy' => $lastApplicant['payment_letter_copy'],
                'loi_photo_copy' => $lastApplicant['loi_photo_copy'],
                'loi_no' => $lastApplicant['loi_no'],
                'loi_issue_date' => $lastApplicant['loi_issue_date'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });

        $this->loiApplicantRepository->insert($bulkInsertions->toArray());
        return $this->loiApplicantRepository->getApplicantsByBatchNo($batchNo);
    }

    public function updateMultipleApplicants(array $data)
    {
        $ids = explode(',', $data['ids']);
        $applicants = $this->loiApplicantRepository->getApplicantsByIds($ids);

        if ($applicants->isEmpty()) {
            return ['message' => 'No records found for the provided IDs', 'status' => false];
        }

        $this->loiApplicantRepository->updateBulkApplicants($data, $applicants);

        return ['message' => 'LOI Applicants updated successfully', 'status' => true];
    }

    public function removeLoiApplicants($data)
    {
        $ids = explode(',', $data['ids']);
        $applicants = $this->loiApplicantRepository->getApplicantsByIds($ids);

        $batchNo = $this->loiApplicantRepository->getBatchNoFromApplicants($ids);
        $this->loiApplicantRepository->deleteApplicantFiles($applicants);

        $this->loiApplicantRepository->removeApplicants($ids);

        $loi = $this->repository->getLoiByBatchNo($batchNo);
        $this->fixApplicantSequence($loi->applicants);
    }

    public function deletePaymentLetterCopy($id, $index): array
    {
        $applicant = $this->loiApplicantRepository->show($id);
        $response = $this->loiApplicantRepository->deletePaymentLetter($applicant, $index);
        return $response;
    }

    public function sendLoiToApplicants($data, $attachments = [])
    {
        $attachmentPaths = $this->handleAttachments($attachments ?? []);

        unset($data['sort_by']);
        unset($data['order']);
        unset($data['method']);

        $recipients = $this->buildRecipientList($data);

        SendLoiToApplicantsJob::dispatch($recipients, $data['subject'], $data['content'], $attachmentPaths);

        $this->cleanupAttachmentFiles($attachmentPaths);

        return 'Email Sent Successfully.';
    }

    private function handleAttachments($attachments)
    {
        $attachmentPaths = [];

        if ($attachments instanceof UploadedFile) {
            $attachments = [$attachments];
        }

        foreach ($attachments as $attachment) {
            if ($attachment instanceof UploadedFile) {
                $path = $attachment->store('attachments', 'public');
                $attachmentPaths[] = $path;
            }
        }

        return $attachmentPaths;
    }

    private function buildRecipientList($data)
    {
        $recipients = [
            'to' => array_filter(array_map('trim', explode(',', $data['to'] ?? ''))),
            'bcc' => array_filter(array_map('trim', explode(',', $data['bcc'] ?? ''))),
            'cc' => array_filter(array_map('trim', explode(',', $data['cc'] ?? ''))),
        ];

        if (!empty($data['all_applicants'])) {
            $applicantEmails = $this->getApplicantEmails($data['batch_no']);
            $recipients['to'] = array_merge($recipients['to'], $applicantEmails);
        }

        if (!empty($data['department_managers'])) {
            $departmentManagersEmails = is_array($data['department_managers'])
                ? $data['department_managers']
                : array_filter(array_map('trim', explode(',', $data['department_managers'])));
            $recipients['cc'] = array_merge($recipients['cc'], $departmentManagersEmails);
        }

        if (!empty($data['loi_focal_points'])) {
            $loi_focal_points = is_array($data['loi_focal_points'])
                ? $data['loi_focal_points']
                : array_filter(array_map('trim', explode(',', $data['loi_focal_points'])));
            $recipients['cc'] = array_merge($recipients['cc'], $loi_focal_points);
        }

        $recipients = array_map(function ($emails) {
            return array_filter($emails, function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });
        }, $recipients);

        return $recipients;
    }

    private function cleanupAttachmentFiles($attachmentPaths)
    {
        foreach ($attachmentPaths as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function getApplicantEmails($batchNo)
    {
        $applicants = $this->loiApplicantRepository->getApplicantsByBatchNo($batchNo);

        $emails = $applicants->map(function ($applicant) {
            return trim($applicant->email);
        })->filter(function ($email) {
            return !empty($email);
        });

        return $emails->toArray();
    }


    public function deleteLoiFiles($id, $data)
    {
        $loi = $this->repository->show($id);

        $this->repository->deleteFilesAndUpdateRecord($loi, $data);
    }

    /**
     * Prepare Relations to be loaded
     *
     * @return array
     */
    public function prepareApplicantsPaxRelations(array &$filters): array
    {
        $baseRelations = [
            'pax', 'pax.company',
            'pax.latestPassport',
            'pax.countryResidence',
            'pax.country',
            'pax.department',
            'pax.latestLoi',
        ];

        return $baseRelations;
    }

    /**
     * Load necessary relationships for a loiApplicants.
     *
     * @param mixed $applicant The pax entity.
     * @return mixed The pax entity with loaded relationships.
     */
    protected function loadApplicantRelationships($applicant)
    {
        return $applicant->load([
            'pax',
            'pax.company',
            'pax.latestPassport',
            'pax.countryResidence',
            'pax.country',
            'pax.department',
            'pax.latestLoi',
            'pax.latestLoi',
        ]);
    }

    public function generateZipFile($file, $batch_no)
    {
        $nameListPath = $this->storeNameListFile($file, $batch_no);
        $adminEmails = $this->getAdminEmails();
        PdfEmailJob::dispatch($batch_no, $adminEmails, $nameListPath);
    }

    private function storeNameListFile($file, $batchNo)
    {
        $storagePath = 'temp_name_list';
        $customFileName = $batchNo . '-NameListLoi.docx';
        $storedFilePath = $file->storeAs($storagePath, $customFileName);

        return Storage::path($storedFilePath);
    }
    private function getAdminEmails()
    {
        return PLMSSetting::where('parameter', 'loi_admin_emails')->first();
    }

    public function getLoiDetails($batchNo)
    {
        return $this->repository->getLoiDetails($batchNo);
    }

    public function generatePDF($data)
    {
        $htmlContent = view('name-list', ['data' => $data])->render();
        $mpdf = new \Mpdf\Mpdf([
            'orientation' =>  'P',
        ]);
        $mpdf->imageVars['logo'] = file_get_contents(public_path('/assets/img/logo.png'));
        $mpdf->imageVars['imageProfile'] = file_get_contents(public_path('/assets/img/profile.png'));
        $mpdf->WriteHTML($htmlContent);
        $pdfContent = $mpdf->Output('', 'S');
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="blood_test.pdf"');
    }

    public function generateDoc($data)
    {
        $this->addHeader();
        $this->addTableHeader($data);
        $this->addApplicantTable($data);

        $filename = 'ApplicantList.docx';
        $objWriter = IOFactory::createWriter($this->phpWord, 'Word2007');
        $objWriter->save($filename);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    private function initializeStyles()
    {
        $this->cellStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START];
        $this->cellStyle2 = ['shading' => ['fill' => 'A9A9A9'], 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $this->fontStyle = ['bold' => true];
        $this->paragraphStyle = ['align' => 'right'];
    }

    private function addHeader()
    {
        $header = $this->section->addHeader();
        $header->addImage(public_path('assets/img/logo.png'), ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $headerLink = $this->section->addTextRun(['alignment' => 'left']);
        $headerLink->addText('www.antonil.com', ['color' => '1F4E78']);
        $headerLink->addTextBreak();
        $headerLink->addText('_________________________________________________________________________________', ['color' => '1F4E78']);

        $titleParagraph = $this->section->addTextRun(['alignment' => 'center']);
        $titleParagraph->addText('استمارة طلب سمات الدخول للشركات المتعاقدة مع الدولة', ['rtl' => true, 'size' => 12]);
    }

    private function addTableHeader($data)
    {
        $tableHeader = $this->section->addTable();
        $this->addHeaderRow($tableHeader, $data->company->country->country_name_short_ar ?? '', '٦- جنسية الشركة', 'Antonoil Services DMCC', ':١- اسم الشركة');
        $this->addHeaderRow($tableHeader, $data->company_address_ar ?? '', '٧- عنوان الشركة خارج العراق', 'بغداد\الجادرية\محلة 913\زقاق 34\دار 85', ':٢- عنوان الشركة داخل العراق');
        $this->addHeaderRow($tableHeader, $data->getLoiTypeArabic() ?? '' . ' متعددة الدخول ', ':٨- نوع سمة الدخول', ':العمل في تطوير حقل مجنون ', ':٣- الغاية من الدخول ');
        $this->addHeaderRow($tableHeader, $data->contract_expiry  ?? '', '٩- تاريخ انتهاء العقد', $data->getLoiTypeArabic() ?? '', ':٤- مدة البقاء المتوقعه داخل العراق');
        $this->addHeaderRow($tableHeader,  ' ', '١٠- تاريخ الإصدار المتوقع', 'عادي', '٥- الأولوية');
    }

    private function addApplicantTable($data)
    {
        $applicantTable = $this->section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $this->addApplicantHeaderRow($applicantTable);
        foreach ($data->applicants as $key => $applicant) {
            $this->addApplicantDataRow($applicantTable, $applicant, $key);
        }

        $this->addFooter($this->section);
    }

    private function addApplicantHeaderRow($table)
    {
        $table->addRow();
        $headers = ['التأمينات', 'بلد الاقامة', 'المهنة والوظيفة', 'اسم المنفذ الحدودي للدخول', 'عنوان الاقامة داخل العراق', 'رقم الجواز', 'الجنسية', 'الاسم', 'تسلسل'];
        foreach ($headers as $header) {
            $table->addCell(2000, $this->cellStyle2)->addText($header, $this->fontStyle, $this->paragraphStyle);
        }
    }

    private function addApplicantDataRow($table, $applicant, $key)
    {
        if ($applicant->pax) {
            $table->addRow();
            $cells = [
                method_exists($applicant, 'generateRemarks') ? $applicant->generateRemarks() : '',
                $applicant->pax->country_residence->country_name_short_ar ?? '',
                htmlspecialchars($applicant->pax->arab_position ?? ''),
                "مطار بغداد الدولي / مطار البصرة الدولي",
                "حقل مجنون - شركة انطونويل",
                isset($applicant->pax->latestPassport->passport_no) ? $applicant->pax->latestPassport->passport_no : '',
                isset($applicant->pax->nationality->country_name_short_ar) ? $applicant->pax->nationality->country_name_short_ar : '',
                isset($applicant->pax->latestPassport->full_name) ? $applicant->pax->latestPassport->full_name : '',
                (string)($key + 1),
            ];
            foreach ($cells as $cellText) {
                $table->addCell()->addText($cellText);
            }
        }
    }

    private function addFooter($section)
    {

        $footerParagraph = $section->addTextRun(['alignment' => 'center']);
        $footerParagraph->addText('أني المخول (ليث حسين محمد) أتعهد بعدم التصرف باوراق الشركة دون علمها او إضافة او تغير او تعديل بيانات المعلومات أعلاه وعدم اخفاء اي معلومة عن مديرية شؤون الأقامة وبخلاف ذلك اتحمل كافة التبعات القانونية. ', array('rtl' => true, 'size' => 12));
        $footerSIgnature = $section->addTextRun(['alignment' => 'right']);
        $footerSIgnature->addText('ختم وتوقيع مدير الشركة', array('rtl' => true, 'size' => 12));
        $footerSIgnature = $section->addTextRun(['alignment' => 'right']);
        $footerSIgnature->addText('YANG, CHENG', array('rtl' => true, 'size' => 12));
        $footerSIgnature = $section->addTextRun(['alignment' => 'right']);
        $footerSIgnature->addText('Assistant Managing Director', array('rtl' => true, 'size' => 12));

        $footerSIgnature = $section->addTextRun(['alignment' => 'left']);
        $footerSIgnature->addText('اسم ممثل الشركة: ليث حسين محمد', array('rtl' => true, 'size' => 12));
        $footerSIgnature = $section->addTextRun(['alignment' => 'left']);
        $footerSIgnature->addText('رقم الهوية: 199040682061', array('rtl' => true, 'size' => 12));
        $footerSIgnature = $section->addTextRun(['alignment' => 'left']);
        $footerSIgnature->addText('محل وتأريخ الإصدار: دائرة احوال - بغداد 7\12\2021', array('rtl' => true, 'size' => 12));
    }

    private function addHeaderRow($table, $cell1Text, $cell2Text, $cell3Text, $cell4Text)
    {
        $table->addRow();
        $table->addCell(4000, $this->cellStyle)->addText($cell1Text, $this->fontStyle, $this->paragraphStyle);
        $table->addCell(4000, $this->cellStyle)->addText($cell2Text, $this->fontStyle, $this->paragraphStyle);
        $table->addCell(4000, $this->cellStyle)->addText($cell3Text, $this->fontStyle, $this->paragraphStyle);
        $table->addCell(4000, $this->cellStyle)->addText($cell4Text, $this->fontStyle, $this->paragraphStyle);
    }
}
