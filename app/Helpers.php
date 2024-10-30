<?php

namespace App;

if (! function_exists('format_price')) {
    function format_price($price): false|string
    {
        return \Number::currency((float) $price, in: 'TRY', locale: 'tr');
    }
}
