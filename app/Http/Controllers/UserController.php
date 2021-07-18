<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Exceptions\ApiException;

class UserController extends Controller
{
    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    public function all_users()
    {
        $users = $this->user_repository->get_all_users();

        if(empty($users)){
            throw new ApiException('client', 404, '', 'No User Found!');
        }
        return $this->jsonResponse($users, 'Users Found!');
    }
}
