<?php

namespace App\Contracts\Repositories\User;

interface UserRepoContracts {
    public function update($condition, $data);

    public function create($data);

    public function getUser($condition);
}
