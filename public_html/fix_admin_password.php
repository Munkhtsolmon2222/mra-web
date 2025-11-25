<?php
/**
 * Fix Admin Password - Run this once to fix the password
 * DELETE THIS FILE AFTER USE!
 */

require_once 'includes/config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$username = 'admin';
$password = 'admin123';

echo "<!DOCTYPE html><html><head><title>Fix Admin Password</title>";
echo "<style>body{font-family:Arial;padding:2rem;max-width:800px;margin:0 auto;background:#f9fafb;}";
echo ".success{background:#10b981;color:white;padding:1rem;border-radius:0.5rem;margin:1rem 0;}";
echo ".error{background:#ef4444;color:white;padding:1rem;border-radius:0.5rem;margin:1rem 0;}";
echo ".info{background:#3b82f6;color:white;padding:1rem;border-radius:0.5rem;margin:1rem 0;}";
echo "pre{background:#1f2937;color:#10b981;padding:1rem;border-radius:0.5rem;overflow:auto;font-size:12px;}";
echo "</style></head><body>";
echo "<h1>🔧 Fix Admin Password</h1>";

try {
    $pdo = getDB();
    echo "<div class='success'>✓ Database connection successful!</div>";
    
    // Generate correct password hash for 'admin123'
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<div class='info'>";
    echo "Updating password for user: <strong>" . htmlspecialchars($username) . "</strong><br>";
    echo "New password: <strong>" . htmlspecialchars($password) . "</strong>";
    echo "</div>";
    
    // Update the password
    $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = ?");
    $stmt->execute([$hash, $username]);
    
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>";
        echo "<strong>✓ Password updated successfully!</strong><br><br>";
        echo "You can now login with:<br>";
        echo "Username: <strong>" . htmlspecialchars($username) . "</strong><br>";
        echo "Password: <strong>" . htmlspecialchars($password) . "</strong><br>";
        echo "</div>";
        
        // Verify it works
        $stmt = $pdo->prepare("SELECT password_hash FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            echo "<div class='success'>✓ Verification test: PASSED - Password is working!</div>";
        } else {
            echo "<div class='error'>✗ Verification test: FAILED</div>";
        }
        
        echo "<div class='info'>";
        echo "<strong>Next steps:</strong><br>";
        echo "1. <a href='admin/index.php' style='color:white;text-decoration:underline;font-weight:bold;'>Try logging in now</a><br>";
        echo "2. <strong style='color:#fbbf24;'>DELETE THIS FILE (fix_admin_password.php) IMMEDIATELY!</strong>";
        echo "</div>";
    } else {
        echo "<div class='error'>No rows updated. User might not exist.</div>";
        
        // Try to create the user
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, $hash]);
        echo "<div class='success'>✓ Admin user created instead!</div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>✗ Database Error:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "</body></html>";
?>

