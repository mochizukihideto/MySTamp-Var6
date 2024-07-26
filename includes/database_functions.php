<?php
// includes/database_functions.php

// データベース接続を確保
require_once 'db_connection.php';

/**
 * ユーザーIDからユーザー情報を取得する関数
 */
function get_user_by_id($conn, $user_id)
{
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * スタンプを削除する関数
 */
function deleteStamp($stamp_id)
{
    global $conn;

    $conn->begin_transaction();

    try {
        $sql = "SELECT image_path FROM stamps WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stamp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stamp = $result->fetch_assoc();

        $sql = "DELETE FROM stamps WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stamp_id);
        $stmt->execute();

        if ($stamp && file_exists($_SERVER['DOCUMENT_ROOT'] . $stamp['image_path'])) {
            if (!unlink($_SERVER['DOCUMENT_ROOT'] . $stamp['image_path'])) {
                throw new Exception("画像ファイルの削除に失敗しました。");
            }
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("スタンプ削除エラー: " . $e->getMessage());
        return false;
    }
}

/**
 * ユーザーのスタンプを取得する関数
 */
function get_user_stamps($conn, $user_id)
{
    $sql = "SELECT * FROM stamps WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * スタンプ使用を更新または挿入する関数
 */
function update_stamp_usage($conn, $user_id, $stamp_id, $date)
{
    $check_sql = "SELECT * FROM stamp_usage WHERE user_id = ? AND stamp_id = ? ORDER BY start_date DESC LIMIT 1";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $stamp_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $existing_usage = $result->fetch_assoc();

    if ($existing_usage) {
        $should_update = check_frequency($existing_usage, $date);

        if ($should_update) {
            $valid_goal_types = ['week', 'month', 'year'];
            $intermediate_goal_type = $existing_usage['intermediate_goal_type'];
            if (!in_array($intermediate_goal_type, $valid_goal_types)) {
                error_log("Invalid intermediate_goal_type: " . $intermediate_goal_type);
                return false;
            }

            $insert_sql = "INSERT INTO stamp_usage (user_id, stamp_id, start_date, frequency_type, frequency_count, duration, intermediate_goal_type, intermediate_goal_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param(
                "iissiisi",
                $user_id,
                $stamp_id,
                $date,
                $existing_usage['frequency_type'],
                $existing_usage['frequency_count'],
                $existing_usage['duration'],
                $intermediate_goal_type,
                $existing_usage['intermediate_goal_count']
            );
            $result = $insert_stmt->execute();
            if (!$result) {
                error_log("Error inserting stamp usage: " . $insert_stmt->error);
            }
            return $result;
        } else {
            return 'no_update';
        }
    } else {
        error_log("No existing usage found for user_id: $user_id, stamp_id: $stamp_id");
        return false;
    }
}

/**
 * 頻度条件をチェックする関数
 */
function check_frequency($usage, $current_date)
{
    $last_use_date = new DateTime($usage['start_date']);
    $current_date = new DateTime($current_date);
    $interval = $last_use_date->diff($current_date);

    switch ($usage['frequency_type']) {
        case 'daily':
            return $interval->days >= $usage['frequency_count'];
        case 'weekly':
            return $interval->days >= ($usage['frequency_count'] * 7);
        case 'monthly':
            return $interval->m >= $usage['frequency_count'] || $interval->y > 0;
        default:
            return false;
    }
}

/**
 * 登録済みのユーザースタンプを取得する関数
 */
function get_registered_user_stamps($conn, $user_id)
{
    $sql = "SELECT * FROM stamps WHERE user_id = ? AND status = 'registered' ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stamps = [];
    while ($row = $result->fetch_assoc()) {
        $stamps[] = $row;
    }

    return $stamps;
}

function update_stamp_continuity($conn, $user_id, $stamp_id, $date)
{
    $start_date_query = "SELECT start_date FROM stamp_usage WHERE user_id = ? AND stamp_id = ?";
    $start_date_stmt = $conn->prepare($start_date_query);
    $start_date_stmt->bind_param("ii", $user_id, $stamp_id);
    $start_date_stmt->execute();
    $start_date_result = $start_date_stmt->get_result();
    $start_date_row = $start_date_result->fetch_assoc();
    $start_date = new DateTime($start_date_row['start_date']);

    $current_date = new DateTime($date);
    $streak_count = $start_date->diff($current_date)->days + 1;

    $sql = "INSERT INTO stamp_continuity (user_id, stamp_id, date, streak_count) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE streak_count = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisii", $user_id, $stamp_id, $date, $streak_count, $streak_count);
    return $stmt->execute();
}

function get_current_streak($conn, $user_id, $stamp_id)
{
    $sql = "SELECT streak_count FROM stamp_continuity 
            WHERE user_id = ? AND stamp_id = ? 
            ORDER BY date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $stamp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    return $data ? $data['streak_count'] : 0;
}


function generate_stamp_data($usage_data, $year, $month)
{
    $calendar_data = [];
    $start_of_month = new DateTime("$year-$month-01");
    $end_of_month = new DateTime("$year-$month-" . $start_of_month->format('t'));
    $today = new DateTime();

    foreach ($usage_data as $stamp) {
        $start_date = new DateTime($stamp['start_date']);
        $current_date = clone $start_of_month;

        while ($current_date <= $end_of_month && $current_date <= $today) {
            $day = $current_date->format('j');
            if (!isset($calendar_data[$day])) {
                $calendar_data[$day] = [];
            }

            $stamp_info = calculate_stamp_info($stamp, $current_date);

            $weekdays = json_decode($stamp['weekdays'], true);
            $current_weekday = strtolower($current_date->format('D'));

            // スタンプ作成から今日までは選択された曜日に基づいて表示
            if ($current_date >= $start_date && $current_date <= $today) {
                if (in_array($current_weekday, $weekdays) && !stampExists($calendar_data[$day], $stamp['stamp_id'])) {
                    $calendar_data[$day][] = $stamp_info;
                }
            }

            // 実際の使用日も表示（今日を含む）
            if (isset($stamp['usage_date']) && $stamp['usage_date'] == $current_date->format('Y-m-d') && !stampExists($calendar_data[$day], $stamp['stamp_id'])) {
                $calendar_data[$day][] = $stamp_info;
            }

            $current_date->modify('+1 day');
        }
    }

    return $calendar_data;
}


function stampExists($dayData, $stampId)
{
    foreach ($dayData as $existingStamp) {
        if ($existingStamp['stamp_id'] == $stampId) {
            return true;
        }
    }
    return false;
}

function calculate_stamp_info($stamp, $current_date)
{
    $start = new DateTime($stamp['start_date']);
    $today = new DateTime();

    $interval = $start->diff($current_date);
    $total_days = $interval->days + 1;

    $stamp_info = $stamp;
    $stamp_info['days_passed'] = $total_days;

    $weekdays = isset($stamp['weekdays']) ? json_decode($stamp['weekdays'], true) : [];
    if (!is_array($weekdays)) {
        $weekdays = [];
    }
    $stamp_info['total_count'] = calculate_actual_usage($stamp['start_date'], $weekdays, $current_date);

    // 開始日の場合も1回としてカウント
    if ($current_date->format('Y-m-d') === $start->format('Y-m-d')) {
        $stamp_info['total_count'] = max(1, $stamp_info['total_count']);
    }

    // streak_count の計算
    $stamp_info['streak_count'] = isset($stamp['streak_count']) ? $stamp['streak_count'] : $total_days;

    $total_minutes = $stamp_info['total_count'] * ($stamp['duration'] ?? 0);
    $stamp_info['total_hours'] = floor($total_minutes / 60);
    $stamp_info['total_minutes'] = $total_minutes % 60;

    $stamp_info['used_today'] = isset($stamp['usage_date']) && $stamp['usage_date'] == $current_date->format('Y-m-d');

    // 目標達成までの日数を計算
    $first_goal_date = new DateTime($stamp['first_goal_date']);
    if ($current_date <= $first_goal_date) {
        $stamp_info['days_until_goal'] = $current_date->diff($first_goal_date)->days;
    } else {
        $next_goal_date = clone $first_goal_date;
        $cycle_count = intval($stamp['next_goal_cycle_count']);
        $cycle_unit = $stamp['next_goal_cycle_unit'];
        if ($cycle_count > 0 && in_array($cycle_unit, ['day', 'week', 'month', 'year'])) {
            while ($next_goal_date <= $current_date) {
                $next_goal_date->modify("+{$cycle_count} {$cycle_unit}");
            }
            $stamp_info['days_until_goal'] = $current_date->diff($next_goal_date)->days;
        } else {
            $stamp_info['days_until_goal'] = null;
        }
    }

    return $stamp_info;
}


function calculate_actual_usage($start_date, $weekdays, $end_date)
{
    $start = new DateTime($start_date);
    $end = new DateTime($end_date->format('Y-m-d'));
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($start, $interval, $end);

    $count = 0;
    foreach ($period as $date) {
        if (!empty($weekdays) && in_array(strtolower($date->format('D')), $weekdays)) {
            $count++;
        }
    }

    // 開始日も含めてカウント
    if (!empty($weekdays) && in_array(strtolower($start->format('D')), $weekdays)) {
        $count++;
    }

    return $count;
}

function calculate_streak($stamp_id, $user_id, $conn)
{
    $sql = "SELECT MAX(date) as last_date FROM stamp_continuity WHERE stamp_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $stamp_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row['last_date']) {
        return 0;
    }

    $last_date = new DateTime($row['last_date']);
    $today = new DateTime();
    $diff = $last_date->diff($today);

    if ($diff->days > 1) {
        return 0;
    }

    $streak = 1;
    $current_date = clone $last_date;

    while (true) {
        $current_date->modify('-1 day');
        $sql = "SELECT COUNT(*) as count FROM stamp_continuity WHERE stamp_id = ? AND user_id = ? AND date = ?";
        $stmt = $conn->prepare($sql);
        $date_str = $current_date->format('Y-m-d');
        $stmt->bind_param("iis", $stamp_id, $user_id, $date_str);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            break;
        }

        $streak++;
    }

    return $streak;
}

