-- Create the database
CREATE DATABASE IF NOT EXISTS hostel_management;

-- Use the database
USE hostel_management;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL,
    full_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    emergency_contact VARCHAR(100)
);

-- Create buildings table
CREATE TABLE buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    floors INT NOT NULL
);

-- Create rooms table
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    building_id INT NOT NULL,
    room_number VARCHAR(20) NOT NULL,
    capacity INT NOT NULL,
    FOREIGN KEY (building_id) REFERENCES buildings(id)
);

-- Create allocations table
CREATE TABLE allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    allocation_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Create attendance table
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('present', 'absent') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    UNIQUE KEY (student_id, date)
);

-- Create payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id)
);

-- Create check_in_out table
CREATE TABLE check_in_out (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    action ENUM('check_in', 'check_out') NOT NULL,
    timestamp DATETIME NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id)
);

-- Create room_change_requests table
CREATE TABLE room_change_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    reason TEXT NOT NULL,
    request_date DATE NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id)
);

-- Create leave_requests table
CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id)
);

-- Create hostel_rules table
CREATE TABLE hostel_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule TEXT NOT NULL
);

-- Create notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert a sample admin user (password: admin123)
INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$8IjGzXbzZ9Q4XK5q5X5Q5OQX5Q5OQX5Q5OQX5Q5OQX5Q5OQX5Q', 'admin');

-- Insert a sample student user (password: student123)
INSERT INTO users (username, password, role) VALUES ('student', '$2y$10$8IjGzXbzZ9Q4XK5q5X5Q5OQX5Q5OQX5Q5OQX5Q5OQX5Q5OQX5Q', 'student');

-- Insert sample hostel rules
INSERT INTO hostel_rules (rule) VALUES 
('Quiet hours are from 10 PM to 6 AM'),
('No smoking or alcohol consumption on premises'),
('Visitors are allowed from 9 AM to 8 PM only'),
('Keep your room and common areas clean'),
('Report any maintenance issues immediately');