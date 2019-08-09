<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

class UserType extends Enum
{
    const Waiting = 0;
    const Accepted = 1;
    const User = 'user';
    const Admin = 'admin';
}
