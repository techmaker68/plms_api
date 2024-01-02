<?php

namespace App\Http\Controllers;

use App\Factories\ExportFactory;
use App\Factories\ExportServiceFactory;
use App\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends BaseController
{
    protected $service;
    public function export(Request $request)
    {
        try {
            $type = $request->input('export_type');
            $this->service = ExportServiceFactory::make($type);
            $filters = $this->service->prepareFilters($request->all());
            $data = $this->service->all($filters);
            $export = ExportFactory::make($type, $data);
            return  Excel::download($export,  $type . ' List.xlsx');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
