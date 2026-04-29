<?php
$host = 'localhost';
$port = '3306';
$dbname = 'cleaning_shop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
} catch(PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserData($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function renderHeader($activePage = '') {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            body { padding-top: 100px; background-color: #a1daf0; }
            .top-header { background-color: #bde4f3; padding: 0px 0; border-bottom: 1px solid #a1daf0; position: fixed; top: 0; width: 100%; z-index: 1030; }
            .logo img { max-height: 100px; width: auto; }
            .navbar-menu { background-color: #a1daf0; border-bottom: 1px solid #88d0ec; position: fixed; top: 100px; width: 100%; z-index: 1020; }
            .main-content { margin-top: 50px;}
            .service-card { background-color: #bde4f3; transition: transform 0.2s; border: none; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
            .service-card:hover { transform: translateY(-5px); }
            .service-card img { height: 350px; object-fit: cover; width: 100%; }
            .footer { background-color: #1a1a2e; color: white; }
            footer a { text-decoration: none; }
            footer a:hover { text-decoration: underline; }
           
    .search-bar { max-width: 400px;
        margin: 0 auto;
    }
    
    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #0d6efd;
    }
    .about-section {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        max-width: 900px;
        margin: 0 auto 50px;
    }
    .about-section h2 {
        margin-bottom: 20px;
        color: #0d6efd;
        text-align: center;
    }
    .about-text {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #333;
    }
   
        </style>
    </head>
    <body>
    <div class="top-header">
        <div class="container d-flex flex-wrap align-items-center justify-content-between">
            <a href="index.php" class="logo"><img src="logo.png" alt="Логотип"></a>
            <div class="search-bar flex-grow-1 mx-4">
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Поиск услуг..." aria-label="Поиск" />
                <button class="btn btn-outline-primary" type="submit">Найти</button>
            </form>
        </div>
            <div class="d-none d-lg-block"><i class="bi bi-telephone-fill"></i> +7 (4852) 123-456</div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-menu py-2">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link <?= $activePage == 'services' ? 'active' : '' ?>" href="services.php">Услуги</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activePage == 'prices' ? 'active' : '' ?>" href="prices.php">Цены</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activePage == 'calculator' ? 'active' : '' ?>" href="calculator.php">Калькулятор</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activePage == 'reviews' ? 'active' : '' ?>" href="reviews.php">Отзывы</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activePage == 'contacts' ? 'active' : '' ?>" href="contacts.php">О нас</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activePage == 'promo' ? 'active' : '' ?>" href="promo.php">Акции</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <?php if (isLoggedIn()): ?>
                        <span class="text-dark me-2">👋 <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        <a href="profile.php" class="btn btn-outline-primary btn-sm">Профиль</a>
                        <a href="logout.php" class="btn btn-danger btn-sm">Выйти</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary btn-sm">Войти</a>
                        <a href="register.php" class="btn btn-primary btn-sm">Регистрация</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="main-content">
    <?php
}

function renderFooter() {
    ?>
    </div>
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-4 mb-4">
                    <h5>Контакты</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt-fill me-2"></i> г. Ярославль, ул. Свободы, 10</li>
                        <li><i class="bi bi-telephone-fill me-2"></i> <a href="tel:+74851234567" class="text-white-50">+7 (4852) 123-456</a></li>
                        <li><i class="bi bi-envelope-fill me-2"></i> <a href="mailto:info@chistoprofi.ru" class="text-white-50">info@chistoprofi.ru</a></li>
                    </ul>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="bi bi-whatsapp fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-telegram fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-vk fs-4"></i></a>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 mb-4">
                    <h5>Навигация</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white-50">Главная</a></li>
                        <li class="mb-2"><a href="services.php" class="text-white-50">Услуги</a></li>
                        <li class="mb-2"><a href="prices.php" class="text-white-50">Цены</a></li>
                        <li class="mb-2"><a href="calculator.php" class="text-white-50">Калькулятор</a></li>
                        <li class="mb-2"><a href="reviews.php" class="text-white-50">Отзывы</a></li>
                        <li class="mb-2"><a href="promo.php" class="text-white-50">Акции</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-lg-4 mb-4">
                    <h5>Личный кабинет</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="login.php" class="text-white-50">Вход</a></li>
                        <li class="mb-2"><a href="register.php" class="text-white-50">Регистрация</a></li>
                        <li class="mb-2"><a href="profile.php" class="text-white-50">Мои заказы</a></li>
                        <li class="mb-2"><a href="bonus.php" class="text-white-50">Бонусная программа</a></li>
                    </ul>
                    <a href="order.php" class="btn btn-outline-light btn-sm mt-2">Вызвать курьера</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center pt-3 border-top border-secondary">
                    <p class="mb-0 text-white-50">© 2026 Мастерская чистоты — химчистка премиум-класса. Все права защищены.</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
?>