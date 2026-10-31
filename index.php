<?php
session_start();
require_once __DIR__ . '/config.php';

// Cek status koneksi MySQL
$dbConnected = false;
$dbMessage = '';
$totalStudents = 0;
$avgGpa = 0.0;
$sampleStudents = [];

mysqli_report(MYSQLI_REPORT_OFF);
$dbTest = @new mysqli($dbhost, $dbuser, $dbpass);

if ($dbTest->connect_error) {
    $dbMessage = 'MySQL tidak dapat dihubungi (' . $dbTest->connect_error . '). Pastikan module MySQL di XAMPP Control Panel sudah di-Start!';
} else {
    // Cek apakah database my_campus ada
    $checkDb = @$dbTest->select_db($dbname);
    if (!$checkDb) {
        $dbMessage = "Koneksi ke MySQL aktif, namun database '{$dbname}' belum dibuat. Klik tombol 'Setup / Inisialisasi Database' di bawah.";
    } else {
        $dbConnected = true;
        $dbMessage = "Terhubung dengan database '{$dbname}' di MySQL (localhost:3306).";
        
        // Ambil statistik data
        $resCount = @$dbTest->query('SELECT COUNT(*) as total, AVG(gpa) as avg_gpa FROM student');
        if ($resCount && $row = $resCount->fetch_assoc()) {
            $totalStudents = (int) $row['total'];
            $avgGpa = (float) $row['avg_gpa'];
        }
        
        $resStudents = @$dbTest->query('SELECT nim, name, birth_city, faculty, department, gpa FROM student ORDER BY nim ASC LIMIT 5');
        if ($resStudents) {
            while ($st = $resStudents->fetch_assoc()) {
                $sampleStudents[] = $st;
            }
        }
    }
    $dbTest->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Tim Si Gemays - Modul 06 PAW</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --brand-purple: #4f46e5;
            --brand-dark: #3730a3;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .header-box {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 35px 30px;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.25);
        }
        .card-member {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            transition: all 0.25s ease;
        }
        .card-member:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }
        .member-avatar {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .badge-branch {
            font-family: monospace;
            background: #e0e7ff;
            color: #4338ca;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .table-custom th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<div class="container py-4">

    <!-- Header Portal -->
    <div class="header-box mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-20 px-3 py-1 rounded-pill mb-3 small text-white">
                    <i class="bi bi-git"></i> Praktikum PAW Modul 06 &middot; Version Control System
                </div>
                <h1 class="fw-bold mb-2">Portal Monitoring Tim Si Gemays</h1>
                <p class="mb-0 opacity-90">
                    Proyek Kolaborasi Git & GitHub: <strong>Aplikasi Pengelolaan Data Mahasiswa (PHP CRUD MySQL)</strong> &middot; S1 Sistem Informasi Telkom University Surabaya.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="https://github.com/irsyadazaki77-beep/laravel-paw-sigemays" target="_blank" class="btn btn-light fw-semibold px-4 py-2 text-dark shadow-sm">
                    <i class="bi bi-github me-1"></i> Buka Repo GitHub
                </a>
            </div>
        </div>
    </div>

    <!-- Status Bar Database & Quick Actions -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-circle <?= $dbConnected ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' ?>">
                            <i class="bi <?= $dbConnected ? 'bi-database-check' : 'bi-database-exclamation' ?> fs-3"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-1">Status Database MySQL (XAMPP):</div>
                            <div class="small <?= $dbConnected ? 'text-success' : 'text-danger' ?>">
                                <?= htmlspecialchars($dbMessage) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                    <a href="generate.php" class="btn btn-outline-primary btn-sm px-3">
                        <i class="bi bi-arrow-clockwise me-1"></i> Setup / Inisialisasi Database
                    </a>
                    <?php if (!empty($_SESSION['username'])): ?>
                        <a href="student_view.php" class="btn btn-success btn-sm px-3">
                            <i class="bi bi-table me-1"></i> Masuk Aplikasi (<?= htmlspecialchars($_SESSION['username']) ?>)
                        </a>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm px-3">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login Aplikasi
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitoring Anggota Kelompok -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Status Pengerjaan Anggota Tim (4 Mahasiswa)</h4>
        <span class="badge bg-secondary px-3 py-2">Proyek: PHP CRUD MySQL (Modul 05)</span>
    </div>

    <div class="row g-3 mb-4">
        <!-- Mahasiswa A -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-member h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="member-avatar bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1 mb-1">Mahasiswa A (Lead)</span>
                        <h6 class="fw-bold mb-0">Zaki Irsyada Putra</h6>
                        <small class="text-muted">108052500069</small>
                    </div>
                </div>
                <p class="small text-secondary mb-2" style="min-height: 48px;">
                    <strong>Modul:</strong> Setup Base PHP, Banner Tim, dan Tampilan Data (<code>student_view.php</code>).
                </p>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="badge-branch"><i class="bi bi-diagram-2"></i> zaki</span>
                    <a href="student_view.php" class="btn btn-outline-primary btn-sm">Uji Modul</a>
                </div>
            </div>
        </div>

        <!-- Mahasiswa B -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-member h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="member-avatar bg-info bg-opacity-10 text-info">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2 py-1 mb-1">Mahasiswa B</span>
                        <h6 class="fw-bold mb-0">M Nabil Alhakim</h6>
                        <small class="text-muted">Anggota Tim</small>
                    </div>
                </div>
                <p class="small text-secondary mb-2" style="min-height: 48px;">
                    <strong>Modul:</strong> Form Tambah Mahasiswa & Validasi Form (<code>student_add.php</code>).
                </p>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="badge-branch"><i class="bi bi-diagram-2"></i> nabil</span>
                    <a href="student_add.php" class="btn btn-outline-primary btn-sm">Uji Modul</a>
                </div>
            </div>
        </div>

        <!-- Mahasiswa C -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-member h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="member-avatar bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2 py-1 mb-1">Mahasiswa C</span>
                        <h6 class="fw-bold mb-0">Daniel Harley K.</h6>
                        <small class="text-muted">Anggota Tim</small>
                    </div>
                </div>
                <p class="small text-secondary mb-2" style="min-height: 48px;">
                    <strong>Modul:</strong> Sistem Autentikasi Pengguna & Session Guard (<code>login.php</code>, <code>logout.php</code>).
                </p>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="badge-branch"><i class="bi bi-diagram-2"></i> daniel</span>
                    <a href="login.php" class="btn btn-outline-primary btn-sm">Uji Modul</a>
                </div>
            </div>
        </div>

        <!-- Mahasiswa D -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-member h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="member-avatar bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2 py-1 mb-1">Mahasiswa D</span>
                        <h6 class="fw-bold mb-0">Deva Mahendra Putra</h6>
                        <small class="text-muted">Anggota Tim</small>
                    </div>
                </div>
                <p class="small text-secondary mb-2" style="min-height: 48px;">
                    <strong>Modul:</strong> Edit Data & Hapus Mahasiswa (<code>student_edit.php</code>, <code>student_delete.php</code>).
                </p>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="badge-branch"><i class="bi bi-diagram-2"></i> deva</span>
                    <a href="student_view.php" class="btn btn-outline-primary btn-sm">Uji Modul</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Data Preview Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1"><i class="bi bi-table text-primary me-2"></i>Pratinjau Data Mahasiswa (Live Database)</h5>
                <p class="text-muted small mb-0">Data yang tersimpan di tabel <code>student</code> database <code>my_campus</code></p>
            </div>
            <?php if ($dbConnected): ?>
                <div class="text-end">
                    <span class="badge bg-light text-dark border px-3 py-2">
                        Total: <strong><?= $totalStudents ?></strong> Mahasiswa &middot; Rata-rata IPK: <strong><?= number_format($avgGpa, 2) ?></strong>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body p-4">
            <?php if (!$dbConnected): ?>
                <div class="alert alert-warning border-0 p-4 text-center">
                    <i class="bi bi-exclamation-triangle fs-1 d-block mb-2 text-warning"></i>
                    <h5>Database Belum Siap / MySQL Belum Aktif</h5>
                    <p class="text-muted small mb-3">Nyalakan MySQL di XAMPP Control Panel lalu klik tombol inisialisasi di bawah untuk membuat database dan mengisi data uji mahasiswa secara otomatis.</p>
                    <a href="generate.php" class="btn btn-primary btn-sm px-4">Jalankan Inisialisasi Database (generate.php)</a>
                </div>
            <?php elseif (empty($sampleStudents)): ?>
                <div class="alert alert-info border-0 p-4 text-center">
                    <p class="mb-0">Tabel <code>student</code> masih kosong. <a href="student_add.php" class="fw-bold">Tambah data pertama</a> atau jalankan <a href="generate.php" class="fw-bold">generate.php</a>.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Tempat Lahir</th>
                                <th>Fakultas</th>
                                <th>Jurusan</th>
                                <th>IPK</th>
                                <th class="text-end">Aksi Pengujian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sampleStudents as $st): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($st['nim']) ?></td>
                                    <td><?= htmlspecialchars($st['name']) ?></td>
                                    <td><?= htmlspecialchars($st['birth_city']) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($st['faculty']) ?></span></td>
                                    <td><?= htmlspecialchars($st['department']) ?></td>
                                    <td><span class="badge bg-success"><?= number_format((float) $st['gpa'], 2) ?></span></td>
                                    <td class="text-end">
                                        <a href="student_edit.php?nim=<?= urlencode($st['nim']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3 pt-3 border-top">
                    <a href="student_view.php" class="btn btn-link text-decoration-none fw-semibold">
                        Lihat Seluruh Data Mahasiswa & Operasi CRUD Lengkap &rarr;
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Git Workflow & Cheat Sheet -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-terminal-fill text-dark me-2"></i>Panduan Singkat Git untuk Anggota Tim</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <h6 class="fw-bold text-primary mb-1">1. Sinkronisasi Awal</h6>
                        <pre class="bg-dark text-white p-2 rounded small mb-0">git checkout master&#10;git pull origin master</pre>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <h6 class="fw-bold text-success mb-1">2. Bekerja di Branch</h6>
                        <pre class="bg-dark text-white p-2 rounded small mb-0">git checkout -b [nama]&#10;git add .&#10;git commit -m "fitur"</pre>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <h6 class="fw-bold text-purple mb-1">3. Push & Pull Request</h6>
                        <pre class="bg-dark text-white p-2 rounded small mb-0">git push -u origin [nama]&#10;# Buka GitHub & PR ke master</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-muted small py-4 mt-3">
        Portal Monitoring Tim Si Gemays &middot; S1 Sistem Informasi Telkom University Surabaya &middot; 2026
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
