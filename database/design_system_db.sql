-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 17-09-2024 a las 01:23:01
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `design_system_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `name_user` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname_user` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email_user` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_user` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at_user` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at_user` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `name_user`, `lastname_user`, `email_user`, `password_user`, `created_at_user`, `updated_at_user`) VALUES
(2, 'John', 'Doe', 'john.doe@example.com', '$2y$10$4VAgRwZfN8eXz1Bj5S7Wju58nyNMsX1SOZFE03vsnFiFkcUoKQ4Vq', '2024-09-12 01:40:15', '2024-09-12 01:40:15');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
