<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class TransactionType extends Enum implements LocalizedEnum
{
    const Buying = 0;
    const Sale = 1;
    const Dividend = 2;
    const Bonus = 3;
    const Rights = 4;
    const Merger = 5;
}
