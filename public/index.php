<?php

/**
 * @property int $balance;
 * @property string $user;
 */
interface Transactions {
    const WITHOUT_BALANCE = 0;
    const MAX_AMOUNT_ABAILABLE = 100000000;
    const MIN_AMOUNT_ABAILABLE = 10;

    public function balance();
    public function charge();
    public function transfer();
    public function withDraw($amount);
}

interface CreditAccount extends Transactions {
    const FEE = 0.5;
    const OPEN_DISCOUNT = 10000;
    const LIMIT_CREDIT = 400000;

    public function pay($amount);
    public function interesFree();
}

interface DebitAccount extends Transactions {
    public function saving($amount);
}

class Credit implements CreditAccount {
    public static $balance = 0;
    public $user = '';

    public function __construct($balance, $user)
    {
        Credit::$balance = $balance - 10000;
        $this->user = $user;
    }

    public function balance()
    {
        return self::$balance;
    }

    public function interesFree()
    {
        // TODO: Implement interesFree() method.
    }

    public static function updateBalance($newBalance)
    {
        return Credit::$balance = $newBalance;
    }

    public function pay($amount)
    {
        if ($amount >= self::LIMIT_CREDIT) {
            throw new Exception('no se puede abonar mas del limite permitido');
        }

        if (self::LIMIT_CREDIT === 0) {
            throw new Exception('has excedido el limite de la cuenta');
        }

        return self::updateBalance(self::$balance + $amount);
    }

    public function charge()
    {
        // TODO: Implement charge() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    public function withdraw($amount)
    {
        if ($amount === 0) {
            throw new Exception('indica una cantidad mayor a cero.');
        }

        if ($amount > self::$balance) {
            throw new Exception('la cantidad es mayor al saldo disponible');
        }

        if (self::$balance === self::WITHOUT_BALANCE) {
            throw new Exception('la cuenta esta vacia no se puede retirar nada:');
        }

        $fee = $amount * self::FEE;
        $amount += $fee;

        return self::updateBalance(self::$balance - $amount);
    }
}

class Debit implements DebitAccount {
    public static $balance = 0;
    public $user = '';

    public function __construct($balance, $user)
    {
        Debit::$balance = $balance;
        $this->user = $user;
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

    public static function updateBalance($newBalance)
    {
        return Debit::$balance = $newBalance;
    }

    public function charge()
    {
        // TODO: Implement charge() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    public function withdraw($amount)
    {
        if ($amount === 0) {
            throw new Exception('indica una cantidad mayor a cero.');
        }

        if ($amount > self::$balance) {
            throw new Exception('la cantidad es mayor al saldo disponible');
        }

        if (self::$balance === self::WITHOUT_BALANCE) {
            throw new Exception('la cuenta esta vacia no se puede retirar nada:');
        }

        return self::updateBalance(self::$balance - $amount) . PHP_EOL;
    }
}

class Account {
    public $accounts = [];

    public function __construct($type, $balance, $user)
    {
        $this->create($type, $user, $balance);
    }

    public function create($type, $user, $balance)
    {
        if ($type !== 'debit') {
            return 'solo se pueden crear cuentas de tipo debito.';
        }

        $this->accounts['debit'] = new Debit($balance, $user);
        $this->accounts['credit'] = new Credit($balance, $user);

        return;
    }
}

class User {
    public $id = '';
    public $account;
    public $registered = false;

    public function __construct()
    {
        $this->id = uniqid();
    }

    public function register()
    {
        $this->registered = true;
        $this->account = new Account('debit', 110000, $this->id);
    }

    public function showAccount()
    {
        return $this->account ?? 'no tienes cuenta';
    }
}

$user = new User('john doe', 'john@gmail.com', '5529889306');
$user->register();

class Atm {
    public $account = [];

    public function __construct($user)
    {
        if (array_key_exists('debit', $user->account->accounts)) {
            $this->account['debit'] = $user->account->accounts['debit'];
        }

        if (array_key_exists('credit', $user->account->accounts)) {
            $this->account['credit'] = $user->account->accounts['credit'];
        }
    }

    public function selectTypeAccount($type)
    {
        return $this->account[$type];
    }
}

$atm = (new Atm($user))->selectTypeAccount('debit');

echo "<pre>"; var_dump($atm);

print 'balance cuenta debito:' . $atm->balance();

print PHP_EOL;

print 'retiro cuenta debito de 2000:' . $atm->withdraw(2000);

print PHP_EOL;

print 'deposito de 100 en ahorros:' . $atm->saving(100);

print PHP_EOL;

print 'balance cuenta debito:' . $atm->balance();

print '<br>';


$atmCredit = (new Atm($user))->selectTypeAccount('credit');

print PHP_EOL;

print 'balance cuenta credito:' . $atmCredit->balance();

print PHP_EOL;

print 'retiro cuenta debito de 2000 (+ 0.5%) :' . $atmCredit->withdraw(2000);

print PHP_EOL;

print 'pago cuenta de credito:' . $atmCredit->pay(1000);

print PHP_EOL;

print 'balance cuenta credito:' . $atmCredit->balance();