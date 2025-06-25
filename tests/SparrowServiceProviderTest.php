<?php

namespace Sushant\SparrowSmsNotification\Tests;

use Illuminate\Notifications\ChannelManager;
use Sushant\SparrowSmsNotification\SparrowSmsChannel;

class SparrowServiceProviderTest extends TestCase
{
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
