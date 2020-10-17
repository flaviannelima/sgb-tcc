<?php

namespace Tests\Builders;

use App\User;

class UserBuilder{
    protected $atributos = [];
    
    public function setName($name)
    {
        $this->atributos['name'] = $name;
        return $this;
    }
    public function setEmail($email)
    {
        $this->atributos['email'] = $email;
        return $this;
    }
    public function setPassword($password)
    {
        $this->atributos['password'] = $password;
        return $this;
    }
    public function create($quantidade = null)
    {
        return factory(User::class,$quantidade)->create($this->atributos);
    }

    public function make($quantidade = null)
    {
        
        return factory(User::class,$quantidade)->make($this->atributos);
    }
}