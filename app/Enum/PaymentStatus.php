<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case pending = 'pending';
    case cancel = 'cancel';
    case error = 'error';
    case approved = 'approved';
    case success = 'success';
}
