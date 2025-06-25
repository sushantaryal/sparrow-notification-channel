<?php

namespace Sushant\SparrowSmsNotification\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sushant\SparrowSmsNotification\SparrowSmsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [SparrowSmsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sparrow-sms.token', 'test-token');
        $app['config']->set('sparrow-sms.from', '1234567890');
    }
}
