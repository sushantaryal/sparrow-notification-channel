<?php

namespace Sushant\SparrowSmsNotification\Tests;

use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Sushant\SparrowSmsNotification\SparrowMessage;

class SparrowSmsChannelTest extends TestCase
{
    public function test_sends_sms_using_sparrowsms_channel()
    {
        Http::fake();

        $notifiable = new class {
            public $phone_number = '9800000000';

            public function routeNotificationFor($channel, $notification)
            {
                return $this->phone_number;
            }
        };

        $notification = new class extends BaseNotification {
            public function via($notifiable)
            {
                return ['sparrowsms'];
            }

            public function toSparrowSms($notifiable)
            {
                return (new SparrowMessage)
                    ->content('Test message');
            }
        };

        Notification::send($notifiable, $notification);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.sparrowsms.com/v2/sms/'
                && $request['token'] === 'test-token'
                && $request['from'] === '1234567890'
                && $request['to'] === '9800000000'
                && $request['text'] === 'Test message';
        });
    }

    public function test_logs_error_if_sms_sending_fails()
    {
        Http::fake([
            'https://api.sparrowsms.com/v2/sms/' => Http::response(['message' => 'Unauthorized'], 401),
        ]);

        Log::shouldReceive('error')
            ->once()
            ->with([
                'message' => 'Unauthorized',
            ]);

        $notifiable = new class {
            public $phone_number = '9800000000';

            public function routeNotificationFor($channel, $notification)
            {
                return $this->phone_number;
            }
        };

        $notification = new class extends BaseNotification {
            public function via($notifiable)
            {
                return ['sparrowsms'];
            }

            public function toSparrowSms($notifiable)
            {
                return (new SparrowMessage)
                    ->content('Fail message');
            }
        };

        Notification::send($notifiable, $notification);
    }
}
