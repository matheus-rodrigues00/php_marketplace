<?php

class Sale {
    private $id;
    private $created_at;
    private $total;
    private $total_tax;
    private $user_id;

    public function __construct($id, $created_at = null, $total = null, $total_tax = null, $user_id = null) {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->total = $total;
        $this->total_tax = $total_tax;
        $this->user_id = $user_id;
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

    public function getUserId() {
        return $this->user_id;
    }

    public function getAll() {
        $data = [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
            'user_id' => $this->user_id
        ];
        return $data;
    }
}
