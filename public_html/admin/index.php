<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$debug_info = [];

// Enable error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $debug_info[] = "Username entered: " . htmlspecialchars($username);
    $debug_info[] = "Password provided: " . (empty($password) ? 'NO' : 'YES');
    
    if (!empty($username) && !empty($password)) {
        try {
            $pdo = getDB();
            $debug_info[] = "✓ Database connection: SUCCESS";
            
            $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin) {
                $debug_info[] = "✓ Admin user found in database";
                $debug_info[] = "User ID: " . $admin['id'];
                $debug_info[] = "Stored hash (first 30 chars): " . substr($admin['password_hash'], 0, 30) . "...";
                $debug_info[] = "Hash length: " . strlen($admin['password_hash']) . " characters";
                
                // Check if hash looks valid
                if (strpos($admin['password_hash'], '$2y$') === 0 || strpos($admin['password_hash'], '$2a$') === 0) {
                    $debug_info[] = "✓ Hash format: Valid bcrypt hash";
                } else {
                    $debug_info[] = "✗ Hash format: INVALID (should start with \$2y\$ or \$2a\$)";
                }
                
                $password_verify_result = password_verify($password, $admin['password_hash']);
                $debug_info[] = "Password verify result: " . ($password_verify_result ? '✓ TRUE (Password matches!)' : '✗ FALSE (Password does NOT match)');
                
                if ($password_verify_result) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    generateCSRFToken();
                    $debug_info[] = "✓ Session created successfully";
                    $debug_info[] = "Redirecting to dashboard...";
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Invalid username or password';
                    $debug_info[] = "✗ Login failed: Password verification failed";
                }
            } else {
                $error = 'Invalid username or password';
                $debug_info[] = "✗ Admin user NOT found in database";
                
                // Check if table exists and has any users
                try {
                    $checkStmt = $pdo->query("SELECT COUNT(*) as count FROM admin_users");
                    $count = $checkStmt->fetch();
                    $debug_info[] = "Total admin users in database: " . $count['count'];
                    
                    if ($count['count'] == 0) {
                        $debug_info[] = "⚠ WARNING: No admin users exist in database!";
                        $debug_info[] = "You need to run the schema.sql file or create an admin user.";
                    }
                } catch (Exception $e) {
                    $debug_info[] = "Error checking admin_users table: " . $e->getMessage();
                }
            }
        } catch (PDOException $e) {
            $error = 'Database connection error';
            $debug_info[] = "✗ Database error: " . $e->getMessage();
            $debug_info[] = "Check your database credentials in includes/config.php";
        } catch (Exception $e) {
            $error = 'An error occurred';
            $debug_info[] = "✗ General error: " . $e->getMessage();
        }
    } else {
        $error = 'Please enter both username and password';
        $debug_info[] = "✗ Missing username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MRA Awards 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0a1c44 0%, #0a1c44 100%);
            font-family: 'Inter', sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 100%;
        }
        .btn-primary {
            background: #0a1c44;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #0d2555;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(10, 28, 68, 0.3);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">MRA Awards 2025</h1>
                <p class="text-gray-600">Admin Login</p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($debug_info)): ?>
                <div class="bg-blue-50 border border-blue-200 text-blue-900 px-4 py-3 rounded-lg mb-4 text-sm">
                    <strong class="block mb-2">🔍 Debug Information:</strong>
                    <div class="space-y-1 font-mono text-xs">
                        <?php foreach ($debug_info as $info): ?>
                            <div><?php echo $info; ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        autocomplete="username"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        autocomplete="current-password"
                    >
                </div>
                
                <button type="submit" class="btn-primary w-full">
                    Login
                </button>
            </form>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center">
                    Default credentials: <strong>admin</strong> / <strong>admin123</strong><br>
                    (If this doesn't work, check debug info above)
                </p>
            </div>
        </div>
    </div>
</body>
</html>

