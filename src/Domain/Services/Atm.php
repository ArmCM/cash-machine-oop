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
            return $this->account->balance()/100;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function withdraw($amount): void
    {
        try {
            $this->account->withDraw($amount);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function saving($amount): void
    {
        try {
            if ($this->account instanceof Credit) {
                throw new Exception('No se puede ahorrar en cuenta de crédito.');
            }

            $this->account->saving($amount);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function pay($amount): void
    {
        if ($this->account instanceof Debit) {
            try {
                $this->account->pay($amount);
            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }

        if ($this->account instanceof Credit) {

        }
    }
}
