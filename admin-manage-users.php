<?php
session_start();
require_once 'db.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit;
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Fetch all users
$sql = "SELECT * FROM users ORDER BY user_id DESC";
$result = $conn->query($sql);

// Date display
date_default_timezone_set('Asia/Kolkata');
$today_date = date("l, d M Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#fef3f7,#e9d5ff,#ddd6fe);
    min-height:100vh;
}

/* Sidebar */
.sidebar{
    background:rgba(124,58,237,0.9);
    color:white;
    min-height:100vh;
    padding:2rem;
    width:250px;
}
.sidebar a{
    display:block;
    margin:1rem 0;
    padding:0.5rem 1rem;
    border-radius:0.5rem;
    font-weight:600;
}
.sidebar a:hover{background:rgba(255,255,255,0.15);}
.sidebar .logout{background:#ef4444;}
.sidebar .logout:hover{background:#dc2626;}

.content{flex:1;padding:2rem;position:relative;}

.date-display{
    position:absolute;
    top:1rem;
    right:2rem;
    font-weight:600;
    color:#6b21a8;
}

@media(max-width:768px){
    .sidebar{position:absolute;left:-100%;transition:0.3s;z-index:50;}
    .sidebar.active{left:0;}
}
</style>
</head>

<body>

<div class="flex">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="text-2xl font-bold mb-6">Admin Panel</h2>
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="admin-manage-users.php" class="bg-white bg-opacity-20">Manage Users</a>
        <a href="admin-manage-feedback.php">Manage Feedback</a>
        <a href="admin-manage-contact.php">Manage Contact</a>
        <a href="admin-logout.php" class="logout mt-6">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <button class="md:hidden mb-4 px-4 py-2 bg-purple-700 text-white rounded" onclick="toggleSidebar()">☰ Menu</button>

        <div class="date-display"><?= $today_date ?></div>
        <h1 class="text-3xl font-bold mb-6">Manage Users</h1>

        <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Stream</th>
                        <th class="px-4 py-3">Institute</th>
                        <th class="px-4 py-3">State</th>
                        <th class="px-4 py-3">Country</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                <?php
                if($result && $result->num_rows > 0):
                    $i = 1;
                    while($user = $result->fetch_assoc()):
                ?>
                    <tr class="hover:bg-purple-50">
                        <td class="px-4 py-3 font-semibold"><?= $i++; ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['name']); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['email']); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['stream']); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['institute_name']); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['state']); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($user['country']); ?></td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <a href="admin-edit-user.php?id=<?= $user['user_id']; ?>"
                               class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                               Edit
                            </a>
                            <a href="admin-delete-user.php?id=<?= $user['user_id']; ?>"
                               onclick="return confirm('Are you sure?')"
                               class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500">No users found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('active');
}
</script>

</body>
</html>
