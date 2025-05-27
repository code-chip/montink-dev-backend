USE myapp;

INSERT INTO products (name, price) VALUES
('T-shirt Basic', 59.90),
('Jeans Classic', 120.00),
('Sneakers Sport', 210.00);

INSERT INTO stocks (product_id, variation, quantity) VALUES
(1, 'Small', 10),
(1, 'Medium', 15),
(1, 'Large', 5),
(2, '32', 8),
(2, '34', 12),
(3, '40', 7),
(3, '42', 4);

INSERT INTO coupons (code, discount, min_value, expires_at) VALUES
('SAVE10', 10.00, 50.00, '2030-12-31'),
('FREESHIP', 20.00, 200.00, '2030-12-31');
