<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/db_connection.php';
require_once '../includes/database_functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'ユーザーがログインしていません。']);
    exit();
}

$user_id = $_SESSION['user_id'];
$date = $_POST['date'] ?? '';
$content = $_POST['content'] ?? '';

// デバッグ情報をログに記録
error_log("Received date: " . $date);
error_log("Received content: " . $content);

if (empty($date) || empty($content)) {
    echo json_encode(['success' => false, 'message' => '日付とコンテンツは必須です。']);
    exit();
}

// 日付の形式を確認し、必要に応じて変換
$date_obj = DateTime::createFromFormat('Y-m-d', $date);
if (!$date_obj) {
    $date_obj = DateTime::createFromFormat('Y-n-j', $date);
}

if (!$date_obj) {
    echo json_encode(['success' => false, 'message' => '無効な日付形式です。']);
    exit();
}

$formatted_date = $date_obj->format('Y-m-d');

// 追加のデバッグ情報をログに記録
error_log("Original date: " . $date);
error_log("Formatted date: " . $formatted_date);

try {
    $stmt = $conn->prepare("INSERT INTO diary (user_id, date, content) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE content = ?");
    $stmt->bind_param("isss", $user_id, $formatted_date, $content, $content);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => '日記が保存されました。']);
    } else {
        echo json_encode(['success' => false, 'message' => 'データベースエラーが発生しました。']);
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '予期せぬエラーが発生しました。']);
}

$conn->close();
?>