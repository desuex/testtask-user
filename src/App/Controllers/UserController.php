<?php

namespace App\Controllers;

use App\Models\User;
use App\Repository\UserRepository;
use App\Request;

class UserController extends BaseController
{

    private UserRepository $repository;
    private Request $request;

    public function __construct(UserRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request = $request;
    }

    public function index(): bool|array
    {
        return $this->repository->all();
    }

    public function show($id): ?User
    {
        return new User(['id' => $id, 'name' => 'John']);
    }
}