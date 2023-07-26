<?php

namespace App\Domain\Entities;

use App\Domain\Interfaces\CreditAccount;
use Exception;

class Credit implements CreditAccount
{
    public static int $balance = self::EMPTY_BALANCE;

    public function __construct(){}

    public function withdraw($amount): bool
    {
        if ($amount === 0) {
            throw new Exception('indica una cantidad mayor a cero.');
        }

        if ($amount > self::$balance) {
            throw new Exception('la cantidad es mayor al saldo disponible');
        }

        if (self::$balance === self::EMPTY_BALANCE) {
            throw new Exception('la cuenta esta vacia no se puede retirar nada:');
        }

        $fee = $amount * self::FEE;
        $amount += $fee;

        return self::updateBalance(self::$balance - $amount);
    }

    public function balance(): int
    {
        return self::$balance;
    }

    public function charge()
    {
        // TODO: Implement charge() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    public static function pay($amount): bool
    {
        if ($amount >= self::LIMIT_CREDIT) {
            throw new Exception('no se puede abonar mas del limite permitido');
        }

        if (self::LIMIT_CREDIT === 0) {
            throw new Exception('has excedido el limite de la cuenta');
        }

        return self::updateBalance(self::$balance + $amount);
    }

    public static function updateBalance($newBalance): bool
    {
        Credit::$balance = $newBalance;

        return true;
    }

    public function fee()
    {
        // TODO: Implement fee() method.
    }
}