<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/db_connection.php';
require_once '../includes/database_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = get_user_by_id($conn, $user_id);
if (!$user) {
    error_log("User not found for ID: $user_id");
    header("Location: ../error.php");
    exit();
}

// カレンダー用の日付処理
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$firstDay = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDay);
$prevMonth = date('Y-m', strtotime('-1 month', $firstDay));
$nextMonth = date('Y-m', strtotime('+1 month', $firstDay));

// スタンプ使用データの取得
$stamp_usage = get_stamp_usage_for_month($conn, $user_id, $year, $month);

// 日記データの取得
$diary_entries = get_diary_entries_for_month($conn, $user_id, $year, $month);

function get_diary_entries_for_month($conn, $user_id, $year, $month)
{
    $start_date = "$year-$month-01";
    $end_date = date('Y-m-t', strtotime($start_date));

    $query = "SELECT date, content FROM diary WHERE user_id = ? AND date BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $diary_entries = [];
    while ($row = $result->fetch_assoc()) {
        $diary_entries[date('j', strtotime($row['date']))] = $row['content'];
    }

    return $diary_entries;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['nickname'] ?? 'ゲスト'); ?>さんのMyCalendar</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="calendar.js"></script>
</head>

<body>

    <header class="main-header">
        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item"><a href="../stamp-usage/index.php" class="nav-link">今日のスタンプ</a></li>
                <li class="nav-item"><a href="../my-calendar/index.php" class="nav-link">MyCalendar</a></li>
                <li class="nav-item"><a href="../stamp-creation/index.php" class="nav-link">スタンプ作成</a></li>
                <li class="nav-item"><a href="../stamp-creation/stamp_management.php" class="nav-link">スタンプ管理</a></li>
                <li class="nav-item"><a href="../registration/logout.php" class="nav-link">ログアウト</a></li>
            </ul>
        </nav>
    </header>

    <h1><?php echo htmlspecialchars($user['nickname']); ?>さんのMyCalendar</h1>

    <div class="calendar-container">
        <div class="calendar-header">
            <a href="?year=<?php echo date('Y', strtotime($prevMonth)); ?>&month=<?php echo date('n', strtotime($prevMonth)); ?>">&lt;</a>
            <h2><?php echo $year; ?>年 <?php echo $month; ?>月</h2>
            <a href="?year=<?php echo date('Y', strtotime($nextMonth)); ?>&month=<?php echo date('n', strtotime($nextMonth)); ?>">&gt;</a>
        </div>
        <table class="calendar">
            <tr>
                <th>日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
            </tr>
            <?php
            // 変更: $dayOfWeek の初期化を修正
            $firstDayOfWeek = date('w', $firstDay);
            $dayOfWeek = $firstDayOfWeek;
            
            echo "<tr>";
            // 変更: 月の最初の日までの空白セルを追加
            for ($i = 0; $i < $firstDayOfWeek; $i++) {
                echo "<td class='empty-day'></td>";
            }
            
            $day = 1;
            while ($day <= $daysInMonth) {
                if ($dayOfWeek == 7) {
                    echo "</tr><tr>";
                    $dayOfWeek = 0;
                }
                $today_class = ($year == date('Y') && $month == date('n') && $day == date('j')) ? ' today' : '';
                $is_today = ($year == date('Y') && $month == date('n') && $day == date('j'));
                echo "<td class='calendar-day{$today_class}'>";
                echo "<div class='day-number'>" . $day . "</div>";
                if (isset($stamp_usage[$day])) {
                    echo "<div class='stamp-container'>";
                    foreach ($stamp_usage[$day] as $stamp) {
                        $image_path = htmlspecialchars($stamp['image_path']);
                        $stamp_data = htmlspecialchars(json_encode($stamp, JSON_HEX_APOS | JSON_HEX_QUOT));
                        echo "<img src='{$image_path}' alt='Stamp' class='calendar-stamp' data-stamp='{$stamp_data}' onerror=\"this.style.display='none'\">";
                        // echo "<span class='streak-count'>{$stamp['days_passed']}日目</span>";
                        // echo "<span class='days-until-goal'>目標まであと{$stamp['days_until_goal']}日</span>";
                    }
                    echo "</div>";
                }
                // 変更: 日記エントリーの表示部分を復元
                if (isset($diary_entries[$day])) {
                    $diary_content = $diary_entries[$day];
                    $short_content = mb_substr($diary_content, 0, 10) . (mb_strlen($diary_content) > 10 ? '...' : '');
                    echo "<div class='diary-entry' data-full-content='" . htmlspecialchars($diary_content, ENT_QUOTES) . "'>";
                    echo htmlspecialchars($short_content);
                    echo "</div>";
                }
                echo "<button class='add-diary-btn' data-date='{$year}-{$month}-{$day}'>+</button>";
                echo "</td>";
                $day++;
                $dayOfWeek++;
            }
            while ($dayOfWeek < 7) {
                echo "<td class='empty-day'></td>";
                $dayOfWeek++;
            }
            echo "</tr>";
            ?>
        </table>
    </div>

    <div id="stamp-info" style="display:none;position:absolute;background-color:white;border:1px solid #ccc;padding:10px;z-index:1000;">
    </div>

    <div id="diary-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>日記を書く</h2>
            <form id="diary-form">
                <input type="hidden" id="diary-date" name="date">
                <textarea id="diary-content" name="content" rows="4" cols="50"></textarea>
                <button type="submit">保存</button>
            </form>
        </div>
    </div>

</body>

</html>