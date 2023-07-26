<?php

namespace App\Entities;

class User {
    public string $id;
    public string $name;
    public string $email;
    public string $phone;
    protected bool $registered = false;

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
}