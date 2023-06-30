<?php

namespace App\Controllers;

use App\Exceptions\ValidationException;
use App\Models\User;
use App\Request;
use App\Services\UserService;

class UserController extends BaseController
{

    private UserService $service;
    private Request $request;

    public function __construct(UserService $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
    }

    public function index(): bool|array
    {
        return $this->service->getAllUsers(true);
    }

    public function show($id): ?User
    {
        return $this->service->getUser($id);
    }

    public function create(): User|array|null
    {
        $data = $this->request->getJsonParams();
        try {
            return $this->service->createUser($data);
        } catch (ValidationException $exception) {
            return [
                "error" => $exception->getMessage(),
                "data" => $exception->getErrors()
            ];
        }
    }
}