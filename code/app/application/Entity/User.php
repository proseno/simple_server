<?php

namespace Entity;

class User
{
    private string $login;
    private string $password;

    public function __construct(
        string $login,
        string $password
    ) {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
}