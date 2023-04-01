<?php

class ProductType {
    private $id;
    private $name;
    private $tax_rate;

    public function __construct($id, $name, $tax_rate) {
        $this->id = $id;
        $this->name = $name;
        $this->tax_rate = $tax_rate;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getTaxRate() {
        return $this->tax_rate;
    }

    public function getAll() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tax_rate' => $this->tax_rate
        ];
    }
}