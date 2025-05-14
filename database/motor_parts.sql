-- Insert Motor Parts main category if not exists
INSERT INTO categories (name, slug, parent_id) 
SELECT 'Motor Parts', 'motor-parts', NULL
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'motor-parts');

-- Get the Motor Parts category ID
SET @motor_parts_id = (SELECT id FROM categories WHERE slug = 'motor-parts');

-- Insert subcategories
INSERT INTO categories (name, slug, parent_id)
SELECT 'Electrical Components', 'electrical-components', @motor_parts_id
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'electrical-components');

INSERT INTO categories (name, slug, parent_id)
SELECT 'Mechanical Components', 'mechanical-components', @motor_parts_id
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'mechanical-components');

-- Get the brand ID or create new brand
INSERT INTO brands (name, slug)
SELECT 'Motor Parts Brand', 'motor-parts-brand'
WHERE NOT EXISTS (SELECT 1 FROM brands WHERE slug = 'motor-parts-brand');

SET @brand_id = (SELECT id FROM brands WHERE slug = 'motor-parts-brand');

-- Get category IDs
SET @electrical_id = (SELECT id FROM categories WHERE slug = 'electrical-components');
SET @mechanical_id = (SELECT id FROM categories WHERE slug = 'mechanical-components');

-- Insert products
INSERT INTO products (name, slug, short_description, description, regular_price, sale_price, SKU, stock_status, quantity, category_id, brand_id, featured, status)
VALUES 
-- Electrical Components
('Stator', 'stator', 'High-quality motor stator', 'Premium quality stator for electric motors', 299.99, 279.99, 'STA-001', 'instock', 50, @electrical_id, @brand_id, 0, 1),
('Rotor', 'rotor', 'Precision-engineered rotor', 'High-performance rotor for motors', 249.99, 229.99, 'ROT-001', 'instock', 45, @electrical_id, @brand_id, 0, 1),
('Motor Windings', 'motor-windings', 'Premium copper windings', 'High-grade copper motor windings', 179.99, 159.99, 'WIN-001', 'instock', 60, @electrical_id, @brand_id, 0, 1),

-- Mechanical Components
('Drive Shaft', 'drive-shaft', 'Heavy-duty drive shaft', 'Precision machined drive shaft', 399.99, 379.99, 'SHA-001', 'instock', 40, @mechanical_id, @brand_id, 0, 1),
('Bearings Set', 'bearings-set', 'Premium bearing set', 'High-performance bearing set', 89.99, 79.99, 'BEA-001', 'instock', 100, @mechanical_id, @brand_id, 0, 1),
('Motor Housing', 'motor-housing', 'Durable motor housing', 'Heavy-duty motor housing', 299.99, 279.99, 'HOU-001', 'instock', 30, @mechanical_id, @brand_id, 0, 1); 