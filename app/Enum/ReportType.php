<?php

namespace App\Enum;

enum ReportType: string
{
    case report_client = 'report_client';
    case report_sale = 'report_sale';
    case report_service = 'report_service';
    case report_taksit = 'report_taksit';
    case report_appointment = 'report_appointment';
    case report_kasa = 'report_kasa';
    case report_sale_product = 'report_sale_product';
    case report_talep = 'report_talep';
    case report_note = 'report_note';
    case report_adisyon = 'report_adisyon';
    case report_offer = 'report_offer';
    case report_coupon = 'report_coupon';
    case report_approve = 'report_approve';
}
