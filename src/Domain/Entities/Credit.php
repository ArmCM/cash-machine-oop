<?php

namespace App\Domain\Entities;

use App\Domain\Interfaces\CreditAccount;
use App\Infrastructure\Exceptions\AtmException;
use Exception;

class Credit implements CreditAccount
{
    public int $balance = self::EMPTY_BALANCE;

    public function __construct()
    {

    }

    /**
     * @throws Exception
     */
    public function withdraw($amount): void
    {
        if ($amount === 0) {
            throw new Exception('indica una cantidad mayor a cero.');
        }

        if ($amount > $this->balance) {
            throw new Exception('la cantidad es mayor al saldo disponible');
        }

        if ($this->balance === self::EMPTY_BALANCE) {
            throw new Exception('la cuenta esta vaciá no se puede retirar nada:');
        }

        $fee = $amount * self::FEE;

        $amount += $fee;

        $this->subtractOnBalance($amount);
    }

    public function balance(): int
    {
        return $this->balance;
    }

    public function charge()
    {
        // TODO: Implement charge() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    /**
     * @throws Exception
     */
    public function pay($amount): bool
    {
        if ($amount >= self::LIMIT_CREDIT) {
            throw new AtmException('no se puede abonar más del limite permitido');
        }

        if (self::LIMIT_CREDIT === 0) {
            throw new AtmException('has excedido el limite de la cuenta');
        }

        return $this->addOnBalance( $amount);
    }

    public function addOnBalance($newBalance): bool
    {
        return $this->balance += $newBalance;
    }

    public function subtractOnBalance($newBalance): bool
    {
        return $this->balance -= $newBalance;
    }

    public function fee()
    {
        // TODO: Implement fee() method.
    }
}
