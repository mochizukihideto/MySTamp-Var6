<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db_connection.php';

// ユーザーがログインしていない場合、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ユーザー情報を取得（ニックネームを含む）
$sql = "SELECT nickname FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// 最新の有効なスタンプを取得
$sql_latest = "SELECT * FROM stamps WHERE user_id = ? AND status = 'active' ORDER BY created_at DESC LIMIT 1";
$stmt_latest = $conn->prepare($sql_latest);
$stmt_latest->bind_param("i", $user_id);
$stmt_latest->execute();
$result_latest = $stmt_latest->get_result();
$latest_stamp = $result_latest->fetch_assoc();


// 作成中のスタンプを取得
$sql_draft = "SELECT * FROM stamps WHERE user_id = ? AND status = 'draft' ORDER BY created_at DESC";
$stmt_draft = $conn->prepare($sql_draft);
$stmt_draft->bind_param("i", $user_id);
$stmt_draft->execute();
$result_draft = $stmt_draft->get_result();
$draft_stamps = $result_draft->fetch_all(MYSQLI_ASSOC);

// 登録済みのスタンプを取得
$sql_registered = "SELECT DISTINCT s.*, su.start_date, su.frequency_type, su.frequency_count 
                   FROM stamps s
                   JOIN stamp_usage su ON s.id = su.stamp_id
                   WHERE s.user_id = ? AND s.status = 'registered' 
                   GROUP BY s.id
                   ORDER BY su.start_date DESC";
$stmt_registered = $conn->prepare($sql_registered);
$stmt_registered->bind_param("i", $user_id);
$stmt_registered->execute();
$result_registered = $stmt_registered->get_result();
$registered_stamps = $result_registered->fetch_all(MYSQLI_ASSOC);

// スタンプが存在するかどうかをチェック
$has_stamps = !empty($draft_stamps) || !empty($registered_stamps);

// 新しいスタンプが作成されたかチェック
$new_stamp_created = false;
if (isset($_SESSION['new_stamp_created']) && $_SESSION['new_stamp_created']) {
    $new_stamp_created = true;
    unset($_SESSION['new_stamp_created']);
}

