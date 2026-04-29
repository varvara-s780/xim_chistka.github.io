<?php
require_once 'config.php';
renderHeader('promo');
?>

<div class="container py-4">
    <h2 class="text-center mb-4">🎉 Акции и специальные предложения</h2>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-danger shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">🔥 Скидка 20% на первый заказ</h5>
                </div>
                <div class="card-body">
                    <p>Для новых клиентов действует специальное предложение - скидка 20% на любой вид услуг!</p>
                    <p><strong>Промокод:</strong> <code class="bg-light p-2">CLEAN20</code></p>
                    <p class="text-muted small">* Акция действует до 31.12.2026</p>
                    <a href="services.php" class="btn btn-danger">Заказать со скидкой</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-success shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🎁 Бонусная программа</h5>
                </div>
                <div class="card-body">
                    <p>Каждый заказ приносит вам 10% бонусами на счет!</p>
                    <ul>
                        <li>💰 100 бонусов = 100 рублей</li>
                        <li>⭐ 500 бонусов за приглашение друга</li>
                        <li>🎂 300 бонусов в день рождения</li>
                    </ul>
                    <?php if (isLoggedIn()): ?>
                        <span class="badge bg-success">Ваш баланс: <?= $_SESSION['bonus_points'] ?? 0 ?> бонусов</span>
                    <?php else: ?>
                        <a href="register.php" class="btn btn-success">Зарегистрироваться</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-warning shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">🏠 Комплексная уборка</h5>
                </div>
                <div class="card-body">
                    <p>Закажите 3 услуги одновременно и получите скидку 15% на весь заказ!</p>
                    <p>Комплекс: химчистка мебели + ковров + штор</p>
                    <a href="services.php" class="btn btn-warning">Выбрать услуги</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-info shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📱 Скидка 5% за отзыв</h5>
                </div>
                <div class="card-body">
                    <p>Оставьте отзыв с фотографией после заказа и получите скидку 5% на следующий!</p>
                    <a href="reviews.php" class="btn btn-info text-white">Оставить отзыв</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">🚚 Бесплатная доставка</h5>
                </div>
                <div class="card-body">
                    <p>При заказе от 3000 рублей - доставка курьером бесплатно!</p>
                    <p class="text-muted">* Акция действует для всех клиентов без ограничений</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-warning mt-4 text-center">
        <i class="bi bi-gift"></i> 
        <strong>Акции суммируются?</strong> Максимальная скидка может достигать 30%! Уточняйте у менеджера.
    </div>
</div>

<?php renderFooter(); ?>