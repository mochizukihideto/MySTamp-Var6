<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db_connection.php';
require_once '../includes/database_functions.php';

$base_url = "/lesson-management-system";

// ユーザーがログインしていない場合、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// スタンプ削除のリクエストを処理
if (isset($_POST['delete_stamp'])) {
    $stamp_id = $_POST['stamp_id'];
    if (deleteStamp($stamp_id)) {
        $message = "スタンプが正常に削除されました。";
    } else {
        $error = "スタンプの削除中にエラーが発生しました。";
    }
}

// スタンプ編集のリクエストを処理
if (isset($_POST['edit_stamp'])) {
    $stamp_id = $_POST['stamp_id'];
    $encouragement_message = $_POST['encouragement_message'];
    $goal_message = $_POST['goal_message'];

    // 励ましの画像のアップロード処理
    $encouragement_image_path = handleImageUpload('encouragement_image', 'encouragement');

    // 目標達成時の画像のアップロード処理
    $goal_image_path = handleImageUpload('goal_image', 'goal');

    // データベースを更新
    $sql = "UPDATE stamps SET encouragement_message = ?, goal_message = ?";
    $params = [$encouragement_message, $goal_message];
    $types = "ss";

    if ($encouragement_image_path) {
        $sql .= ", encouragement_image_path = ?";
        $params[] = $encouragement_image_path;
        $types .= "s";
    }

    if ($goal_image_path) {
        $sql .= ", goal_image_path = ?";
        $params[] = $goal_image_path;
        $types .= "s";
    }

    $sql .= " WHERE id = ? AND user_id = ?";
    $params[] = $stamp_id;
    $params[] = $user_id;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $message = "スタンプが正常に更新されました。";
    } else {
        $error = "スタンプの更新中にエラーが発生しました。";
    }
}

// 画像アップロードを処理する関数
function handleImageUpload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
        $full_upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/lesson-management-system/uploads/' . $upload_dir;
        if (!is_dir($full_upload_dir)) {
            if (!@mkdir($full_upload_dir, 0777, true)) {
                error_log("Failed to create directory: " . $full_upload_dir);
                return '';
            }
        }

        $file_extension = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $file_path = $full_upload_dir . '/' . $file_name;

        if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $file_path)) {
            return '/uploads/' . $upload_dir . '/' . $file_name; // ウェブルートからの相対パスを返す
        } else {
            error_log("Failed to move uploaded file: " . $_FILES[$file_key]['tmp_name'] . " to " . $file_path);
        }
    }
    return '';
}

// スタンプ編集のリクエストを処理
if (isset($_POST['edit_stamp'])) {
    $stamp_id = $_POST['stamp_id'];
    $encouragement_message = $_POST['encouragement_message'];
    $goal_message = $_POST['goal_message'];

    // 励ましの画像のアップロード処理
    $encouragement_image_path = handleImageUpload('encouragement_image', 'encouragement');

    // 目標達成時の画像のアップロード処理
    $goal_image_path = handleImageUpload('goal_image', 'goal');

    // データベースを更新
    $sql = "UPDATE stamps SET encouragement_message = ?, goal_message = ?";
    $params = [$encouragement_message, $goal_message];
    $types = "ss";

    if ($encouragement_image_path) {
        $sql .= ", encouragement_image_path = ?";
        $params[] = $encouragement_image_path;
        $types .= "s";
    }

    if ($goal_image_path) {
        $sql .= ", goal_image_path = ?";
        $params[] = $goal_image_path;
        $types .= "s";
    }

    $sql .= " WHERE id = ? AND user_id = ?";
    $params[] = $stamp_id;
    $params[] = $user_id;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $message = "スタンプが正常に更新されました。";
    } else {
        $error = "スタンプの更新中にエラーが発生しました。";
    }
}


// スタンプ一覧の取得
$sql = "SELECT * FROM stamps WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stamps = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタンプ管理</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>スタンプ管理</h1>
        <nav>
            <ul>
                <li><a href="../stamp-usage/index.php">今日のスタンプ</a></li>
                <li><a href="../my-calendar/index.php">Mycalender</a></li>
                <li><a href="index.php">スタンプ作成</a></li>
                <li><a href="../registration/logout.php">ログアウト</a></li>
            </ul>
        </nav>

        <?php
        // メッセージがあれば表示
        if (isset($message)) {
            echo "<p class='success'>" . htmlspecialchars($message) . "</p>";
        }
        if (isset($error)) {
            echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
        }

        // スタンプ一覧の表示
        if (!empty($stamps)) {
            echo "<div class='stamp-list'>";
            foreach ($stamps as $stamp) {
                echo "<div class='stamp-item'>";
                echo "<img src='" . $base_url . htmlspecialchars($stamp['image_path'] ?? '') . "' alt='Stamp' width='100'>";
                echo "<p>" . htmlspecialchars($stamp['hobby'] ?? '') . "</p>";

                // 編集フォーム
                echo "<form method='post' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='stamp_id' value='" . htmlspecialchars($stamp['id'] ?? '') . "'>";
                echo "<textarea name='encouragement_message' placeholder='応援メッセージ'>" . htmlspecialchars($stamp['encouragement_message'] ?? '') . "</textarea>";
                echo "<input type='file' name='encouragement_image'>";
                if (!empty($stamp['encouragement_image_path'])) {
                    $image_path = $base_url . htmlspecialchars($stamp['encouragement_image_path']);
                    echo "<img src='$image_path' alt='Encouragement Image' width='100'>";
                }
                echo "<textarea name='goal_message' placeholder='目標達成時のメッセージ'>" . htmlspecialchars($stamp['goal_message'] ?? '') . "</textarea>";
                echo "<input type='file' name='goal_image'>";
                if (!empty($stamp['goal_image_path'])) {
                    $image_path = $base_url . htmlspecialchars($stamp['goal_image_path']);
                    echo "<img src='$image_path' alt='Goal Achievement Image' width='100'>";
                }
                echo "<input type='submit' name='edit_stamp' value='更新'>";
                echo "</form>";

                // 削除フォーム
                echo "<form method='post'>";
                echo "<input type='hidden' name='stamp_id' value='" . htmlspecialchars($stamp['id'] ?? '') . "'>";
                echo "<input type='submit' name='delete_stamp' value='削除' onclick='return confirm(\"このスタンプを削除しますか？\");'>";
                echo "</form>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>スタンプがありません。</p>";
        }
        ?>
    </div>
</body>

</html>