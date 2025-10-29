<?php
require_once '../config/session.php';
require_once '../config/database.php';

// Redirect jika sudah login
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

// Inisialisasi variabel agar tidak undefined
$error = '';

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Username atau password salah';
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan saat login';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - RS Survei Kepuasan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background image -->
    <div class="absolute inset-0">
        <img
            src="https://images.unsplash.com/photo-1503264116251-35a269479413?auto=format&fit=crop&w=1600&q=80"
            alt="Background"
            class="w-full h-full object-cover"
        >
        <!-- Overlay gelap agar teks kontras -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    </div>

    <!-- Konten Login -->
    <div class="relative z-10 max-w-md w-full mx-4">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-hospital text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">RS</h1>
            <p class="text-blue-100">Admin Panel - Survei Kepuasan</p>
        </div>

        <!-- Card Login -->
        <div class="bg-white/20 backdrop-blur-xl rounded-xl shadow-2xl p-8 border border-white/30">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Login Administrator</h2>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100/90 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- autocomplete off di form dan input, tidak ada value yang di-echo -->
            <form method="POST" class="space-y-6" autocomplete="off" id="loginForm">
                <div class="space-y-2">
                    <label for="username" class="block text-sm font-medium text-white">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        autocomplete="off"
                        class="w-full px-3 py-2 border border-white/40 rounded-md bg-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                        placeholder="Masukkan username">
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-white">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-3 py-2 border border-white/40 rounded-md bg-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                        placeholder="Masukkan password">
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-transparent">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="../index.php" class="text-blue-200 hover:text-white text-sm transition">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Survei
                </a>
            </div>
        </div>
    </div>

    <!-- Script untuk memastikan field selalu dikosongkan pada load / pageshow -->
    <script>
        (function(){
            function clearFields() {
                var u = document.getElementById('username');
                var p = document.getElementById('password');
                if (u) u.value = '';
                if (p) p.value = '';
            }

            // Clear saat halaman dimuat
            window.addEventListener('load', function(){ clearFields(); });

            // Clear ketika halaman dibuka dari cache (back/forward navigation)
            window.addEventListener('pageshow', function(event){
                // event.persisted true for BFCache in some browsers
                if (event.persisted) {
                    clearFields();
                } else {
                    // di beberapa browser pageshow fired even if not persisted; tetap bersihkan
                    clearFields();
                }
            });

            // Optionally clear on form submit to avoid values staying in memory
            var form = document.getElementById('loginForm');
            if (form) {
                form.addEventListener('submit', function(){
                    // delay sedikit agar POST tetap terkirim sebelum clear (untuk safety)
                    setTimeout(clearFields, 50);
                });
            }
        })();
    </script>
</body>
</html>
