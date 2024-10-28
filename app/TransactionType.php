<?php

namespace App;

enum TransactionType: string
{
    case pesinat = 'pesinat';
    case staff_pay = 'staff_pay';
    case adisyon_pesinat = 'adisyon_pesinat';
    case product_pesinat = 'product_pesinat';
    case mahsup = 'mahsup';
}
