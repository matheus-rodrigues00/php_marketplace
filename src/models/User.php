<?php

class User {
    private $id;
    private $username;
    private $password;

    public function __construct($id, $username, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getAll() {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password
        ];
    }
}
