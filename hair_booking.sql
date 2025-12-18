-- Drop and create fresh database
DROP DATABASE IF EXISTS hair_booking;
CREATE DATABASE hair_booking;
USE hair_booking;

-- 1. USERS TABLE (Essential for login/signup)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. STYLISTS TABLE (Essential for booking)
CREATE TABLE stylists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100),
    photo VARCHAR(200),
    email VARCHAR(100),
    phone VARCHAR(20),
    is_available BOOLEAN DEFAULT TRUE
);

-- 3. HAIRSTYLES TABLE (Essential for gallery)
CREATE TABLE hairstyles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    gender ENUM('men', 'women', 'unisex') DEFAULT 'unisex',
    image_url VARCHAR(255),
    estimated_time VARCHAR(50),
    is_featured BOOLEAN DEFAULT FALSE
);

-- 4. APPOINTMENTS TABLE (Core booking functionality)
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    stylist_id INT,
    hairstyle_name VARCHAR(100),
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    phone VARCHAR(20),
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    service_type VARCHAR(100),
    notes TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (stylist_id) REFERENCES stylists(id) ON DELETE SET NULL
);

-- Insert essential sample data
INSERT INTO stylists (name, specialty, photo, email, phone) VALUES 
('Samy Joe', 'All Hair Stylist', 'stylist1.jpg', 'SamyJoe@email.com', '+233596291917'),
('Jordan Lee', 'Natural Hair Care', 'stylist2.jpg', 'jordan@email.com', '555-5678'),
('Taylor Kim', 'Wig Installation', 'stylist3.jpg', 'taylor@email.com', '555-9012'),
('Chris Evans', 'Men\'s Cuts & Braids', 'stylist4.jpg', 'chris@email.com', '555-3456');

INSERT INTO hairstyles (name, category, gender, image_url, estimated_time, is_featured) VALUES
('Box Braids', 'braids', 'women', 'box-braids.jpg', '4-6 hours', TRUE),
('Cornrows', 'braids', 'unisex', 'cornrows.jpg', '2-3 hours', TRUE),
('Knotless Braids', 'braids', 'women', 'knotless-braids.jpg', '5-7 hours', TRUE),
('Fulani Braids', 'braids', 'women', 'fulani-braids.jpg', '3-5 hours', FALSE),
('Twist Out', 'natural', 'women', 'twist-out.jpg', '1-2 hours', FALSE),
('Bantu Knots', 'natural', 'women', 'bantu-knots.jpg', '2-3 hours', FALSE),
('Lace Front Wig', 'wigs', 'women', 'lace-front-wig.jpg', '1-2 hours', TRUE),
('Men\'s Cornrows', 'braids', 'men', 'mens-cornrows.jpg', '2-3 hours', FALSE),
('Buzz Cut', 'mens', 'men', 'buzz-cut.jpg', '30 minutes', FALSE),
('Crew Cut', 'mens', 'men', 'crew-cut.jpg', '45 minutes', FALSE),
('Man Bun', 'mens', 'men', 'man-bun.jpg', '15 minutes', FALSE),
('Bob Wig', 'wigs', 'women', 'bob-wig.jpg', '1 hour', FALSE);

-- Create indexes for better performance
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_user ON appointments(user_id);
CREATE INDEX idx_hairstyles_category ON hairstyles(category);
CREATE INDEX idx_hairstyles_gender ON hairstyles(gender);