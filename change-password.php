<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!password_verify($old_password, $user['password_hash'])) {
        $error = 'Неверный текущий пароль';
    } elseif (strlen($new_password) < 6) {
        $error = 'Новый пароль должен быть не менее 6 символов';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } else {
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$new_hash, $user_id]);
        $success = 'Пароль успешно изменен!';
    }
}

renderHeader('profile');
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Смена пароля</h4>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                        <a href="profile.php" class="btn btn-primary">Вернуться в профиль</a>
                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Текущий пароль</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Новый пароль</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Подтверждение пароля</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Сменить пароль</button>
                            <a href="profile.php" class="btn btn-secondary">Отмена</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php renderFooter(); ?>