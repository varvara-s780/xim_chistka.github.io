<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $stmt = $pdo->prepare("SELECT id, fullname, email, password_hash, bonus_points FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['bonus_points'] = $user['bonus_points'];
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход | Мастерская чистоты</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #a1daf0; padding-top: 100px; }
        .login-card { max-width: 450px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .top-header, .navbar-menu { background-color: #bde4f3; }
        .top-header { position: fixed; top: 0; width: 100%; z-index: 1030; }
        .navbar-menu { position: fixed; top: 100px; width: 100%; z-index: 1020; }
        .logo img { max-height: 100px; }
        .main-content { margin-top: 50px; }
    </style>
</head>
<body>
<div class="top-header">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo"><img src="logo.png" alt="Логотип"></a>
        <div><i class="bi bi-telephone-fill"></i> +7 (4852) 123-456</div>
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
    <div class="container py-5">
        <div class="login-card p-4">
            <h2 class="text-center mb-4">Вход в личный кабинет</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Войти</button>
            </form>
            <div class="text-center mt-3">
                <a href="register.php">Нет аккаунта? Зарегистрироваться</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>