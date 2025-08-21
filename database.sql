-- Database for Hardware Repair Request Tracker
-- CREATE DATABASE repair_tracker;
-- USE repair_tracker;
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  device_type VARCHAR(100) NOT NULL,
  model VARCHAR(100),
  serial_no VARCHAR(100),
  priority ENUM('Low','Medium','High') DEFAULT 'Medium',
  description TEXT NOT NULL,
  status ENUM('Pending','In Progress','Completed','Rejected') DEFAULT 'Pending',
  due_date DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_req_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO users (username, password, role) VALUES ('uoc','uoc','user');
INSERT INTO users (username, password, role) VALUES ('admin','admin','admin');
