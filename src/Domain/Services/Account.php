<?php

namespace App\Domain\Services;

use App\Domain\Entities\Credit;
use App\Domain\Entities\Debit;
use App\Domain\Interfaces\UserInterface;
use Exception;

class Account
{
    public Debit $debit;
    public Credit $credit;

    public function __construct(UserInterface $user)
    {
        if (! $user->statusRegister()) {
            throw new Exception("El usuario {$user->name} no esta registrado, es necesario registralo para poder acceder a una cuenta:");
        }

        $user->isAdmin();

        $this->debit = new Debit($user);
        $this->credit = new Credit($user);
    }
}