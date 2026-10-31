# Project Kolaborasi PHP CRUD MySQL - Kelompok Si Gemays

**Mata Kuliah:** Praktikum Pengembangan Aplikasi Web (PAW)  
**Modul 06:** Version Control System (Git & GitHub)  
**Program Studi:** S1 Sistem Informasi - Telkom University Surabaya (Tahun 2026)

---

## 👥 Profil Anggota Kelompok & Pembagian Tugas:

| No | Nama Mahasiswa | NIM / Akun | Peran / Modul Kolaborasi | Branch Git |
|:--:|:---|:---|:---|:---:|
| 1 | **Zaki Irsyada Putra** | **108052500069** *(Lead)* | Inisiasi Repository, Setup Base Project PHP, Banner Informasi Tim, & Modul Tampil Data (`student_view.php`) | `zaki` |
| 2 | **M Nabil Alhakim** | Mahasiswa B | Modul Tambah Data Mahasiswa (`student_add.php`) & Validasi Input | `nabil` |
| 3 | **Daniel Harley Kamasaan** | Mahasiswa C | Modul Autentikasi Pengguna (`login.php`, `logout.php`) & Session Guard | `daniel` |
| 4 | **Deva Mahendra Putra** | Mahasiswa D | Modul Manajemen Pengubahan (`student_edit.php`) & Penghapusan (`student_delete.php`) | `deva` |

---

## 📂 Struktur Direktori Project (Native PHP & MySQL):
- `config.php`: Konfigurasi parameter koneksi database MySQL.
- `connection.php`: Inisialisasi koneksi MySQLi dengan penanganan error.
- `generate.php`: Generator otomatis database `kampusku` dan tabel `student` & `admin`.
- `index.php`: Gerbang utama routing session (mengarahkan ke login atau student_view).
- `login.php` & `logout.php`: Modul otentikasi admin.
- `student_view.php`: Menampilkan daftar seluruh data mahasiswa dalam tabel.
- `student_add.php`: Formulir penambahan mahasiswa baru dengan validasi data.
- `student_edit.php`: Formulir pengubahan data mahasiswa yang sudah ada.
- `student_delete.php`: Proses penghapusan data mahasiswa dengan proteksi CSRF.
- `assets/`: Berisi file styling CSS (`style.css` dan `login.css`).
- `includes/`: Template parsial (`header.php`, `footer.php`, `bootstrap.php`, `functions.php`, `student_form_fields.php`).

---

## 🚀 Alur Kerja Kolaborasi Git:
1. **Inisiasi Awal (Mahasiswa A):** Setup repository, push branch `master`, dan undang anggota tim sebagai Collaborator di GitHub.
2. **Clone & Percabangan (Mahasiswa B, C, D):** Menggandakan repository dan membuat branch sesuai nama masing-masing (`git checkout -b <nama>`).
3. **Pengerjaan Modul & Push:** Masing-masing anggota menyelesaikan modul pada branch masing-masing lalu melakukan `git push origin <nama-branch>`.
4. **Pull Request & Code Review:** Mengajukan Pull Request ke branch `master` di GitHub, me-review perubahan, dan melakukan merge.
5. **Sinkronisasi Kode (Semua Anggota):** Seluruh anggota menarik pembaruan kode terbaru ke lokal melalui `git pull origin master`.
