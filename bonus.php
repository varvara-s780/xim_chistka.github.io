<?php
require_once 'config.php';
renderHeader('bonus');
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-gradient bg-primary text-white text-center">
                    <h2 class="mb-0">🎯 Бонусная программа</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-1 text-warning">⭐</div>
                        <h3>Зарабатывайте баллы и экономьте!</h3>
                    </div>
                    
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h1 class="text-success">10%</h1>
                                <p>бонусами от заказа</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h1 class="text-info">1:1</h1>
                                <p>1 бонус = 1 рубль</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h1 class="text-warning">∞</h1>
                                <p>без срока годности</p>
                            </div>
                        </div>
                    </div>
                    
                    <h5>Как получить бонусы:</h5>
                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Регистрация на сайте
                            <span class="badge bg-primary rounded-pill">+100 бонусов</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Заказ любой услуги
                            <span class="badge bg-success rounded-pill">+10% от суммы</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Отзыв с фотографией
                            <span class="badge bg-info rounded-pill">+50 бонусов</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Приглашение друга
                            <span class="badge bg-warning rounded-pill">+100 бонусов</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            В день рождения
                            <span class="badge bg-danger rounded-pill">+300 бонусов</span>
                        </li>
                    </ul>
                    
                    <div class="alert alert-success">
                        <strong>Ваш текущий баланс:</strong>
                        <?php if (isLoggedIn()): ?>
                            <span class="fs-3"><?= $_SESSION['bonus_points'] ?? 0 ?> бонусов</span>
                            <a href="profile.php" class="btn btn-sm btn-success ms-3">Перейти в профиль</a>
                        <?php else: ?>
                            <a href="register.php">Зарегистрируйтесь</a>, чтобы начать копить бонусы!
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php renderFooter(); ?>