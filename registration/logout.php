<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h1>ログアウト</h1>
        <div id="logoutConfirm">
            <p>ログアウトしますか？</p>
            <div class="button-group">
                <button onclick="logout()">はい</button>
                <button onclick="goBack()">いいえ</button>
            </div>
        </div>
        <div id="logoutMessage" style="display:none;">
            <p>See you!</p>
            <p><a href="login.php">もう一度ログインする</a></p>
        </div>
    </div>

    <script>
        function logout() {
            fetch('../api/logout.php', {
                method: 'POST',
                credentials: 'same-origin'
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(data => {
                if (data.success) {
                    document.getElementById('logoutConfirm').style.display = 'none';
                    document.getElementById('logoutMessage').style.display = 'block';
                } else {
                    alert('ログアウトに失敗しました。');
                }
            }).catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                alert('ログアウト処理中にエラーが発生しました。');
            });
        }

        function goBack() {
            window.location.href = '../my-calendar/index.php';
        }
    </script>
</body>
</html>