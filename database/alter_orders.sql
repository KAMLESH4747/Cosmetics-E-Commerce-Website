USE radiance_db;
ALTER TABLE orders ADD COLUMN items TEXT AFTER customer_name;
