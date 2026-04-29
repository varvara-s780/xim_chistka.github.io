<?php
require_once 'config.php';

$total = 0;
$selected_service = null;
$result = '';

$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1");
$services = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = (int)$_POST['service_id'];
    $quantity = (int)$_POST['quantity'];
    $has_delivery = isset($_POST['delivery']);
    
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $selected_service = $stmt->fetch();
    
    if ($selected_service) {
        $total = $selected_service['price_from'] * $quantity;
        
        if ($has_delivery) {
            $total += 500;
        }
        
        if (isLoggedIn()) {
            $discount = $total * 0.05;
            $total = $total - $discount;
            $result = "Скидка 5% для зарегистрированных пользователей: -" . number_format($discount, 0, '', ' ') . " ₽<br>";
        }
        
        $result .= "<strong>Итоговая стоимость: " . number_format($total, 0, '', ' ') . " ₽</strong>";
    }
}

renderHeader('calculator');
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-calculator"></i> Калькулятор стоимости</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Выберите услугу</label>
                            <select name="service_id" class="form-select" required>
                                <option value="">-- Выберите услугу --</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?= $service['id'] ?>" <?= isset($_POST['service_id']) && $_POST['service_id'] == $service['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($service['name']) ?> - от <?= number_format($service['price_from'], 0, '', ' ') ?> ₽
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Количество единиц</label>
                            <input type="number" name="quantity" class="form-control" value="<?= $_POST['quantity'] ?? 1 ?>" min="1" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="delivery" class="form-check-input" id="delivery" <?= isset($_POST['delivery']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="delivery">Курьерская доставка (+500 ₽)</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Рассчитать стоимость</button>
                    </form>
                    
                    <?php if ($result): ?>
                        <div class="alert alert-success mt-4">
                            <h5>Результат расчета:</h5>
                            <?= $result ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (isLoggedIn()): ?>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-gift"></i> Вы авторизованы! Вам доступна скидка 5% на все услуги.
                    У вас <?= $_SESSION['bonus_points'] ?? 0 ?> бонусных баллов.
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-person-plus"></i> 
                    <a href="register.php">Зарегистрируйтесь</a> или <a href="login.php">войдите</a>, чтобы получить скидку 5%!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php renderFooter(); ?>