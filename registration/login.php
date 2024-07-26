<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../includes/db_connection.php');
require_once('../includes/encryption_functions.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        error_log("Attempting login for email: " . $email);
        
        $encrypted_email = encrypt($email);
        error_log("Encrypted email: " . $encrypted_email);
        
        $all_users_sql = "SELECT id, email, password FROM users";
        $all_users_result = $conn->query($all_users_sql);
        $user_found = false;
        while ($row = $all_users_result->fetch_assoc()) {
            $db_email = $row['email'];
            error_log("DB email (raw): " . $db_email);
            try {
                $decrypted_email = decrypt($db_email);
                error_log("DB email (decrypted): " . $decrypted_email);
                if ($decrypted_email === $email) {
                    $user_found = true;
                    $user = $row;
                    break;
                }
            } catch (Exception $e) {
                error_log("Failed to decrypt email: " . $e->getMessage());
            }
        }
        
        if ($user_found) {
            error_log("User found in database");
            error_log("Password verification result: " . (password_verify($password, $user['password']) ? 'true' : 'false'));
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $email;
                error_log("Login successful for user: " . $_SESSION['username']);
                header("Location: ../stamp-creation/index.php");
                exit();
            } else {
                error_log("Password verification failed");
                $error = "メールアドレスまたはパスワードが正しくありません。";
            }
        } else {
            error_log("User not found in database for email: " . $email);
            $error = "メールアドレスまたはパスワードが正しくありません。";
        }
    } catch (Exception $e) {
        error_log("Exception occurred: " . $e->getMessage());
        $error = "エラーが発生しました: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #5a6fd6;
        }
        .register-link {
            text-align: center;
            margin-top: 1rem;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .error {
            color: #ff3860;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ログイン</h1>
        <?php if (!empty($error)) echo "<p class='error'>". htmlspecialchars($error) ."</p>"; ?>
        <form method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="メールアドレス" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="パスワード" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
        <p class="register-link">アカウントをお持ちでない方は<a href="index.php">こちら</a>から登録してください。</p>
    </div>
</body>
</html>