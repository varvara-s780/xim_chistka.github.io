<?php
require_once 'config.php';

$stmt = $pdo->query("SELECT DISTINCT category FROM services WHERE category IS NOT NULL");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM services WHERE is_active = 1";
$params = [];

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}
if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();
renderHeader('services');
?>
<div class="container py-4">
    <h2 class="text-center mb-4">Наши услуги</h2>
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
           
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="services.php" class="btn <?= empty($category) ? 'btn-primary' : 'btn-outline-primary' ?>">Все</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?= urlencode($cat) ?>" class="btn <?= $category == $cat ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <?= htmlspecialchars($cat) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <?php if (count($services) > 0): ?>
            <?php foreach ($services as $service): ?>
            <div class="col-md-4">
                <div class="card service-card h-350">
                    <img src="<?= htmlspecialchars($service['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($service['name']) ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($service['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($service['description']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary">от <?= number_format($service['price_from'], 0, '', ' ') ?> ₽</span>
                            <a href="order.php?service_id=<?= $service['id'] ?>" class="btn btn-primary">Заказать</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="h4 text-muted">Услуги не найдены</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php renderFooter(); ?>