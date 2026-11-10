
-- ============================================
-- INFO2180 Project 2
-- Dolphin CRM Database Schema
-- ============================================

CREATE DATABASE IF NOT EXISTS dolphin_crm;

USE dolphin_crm;

-- ============================================
-- 1. USERS TABLE
-- ============================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(20) NOT NULL DEFAULT 'Member',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- 2. CONTACTS TABLE
-- ============================================

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(20) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    company VARCHAR(255) NOT NULL,
    type VARCHAR(20) NOT NULL,
    assigned_to INT NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_contacts_assigned_to
        FOREIGN KEY (assigned_to) REFERENCES users(id),

    CONSTRAINT fk_contacts_created_by
        FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

-- ============================================
-- 3. NOTES TABLE
-- ============================================

CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notes_contact
        FOREIGN KEY (contact_id) REFERENCES contacts(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_notes_created_by
        FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

-- ============================================
-- 4. INITIAL ADMINISTRATOR ACCOUNT
-- ============================================

INSERT INTO users (firstname, lastname, password, email, role)
VALUES (
    'Admin',
    'User',
    '$2y$12$pVFTXUh8feznXI8iYifkfuLVMF6fGbD/J4YNL/13lpFhuKqjLY/P6',
    'admin@project2.com',
    'Admin'
);
