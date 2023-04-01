<?php

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database.php';

use PHPUnit\Framework\TestCase;

class ProductTypeTest extends TestCase
{
    private $db;
    private $controller;

    protected function setUp(): void
    {
        $this->db = new Database();
        $this->controller = new ProductTypesController($this->db);
    }

    public function testIndex()
    {
        // Define the test data
        $testData = [        ['id' => 1, 'name' => 'Type A', 'tax_rate' => 10],
            ['id' => 2, 'name' => 'Type B', 'tax_rate' => 20],
            ['id' => 3, 'name' => 'Type C', 'tax_rate' => 30],
        ];

        // Mock the database object and set up the expected method call and result
        $db = $this->getMockBuilder(Database::class)
                ->disableOriginalConstructor()
                ->getMock();
        $db->expects($this->once())
        ->method('select')
        ->with('SELECT * FROM product_types')
        ->willReturn($testData);

        // Create the controller instance with the mocked database
        $controller = new ProductTypesController($db);

        // Call the method being tested
        $result = $controller->index();

        // Assert that the method returns the expected result
        $this->assertEquals($testData, $result);
    }
 

    public function testIndexReturnsArrayOfProductTypes()
    {
        $result = $this->controller->index();
        $this->assertIsArray($result);

        foreach ($result as $product_type) {
            $this->assertArrayHasKey('id', $product_type);
            $this->assertArrayHasKey('name', $product_type);
            $this->assertArrayHasKey('tax_rate', $product_type);
        }
    }

    public function testCreateAddsProductTypeToDatabase()
    {
        $name = 'Test Product Type';
        $tax_rate = 0.10;
        $result = $this->controller->create($name, $tax_rate);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('tax_rate', $result);
        $this->assertEquals($name, $result['name']);
        $this->assertEquals($tax_rate, $result['tax_rate']);

        $sql = "SELECT * FROM product_types WHERE id = ?";
        $params = [$result['id']];
        $rows = $this->db->select($sql, $params);
        $this->assertEquals(1, count($rows));
        $this->assertEquals($name, $rows[0]['name']);
        $this->assertEquals($tax_rate, $rows[0]['tax_rate']);
    }

    public function testShowReturnsNullWhenProductTypeNotFound()
    {
        $result = $this->controller->show(999);
        $this->assertNull($result);
    }

    public function testShowReturnsProductTypeIfExists()
    {
        $name = 'Test Product Type';
        $tax_rate = 0.10;
        $created = $this->controller->create($name, $tax_rate);
        $result = $this->controller->show($created['id']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('tax_rate', $result);
        $this->assertEquals($created, $result);
    }

    public function testUpdateReturnsNullWhenNoUpdatesProvided()
    {
        $result = $this->controller->update(1);
        $this->assertNull($result);
    }

    public function testUpdateReturnsNullWhenProductTypeNotFound()
    {
        $result = $this->controller->update(999, 'Test Product Type');
        $this->assertNull($result);
    }

    public function testUpdateReturnsUpdatedProductTypeIfExists()
    {
        // Create a product type
        $name = 'Test Product Type';
        $tax_rate = 0.10;
        $created = $this->controller->create($name, $tax_rate);

        // Update the product type
        $newName = 'Updated Product Type';
        $newTaxRate = 0.20;
        $updated = $this->controller->update($created['id'], $newName, $newTaxRate);

        // Assert that the product type was updated correctly
        $this->assertIsArray($updated);
        $this->assertArrayHasKey('id', $updated);
        $this->assertArrayHasKey('name', $updated);
        $this->assertArrayHasKey('tax_rate', $updated);
        $this->assertEquals($created['id'], $updated['id']);
        $this->assertEquals($newName, $updated['name']);
        $this->assertEquals($newTaxRate, $updated['tax_rate']);

        // Check that the update was persisted to the database
        $sql = "SELECT * FROM product_types WHERE id = ?";
        $params = [$updated['id']];
        $rows = $this->db->select($sql, $params);
        $this->assertEquals(1, count($rows));
        $this->assertEquals($newName, $rows[0]['name']);
        $this->assertEquals($newTaxRate, $rows[0]['tax_rate']);
    }

    public function testDestroyRemovesProductTypeIfExists()
    {
        // Create a product type
        $name = 'Test Product Type';
        $tax_rate = 0.10;
        $created = $this->controller->create($name, $tax_rate);

        // Delete the product type
        $this->controller->destroy($created['id']);

        // Assert that the product type was deleted
        $sql = "SELECT * FROM product_types WHERE id = ?";
        $params = [$created['id']];
        $rows = $this->db->select($sql, $params);
        $this->assertEquals(0, count($rows));
    }

}
