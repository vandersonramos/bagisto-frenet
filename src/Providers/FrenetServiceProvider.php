<?php

namespace VandersonRamos\Frenet\Providers;

use Illuminate\Support\ServiceProvider;

class FrenetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap Services
     */
    public function boot()
    {

    }

    /**
     * Register Services
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register Config
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/carriers.php', 'carriers');
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/system.php', 'core');
    }
}