<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = getUserData($pdo, $user_id);

$stmt = $pdo->prepare("
    SELECT o.*, s.name as service_name, s.price_from 
    FROM orders o 
    LEFT JOIN services s ON o.service_id = s.id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT * FROM bonus_transactions 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 20
");
$stmt->execute([$user_id]);
$bonus_transactions = $stmt->fetchAll();

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    
    if (!empty($fullname) && !empty($email)) {
        $stmt = $pdo->prepare("UPDATE users SET fullname = ?, phone = ?, email = ? WHERE id = ?");
        $stmt->execute([$fullname, $phone, $email, $user_id]);
        $_SESSION['user_name'] = $fullname;
        $_SESSION['user_email'] = $email;
        $success = 'Данные успешно обновлены!';
        $user = getUserData($pdo, $user_id);
    } else {
        $error = 'Заполните обязательные поля';
    }
}

renderHeader('profile');
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle fs-1"></i>
                    <h5 class="mt-2"><?= htmlspecialchars($user['fullname']) ?></h5>
                    <p class="text-muted small"><?= htmlspecialchars($user['email']) ?></p>
                    <hr>
                    <div class="alert alert-info">
                        <strong>🎁 Бонусных баллов:</strong><br>
                        <span class="fs-3"><?= number_format($user['bonus_points'], 0, '', ' ') ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <ul class="nav nav-tabs" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#orders" type="button">📦 Мои заказы</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bonus" type="button">🎯 Бонусы</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#settings" type="button">⚙️ Настройки</button>
                </li>
            </ul>
            
            <div class="tab-content p-3 bg-white rounded-bottom shadow-sm">
                <div class="tab-pane fade show active" id="orders">
                    <h5>История заказов</h5>
                    <?php if (count($orders) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Услуга</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Дата</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= htmlspecialchars($order['service_name'] ?? 'Услуга') ?></td>
                                        <td><?= number_format($order['total_price'], 0, '', ' ') ?> ₽</td>
                                        <td>
                                            <?php
                                            $status_class = [
                                                'new' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $status_text = [
                                                'new' => 'Новый',
                                                'processing' => 'В обработке',
                                                'completed' => 'Выполнен',
                                                'cancelled' => 'Отменен'
                                            ];
                                            ?>
                                            <span class="badge bg-<?= $status_class[$order['status']] ?>">
                                                <?= $status_text[$order['status']] ?>
                                            </span>
                                        </td>
                                        <td><?= date('d.m.Y', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <a href="order-detail.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">Детали</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            У вас пока нет заказов. <a href="services.php">Перейти к услугам →</a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="tab-pane fade" id="bonus">
                    <h5>История бонусов</h5>
                    <?php if (count($bonus_transactions) > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Операция</th>
                                        <th>Сумма</th>
                                        <th>Описание</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bonus_transactions as $transaction): ?>
                                    <tr>
                                        <td><?= date('d.m.Y', strtotime($transaction['created_at'])) ?></td>
                                        <td>
                                            <?php if ($transaction['type'] == 'earn'): ?>
                                                <span class="badge bg-success">Начисление</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Списание</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="<?= $transaction['type'] == 'earn' ? 'text-success' : 'text-danger' ?>">
                                            <?= $transaction['type'] == 'earn' ? '+' : '-' ?> <?= $transaction['amount'] ?>
                                        </td>
                                        <td><?= htmlspecialchars($transaction['description']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Пока нет бонусных операций. Совершите первый заказ, чтобы получить бонусы!
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-success mt-3">
                        <strong>Как получить бонусы?</strong><br>
                        - 10% от суммы заказа начисляется на бонусный счет<br>
                        - 50 бонусов за отзыв с фото<br>
                        - 100 бонусов за приглашение друга
                    </div>
                </div>
                
                <div class="tab-pane fade" id="settings">
                    <h5>Редактирование профиля</h5>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">ФИО *</label>
                            <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                        <a href="change-password.php" class="btn btn-outline-secondary">Сменить пароль</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php renderFooter(); ?>