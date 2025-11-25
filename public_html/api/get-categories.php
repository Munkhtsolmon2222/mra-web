<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

try {
    $categories = getCategories();
    
    $formatted = array_map(function($cat) {
        return [
            'id' => (int)$cat['id'],
            'name' => $cat['name'],
            'slug' => $cat['slug']
        ];
    }, $categories);
    
    jsonResponse([
        'success' => true,
        'categories' => $formatted
    ]);
} catch (Exception $e) {
    error_log("Error fetching categories: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to fetch categories'], 500);
}

