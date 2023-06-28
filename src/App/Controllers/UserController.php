<?php

namespace App\Controllers;

use App\Repository\UserRepository;

class UserController extends BaseController
{

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function actionIndex(): bool|array
    {
        return $this->repository->all();
    }
}