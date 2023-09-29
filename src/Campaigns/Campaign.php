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

        foreach ($this->getRecipients() as $user) {
            Mail::to($user)->queue($class->newInstanceArgs($user));
        }
    }

    abstract protected function getRecipients(): Collection;
}