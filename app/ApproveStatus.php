<?php

namespace App;

enum ApproveStatus: string
{
    case waiting = 'waiting';
    case rejected = 'rejected';
    case approved = 'approved';
}
