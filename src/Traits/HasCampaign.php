<?php

namespace Atin\LaravelCampaign\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasCampaign
{
    public function campaignEmail(): ?string
    {
        return $this->email && ! $this->campaign_unsubscribed_at ? $this->email : null;
    }
}