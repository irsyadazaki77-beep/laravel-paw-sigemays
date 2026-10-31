<?php
session_start();

require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/includes/functions.php';

// Jika sudah login, langsung menuju halaman mahasiswa
if (!empty($_SESSION['username'])) {
    header('Location: student_view.php');
    exit;
}

$errorMessage = '';
$username = '';

// Notifikasi setelah berhasil logout
$logoutSuccess = isset($_GET['logout']) && $_GET['logout'] === 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validasi CSRF
    verify_csrf();

    // Mengambil input pengguna
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    // Validasi input kosong
    if ($username === '' || $password === '') {

        $errorMessage = 'Username dan password wajib diisi.';

    } else {

        // Mencari password berdasarkan username
        $statement = $connection->prepare(
            'SELECT password FROM admin WHERE username = ?'
        );

        $statement->bind_param('s', $username);
        $statement->execute();
        $statement->bind_result($storedHash);

        $found = $statement->fetch();
        $statement->close();

        // Verifikasi password
        $validPassword = $found && password_verify($password, $storedHash);

        // Mendukung password lama dengan SHA1
        $legacyPassword = $found && hash_equals($storedHash, sha1($password));

        if ($validPassword || $legacyPassword) {

            // Memperbarui password lama menjadi password_hash
            if ($legacyPassword && !$validPassword) {

                $newHash = password_hash($password, PASSWORD_DEFAULT);

                $update = $connection->prepare(
                    'UPDATE admin SET password = ? WHERE username = ?'
                );

                $update->bind_param('ss', $newHash, $username);
                $update->execute();
                $update->close();
            }

            // Membuat session baru setelah login berhasil
            session_regenerate_id(true);

            $_SESSION['username'] = $username;

            // Mengarahkan ke halaman data mahasiswa
            header('Location: student_view.php');
            exit;

        } else {

            // Pesan jika username atau password salah
            $errorMessage = 'Login gagal! Username atau password salah.';
        }
    }
}

$pageTitle = 'Login Data Mahasiswa';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($pageTitle) ?></title>

    <link rel="stylesheet" href="assets/login.css">
</head>

<body>

    <main class="login-card">

        <h1>Data Mahasiswa</h1>

        <p>Masuk untuk mengelola data mahasiswa.</p>

        <!-- Notifikasi Berhasil Logout -->
        <?php if ($logoutSuccess): ?>
            <div class="success" role="status">
                ✅ Berhasil logout! Sampai jumpa kembali.
            </div>
        <?php endif; ?>

        <!-- Pesan Error Login -->
        <?php if ($errorMessage !== ''): ?>
            <div class="error" role="alert">
                ⚠️ <?= e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form action="login.php" method="post">

            <input
                type="hidden"
                name="_csrf_token"
                value="<?= e(csrf_token()) ?>"
            >

            <label for="username">Username</label>

            <input
                type="text"
                name="username"
                id="username"
                autocomplete="username"
                value="<?= e($username) ?>"
                required
            >

            <label for="password">Password</label>

            <input
                type="password"
                name="password"
                id="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">Log In</button>

        </form>

        <p class="hint">
            Database belum disiapkan?
            Jalankan
            <a href="generate.php">generate.php</a>
            terlebih dahulu.
        </p>

    </main>

</body>
</html>
