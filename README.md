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

### Trait
Add ```HasCampaign``` trait.

```php
use Atin\LaravelCampaign\Traits\HasCampaign;

class User extends Authenticatable
{
    use HasCampaign;
    …
}
```

### Campaigns
Create ```app/Campaigns``` directory. Create campaign class which implements ```Atin\LaravelCampaign\Campaigns\Campaign```. 
Don't forget to add your campaign class to ```laravel-campaign``` config.

```php
<?php

return [
    'active_mails' => [
        '\App\Mail\TestCampaign' => 'daily',
    ]
];
```

```php
use Atin\LaravelCampaign\Campaigns\Campaign;

use Illuminate\Database\Eloquent\Collection;

class TestCampaign implements Campaign
{
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

### Migrations
```php
php artisan vendor:publish --tag="laravel-campaign-migrations"
```