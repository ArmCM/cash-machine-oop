<?php

namespace App\Domain\Entities;

use App\Domain\Interfaces\UserInterface;

class User extends BaseUser implements UserInterface {

    public function __construct($name, $email, $phone)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function register(): bool
    {
       return $this->registered = true;
    }

    public function statusRegister(): bool
    {
        return $this->registered;
    }

    public function isAdmin()
    {
        // TODO: Implement isAdmin() method.
    }
}