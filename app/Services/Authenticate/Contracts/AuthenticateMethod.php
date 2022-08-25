<?php

namespace App\Services\Authenticate\Contracts;

use App\Traits\EnumGetters;

enum AuthenticateMethod
{
    use EnumGetters;

    case Server;
    case Google;
}
