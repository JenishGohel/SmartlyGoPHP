<?php
session_start();
require_once 'db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'];

/* Fetch Counts */
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_feedback = $conn->query("SELECT COUNT(*) AS total FROM feedback")->fetch_assoc()['total'];
$total_contacts = $conn->query("SELECT COUNT(*) AS total FROM contact_messages")->fetch_assoc()['total'];

date_default_timezone_set('Asia/Kolkata');
$today_date = date("l, d M Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
    min-height:100vh;
    overflow-x:hidden;
}

/* Floating background objects */
.float-object {
    position: fixed;
    font-size:60px;
    opacity:0.12;
    z-index:0;
    animation: float 20s ease-in-out infinite;
    filter: blur(1px);
}
.float1 { top:10%; left:10%; }
.float2 { top:60%; right:15%; animation-duration:25s; }
.float3 { bottom:15%; left:20%; animation-duration:18s; }
.float4 { top:40%; right:5%; animation-duration:22s; }

@keyframes float {
    0%,100%{transform:translate(0,0);}
    50%{transform:translate(0,-20px);}
}

/* Sidebar */
.sidebar {
    background: rgba(124,58,237,0.9);
    color:white;
    min-height:100vh;
    padding:2rem;
    width:250px;
}
.sidebar a {
    display:block;
    margin:1rem 0;
    padding:0.5rem 1rem;
    border-radius:0.5rem;
    transition:0.3s;
    font-weight:600;
}
.sidebar a:hover { background: rgba(255,255,255,0.15); }
.sidebar .logout { background:#ef4444; }
.sidebar .logout:hover { background:#dc2626; }

.content {
    flex:1;
    padding:2rem;
    position:relative;
}

/* Date display */
.date-display {
    position:absolute;
    top:1rem;
    right:2rem;
    font-weight:600;
    color:#6b21a8;
}

/* Stat Cards */
.stat-card {
    background:white;
    border-radius:1rem;
    padding:2rem;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    transition:0.3s;
}
.stat-card:hover {
    transform: translateY(-6px);
    box-shadow:0 20px 40px rgba(0,0,0,0.25);
}

/* Responsive */
@media(max-width:768px){
    .sidebar { position:absolute; left:-100%; transition:0.3s; z-index:50; }
    .sidebar.active { left:0; }
}
</style>
</head>

<body>

<!-- Floating Icons -->
<div class="float-object float1">💻</div>
<div class="float-object float2">🎮</div>
<div class="float-object float3">🎯</div>
<div class="float-object float4">📚</div>

<div class="flex">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="text-2xl font-bold mb-6">Admin Panel</h2>
        <a href="admin-dashboard.php" class="bg-white bg-opacity-20">Dashboard</a>
        <a href="admin-manage-users.php">Manage Users</a>
        <a href="admin-manage-feedback.php">Manage Feedback</a>
        <a href="admin-manage-contact.php">Manage Contact</a>
        <a href="admin-logout.php" class="logout mt-6">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <button class="md:hidden mb-4 px-4 py-2 bg-purple-700 text-white rounded" onclick="toggleSidebar()">☰ Menu</button>

        <div class="date-display"><?= $today_date ?></div>

        <h1 class="text-4xl font-bold mb-8 text-purple-800">
            Welcome, <?= htmlspecialchars($admin_name); ?> 👋
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="stat-card">
                <h2 class="text-lg font-semibold text-gray-600">Total Users</h2>
                <p class="text-4xl font-bold text-purple-600 mt-2"><?= $total_users ?></p>
            </div>

            <div class="stat-card">
                <h2 class="text-lg font-semibold text-gray-600">Total Feedback</h2>
                <p class="text-4xl font-bold text-green-600 mt-2"><?= $total_feedback ?></p>
            </div>

            <div class="stat-card">
                <h2 class="text-lg font-semibold text-gray-600">Contact Messages</h2>
                <p class="text-4xl font-bold text-blue-600 mt-2"><?= $total_contacts ?></p>
            </div>

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
