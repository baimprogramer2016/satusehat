<?php

namespace App\Repositories\Account;

use App\Models\User;

class AccountRepository implements AccountInterface
{
    private $model;
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataAccountFirst($username)
    {
        return $this->model->where('username', $username)->orderBy('id', 'ASC')->first();
    }

    public function updateAccont($param = [])
    {
        $data = $this->model->where('username', $param['username'])->first();
        $data->name = $param['name'];
        $data->email = $param['email'];
        if (!empty($param['pass_baru'])) {
            $data->password = $param['pass_baru'];
        }
        $data->update();

        return $data;
    }
}
