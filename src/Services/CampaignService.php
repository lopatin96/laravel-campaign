<?php

namespace Atin\LaravelCampaign\Services;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class CampaignService
{
    public function getCampaignUnsubscribeLink(User $user): string
    {
        return env('APP_URL') . '/campaigns/unsubscribe/' . $this->generateToken($user);
    }

    private function generateToken(User $user): string
    {
        $data = json_encode([
            'user_id' => $user->id,
            'type' => 'campaign',
            'salt' => now(),
        ]);

        return Crypt::encryptString($data);
    }

    public function decryptToken(string $token): ?array
    {
        try {
            return json_decode(Crypt::decryptString($token), true);
        } catch (DecryptException $e) {
            return null;
        }
    }
}
