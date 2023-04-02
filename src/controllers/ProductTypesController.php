<?php

class ProductTypesController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $sql = "SELECT * FROM product_types";
        $result = $this->db->select($sql);
        $product_types = [];

        foreach ($result as $data) {
            $product_type = new ProductType($data['id'], $data['name'], $data['tax_rate']);
            $product_types[] = $product_type->getAll();
        }
        usort($product_types, function($a, $b) {
            return strcmp(strtolower($a['name']), strtolower($b['name']));
        });

        return $product_types;
    }

    public function create($name, $tax_rate) {
        $sql = "INSERT INTO product_types (name, tax_rate) VALUES (?, ?)";
        $params = [$name, $tax_rate];
        $product_type_id = $this->db->insert($sql, $params);
        return $this->show($product_type_id);
    }

    public function show($id) {
        $sql = "SELECT * FROM product_types WHERE id = ?";
        $params = [$id];
        $result = $this->db->select($sql, $params);
        if (count($result) == 0) {
            return null;
        }
        
        $data = $result[0];
        $product_type = new ProductType($data['id'], $data['name'], $data['tax_rate']);
        return $product_type->getAll();
    }

    public function update($id, $name = null, $tax_rate = null) {
        $updates = [];
    
        if (!is_null($name)) {
            $updates[] = "name = '{$name}'";
        }
    
        if (!is_null($tax_rate)) {
            $updates[] = "tax_rate = '{$tax_rate}'";
        }
    
        if (empty($updates)) {
            return null;
        }
    
        $updates_str = implode(', ', $updates);
    
        $sql = "UPDATE product_types SET {$updates_str} WHERE id = ?";
        $params = [$id];
        $row_count = $this->db->update($sql, $params);
    
        if ($row_count == 0) {
            return null;
        }
    
        return $this->show($id);
    }
    

    public function destroy($id) {
        $sql = "DELETE FROM product_types WHERE id = ?";
        $params = [$id];
        $row_count = $this->db->delete($sql, $params);

        if ($row_count == 0) {
            return null;
        }

        return true;
    }
}
