<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($categoryId <= 0) {
    jsonResponse(['success' => false, 'error' => 'Invalid category ID'], 400);
}

try {
    $pdo = getDB();
    
    // Get participants with vote counts
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.name,
            p.logo_path,
            p.description,
            COUNT(v.id) as vote_count
        FROM participants p
        LEFT JOIN votes v ON p.id = v.participant_id
        WHERE p.category_id = ?
        GROUP BY p.id
        ORDER BY p.name ASC
    ");
    $stmt->execute([$categoryId]);
    $participants = $stmt->fetchAll();
    
    // Format response
    $formatted = array_map(function($p) {
        return [
            'id' => (int)$p['id'],
            'name' => $p['name'],
            'logo_path' => $p['logo_path'],
            'description' => $p['description'],
            'vote_count' => (int)$p['vote_count']
        ];
    }, $participants);
    
    jsonResponse([
        'success' => true,
        'participants' => $formatted
    ]);
} catch (PDOException $e) {
    error_log("Error fetching participants: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to fetch participants'], 500);
}

