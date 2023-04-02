<?php

class SalesController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $sql = "SELECT * FROM sales";
        $sales = $this->db->select($sql);
        foreach ($sales as &$sale) {
            $sql = "SELECT * FROM sale_items WHERE sale_id = ?";
            $params = [$sale['id']];
            $sale_items = $this->db->select($sql, $params);
            $sale['sale_items'] = $sale_items;
        }
        return $sales;
    }

    public function create($user_id) {
        $sql = "INSERT INTO sales (total, total_tax, user_id) VALUES (0, 0, ?)";
        $params = [$user_id];
        $sale_id = $this->db->insert($sql, $params);

        return $this->show($sale_id);
    }

    public function showUserSales($user_id) {
        $sql = "SELECT * FROM sales WHERE user_id = ?";
        $params = [$user_id];
        $sales = $this->db->select($sql, $params);
        // also get the sale items for each sale
        foreach ($sales as &$sale) {
            $sale = $this->show($sale['id']);
        }
        usort($sales, function($a, $b) {
            return strcmp($a['created_at'], $b['created_at']);
        });
        return $sales;
    }

    public function show($id) {
        $sql = "SELECT * FROM sales WHERE id = ?";
        $params = [$id];
        $result = $this->db->select($sql, $params);
        if (count($result) == 0) {
            return null;
        }
        
        $data = $result[0];
        $sale = new Sale($data['id'],$data['created_at'], $data['total'], $data['total_tax']);
        $sql = "SELECT * FROM sale_items WHERE sale_id = ?";
        $params = [$id];
        $result = $this->db->select($sql, $params);
        $products = [];
        foreach ($result as $data) {
            $product = $this->db->select("SELECT * FROM products WHERE id = ?", [$data['product_id']])[0];
            $sale_object = [
                'sale_item_id' => $data['id'],
                'product' => $product['name'],
                'quantity' => $data['quantity'],
                'total' => $data['price'],
                'tax' => $data['tax'],
                'unit_price' => $product['price']
            ];
            $products[] = $sale_object;
        }
        // sort alphabetically
        usort($products, function($a, $b) {
            return strcmp($a['product'], $b['product']);
        });
        $response = [
            'id' => $sale->getId(),
            'total' => $sale->getTotal(),
            'total_tax' => $sale->getTotalTax(),
            'created_at' => $sale->getCreatedAt(),
            'products' => $products
        ];
        return $response;
    }

    public function addSaleItem($sale_id, $product_id, $quantity) {
        $sale = $this->db->select("SELECT * FROM sales WHERE id = ?", [$sale_id])[0];
        if ($sale == null || $quantity <= 0) {
            return [];
        }
    
        $product = $this->db->select("SELECT * FROM products WHERE id = ?", [$product_id])[0];
        if ($product == null) {
            return null;
        }
        $product_id = $product['id'];
        $product_price = $product['price'];

        $product_type_id = $product['product_type_id'];
        $product_type = $this->db->select("SELECT * FROM product_types WHERE id = ?", [$product_type_id])[0];

        $total_without_tax = $product_price * $quantity;
        $tax = $total_without_tax * ($product_type['tax_rate'] / 100);
        $total_with_tax = $total_without_tax + $tax;
        
        $sale_item = new SaleItem(null, $sale_id, $product_id, $quantity, $total_with_tax, $tax);
    
        $sql = "INSERT INTO sale_items (sale_id, product_id, quantity, price, tax) VALUES (?, ?, ?, ?, ?)";
        $params = [$sale_id, $product_id, $quantity, $total_with_tax, $tax];
        $this->db->insert($sql, $params);

        $sql = "UPDATE sales SET total = total + ?, total_tax = total_tax + ? WHERE id = ?";
        $params = [$total_with_tax, $tax, $sale_id];
        $this->db->update($sql, $params);
    
        return $sale_item->getAll();
    }

    public function update($sale_item_id, $quantity) {
        $sale_item = $this->db->select("SELECT * FROM sale_items WHERE id = ?", [$sale_item_id])[0];
        if ($sale_item == null) {
            return null;
        }
    
        $old_quantity = $sale_item['quantity'];
        $new_quantity = $quantity;
        if($new_quantity == $old_quantity) {
            $sale = $this->show($sale_item['sale_id']);
            return $sale;
        }
    
        $product = $this->db->select("SELECT * FROM products WHERE id = ?", [$sale_item['product_id']])[0];
        if ($product == null) {
            return null;
        }
        
        $product_price = $product['price'];
        $product_type_id = $product['product_type_id'];
        $product_type = $this->db->select("SELECT * FROM product_types WHERE id = ?", [$product_type_id])[0];
        // avoid floating point imprecisions:
        $product_price = round($product_price, 2);
        $product_type['tax_rate'] = round($product_type['tax_rate'], 2);
        $total_without_tax = $product_price * $new_quantity;
        $total_without_tax = round($total_without_tax, 2);
        $old_total_without_tax = $product_price * $old_quantity;
        $old_total_without_tax = round($old_total_without_tax, 2);
        $tax = $total_without_tax * ($product_type['tax_rate'] / 100);
        $tax = round($tax, 2);
        $old_tax = $old_total_without_tax * ($product_type['tax_rate'] / 100);
        $old_tax = round($old_tax, 2);
        $total_with_tax = $total_without_tax + $tax;
        $total_with_tax = round($total_with_tax, 2);
        $old_total_with_tax = $old_total_without_tax + $old_tax;
        $old_total_with_tax = round($old_total_with_tax, 2);
    
        $sql = "";
        $params = [];
    
        if ($new_quantity <= 0) {
            $sql = "DELETE FROM sale_items WHERE id = ?";
            $params = [$sale_item_id];
            $this->db->delete($sql, $params);
    
            $sql = "UPDATE sales SET total = total - ?, total_tax = total_tax - ? WHERE id = ?";
            $params = [$old_total_with_tax, $old_tax, $sale_item['sale_id']];
            $this->db->update($sql, $params);
        } else {
            $sql = "UPDATE sale_items SET quantity = ?, price = ?, tax = ? WHERE id = ?";
            $params = [$new_quantity, $total_with_tax, $tax, $sale_item_id];
            $this->db->update($sql, $params);
            if($new_quantity < $old_quantity) {
                $sql = "UPDATE sales SET total = total - ?, total_tax = total_tax - ? WHERE id = ?";
                $params = [$old_total_with_tax - $total_with_tax, $old_tax - $tax, $sale_item['sale_id']];
                $this->db->update($sql, $params);
            } else {
                $sql = "UPDATE sales SET total = total + ?, total_tax = total_tax + ? WHERE id = ?";
                $params = [$total_with_tax - $old_total_with_tax, $tax - $old_tax, $sale_item['sale_id']];
                $this->db->update($sql, $params);
            }
        }
    
        return $this->show($sale_item['sale_id']);
    }

    public function delete($sale_item_id) {
        $sale_item = $this->db->select("SELECT * FROM sale_items WHERE id = ?", [$sale_item_id])[0];
        if ($sale_item == null) {
            return null;
        }
    
        $sql = "DELETE FROM sale_items WHERE id = ?";
        $params = [$sale_item_id];
        $this->db->delete($sql, $params);
    
        $sale_id = $sale_item['sale_id'];
        $total_price_delta = -1 * $sale_item['price'];
        $total_tax_delta = -1 * $sale_item['tax'];
        $sql = "UPDATE sales SET total = total + ?, total_tax = total_tax + ? WHERE id = ?";
        $params = [$total_price_delta, $total_tax_delta, $sale_id];
        $this->db->update($sql, $params);
    
        return $sale_item;
    }
}
