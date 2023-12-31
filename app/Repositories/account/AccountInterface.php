<?php

namespace App\Repositories\Account;

interface AccountInterface
{
    public function getDataAccountFirst($username);
    public function updateAccont($param = []);
}
