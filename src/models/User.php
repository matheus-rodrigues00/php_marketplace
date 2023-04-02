<?php

class User {
    private $id;
    private $name;
    private $password;
    private $token;
    private $email;

    public function __construct($id, $name = null, $password = null, $email = null, $token = null, ) {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->token = $token;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function getToken() {
        return $this->token;
    }

    public function getAll() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token,
        ];
    }
}
