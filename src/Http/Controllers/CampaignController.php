<?php

namespace Atin\LaravelCampaign\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Atin\LaravelCampaign\Services\CampaignService;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;


class CampaignController extends Controller
{
    public function __invoke(string $token)
    {
        if (
            ($data = (new CampaignService)->decryptToken($token))
            && array_key_exists('user_id', $data)
            && array_key_exists('type', $data)
            && array_key_exists('salt', $data)
            && $data['type'] === 'campaign'
            && ($user = User::find($data['user_id']))
        ) {
            if ($user->campaign_unsubscribed_at) {
                return view('laravel-campaign::campaign.page', [
                    'title' => __('laravel-campaign::page.already_unsubscribed_title'),
                    'message' => __('laravel-campaign::page.already_unsubscribed_message'),
                ]);
            } else {
                $user->campaign_unsubscribed_at = now();
                $user->save();

                return view('laravel-campaign::campaign.page', [
                    'title' => __('laravel-campaign::page.successful_unsubscribed_title'),
                    'message' => __('laravel-campaign::page.successful_unsubscribed_message'),
                ]);
            }
        }

        abort(404);
    }
}
