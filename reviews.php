<?php
require_once 'config.php';
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    
    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, rating, comment, is_approved) VALUES (?, ?, ?, 1)");
        $stmt->execute([$_SESSION['user_id'], $rating, $comment]);
        $success = 'Спасибо за ваш отзыв! Он будет опубликован после проверки.';
    } else {
        $error = 'Пожалуйста, заполните все поля корректно';
    }
}
$stmt = $pdo->query("
    SELECT r.*, u.fullname 
    FROM reviews r 
    LEFT JOIN users u ON r.user_id = u.id 
    WHERE r.is_approved = 1 
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll();

$stmt = $pdo->query("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE is_approved = 1");
$stats = $stmt->fetch();

renderHeader('reviews');
?>

<div class="container py-4">
    <h2 class="text-center mb-4">Отзывы наших клиентов</h2>
    <div class="row mb-5">
        <div class="col-md-6 mx-auto text-center">
            <div class="card bg-light">
                <div class="card-body">
                    <h3 class="display-4 text-warning">
                        <?= number_format($stats['avg_rating'] ?? 0, 1) ?>
                        <i class="bi bi-star-fill"></i>
                    </h3>
                    <p>Средняя оценка на основе <?= $stats['total'] ?? 0 ?> отзывов</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (isLoggedIn()): ?>
        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Оставить отзыв</h5>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Ваша оценка</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">5 - Отлично</option>
                            <option value="4">4 - Хорошо</option>
                            <option value="3">3 - Средне</option>
                            <option value="2">2 - Плохо</option>
                            <option value="1">1 - Ужасно</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ваш комментарий</label>
                        <textarea name="comment" class="form-control" rows="4" required placeholder="Расскажите о вашем опыте..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить отзыв</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center mb-5">
            <i class="bi bi-person-circle"></i> 
            <a href="login.php">Войдите</a> или <a href="register.php">зарегистрируйтесь</a>, чтобы оставить отзыв
        </div>
    <?php endif; ?>
    
    <div class="row">
        <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="text-warning mb-2">
                                <?php for($i = 1; $i <= $review['rating']; $i++): ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php endfor; ?>
                                <?php for($i = $review['rating']; $i < 5; $i++): ?>
                                    <i class="bi bi-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <hr>
                            <small class="text-muted">
                                <i class="bi bi-person-circle"></i> 
                                <strong><?= htmlspecialchars($review['fullname'] ?? 'Анонимный клиент') ?></strong>
                                <br>
                                <i class="bi bi-calendar"></i> <?= date('d.m.Y H:i', strtotime($review['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="h4 text-muted">Пока нет отзывов. Будьте первым!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php renderFooter(); ?>