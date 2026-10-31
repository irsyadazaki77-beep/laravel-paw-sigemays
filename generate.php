<?php
require_once __DIR__ . '/config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $connection = new mysqli($dbhost, $dbuser, $dbpass);
    $connection->set_charset('utf8mb4');
    $connection->query('CREATE DATABASE IF NOT EXISTS `' . $connection->real_escape_string($dbname) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $connection->select_db($dbname);

    $connection->query("CREATE TABLE IF NOT EXISTS student (
        nim CHAR(8) NOT NULL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        birth_city VARCHAR(50) NOT NULL,
        birth_date DATE NOT NULL,
        faculty VARCHAR(50) NOT NULL,
        department VARCHAR(100) NOT NULL,
        gpa DECIMAL(3,2) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $connection->query("CREATE TABLE IF NOT EXISTS admin (
        username VARCHAR(50) NOT NULL PRIMARY KEY,
        password VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $connection->query('ALTER TABLE admin MODIFY password VARCHAR(255) NOT NULL');

    $studentCount = (int) $connection->query('SELECT COUNT(*) AS total FROM student')->fetch_assoc()['total'];
    if ($studentCount === 0) {
        $students = [
            ['12041952', 'Brian Yuko', 'Padang', '1996-11-23', 'FTIB', 'Sistem Informasi', 3.10],
            ['12042010', 'Safira Yanuar', 'Bandung', '1994-08-22', 'FTIB', 'Sistem Informasi', 3.90],
            ['12042012', 'Rezja Zalva', 'Jakarta', '1997-12-31', 'FTIB', 'Informatika', 3.50],
            ['12042034', 'Agha Rizky', 'Jakarta', '1997-06-28', 'FTEIC', 'Sains Data', 3.40],
            ['12042041', 'Adhiaksa', 'Medan', '1995-04-02', 'FTEIC', 'Bisnis Digital', 3.70],
        ];
        $statement = $connection->prepare('INSERT INTO student (nim, name, birth_city, birth_date, faculty, department, gpa) VALUES (?, ?, ?, ?, ?, ?, ?)');
        foreach ($students as $row) {
            $statement->bind_param('ssssssd', $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
            $statement->execute();
        }
        $statement->close();
    }

    $adminCount = (int) $connection->query("SELECT COUNT(*) AS total FROM admin WHERE username = 'admin'")->fetch_assoc()['total'];
    if ($adminCount === 0) {
        $username = 'admin';
        $passwordHash = password_hash('adminmahasiswa', PASSWORD_DEFAULT);
        $statement = $connection->prepare('INSERT INTO admin (username, password) VALUES (?, ?)');
        $statement->bind_param('ss', $username, $passwordHash);
        $statement->execute();
        $statement->close();
    }
    echo 'Database my_campus siap. Data contoh hanya ditambahkan jika tabel masih kosong. <a href="login.php">Buka halaman login</a>.';
    $connection->close();
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('Pembuatan database gagal. Periksa MySQL dan konfigurasi di config.php.');
}