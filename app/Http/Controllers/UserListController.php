<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserListController extends Controller
{
    public function showList(UserService $service)
    {
        return UserResource::collection($service->listUsers());
    }
}
