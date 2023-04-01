<?php

class Product {
    private $id;
    private $name;
    private $price;
    private $product_type_id;

    public function __construct($id, $name, $price, $product_type_id) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->product_type_id = $product_type_id;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getProductTypeId() {
        return $this->product_type_id;
    }

    public function getAll() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'product_type_id' => $this->product_type_id
        ];
    }
}