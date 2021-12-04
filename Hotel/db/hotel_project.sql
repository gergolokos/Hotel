-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2021 at 01:18 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `booking_room_id` int(11) DEFAULT NULL,
  `booking_guest_id` int(11) DEFAULT NULL,
  `booking_amount` float DEFAULT NULL,
  `booking_created` datetime NOT NULL DEFAULT current_timestamp(),
  `booking_checkin` datetime NOT NULL,
  `booking_checkout` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4_hungarian_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `booking_room_id`, `booking_guest_id`, `booking_amount`, `booking_created`, `booking_checkin`, `booking_checkout`) VALUES
(8, 1, 7, 120, '2021-11-26 00:13:54', '2021-11-26 00:00:00', '2021-12-08 00:00:00'),
(9, 3, 8, 50, '2021-11-26 00:15:07', '2021-11-24 00:00:00', '2021-11-29 00:00:00'),
(10, 14, 9, 100, '2021-11-26 00:52:03', '2021-11-26 00:00:00', '2021-12-06 00:00:00'),
(11, 14, 10, 100, '2021-11-26 00:54:01', '2021-11-26 00:00:00', '2021-12-06 00:00:00'),
(12, 2, 11, 100, '2021-11-26 00:55:00', '2021-11-01 00:00:00', '2021-11-06 00:00:00'),
(13, 14, 12, 300, '2021-11-26 00:55:46', '2021-11-11 00:00:00', '2021-12-11 00:00:00'),
(14, 4, 13, 100, '2021-11-26 00:56:20', '2021-11-18 00:00:00', '2021-11-23 00:00:00'),
(15, 2, 14, 200, '2021-11-26 01:06:17', '2021-11-26 00:00:00', '2021-12-06 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `guest_picture` varchar(100) DEFAULT NULL,
  `guest_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4_hungarian_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guest_id`, `guest_name`, `guest_picture`, `guest_created`) VALUES
(7, 'John Snow', '', '2021-11-26 04:13:54'),
(8, 'Carl', '1637882107.png', '2021-11-26 04:15:07'),
(9, 'Book', '', '2021-11-26 04:52:03'),
(10, 'Book2', '', '2021-11-26 04:54:01'),
(11, 'Ok', '', '2021-11-26 04:55:00'),
(12, 'Olivia', '', '2021-11-26 04:55:46'),
(13, 'Robin', '', '2021-11-26 04:56:20'),
(14, 'Tom Hom', '1637885177.png', '2021-11-26 05:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_rtype_id` int(11) DEFAULT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_status` enum('Available','Occupied') NOT NULL DEFAULT 'Available',
  `room_booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4_hungarian_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_rtype_id`, `room_name`, `room_status`, `room_booking_id`) VALUES
(1, 9, 'Room-1', 'Occupied', 8),
(2, 10, 'Room-2', 'Occupied', 15),
(3, 9, 'Room-3', 'Occupied', 9),
(4, 10, 'Room-4', 'Occupied', 14),
(14, 9, 'Room-5', 'Occupied', 13),
(16, 11, 'Room-07', 'Available', NULL),
(17, 10, 'Room-08', 'Available', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `rtype_id` int(11) NOT NULL,
  `rtype_name` varchar(100) NOT NULL,
  `rtype_cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4_hungarian_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`rtype_id`, `rtype_name`, `rtype_cost`) VALUES
(9, 'Single', 10),
(10, 'Double', 20),
(11, 'Triple', 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `bookings_FK` (`booking_guest_id`),
  ADD KEY `bookings_FK_1` (`booking_room_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `rooms_FK` (`room_rtype_id`),
  ADD KEY `rooms_FK_1` (`room_booking_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`rtype_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `rtype_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_FK` FOREIGN KEY (`booking_guest_id`) REFERENCES `guests` (`guest_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_FK_1` FOREIGN KEY (`booking_room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_FK` FOREIGN KEY (`room_rtype_id`) REFERENCES `room_types` (`rtype_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rooms_FK_1` FOREIGN KEY (`room_booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
