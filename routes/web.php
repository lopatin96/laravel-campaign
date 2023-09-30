<?php

use Atin\LaravelCampaign\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;

Route::get('/campaigns/unsubscribe/{token}', CampaignController::class)->where('token', '.*');