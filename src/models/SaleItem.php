<?php
class SaleItem {
    private $id;
    private $sale_id;
    private $product_id;
    private $quantity;
    private $price;
    private $tax;

    public function __construct($id, $sale_id = null, $product_id = null, $quantity = null, $price = null, $tax = null) {
        $this->id = $id;
        $this->sale_id = $sale_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->tax = $tax;
    }

    public function getId() {
        return $this->id;
    }

    public function getSaleId() {
        return $this->sale_id;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getTotal() {
        return $this->price * $this->quantity + $this->tax;
    }

    public function getTotalTax() {
        return $this->tax;
    }

    public function getAll() {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'sale_id' => $this->sale_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'tax' => $this->tax,
            'total' => $this->getTotal(),
            'total_tax' => $this->getTotalTax()
        ];
    }
}
