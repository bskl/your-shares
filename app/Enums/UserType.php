<?php

namespace App\Enums;

use App\Enums\Traits\Enumable;

enum UserType: int
{
    use Enumable;

    case Waiting = 0;
    case Accepted = 1;
    case User = 2;
    case Admin = 9;
}
