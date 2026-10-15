-- =====================================================
-- Sunrise Community Hospital - Database Export
-- Task 5: Database Implementation
-- Task 11: Database export (.sql)
-- =====================================================

-- Create the database (skip this line if it already exists)
CREATE DATABASE IF NOT EXISTS hospital_management;
USE hospital_management;

-- Task 5: appointments table
CREATE TABLE IF NOT EXISTS appointments (
    appointment_id   INT AUTO_INCREMENT PRIMARY KEY,
    patient_name      VARCHAR(100) NOT NULL,
    national_id       VARCHAR(10)  NOT NULL,
    gender            VARCHAR(10)  NOT NULL,
    phone_number      VARCHAR(15)  NOT NULL,
    email             VARCHAR(100) NOT NULL,
    department        VARCHAR(50)  NOT NULL,
    appointment_date  DATE         NOT NULL,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);