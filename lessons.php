<?php
session_start();

// Проверка на валидность сессии и регенерация ID для безопасности
if (!isset($_SESSION['user_id'])) {
    // Перенаправляем на страницу входа, если пользователь не авторизован
    header('Location: login.php');
    exit;
}

// Регенерация ID сессии для предотвращения фиксации
if (!isset($_SESSION['created'])) {
    session_regenerate_id(true); // Создаем новый ID и уничтожаем старый
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) { // Обновляем каждые 30 минут
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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Уроки - Cybersecurity Learning Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- Остальной HTML-код остается без изменений -->
</body>
</html>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Уроки - Cybersecurity Learning Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="<?php echo $category; ?>">
    <nav>
        <a href="lessons.php?category=<?php echo $category; ?>">Уроки</a>
        <a href="news.php?category=<?php echo $category; ?>">Новости</a>
        <a href="career.php?category=<?php echo $category; ?>">Карьера</a>
        <a href="library.php?category=<?php echo $category; ?>">Библиотека</a>
        <a href="community.php?category=<?php echo $category; ?>">Сообщество</a>
        <a href="profile.php?category=<?php echo $category; ?>">Профиль</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Выйти</a>
        <?php else: ?>
            <a href="login.php">Войти</a>
        <?php endif; ?>
    </nav>

    <header>
        <h1>Уроки</h1>
        <p>
            <?php
            if ($category === 'student') echo 'Начни своё обучение кибербезопасности!';
            elseif ($category === 'college') echo 'Практические знания для будущих экспертов';
            elseif ($category === 'pro') echo 'Совершенствуй свои навыки';
            else echo 'Выбери категорию, чтобы начать обучение';
            ?>
        </p>
        <div class="category-select">
            <a href="index.php?category=student"><button <?php if ($category === 'student') echo 'class="active"'; ?>>Школьник</button></a>
            <a href="index.php?category=college"><button <?php if ($category === 'college') echo 'class="active"'; ?>>Студент</button></a>
            <a href="index.php?category=pro"><button <?php if ($category === 'pro') echo 'class="active"'; ?>>IT-специалист</button></a>
        </div>
    </header>

    <div class="section" id="lessons">
        <h2>Уроки</h2>
        <p>Раздел в разработке. Скоро здесь появятся уроки по кибербезопасности для твоей категории!</p>
    </div>
</body>
</html>