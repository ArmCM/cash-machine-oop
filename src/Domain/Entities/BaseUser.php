<?php

namespace App\Domain\Entities;

class BaseUser
{
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