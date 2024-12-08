## Gupshup SMS Laravel Notification Channel

This package will manages to send Text SMS using  [Gupshup.io](https://www.gupshup.io) service

### Version - v1.0

### Requirement

- PHP VERSION >= 7.3
- Laravel 8+
- Gupshup.io Account

### Install Using Composer
```bash
composer require vivekdhumal/gupshup-sms-channel
```

### Publish Config (Optional)
```bash
php artisan vendor:publish --tag=config
```

### Environment Variables
```env
GUPSHUP_USERID='Your user id'
GUPSHUP_PASSWORD='Your password'
GUPSHUP_MASK='Your Company Sender ID / Mask'
GUPSHUP_ENTITYID='Your company Entity ID if required',
GUPSHUP_TEMPLATE_ID='Your registered SMS Template ID if required',
```

### Implementation

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use VivekDhumal\GupshupSmsChannel\GupshupChannel;
use VivekDhumal\GupshupSmsChannel\GupshupMessage;

class SendTestMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [GupshupChannel::class];
    }

    public function toGupshup($notifiable)
    {
        return (new GupshupMessage())
                ->to('+91'.$notifiable->mobile)
                ->message('Your message');
    }
}
```

### Event Listener

if you want to save response in logs or database.

```php
<?php

namespace App\Listeners;

use App\Events\NotificationSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use VivekDhumal\GupshupSmsChannel\GupshupChannel;

class SaveGupshupResponse
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotificationSent  $event
     * @return void
     */
    public function handle($event)
    {
        if($event->channel = "VivekDhumal\GupshupSmsChannel\GupshupChannel") {
            $apiResponse = $event->response;

            dd($apiResponse->status, $apiResponse->message, $apiResponse->id,
                $apiResponse->details, $apiResponse->phone);
        }
    }
}
```

in EventServiceProvider class register the listener as below

```php
use App\Listeners\SaveGupshupResponse;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        NotificationSent::class => [
            SaveGupshupResponse::class,
        ]
    ];
```