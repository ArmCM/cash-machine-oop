<?php

namespace App\Domain\Interfaces;

interface Transactions
{
    const EMPTY_BALANCE = 0;
    const MAX_AMOUNT_AVAILABLE = 100000000;
    const MIN_AMOUNT_AVAILABLE = 10;

    public function balance();
    public function charge();
    public function transfer();
    public function withDraw($amount);
}