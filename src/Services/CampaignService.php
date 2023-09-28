<?php

namespace Atin\LaravelCampaign\Services;

use App\Models\User;

class CampaignService
{
    public function __construct()
    {
        // Store cipher method
        $this->ciphering = "BF-CBC";

        // Use OpenSSL encryption method
        $this->iv_length = openssl_cipher_iv_length($this->ciphering);
        $this->options = 0;

        // Use random_bytes() function to generate a random initialization vector (iv)
        $this->encryption_iv = random_bytes($this->iv_length);

        // Use php_uname() as the encryption key
        $this->encryption_key = openssl_digest(php_uname(), 'MD5', TRUE);
    }

    public function getCampaignUnsubscribedLink(User $user): string
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

        return openssl_encrypt($data, $this->ciphering, $this->encryption_key, $this->options, $this->encryption_iv);
    }

    public function decryptToken(string $token): array
    {
        return json_decode(openssl_encrypt($token, $this->ciphering, $this->encryption_key, $this->options, $this->encryption_iv));
    }
}