<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get_all_users()
    {
        return $this->user->all();
    }

    public function get_user($id)
    {
        return $this->user->find($id);
    }
}