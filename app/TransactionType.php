<?php

namespace App;

enum TransactionType: string
{
    case pesinat = 'pesinat';
    case staff_pay = 'staff_pay';
    case cancel = 'cancel';
}
