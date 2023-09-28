<?php

namespace Atin\LaravelCampaign\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasCampaign
{
    protected $casts = [
        'campaign_unsubscribed_at' => 'datetime',
    ];

    public function campaignEmail(): ?string
    {
        return $this->email && ! $this->campaign_unsubscribed_at ? $this->email : null;
    }
}