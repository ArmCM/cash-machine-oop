<?php

namespace App\Contracts;

interface CreditAccount extends Transactions
{
    const FEE = 0.05;
    const OPEN_DISCOUNT = 1000;
    const LIMIT_CREDIT = 400000;

    public function fee();
    public static function pay($amount);
}