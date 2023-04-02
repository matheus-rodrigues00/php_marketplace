<?php

class SalesController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $sql = "SELECT * FROM sales";
        $sales = $this->db->select($sql);
        // also get the sale items for each sale
        foreach ($sales as &$sale) {
            $sql = "SELECT * FROM sale_items WHERE sale_id = ?";
            $params = [$sale['id']];
            $sale_items = $this->db->select($sql, $params);
            $sale['sale_items'] = $sale_items;
        }
        return $sales;
    }

    public function create($sale_items = []) {
        // Calculate total price and tax for the sale
        $total_price = 0;
        $total_tax = 0;
        foreach ($sale_items as $sale_item) {
            $product = $this->db->select("SELECT * FROM products WHERE id = ?", [$sale_item['product_id']])[0];
            $product_type = $this->db->select("SELECT * FROM product_types WHERE id = ?", [$product['product_type_id']])[0];
            $price = $product['price'];
            $quantity = $sale_item['quantity'];
            $item_total_price = $price * $quantity;
            $item_total_tax = $item_total_price * ($product_type['tax_rate'] / 100);
            $total_price += $item_total_price;
            $total_tax += $item_total_tax;
        }

        // Save the sale
        $sql = "INSERT INTO sales (total, total_tax) VALUES (?, ?)";
        $params = [$total_price, $total_tax];
        $sale_id = $this->db->insert($sql, $params);

        // Save the sale items
        foreach ($sale_items as $sale_item) {
            $product_id = $sale_item['product_id'];
            $quantity = $sale_item['quantity'];
            $sql = "INSERT INTO sale_items (sale_id, product_id, quantity) VALUES (?, ?, ?)";
            $params = [$sale_id, $product_id, $quantity];
            $this->db->insert($sql, $params);
        }

        return $this->show($sale_id);
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
        $sale_item = $this->db->select("SELECT * FROM sale_items WHERE id = ?", [$sale_item_id]);
        if (count($sale_item) == 0) {
            return null;
        } else {
            $sale_item = $sale_item[0];
        }

        if($quantity <= 0) {
            $this->delete($sale_item_id);
            return [];
        }
    
        $product = $this->db->select("SELECT * FROM products WHERE id = ?", [$sale_item['product_id']])[0];
        $product_type = $this->db->select("SELECT * FROM product_types WHERE id = ?", [$product['product_type_id']])[0];
        $price = $product['price'];
        $old_quantity = $sale_item['quantity'];
        $item_total_price = $price * $quantity;
        $item_total_tax = $item_total_price * ($product_type['tax_rate'] / 100);
    
        $sql = "UPDATE sale_items SET quantity = ?, price = ?, tax = ? WHERE id = ?";
        $params = [$quantity, $item_total_price, $item_total_tax, $sale_item_id];
        $this->db->update($sql, $params);
    
        $sale_id = $sale_item['sale_id'];
        $total_price_delta = ($item_total_price - ($price * $old_quantity));
        $total_tax_delta = ($item_total_tax - ($sale_item['tax']));
        $sql = "UPDATE sales SET total = total + ?, total_tax = total_tax + ? WHERE id = ?";
        $params = [$total_price_delta, $total_tax_delta, $sale_id];
        $this->db->update($sql, $params);
    
        $updated_sale_item = $this->db->select("SELECT * FROM sale_items WHERE id = ?", [$sale_item_id])[0];
        return $updated_sale_item;
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
