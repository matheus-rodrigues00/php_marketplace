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
            $product = new Product($product_data['id'], $product_data['name'], $product_data['price']);
            $products[] = $product->getAll();
        }

        return $products;
    }

    public function create($name, $price) {
        $sql = "INSERT INTO products (name, price) VALUES (?, ?)";
        $params = [$name, $price];
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
        // echo json_encode($product_data);
        $product = new Product($product_data['id'], $product_data['name'], $product_data['price']);
        return $product->getAll();
    }

    public function update($id, $name, $price) {
        $sql = "UPDATE products SET name = ?, price = ? WHERE id = ?";
        $params = [$name, $price, $id];
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
