<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$order_id = (int)($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT o.*, s.name as service_name, s.description as service_description 
    FROM orders o 
    LEFT JOIN services s ON o.service_id = s.id 
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: profile.php');
    exit;
}

renderHeader('profile');
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <h4 class="mb-0">Заказ #<?= $order['id'] ?></h4>
                    <?php
                    $status_text = [
                        'new' => 'Новый',
                        'processing' => 'В обработке',
                        'completed' => 'Выполнен',
                        'cancelled' => 'Отменен'
                    ];
                    ?>
                    <span class="badge bg-light text-dark"><?= $status_text[$order['status']] ?></span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Услуга:</strong><br>
                            <?= htmlspecialchars($order['service_name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Стоимость:</strong><br>
                            <?= number_format($order['total_price'], 0, '', ' ') ?> ₽
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Адрес забора:</strong><br>
                            <?= htmlspecialchars($order['address']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Дата забора:</strong><br>
                            <?= date('d.m.Y', strtotime($order['pickup_date'])) ?>
                        </div>
                    </div>
                    
                    <?php if ($order['notes']): ?>
                        <div class="mb-4">
                            <strong>Комментарий:</strong><br>
                            <?= nl2br(htmlspecialchars($order['notes'])) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <strong>Статус заказа:</strong> 
                        <?php if ($order['status'] == 'new'): ?>
                            Заказ принят, менеджер свяжется с вами в ближайшее время
                        <?php elseif ($order['status'] == 'processing'): ?>
                            Ваш заказ в обработке, мы уже занимаемся им
                        <?php elseif ($order['status'] == 'completed'): ?>
                            Заказ выполнен! Спасибо, что выбрали нас
                        <?php elseif ($order['status'] == 'cancelled'): ?>
                            Заказ отменен
                        <?php endif; ?>
                    </div>
                    
                    <a href="profile.php" class="btn btn-outline-primary">← Вернуться в профиль</a>
                    <?php if ($order['status'] == 'completed' && !$has_review): ?>
                        <a href="reviews.php" class="btn btn-success">Оставить отзыв</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php renderFooter(); ?>