<?php
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsersController {
    private $db;
    private $key; 

    public function __construct($db) {
        $this->db = $db;
        $this->key = "random_secret_key_that_in_a_real_I_would_put_in_a_config_file";
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
        $params = [$name, $email, $password];
        $user_id = $this->db->insert($sql, $params);

        $sql = "INSERT INTO sales (total, total_tax, user_id) VALUES (0, 0, ?)";
        $params = [$user_id];
        $sale_id = $this->db->insert($sql, $params);

        return $this->show($user_id);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $params = [$email, $password];
        $result = $this->db->select($sql, $params);
        if (count($result) == 0) {
            return null;
        }
        
        $payload = array(
            "sub" => $result[0]['id'],
            "name" => $result[0]['name'],
            "iat" => time(),
            "exp" => time() + 30 * 60 * 60 * 24 * 365
        );
        $token = JWT::encode($payload, $this->key, 'HS256');
        $user = new User($result[0]['id'], $result[0]['name'], $result[0]['password'], $result[0]['email'], $token);
        return $user->getAll();
    }    

    public function showMe() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return null;
        }
        
        $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            $user_id = $decoded->sub;
            return $this->show($user_id);
        } catch (Exception $e) {
            return null;
        }
    }

    public function logout() {
        return null;
    }

    public function show($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = [$id];
        $result = $this->db->select($sql, $params);
        if (count($result) == 0) {
            return null;
        }
        
        $data = $result[0];
        $user = new User($data['id'], $data['name'], $data['password'], $data['email']);
        return $user->getAll();
    }

    // get by token

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
