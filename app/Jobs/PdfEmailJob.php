<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Jobs;

use App\Exports\NameListExport;
use App\Mail\ZipFileEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Loi\Entities\PLMSLoi;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

class PdfEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $batchNo;
    private $adminEmails;
    private $nameListPath;

    public function __construct($batchNo, $adminEmails, $nameListPath)
    {
        $this->batchNo = $batchNo;
        $this->adminEmails = $adminEmails;
        $this->nameListPath = $nameListPath;
    }

    public function handle()
    {
        $tempDir = $this->createTemporaryDirectory();
        $zipFilePath = $this->createZipArchive($tempDir);
        $this->sendEmailWithDownloadLink($zipFilePath);
        $this->deleteDirectory($tempDir);
    }

    private function createTemporaryDirectory()
    {
        $tempDir = storage_path('app/temp_files');
        $phpWordTempDir = storage_path('app/phpword_temp');
        $downloadDir = public_path('temp_download');

        foreach ([$tempDir, $phpWordTempDir, $downloadDir] as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }

        return $tempDir;
    }

    private function createZipArchive($tempDir)
    {
        $zipFileName = $this->batchNo . '.zip';
        $zipFilePath = public_path('temp_download/' . $zipFileName);
        $zip = new ZipArchive;

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $pdfViews = ['051-MOI', '049-MOI', '050-MOO', '048-Majnoon', 'name-list'];
            $dataObject = $this->createDataObject();
            $result = [];

            foreach ($dataObject->applicants as $applicant) {

                //Generating Passports
                $this->addPassportFilesToZip($zip, $applicant);

                //Preparing Data for Deposits
                $receipt_no = $applicant->loi_payment_receipt_no;
                $sequence_no = $applicant->sequence_no;
                $payment_letter_copy = $applicant->payment_letter_copy;

                if (!isset($result[$receipt_no])) {
                    $result[$receipt_no] = [
                        'sequence_numbers' => [],
                        'payment_letter_copies' => [],
                    ];
                }

                $result[$receipt_no]['sequence_numbers'][] = $sequence_no;
                $result[$receipt_no]['payment_letter_copies'] = $payment_letter_copy;
                if ($applicant->loi_photo_copy != null) {
                    $result[$receipt_no]['loi_photo_copy'] = $applicant->loi_photo_copy;
                } else {
                    $result[$receipt_no]['loi_photo_copy'] = $dataObject->loi_photo_copy;
                }
            }

            //Generating Deposits
            $this->addDepositsToZip($zip, $result);

            //Generating PDFs and Docx
            $documentSubfolder = 'documents';
            foreach ($pdfViews as $view) {
                $pdfContent = $this->generatePdfContent($view, $dataObject, $view === 'name-list');
                $pdfFileName =  $this->setFileNames($view, $dataObject, '.pdf');
                $pdfFilePath = $tempDir . '/' . $pdfFileName;
                file_put_contents($pdfFilePath, $pdfContent);
                $fileName = $documentSubfolder . '/' . $pdfFileName;
                $zip->addFile($pdfFilePath, $fileName);

                if ($view != 'name-list') {
                    $wordFilePath = $this->generateWordContent($view, $dataObject, $tempDir);
                    $wordFileName =  $this->setFileNames($view, $dataObject, '.docx');
                    $fileName = $documentSubfolder . '/' . $wordFileName;
                    $zip->addFile($wordFilePath, $fileName);
                }
            }

            //Adding name list file
            if (file_exists($this->nameListPath)) {
                $nameListFileName = basename($this->nameListPath);
                $nameListfile = $documentSubfolder . '/' . $nameListFileName;
                $zip->addFile($this->nameListPath, $nameListfile);
            }
            // adding name list excel file
            $nameListExcel = $this->nameListExcel();
            if (file_exists($nameListExcel)) {
                $nameListExcelFileName = basename($nameListExcel);
                $nameListExcelfile = $documentSubfolder . '/' . $nameListExcelFileName;
                $zip->addFile($nameListExcel, $nameListExcelfile);
            }
            $zip->close();
        }

        return $zipFilePath;
    }

    private function createDataObject()
    {
        return PLMSLoi::where('batch_no', $this->batchNo)
            ->with(['applicants' => function ($query) {
                $query->orderBy('sequence_no');
            }, 'company', 'applicants.pax'])
            ->first();
    }

    private function generatePdfContent($view, $dataObject, $landscape = false)
    {
        $htmlContent = view($view, ['data' => $dataObject])->render();
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => $landscape ? 'L' : 'P',
        ]);
        $mpdf->imageVars['imageProfile'] = file_get_contents(public_path('/assets/img/profile.png'));
        $mpdf->imageVars['logo'] = file_get_contents(public_path('/assets/img/logo.png'));
        $mpdf->WriteHTML($htmlContent);
        return $mpdf->Output('', 'S');
    }

    private function generateWordContent($view, $data, $tempDir)
    {
        \PhpOffice\PhpWord\Settings::setTempDir(storage_path('app/phpword_temp'));
        $template = new TemplateProcessor(public_path('docs/' . $view . '.docx'));

        $template->setValue('loi_type', $data->getLoiType());
        $template->setValue('applicants_counts', $data->applicants->count());
        $template->setValue('largest_record_eng', $data->getLargestIdRecord()->pax->eng_full_name ?? '');
        $template->setValue('smallest_record_eng', $data->getSmallestIdRecord()->pax->eng_full_name ?? '');
        $template->setValue('lg_name_a', $data->getLargestIdRecord()->latestPassport->full_name ?? '');
        $template->setValue('sm_name_a', $data->getSmallestIdRecord()->latestPassport->full_name ?? '');
        $template->setValue('loi_type_arab', $data->getLoiTypeArabic());
        $template->setValue('moi_ref', $data->moi_ref);
        $template->setValue('moi_date', $data->moi_date);
        $template->setValue('majnoon_ref', $data->majnoon_ref);
        $template->setValue('majnoon_date', $data->majnoon_date);
        $template->setValue('moo_ref', $data->moo_ref);
        $template->setValue('moo_date', $data->moo_date);
        $template->setValue('moi_2_ref', $data->moi_2_ref);
        $template->setValue('moi_2_date', $data->moi_2_date);
        $template->setValue('a_count', $data->convertToArabicNumber());
        $template->setValue('count', $data->applicants->count());

        $wordFileName = $view . '.docx';
        $wordFilePath = $tempDir . '/' . $wordFileName;
        $template->saveAs($wordFilePath);
        return $wordFilePath;
    }

    private function addPassportFilesToZip($zip, $applicant)
    {
        $passport = $applicant->latestPassport;
        if ($passport && !empty($passport->file)) {
            $passportFilePath = public_path('media/' . $passport->file);
            if (file_exists($passportFilePath)) {
                $srNo = $applicant->sequence_no;
                $fileExtension = File::extension($passportFilePath);
                $passportFileName = "$srNo. {$applicant->pax->eng_full_name}.$fileExtension";
                $zip->addFile($passportFilePath, "passports/$srNo. {$applicant->pax->eng_full_name}/$passportFileName");
            }
        }
    }

    private function addDepositsToZip($zip, $result)
    {
        $depositSubfolder = 'deposits';
        $zip->addEmptyDir($depositSubfolder);
        foreach ($result as $group) {
            sort($group['sequence_numbers']);
            $key = implode(',', $group['sequence_numbers']);
            $depositFolderName = $depositSubfolder . '/' . $key;
            $final_payment_letter_copies = array_unique($group['payment_letter_copies']);

            foreach ($final_payment_letter_copies as $num => $letter_copy) {
                if (!empty($letter_copy)) {
                    $depositFilePath = public_path('media/' . $letter_copy);
                    if (file_exists($depositFilePath)) {
                        $fileExtension = File::extension($depositFilePath);
                        $depositFileName = 'Document ' . ($num + 1) . '.' . $fileExtension;
                        $zip->addFile($depositFilePath, $depositFolderName . '/' . $depositFileName);
                    }
                }
            }
            if (!empty($group['loi_photo_copy'])) {
                $filePath = public_path('media/' . $group['loi_photo_copy']);
                if (file_exists($filePath)) {
                    $fileExtension = File::extension($filePath);
                    $loiFileName = 'LOI' . '.' . $fileExtension;
                    $zip->addFile($filePath, $depositFolderName . '/' . $loiFileName);
                }
            }
        }
    }

    private function sendEmailWithDownloadLink($zipFilePath)
    {
        $emails = explode(',', $this->adminEmails->value);
        $mainRecipient = trim(array_shift($emails));
        $ccEmails = array_filter(array_map('trim', $emails));

        if (!empty($mainRecipient)) {
            $downloadLink = url('temp_download/' . basename($zipFilePath));
            Mail::to($mainRecipient)->send(new ZipFileEmail($this->batchNo, $downloadLink, $ccEmails));
        }
    }

    private function deleteDirectory($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . '/' . $object)) {
                        $this->deleteDirectory($dir . '/' . $object);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }
            rmdir($dir);
        }
        $phpWordTempDir = storage_path('app/phpword_temp');
        if (is_dir($phpWordTempDir)) {
            array_map('unlink', glob("$phpWordTempDir/*"));
            rmdir($phpWordTempDir);
        }

        $nameListTempDire = storage_path('app/temp_name_list');
        if (is_dir($nameListTempDire)) {
            array_map('unlink', glob("$nameListTempDire/*"));
            rmdir($nameListTempDire);
        }
    }

    public function nameListExcel()
    {
        // Generate the Excel file using Maatwebsite Excel
        $export = new NameListExport($this->batchNo);
        $excel = app(\Maatwebsite\Excel\Excel::class);
        $tempExcelFileName = 'temp_name_list/' . $this->batchNo . '-NameListLoi.xlsx';
        // Export and store the Excel file
        $excel->store($export, $tempExcelFileName, 'local');
        // Provide the download link for the Excel file
        return Storage::disk('local')->path($tempExcelFileName);
    }
    public function setFileNames($view, $data, $ext)
    {
        if ($view == 'name-list') {
            return $view . $ext;
        }
        $prefix = '';
        switch ($view) {
            case '048-Majnoon':
                $prefix = $data->majnoon_ref;
                break;
            case '049-MOI':
                $prefix = $data->moi_ref;
                break;
            case '050-MOO':
                $prefix = $data->moo_ref;
                break;
            case '051-MOI':
                $prefix = $data->moi_2_ref;
                break;
        }
        $viewParts = explode('-', $view);
        $secondPart = end($viewParts);
        $secondPart = $prefix . '-' . $secondPart;
        return $secondPart . $ext;
    }
}
