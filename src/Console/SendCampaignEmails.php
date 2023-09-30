<?php

namespace Atin\LaravelCampaign\Console;

class SendCampaignEmails
{
    public function __invoke(): void
    {
        foreach (config('laravel-campaign.active_mails') as $campaign => $frequency) {
            (new $campaign())->run();
        }
    }
}
