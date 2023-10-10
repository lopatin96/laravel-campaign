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

### Generating Mailables
New "mailable" class will be stored in the *app/Mail* directory.
```php
php artisan make:mail TestMail
```

```php
use Atin\LaravelMail\Mail\Mailable;

class TestMail extends Mailable
{
    public function build()
    {
         // Build email
    }
}
```

### Campaigns
Create ```app/Campaigns``` directory and Campaign class:

```php
use Atin\LaravelCampaign\Campaigns\Campaign;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class TestCampaign extends Campaign
{
    protected string $mailable = '\App\Mail\TestMail';
    
    protected function buildQuery(): Builder
    {
         return \App\Models\User::where(function($query) {
            $query->whereDate('users.trial_ends_at', '>=', now())
                ->whereDate('users.trial_ends_at', '<', now()->addDay());
        });
    }
}
```

```php
<?php

return [
    'active_mails' => [
        '\App\Campaigns\TestCampaign' => 'daily',
    ]
];
```
Don't forget to add your campaign class to ```laravel-campaign``` config.


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