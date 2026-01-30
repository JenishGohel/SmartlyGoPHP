<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin-manage-users.php");
    exit;
}

$user_id = (int)$_GET['id'];

// Prevent deleting admin accidentally (optional safety)
if ($user_id === (int)$_SESSION['admin_id']) {
    header("Location: admin-manage-users.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

header("Location: admin-manage-users.php");
exit;
