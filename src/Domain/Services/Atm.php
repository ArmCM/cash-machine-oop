<?php

namespace App\Domain\Services;

use App\Domain\Entities\Credit;
use App\Domain\Entities\Debit;
use Exception;

class Atm
{
    public Debit | Credit $account;

    public function __construct(Debit | Credit $account)
    {
        $this->account = $account;
    }

    public function balance()
    {
        try {
            return $this->account->balance();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function withdraw($amount)
    {
        try {
            return $this->account->withDraw($amount);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function saving($amount): void
    {
        try {
            if ($this->account instanceof Credit) {
                throw new Exception('No se puede ahorrar en cuenta de credito.');
            }

            $this->account->saving($amount);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function pay($amount)
    {
        if ($this->account instanceof Credit) {
            throw new Exception('No se puedes pagar una tarjeta de credito con una cuenta de credito.');
        }

        if (Credit::pay($amount)) {
            Debit::updateBalance(Debit::$balance - $amount);
        }
    }
}