<?php
session_start();

// Регенерация ID сессии для безопасности перед завершением
if (isset($_SESSION['created'])) {
    session_regenerate_id(true);
}

// Категория из сессии или GET с валидацией (для сохранения дизайна)
$allowed_categories = ['student', 'college', 'pro', 'none'];
$category = isset($_SESSION['category']) ? $_SESSION['category'] : (isset($_GET['category']) ? $_GET['category'] : 'none');
if (!in_array($category, $allowed_categories)) {
    $category = 'none';
}

// Завершение сессии
$_SESSION = array(); // Очищаем все данные сессии
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy(); // Уничтожаем сессию

// Перенаправление на главную страницу или страницу входа
header('Location: login.php?category=' . urlencode($category));
exit;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выход - Cybersecurity Learning Platform</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Если хотите использовать отдельные стили для авторизации -->
    <!-- <link rel="stylesheet" href="auth-styles.css"> -->
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
        <h1>Выход из системы</h1>
        <p>
            <?php
            if ($category === 'student') echo 'До встречи, кибер-ниндзя!';
            elseif ($category === 'college') echo 'До новых встреч в мире кибербезопасности!';
            elseif ($category === 'pro') echo 'До скорого, эксперт!';
            else echo 'Вы вышли из системы.';
            ?>
        </p>
        <div class="category-select">
            <a href="?category=student"><button>Школьник</button></a>
            <a href="?category=college"><button>Студент</button></a>
            <a href="?category=pro"><button>IT-специалист</button></a>
        </div>
    </header>

    <div class="section" id="logout">
        <h2>Выход выполнен</h2>
        <p>Вы успешно вышли из системы. Перенаправляем вас на страницу входа...</p>
        <p><a href="login.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Вернуться к входу</a></p>
    </div>
</body>
</html>