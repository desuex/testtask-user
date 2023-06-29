<?php

namespace App\Exceptions;

abstract class HttpException extends \Exception
{
    protected $httpCode = null;
}