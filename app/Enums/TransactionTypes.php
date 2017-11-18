<?php
namespace App\Enums;

abstract class TransactionTypes extends Enum
{
    const BUYING = 1;
    const SALE = 2;
    const DIVIDEND = 3;
}