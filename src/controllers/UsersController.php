<?php

class UsersController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $sql = "SELECT * FROM users";
        $result = $this->db->select($sql);
        $users = [];

        foreach ($result as $data) {
            $user = new User($data['id'], $data['name'], $data['password']);
            $users[] = $user->getAll();
        }

        return $users;
    }

    public function create($name, $email, $password) {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $params = [$name, $password, $email];
        $user_id = $this->db->insert($sql, $params);
        return $this->show($user_id);
    }

    public function show($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = [$id];
        $result = $this->db->select($sql, $params);
        if (count($result) == 0) {
            return null;
        }
        
        $data = $result[0];
        $user = new User($data['id'], $data['name'], $data['password']);
        return $user->getAll();
    }

    public function update($id, $name = null, $password = null) {
        $updates = [];
    
        if (!is_null($name)) {
            $updates[] = "name = '{$name}'";
        }
    
        if (!is_null($password)) {
            $updates[] = "password = '{$password}'";
        }
    
        if (empty($updates)) {
            return null;
        }
    
        $updates_str = implode(', ', $updates);
    
        $sql = "UPDATE users SET {$updates_str} WHERE id = ?";
        $params = [$id];
        $row_count = $this->db->update($sql, $params);
    
        if ($row_count == 0) {
            return null;
        }
    
        return $this->show($id);
    }

    public function destroy($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $params = [$id];
        $row_count = $this->db->delete($sql, $params);

        if ($row_count == 0) {
            return null;
        }

        return true;
    }
}
