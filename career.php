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
    <title>Карьера - Cybersecurity Learning Platform</title>
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
    </nav>

    <header>
        <h1>Карьера</h1>
        <p>
            <?php
            if ($category === 'student') echo 'Узнай, кем ты можешь стать в мире кибербезопасности!';
            elseif ($category === 'college') echo 'Твой путь к профессии начинается здесь';
            elseif ($category === 'pro') echo 'Развивай карьеру эксперта';
            else echo 'Выбери категорию, чтобы узнать о карьерах';
            ?>
        </p>
        <div class="category-select">
            <a href="?category=student"><button>Школьник</button></a>
            <a href="?category=college"><button>Студент</button></a>
            <a href="?category=pro"><button>IT-специалист</button></a>
        </div>
    </header>

    <div class="section" id="career">
        <h2>Карьера</h2>
        <?php
        if (!empty($career)) {
            foreach ($career as $item) {
                echo "<p><strong>{$item['title']}</strong><br>{$item['description']}</p>";
            }
        } else {
            echo '<p>Выбери категорию, чтобы узнать о профессиях.</p>';
        }
        ?>
    </div>
</body>
</html>