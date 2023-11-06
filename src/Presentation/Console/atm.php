<?php

namespace App\Presentation\Console;

use App\Domain\Entities\User;
use App\Domain\Services\Account;
use App\Domain\Services\Atm;

try {
    // Create a user (you can modify the user details)
    $user = new User('John Doe', 'john@example.com', '5529889306');
    $user->register();

    echo "<pre>";

    // Create an account service and ATM service
    $account = new Account($user);
    $debitAccount = new Atm($account->debit);

    echo "<h3>Debito</h3>";
    echo "La cuenta Debito se abre inicialmente con $1100". PHP_EOL;
    echo "La cuenta Crédito se abre tomando $100 de la cuenta de Debito.". PHP_EOL;
    echo 'balance cuenta Debito: $' .$debitAccount->balance();

    echo PHP_EOL;
    echo PHP_EOL;

    echo 'retiro cuenta debito de $20:' . $debitAccount->withdraw(2000);

    echo PHP_EOL;

    echo 'balance después del retiro:'. '$' .$debitAccount->balance();

    echo PHP_EOL;
    echo PHP_EOL;

    echo 'ahorro cuenta debito de $10: '. $debitAccount->saving(1000);

    echo PHP_EOL;

    echo 'balance:'. '$' .$debitAccount->balance();

    echo PHP_EOL;

    echo "pagar cuenta de crédito de $100 " . $debitAccount->pay(10000);

   echo PHP_EOL;

    echo "<h3>Crédito</h3>";

    $creditAccount = new Atm($account->credit);

    echo 'balance cuenta Crédito: $' .$creditAccount->balance();

    echo PHP_EOL;

    echo 'retiro cuenta crédito de $10 - ' . $creditAccount->withdraw(1000);
    echo 'comisión de 5%:';

    echo PHP_EOL;
    echo PHP_EOL;

    echo 'balance cuenta Crédito: $' .$creditAccount->balance();


} catch (\Exception $exception) {
    echo "Error: {$exception->getMessage()}\n";
}
