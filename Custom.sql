-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2024 at 06:11 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elite_sample`
--

-- --------------------------------------------------------

--
-- Table structure for table `elite_about`
--

CREATE TABLE `elite_about` (
  `a_id` int(11) NOT NULL,
  `a_title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `upload_option` varchar(20) CHARACTER SET utf8 NOT NULL,
  `a_subtitle` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `a_link` varchar(250) CHARACTER SET utf8 NOT NULL,
  `a_image` blob NOT NULL,
  `a_image1` blob NOT NULL,
  `a_page` varchar(20) CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` varchar(10) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `elite_banner`
--

CREATE TABLE `custom_customer` (
  `custom_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `customer_name` VARCHAR(200) CHARACTER SET utf8 NOT NULL,
  `phone_number` VARCHAR(20) NOT NULL,
  `email` VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  `address` BLOB NOT NULL
); ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE custom_Inventory_Management (
    InventoryID INT NOT NULL AUTO_INCREMENT,
    Total_Stock INT NOT NULL,
    product_id INT,
    PRIMARY KEY (InventoryID),
    FOREIGN KEY (product_id) REFERENCES custom_product_management(product_id)
);


--
-- Dumping data for table `elite_banner`
--

INSERT INTO `custom_customer`(`custom_id`, `customer_name`, `phone_number`, `email`, `address`) VALUES ('1','Sankar','9626585077','uthra.math@example.com','[value-5]')

-- --------------------------------------------------------

--
-- Table structure for table `elite_content`
--

CREATE TABLE `elite_content` (
  `e_id` int(11) NOT NULL,
  `s_title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `s_content` blob NOT NULL,
  `s_subcontent` blob NOT NULL,
  `s_image` blob NOT NULL,
  `s_image1` blob NOT NULL,
  `s_email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `s_phone` varchar(50) CHARACTER SET utf8 NOT NULL,
  `s_fax` varchar(50) CHARACTER SET utf8 NOT NULL,
  `s_address` varchar(500) CHARACTER SET utf8 NOT NULL,
  `s_copyright` varchar(100) CHARACTER SET utf8 NOT NULL,
  `s_link` varchar(200) CHARACTER SET utf8 NOT NULL,
  `menu_id` int(11) NOT NULL,
  `s_page` varchar(20) CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `modified_date` varchar(10) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `elite_content`
--

INSERT INTO `elite_content` (`e_id`, `s_title`, `s_content`, `s_subcontent`, `s_image`, `s_image1`, `s_email`, `s_phone`, `s_fax`, `s_address`, `s_copyright`, `s_link`, `menu_id`, `s_page`, `created_date`, `modified_date`) VALUES
(1, '', 0x456c697465204163636f756e74696e6725323620436f6e73756c74696e672053657276696365732532434c4c432069732064656469636174656420746f2073657276696e6720746865206163636f756e74696e6720616e6420746178206e65656473206f6620736d616c6c20746f206d696473697a656420627573696e65737320616e6420696e646976696475616c732e576520626f61737420612066756c6c79207175616c6966696564207465616d206f662066696e616e6369616c2070726f66657373696f6e616c73, '', '', '', 'keerthiga.zerosoft@gmail.com', '(813) 413%u2212;5014', '(813) 413%u2212;5014', ' 29211 Perilli PlaceWesley Chapel%2C%3Cbr%3EFL 33543', 'Copyright © 2019 Elite%u2212;Consultancy. All rights reserved', '', 0, 'setting', '2022-10-11 11:21:05.436238', '2022-10-11');

-- --------------------------------------------------------

--
-- Table structure for table `elite_login`
--

CREATE TABLE `custom_login` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_password` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `elite_login`
--

INSERT INTO `custom_login` (`user_id`, `user_name`, `user_password`) VALUES
(1, 'custom', 'custom123');

-- --------------------------------------------------------

--
-- Table structure for table `elite_services`
--

CREATE TABLE `elite_services` (
  `e_id` int(11) NOT NULL,
  `e_image` blob NOT NULL,
  `e_image1` blob NOT NULL,
  `e_title` varchar(50) CHARACTER SET utf8 NOT NULL,
  `e_content` varchar(500) CHARACTER SET utf8 NOT NULL,
  `e_link` varchar(250) CHARACTER SET utf8 NOT NULL,
  `e_page` varchar(20) CHARACTER SET utf8 NOT NULL,
  `created time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified time` varchar(10) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `elite_state`
--

CREATE TABLE `elite_state` (
  `r_id` int(11) NOT NULL,
  `r_appr` varchar(2) CHARACTER SET utf8 NOT NULL,
  `r_name` varchar(250) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `elite_topic`
--

CREATE TABLE `elite_topic` (
  `r_id` int(11) NOT NULL,
  `r_topic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `elite_about`
--
ALTER TABLE `elite_about`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `elite_banner`
--
ALTER TABLE `elite_banner`
  ADD PRIMARY KEY (`elite_id`);

--
-- Indexes for table `elite_content`
--
ALTER TABLE `elite_content`
  ADD PRIMARY KEY (`e_id`);

--
-- Indexes for table `elite_login`
--
ALTER TABLE `elite_login`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `elite_services`
--
ALTER TABLE `elite_services`
  ADD PRIMARY KEY (`e_id`);

--
-- Indexes for table `elite_state`
--
ALTER TABLE `elite_state`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `elite_topic`
--
ALTER TABLE `elite_topic`
  ADD PRIMARY KEY (`r_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `elite_about`
--
ALTER TABLE `elite_about`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `elite_banner`
--
ALTER TABLE `elite_banner`
  MODIFY `elite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `elite_content`
--
ALTER TABLE `elite_content`
  MODIFY `e_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `elite_login`
--
ALTER TABLE `elite_login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1236549;

--
-- AUTO_INCREMENT for table `elite_services`
--
ALTER TABLE `elite_services`
  MODIFY `e_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `elite_state`
--
ALTER TABLE `elite_state`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `elite_topic`
--
ALTER TABLE `elite_topic`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
