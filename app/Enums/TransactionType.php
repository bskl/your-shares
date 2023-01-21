<?php

namespace App\Enums;

use App\Enums\Traits\Enumable;

enum TransactionType: int
{
    use Enumable;

    case Buying = 0;
    case Sale = 1;
    case Dividend = 2;
    case Bonus = 3;
    case Rights = 4;
    case MergerOut = 5;
    case MergerIn = 6;
    case PublicOffering = 7;
}
