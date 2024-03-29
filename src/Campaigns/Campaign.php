<?php

namespace Atin\LaravelCampaign\Campaigns;

use Atin\LaravelCampaign\Enums\SubscriptionStatus;
use Atin\LaravelMail\Models\MailLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

abstract class Campaign
{
    protected string $mailable;

    protected bool $sendOnlyOnce = true;

    protected bool $doNotSendToUnsubscribedFromCampaigns = true;

    protected SubscriptionStatus $subscriptionStatus = SubscriptionStatus::Any;

    protected array $sendToUsersWithStatuses = ['active'];

    abstract protected function buildQuery(): Builder;

    public function run(): void
    {
        foreach ($this->getRecipients() as $user) {
            $this->send($user);
        }
    }

    private function getRecipients(): Collection
    {
        return $this->buildQuery()
            ->select('users.*')
            ->when($this->sendOnlyOnce, function ($query) {
                $query->leftJoin('mail_logs', function($join) {
                    $join->on('users.id', '=', 'mail_logs.user_id')
                        ->where('mail_logs.mail_type', '=', $this->mailable);
                })
                    ->whereNull('mail_logs.user_id');
            })
            ->when($this->doNotSendToUnsubscribedFromCampaigns, function ($query) {
                $query->whereNull('campaign_unsubscribed_at');
            })
            ->when($this->subscriptionStatus === SubscriptionStatus::Active, function ($query) {
                $query->leftJoin('subscriptions', function($join) {
                    $join->on('users.id', '=', 'subscriptions.user_id');
                })
                    ->where('subscriptions.stripe_status', '=', 'active');
            })
            ->when($this->subscriptionStatus === SubscriptionStatus::Canceled, function ($query) {
                $query->leftJoin('subscriptions', function($join) {
                    $join->on('users.id', '=', 'subscriptions.user_id');
                })
                    ->where('subscriptions.stripe_status', '=', 'canceled');
            })
            ->when($this->subscriptionStatus === SubscriptionStatus::NeverPaid, function ($query) {
                $query->leftJoin('subscriptions', function($join) {
                    $join->on('users.id', '=', 'subscriptions.user_id');
                })
                    ->whereNull('subscriptions.stripe_status');
            })
            ->when($this->subscriptionStatus === SubscriptionStatus::CanceledOrNeverPaid, function ($query) {
                $query->leftJoin('subscriptions', function($join) {
                    $join->on('users.id', '=', 'subscriptions.user_id');
                })
                    ->where(function($query) {
                        $query->where('subscriptions.stripe_status', '=', 'canceled')
                            ->orWhereNull('subscriptions.stripe_status');
                    });
            })
            ->when($this->subscriptionStatus === SubscriptionStatus::EverPaid, function ($query) {
                $query->leftJoin('subscriptions', function($join) {
                    $join->on('users.id', '=', 'subscriptions.user_id');
                })
                    ->where(function($query) {
                        $query->whereNotNull('subscriptions.stripe_status');
                    });
            })
            ->whereIn('users.status', $this->sendToUsersWithStatuses)
            ->distinct()
            ->get()
            ->shuffle()
            ->take(config('laravel-campaign.max_emails_per_campaign'));
    }

    private function send(User $user): void
    {
        if ($user->campaignEmail()) {
            Mail::to($user)->queue((new \ReflectionClass($this->mailable))->newInstanceArgs([$user]));

            MailLog::create([
                'user_id' => $user->id,
                'mail_type' => $this->mailable,
            ]);
        }
    }
}
