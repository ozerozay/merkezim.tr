<?php

namespace App\Enum;

enum PermissionType: string
{
    case action_client_create = 'action_client_create';
    case action_client_add_note = 'action_client_add_note';
    case action_client_add_label = 'action_client_add_label';
    case action_client_create_service = 'action_client_create_service';
    case action_client_use_service = 'action_client_use_service';
    case action_client_create_offer = 'action_client_create_offer';
    case action_client_create_appointment = 'action_client_create_appointment';
    case action_adisyon_create = 'action_adisyon_create';
}
