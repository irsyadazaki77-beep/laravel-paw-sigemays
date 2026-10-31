<?php
$monthNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Data Mahasiswa') ?> - Si Gemays</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .team-banner {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .team-banner h3 {
            margin: 0 0 6px 0;
            font-size: 1.15rem;
            color: #ffffff;
        }
        .team-banner p {
            margin: 0;
            font-size: 0.85rem;
            opacity: 0.95;
            line-height: 1.4;
        }
        .team-members-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }
        .member-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="team-banner">
        <h3>🚀 Portal Kolaborasi Si Gemays - Sistem Informasi</h3>
        <p>Praktikum Pengembangan Aplikasi Web (PAW) - Modul 06 Version Control System (Git & GitHub)</p>
        <div class="team-members-list">
            <span class="member-badge">👑 Zaki Irsyada P. (108052500069) - Lead</span>
            <span class="member-badge">M Nabil Alhakim (Mahasiswa B)</span>
            <span class="member-badge">Daniel Harley K. (Mahasiswa C)</span>
            <span class="member-badge">Deva Mahendra P. (Mahasiswa D)</span>
        </div>
    </div>
    <header><h1>Data Mahasiswa</h1></header>
    <nav aria-label="Navigasi utama">
        <a href="index.php" style="background: #4f46e5; color: #ffffff; font-weight: bold;">📊 Dashboard Tim</a>
        <a href="student_view.php">Tampil</a>
        <a href="student_add.php">Tambah</a>
        <a href="logout.php">Logout (<?= e($_SESSION['username'] ?? 'User') ?>)</a>
    </nav>
