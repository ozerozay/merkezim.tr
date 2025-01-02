<?php

namespace App\Enum;

enum SMSStatus: string
{
    case pending = 'pending';
    case sent = 'sent';
    case delivery = 'delivery';
    case failed = 'failed';

}
