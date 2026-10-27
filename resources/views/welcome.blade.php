<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Si Gemays - Portal Aplikasi Web PAW</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-purple: #6f42c1;
            --primary-dark: #593196;
        }
        .bg-purple {
            background-color: var(--primary-purple) !important;
        }
        .hero-section {
            background: linear-gradient(135deg, #6f42c1 0%, #4b1088 100%);
            color: white;
            padding: 80px 0 60px;
        }
        .card-team {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.08);
        }
        .card-team:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(111, 66, 193, 0.15);
        }
        .badge-branch {
            font-family: monospace;
            font-size: 0.85rem;
            background-color: #f1f3f5;
            color: #495057;
            padding: 4px 10px;
            border-radius: 6px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-purple sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <i class="bi bi-layers-fill me-2 fs-4"></i> Si Gemays
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#home"><i class="bi bi-house-door me-1"></i> Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tim"><i class="bi bi-people me-1"></i> Tim Kolaborasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur"><i class="bi bi-code-slash me-1"></i> Fitur Modul</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center" id="home">
        <div class="container">
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-3 fw-semibold">
                <i class="bi bi-git text-danger me-1"></i> Praktikum Modul 6 - Version Control System
            </span>
            <h1 class="display-4 fw-bold mb-3">Selamat Datang di Portal Si Gemays</h1>
            <p class="lead mb-4 mx-auto" style="max-width: 720px;">
                Platform kolaborasi tim berbasis Laravel 11 & GitHub untuk tugas besar Pengembangan Aplikasi Web (PAW) - Telkom University Surabaya.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#tim" class="btn btn-warning btn-lg px-4 fw-semibold text-dark shadow-sm">
                    <i class="bi bi-people-fill me-1"></i> Lihat Tim Pengembang
                </a>
                <a href="https://github.com/irsyadazaki77-beep/laravel-paw-sigemays" target="_blank" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-github me-1"></i> Buka Repository
                </a>
            </div>
        </div>
    </section>

    <!-- Info Banner -->
    <div class="container my-5">
        <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-2 me-3 text-primary"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Status Kolaborasi Git & GitHub:</h5>
                <p class="mb-0 text-secondary">
                    Branch aktif: <code>zaki</code> | Fitur: <strong>Inisialisasi Home Portal & Routing Terpusat (Mahasiswa A)</strong>.
                </p>
            </div>
        </div>
    </div>

    <!-- Tim Pengembang (Cards) -->
    <section class="container my-5" id="tim">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Struktur Kolaborasi Tim</h2>
            <p class="text-muted">Pembagian tugas dan percabangan branch Git untuk setiap mahasiswa</p>
        </div>

        <div class="row g-4">
            @foreach($projectData['teamMembers'] as $member)
                <div class="col-md-6 col-lg-3">
                    <div class="card card-team h-100 bg-white p-3 text-center">
                        <div class="card-body">
                            <div class="rounded-circle bg-purple text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                <i class="bi bi-person-fill fs-2"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-1">{{ $member['name'] }}</h5>
                            <p class="text-primary small fw-semibold mb-2">{{ $member['role'] }}</p>
                            <hr class="my-2">
                            <div class="mt-3">
                                <span class="d-block small text-muted mb-1">Branch Pengerjaan:</span>
                                <span class="badge-branch"><i class="bi bi-diagram-2 me-1"></i> {{ $member['branch'] }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <span class="badge {{ $member['status'] == 'Lead' ? 'bg-success' : 'bg-secondary' }} px-3 py-1 rounded-pill">
                                {{ $member['status'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Fitur & Alur Kerja -->
    <section class="container my-5" id="fitur">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="card-body">
                <h3 class="fw-bold mb-4 text-purple"><i class="bi bi-diagram-3-fill me-2"></i> Alur Kerja Git Kelompok (Modul 10):</h3>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-primary">1. Clone & Branch</h6>
                            <p class="small text-muted mb-0">Setiap anggota clone repo dan buat branch nama masing-masing (<code>git checkout -b &lt;nama&gt;</code>).</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-success">2. Koding Mandiri</h6>
                            <p class="small text-muted mb-0">Mengerjakan fitur masing-masing di branch terisolasi tanpa merusak branch master.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-warning">3. Push & Pull Request</h6>
                            <p class="small text-muted mb-0">Push branch ke GitHub dan ajukan PR untuk direview ketua / tim sebelum merge.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-danger">4. Sync Master</h6>
                            <p class="small text-muted mb-0">Semua anggota checkout ke master dan lakukan <code>git pull origin master</code>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 text-center mt-5 text-muted small">
        <div class="container">
            <p class="mb-1 fw-semibold text-dark">{{ $projectData['appName'] }} &copy; 2026</p>
            <p class="mb-0">{{ $projectData['course'] }} &middot; {{ $projectData['university'] }}</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
