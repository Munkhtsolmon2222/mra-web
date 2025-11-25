<?php
/**
 * TEMPORARY SCRIPT - Create/Update Admin User
 * DELETE THIS FILE AFTER USE!
 */

require_once 'includes/config.php';

// Enable error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

$username = 'admin';
$password = 'admin123'; // Change this if needed

echo "<!DOCTYPE html><html><head><title>Create Admin User</title>";
echo "<style>body{font-family:Arial;padding:2rem;max-width:800px;margin:0 auto;}";
echo ".success{background:#10b981;color:white;padding:1rem;border-radius:0.5rem;margin:1rem 0;}";
echo ".error{background:#ef4444;color:white;padding:1rem;border-radius:0.5rem;margin:1rem 0;}";
echo ".info{background:#3b82f6;color:white;padding:1rem;border-radius:0.5rem;margin:1rem 0;}";
echo "pre{background:#f3f4f6;padding:1rem;border-radius:0.5rem;overflow:auto;}";
echo "</style></head><body>";
echo "<h1>MRA Awards - Create Admin User</h1>";

try {
    $pdo = getDB();
    echo "<div class='success'>✓ Database connection successful!</div>";
    
    // Generate password hash
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<div class='info'>Generated password hash for: <strong>" . htmlspecialchars($password) . "</strong></div>";
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id, username FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $exists = $stmt->fetch();
    
    if ($exists) {
        // Update existing
        $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = ?");
        $stmt->execute([$hash, $username]);
        echo "<div class='success'>";
        echo "<strong>✓ Admin password updated successfully!</strong><br><br>";
        echo "Username: <strong>" . htmlspecialchars($username) . "</strong><br>";
        echo "Password: <strong>" . htmlspecialchars($password) . "</strong><br>";
        echo "</div>";
    } else {
        // Create new
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, $hash]);
        echo "<div class='success'>";
        echo "<strong>✓ Admin user created successfully!</strong><br><br>";
        echo "Username: <strong>" . htmlspecialchars($username) . "</strong><br>";
        echo "Password: <strong>" . htmlspecialchars($password) . "</strong><br>";
        echo "</div>";
    }
    
    // Verify it works
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password_hash'])) {
        echo "<div class='success'>✓ Password verification test: PASSED</div>";
    } else {
        echo "<div class='error'>✗ Password verification test: FAILED</div>";
    }
    
    echo "<div class='info'>";
    echo "<strong>⚠ IMPORTANT:</strong><br>";
    echo "1. Test login at: <a href='admin/index.php' style='color:white;text-decoration:underline;'>admin/index.php</a><br>";
    echo "2. <strong style='color:#fbbf24;'>DELETE THIS FILE (create_admin.php) IMMEDIATELY!</strong>";
    echo "</div>";
    
    echo "<h2>Debug Info:</h2>";
    echo "<pre>";
    echo "Database: " . DB_NAME . "\n";
    echo "Host: " . DB_HOST . "\n";
    echo "User: " . DB_USER . "\n";
    echo "Admin ID: " . $admin['id'] . "\n";
    echo "Hash (first 50 chars): " . substr($admin['password_hash'], 0, 50) . "...\n";
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>✗ Database Error:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "<br><br>Check your database credentials in includes/config.php";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<strong>✗ Error:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "</body></html>";
?>

