<?php

namespace Atin\LaravelCampaign\Campaigns;

use Illuminate\Database\Eloquent\Collection;

interface Campaign
{
    public function getRecipients(): Collection;
}