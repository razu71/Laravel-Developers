<?php

namespace App\Repository\User;

use App\Contracts\Repositories\User\UserRepoContracts;
use App\Models\User;

class UserRepositories implements UserRepoContracts {
    private $model;

    public function __construct(User $user) {
        $this->model = $user;
    }

    public function create($data) {
        return $this->model->create($data);
    }

    public function update($condition, $data) {
        return $this->model->where($condition)->update($data);
    }

    public function getUser($condition) {
        return $this->model->where($condition)->first();
    }
}
