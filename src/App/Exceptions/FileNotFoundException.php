<?php

namespace App\Exceptions;

class FileNotFoundException extends HttpException
{
    protected $httpCode = 404;
    protected $message = "Not Found";
}