<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lesson-management-system/includes/db_connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lesson-management-system/includes/database_functions.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$today = new DateTime();
$year = $today->format('Y');
$month = $today->format('n');

// get_stamp_usage_for_month関数を使用して最新のスタンプデータを取得
$stamps = get_stamp_usage_for_month($conn, $user_id, $year, $month);

// 必要な情報のみを抽出
$updated_stamps = [];
foreach ($stamps as $day => $day_stamps) {
    foreach ($day_stamps as $stamp) {
        $updated_stamps[] = [
            'stamp_id' => $stamp['stamp_id'],
            'days_passed' => $stamp['days_passed'],
            'days_until_goal' => $stamp['days_until_goal'],
            'hobby' => $stamp['hobby']
        ];
    }
}

// JSONとしてデータを返す
header('Content-Type: application/json');
echo json_encode($updated_stamps);
?>