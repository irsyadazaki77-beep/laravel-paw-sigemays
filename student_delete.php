<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: student_view.php');
    exit;
}
verify_csrf();
$nim = trim((string) ($_POST['nim'] ?? ''));

// Validasi tambahan (Deva): Pastikan NIM tidak kosong sebelum eksekusi DELETE
if ($nim === '') {
    set_flash('Gagal menghapus: NIM mahasiswa tidak valid.');
    header('Location: student_view.php');
    exit;
}

$statement = $connection->prepare('DELETE FROM student WHERE nim = ?');
$statement->bind_param('s', $nim);
$statement->execute();
$deleted = $statement->affected_rows > 0;
$statement->close();

$message = $deleted ? 'Data mahasiswa berhasil dihapus.' : 'Data mahasiswa tidak ditemukan.';
$icon = $deleted ? 'success' : 'error';
$title = $deleted ? 'Berhasil Dihapus!' : 'Gagal Menghapus!';
set_flash($message);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Hapus Mahasiswa</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
            title: <?= json_encode($title) ?>,
            text: <?= json_encode($message) ?>,
            icon: <?= json_encode($icon) ?>,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'student_view.php';
        });
    </script>
</body>
</html>
<?php
exit;