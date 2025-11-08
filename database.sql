-- database.sql
-- SQL structure for VanillaBlog

CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS blogPost (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  content MEDIUMTEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_user_post FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: insert a sample user and post
INSERT INTO user (username, email, password, role) VALUES
('demo', 'demo@example.com', '$2y$10$vYz1p3aTf88k3/haZThcDe7a7cJ2whXPLK7bZ5zqZDWJpQHjWqW.u', 'user');

INSERT INTO blogPost (user_id, title, content) VALUES
(1, 'Welcome to VanillaBlog', 'This is your first blog post on VanillaBlog!');
