<?php
ob_start();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

function returnError($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

try {
    session_start();
    require_once '../includes/db_connection.php';
    require_once '../includes/database_functions.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        returnError('Invalid request method');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $stamp_id = $input['stamp_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$stamp_id || !$user_id) {
        returnError('Missing stamp_id or user_id');
    }

    $current_date = date('Y-m-d');

    $conn->begin_transaction();

    // Update stamp_usage
    $update_result = update_stamp_usage($conn, $user_id, $stamp_id, $current_date);

    if ($update_result === true) {
        // Update stamp_continuity
        $continuity_result = update_stamp_continuity($conn, $user_id, $stamp_id, $current_date);

        if ($continuity_result) {
            // Get updated stamp information
            $stmt = $conn->prepare("SELECT su.*, sc.streak_count, sc.total_count 
                                    FROM stamp_usage su 
                                    JOIN stamp_continuity sc ON su.stamp_id = sc.stamp_id AND su.user_id = sc.user_id
                                    WHERE su.stamp_id = ? AND su.user_id = ? 
                                    ORDER BY su.start_date DESC LIMIT 1");
            $stmt->bind_param("ii", $stamp_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stamp_info = $result->fetch_assoc();

            $start_date = new DateTime($stamp_info['start_date']);
            $current_date_obj = new DateTime($current_date);
            $days_passed = $current_date_obj->diff($start_date)->days + 1;

            $goal_days = 0;
            switch ($stamp_info['next_goal_cycle_unit']) {
                case 'week':
                    $goal_days = $stamp_info['next_goal_cycle_count'] * 7;
                    break;
                case 'month':
                    $goal_days = $stamp_info['next_goal_cycle_count'] * 30; // 概算
                    break;
                case 'year':
                    $goal_days = $stamp_info['next_goal_cycle_count'] * 365;
                    break;
            }

            $days_left = max(0, $goal_days - $days_passed);

            $conn->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Stamp used successfully',
                'days_left' => $days_left,
                'streak_count' => $stamp_info['streak_count'],
                'total_count' => $stamp_info['total_count']
            ]);
        } else {
            $conn->rollback();
            returnError('Failed to update stamp continuity');
        }
    } elseif ($update_result === 'no_update') {
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'No update needed']);
    } else {
        $conn->rollback();
        returnError('Stamp usage information not found');
    }

    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    returnError($e->getMessage());
}

$output = ob_get_clean();
if (!empty($output)) {
    error_log("PHP Output before JSON: " . $output);
}