# Marketplace API
This is a marketplace API, pretty much like a shopping cart.

## Endpoints
### products
- GET /products: Returns a list of all products.
- POST /products: Creates a new product.
- GET /products/{id}: Returns the details of a single product.
- PUT /products/{id}: Updates an existing product.
- DELETE /products/{id}: Deletes an existing product.

### product_types
- GET /product-types: Returns a list of all product types.
- POST /product-types: Creates a new product type.
- GET /product-types/{id}: Returns the details of a single product type.
- PUT /product-types/{id}: Updates an existing product type.
- DELETE /product-types/{id}: Deletes an existing product type.

### sales
- POST /sales: Creates a new sale.
- GET /sales/{id}: Returns the details of a single sale.

### sale items
- POST /sales/items: Adds a new item to a sale.
- PUT /sales/items: Updates an existing item in a sale.

## Installation
1. Clone the repository: git clone https://github.com/matheus-rodrigues00/php_marketplace
2. Install the dependencies: composer install
3. Start the API: php -S localhost:8000 -t public

## Testing
To run the tests, go to the root directory and run:
```php vendor/bin/phpunit tests/```

## Dependencies
- **bramus/router**: routing library for PHP;
- **phpunit/phpunit**: a testing framework for PHP;

## Database Modeling Schema:
### products
- id (integer, primary key, auto increment)
- name (string)
- price (decimal)
- product_type_id (integer, foreign key references product_types(id))

### product_types
- id (integer, primary key, auto increment)
- name (string)
- tax_rate (decimal)

### sales
- id (integer, primary key, auto increment)
- total (decimal)
- total_tax (decimal)
- created_at (timestamp)

### users
- id (integer, primary key, auto increment)
- user_id (integer, foreign key references users(id))
- name (string)
- email (string, unique)
- password (string)


### sale_items
- id (integer, primary key, auto increment)
- sale_id (integer, foreign key references sales(id))
- product_id (integer, foreign key references products(id))
- quantity (integer)
- price (decimal)
- tax (decimal)

The script I have used to create the database is on **db_creation_script.sql**, you can use it as a reference :D

I hope you enjoy it!