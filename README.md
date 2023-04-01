# Database Modeling Schema:

## products
- id (integer, primary key, auto increment)
- name (string)
- price (decimal)

## product_types
- id (integer, primary key, auto increment)
- name (string)
- tax_rate (decimal)

## product_product_type (pivot table)
- product_id (integer, foreign key references products(id))
- product_type_id (integer, foreign key references product_types(id))

## sales
- id (integer, primary key, auto increment)
- total (decimal)
- total_tax (decimal)
- created_at (timestamp)

## sale_items
- id (integer, primary key, auto increment)
- sale_id (integer, foreign key references sales(id))
- product_id (integer, foreign key references products(id))
- quantity (integer)
- price (decimal)
- tax (decimal)
