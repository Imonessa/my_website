<?php
session_start();
require 'config.php';

// Категория из сессии или GET
$category = isset($_SESSION['category']) ? $_SESSION['category'] : (isset($_GET['category']) ? $_GET['category'] : 'none');
function getContent($pdo, $category, $section) {
    $stmt = $pdo->prepare("SELECT title, description FROM content WHERE category = ? AND section = ?");
    $stmt->execute([$category, $section]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$news = $category !== 'none' ? getContent($pdo, $category, 'news') : [];
$career = $category !== 'none' ? getContent($pdo, $category, 'career') : [];
$library = $category !== 'none' ? getContent($pdo, $category, 'library') : [];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cybersecurity Learning Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="<?php echo $category; ?>">
<nav>
    <a href="lessons.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Уроки</a>
    <a href="news.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Новости</a>
    <a href="career.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Карьера</a>
    <a href="library.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Библиотека</a>
    <a href="community.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Сообщество</a>
    <a href="profile.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Профиль</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Выйти</a>
    <?php else: ?>
        <a href="login.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">Войти</a>
    <?php endif; ?>
</nav>

    <header>
        <h1>
            <?php
            if ($category === 'student') echo 'Добро пожаловать в мир кибербезопасности!';
            elseif ($category === 'college' || $category === 'pro') echo 'Cybersecurity Hub';
            else echo 'Cybersecurity Learning Platform';
            ?>
        </h1>
        <p>
            <?php
            if ($category === 'student') echo 'Учись защищать себя и стань кибер-ниндзя!';
            elseif ($category === 'college') echo 'Овладей навыками защиты в цифровом хаосе';
            elseif ($category === 'pro') echo 'Стань мастером в мире киберугроз';
            else echo 'Выбери свой путь в мире кибербезопасности';
            ?>
        </p>
        <div class="category-select">
            <a href="index.php?category=student"><button <?php if ($category === 'student') echo 'class="active"'; ?>>Школьник</button></a>
            <a href="index.php?category=college"><button <?php if ($category === 'college') echo 'class="active"'; ?>>Студент</button></a>
            <a href="index.php?category=pro"><button <?php if ($category === 'pro') echo 'class="active"'; ?>>IT-специалист</button></a>
        </div>
        <a href="lessons.php?category=<?php echo $category; ?>" class="start-btn">Начать учиться</a>
    </header>

    <div class="section" id="news">
        <h2>Новости и угрозы</h2>
        <?php
        if (!empty($news)) {
            foreach ($news as $item) {
                echo "<p><strong>{$item['title']}</strong><br>{$item['description']}</p>";
            }
        } else {
            echo '<p>Выбери категорию, чтобы увидеть новости.</p>';
        }
        ?>
    </div>

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

    <div class="section" id="library">
        <h2>Библиотека</h2>
        <?php
        if (!empty($library)) {
            foreach ($library as $item) {
                echo "<p><strong>{$item['title']}</strong><br>{$item['description']}</p>";
            }
        } else {
            echo '<p>Выбери категорию, чтобы получить материалы.</p>';
        }
        ?>
    </div>
</body>
</html>