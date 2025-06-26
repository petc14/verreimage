-- sql/schema.sql

-- Create database
CREATE DATABASE IF NOT EXISTS verre_image_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE verre_image_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des plaques personnalisées
CREATE TABLE IF NOT EXISTS custom_plaques (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  format VARCHAR(20) NOT NULL,
  fixation VARCHAR(50) NOT NULL,
  background_image VARCHAR(255) NOT NULL,
  text_content TEXT,
  image_uploads TEXT,
  price DECIMAL(10,2) NOT NULL,
  thumbnail_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des commandes
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  order_reference VARCHAR(50) NOT NULL UNIQUE,
  total_amount DECIMAL(10,2) NOT NULL,
  shipping_firstname VARCHAR(100) NOT NULL,
  shipping_lastname VARCHAR(100) NOT NULL,
  shipping_address VARCHAR(255) NOT NULL,
  shipping_zip_code VARCHAR(20) NOT NULL,
  shipping_city VARCHAR(100) NOT NULL,
  billing_firstname VARCHAR(100) NOT NULL,
  billing_lastname VARCHAR(100) NOT NULL,
  billing_address VARCHAR(255) NOT NULL,
  billing_zip_code VARCHAR(20) NOT NULL,
  billing_city VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  order_status VARCHAR(50) DEFAULT 'pending',
  payment_method VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des articles de commande
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  custom_plaque_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  price_at_purchase DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (custom_plaque_id) REFERENCES custom_plaques(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

