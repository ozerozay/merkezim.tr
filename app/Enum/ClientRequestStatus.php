<?php

namespace App\Enum;

enum ClientRequestStatus: string
{
    case pending = 'pending';
    case reject = 'reject';
    case success = 'success';
}
