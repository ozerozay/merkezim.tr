<?php

namespace App;

enum ApproveTypes: string
{
    case client_add_service = 'client_add_service';
    case client_use_service = 'client_use_service';
    case client_transfer_service = 'client_transfer_service';
    case client_update_label = 'client_update_label';
}
