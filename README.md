# Sparrow SMS notifications channel for Laravel

## Installation

You can install the package via Composer:

```bash
$ composer require sushantaryal/sparrow-notification-channel
```

### Configuration

Add your Sparrow Token, and From identity to your `.env`:

```dotenv
SPARROW_SMS_TOKEN=ABCD #always required
SPARROW_SMS_FROM=identity #always required
```

### Advanced configuration

Run

```
php artisan vendor:publish --provider="Sushant\SparrowSmsNotification\SparrowSmsServiceProvider"
```

```
/config/sparrow-sms.php
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Sushant\SparrowSmsNotification\SparrowMessage;

class VerifyOtp extends Notification
{
    public function via($notifiable)
    {
        return ['sparrowsms'];
    }

    public function toSparrowSms($notifiable)
    {
        return (new SparrowMessage)
            ->content('Your OTP code is 12345.');
    }
}
```

### Available Message methods

#### SparrowMessage

-   `from('')`: Accepts a identity provided to you.
-   `content('')`: Accepts a string value for the notification body.configuration.

## License

Sparrow SMS notifications channel is open-sourced software licensed under the [MIT license](LICENSE.md).
