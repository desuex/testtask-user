<?php

namespace App\Exceptions;

abstract class HttpException extends \Exception
{
    protected int $httpCode = 500;
    protected $message = "Server Error";

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}