<?php

namespace Atin\LaravelCampaign\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Atin\LaravelCampaign\Services\CampaignService;
use App\Models\User;

class CampaignController extends Controller
{
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function __invoke(string $token)
    {
        $data = $this->campaignService->decryptToken($token);

        if (
            array_key_exists('user_id', $data)
            && array_key_exists('type', $data)
            && array_key_exists('salt', $data)
            && $data['type'] === 'campaign'
            && ($user = User::find($data['user_id']))
        ) {
            // todo: unsubscribed_at
            abort(404, 'You have unsubscribed from campaign mailings.');
        }

        abort(404, 'Token is not valid.');
    }
}
