<?php

namespace App\Presentation\Console;

use App\Domain\Entities\User;
use App\Domain\Services\Account;
use App\Domain\Services\Atm;

try {
    // Create a user (you can modify the user details)
    $user = new User('John Doe', 'john@example.com', '5529889306');
    $user->register();

    echo "<pre>"; var_dump($user);

    // Create a debit account service and ATM service
    $account = new Account($user);
    $debitAtmService = new Atm($account->debit);

    echo 'balance cuenta Debito: '. $debitAtmService->balance();

    echo PHP_EOL;

    echo 'retiro cuenta debito de $20: '. $debitAtmService->withdraw(2000);

    echo PHP_EOL;

    echo 'balance despues del retiro de $20 cuenta Debito: '. $debitAtmService->balance();

    echo PHP_EOL;

    echo 'ahorro cuenta debito de $10: '. $debitAtmService->saving(1000);

    echo PHP_EOL;

    echo 'balance despues de ahorrar $10 cuenta Debito: '. $debitAtmService->balance();

    echo PHP_EOL;
    echo PHP_EOL;

    echo 'pago a cuenta de credito de $10 ' . $debitAtmService->pay(1000);

    echo PHP_EOL;

    echo 'balance cuenta Debito: '. $debitAtmService->balance();

    echo PHP_EOL;
    echo PHP_EOL;

} catch (\Exception $exception) {
    echo "Error: {$exception->getMessage()}\n";
}
