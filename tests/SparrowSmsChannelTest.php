<?php

namespace Sushant\SparrowSmsNotification\Tests;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;
use Sushant\SparrowSmsNotification\SparrowMessage;
use Sushant\SparrowSmsNotification\SparrowSmsChannel;

class SparrowSmsChannelTest extends TestCase
{
    public function test_sends_sms_using_sparrowsms_channel()
    {
        $response = Http::fake();

        $channel = new SparrowSmsChannel('test-token', '1234567890');

        $notifiable = new class {
            public $phone_number = '1234567890';

            public function routeNotificationFor($channel, $notification)
            {
                return $this->phone_number;
            }
        };

        $notification = new class extends Notification {
            public function toSparrowSms($notifiable)
            {
                return new SparrowMessage('Hello World');
            }
        };

        $channel->send($notifiable, $notification);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.sparrowsms.com/v2/sms/'
                && $request['token'] === 'test-token'
                && $request['from'] === '1234567890'
                && $request['to'] === '1234567890'
                && $request['text'] === 'Hello World';
        });
    }

    public function test_logs_error_if_sms_sending_fails()
    {
        Http::fake([
            'https://api.sparrowsms.com/v2/sms/' => Http::response(['message' => 'Unauthorized'], 401),
        ]);

        $channel = new SparrowSmsChannel('bad-token', '1234567890');

        $notifiable = new class {
            public $phone_number = '1234567890';

            public function routeNotificationFor($channel, $notification)
            {
                return $this->phone_number;
            }
        };

        $notification = new class extends Notification {
            public function toSparrowSms($notifiable)
            {
                return new SparrowMessage('Fail test');
            }
        };

        Log::shouldReceive('error')->once();

        $channel->send($notifiable, $notification);
    }
}
