<?php
/**
 * Utility Functions
 * MRA Awards 2025 Voting System
 */

require_once __DIR__ . '/config.php';

/**
 * Sanitize input to prevent XSS
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require admin authentication
 */
function requireAdmin() {
    if (!isAdmin()) {
        $adminPath = strpos($_SERVER['PHP_SELF'], '/admin/') !== false 
            ? 'index.php' 
            : 'admin/index.php';
        header('Location: ' . $adminPath);
        exit;
    }
}

/**
 * Send JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get all categories
 */
function getCategories() {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
    return $stmt->fetchAll();
}

/**
 * Get category by ID
 */
function getCategoryById($id) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Get participants by category
 */
function getParticipantsByCategory($categoryId) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM participants WHERE category_id = ? ORDER BY name ASC");
    $stmt->execute([$categoryId]);
    return $stmt->fetchAll();
}

/**
 * Get vote count for participant
 */
function getVoteCount($participantId) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM votes WHERE participant_id = ?");
    $stmt->execute([$participantId]);
    $result = $stmt->fetch();
    return (int)$result['count'];
}

/**
 * Check if user has already voted in category within the last 2 hours
 * Primary check: Session ID (prevents same device from voting multiple times within 2 hours)
 * Secondary check: IP + Session combination (allows different devices on same network)
 * 
 * Returns array with 'has_voted' (boolean) and 'can_vote_again_at' (timestamp or null)
 */
function hasVotedInCategory($categoryId, $ip, $sessionId) {
    $pdo = getDB();
    
    // PRIMARY CHECK: Session ID - check if voted within last 2 hours
    // This prevents voting from the same device even if network/IP changes
    if ($sessionId) {
        $stmt = $pdo->prepare("
            SELECT voted_at 
            FROM votes 
            WHERE category_id = ? AND voter_session = ? 
            ORDER BY voted_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$categoryId, $sessionId]);
        $result = $stmt->fetch();
        
        if ($result) {
            $lastVoteTime = strtotime($result['voted_at']);
            $currentTime = time();
            $timeDifference = $currentTime - $lastVoteTime;
            $twoHoursInSeconds = 2 * 60 * 60; // 2 hours

            
            // If less than 2 hours have passed, user cannot vote yet
            if ($timeDifference < $twoHoursInSeconds) {
                return [
                    'has_voted' => true,
                    'can_vote_again_at' => date('Y-m-d H:i:s', $lastVoteTime + $twoHoursInSeconds),
                    'time_remaining' => $twoHoursInSeconds - $timeDifference
                ];
            }
            // If 2+ hours have passed, user can vote again
            return [
                'has_voted' => false,
                'can_vote_again_at' => null,
                'time_remaining' => 0
            ];
        }
    }
    
    // SECONDARY CHECK: IP + Session combination (for additional validation)
    // This is mainly for logging/analytics since session check above handles the main logic
    if ($ip && $ip !== '0.0.0.0' && $ip !== 'UNKNOWN' && $sessionId) {
        $stmt = $pdo->prepare("
            SELECT voted_at 
            FROM votes 
            WHERE category_id = ? AND voter_ip = ? AND voter_session = ? 
            ORDER BY voted_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$categoryId, $ip, $sessionId]);
        $result = $stmt->fetch();
        
        if ($result) {
            $lastVoteTime = strtotime($result['voted_at']);
            $currentTime = time();
            $timeDifference = $currentTime - $lastVoteTime;
            $twoHoursInSeconds = 2 * 60 * 60;
            
            if ($timeDifference < $twoHoursInSeconds) {
                return [
                    'has_voted' => true,
                    'can_vote_again_at' => date('Y-m-d H:i:s', $lastVoteTime + $twoHoursInSeconds),
                    'time_remaining' => $twoHoursInSeconds - $timeDifference
                ];
            }
        }
    }
    
    // No vote found or 2+ hours have passed
    return [
        'has_voted' => false,
        'can_vote_again_at' => null,
        'time_remaining' => 0
    ];
}

/**
 * Validate image file
 */
function validateImageFile($file) {
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['valid' => false, 'error' => 'No file uploaded'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['valid' => false, 'error' => 'File size exceeds maximum allowed size'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return ['valid' => false, 'error' => 'Invalid file type. Only JPEG, PNG, and WebP are allowed'];
    }
    
    return ['valid' => true, 'mime' => $mimeType];
}

/**
 * Resize and save image
 */
function saveImage($file, $filename) {
    $validation = validateImageFile($file);
    if (!$validation['valid']) {
        return ['success' => false, 'error' => $validation['error']];
    }
    
    // Create upload directory if it doesn't exist
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    $targetPath = UPLOAD_DIR . $filename;
    $mimeType = $validation['mime'];
    
    // Get image dimensions
    list($width, $height) = getimagesize($file['tmp_name']);
    
    // Create image resource based on type
    switch ($mimeType) {
        case 'image/jpeg':
        case 'image/jpg':
            $source = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $source = imagecreatefrompng($file['tmp_name']);
            break;
        case 'image/webp':
            $source = imagecreatefromwebp($file['tmp_name']);
            break;
        default:
            return ['success' => false, 'error' => 'Unsupported image type'];
    }
    
    // Calculate new dimensions (max 500px width/height, maintain aspect ratio)
    $maxSize = 500;
    if ($width > $height) {
        $newWidth = $maxSize;
        $newHeight = intval($height * ($maxSize / $width));
    } else {
        $newHeight = $maxSize;
        $newWidth = intval($width * ($maxSize / $height));
    }
    
    // Create resized image
    $resized = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency for PNG
    if ($mimeType === 'image/png') {
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
        imagefill($resized, 0, 0, $transparent);
    }
    
    imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Save image
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($resized, $targetPath, 85);
            break;
        case 'png':
            imagepng($resized, $targetPath, 8);
            break;
        case 'webp':
            imagewebp($resized, $targetPath, 85);
            break;
    }
    
    imagedestroy($source);
    imagedestroy($resized);
    
    return ['success' => true, 'path' => 'uploads/logos/' . $filename];
}

