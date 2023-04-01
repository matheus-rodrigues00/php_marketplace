<?php

class Sale {
    private $id;
    private $created_at;
    private $total;
    private $total_tax;

    public function __construct($id, $created_at = null, $total = null, $total_tax = null) {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->total = $total;
        $this->total_tax = $total_tax;
    }

    public function getId() {
        return $this->id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getTotalTax() {
        return $this->total_tax;
    }

    public function getAll() {
        $data = [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'total' => $this->total,
            'total_tax' => $this->total_tax
        ];
        return $data;
    }
}
