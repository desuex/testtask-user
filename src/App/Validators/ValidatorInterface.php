<?php

namespace App\Validators;

interface ValidatorInterface
{
    public function validate($input):array;
}