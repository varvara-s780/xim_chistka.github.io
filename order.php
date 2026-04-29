<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=order.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = getUserData($pdo, $user_id);

$service_id = (int)($_GET['service_id'] ?? 0);
$selected_service = null;

if ($service_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND is_active = 1");
    $stmt->execute([$service_id]);
    $selected_service = $stmt->fetch();
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = (int)$_POST['service_id'];
    $address = trim($_POST['address']);
    $pickup_date = $_POST['pickup_date'];
    $notes = trim($_POST['notes']);
    $use_bonus = isset($_POST['use_bonus']);
    
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
    
    if (!$service) {
        $error = 'Услуга не найдена';
    } elseif (empty($address)) {
        $error = 'Укажите адрес';
    } elseif (empty($pickup_date)) {
        $error = 'Укажите дату забора';
    } else {
        $total_price = $service['price_from'];
        
        $bonus_used = 0;
        if ($use_bonus && $user['bonus_points'] > 0) {
            $bonus_used = min($user['bonus_points'], $total_price);
            $total_price -= $bonus_used;
            
            $stmt = $pdo->prepare("INSERT INTO bonus_transactions (user_id, amount, type, description) VALUES (?, ?, 'spend', ?)");
            $stmt->execute([$user_id, $bonus_used, "Списание бонусов за заказ #"]);
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, service_id, total_price, address, pickup_date, notes, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'new')
        ");
        $stmt->execute([$user_id, $service_id, $total_price, $address, $pickup_date, $notes]);
        $order_id = $pdo->lastInsertId();
        
        $new_bonus_points = $user['bonus_points'] - $bonus_used;
        $stmt = $pdo->prepare("UPDATE users SET bonus_points = ? WHERE id = ?");
        $stmt->execute([$new_bonus_points, $user_id]);
        
        $_SESSION['bonus_points'] = $new_bonus_points;
        
        $bonus_earn = floor($total_price * 0.1);
        if ($bonus_earn > 0) {
            $stmt = $pdo->prepare("UPDATE users SET bonus_points = bonus_points + ? WHERE id = ?");
            $stmt->execute([$bonus_earn, $user_id]);
            
            $stmt = $pdo->prepare("INSERT INTO bonus_transactions (user_id, amount, type, description) VALUES (?, ?, 'earn', ?)");
            $stmt->execute([$user_id, $bonus_earn, "Начисление бонусов за заказ #$order_id"]);
            
            $_SESSION['bonus_points'] += $bonus_earn;
        }
        
        $success = "Заказ #$order_id успешно создан! Наш менеджер свяжется с вами в ближайшее время.";
        
        $_POST = [];
    }
}

$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1");
$services = $stmt->fetchAll();

renderHeader('order');
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-cart-check"></i> Оформление заказа</h4>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?= $success ?>
                            <hr>
                            <a href="profile.php" class="btn btn-success">Перейти к заказам</a>
                            <a href="services.php" class="btn btn-outline-primary">Заказать еще</a>
                        </div>
                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Выберите услугу *</label>
                                <select name="service_id" class="form-select" required>
                                    <option value="">-- Выберите услугу --</option>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?= $service['id'] ?>" <?= ($selected_service && $selected_service['id'] == $service['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($service['name']) ?> - <?= number_format($service['price_from'], 0, '', ' ') ?> ₽
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ваши контактные данные</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" disabled>
                                        <small class="text-muted">ФИО</small>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                                        <small class="text-muted">Email</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Адрес забора вещей *</label>
                                <input type="text" name="address" class="form-control" placeholder="г. Ярославль, ул. ..." value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Дата забора *</label>
                                <input type="date" name="pickup_date" class="form-control" value="<?= $_POST['pickup_date'] ?? date('Y-m-d', strtotime('+2 days')) ?>" required>
                                <small class="text-muted">Минимальный срок - через 2 дня</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Комментарий к заказу</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Особые пожелания, тип ткани, особенности..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                            </div>
                            
                            <?php if ($user['bonus_points'] > 0): ?>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="use_bonus" class="form-check-input" id="use_bonus" <?= isset($_POST['use_bonus']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="use_bonus">
                                        Использовать бонусные баллы (до <?= number_format($user['bonus_points'], 0, '', ' ') ?> ₽)
                                    </label>
                                </div>
                            <?php endif; ?>
                            
                            <div class="alert alert-info">
                                <strong>Информация о заказе:</strong><br>
                                - Стоимость услуги будет рассчитана после осмотра вещей<br>
                                - Курьер приедет в удобное для вас время<br>
                                - Оплата наличными или картой при получении
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Оформить заказ</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php renderFooter(); ?>