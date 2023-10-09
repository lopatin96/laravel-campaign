<?php

namespace Atin\LaravelCampaign\Campaigns;

use Atin\LaravelMail\Models\MailLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

abstract class Campaign
{
    protected string $mailable;

    abstract protected function getRecipients(): Collection;

    public function run(): void
    {
        foreach ($this->getRecipients()->shuffle()->take(config('laravel-campaign.max_emails_per_campaign')) as $user) {
            $this->send($user);
        }
    }

    private function send(User $user): void
    {
        if ($email = $user->campaignEmail()) {
            Mail::to($email)->queue((new \ReflectionClass($this->mailable))->newInstanceArgs([$user]));

            MailLog::create([
                'user_id' => $user->id,
                'mail_type' => $this->mailable,
            ]);
        }
    }
}
