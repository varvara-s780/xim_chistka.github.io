<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($fullname) || empty($email) || empty($password)) {
        $error = 'Заполните все обязательные поля';
    } elseif ($password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким email уже существует';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (fullname, email, phone, password_hash) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$fullname, $email, $phone, $password_hash])) {
                $success = 'Регистрация успешна! Теперь вы можете войти.';
                $_POST = [];
            } else {
                $error = 'Ошибка при регистрации';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | Мастерская чистоты</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #a1daf0; padding-top: 100px; }
        .register-card { max-width: 500px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .top-header, .navbar-menu { background-color: #bde4f3; }
        .top-header { position: fixed; top: 0; width: 100%; z-index: 1030; padding: 0px 0; border-bottom: 1px solid #a1daf0; }
        .navbar-menu { position: fixed; top: 100px; width: 100%; z-index: 1020; }
        .logo img { max-height: 100px; width: auto; }
        .main-content { margin-top: 50px; }
    </style>
</head>
<body>
<div class="top-header">
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
        <a href="index.php" class="logo"><img src="logo.png" alt="Логотип"></a>
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
    <div class="container py-5">
        <div class="register-card p-4">
            <h2 class="text-center mb-4">Регистрация</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">ФИО *</label>
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Телефон</label>
                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Подтверждение пароля *</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
            </form>
            <div class="text-center mt-3">
                <a href="login.php">Уже есть аккаунт? Войти</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>