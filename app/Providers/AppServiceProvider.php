<?php

namespace App\Providers;

use App\Services\BaseService;
use App\Services\KpiExportService;
use App\Services\LoiExportService;
use App\Contracts\LoiExportContract;
use App\Repositories\BaseRepository;
use App\Contracts\KPIServiceContract;
use App\Contracts\BaseServiceContract;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Contracts\BaseRepositoryContract;
use App\Contracts\ExcelImportRepositoryContract;
use App\Contracts\ExcelImportServiceContract;
use App\Repositories\ExcelImportRepository;
use App\Services\ExcelImportService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseServiceContract::class, BaseService::class);
        $this->app->bind(BaseRepositoryContract::class, BaseRepository::class);
        $this->app->bind(KPIServiceContract::class, KpiExportService::class);
        $this->app->bind(LoiExportContract::class, LoiExportService::class);
        $this->app->bind(ExcelImportServiceContract::class, ExcelImportService::class);
        $this->app->bind(ExcelImportRepositoryContract::class, ExcelImportRepository::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

    }
}
