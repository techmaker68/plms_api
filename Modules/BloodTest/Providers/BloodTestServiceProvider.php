<?php

namespace Modules\BloodTest\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\BloodTest\Contracts\BloodApplicantRepositoryContract;
use Modules\BloodTest\Contracts\BloodApplicantServiceContract;
use Modules\BloodTest\Services\BloodTestService;
use Modules\BloodTest\Repositories\BloodTestRepository;
use Modules\BloodTest\Contracts\BloodTestServiceContract;
use Modules\BloodTest\Contracts\BloodTestRepositoryContract;
use Modules\BloodTest\Repositories\BloodApplicantRepository;
use Modules\BloodTest\Services\BloodApplicantService;

class BloodTestServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'BloodTest';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'bloodtest';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(BloodTestServiceContract::class, BloodTestService::class);
        $this->app->bind(BloodTestRepositoryContract::class, BloodTestRepository::class);
        //binding for blood applicants
        $this->app->bind(BloodApplicantServiceContract::class, BloodApplicantService::class);
        $this->app->bind(BloodApplicantRepositoryContract::class, BloodApplicantRepository::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
