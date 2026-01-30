<?php
session_start();
require_once 'db.php';

if (isset($_POST['login_btn'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch admin
    $sql = "SELECT * FROM admin WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $admin = $result->fetch_assoc();

        // Compare MD5 password
        if ($admin['admin_password'] === md5($password)) {

            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name'];

            header("Location: admin-dashboard.php");
            exit;

        } else {
            $error = "Invalid password";
        }

    } else {
        $error = "Admin not found";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #fdf2f8, #e9d5ff, #c4b5fd);
            height: 100vh;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        /* Floating Animated Objects */
        .float-object {
            position: absolute;
            font-size: 70px;
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
            filter: blur(1px);
            user-select: none;
        }

        .obj1 { top: 10%; left: 5%; animation-delay: 0s; }
        .obj2 { top: 65%; left: 15%; animation-delay: 1.5s; }
        .obj3 { top: 30%; right: 10%; animation-delay: 3s; }
        .obj4 { bottom: 10%; right: 20%; animation-delay: 4.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-25px); }
        }

        /* Card Glow Animation */
        .login-card {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: glow 4s infinite alternate;
        }

        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(168, 85, 247, 0.4); }
            to { box-shadow: 0 0 40px rgba(236, 72, 153, 0.5); }
        }
    </style>
</head>

<body class="flex items-center justify-center relative">

    <!-- Floating Emojis -->
    <div class="float-object obj1">💻</div>
    <div class="float-object obj2">✨</div>
    <div class="float-object obj3">🚀</div>
    <div class="float-object obj4">📚</div>

    <!-- LOGIN CARD -->
    <div class="login-card w-full max-w-md p-10 rounded-2xl shadow-2xl relative z-20">

        <h2 class="text-4xl font-bold text-center mb-6 text-purple-700 drop-shadow-sm">
            Admin Login
        </h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center font-semibold shadow">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label class="block mb-2 font-semibold text-gray-800">Email</label>
            <input type="email" name="email" required
                class="w-full p-3 mb-4 rounded-lg border outline-none focus:ring-2 focus:ring-purple-500 shadow-sm">

            <label class="block mb-2 font-semibold text-gray-800">Password</label>
            <input type="password" name="password" required
                class="w-full p-3 mb-6 rounded-lg border outline-none focus:ring-2 focus:ring-purple-500 shadow-sm">

            <button type="submit" name="login_btn"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white p-3 text-lg font-semibold rounded-lg shadow-lg hover:opacity-90 transition">
                Login
            </button>

        </form>

        <!-- Small footer -->
        <p class="text-center mt-6 text-gray-700 font-medium text-sm opacity-75">
            SmartlyGoPHP Admin Portal
        </p>

    </div>

</body>
</html>
