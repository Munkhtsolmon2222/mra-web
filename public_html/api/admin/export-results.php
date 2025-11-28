<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

try {
    $pdo = getDB();
    
    // Get all votes with participant and category information
    $stmt = $pdo->query("
        SELECT 
            c.name as category_name,
            p.name as participant_name,
            COUNT(v.id) as vote_count,
            p.id as participant_id,
            c.id as category_id
        FROM participants p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN votes v ON p.id = v.participant_id
        GROUP BY p.id, c.id, c.name, p.name
        ORDER BY c.id ASC, vote_count DESC, p.name ASC
    ");
    $results = $stmt->fetchAll();
    
    // Get total votes per category
    $categoryTotals = [];
    $stmt = $pdo->query("
        SELECT 
            c.id,
            c.name,
            COUNT(v.id) as total_votes
        FROM categories c
        LEFT JOIN votes v ON c.id = v.category_id
        GROUP BY c.id, c.name
        ORDER BY c.id ASC
    ");
    $categoryStats = $stmt->fetchAll();
    foreach ($categoryStats as $stat) {
        $categoryTotals[$stat['id']] = [
            'name' => $stat['name'],
            'total_votes' => $stat['total_votes']
        ];
    }
    
    // Get overall statistics
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM votes");
    $totalVotes = $stmt->fetch()['total'];
    
    // Set headers for CSV download
    $filename = 'MRA_Awards_2025_Vote_Results_' . date('Y-m-d_His') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Add BOM for UTF-8 (helps Excel display Mongolian text correctly)
    echo "\xEF\xBB\xBF";
    
    // Open output stream
    $output = fopen('php://output', 'w');
    
    // Write headers
    fputcsv($output, ['MRA Awards 2025 - Санал асуулгын үр дүн'], ',');
    fputcsv($output, ['Экспорт хийсэн огноо: ' . date('Y-m-d H:i:s')], ',');
    fputcsv($output, ['Нийт санал: ' . $totalVotes], ',');
    fputcsv($output, []); // Empty row
    
    // Write category sections
    $currentCategoryId = null;
    foreach ($results as $row) {
        // If new category, write category header
        if ($currentCategoryId !== $row['category_id']) {
            if ($currentCategoryId !== null) {
                fputcsv($output, []); // Empty row between categories
            }
            $currentCategoryId = $row['category_id'];
            $categoryName = $row['category_name'];
            $categoryTotal = $categoryTotals[$currentCategoryId]['total_votes'] ?? 0;
            fputcsv($output, ['Номинаци: ' . $categoryName], ',');
            fputcsv($output, ['Нийт санал: ' . $categoryTotal], ',');
            fputcsv($output, ['Оролцогч', 'Санал'], ',');
        }
        
        // Write participant data
        fputcsv($output, [
            $row['participant_name'],
            $row['vote_count']
        ], ',');
    }
    
    // Write summary at the end
    fputcsv($output, []); // Empty row
    fputcsv($output, ['Нийт дүн'], ',');
    fputcsv($output, ['Нийт санал: ' . $totalVotes], ',');
    
    fclose($output);
    exit;
    
} catch (PDOException $e) {
    error_log("Error exporting results: " . $e->getMessage());
    die("Алдаа гарлаа. Дахин оролдоно уу.");
}

