<?php

namespace Sushant\SparrowSmsNotification;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SparrowSmsChannel
{
    /**
     * API endpoint sparrow sms
     *
     * @var string
     */
    protected $endpoint = "https://api.sparrowsms.com/v2/sms/";

    /**
     * Token generated from website
     *
     * @var string
     */
    protected $token;

    /**
     * Identifier
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new Sparrow channel instance.
     *
     * @param  string  $token
     * @param  string  $from
     * @return void
     */
    public function __construct($token, $from)
    {
        $this->token = $token;
        $this->from = $from;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSparrowSms($notifiable);

        if (is_string($message)) {
            $message = new SparrowMessage($message);
        }

        $response = Http::post($this->endpoint, [
            "token" => $this->token,
            "from" => $message->from ?: $this->from,
            "to" => $this->getTo($notifiable, $notification),
            "text" => trim($message->content),
        ]);

        if ($response->failed()) {
            Log::error($response->json());
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return mixed
     */
    protected function getTo($notifiable, Notification $notification)
    {
        if ($notifiable->routeNotificationFor(self::class, $notification)) {
            return $notifiable->routeNotificationFor(
                self::class,
                $notification
            );
        }

        if ($notifiable->routeNotificationFor("sparrowsms", $notification)) {
            return $notifiable->routeNotificationFor(
                "sparrowsms",
                $notification
            );
        }

        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }
    }
}
