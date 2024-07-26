<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log('/path/to/your/error_log.txt'); // エラーログのパスを適切に設定してください

session_start();
require_once '../includes/db_connection.php';

try {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../registration/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $hobby = filter_input(INPUT_POST, 'hobby', FILTER_SANITIZE_STRING);
        $shape = filter_input(INPUT_POST, 'shape', FILTER_SANITIZE_STRING);
        $font = filter_input(INPUT_POST, 'font', FILTER_SANITIZE_STRING);
        $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);

        if (!$hobby || !$shape || !$font || !$color) {
            header("Location: index.php?error=1");
            exit();
        }

        $filename = 'stamp_' . time() . '_' . uniqid() . '.png';
        $image_path = "/assets/images/generated_stamps/" . $filename;
        $full_path = $_SERVER['DOCUMENT_ROOT'] . "/lesson-management-system" . $image_path;

        $shape_path = $_SERVER['DOCUMENT_ROOT'] . "/lesson-management-system/assets/images/stamp_shapes/{$shape}.png";
        if (!file_exists($shape_path)) {
            header("Location: index.php?error=2");
            exit();
        }
        $image = imagecreatefrompng($shape_path);

        $width = imagesx($image);
        $height = imagesy($image);

        imagealphablending($image, true);
        imagesavealpha($image, true);

        $japanese_fonts = ['NotoSansJP', 'KaisotaiNextUPB', 'TogaliteBlack', 'Pigmo01'];
        $original_font = $font;
        $font_extension = (in_array($font, ['TogaliteBlack', 'Pigmo01'])) ? '.otf' : '.ttf';
        $font_path = dirname(__FILE__) . "/../assets/fonts/{$font}{$font_extension}";

        // 日本語文字が含まれているか、または日本語フォントが選択されている場合
        if (preg_match('/[\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', $hobby) || in_array($font, $japanese_fonts)) {
            // 選択されたフォントが日本語フォントでない場合、NotoSansJPを使用
            if (!in_array($font, $japanese_fonts)) {
                $font = 'NotoSansJP';
                $font_path = dirname(__FILE__) . "/../assets/fonts/NotoSansJP.ttf";
            }
        } else {
            // 英字フォントが見つからない場合、Robotoを使用
            if (!file_exists($font_path)) {
                $font = 'Roboto';
                $font_path = dirname(__FILE__) . "/../assets/fonts/Roboto.ttf";
            }
        }

        $hobby = mb_convert_encoding($hobby, 'UTF-8', 'auto');
        $hobby = preg_replace('/[\x00-\x1F\x7F]/u', '', $hobby);

        $padding = (int) ($width * 0.1);
        $available_width = $width - (2 * $padding);
        $available_height = $height - (2 * $padding);

        $font_size = min($available_width, $available_height) / 5;

        do {
            $bbox = imagettfbbox($font_size, 0, $font_path, $hobby);
            if ($bbox === false) {
                header("Location: index.php?error=3");
                exit();
            }
            $text_width = $bbox[2] - $bbox[0];
            $text_height = $bbox[1] - $bbox[7];
            if ($text_width > $available_width || $text_height > $available_height) {
                $font_size *= 0.9;
            }
        } while (($text_width > $available_width || $text_height > $available_height) && $font_size > 1);

        $x = (int) (($width - $text_width) / 2);
        $y = (int) (($height - $text_height) / 2 + $text_height);

        if ($color === 'rainbow') {
            $colors = [
                imagecolorallocate($image, 255, 0, 0),   // Red
                imagecolorallocate($image, 255, 127, 0), // Orange
                imagecolorallocate($image, 255, 255, 0), // Yellow
                imagecolorallocate($image, 0, 255, 0),   // Green
                imagecolorallocate($image, 0, 0, 255),   // Blue
                imagecolorallocate($image, 75, 0, 130),  // Indigo
                imagecolorallocate($image, 143, 0, 255)  // Violet
            ];

            $char_width = $text_width / mb_strlen($hobby);
            for ($i = 0; $i < mb_strlen($hobby); $i++) {
                $char = mb_substr($hobby, $i, 1);
                $color_index = $i % count($colors);
                imagettftext($image, $font_size, 0, $x + ($i * $char_width), $y, $colors[$color_index], $font_path, $char);
            }
        } else {
            $text_color = imagecolorallocate($image, ...sscanf($color, "#%02x%02x%02x"));
            $result = imagettftext($image, $font_size, 0, $x, $y, $text_color, $font_path, $hobby);
            if ($result === false) {
                header("Location: index.php?error=4");
                exit();
            }
        }

        if (!imagepng($image, $full_path)) {
            header("Location: index.php?error=5");
            exit();
        }
        imagedestroy($image);

        $sql = "INSERT INTO stamps (user_id, hobby, shape, font, color, image_path, status) VALUES (?, ?, ?, ?, ?, ?, 'draft')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $user_id, $hobby, $shape, $font, $color, $image_path);

        if (!$stmt->execute()) {
            header("Location: index.php?error=6");
            exit();
        }

        $new_stamp_id = $stmt->insert_id;

        $_SESSION['new_stamp_created'] = true;
        $_SESSION['new_stamp_id'] = $new_stamp_id;

        header("Location: index.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
} catch (Exception $e) {
    error_log('Error in save_stamp.php: ' . $e->getMessage());
    header("Location: index.php?error=7");
    exit();
}