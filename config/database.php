<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // غيرها حسب إعداداتك
define('DB_NAME', 'mug_store');

// إنشاء الاتصال
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch(PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// دالة مساعدة للاستعلامات
function query($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// دالة جلب صف واحد
function fetchOne($sql, $params = []) {
    return query($sql, $params)->fetch();
}

// دالة جلب كل الصفوف
function fetchAll($sql, $params = []) {
    return query($sql, $params)->fetchAll();
}
?>