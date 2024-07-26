<?php
session_start();

// セッションを破棄
session_destroy();

// JSONレスポンスを返す
header('Content-Type: application/json');
echo json_encode(['success' => true]);