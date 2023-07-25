<?php
interface IUser {
    public function isAdmin();
}

/**
 * @property int $balance;
 * @property string $user;
 */
interface Transactions {
    const EMPTY_BALANCE = 0;
    const MAX_AMOUNT_ABAILABLE = 100000000;
    const MIN_AMOUNT_ABAILABLE = 10;

    public function balance();
    public function charge();
    public function transfer();
    public function withDraw($amount);
}

interface CreditAccount extends Transactions {
    const FEE = 0.05;
    const OPEN_DISCOUNT = 1000;
    const LIMIT_CREDIT = 400000;

    public function interesFree();
    public static function pay($amount);
}

interface DebitAccount extends Transactions {
    const INITIALIZE_BALANCE_AMOUNT = 110000;

    public function saving($amount);
}

class Credit implements CreditAccount {
    public static $balance = self::EMPTY_BALANCE;

    public function __construct(IUser $user)
    {
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

        $fee = $amount * self::FEE;
        $amount += $fee;

        return self::updateBalance(self::$balance - $amount);
    }

    public function balance()
    {
        return self::$balance;
    }

    public function interesFree()
    {
        // TODO: Implement interesFree() method.
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
}

class Debit implements DebitAccount {
    public $user;
    public static $balance = 0;

    public function __construct(IUser $user)
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

abstract class BaseUser {
    public $id;
    public $name;
    public $email;
    public $phone;
    protected $registered = false;

    public function register()
    {
        $this->registered = true;
    }

    public function statusRegister()
    {
        return $this->registered;
    }
}

class User extends BaseUser implements IUser {

    public function __construct($name, $email, $phone)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function isAdmin()
    {
        return false;
    }
}

class AdminUser extends BaseUser implements IUser {

    public $name;
    public bool $isAdmin = true;

    public function __construct($name)
    {
        $this->name = $name;
        parent::register();
    }

    public function isAdmin()
    {
        return $this->isAdmin;
    }

    public function isRegistered()
    {

    }
}

class Account {
    public Debit $debit;
    public Credit $credit;

    // en ves de pasar una clase en concreto puede ser una Interface IUser
    // que pueda  mutar sin alterar all lo demas
    public function __construct(IUser $user)
    {
        if (! $user->statusRegister()) {
            throw new Exception("El usuario {$user->name} no esta registrado, es neecsario registralo para poder acceder a una cuenta:");
        }

        $user->isAdmin();

        $this->debit = new Debit($user);
        $this->credit = new Credit($user);
    }
}

class Atm {
    public Debit | Credit $account;

    public function __construct(Debit | Credit $account)
    {
        $this->account = $account;
    }

    public function balance()
    {
        try {
            return $this->account->balance();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function withdraw($amount)
    {
        try {
            return $this->account->withDraw($amount);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function saving($amount): void
    {
        try {
            if ($this->account instanceof Credit) {
                throw new Exception('No se puede ahorrar en cuenta de credito.');
            }

            $this->account->saving($amount);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function pay($amount)
    {
        if ($this->account instanceof Credit) {
            throw new Exception('No se puedes pagar una tarjeta de credito con una cuenta de credito.');
        }

        if (Credit::pay($amount)) {
            Debit::updateBalance(Debit::$balance - $amount);
        }
    }
}

$user = new User('john doe', 'john@gmail.com', '5529889306');
$user->register();
echo '<pre>';
echo '<pre>';var_dump('registered User:', $user);

try {
    $account = new Account($user);
    //echo "<pre>"; var_dump('Account:', $account->credit->disccount());

    $atmDebit = new Atm($account->debit);

    echo 'balance cuenta Debito: '. $atmDebit->balance();

    echo PHP_EOL;

    echo 'retiro cuenta debito de $20: '. $atmDebit->withdraw(2000);

    echo PHP_EOL;

    echo 'balance despues del retiro de $20 cuenta Debito: '. $atmDebit->balance();

    echo PHP_EOL;

    echo 'ahorro cuenta debito de $10: '. $atmDebit->saving(1000);

    echo PHP_EOL;

    echo 'balance despues de ahorrar $10 cuenta Debito: '. $atmDebit->balance();

    echo PHP_EOL;
    echo PHP_EOL;

    echo 'pago a cuenta de credito de $10 ' . $atmDebit->pay(1000);

    echo PHP_EOL;

    echo 'balance cuenta Debito: '. $atmDebit->balance();

    echo PHP_EOL;
    echo PHP_EOL;

    $atmCredit = new Atm($account->credit);

    echo 'balance cuenta Credito: '. $atmCredit->balance();

    echo PHP_EOL;

    echo 'retiro cuenta credito de $10, comisión de 5% ~ 0.5:'. $atmCredit->withDraw(1000);

    echo PHP_EOL;

    echo 'balance cuenta credito: ' . $atmCredit->account->balance();
} catch (\Exception $exception) {
    echo $exception->getMessage();
}