<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lesson-management-system/includes/db_connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lesson-management-system/includes/database_functions.php';

// デバッグ情報
error_log("Session data: " . print_r($_SESSION, true));
error_log("User ID: " . ($_SESSION['user_id'] ?? 'Not set'));

if (session_status() !== PHP_SESSION_ACTIVE) {
    error_log("Session not active");
}

// ユーザーがログインしていない場合、ログインページにリダイレクト
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

// 現在の日付を取得
$today = new DateTime();
$year = $today->format('Y');
$month = $today->format('n');

// get_stamp_usage_for_month関数を使用してスタンプデータを取得
$all_stamps = get_stamp_usage_for_month($conn, $user_id, $year, $month);

// 各スタンプの最新の使用日のみを保持する
$latest_stamps = [];
foreach ($all_stamps as $day => $day_stamps) {
    foreach ($day_stamps as $stamp) {
        $stamp_id = $stamp['stamp_id'];
        if (!isset($latest_stamps[$stamp_id]) || $day > $latest_stamps[$stamp_id]['day']) {
            $latest_stamps[$stamp_id] = array_merge($stamp, ['day' => $day]);
        }
    }
}

$new_stamp_created = false;
$new_stamp_id = null;
if (isset($_SESSION['new_stamp_created']) && $_SESSION['new_stamp_created']) {
    $new_stamp_created = true;
    $new_stamp_id = $_SESSION['new_stamp_id'];
    unset($_SESSION['new_stamp_created']);
    unset($_SESSION['new_stamp_id']);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタンプ使用ページ - <?php echo htmlspecialchars($user['nickname'] ?? 'ゲスト'); ?>さん</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="use-stamp.js" defer></script>
</head>

<body>
    <header class="main-header">
        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item"><a href="../my-calendar/index.php" class="nav-link">MyCalendar</a></li>
                <li class="nav-item"><a href="../stamp-creation/index.php" class="nav-link">スタンプ作成</a></li>
                <li class="nav-item"><a href="../stamp-creation/stamp_management.php" class="nav-link">スタンプ管理</a></li>
                <li class="nav-item"><a href="../registration/logout.php" class="nav-link">ログアウト</a></li>
            </ul>
        </nav>
    </header>

    <h1><?php echo htmlspecialchars($user['nickname'] ?? ''); ?>さん、今日はどれを頑張りましたか？</h1>

    <?php if ($new_stamp_created): ?>
        <div class="alert alert-success">新しいスタンプが作成されました！</div>
    <?php endif; ?>

    <div id="stamps-container">
        <?php foreach ($latest_stamps as $stamp): ?>
            <div class="stamp <?php echo ($stamp['stamp_id'] == $new_stamp_id) ? 'new-stamp' : ''; ?>"
                data-stamp-id="<?php echo htmlspecialchars($stamp['stamp_id'] ?? ''); ?>"
                data-encouragement-message="<?php echo htmlspecialchars($stamp['encouragement_message'] ?? ''); ?>"
                data-encouragement-image="<?php echo htmlspecialchars('/lesson-management-system' . ($stamp['encouragement_image_path'] ?? '')); ?>"
                data-days-passed="<?php echo $stamp['days_passed']; ?>"
                data-days-left="<?php echo $stamp['days_until_goal']; ?>"
                data-streak-count="<?php echo $stamp['days_passed']; ?>"
                data-first-goal-date="<?php echo $stamp['first_goal_date']; ?>"
                data-next-goal-cycle-count="<?php echo $stamp['next_goal_cycle_count']; ?>"
                data-next-goal-cycle-unit="<?php echo $stamp['next_goal_cycle_unit']; ?>"
                data-goal-achieved="<?php echo ($stamp['days_until_goal'] == 0) ? 'true' : 'false'; ?>"
                data-goal-message="<?php echo htmlspecialchars($stamp['goal_message'] ?? ''); ?>"
                data-goal-image="<?php echo htmlspecialchars('/lesson-management-system' . ($stamp['goal_image_path'] ?? '')); ?>">
                <?php
                $image_path = htmlspecialchars($stamp['image_path']);
                echo "<img src='{$image_path}' alt='Stamp' class='calendar-stamp' onerror=\"this.style.display='none'\">";
                ?>
                <div class="stamp-info">
                    <p><?php echo htmlspecialchars($stamp['hobby'] ?? ''); ?></p>
                    <p class="streak-count"><?php echo $stamp['days_passed']; ?>日連続</p>
                    <p class="days-left">今度の目標達成まであと<?php echo $stamp['days_until_goal']; ?>日</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 通常の励ましポップアップ -->
    <div id="encouragementPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <img id="encouragementImage" src="" alt="励ましの画像">
            <p id="encouragementMessage"></p>
        </div>
    </div>

    <!-- 目標達成時の特別なポップアップ -->
    <div id="goalAchievedPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <img id="goalAchievedImage" src="" alt="目標達成画像">
            <p id="goalAchievedMessage"></p>
        </div>
    </div>

    <script>
        // AJAXによる定期的なデータ更新
        function updateStamps() {
            $.ajax({
                url: 'get_updated_stamps.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // スタンプ情報を更新
                    data.forEach(function(stamp) {
                        var stampElement = $('.stamp[data-stamp-id="' + stamp.stamp_id + '"]');
                        if (stampElement.length) {
                            stampElement.find('.streak-count').text(stamp.days_passed + '日連続');
                            stampElement.find('.days-left').text('今度の目標達成まであと' + stamp.days_until_goal + '日');
                            stampElement.data('days-passed', stamp.days_passed);
                            stampElement.data('days-left', stamp.days_until_goal);
                            stampElement.data('streak-count', stamp.days_passed);
                        }
                    });
                }
            });
        }

        // 5分ごとにデータを更新
        setInterval(updateStamps, 300000);
    </script>
</body>

</html>