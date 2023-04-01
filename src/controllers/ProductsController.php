<?php

class ProductController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $sql = "SELECT * FROM products";
        $result = $this->db->select($sql);
        $products = [];

        foreach ($result as $product_data) {
            $product = new Product($product_data['id'], $product_data['name'], $product_data['price'], $product_data['product_type_id']);
            $products[] = $product->getAll();
        }

        return $products;
    }

    public function create($name, $price, $product_type_id) {
        $sql = "INSERT INTO products (name, price, product_type_id) VALUES (?, ?, ?)";
        $params = [$name, $price, $product_type_id];
        $product_id = $this->db->insert($sql, $params);
        return $this->show($product_id);
    }

    public function show($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $params = [$id];
        $result = $this->db->select($sql, $params);
        if (count($result) == 0) {
            return null;
        }
        
        $product_data = $result[0];
        $product = new Product($product_data['id'], $product_data['name'], $product_data['price'], $product_data['product_type_id']);
        return $product->getAll();
    }

    public function update($id, $name = null, $price = null, $product_type_id = null) {
        $params = [];
        $set_clauses = [];
    
        if ($name !== null) {
            $set_clauses[] = "name = ?";
            $params[] = $name;
        }
    
        if ($price !== null) {
            $set_clauses[] = "price = ?";
            $params[] = $price;
        }
    
        if ($product_type_id !== null) {
            $set_clauses[] = "product_type_id = ?";
            $params[] = $product_type_id;
        }
    
        if (empty($set_clauses)) {
            return null;
        }
    
        $set_clause = implode(", ", $set_clauses);
    
        $sql = "UPDATE products SET {$set_clause} WHERE id = ?";
        $params[] = $id;
    
        $row_count = $this->db->update($sql, $params);
    
        if ($row_count == 0) {
            return null;
        }
    
        return $this->show($id);
    }    

    public function destroy($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        $params = [$id];
        $row_count = $this->db->delete($sql, $params);

        if ($row_count == 0) {
            return null;
        }

        return true;
    }
}
