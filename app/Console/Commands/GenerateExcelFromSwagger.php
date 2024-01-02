<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class GenerateExcelFromSwagger extends Command
{
    protected $signature = 'generate:excel';
    protected $description = 'Generate Excel file from Swagger documentation';

    public function handle()
    {
        $swaggerJsonPath = public_path('api-docs/api-docs.json');
        
        // Read the content of the Swagger JSON file
        $swaggerJson = file_get_contents($swaggerJsonPath);
        $swaggerData = json_decode($swaggerJson, true);

        if ($swaggerData === null) {
            $this->error('Error decoding JSON data from Swagger file');
            return;
        }

        $data[] = ['Path', 'Method', 'Description'];

        foreach ($swaggerData['paths'] as $path => $methods) {
            foreach ($methods as $method => $details) {
                $data[] = [$path, $method, $details['summary']];
            }
        }

        $excelFileName = 'swagger.xlsx';
        $excelFilePath = 'public/' . $excelFileName;

        Excel::store(new SwaggerExcelExporter($data), $excelFileName, 'public');

        $this->info('Excel file generated successfully: ' . $excelFilePath);
    }
}

class SwaggerExcelExporter implements FromCollection
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }
}
