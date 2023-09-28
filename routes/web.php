<?php

use Illuminate\Support\Facades\Route;

Route::get('/campaigns/unsubscribe/{token}', \Atin\LaravelCampaign\Http\Controllers\CampaignController::class);