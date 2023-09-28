<?php

namespace Atin\LaravelCampaign\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function __invoke(string $token)
    {
        $request->validate([
            'flexlink' => 'required',
        ]);

        $flexLink = $request->flexlink;
        $qr = self::generateQrCode($flexLink);

        $parsed = parse_url($flexLink);
        $filename = $parsed['host'].$parsed['path'].'.png';

        return response($qr->getString())
            ->header('Content-Type', $qr->getMimeType())
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
