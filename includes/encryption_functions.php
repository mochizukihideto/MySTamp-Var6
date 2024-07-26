<?php
// エラー報告を有効化（開発時のみ）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 暗号化キーが設定されていない場合のフォールバック（開発環境用）
if (!getenv('ENCRYPTION_KEY')) {
    putenv('ENCRYPTION_KEY=temporary_development_key_change_this_in_production');
    error_log('Warning: Using temporary encryption key. Please set ENCRYPTION_KEY in your environment.');
}

function encrypt($data) {
    $key = getenv('ENCRYPTION_KEY');
    if (!$key) {
        error_log('Encryption key is not set. Please check your server configuration.');
        throw new Exception('Encryption key is not set. Please contact the administrator.');
    }
    
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    
    if ($encrypted === false) {
        throw new Exception('Encryption failed: ' . openssl_error_string());
    }
    
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($data) {
    $key = getenv('ENCRYPTION_KEY');
    if (!$key) {
        error_log('Encryption key is not set. Please check your server configuration.');
        throw new Exception('Encryption key is not set. Please contact the administrator.');
    }
    
    // Base64デコードを試みる
    $decoded = base64_decode($data, true);
    
    // Base64デコードが成功し、'::'を含む場合は新しい形式とみなす
    if ($decoded !== false && strpos($decoded, '::') !== false) {
        list($encrypted_data, $iv) = array_pad(explode('::', $decoded, 2), 2, null);
        if ($iv === null) {
            throw new Exception('IV is missing from the encrypted data');
        }
    } else {
        // 古い形式のデータとみなし、IVなしで復号を試みる
        $encrypted_data = $data;
        $iv = str_repeat("\0", openssl_cipher_iv_length('aes-256-cbc'));
    }
    
    $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    
    if ($decrypted === false) {
        throw new Exception('Decryption failed: ' . openssl_error_string());
    }
    
    return $decrypted;
}

// 暗号化関数のテスト
try {
    $test_data = "Hello, World!";
    $encrypted = encrypt($test_data);
    $decrypted = decrypt($encrypted);
    
    if ($test_data !== $decrypted) {
        error_log("Encryption test failed: original data does not match decrypted data.");
    } else {
        error_log("Encryption test passed successfully.");
    }
} catch (Exception $e) {
    error_log("Encryption test failed with error: " . $e->getMessage());
}
?>