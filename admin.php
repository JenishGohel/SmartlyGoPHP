<?php
session_start();
require_once 'db.php';

// CHECK ADMIN LOGIN
if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? "Admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SmartlyGo</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .sidebar {
            width: 250px;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.2);
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- TOP NAVBAR -->
    <nav class="w-full bg-purple-700 text-white px-6 py-4 flex justify-between items-center shadow-lg">
        <div class="text-2xl font-bold">SmartlyGo Admin</div>
        
        <div class="flex items-center space-x-4">
            <span class="font-semibold">Hello, <?php echo $admin_name; ?></span>
            <a href="admin-logout.php" 
               class="bg-red-600 px-4 py-2 rounded-md hover:bg-red-700 transition"
               onclick="return confirm('Logout admin account?')">Logout</a>
        </div>
    </nav>

    <div class="flex">

        <!-- SIDEBAR -->
        <aside class="sidebar bg-purple-800 text-white min-h-screen p-6 space-y-4">

            <a href="admin-dashboard.php" 
               class="block sidebar-link px-4 py-3 rounded-md font-semibold">
                🏠 Dashboard
            </a>

            <a href="admin-manage-users.php" 
               class="block sidebar-link px-4 py-3 rounded-md font-semibold">
                👥 Manage Users
            </a>

            <a href="admin-manage-feedback.php" 
               class="block sidebar-link px-4 py-3 rounded-md font-semibold">
                💬 Manage Feedback
            </a>

            <a href="admin-manage-contact.php" 
               class="block sidebar-link px-4 py-3 rounded-md font-semibold">
                📧 Manage Contact
            </a>

            <a href="admin-manage-quiz.php" 
               class="block sidebar-link px-4 py-3 rounded-md font-semibold">
                📝 Manage Quiz
            </a>

            <a href="admin-logout.php" 
               class="block mt-10 bg-red-600 text-center px-4 py-3 rounded-md font-semibold hover:bg-red-700"
               onclick="return confirm('Do you want to logout?')">
                🚪 Sign Out
            </a>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-10">

            <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 shadow rounded-lg">
                    <h2 class="text-xl font-bold">Total Users</h2>
                    <p class="text-gray-600 mt-2">You can show count here</p>
                </div>

                <div class="bg-white p-6 shadow rounded-lg">
                    <h2 class="text-xl font-bold">Total Feedback</h2>
                    <p class="text-gray-600 mt-2">You can show count here</p>
                </div>

                <div class="bg-white p-6 shadow rounded-lg">
                    <h2 class="text-xl font-bold">Total Messages</h2>
                    <p class="text-gray-600 mt-2">You can show count here</p>
                </div>

            </div>

        </main>
    </div>

</body>
</html>
