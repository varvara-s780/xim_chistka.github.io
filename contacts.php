<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>О нас | Мастерская чистоты</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
<style>
    body {
        padding-top: 100px; 
        background-color: #a1daf0;
    }
    .top-header {
        background-color: #bde4f3;
        padding: 0px 0;
        border-bottom: 1px solid #a1daf0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
    }
    .logo img {
        max-height: 100px;
        width: auto;
    }
    .search-bar {
        max-width: 400px;
        margin: 0 auto;
    }
    .navbar-menu {
        background-color: #a1daf0;
        border-bottom: 1px solid #88d0ec;
        position: fixed;
        top: 100px;
        width: 100%;
        z-index: 1020;
    }
    .main-content {
        margin-top: 50px; 
        padding: 30px 15px;
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
    footer {
        background-color: #222;
        color: #ccc;
    }
    footer a {
        text-decoration: none;
    }
    footer a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="top-header">
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
        <a href="index.html" class="logo">
            <img src="logo.png" alt="Логотип">
        </a>
        <div class="search-bar flex-grow-1 mx-4">
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Поиск услуг..." aria-label="Поиск" />
                <button class="btn btn-outline-primary" type="submit">Найти</button>
            </form>
        </div>
        <div class="d-none d-lg-block">
            <i class="bi bi-telephone-fill"></i> +7 (4852) 123-456
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-menu py-2">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Переключить меню">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="services.php">Услуги</a></li>
                <li class="nav-item"><a class="nav-link" href="prices.php">Цены</a></li>
                <li class="nav-item"><a class="nav-link" href="calculator.php">Калькулятор</a></li>
                <li class="nav-item"><a class="nav-link" href="reviews.php">Отзывы</a></li>
                <li class="nav-item"><a class="nav-link active" href="about.php">О нас</a></li>
                <li class="nav-item"><a class="nav-link" href="promo.php">Акции</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="login.php" class="btn btn-outline-primary btn-sm">Войти</a>
                <a href="register.php" class="btn btn-primary btn-sm">Регистрация</a>
            </div>
        </div>
    </div>
</nav>

<div class="main-content">
    <h1>О нашей компании</h1>
    <section class="about-section">
        <h2>Наша история</h2>
        <div class="about-text">
            <p>Мастерская чистоты — это команда профессионалов, которая уже более 10 лет занимается предоставлением высококачественных услуг по химчистке и уходу за одеждой, мебелью, коврами и обувью. Мы стремимся обеспечить наших клиентов безупречным сервисом, использованием современных технологий и экологически безопасных средств.</p>
        </div>
    </section>
    <section class="about-section">
        <h2>Наши ценности</h2>
        <div class="about-text">
            <ul>
                <li><strong>Качество:</strong> Мы гарантируем высокий стандарт выполнения работ и долговечность результатов.</li>
                <li><strong>Экология:</strong> Используем только безопасные и экологически чистые средства.</li>
                <li><strong>Клиент:</strong> Ваша удовлетворенность — наш главный приоритет.</li>
                <li><strong>Инновации:</strong> Постоянно совершенствуем наши технологии и подходы.</li>
            </ul>
        </div>
    </section>
    <section class="about-section">
        <h2>Наша команда</h2>
        <div class="about-text">
            <p>В нашей команде работают опытные специалисты, которые постоянно повышают свою квалификацию и используют только проверенные методы и оборудование. Мы ценим каждого клиента и делаем все, чтобы ваши вещи всегда выглядели идеально.</p>
        </div>
    </section>
    <section class="about-section">
        <h2>Контакты и местоположение</h2>
        <div class="about-text">
            <p><strong>Адрес:</strong> г. Ярославль, ул. Свободы, 10</p>
            <p><strong>Телефон:</strong> +7 (4852) 123-456</p>
            <p><strong>Email:</strong> info@chistoprofi.ru</p>
            <p>Свяжитесь с нами или посетите наш офис, чтобы узнать больше о наших услугах и условиях сотрудничества.</p>
        </div>
    </section>
</div>

<footer class="bg-dark text-white pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-3">
                <h5>Контакты</h5>
                <ul class="list-unstyled">
                    <li><i class="bi bi-geo-alt-fill me-2"></i> г. Ярославль, ул. Свободы, 10</li>
                    <li><i class="bi bi-telephone-fill me-2"></i> <a href="tel:+74851234567" class="text-white-50">+7 (4852) 123-456</a></li>
                    <li><i class="bi bi-envelope-fill me-2"></i> <a href="mailto:info@chistoprofi.ru" class="text-white-50">info@chistoprofi.ru</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Навигация</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-white-50">Главная</a></li>
                    <li class="mb-2"><a href="services.php" class="text-white-50">Услуги</a></li>
                    <li class="mb-2"><a href="prices.php" class="text-white-50">Цены</a></li>
                    <li class="mb-2"><a href="calculator.php" class="text-white-50">Калькулятор</a></li>
                    <li class="mb-2"><a href="reviews.php" class="text-white-50">Отзывы</a></li>
                    <li class="mb-2"><a href="contacts.php" class="text-white-50">О нас</a></li>
                    <li class="mb-2"><a href="promo.php" class="text-white-50">Акции</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h5>Личный кабинет</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="login.php" class="text-white-50">Вход</a></li>
                    <li class="mb-2"><a href="register.php" class="text-white-50">Регистрация</a></li>
                    <li class="mb-2"><a href="profile.php" class="text-white-50">Мои заказы</a></li>
                    <li class="mb-2"><a href="bonus.php" class="text-white-50">Бонусы</a></li>
                </ul>
                <a href="order.php" class="btn btn-outline-light btn-sm mt-2">Вызвать курьера</a>
            </div>
        </div>
        <div class="row pt-3 border-top border-secondary">
            <div class="col-12 text-center">
                <p class="mb-0 text-white-50">© 2026 Мастерская чистоты — химчистка премиум-класса. Все права защищены.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>