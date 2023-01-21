<?php

namespace App\Enums;

use App\Enums\Traits\Enumable;

enum Currency: string
{
    use Enumable;

    case Try = 'TRY';
}
