<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db_connection.php';
require_once '../includes/database_functions.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'ユーザーがログインしていません']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['stamp_id'])) {
    echo json_encode(['success' => false, 'message' => 'スタンプIDが指定されていません']);
    exit();
}

$user_id = $_SESSION['user_id'];
$stamp_id = $input['stamp_id'];
$today = new DateTime();

// トランザクション開始
$conn->begin_transaction();

try {
    // stamp_usageの情報を取得
    $usage_sql = "SELECT start_date, frequency_type, frequency_count, duration, weekdays, first_goal_date, next_goal_cycle_count, next_goal_cycle_unit FROM stamp_usage WHERE user_id = ? AND stamp_id = ?";
    $usage_stmt = $conn->prepare($usage_sql);
    $usage_stmt->bind_param("ii", $user_id, $stamp_id);
    $usage_stmt->execute();
    $usage_result = $usage_stmt->get_result();
    $usage_data = $usage_result->fetch_assoc();

    if (!$usage_data) {
        throw new Exception('スタンプの使用情報が見つかりません');
    }

    $start_date = new DateTime($usage_data['start_date']);
    $days_passed = $today->diff($start_date)->days + 1;  // 開始日から今日までの日数

    // 次の目標日を計算
    $first_goal_date = new DateTime($usage_data['first_goal_date']);
    
    if ($today <= $first_goal_date) {
        $days_until_goal = $today->diff($first_goal_date)->days;
    } else {
        $next_goal_date = clone $first_goal_date;
        while ($next_goal_date <= $today) {
            $next_goal_date->modify("+{$usage_data['next_goal_cycle_count']} {$usage_data['next_goal_cycle_unit']}");
        }
        $days_until_goal = $today->diff($next_goal_date)->days;
    }

    // stamp_continuityの更新
    $continuity_sql = "INSERT INTO stamp_continuity (user_id, stamp_id, date, streak_count) VALUES (?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE streak_count = ?";
    $continuity_stmt = $conn->prepare($continuity_sql);
    $today_formatted = $today->format('Y-m-d');
    $continuity_stmt->bind_param("iisii", $user_id, $stamp_id, $today_formatted, $days_passed, $days_passed);
    $continuity_result = $continuity_stmt->execute();

    if ($continuity_result) {
        $conn->commit();

        echo json_encode([
            'success' => true, 
            'message' => 'スタンプの使用が記録されました', 
            'days_passed' => $days_passed,
            'streak_count' => $days_passed,
            'days_until_goal' => $days_until_goal
        ]);
    } else {
        throw new Exception('継続記録の更新に失敗しました');
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>