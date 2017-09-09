<?php namespace AwkwardIdeas\PHPCFEncrypt;

use Illuminate\Support\ServiceProvider;

class PHPCFEncryptServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/PHPCFEncrypt.php';

        $this->publishes([$configPath => $this->getConfigPath()], 'config');


    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

    }

    private function getConfigPath()
    {
        return config_path('PHPCFEncrypt.php');
    }

}