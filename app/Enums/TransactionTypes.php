<?php

namespace App\Enums;

abstract class TransactionTypes extends Enum
{
    const BUYING = 0;
    const SALE = 1;
    const DIVIDEND = 2;
    const BONUS = 3;
    const RIGHTS = 4;
}
