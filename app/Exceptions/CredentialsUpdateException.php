<?php

namespace App\Exceptions;

use Exception;

class CredentialsUpdateException extends Exception
{
    protected $message = "Cannot update user credentials";
}
