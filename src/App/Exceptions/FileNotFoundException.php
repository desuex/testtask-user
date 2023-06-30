<?php

namespace App\Exceptions;

class FileNotFoundException extends HttpException
{
    protected int $httpCode = 404;
    protected $message = "Not Found";
}