<?php

namespace App\Domain\Interfaces;

interface CreditAccount extends Transactions
{
    const FEE = 0.05;
    const OPEN_DISCOUNT = 10000;
    const LIMIT_CREDIT = 400000;

    public function fee();
    public function pay($amount);
}
