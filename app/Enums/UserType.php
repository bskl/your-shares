<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static self Waiting()
 * @method static self Accepted()
 * @method static self User()
 * @method static self Admin()
 */
final class UserType extends Enum
{
    const Waiting = 0;
    const Accepted = 1;
    const User = 2;
    const Admin = 9;
}
