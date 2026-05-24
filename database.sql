    CREATE DATABASE resepkita;
    USE resepkita;

    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        PASSWORD VARCHAR(255) NOT NULL,
        ROLE ENUM('admin', 'user') DEFAULT 'user'
    );


    CREATE TABLE resep (
        id_resep INT AUTO_INCREMENT PRIMARY KEY, 
        id INT NOT NULL,                          
        judul VARCHAR(255) NOT NULL,
        deskripsi TEXT,
        kategori_daerah ENUM('jawa', 'kaltim', 'sumatra', 'papua', 'lainnya') NOT NULL,
        foto VARCHAR(255) NOT NULL,
        bahan TEXT NOT NULL,
        langkah TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE komentar (
        id_komentar INT AUTO_INCREMENT PRIMARY KEY,
        id_resep INT NOT NULL,
        id INT NOT NULL,
        isi_komentar TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (id_resep) REFERENCES resep(id_resep) ON DELETE CASCADE,
        FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
    );

    -- username : admin 
    -- pw : admin123

    -- username : user
    -- pw : user123 