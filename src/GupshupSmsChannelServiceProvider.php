<?php

namespace VivekDhumal\GupshupSmsChannel;

use Illuminate\Support\ServiceProvider;

class GupshupSmsChannelServiceProvider extends ServiceProvider 
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/gupshup.php',
            'gupshup'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/gupshup.php' => config_path('gupshup.php'),
        ], 'config');
    }
}