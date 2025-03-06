<?php
session_start();

// Регенерация ID сессии для безопасности
if (!isset($_SESSION['created'])) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

require 'config.php';

// Категория из сессии или GET с валидацией
$allowed_categories = ['student', 'college', 'pro', 'none'];
$category = isset($_SESSION['category']) ? $_SESSION['category'] : (isset($_GET['category']) ? $_GET['category'] : 'none');
if (!in_array($category, $allowed_categories)) {
    $category = 'none';
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF-токена
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Неверный запрос. Пожалуйста, попробуйте снова.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
 
        if (empty($name)  || empty($email) || empty($password) || empty($confirm_password)) {
            $error = 'Пожалуйста, заполните все поля.';
        } elseif ($password !== $confirm_password) {
            $error = 'Пароли не совпадают.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Неверный формат email.';
        } else {
            try {
                // Проверка уникальности email
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'Этот email уже зарегистрирован.';
                } else {
                    // Хеширование пароля
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    // Вставка нового пользователя
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, category) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $hashed_password, $category]);

                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['category'] = $category;
                    header('Location: index.php?category=' . urlencode($category));
                    exit;
                }
            } catch (PDOException $e) {
                error_log("Ошибка регистрации: " . $e->getMessage());
                $error = 'Произошла ошибка. Пожалуйста, попробуйте позже.';
            }
        }
    }
}

// Генерация нового CSRF-токена
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Cybersecurity Learning Platform</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="auth-styles.css">
</head>
<body class="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
    <nav>
        <a href="index.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Главная</a>
        <a href="lessons.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Уроки</a>
        <a href="news.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Новости</a>
        <a href="career.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Карьера</a>
        <a href="library.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Библиотека</a>
        <a href="community.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Сообщество</a>
        <a href="profile.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Профиль</a>
    </nav>

    <header>
        <h1>Регистрация</h1>
        <p>
            <?php

if ($category === 'student') echo 'Зарегистрируйся и стань кибер-ниндзя!';
            elseif ($category === 'college') echo 'Зарегистрируйся для обучения кибербезопасности';
            elseif ($category === 'pro') echo 'Зарегистрируйся как эксперт кибербезопасности';
            else echo 'Выбери категорию и зарегистрируйся';
            ?>
        </p>
        <div class="category-select">
            <a href="?category=student"><button>Школьник</button></a>
            <a href="?category=college"><button>Студент</button></a>
            <a href="?category=pro"><button>IT-специалист</button></a>
        </div>
    </header>

    <div class="section" id="register">
        <h2>Регистрация</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm_password">Подтвердите пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p>Уже есть аккаунт? <a href="login.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Войдите</a>.</p>
    </div>
</body>
</html>