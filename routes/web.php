<?php

use Illuminate\Support\Facades\Route;

Route::get('/campaigns/unsubscribe/{token}', \Ating\LaravelCampaign\Http\Controllers\CampaignController::class);