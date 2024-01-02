<?php
// ***********************************
// @author Syed, Umair
// @create_date 21-07-2023
// ***********************************
namespace App\Http\Controllers;

use App\Contracts\ExcelImportServiceContract;
use App\Http\Requests\DownloadImportTypeRequest;
use App\Http\Requests\ImportExcelRequest;

class PLMSExcelController extends BaseController
{

    protected ExcelImportServiceContract $service;

    /**
     * PLMSPaxController constructor.
     *
     * @param ExcelImportServiceContract $service The service contract for handling imports.
     */
    public function __construct(ExcelImportServiceContract $service)
    {
        $this->service = $service;

        // Uncomment the following lines to add middleware permissions for specific actions
        // $this->middleware('permission:DOWNLOAD_EXCEL', ['only' => ['downloadExcel']]);
        // $this->middleware('permission:IMPORT_EXCEL', ['only' => ['importView']]);

    }

    public function downloadExcel(DownloadImportTypeRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $data = $request->validated();
            return  $this->service->downloadExcel($data);
        });
    }

    public function importView(ImportExcelRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $data = $request->validated();
            return  $this->service->importView($data);
        });
    }
}
