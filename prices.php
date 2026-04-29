<?php
require_once 'config.php';

$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY price_from");
$services = $stmt->fetchAll();

renderHeader('prices');
?>

<div class="container py-4">
    <h2 class="text-center mb-4">Прайс-лист на услуги</h2>
    <p class="text-center text-muted mb-5">Цены указаны ориентировочно. Точную стоимость вы можете рассчитать в калькуляторе</p>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-primary">
                <tr>
                    <th>№</th>
                    <th>Услуга</th>
                    <th>Описание</th>
                    <th>Цена от</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $index => $service): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><strong><?= htmlspecialchars($service['name']) ?></strong></td>
                    <td><?= htmlspecialchars($service['description']) ?></td>
                    <td class="text-primary fw-bold"><?= number_format($service['price_from'], 0, '', ' ') ?> ₽</td>
                    <td>
                        <a href="order.php?service_id=<?= $service['id'] ?>" class="btn btn-sm btn-outline-primary">Заказать</a>
                        <a href="calculator.php?service=<?= $service['id'] ?>" class="btn btn-sm btn-outline-secondary">Рассчитать</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle"></i> 
        * Для постоянных клиентов действует система скидок и бонусная программа. 
        <a href="bonus.php">Подробнее о бонусах →</a>
    </div>
</div>

<?php renderFooter(); ?>