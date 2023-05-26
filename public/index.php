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
    const OPEN_DISCOUNT = 10000;
    const LIMIT_CREDIT = 400000;

    public function pay($amount);
    public function interesFree();
}

interface DebitAccount extends Transactions {
    const INITIALIZE_BALANCE = 110000;

    public function saving($amount);
}

class Credit implements CreditAccount {
    public static $balance = self::EMPTY_BALANCE;
    public $user = '';

    public function __construct(IUser $user)
    {
        self::$balance = Debit::$balance - 10000;
        $this->user = $user;
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

    public static function updateBalance($newBalance)
    {
        return Credit::$balance = $newBalance;
    }
}

class Debit implements DebitAccount {
    public $user;
    public static $balance = 0;

    public function __construct(IUser $user)
    {
        $this->user = $user;
        self::$balance = self::INITIALIZE_BALANCE;
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

        return self::updateBalance(self::$balance - $amount) . PHP_EOL;
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

/**
 * @TODO si existe un guest user deberia implemtar una interface user para que no choquen con estos metodos
 */
class User extends BaseUser implements IUser {
    public $id = '';
    public $name;
    public $email;
    public $phone;

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
}

$user = new User('john doe', 'john@gmail.com', '5529889306');
$user->register();
print '<pre>';var_dump('registered User:', $user);

$account = new Account($user);
echo "<pre>"; var_dump('Account:', $account);
// object value != entidad

//print '<br>';
//
//$admin = new AdminUser('Jhon Dos');
//$adminAccount = new Account($admin);
//echo "<pre>"; var_dump('Admin Account', $adminAccount);
//
$atmDebit = new Atm($account->debit);
echo "<pre>"; var_dump('ATM-cuenta', $atmDebit);

//print 'retiro cuenta debito de 2000:'. $atmDebit->account->withDraw(2000);
//
//print PHP_EOL;
//
//print 'ahorro cuenta debito de 100:'. $atmDebit->account->saving(100);
//
//print PHP_EOL;
//
//print 'balance cuenta debito:'. $atmDebit->account->balance();
//
//print '<br>';

//$account = new Account($user);
//$atmCredit = new Atm($account->credit);
//
//print PHP_EOL;
//
//print 'balance cuenta credito:' . $atmCredit->account->balance();
//
//print PHP_EOL;
//
//print 'retiro cuenta debito de 2000 (+ 0.05%) :' . $atmCredit->account->withdraw(2000);
//
//print PHP_EOL;
//
//print 'pago cuenta credito de 1000:' . $atmCredit->account->pay(1000);
//
//print PHP_EOL;
//
//print 'balance cuenta credito:' . $atmCredit->account->balance();