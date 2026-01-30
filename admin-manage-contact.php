<?php
session_start();
require_once 'db.php';

// Check admin session
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

$page_title = "Manage Contact Messages";

// Fetch contact messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);

date_default_timezone_set('Asia/Kolkata');
$today_date = date("l, d M Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $page_title ?></title>

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
    <a href="admin-manage-users.php">Manage Users</a>
    <a href="admin-manage-feedback.php">Manage Feedback</a>
    <a href="admin-manage-contact.php" class="bg-white bg-opacity-20">Manage Contact</a>
    <a href="admin-logout.php" class="logout mt-6">Logout</a>
</aside>

<!-- Main Content -->
<main class="content">
    <button class="md:hidden mb-4 px-4 py-2 bg-purple-700 text-white rounded" onclick="toggleSidebar()">☰ Menu</button>

    <div class="date-display"><?= $today_date ?></div>

    <h1 class="text-3xl font-bold mb-6 text-purple-800"><?= $page_title ?></h1>

    <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-purple-700 text-white">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Subject</th>
                    <th class="px-4 py-3">Message</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-purple-50">
                    <td class="px-4 py-3 font-semibold"><?= $i++; ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['name']); ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['email']); ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['subject']); ?></td>
                    <td class="px-4 py-3 max-w-xs truncate"
                        title="<?= htmlspecialchars($row['message']); ?>">
                        <?= htmlspecialchars($row['message']); ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-white text-xs
                        <?= $row['status']=='Resolved' ? 'bg-green-600' : 'bg-yellow-500'; ?>">
                        <?= htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <?= date("d M Y", strtotime($row['created_at'])); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        No contact messages found
                    </td>
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
