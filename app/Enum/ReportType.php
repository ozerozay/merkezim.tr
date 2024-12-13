<?php

namespace App\Enum;

enum ReportType: string
{
    case report_client = 'report_client';
    case report_sale = 'report_sale';
    case report_service = 'report_service';
}