function should_add_stamp($stamp, $current_date)
{
    $start = new DateTime($stamp['start_date']);
    $today = new DateTime();
    $diff = $start->diff($current_date);

    if ($current_date > $today) {
        return false;
    }

    if (isset($stamp['usage_date']) && $stamp['usage_date'] == $current_date->format('Y-m-d')) {
        return true;
    }

    switch ($stamp['frequency_type']) {
        case 'daily':
            return true;
        case 'weekly':
            return $diff->days % 7 == 0 || $current_date == $today;
        case 'monthly':
            return $diff->d == 0 || $current_date == $today;
        default:
            return false;
    }
}



function get_stamp_usage_for_month($conn, $user_id, $year, $month)
{
    $start_date = "$year-$month-01";
    $end_date = date('Y-m-t', strtotime($start_date));
    $today = date('Y-m-d');

    $query = "SELECT su.id, su.created_at, s.id as stamp_id, s.image_path, s.hobby,
                 su.start_date, su.frequency_type, su.frequency_count, su.duration,
                 su.first_goal_date, su.next_goal_cycle_count, su.next_goal_cycle_unit,
                 sc.date as usage_date, s.encouragement_message, s.encouragement_image_path,
                 s.goal_message, s.goal_image_path, su.weekdays
          FROM stamp_usage su 
          JOIN stamps s ON su.stamp_id = s.id 
          LEFT JOIN stamp_continuity sc ON su.stamp_id = sc.stamp_id AND sc.user_id = su.user_id 
                 AND sc.date BETWEEN ? AND ?
          WHERE su.user_id = ? AND su.start_date <= ?
          ORDER BY su.start_date, sc.date";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $start_date, $end_date, $user_id, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $usage_data = [];
    while ($row = $result->fetch_assoc()) {
        $row['image_path'] = '/lesson-management-system' . $row['image_path'];
        
        // Calculate next_goal_date and days_until_goal
        $start_date = new DateTime($row['start_date']);
        $first_goal_date = new DateTime($row['first_goal_date']);
        $today = new DateTime();
        
        if ($today <= $first_goal_date) {
            $row['next_goal_date'] = $first_goal_date->format('Y-m-d');
            $row['days_until_goal'] = $today->diff($first_goal_date)->days;
        } else {
            $next_goal_date = clone $first_goal_date;
            $cycle_count = intval($row['next_goal_cycle_count']);
            $cycle_unit = $row['next_goal_cycle_unit'];
            
            while ($next_goal_date <= $today) {
                $next_goal_date->modify("+{$cycle_count} {$cycle_unit}");
            }
            $row['next_goal_date'] = $next_goal_date->format('Y-m-d');
            $row['days_until_goal'] = $today->diff($next_goal_date)->days;
        }
        
        $row['days_passed'] = $today->diff($start_date)->days + 1;
        
        $usage_data[] = $row;
    }

    return generate_stamp_data($usage_data, $year, $month);
}

?>