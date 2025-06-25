<?php

namespace Sushant\SparrowSmsNotification\Tests;

use Illuminate\Notifications\ChannelManager;
use Orchestra\Testbench\TestCase;
use Sushant\SparrowSmsNotification\SparrowSmsChannel;
use Sushant\SparrowSmsNotification\SparrowSmsServiceProvider;

class SparrowServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SparrowSmsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sparrow-sms.token', '1234567890');
        $app['config']->set('sparrow-sms.from', '1234567890');
    }

    public function test_binds_sparrow_sms_channel()
    {
        $channel = $this->app->make(SparrowSmsChannel::class);

        $this->assertInstanceOf(SparrowSmsChannel::class, $channel);
    }

    public function test_registers_sparrowsms_notification_channel()
    {
        $manager = $this->app->make(ChannelManager::class);

        $channel = $manager->channel('sparrowsms');

        $this->assertInstanceOf(SparrowSmsChannel::class, $channel);
    }
}
