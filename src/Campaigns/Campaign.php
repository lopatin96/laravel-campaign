<?php

namespace Atin\LaravelCampaign\Campaigns;

use Atin\LaravelMail\Models\MailLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

abstract class Campaign
{
    protected string $mailable;

    protected bool $sendOnlyOnce = true;

    protected bool $doNotSendToUnsubscribed = true;

    protected bool $excludeBlockedUsers = true;

    abstract protected function buildQuery(): Builder;

    public function run(): void
    {
        foreach ($this->getRecipients() as $user) {
            $this->send($user);
        }
    }

    private function getRecipients(): Collection
    {
        return $this->select('users.*')
            ->when($this->sendOnlyOnce, function ($query) {
                $query->leftJoin('mail_logs', function($join) {
                    $join->on('users.id', '=', 'mail_logs.user_id')
                        ->where('mail_logs.mail_type', '=', $this->mailable);
                })
                    ->whereNull('mail_logs.user_id');
            })
            ->when($this->doNotSendToUnsubscribed, function ($query) {
                $query->whereNull('campaign_unsubscribed_at');
            })
            ->when($this->excludeBlockedUsers, function ($query) {
                $query->whereNotIn('users.status', ['blocked']);
            })
            ->shuffle()
            ->take(config('laravel-campaign.max_emails_per_campaign'))
            ->distinct()
            ->get();
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
