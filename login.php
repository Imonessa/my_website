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
    // Генерация и проверка CSRF-токена
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Неверный запрос. Пожалуйста, попробуйте снова.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Пожалуйста, заполните все поля.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id, email, password, category FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['category'] = $user['category'];
                    header('Location: index.php?category=' . urlencode($user['category']));
                    exit;
                } else {
                    $error = 'Неверный email или пароль.';
                }
            } catch (PDOException $e) {
                error_log("Ошибка входа: " . $e->getMessage());
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
    <title>Вход - Cybersecurity Learning Platform</title>
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
        <h1>Вход в систему</h1>
        <p>
            <?php
            if ($category === 'student') echo 'Войди и начни свой путь кибер-ниндзя!';
            elseif ($category === 'college') echo 'Войди, чтобы освоить кибербезопасность';
            elseif ($category === 'pro') echo 'Войди как эксперт кибербезопасности';
            else echo 'Выбери категорию и войди в платформу';
            ?>
        </p>
        <div class="category-select">
            <a href="?category=student"><button>Школьник</button></a>
            <a href="?category=college"><button>Студент</button></a>
            <a href="?category=pro"><button>IT-специалист</button></a>
        </div>
    </header>

    <div class="section" id="login">
        <h2>Вход</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Войти</button>
        </form>
        <p>Нет аккаунта? <a href="register.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Зарегистрируйтесь</a>.</p>
    </div>
</body>
</html>