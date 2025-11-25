<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Invalid request method'], 405);
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    jsonResponse(['success' => false, 'error' => 'Invalid CSRF token'], 403);
}

$categoryId = intval($_POST['category_id'] ?? 0);
$name = sanitize($_POST['name'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$logoPath = sanitize($_POST['logo_path'] ?? '');

if (empty($name) || $categoryId <= 0) {
    jsonResponse(['success' => false, 'error' => 'Name and category are required'], 400);
}

// Validate category exists
$category = getCategoryById($categoryId);
if (!$category) {
    jsonResponse(['success' => false, 'error' => 'Invalid category'], 400);
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO participants (category_id, name, logo_path, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$categoryId, $name, $logoPath ?: null, $description ?: null]);
    
    $participantId = $pdo->lastInsertId();
    
    jsonResponse([
        'success' => true,
        'participant_id' => $participantId,
        'message' => 'Participant added successfully'
    ]);
} catch (PDOException $e) {
    error_log("Error adding participant: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to add participant'], 500);
}

