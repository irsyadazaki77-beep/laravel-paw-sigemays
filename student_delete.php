<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: student_view.php');
    exit;
}
verify_csrf();
$nim = trim((string) ($_POST['nim'] ?? ''));
$statement = $connection->prepare('DELETE FROM student WHERE nim = ?');
$statement->bind_param('s', $nim);
$statement->execute();
$deleted = $statement->affected_rows > 0;
$statement->close();
set_flash($deleted ? 'Data mahasiswa berhasil dihapus.' : 'Data mahasiswa tidak ditemukan.');
header('Location: student_view.php');
exit;