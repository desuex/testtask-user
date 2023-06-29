<?php

namespace App\Controllers;

class IndexController extends BaseController
{
    public function index(): string
    {
        return "Hello, World! From App\Controllers\IndexController@index";
    }
}