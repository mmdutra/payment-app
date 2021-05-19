<?php

namespace App\Base\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Faker\Generator::class, fn () => \Faker\Factory::create('pt_BR'));
    }
}
