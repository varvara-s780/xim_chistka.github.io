<?php
require_once 'config.php';

$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 LIMIT 6");
$services = $stmt->fetchAll();

$stmt = $pdo->query("SELECT r.*, u.fullname FROM reviews r LEFT JOIN users u ON r.user_id = u.id WHERE r.is_approved = 1 ORDER BY r.created_at DESC LIMIT 3");
$reviews = $stmt->fetchAll();

renderHeader('index');
?>

<div class="container">
    <div id="heroCarousel" class="carousel slide carousel-fade mx-auto" style="max-width: 1100px;" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="1.jpeg" class="d-block w-100" alt="Чистая одежда" style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                    <h3>Профессиональная химчистка</h3>
                    <p>Деликатный уход за вашими вещами</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="2.jpg" class="d-block w-100" alt="Чистка мебели" style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                    <h3>Химчистка мебели и ковров</h3>
                    <p>Вернем свежесть вашему дому</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="3.jpg" class="d-block w-100" alt="Чистка обуви" style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                    <h3>Чистка обуви и аксессуаров</h3>
                    <p>Ваша обувь как новая</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Наши услуги</h2>
            <div class="row g-4">
                <?php foreach ($services as $service): ?>
                <div class="col-md-4">
                    <div class="card service-card">
                        <img src="<?= htmlspecialchars($service['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($service['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($service['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(mb_substr($service['description'], 0, 80)) ?>...</p>
                            <a href="service-detail.php?id=<?= $service['id'] ?>" class="btn btn-outline-primary">от <?= number_format($service['price_from'], 0, '', ' ') ?> ₽</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="services.php" class="btn btn-primary">Все услуги →</a>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light rounded">
        <div class="container">
            <h2 class="text-center mb-5">Почему выбирают нас</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <i class="bi bi-phone fs-1 text-primary"></i>
                    <h5 class="mt-3">Адаптивный сайт</h5>
                    <p>Удобный заказ с телефона, планшета или компьютера</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-calculator fs-1 text-primary"></i>
                    <h5 class="mt-3">Калькулятор цен</h5>
                    <p>Мгновенный расчёт стоимости любой услуги онлайн</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-truck fs-1 text-primary"></i>
                    <h5 class="mt-3">Вызов курьера</h5>
                    <p>Заберём вещи и привезём обратно в удобное время</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Отзывы клиентов</h2>
            <div class="row g-4">
                <?php foreach ($reviews as $review): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="text-warning mb-2">
                                <?php for($i = 1; $i <= $review['rating']; $i++) echo '<i class="bi bi-star-fill"></i>'; ?>
                                <?php for($i = $review['rating']; $i < 5; $i++) echo '<i class="bi bi-star"></i>'; ?>
                            </div>
                            <p class="card-text">"<?= htmlspecialchars(mb_substr($review['comment'], 0, 100)) ?>..."</p>
                            <small class="text-muted">— <?= htmlspecialchars($review['fullname'] ?? 'Клиент') ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="reviews.php" class="btn btn-outline-primary">Все отзывы →</a>
            </div>
        </div>
    </section>
</div>

<?php renderFooter(); ?>