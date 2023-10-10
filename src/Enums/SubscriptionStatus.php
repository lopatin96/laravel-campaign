<?php

namespace Atin\LaravelCampaign\Enums;

enum SubscriptionStatus
{
    case Any;
    case Active;
    case Canceled;
    case NeverPaid ;
    case CanceledOrNeverPaid;
    case EverPaid;
}
