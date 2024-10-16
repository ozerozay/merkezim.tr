<?php

namespace App;

enum OfferStatus: string
{
    case waiting = 'waiting';
    case success = 'success';
    case cancel = 'cancel';
}
