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

/* FETCH USER */
$stmt = $conn->prepare("SELECT name, email, stream, institute_name, state, country FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin-manage-users.php");
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

/* UPDATE USER */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stream = $_POST['stream'];
    $institute = $_POST['institute_name'];
    $state = $_POST['state'];
    $country = $_POST['country'];

    $stmt = $conn->prepare(
        "UPDATE users SET name=?, stream=?, institute_name=?, state=?, country=? WHERE user_id=?"
    );
    $stmt->bind_param(
        "sssssi",
        $name,
        $stream,
        $institute,
        $state,
        $country,
        $user_id
    );
    $stmt->execute();
    $stmt->close();

    header("Location: admin-manage-users.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-200 via-indigo-200 to-blue-200 min-h-screen flex items-center justify-center">

<div class="bg-white shadow-2xl rounded-xl w-full max-w-lg p-8 border-t-8 border-purple-600">
    <h2 class="text-3xl font-bold text-center text-purple-700 mb-6">Edit User Details</h2>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-600 font-medium">Full Name</label>
            <input name="name" value="<?= htmlspecialchars($user['name']) ?>" 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div>
            <label class="block text-gray-600 font-medium">Stream</label>
            <input name="stream" value="<?= htmlspecialchars($user['stream']) ?>" 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div>
            <label class="block text-gray-600 font-medium">Institute Name</label>
            <input name="institute_name" value="<?= htmlspecialchars($user['institute_name']) ?>" 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div>
            <label class="block text-gray-600 font-medium">State</label>
            <input name="state" value="<?= htmlspecialchars($user['state']) ?>" 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div>
            <label class="block text-gray-600 font-medium">Country</label>
            <input name="country" value="<?= htmlspecialchars($user['country']) ?>" 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-400 outline-none">
        </div>

        <div class="flex justify-between pt-4">
            <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg shadow-md transition">
                Update User
            </button>

            <a href="admin-manage-users.php" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg shadow-md transition">
                Cancel
            </a>
        </div>
    </form>
</div>

</body>
</html>
