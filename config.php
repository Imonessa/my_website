<?php
$host = 'localhost';
$dbname = 'cybersecurity_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Оптимизация по умолчанию
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Отключаем эмуляцию для большей безопасности
} catch (PDOException $e) {
    error_log("Ошибка подключения к базе данных: " . $e->getMessage());
    http_response_code(500);
    echo "Произошла ошибка на сервере. Пожалуйста, попробуйте позже.";
    exit;
}
?>