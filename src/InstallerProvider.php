<?php

namespace SyntesyDigital\InsuranceInstaller;

use Illuminate\Support\ServiceProvider;

class InstallerProvider extends ServiceProvider
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
            SyntesyDigital\InsuranceInstaller\Installer::class
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
