<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function verify_csrf(): void
{
    $submitted = (string) ($_POST['_csrf_token'] ?? '');
    if ($submitted === '' || !hash_equals(csrf_token(), $submitted)) {
        http_response_code(400);
        exit('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

function set_flash(string $message): void
{
    $_SESSION['_flash_message'] = $message;
}

function take_flash(): ?string
{
    if (!isset($_SESSION['_flash_message'])) {
        return null;
    }
    $message = (string) $_SESSION['_flash_message'];
    unset($_SESSION['_flash_message']);
    return $message;
}

function student_input_from_post(): array
{
    return [
        'nim' => trim((string) ($_POST['nim'] ?? '')),
        'name' => trim((string) ($_POST['name'] ?? '')),
        'birth_city' => trim((string) ($_POST['birth_city'] ?? '')),
        'birth_day' => trim((string) ($_POST['birth_day'] ?? '1')),
        'birth_month' => trim((string) ($_POST['birth_month'] ?? '1')),
        'birth_year' => trim((string) ($_POST['birth_year'] ?? '1996')),
        'faculty' => trim((string) ($_POST['faculty'] ?? 'FTIB')),
        'department' => trim((string) ($_POST['department'] ?? '')),
        'gpa' => trim((string) ($_POST['gpa'] ?? '')),
    ];
}

function validate_student_input(array $student, mysqli $connection, ?string $originalNim = null): array
{
    $errors = [];
    if (!preg_match('/^[0-9]{8}$/', $student['nim'])) {
        $errors[] = 'NIM harus terdiri dari 8 digit angka.';
    } else {
        if ($originalNim === null) {
            $statement = $connection->prepare('SELECT nim FROM student WHERE nim = ?');
            $statement->bind_param('s', $student['nim']);
        } else {
            $statement = $connection->prepare('SELECT nim FROM student WHERE nim = ? AND nim <> ?');
            $statement->bind_param('ss', $student['nim'], $originalNim);
        }
        $statement->execute();
        $statement->store_result();
        if ($statement->num_rows > 0) {
            $errors[] = 'NIM sudah digunakan mahasiswa lain.';
        }
        $statement->close();
    }
    if ($student['name'] === '') {
        $errors[] = 'Nama wajib diisi.';
    }
    if ($student['birth_city'] === '') {
        $errors[] = 'Tempat lahir wajib diisi.';
    }
    if (!in_array($student['faculty'], ['FTIB', 'FTEIC'], true)) {
        $errors[] = 'Pilih fakultas yang tersedia.';
    }
    if ($student['department'] === '') {
        $errors[] = 'Jurusan wajib diisi.';
    }
    if (!is_numeric($student['gpa']) || (float) $student['gpa'] <= 0 || (float) $student['gpa'] > 4) {
        $errors[] = 'IPK harus berupa angka lebih dari 0 sampai 4.';
    }
    $day = filter_var($student['birth_day'], FILTER_VALIDATE_INT);
    $month = filter_var($student['birth_month'], FILTER_VALIDATE_INT);
    $year = filter_var($student['birth_year'], FILTER_VALIDATE_INT);
    if ($day === false || $month === false || $year === false || $year < 1990 || $year > 2005 || !checkdate((int) $month, (int) $day, (int) $year)) {
        $errors[] = 'Tanggal lahir tidak valid.';
    }
    return $errors;
}

function birth_date_from_input(array $student): string
{
    return sprintf('%04d-%02d-%02d', (int) $student['birth_year'], (int) $student['birth_month'], (int) $student['birth_day']);
}
