<?php

namespace App\Contracts;

interface DebitAccount extends Transactions
{
    const INITIALIZE_BALANCE_AMOUNT = 110000;

    public function saving($amount);
}