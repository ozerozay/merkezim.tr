<?php

namespace App;

enum SaleStatus: string
{
    case waiting = 'waiting';
    case success = 'success';
    case cancel = 'cancel';
    case freeze = 'freeze';
}
