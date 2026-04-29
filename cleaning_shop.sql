

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `bonus_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `type` enum('earn','spend') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `bonus_transactions` (`id`, `user_id`, `amount`, `type`, `description`, `created_at`) VALUES
(1, 2, 50, 'earn', 'Начисление бонусов за заказ #1', '2026-04-06 10:34:41');


CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `status` enum('new','processing','completed','cancelled') DEFAULT 'new',
  `total_price` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `orders` (`id`, `user_id`, `service_id`, `status`, `total_price`, `address`, `pickup_date`, `notes`, `created_at`) VALUES
(1, 2, 1, 'new', 500, 'г. Ярославль ул. Пирогова 4', '2026-04-08', 'замша', '2026-04-06 10:34:41');



CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `reviews` (`id`, `user_id`, `rating`, `comment`, `is_approved`, `created_at`) VALUES
(1, 1, 5, 'Отличный сервис! Быстро, качественно и вежливо. Очень довольна результатом!', 1, '2026-04-06 09:56:19'),
(2, 1, 4, 'Хорошая химчистка, вещи как новые. Немного дороговато, но качество отличное.', 1, '2026-04-06 09:56:19'),
(3, NULL, 5, 'Заказывал чистку дивана - супер! Пятна вывели даже старые. Рекомендую!', 1, '2026-04-06 09:56:19'),
(4, NULL, 5, 'Очень довольна чисткой пуховика. Вернули как новый, спасибо большое!', 1, '2026-04-06 09:56:19'),
(5, 2, 5, 'мне все понравилось', 1, '2026-04-06 10:14:36'),
(6, 2, 5, 'быстро оформили заказ', 1, '2026-04-06 10:35:34');



CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_from` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `services` (`id`, `name`, `description`, `price_from`, `image_url`, `category`, `is_active`, `created_at`) VALUES
(1, 'Химчистка одежды', 'Пальто, платья, костюмы, трикотаж – вернем безупречный вид любой вещи.', 500, '4-Photoroom.png', 'clothing', 1, '2026-04-06 09:56:19'),
(2, 'Чистка мягкой мебели', 'Диваны, кресла, стулья – удалим пятна и нейтрализуем запахи.', 1500, '5.png', 'furniture', 1, '2026-04-06 09:56:19'),
(3, 'Химчистка ковров', 'Ковры и паласы любых размеров – глубокая чистка и освежение.', 800, '5-Photoroom (1).png', 'carpet', 1, '2026-04-06 09:56:19'),
(4, 'Чистка обуви', 'Кожа, замша, нубук – восстановим цвет и форму.', 300, '1-Photoroom.png', 'shoes', 1, '2026-04-06 09:56:19'),
(5, 'Химчистка пуховиков', 'Бережная стирка, сохранение объема и теплосберегающих свойств.', 1200, '9-Photoroom.png', 'clothing', 1, '2026-04-06 09:56:19'),
(6, 'Химчистка штор', 'Деликатная чистка любых видов тканей, включая тюль.', 700, '8-Photoroom.png', 'curtains', 1, '2026-04-06 09:56:19'),
(7, 'Водооталкивающая обработка', 'Создание гидрофобного слоя.', 270, '10.png', 'protection', 1, '2026-04-06 09:56:19'),
(8, 'Чехлы для авто', 'Эффективный способ вернуть салону чистоту.', 1000, '11.png', 'auto', 1, '2026-04-06 09:56:19'),
(9, 'Попона', 'Профессиональная чистка конной амуниции.', 1010, '12.png', 'other', 1, '2026-04-06 09:56:19'),
(10, 'Мягкие игрушки', 'Глубокое очищение материала от пыли, пылевых клещей и бактерий.', 500, '13.png', 'toys', 1, '2026-04-06 09:56:19'),
(11, 'Химчистка сумок', 'Деликатная чистка любых видов тканей, включая кожу.', 1300, '22.png', 'accessories', 1, '2026-04-06 09:56:19'),
(12, 'Озонирование', 'Технология очистки, основанная на использовании газа озона.', 2000, '23.png', 'special', 1, '2026-04-06 09:56:19');



CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `bonus_points` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password_hash`, `bonus_points`, `created_at`, `updated_at`) VALUES
(1, 'Тестовый Пользователь', 'test@test.com', '+7 (999) 123-45-67', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 100, '2026-04-06 09:56:19', '2026-04-06 09:56:19'),
(2, 'Капшукова Варвара Андреевна', 'varvarakapsukova@gmail.com', '89159630027', '$2y$10$185zkoCHMfLjj0nofK1bv.ETU0SAvm452v.WgurTOL53VXnIwvH/O', 50, '2026-04-06 10:12:19', '2026-04-06 10:34:41');


ALTER TABLE `bonus_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);


ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);


ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);


ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `bonus_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


ALTER TABLE `bonus_transactions`
  ADD CONSTRAINT `bonus_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);


ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;
