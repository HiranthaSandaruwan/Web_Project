-- Database for Hardware Repair Request Tracker
-- CREATE DATABASE repair_tracker;
-- USE repair_tracker;
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  last_login TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  device_type VARCHAR(100) NOT NULL,
  model VARCHAR(100),
  serial_no VARCHAR(100),
  priority ENUM('Low','Medium','High') DEFAULT 'Medium',
  category ENUM('Hardware Failure','Software Issue','Physical Damage','Other') NOT NULL DEFAULT 'Hardware Failure',
  description TEXT NOT NULL,
  status ENUM('Pending','In Progress','Completed','Rejected') DEFAULT 'Pending',
  due_date DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_req_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  INDEX idx_req_user (user_id),
  INDEX idx_req_category (category),
  INDEX idx_req_updated (updated_at)
);

CREATE TABLE comments (
  comment_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  user_id INT NOT NULL,
  comment_text TEXT NOT NULL,
  admin_only TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_comment_request FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  CONSTRAINT fk_comment_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  INDEX idx_comment_req (request_id)
);

CREATE TABLE request_history (
  history_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  field_changed VARCHAR(50) NOT NULL,
  old_value VARCHAR(255) NULL,
  new_value VARCHAR(255) NULL,
  changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_history_request FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  INDEX idx_history_req (request_id)
);

-- If migrating an existing DB, apply:
-- ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL AFTER role;
-- ALTER TABLE requests ADD COLUMN category ENUM('Hardware Failure','Software Issue','Physical Damage','Other') NOT NULL DEFAULT 'Hardware Failure' AFTER priority;
-- CREATE TABLE comments ( ... ) ; -- See above definition
-- CREATE TABLE request_history ( ... );

INSERT INTO users (username, password, role) VALUES ('uoc','uoc','user');
INSERT INTO users (username, password, role) VALUES ('admin','admin','admin');
