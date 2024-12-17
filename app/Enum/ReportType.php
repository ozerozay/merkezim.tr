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
}
