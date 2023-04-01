<?php

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database.php';

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private $db;
    private $controller;
    private $product_type_id;

    protected function setUp(): void
    {
        $this->db = new Database();
        $this->controller = new ProductController($this->db);
        // mock prodyct_type here
        $this->product_type_id = $this->db->insert("INSERT INTO product_types (name, tax_rate) VALUES ('Test Type', 10)");
    }

    public function testIndex()
    {
        // Define the test data
        $testData = [
            ['id' => 1, 'name' => 'Product A', 'price' => 10, 'product_type_id' => $this->product_type_id],
            ['id' => 2, 'name' => 'Product B', 'price' => 20, 'product_type_id' => $this->product_type_id],
            ['id' => 3, 'name' => 'Product C', 'price' => 30, 'product_type_id' => $this->product_type_id],
        ];
    
        // Mock the database object and set up the expected method call and result
        $db = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();
        $db->expects($this->once())
            ->method('select')
            ->with('SELECT * FROM products')
            ->willReturn($testData);
    
        // Create the controller instance with the mocked database
        $controller = new ProductController($db);
    
        // Call the method being tested
        $result = $controller->index();
    
        // Assert that the method returns the expected result
        $this->assertEquals($testData, $result);
    }    

    public function testIndexReturnsArrayOfProducts()
    {
        $result = $this->controller->index();
        $this->assertIsArray($result);
    
        foreach ($result as $product) {
            $this->assertArrayHasKey('id', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('product_type_id', $product);
        }
    }    

    public function testCreateAddsProductToDatabase()
    {
        $name = 'Test Product';
        $price = 10.99;
        $product_type_id = $this->product_type_id;
        $result = $this->controller->create($name, $price, $product_type_id);
    
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertEquals($name, $result['name']);
        $this->assertEquals($price, $result['price']);
        $this->assertEquals($product_type_id, $result['product_type_id']);
    
        $sql = "SELECT * FROM products WHERE id = ?";
        $params = [$result['id']];
        $rows = $this->db->select($sql, $params);
        $this->assertEquals(1, count($rows));
        $this->assertEquals($name, $rows[0]['name']);
        $this->assertEquals($price, $rows[0]['price']);
        $this->assertEquals($product_type_id, $rows[0]['product_type_id']);
    }

    public function testShowReturnsNullWhenProductNotFound()
    {
        $result = $this->controller->show(999);
        $this->assertNull($result);
    }

    public function testUpdateUpdatesProductInDatabase()
    {
        // Define the test data
        $newProduct = [
            'name' => 'Product A',
            'price' => 10.99,
            'product_type_id' => $this->product_type_id
        ];
        $createdProduct = $this->controller->create(
            $newProduct['name'],
            $newProduct['price'],
            $newProduct['product_type_id']
        );
        $productId = $createdProduct['id'];
        $newName = 'Updated Product A';
        $newPrice = 15.99;
        $newProductTypeId = $this->product_type_id; // or any valid product_type_id
    
        // Call the method being tested
        $result = $this->controller->update($productId, $newName, $newPrice, $newProductTypeId);
    
        // Assert that the method returns the expected result
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertEquals($productId, $result['id']);
        $this->assertEquals($newName, $result['name']);
        $this->assertEquals($newPrice, $result['price']);
        $this->assertEquals($newProductTypeId, $result['product_type_id']);
    
        // Check that the product was updated in the database
        $sql = "SELECT * FROM products WHERE id = ?";
        $params = [$productId];
        $rows = $this->db->select($sql, $params);
        $this->assertEquals(1, count($rows));
        $this->assertEquals($newName, $rows[0]['name']);
        $this->assertEquals($newPrice, $rows[0]['price']);
        $this->assertEquals($newProductTypeId, $rows[0]['product_type_id']);
    }    

    public function testDeleteDeletesProductFromDatabase()
    {
        // Create a new product to be deleted
        $product = [
            'name' => 'Test Product',
            'price' => 9.99,
            'product_type_id' => $this->product_type_id
        ];
        $created_product = $this->controller->create(
            $product['name'],
            $product['price'],
            $product['product_type_id']
        );
    
        // Call the method being tested
        $result = $this->controller->destroy($created_product['id']);
    
        // Assert that the method returns the expected result
        $this->assertTrue($result);
    
        // Check that the product was deleted from the database
        $sql = "SELECT * FROM products WHERE id = ?";
        $params = [$created_product['id']];
        $rows = $this->db->select($sql, $params);
        $this->assertEquals(0, count($rows));
    }    
}