// エラーチェック
$error_message = '';
if (isset($_GET['error'])) {
    $error_message = 'スタンプの作成中にエラーが発生しました。';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>習い事のオリジナルスタンプ作成 - <?php echo htmlspecialchars($user['nickname'] ?? ''); ?>さんのページ</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script&family=Fredoka+One&family=Indie+Flower&family=Pacifico&family=Permanent+Marker&family=Roboto&family=Noto+Sans+JP&display=swap"
        rel="stylesheet">
    <style>
        @font-face {
            font-family: 'BebasNeue';
            src: url('../assets/fonts/BebasNeue.ttf') format('truetype');
        }

        @font-face {
            font-family: 'DancingScript';
            src: url('../assets/fonts/DancingScript.ttf') format('truetype');
        }

        @font-face {
            font-family: 'FredokaOne';
            src: url('../assets/fonts/FredokaOne.ttf') format('truetype');
        }

        @font-face {
            font-family: 'IndieFlower';
            src: url('../assets/fonts/IndieFlower.ttf') format('truetype');
        }

        @font-face {
            font-family: 'Pacifico';
            src: url('../assets/fonts/Pacifico.ttf') format('truetype');
        }

        @font-face {
            font-family: 'PermanentMarker';
            src: url('../assets/fonts/PermanentMarker.ttf') format('truetype');
        }

        @font-face {
            font-family: 'Roboto';
            src: url('../assets/fonts/Roboto.ttf') format('truetype');
        }

        @font-face {
            font-family: 'NotoSansJP';
            src: url('../assets/fonts/NotoSansJP.ttf') format('truetype');
        }

        @font-face {
            font-family: 'KaisotaiNextUPB';
            src: url('../assets/fonts/KaisotaiNextUPB.ttf') format('truetype');
        }

        @font-face {
            font-family: 'TogaliteBlack';
            src: url('../assets/fonts/TogaliteBlack.otf') format('opentype');
        }

        @font-face {
            font-family: 'Pigmo01';
            src: url('../assets/fonts/Pigmo01.otf') format('opentype');
        }

        .font-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .font-option {
            border: 1px solid #ccc;
            padding: 10px;
            cursor: pointer;
        }

        .font-option.selected {
            border-color: #007bff;
            background-color: #e7f1ff;
        }

        #confirmationDialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #finalStamp {
            text-align: center;
        }

        .new-stamp {
            border: 2px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }

        #additionalInfo {
            margin-top: 20px;
        }

        #additionalInfo label {
            display: block;
            margin-top: 10px;
        }

        #colorOptions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .color-option {
            width: 30px;
            height: 30px;
            border: 2px solid transparent;
            border-radius: 50%;
            cursor: pointer;
            padding: 0;
        }

        .color-option.selected {
            border-color: #000;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .color-option.rainbow {
            background: linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet);
        }

        .color-option.color-black {
            background-color: #000000;
        }

        .color-option.color-red {
            background-color: #FF0000;
        }

        .color-option.color-green {
            background-color: #00FF00;
        }

        .color-option.color-blue {
            background-color: #0000FF;
        }

        .color-option.color-yellow {
            background-color: #FFFF00;
        }

        .color-option.color-magenta {
            background-color: #FF00FF;
        }

        .color-option.color-cyan {
            background-color: #00FFFF;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>ようこそ、<?php echo htmlspecialchars($user['nickname']); ?>さん！</h1>
            <nav>
                <ul>
                    <li><a href="../stamp-usage/index.php">今日のスタンプ</a></li>
                    <li><a href="../my-calendar/index.php">Mycalender</a></li>
                    <li><a href="stamp_management.php">スタンプ管理</a></li>
                    <li><a href="../registration/logout.php">ログアウト</a></li>
                </ul>
            </nav>
        </header>

        <div class="container">
            <h2>オリジナルスタンプを作ろう！</h2>
            <?php if ($new_stamp_created): ?>
                <div class="alert alert-success">
                    新しいスタンプが正常に作成されました。
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form id="stampForm" method="post" action="save_stamp.php">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="form-group">
                    <label for="hobby">習い事を入力してください:</label>
                    <input type="text" id="hobby" name="hobby" required>
                </div>
                <div class="form-group">
                    <label>枠を選択してください:</label>
                    <div id="shapeOptions">
                        <label>
                            <input type="radio" name="shape" value="circle" checked>
                            <img src="../assets/images/stamp_shapes/circle.png" alt="丸型">
                        </label>
                        <label>
                            <input type="radio" name="shape" value="cloud">
                            <img src="../assets/images/stamp_shapes/cloud.png" alt="雲型">
                        </label>
                        <label>
                            <input type="radio" name="shape" value="square">
                            <img src="../assets/images/stamp_shapes/square.png" alt="四角型">
                        </label>
                        <label>
                            <input type="radio" name="shape" value="heart">
                            <img src="../assets/images/stamp_shapes/heart.png" alt="ハート型">
                        </label>
                        <label>
                            <input type="radio" name="shape" value="star">
                            <img src="../assets/images/stamp_shapes/star.png" alt="星型">
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>フォントを選んでください:</label>
                    <div id="fontPreview" class="font-preview">
                        <!-- JavaScriptで動的に生成されます -->
                    </div>
                    <input type="hidden" id="font" name="font" value="BebasNeue">
                    <div class="form-group">
                        <label>文字色を選択してください:</label>
                        <div id="colorOptions">
                            <button type="button" class="color-option color-black" data-color="#000000"></button>
                            <button type="button" class="color-option color-red" data-color="#FF0000"></button>
                            <button type="button" class="color-option color-green" data-color="#00FF00"></button>
                            <button type="button" class="color-option color-blue" data-color="#0000FF"></button>
                            <button type="button" class="color-option color-yellow" data-color="#FFFF00"></button>
                            <button type="button" class="color-option color-magenta" data-color="#FF00FF"></button>
                            <button type="button" class="color-option color-cyan" data-color="#00FFFF"></button>
                            <button type="button" class="color-option rainbow" data-color="rainbow"></button>
                        </div>
                        <input type="hidden" id="selectedColor" name="color" value="#000000">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="submit-button">スタンプを作成</button>
                </div>
            </form>

            <div id="stampPreview"></div>

            <div id="stampSelection">
                <h2>スタンプ一覧</h2>
                <div id="savedStamps">
                    <?php if ($has_stamps): ?>
                        <?php if (!empty($draft_stamps)): ?>
                            <div class="stamp-section">
                                <h3>作成中のスタンプ</h3>
                                <div class="stamp-list" id="draftStampsList">
                                    <?php foreach ($draft_stamps as $stamp): ?>
                                        <div class="saved-stamp draft-stamp"
                                            data-stamp-id="<?php echo htmlspecialchars($stamp['id']); ?>">
                                            <img src="/lesson-management-system<?php echo htmlspecialchars($stamp['image_path']); ?>"
                                                alt="Draft Stamp">
                                            <p><?php echo htmlspecialchars($stamp['hobby']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($registered_stamps)): ?>
                            <div class="stamp-section">
                                <h3>登録済みスタンプ</h3>
                                <div class="stamp-list" id="registeredStampsList">
                                    <?php foreach ($registered_stamps as $stamp): ?>
                                        <div class="saved-stamp registered-stamp"
                                            data-stamp-id="<?php echo htmlspecialchars($stamp['id']); ?>">
                                            <img src="/lesson-management-system<?php echo htmlspecialchars($stamp['image_path']); ?>"
                                                alt="Registered Stamp">
                                            <p><?php echo htmlspecialchars($stamp['hobby']); ?></p>
                                            <p>開始日: <?php echo htmlspecialchars($stamp['start_date']); ?></p>
                                            <p>頻度:
                                                <?php echo htmlspecialchars($stamp['frequency_count']) . ' 回/' . htmlspecialchars($stamp['frequency_type']); ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>まだスタンプを作成していません。新しいスタンプを作成してみましょう！</p>
                    <?php endif; ?>
                </div>
            </div>
            <div id="confirmationDialog" style="display: none;">
                <p>このデザインにしますか？</p>
                <button id="confirmYes">はい</button>
                <button id="confirmNo">いいえ</button>
            </div>

            <div id="finalStamp" style="display: none;">
                <h2>スタンプが決まりました！</h2>
                <div id="selectedStamp"></div>
                <div id="additionalInfo">
                    <form id="additionalInfoForm">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <div class="input-group">
                            <label for="startDate">開始日：</label>
                            <input type="date" id="startDate" name="startDate" required>
                        </div>

                        <div class="input-group">
                            <label for="frequencyType">頻度タイプ：</label>
                            <select id="frequencyType" name="frequencyType" required>
                                <option value="daily">日ごと</option>
                                <option value="weekly">週ごと</option>
                                <option value="monthly">月ごと</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="frequencyCount">頻度回数：</label>
                            <input type="number" id="frequencyCount" name="frequencyCount" min="1" required>
                            <span id="frequencyUnit">回</span>
                        </div>

                        <div class="input-group" id="weekdaySelection" style="display: none;">
                            <label>曜日選択：</label>
                            <div>
                                <input type="checkbox" id="mon" name="weekdays[]" value="mon"> 月
                                <input type="checkbox" id="tue" name="weekdays[]" value="tue"> 火
                                <input type="checkbox" id="wed" name="weekdays[]" value="wed"> 水
                                <input type="checkbox" id="thu" name="weekdays[]" value="thu"> 木
                                <input type="checkbox" id="fri" name="weekdays[]" value="fri"> 金
                                <input type="checkbox" id="sat" name="weekdays[]" value="sat"> 土
                                <input type="checkbox" id="sun" name="weekdays[]" value="sun"> 日
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="duration">1回の所要時間（分）：</label>
                            <input type="number" id="duration" name="duration" min="1" required>
                        </div>

                        <div class="input-group">
                            <label for="firstGoalDate">最初の目標達成期間：</label>
                            <input type="date" id="firstGoalDate" name="firstGoalDate" required>
                        </div>

                        <div class="input-group">
                            <label for="nextGoalCycle">次からの目標期間サイクル：</label>
                            <input type="number" id="nextGoalCycleCount" name="nextGoalCycleCount" min="1" required
                                style="width: 80px;">
                            <select id="nextGoalCycleUnit" name="nextGoalCycleUnit" required style="width: 80px;">
                                <option value="day">日毎</option>
                                <option value="month">月毎</option>
                                <option value="year">年毎</option>
                            </select>
                        </div>

                        <button type="submit">保存</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="create-stamp.js"></script>
</body>

</html>