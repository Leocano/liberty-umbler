<?php

class ProductsDAO {
    public function getAllProducts() {
        $db = Database::getInstance();

        $db->query("SELECT * FROM tb_products");

        return $db->getResults();
    }
}