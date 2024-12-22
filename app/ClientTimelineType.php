<?php

namespace App;

enum ClientTimelineType: string
{
    case delete_service = 'delete_service';
    case edit_service = 'edit_service';
    case update_service_status = 'update_service_status';

    case update_adisyon = 'update_adisyon';

    case update_sale = 'update_sale';
    case update_sale_status = 'update_sale_status';
    case delete_sale = 'delete_sale';

    case update_taksit_date = 'update_taksit_date';
    case delete_taksit = 'delete_taksit';
    case update_taksit_status = 'update_taksit_status';

    case delete_sale_product = 'delete_sale_product';
    case update_sale_product = 'update_sale_product';

    case delete_adisyon = 'delete_adisyon';

}
