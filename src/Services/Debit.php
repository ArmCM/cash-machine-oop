<?php

namespace App\Services;

use App\Contracts\DebitAccount;
use App\Contracts\User;

class Debit implements DebitAccount
{
    public User $user;
    public static int $balance = 0;

    public function __construct(User $user)
    {
        $this->user = $user;
        self::$balance = self::INITIALIZE_BALANCE_AMOUNT - CreditAccount::OPEN_DISCOUNT;
        Credit::updateBalance(CreditAccount::OPEN_DISCOUNT);
    }

    public function withdraw($amount)
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

        self::updateBalance(self::$balance - $amount) . PHP_EOL;
    }

    public function balance()
    {
        return self::$balance;
    }

    public function saving($amount)
    {
        if ($amount >= self::MAX_AMOUNT_ABAILABLE) {
            throw new Exception('no se permiten transacciones superiores o igual a 1M');
        }

        if ($amount < self::MIN_AMOUNT_ABAILABLE) {
            throw new Exception('no se permiten transacciones menores a 1 centavo');
        }

        self::updateBalance(self::$balance + $amount);
    }

    public function charge()
    {
        // TODO: Implement charge() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    public static function updateBalance($newBalance)
    {
        return Debit::$balance = $newBalance;
    }
}