<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

require_once "connection.php";

$programs_by_faculty = [
    "FTE" => [
        "Teknik Telekomunikasi",
        "Teknik Elektro",
        "Teknik Komputer",
    ],
    "FRI" => [
        "Teknik Industri",
        "Sistem Informasi",
        "Digital Supply Chain",
    ],
    "FIF" => [
        "Informatika",
        "Teknologi Informasi",
        "Rekayasa Perangkat Lunak",
        "Data Sains",
    ],
    "FEB" => [
        "Digital Business",
    ],
];

$student = [
    "nim" => "",
    "name" => "",
    "birth_city" => "",
    "birth_date" => "",
    "faculty" => "",
    "department" => "",
    "gpa" => "",
];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($student as $field => $value) {
        $posted_value = $_POST[$field] ?? "";
        $student[$field] = is_string($posted_value) ? trim($posted_value) : "";
    }

    if (in_array("", $student, true)) {
        $error_message = "Semua kolom wajib diisi.";
    } elseif (!isset($programs_by_faculty[$student["faculty"]])
        || !in_array($student["department"], $programs_by_faculty[$student["faculty"]], true)) {
        $error_message = "Program studi tidak sesuai dengan fakultas yang dipilih.";
    } elseif (!is_numeric($student["gpa"]) || (float) $student["gpa"] < 0 || (float) $student["gpa"] > 4) {
        $error_message = "IPK harus berupa angka antara 0 dan 4.";
    } elseif (!DateTime::createFromFormat("!Y-m-d", $student["birth_date"])
        || DateTime::createFromFormat("!Y-m-d", $student["birth_date"])->format("Y-m-d") !== $student["birth_date"]) {
        $error_message = "Tanggal lahir tidak valid.";
    } else {
        $statement = mysqli_prepare(
            $connection,
            "INSERT INTO student (nim, name, birth_city, birth_date, faculty, department, gpa) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param(
            $statement,
            "ssssssd",
            $student["nim"],
            $student["name"],
            $student["birth_city"],
            $student["birth_date"],
            $student["faculty"],
            $student["department"],
            $student["gpa"]
        );

        try {
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
            header("Location: student_view.php?message=" . urlencode("Data mahasiswa berhasil ditambahkan."));
            exit;
        } catch (mysqli_sql_exception $exception) {
            mysqli_stmt_close($statement);
            if ($exception->getCode() === 1062) {
                $error_message = "NIM tersebut sudah terdaftar.";
            } else {
                throw $exception;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Mahasiswa</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .student-form {
            max-width: 760px;
            margin: 0 auto;
        }

        .student-form .form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 16px;
        }

        .student-form .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .student-form label {
            font-weight: 600;
            color: #182230;
        }

        .student-form input {
            width: 100%;
        }

        .student-form select {
            width: 100%;
        }

        .student-form .center-row {
            display: flex;
            justify-content: center;
            margin-top: 8px;
        }

        .student-form .form-group.centered {
            width: min(100%, 340px);
        }

        .student-form .form-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 18px;
        }

        @media (max-width: 640px) {
            .student-form .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Tambah Data Mahasiswa</h1>
        <?php if ($error_message !== ""): ?>
            <p class="error" role="alert"><?= htmlspecialchars($error_message, ENT_QUOTES, "UTF-8") ?></p>
        <?php endif; ?>
        <form class="form-card student-form" method="post" action="student_add.php">
            <div class="form-row">
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input id="nim" name="nim" maxlength="8" required value="<?= htmlspecialchars($student["nim"], ENT_QUOTES, "UTF-8") ?>">
                </div>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input id="name" name="name" maxlength="100" required value="<?= htmlspecialchars($student["name"], ENT_QUOTES, "UTF-8") ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="birth_city">Kota Lahir</label>
                    <input id="birth_city" name="birth_city" maxlength="50" required value="<?= htmlspecialchars($student["birth_city"], ENT_QUOTES, "UTF-8") ?>">
                </div>
                <div class="form-group">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input id="birth_date" type="date" name="birth_date" required value="<?= htmlspecialchars($student["birth_date"], ENT_QUOTES, "UTF-8") ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="faculty">Fakultas</label>
                    <select id="faculty" name="faculty" required>
                        <option value="">Pilih fakultas</option>
                        <?php foreach ($programs_by_faculty as $faculty => $departments): ?>
                            <option value="<?= htmlspecialchars($faculty, ENT_QUOTES, "UTF-8") ?>" <?= $student["faculty"] === $faculty ? "selected" : "" ?>>
                                <?= htmlspecialchars($faculty, ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="department">Program Studi</label>
                    <select id="department" name="department" required disabled>
                        <option value="">Pilih program studi</option>
                    </select>
                </div>
            </div>

            <div class="form-row center-row">
                <div class="form-group centered">
                    <label for="gpa">IPK</label>
                    <input id="gpa" type="number" name="gpa" min="0" max="4" step="0.01" required value="<?= htmlspecialchars($student["gpa"], ENT_QUOTES, "UTF-8") ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Simpan</button>
                <a class="button secondary" href="student_view.php">Batal</a>
            </div>
        </form>
    </main>
    <script>
        const programsByFaculty = <?= json_encode($programs_by_faculty, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const facultySelect = document.getElementById("faculty");
        const departmentSelect = document.getElementById("department");
        const selectedDepartment = <?= json_encode($student["department"], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        function updateDepartments(selectedValue = "") {
            const departments = programsByFaculty[facultySelect.value] || [];
            departmentSelect.replaceChildren(new Option("Pilih program studi", ""));
            departments.forEach((department) => {
                departmentSelect.add(new Option(department, department, false, department === selectedValue));
            });
            departmentSelect.disabled = departments.length === 0;
        }

        facultySelect.addEventListener("change", () => updateDepartments());
        updateDepartments(selectedDepartment);
    </script>
</body>
</html>
