<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Буруу хүсэлт'], 405);
}

// Check if file was uploaded
if (!isset($_FILES['logo'])) {
    jsonResponse(['success' => false, 'error' => 'Файл сонгоогүй байна. Зураг сонгоно уу.'], 400);
}

$file = $_FILES['logo'];

// Check for upload errors with specific messages
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'Файлын хэмжээ серверийн хязгаараас хэтэрсэн байна.',
        UPLOAD_ERR_FORM_SIZE => 'Файлын хэмжээ зөвшөөрөгдсөн хязгаараас хэтэрсэн байна.',
        UPLOAD_ERR_PARTIAL => 'Файл бүрэн ачааллаагүй байна. Дахин оролдоно уу.',
        UPLOAD_ERR_NO_FILE => 'Файл илгээгдээгүй байна. Файл сонгоно уу.',
        UPLOAD_ERR_NO_TMP_DIR => 'Серверийн алдаа: Түр хадгалах хавтас олдсонгүй.',
        UPLOAD_ERR_CANT_WRITE => 'Серверийн алдаа: Диск рүү бичихэд алдаа гарлаа.',
        UPLOAD_ERR_EXTENSION => 'PHP өргөтгөл файлын ачааллыг зогсоосон байна.',
    ];
    
    $errorMsg = $errorMessages[$file['error']] ?? 'Тодорхойгүй алдаа (Алдааны код: ' . $file['error'] . ')';
    jsonResponse(['success' => false, 'error' => $errorMsg], 400);
}

// Check file size against our limit
if ($file['size'] > MAX_FILE_SIZE) {
    $maxSizeMB = MAX_FILE_SIZE / (1024 * 1024);
    $fileSizeMB = round($file['size'] / (1024 * 1024), 2);
    jsonResponse([
        'success' => false, 
        'error' => "Файлын хэмжээ ({$fileSizeMB}MB) зөвшөөрөгдсөн хязгаараас ({$maxSizeMB}MB) хэтэрсэн байна."
    ], 400);
}

// Validate file
$validation = validateImageFile($file);
if (!$validation['valid']) {
    jsonResponse(['success' => false, 'error' => $validation['error']], 400);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('logo_', true) . '.' . $extension;

// Save image
$result = saveImage($file, $filename);

if ($result['success']) {
    jsonResponse([
        'success' => true,
        'path' => $result['path'],
        'filename' => $filename
    ]);
} else {
    jsonResponse(['success' => false, 'error' => $result['error'] ?? 'Зураг хадгалахад алдаа гарлаа'], 500);
}