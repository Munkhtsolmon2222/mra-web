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

$participantId = intval($_POST['participant_id'] ?? 0);
$categoryId = intval($_POST['category_id'] ?? 0);
$name = sanitize($_POST['name'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$logoPath = sanitize($_POST['logo_path'] ?? '');

if ($participantId <= 0 || empty($name) || $categoryId <= 0) {
    jsonResponse(['success' => false, 'error' => 'Invalid data provided'], 400);
}

// Validate category exists
$category = getCategoryById($categoryId);
if (!$category) {
    jsonResponse(['success' => false, 'error' => 'Invalid category'], 400);
}

try {
    $pdo = getDB();
    
    // Get old logo path to delete if changed
    $stmt = $pdo->prepare("SELECT logo_path FROM participants WHERE id = ?");
    $stmt->execute([$participantId]);
    $oldParticipant = $stmt->fetch();
    
    if (!$oldParticipant) {
        jsonResponse(['success' => false, 'error' => 'Participant not found'], 404);
    }
    
    // Update participant
    $stmt = $pdo->prepare("UPDATE participants SET category_id = ?, name = ?, logo_path = ?, description = ? WHERE id = ?");
    $stmt->execute([$categoryId, $name, $logoPath ?: null, $description ?: null, $participantId]);
    
    // Delete old logo if it changed and old logo exists
    if ($oldParticipant['logo_path'] && $oldParticipant['logo_path'] !== $logoPath && file_exists(__DIR__ . '/../../' . $oldParticipant['logo_path'])) {
        @unlink(__DIR__ . '/../../' . $oldParticipant['logo_path']);
    }
    
    jsonResponse([
        'success' => true,
        'message' => 'Participant updated successfully'
    ]);
} catch (PDOException $e) {
    error_log("Error updating participant: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to update participant'], 500);
}

