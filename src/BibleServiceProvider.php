<?php

namespace PrevailExcel\Bible;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

/*
 * This file is part of the Laravel Bible package.
 *
 * (c) Prevail Ejimadu <prevailexcellent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BibleServiceProvider extends ServiceProvider
{

    /*
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
    * Publishes all the config file this package needs to function
    */
    public function boot()
    {
        $config = realpath(__DIR__.'/../utils/config/bible.php');

        $this->publishes([
            $config => config_path('bible.php')
        ]);
        if (File::exists(__DIR__ . '/../utils/helpers/bible.php')) {
            require __DIR__ . '/../utils/helpers/bible.php';
        }
    }

    /**
    * Register the application services.
    */
    public function register()
    {
        $this->app->bind('laravel-bible', function () {

            return new Bible;

        });
    }

    
    /**
    * Get the services provided by the provider
    * @return array
    */
    public function provides()
    {
        return ['laravel-bible'];
    }
}