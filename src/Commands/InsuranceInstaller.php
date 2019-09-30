<?php

namespace SyntesyDigital\InsuranceInstaller\Commands;

use Illuminate\Console\Command;

class InsuranceInstaller extends Command
{
    private $packages = [
        [
            'name' => 'Insurance CMS',
            'description' => 'CMS for Insurance Solution',
            'url' => 'https://github.com/SyntesyDigital/InsuranceArchitect',
            'directory' => 'Architect',
        ],
        [
            'name' => 'Insurance Extranet',
            'description' => 'Extranet for Insurance Solution',
            'url' => 'https://github.com/SyntesyDigital/InsuranceExtranet',
            'directory' => 'Extranet',
        ],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insurance:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install insurance package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function prompt()
    {
        $packagesList = collect($this->packages)
            ->map(function($package){
                return [$package["name"], $package["description"]];
            });

        $packageNames = collect($this->packages)
            ->map(function($package){
                return $package["name"];
            })->toArray();

        $this->info('Syntesy Digital © http://syntesy.io');

        $this->table([
            'Package Name',
            'Description'
        ], $packagesList);

        $this->package = $this->choice('Chose the packages to install', $packageNames, $packageNames[0]);
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->prompt();

        if(!$this->package) {
            $this->error('No package selected');
            return false;
        }

        $path = base_path('Modules/');

        if (!is_dir($path)) {
            $this->info(' -> Modules directory no exist... creating');
            if(mkdir($path, 0775)) {
                $this->info(' -> OK');
            }
        }

        // Get package to install
        $package = $this->getPackageByName($this->package);

        if(!$package) {
            $this->error("$package no found");
            return false;
        }

        if(is_dir($path . $package["directory"])) {
            $this->error('Module '.$package["name"].' is already installed');
            return false;
        }

        // Clone Module directory
        $this->info(' -> cloning files from repository');
        exec("git clone ".$package["url"]."  Modules/" . $package["directory"]);

        // $this->installComposerDependencies($package);
        // $this->installPackage($package);
        // $this->installNpmDependencies($package);

        // Dump autoload
        $this->info(' -> composer dump autoload');
        exec('composer dumpautoload');

        if(is_file(base_path() . '/Modules/' . $package["directory"] . '/install.php')) {
            $this->info(' -> run module configurator');
            include(base_path() . '/Modules/' . $package["directory"] . '/install.php');
        }
    
    }


    private function getPackageByName($name)
    {
        return collect($this->packages)
            ->filter(function($item) use ($name) {
                return $item["name"] === $name ? true : false;
            })->first();
    }
}
