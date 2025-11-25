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
    
    // Get vote counts for all participants in category
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            COUNT(v.id) as vote_count
        FROM participants p
        LEFT JOIN votes v ON p.id = v.participant_id
        WHERE p.category_id = ?
        GROUP BY p.id
    ");
    $stmt->execute([$categoryId]);
    $results = $stmt->fetchAll();
    
    // Format as associative array
    $formatted = [];
    foreach ($results as $result) {
        $formatted[(int)$result['id']] = (int)$result['vote_count'];
    }
    
    jsonResponse([
        'success' => true,
        'results' => $formatted
    ]);
} catch (PDOException $e) {
    error_log("Error fetching results: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to fetch results'], 500);
}

