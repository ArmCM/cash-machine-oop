<?php

namespace App\Domain\Entities;

use App\Domain\Interfaces\CreditAccount;
use App\Domain\Interfaces\DebitAccount;
use App\Domain\Interfaces\UserInterface;
use Exception;

class Debit implements DebitAccount
{
    public UserInterface $user;
    public int $balance = 0;
    public Credit $credit;

    public function __construct(UserInterface $user, Credit $credit)
    {
        $this->user = $user;
        $this->balance = self::INITIALIZE_BALANCE_AMOUNT - CreditAccount::OPEN_DISCOUNT;
        $this->credit = $credit;

        $this->openCreditAccount();
    }

    public function withdraw($amount): void
    {
        if ($amount === 0) {
            throw new Exception('indica una cantidad mayor a cero.');
        }

        if ($amount > $this->balance) {
            throw new Exception('la cantidad es mayor al saldo disponible');
        }

        if ($this->balance === self::EMPTY_BALANCE) {
            throw new Exception('la cuenta esta vacia no se puede retirar nada:');
        }

        $this->updateBalance($this->balance - $amount) . PHP_EOL;
    }

    public function balance(): int
    {
        return $this->balance;
    }

    public function saving($amount): void
    {
        if ($amount >= self::MAX_AMOUNT_AVAILABLE) {
            throw new Exception('no se permiten transacciones superiores o igual a 1M');
        }

        if ($amount < self::MIN_AMOUNT_AVAILABLE) {
            throw new Exception('no se permiten transacciones menores a 1 centavo');
        }

        self::updateBalance($this->balance + $amount);
    }

    public function charge()
    {
        // TODO: Implement charge() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    public function updateBalance($newBalance)
    {
        return $this->balance += $newBalance;
    }

    public function openCreditAccount(): void
    {
        $this->credit->addOnBalance(CreditAccount::OPEN_DISCOUNT);
    }

    public function pay($amount): void
    {
        try {
            $this->credit->pay($amount);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
