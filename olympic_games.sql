-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2023 at 02:24 PM
-- Server version: 8.0.32-0ubuntu0.22.04.2
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `olympic_games`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id` int UNSIGNED NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_slovak_ci NOT NULL,
  `year` smallint NOT NULL,
  `game_order` tinyint UNSIGNED NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_slovak_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `type`, `year`, `game_order`, `city`, `country`) VALUES
(1, 'LOH', 1948, 14, 'Londýn', 'UK'),
(2, 'LOH', 1952, 15, 'Helsinki', 'Fínsko'),
(3, 'LOH', 1956, 16, 'Melbourne/Štokholm', 'Austrália/Švédsko'),
(4, 'LOH', 1960, 17, 'Rím', 'Taliansko'),
(5, 'LOH', 1964, 18, 'Tokio', 'Japonsko'),
(6, 'LOH', 1968, 19, 'Mexiko', 'Mexiko'),
(7, 'LOH', 1972, 20, 'Mníchov', 'Nemecko'),
(8, 'LOH', 1976, 21, 'Montreal', 'Kanada'),
(9, 'LOH', 1980, 22, 'Moskva', 'Sovietsky zväz'),
(10, 'LOH', 1984, 23, 'Los Angeles', 'USA'),
(11, 'LOH', 1988, 24, 'Soul', 'Južná Kórea'),
(12, 'LOH', 1992, 25, 'Barcelona', 'Španielsko'),
(13, 'LOH', 1996, 26, 'Atlanta', 'USA'),
(14, 'LOH', 2000, 27, 'Sydney', 'Austrália'),
(15, 'LOH', 2004, 28, 'Atény', 'Grécko'),
(16, 'LOH', 2008, 29, 'Peking/Hongkong', 'Čína'),
(17, 'LOH', 2012, 30, 'Londýn', 'UK'),
(18, 'LOH', 2016, 31, 'Rio de Janeiro', 'Brazília'),
(19, 'LOH', 2020, 32, 'Tokio', 'Japonsko'),
(20, 'ZOH', 1964, 9, 'Innsbruck', 'Rakúsko'),
(21, 'ZOH', 1968, 10, 'Grenoble', 'Francúzsko'),
(22, 'ZOH', 1972, 11, 'Sapporo', 'Japonsko'),
(23, 'ZOH', 1976, 12, 'Innsbruck', 'Rakúsko'),
(24, 'ZOH', 1980, 13, 'Lake Placid', 'USA'),
(25, 'ZOH', 1984, 14, 'Sarajevo', 'Juhoslávia'),
(26, 'ZOH', 1988, 15, 'Calgary', 'Kanada'),
(27, 'ZOH', 1992, 16, 'Albertville', 'Francúzsko'),
(28, 'ZOH', 1994, 17, 'Lillehammer', 'Nórsko'),
(29, 'ZOH', 1998, 18, 'Nagano', 'Japonsko'),
(30, 'ZOH', 2002, 19, 'Salt Lake City', 'USA'),
(31, 'ZOH', 2006, 20, 'Turín', 'Taliansko'),
(32, 'ZOH', 2010, 21, 'Vancouver', 'Kanada'),
(33, 'ZOH', 2014, 22, 'Soči', 'Rusko'),
(34, 'ZOH', 2018, 23, 'Pjongčang', 'Kórea'),
(35, 'ZOH', 2022, 24, 'Peking', 'Čína');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL,
  `login_type` varchar(64) COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `history`
--

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_slovak_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_slovak_ci NOT NULL,
  `birth_day` date NOT NULL,
  `birth_place` varchar(100) COLLATE utf8mb4_slovak_ci NOT NULL,
  `birth_country` varchar(100) COLLATE utf8mb4_slovak_ci NOT NULL,
  `death_day` date DEFAULT NULL,
  `death_place` varchar(100) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `death_country` varchar(100) COLLATE utf8mb4_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `name`, `surname`, `birth_day`, `birth_place`, `birth_country`, `death_day`, `death_place`, `death_country`) VALUES
(1, 'Peter', 'Hochschorner', '1979-09-07', 'Bratislava', 'Slovensko', NULL, NULL, NULL),
(2, 'Pavol', 'Hochschorner', '1979-09-07', 'Bratislava', 'Slovensko', NULL, NULL, NULL),
(3, 'Elena', 'Kaliská', '1972-01-19', 'Zvolen', 'Slovensko', NULL, NULL, NULL),
(4, 'Anastasiya', 'Kuzmina', '1984-08-28', 'Ťumeň', 'Sovietsky zväz', NULL, NULL, NULL),
(5, 'Michal', 'Martikán', '1979-05-18', 'Liptovský Mikuláš', 'Slovensko', NULL, NULL, NULL),
(6, 'Ondrej', 'Nepela', '1951-01-22', 'Bratislava', 'Slovensko', '1989-02-02', 'Mannheim', 'Nemecko'),
(7, 'Jozef', 'Pribilinec', '1960-07-06', 'Kopernica', 'Slovensko', NULL, NULL, NULL),
(8, 'Anton', 'Tkáč', '1951-03-30', 'Lozorno', 'Slovensko', NULL, NULL, NULL),
(9, 'Ján', 'Zachara', '1928-08-27', 'Kubrá pri Trenčíne', 'Slovensko', NULL, NULL, NULL),
(10, 'Július', 'Torma', '1922-03-07', 'Budapešť', 'Maďarsko', '1991-10-23', 'Praha', 'Česko'),
(11, 'Stanislav', 'Seman', '1952-08-06', 'Košice', 'Slovensko', NULL, NULL, NULL),
(12, 'František', 'Kunzo', '1954-09-17', 'Spišský Hrušov', 'Slovensko', NULL, NULL, NULL),
(13, 'Miloslav', 'Mečíř', '1964-05-19', 'Bojnice', 'Slovensko', NULL, NULL, NULL),
(14, 'Radoslav', 'Židek', '1981-10-15', 'Žilina', 'Slovensko', NULL, NULL, NULL),
(15, 'Pavol', 'Hurajt', '1978-02-04', 'Poprad', 'Slovensko', NULL, NULL, NULL),
(16, 'Matej', 'Tóth', '1983-02-10', 'Nitra', 'Slovensko', NULL, NULL, NULL),
(17, 'Matej', 'Beňuš', '1987-11-02', 'Bratislava', 'Slovensko', NULL, NULL, NULL),
(18, 'Ladislav', 'Škantár', '1983-02-11', 'Kežmarok', 'Slovensko', NULL, NULL, NULL),
(19, 'Peter', 'Škantár', '1982-07-20', 'Kežmarok', 'Slovensko', NULL, NULL, NULL),
(20, 'Erik', 'Vlček', '1981-12-29', 'Komárno', 'Slovensko', NULL, NULL, NULL),
(21, 'Juraj', 'Tarr', '1979-02-18', 'Komárno', 'Slovensko', NULL, NULL, NULL),
(22, 'Denis', 'Myšák', '1995-11-30', 'Bojnice', 'Slovensko', NULL, NULL, NULL),
(23, 'Tibor', 'Linka', '1995-02-13', 'Šamorín', 'Slovensko', NULL, NULL, NULL),
(46, 'Mirko', 'Hlboky', '1998-01-01', 'Puchov', 'Slovensko', NULL, NULL, NULL),
(47, 'Mirko', 'Maly', '1998-01-01', 'Puchov', 'Slovensko', NULL, NULL, NULL),
(51, 'Jozo', 'Jozovy', '1990-01-01', 'Kezmarok', 'Slovensko', NULL, NULL, NULL),
(52, 'Janko', 'Hrach', '1990-12-18', 'Bratislava', 'Slovensko', NULL, NULL, NULL),
(53, 'Zdeno', 'Kocúr', '2023-03-23', 'Fei', 'Stu', NULL, NULL, NULL),
(54, 'Mato', 'Beno', '1111-11-11', 'Blava', 'SLova', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `placement`
--

CREATE TABLE `placement` (
  `id` int UNSIGNED NOT NULL,
  `person_id` int UNSIGNED NOT NULL,
  `game_id` int UNSIGNED NOT NULL,
  `placing` smallint UNSIGNED NOT NULL,
  `discipline` varchar(200) COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `placement`
--

INSERT INTO `placement` (`id`, `person_id`, `game_id`, `placing`, `discipline`) VALUES
(1, 1, 14, 1, 'vodný slalom - C2'),
(2, 1, 15, 1, 'vodný slalom - C2'),
(3, 1, 16, 1, 'vodný slalom - C2'),
(4, 1, 17, 3, 'vodný slalom - C2'),
(5, 2, 14, 1, 'vodný slalom - C2'),
(6, 2, 15, 1, 'vodný slalom - C2'),
(7, 2, 16, 1, 'vodný slalom - C2'),
(8, 2, 17, 3, 'vodný slalom - C2'),
(9, 3, 13, 19, 'vodný slalom - K1'),
(10, 3, 14, 4, 'vodný slalom - K1'),
(11, 3, 15, 1, 'vodný slalom - K1'),
(12, 3, 16, 1, 'vodný slalom - K1'),
(13, 4, 32, 1, 'biatlon - šprint na 7.5 km'),
(14, 5, 13, 1, 'vodný slalom - C1'),
(15, 5, 14, 2, 'vodný slalom - C1'),
(16, 5, 15, 2, 'vodný slalom - C1'),
(17, 5, 16, 1, 'vodný slalom - C1'),
(18, 5, 17, 3, 'vodný slalom - C1'),
(19, 6, 20, 22, 'krasokorčuľovanie'),
(20, 6, 21, 8, 'krasokorčuľovanie'),
(21, 6, 22, 1, 'krasokorčuľovanie'),
(22, 7, 11, 1, 'atletika - chôdza'),
(23, 8, 8, 1, 'dráhová cyklistika - šprint'),
(24, 9, 2, 1, 'box do 57 kg'),
(25, 10, 1, 1, 'box do 67 kg'),
(26, 11, 9, 1, 'futbal'),
(27, 12, 9, 1, 'futbal'),
(28, 13, 11, 1, 'tenis'),
(29, 4, 32, 2, 'biatlon - stíhacie preteky na 10 km'),
(30, 15, 32, 3, 'biatlon - hromadný štart'),
(31, 14, 31, 2, 'snoubordkros'),
(32, 4, 33, 1, 'biatlon - šprint na 7.5 km'),
(33, 4, 34, 1, 'biatlon - hromadný štart'),
(34, 4, 34, 2, 'biatlon - stíhacie preteky na 10 km'),
(35, 4, 34, 2, 'biatlon - vytrvalostné preteky na 15 km'),
(36, 18, 18, 1, 'vodný slalom - C2'),
(37, 19, 18, 1, 'vodný slalom - C2'),
(38, 16, 18, 1, 'atletika - chôdza'),
(39, 17, 18, 2, 'vodný slalom - C1'),
(40, 20, 18, 2, 'kanoistika - K4 na 1000m'),
(41, 21, 18, 2, 'kanoistika - K4 na 1000m'),
(42, 22, 18, 2, 'kanoistika - K4 na 1000m'),
(43, 23, 18, 2, 'kanoistika - K4 na 1000m'),
(46, 47, 1, 3, 'Krasokorculovanie'),
(50, 47, 19, 1, 'Plavanie'),
(51, 51, 26, 4, 'Hod ostepom'),
(53, 52, 1, 2, 'Hod ostepom'),
(54, 53, 18, 1, 'Hod ostepom'),
(55, 54, 20, 5, 'Velos pod peros');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `fullname` varchar(128) COLLATE utf8mb4_slovak_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_slovak_ci NOT NULL,
  `login` varchar(32) COLLATE utf8mb4_slovak_ci NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `2fa_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `users`
--
--
-- Indexes for dumped tables
--

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `placement`
--
ALTER TABLE `placement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `placement`
--
ALTER TABLE `placement`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `placement`
--
ALTER TABLE `placement`
  ADD CONSTRAINT `placement_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `placement_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
