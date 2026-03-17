-- Database Schema for Advanced POS System
-- PostgreSQL

-- 1. Users & Roles
CREATE TYPE user_role AS ENUM ('admin', 'cashier', 'owner');

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role user_role DEFAULT 'cashier',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 2. Categories
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 3. Products
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    sku VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(12, 2) NOT NULL DEFAULT 0,
    cost_price DECIMAL(12, 2) NOT NULL DEFAULT 0,
    stock_quantity INTEGER DEFAULT 0,
    min_stock_level INTEGER DEFAULT 5,
    barcode VARCHAR(100) UNIQUE,
    image_url TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 4. Transactions (Header)
CREATE TABLE transactions (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    transaction_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    tax_amount DECIMAL(12, 2) DEFAULT 0,
    grand_total DECIMAL(12, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL, -- 'cash', 'qris', 'transfer', etc.
    payment_status VARCHAR(20) DEFAULT 'paid', -- 'paid', 'pending', 'cancelled'
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 5. Transaction Items (Detail)
CREATE TABLE transaction_items (
    id SERIAL PRIMARY KEY,
    transaction_id INTEGER REFERENCES transactions(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id),
    quantity INTEGER NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    discount DECIMAL(12, 2) DEFAULT 0
);

-- 6. Stock Logs (Inventory History)
CREATE TABLE stock_logs (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    quantity_change INTEGER NOT NULL,
    type VARCHAR(20) NOT NULL, -- 'sale', 'restock', 'adjustment', 'return'
    reference_id INTEGER, -- Transaction ID or Restock ID
    note TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for performance
CREATE INDEX idx_products_sku ON products(sku);
CREATE INDEX idx_products_barcode ON products(barcode);
CREATE INDEX idx_transactions_number ON transactions(transaction_number);
CREATE INDEX idx_transaction_items_transaction_id ON transaction_items(transaction_id);

-- Trigger for updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();
CREATE TRIGGER update_products_updated_at BEFORE UPDATE ON products FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();

-- Sample Data (Optional)
INSERT INTO users (username, password_hash, full_name, role) VALUES 
('admin', '$2b$10$YourHashedPassword', 'Administrator POS', 'admin');

INSERT INTO categories (name, description) VALUES 
('Makanan', 'Kategori produk makanan'),
('Minuman', 'Kategori produk minuman');
