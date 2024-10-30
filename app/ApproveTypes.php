<?php

namespace App;

enum ApproveTypes: string
{
    case client_add_service = 'client_add_service';
    case client_use_service = 'client_use_service';
    case client_transfer_service = 'client_transfer_service';
    case client_update_label = 'client_update_label';
    case create_adisyon = 'create_adisyon';
    case create_product_sale = 'create_product_sale';
    case create_coupon = 'create_coupon';
    case create_offer = 'create_offer';
    case update_offer = 'update_offer';
    case cancel_offer = 'cancel_offer';
    case approve_offer = 'approve_offer';
    case create_taksit = 'create_taksit';
    case mahsup = 'mahsup';
    case payment = 'payment';
}
