# Install
### Migrations
```php
php artisan vendor:publish --tag="laravel-campaign-migrations"
```

then run ```php artisan migrate```

### Config
Publish config to manage active mails:
```php
php artisan vendor:publish --tag="laravel-campaign-config"
```

### Trait and Casts
Add ```HasCampaign``` trait and casts to User model.

```php
use Atin\LaravelCampaign\Traits\HasCampaign;

class User extends Authenticatable
{
    use HasCampaign;
   
    protected $casts = [
         'campaign_unsubscribed_at' => 'datetime',
    ];
}
```

### Campaigns
Create ```app/Campaigns``` directory. Create campaign class which extends ```Atin\LaravelCampaign\Campaigns\Campaign```. 
Don't forget to add your campaign class to ```laravel-campaign``` config.
Don't forget to create a mail which extends from ```Atin\LaravelMail\Mail\Mailable```.
```php
use Atin\LaravelMail\Mail\Mailable;

class TestMail extends Mailable
{
    public function build()
    {
         // Build email
    }
}
````

```php
<?php

return [
    'active_mails' => [
        '\App\Campaigns\TestCampaign' => 'daily',
    ]
];
```

```php
use Atin\LaravelCampaign\Campaigns\Campaign;
use Illuminate\Database\Eloquent\Collection;

class TestCampaign extends Campaign
{
    protected string $mailable = '\App\Mail\TestMail';
    
    public function getRecipients(): Collection
    {
        return \App\Models\User::where('id', '=', 1)->get();
    }
}
```

### Schedule
Register schedule task in ```app/Concole/Kernel.php```
```php
use Atin\LaravelCampaign\Console\SendCampaignEmails;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(new SendCampaignEmails())
            ->hourly();
    }
```

# Publishing
### Config
```php
php artisan vendor:publish --tag="laravel-campaign-config"
```

### Views
```php
php artisan vendor:publish --tag="laravel-campaign-views"
```

### Localization
```php
php artisan vendor:publish --tag="laravel-campaign-lang"
```

### Migrations
```php
php artisan vendor:publish --tag="laravel-campaign-migrations"
```