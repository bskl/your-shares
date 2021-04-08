<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static self Buying()
 * @method static self Sale()
 * @method static self Dividend()
 * @method static self Bonus()
 * @method static self Rights()
 * @method static self MergerOut()
 * @method static self MergerIn()
 * @method static self PublicOffering()
 */
final class TransactionType extends Enum implements LocalizedEnum
{
    const Buying = 0;
    const Sale = 1;
    const Dividend = 2;
    const Bonus = 3;
    const Rights = 4;
    const MergerOut = 5;
    const MergerIn = 6;
    const PublicOffering = 7;
}
