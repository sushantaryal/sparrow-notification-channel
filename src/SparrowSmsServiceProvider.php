<?php

namespace Sushant\Notifications\SparrowSms;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Sushant\Notifications\SparrowSms\Channels\SparrowSmsChannel;

class SparrowSmsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sparrow-sms.php', 'sparrow-sms');

        $this->app->bind(SparrowSmsChannel::class, function ($app) {
            return new SparrowSmsChannel(
                $app['config']['sparrow-sms.token'],
                $app['config']['sparrow-sms.from']
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sparrowsms', function ($app) {
                return $app->make(SparrowSmsChannel::class);
            });
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/sparrow-sms.php' => $this->app->configPath('sparrow-sms.php'),
            ],
            'sparrow-sms'
        );
    }
}
