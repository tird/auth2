-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 28 2015 г., 11:20
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `yii2db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 'demo', 'mPLobHQJkMV7pdw6JM5azks9n-Fkx9EY', '$2y$13$BlX7rTSKcUaluomULVXgRec/1H.y2yJG.K7jSXZArCq4OkzhJ9S/y', NULL, 'demo@mail.net', 1, '0000-00-00', '0000-00-00'),
(3, 'mof', 'pdw6JM5azks9n-Fkx9EYmPLobHQJkMV7', '$2y$13$BlX7rTSKcUaluomULVXgRec/1H.y2yJG.K7jSXZArCq4OkzhJ9S/y', NULL, 'mof@gmail.com', 2, '0000-00-00', '0000-00-00'),
(4, 'tester', 'hgfdgsdf', '44gfsdfdhgxgf', NULL, 'tester@gmail.com', 3, '0000-00-00', '0000-00-00'),
(6, 'mof', '', '', NULL, 'vasd@yasdas.ru', 3, '0000-00-00', '0000-00-00'),
(7, 'mof', '', '$2y$13$geRrx6REi/BacVVJxd5VleAbZNE2K17Kce9ZJLS3VsOL1GkBz7pvO', NULL, 'vasd@yasdas.ru', 3, '0000-00-00', '0000-00-00'),
(8, 'mof2', '', '$2y$13$9itMicTIQ65L9eev.h05Xejrnb.jyWbzTaUH/QWyU.P.QfCz/89xO', NULL, 'vasd@yasdas.ru', 3, '0000-00-00', '0000-00-00'),
(9, 'mof3', '', '$2y$13$SLzklsfIIxUZdC/Svl4TXuszU4vlx21FTJIsScHoyGfZZPnt.4Rxq', NULL, 'vagfdsd@yasdas.ru', 3, '0000-00-00', '0000-00-00'),
(10, 'mof4', 'OOwIJwdd7DsEx8vfAjvg6D4objO6g9Cz', '$2y$13$rGJP2ClfCUkxLe8wxV8JiO6WPo/5hEDPV1As9fVdHY2AlpQUk/Yh2', NULL, 'vgsd@yasdas.ru', 3, '0000-00-00', '0000-00-00'),
(11, 'tird', '4k3sjW6TW8Dbt6vo3rBdihT7eHsb7AVn', '$2y$13$iapGgmibcYU/7e7HYe42A.RPTZQnLIsx9uNh8uuxDm8PkQqJUxuT6', NULL, 'vgsd@yasdas.ru', 3, '0000-00-00', '0000-00-00'),
(12, 'tirdfg', 'UvS_WMJpte_lmiPfqtjyIb6OO-oIRxlz', '$2y$13$HZal6tTd6/ZCPF.1IsnHk.JpaAIZ6inFUtePQ2F793X.kX44pSJt.', NULL, 'vgsd@yasdas.ru', 3, '0000-00-00', '0000-00-00'),
(13, 'tisd', 'J7cQs-LjxzVXNop3I0NKR6XK6oiDzW23', '$2y$13$EhURc.idW7hmlCGoVfHbVutORsfO8igAWdJpdF3zL6UEubI9Oo/um', NULL, 'vgsd@yasdas.ru', 3, '0000-00-00', '0000-00-00');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
