<?php

namespace Atin\LaravelCampaign\Campaigns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

abstract class Campaign
{
    protected string $mailable;

    public function run(): void
    {
        $class = new \ReflectionClass($this->mailable);

        foreach ($this->getRecipients()->shuffle()->take(config('laravel-campaign.max_emails_per_campaign')) as $user) {
            if ($email = $user->campaignEmail()) {
                Mail::to($email)->queue($class->newInstanceArgs([$user]));
            }
        }
    }

    abstract protected function getRecipients(): Collection;
}
