<?php
session_start();
if (!empty($_SESSION['username'])) {
    header('Location: student_view.php');
} else {
    header('Location: login.php');
}
exit;