CREATE DATABASE resepkita;
USE resepkita;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    PASSWORD VARCHAR(255) NOT NULL,
    ROLE ENUM('admin', 'user') DEFAULT 'user'
);

-- username : admin 
-- pw : admin123

-- username : user
-- pw : user123