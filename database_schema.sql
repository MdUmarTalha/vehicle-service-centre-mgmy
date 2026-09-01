CREATE DATABASE vehicle_service_centre;

USE vehicle_service_centre;

-- Customers table
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(15) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE
);

-- Vehicles table
CREATE TABLE vehicles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  reg_number VARCHAR(20) NOT NULL UNIQUE,
  model VARCHAR(100) NOT NULL,
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT
);

-- Services table (service types)
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(100) NOT NULL,
  cost DECIMAL(10,2) NOT NULL
);

-- Service requests table
CREATE TABLE service_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  vehicle_id INT NOT NULL,
  service_id INT NOT NULL,
  service_date DATE NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'pending' COMMENT 'e.g., pending, completed',
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT,
  INDEX idx_status (status)
);