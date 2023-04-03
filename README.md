# Marketplace API
This is a marketplace API, pretty much like a shopping cart.

## How to Run the Server (Backend)
1. Clone the repository: git clone https://github.com/matheus-rodrigues00/php_marketplace
2. Install the dependencies: composer install
3. On the project root "/", start the API: php -S localhost:8000 -t public

## How to Run the Interface (Frontend)
- Here is the project but don't worry, you don't need to clone it, https://github.com/matheus-rodrigues00/marketplace_vue;
- The build of the frontend is already on the root directory of the project. To run do the following commands:
```
npm install -g serve
``` 
```
serve -s dist
```
- That's it, you can access the interface on http://localhost:3000 || http://localhost:5000 (maybe you will need to change the port if it is already in use)

## How to Use
- I've already created a user for testing but feel free to create your own: 
```
admin@admin.com
123456
```
- On the dashboard you can take a look on the Products and ProductTypes which are the main entities of this project.
- Briefly the ProductTypes gives the Tax Rate to the products in order to calculate the total price of the sale;
- You can add the products on the Products tab after log in and add the products to your cart;
- You can see your cart on the Cart tab and modify the product quantity or remove it;
- Also you can create a new product as well as create a new Product Type which will be used to add Taxes over the products;
- The main thing is on the cart, where you can visualize the total price of the sale and the total taxes;

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

## Testing
- To run the tests you need to create a database called **marketplace_test**. to isolate the test enviroment.
- Go to the root directory and run:
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