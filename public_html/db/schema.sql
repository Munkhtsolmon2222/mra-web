-- MRA Awards 2025 Voting System Database Schema
-- Run this SQL script to set up the database

CREATE DATABASE IF NOT EXISTS mra_awards CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mra_awards;

-- Categories table (8 award categories)
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert the 8 award categories
INSERT INTO categories (id, name, slug) VALUES
(1, 'Шилдэг ресторан', 'best-restaurant'),
(2, 'Шилдэг франчайз ресторан / сүлжээ', 'best-franchise'),
(3, 'Шилдэг эвент холл', 'best-event-hall'),
(4, 'Шилдэг кофе шоп', 'best-coffee-shop'),
(5, 'Шилдэг түргэн хоолны газар', 'best-fast-food'),
(6, 'Шилдэг бар & паб', 'best-bar-pub'),
(7, 'Шилдэг диско клуб', 'best-disco-club'),
(8, 'Шилдэг мэргэжлийн сургалтын байгууллага', 'best-training-institute')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Participants table
CREATE TABLE IF NOT EXISTS participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(500) DEFAULT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Votes table
CREATE TABLE IF NOT EXISTS votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    participant_id INT NOT NULL,
    category_id INT NOT NULL,
    voter_ip VARCHAR(45) NOT NULL,
    voter_session VARCHAR(255) NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id) REFERENCES participants(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_participant (participant_id),
    INDEX idx_category (category_id),
    INDEX idx_voter (voter_ip, voter_session, category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create default admin user (password: admin123 - CHANGE THIS!)
-- Password hash for 'admin123' (properly generated)
INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy')
ON DUPLICATE KEY UPDATE username=VALUES(username);

