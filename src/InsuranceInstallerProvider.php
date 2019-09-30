<?php

namespace SyntesyDigital\InsuranceInstaller;

use Illuminate\Support\ServiceProvider;

class InsuranceInstallerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register command
        $this->commands([
            \SyntesyDigital\InsuranceInstaller\Commands\InsuranceInstaller::class
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {}
}
