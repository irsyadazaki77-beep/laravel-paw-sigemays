<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/functions.php';

if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}