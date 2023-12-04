<?php

namespace App\Domain\Services;

use App\Domain\Entities\Credit;
use App\Domain\Entities\Debit;
use App\Infrastructure\Exceptions\AtmException;
use Exception;

class Atm
{
    public Debit | Credit $account;

    public function __construct(Debit | Credit $account)
    {
        $this->account = $account;
    }

    /**
     * @throws AtmException
     */
    public function balance(): float|int
    {
        try {
            return $this->account->balance() / 100;
        } catch (\Exception $exception) {
            throw new AtmException('Error al obtener el saldo', $exception->getCode(), $exception);
        }
    }

    public function withdraw($amount): void
    {
        try {
            if ($this->account instanceof Debit) {
                $this->account->withDraw($amount);
            }

            if ($this->account instanceof Credit) {
                $this->account->withDraw($amount);
            }
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

    /**
     * @throws Exception
     */
    public function pay($amount): void
    {
        if ($this->account instanceof Debit) {
            $this->account->pay($amount);
        }

        if ($this->account instanceof Credit) {
            //
        }
    }
}
