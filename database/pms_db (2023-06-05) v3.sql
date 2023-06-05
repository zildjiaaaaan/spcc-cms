-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2023 at 05:24 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowed`
--

CREATE TABLE `borrowed` (
  `id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `equipment_details_id` int(11) NOT NULL,
  `is_returned` int(1) NOT NULL,
  `borrowed_date` text NOT NULL,
  `returned_date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrowed`
--

INSERT INTO `borrowed` (`id`, `borrower_id`, `equipment_details_id`, `is_returned`, `borrowed_date`, `returned_date`) VALUES
(1, 3, 6, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `borrower_id` varchar(50) DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrowers`
--

INSERT INTO `borrowers` (`id`, `fname`, `mname`, `lname`, `position`, `borrower_id`, `contact_no`, `is_del`) VALUES
(1, 'John', 'Andrew', 'Doe', 'Teacher', 'B001', '1234567890', 0),
(2, 'Jane', 'Marie', 'Smith', 'Student', 'S002', '9876543210', 0),
(3, 'Michael', 'Robert', 'Johnson', 'Principal', 'B003', '5555555555', 0);

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `equipment` varchar(50) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `date_acquired` date NOT NULL,
  `total_qty` int(11) DEFAULT NULL,
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `equipment`, `brand`, `date_acquired`, `total_qty`, `is_del`) VALUES
(1, 'Stethoscope', 'Littmann', '2020-03-10', 3, 0),
(2, 'Blood Pressure Monitor', 'Omron', '2019-06-25', 7, 0),
(3, 'Oxygen Concentrator', 'Philips', '2021-01-15', 1, 0),
(4, 'Autoclave', 'Midmark', '2018-09-05', 2, 0),
(5, 'Syringe', 'Miltex', '2022-02-20', 150, 0);

-- --------------------------------------------------------

--
-- Table structure for table `equipment_details`
--

CREATE TABLE `equipment_details` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `state` varchar(50) NOT NULL,
  `unavailable_since` date DEFAULT NULL,
  `unavailable_until` date DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `img_name` varchar(255) NOT NULL,
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipment_details`
--

INSERT INTO `equipment_details` (`id`, `equipment_id`, `status`, `state`, `unavailable_since`, `unavailable_until`, `quantity`, `remarks`, `img_name`, `is_del`) VALUES
(1, 4, 'Available', 'Active', NULL, NULL, 2, 'Located at Room 101', 'none.jpeg', 0),
(2, 2, 'Available', 'Non-Borrowable', NULL, NULL, 5, 'Requires calibration before use.', 'none.jpeg', 0),
(3, 3, 'Unavailable', 'Defective', '2023-06-05', '2023-06-12', 1, 'Currently in repair', 'none.jpeg', 0),
(4, 1, 'Unavailable', 'Missing', '2023-06-05', NULL, 3, 'Last seen at Room 102', 'none.jpeg', 0),
(5, 5, 'Unavailable', 'Used', '2023-06-05', '2023-06-07', 150, 'Used in health program', 'none.jpeg', 0),
(6, 2, 'Unavailable', 'Borrowed', '2023-06-05', '2023-06-06', 2, 'In section 302B', 'none.jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(60) NOT NULL,
  `medicine_brand` varchar(50) NOT NULL,
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `medicine_name`, `medicine_brand`, `is_del`) VALUES
(1, 'Paracetamol', 'Biogesic', 0),
(2, 'Lisinopril', 'Pfizer', 0),
(3, 'Amoxicillin', 'Novartis', 0),
(4, 'Omeprazole', 'GSK', 0),
(5, 'Metformin', 'Bayer', 0);

-- --------------------------------------------------------

--
-- Table structure for table `medicine_details`
--

CREATE TABLE `medicine_details` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `packing` varchar(60) NOT NULL,
  `exp_date` date NOT NULL,
  `quantity` int(5) NOT NULL,
  `img_name` text,
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medicine_details`
--

INSERT INTO `medicine_details` (`id`, `medicine_id`, `packing`, `exp_date`, `quantity`, `img_name`, `is_del`) VALUES
(1, 1, 'Tablet', '2024-06-30', 100, 'none.jpeg', 0),
(2, 2, 'Capsule', '2023-12-15', 50, 'none.jpeg', 0),
(3, 3, 'Syrup', '2025-03-31', 80, 'none.jpeg', 0),
(4, 4, 'Gel', '2024-09-30', 120, 'none.jpeg', 0),
(5, 5, 'Cream', '2024-07-31', 60, 'none.jpeg', 0),
(6, 1, 'Drops', '2023-11-30', 90, 'none.jpeg', 0),
(7, 2, 'Inhaler', '2024-05-15', 70, 'none.jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(60) NOT NULL,
  `address` varchar(100) NOT NULL,
  `cnic` varchar(17) NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` varchar(12) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `contact_person_no` varchar(20) NOT NULL,
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `patient_name`, `address`, `cnic`, `date_of_birth`, `phone_number`, `gender`, `contact_person`, `relationship`, `contact_person_no`, `is_del`) VALUES
(1, 'JOHNSON, EMMA, GRACE', '456 Elm St', 'S002', '1999-09-20', '9876543210', 'Female', 'David Johnson', 'Father', '1234567890', 0),
(2, 'DAVIS, MICHAEL, JAMES', '789 Oak St', 'S003', '2001-02-10', '5555555555', 'Male', 'Laura Davis', 'Guardian', '1111111111', 0),
(3, 'WILSON, EMILY, ROSE', '321 Pine St', 'S004', '2002-07-05', '7777777777', 'Female', 'Daniel Wilson', 'Father', '8888888888', 0);

-- --------------------------------------------------------

--
-- Table structure for table `patient_medication_history`
--

CREATE TABLE `patient_medication_history` (
  `id` int(11) NOT NULL,
  `patient_visit_id` int(11) NOT NULL,
  `medicine_details_id` int(11) NOT NULL,
  `quantity` tinyint(4) NOT NULL,
  `dosage` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `patient_visits`
--

CREATE TABLE `patient_visits` (
  `id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `next_visit_date` date DEFAULT NULL,
  `bp` varchar(23) NOT NULL,
  `weight` varchar(12) NOT NULL,
  `disease` varchar(30) NOT NULL,
  `pres_remarks` varchar(300) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `display_name` varchar(30) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `access_lvl` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `display_name`, `user_name`, `password`, `profile_picture`, `access_lvl`) VALUES
(1, 'Clinic Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'admin.png', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowed`
--
ALTER TABLE `borrowed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `equipment_details_id` (`equipment_details_id`);

--
-- Indexes for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment_details`
--
ALTER TABLE `equipment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_details`
--
ALTER TABLE `medicine_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_medicine_details_medicine_id` (`medicine_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_medication_history`
--
ALTER TABLE `patient_medication_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patient_medication_history_patients_visits_id` (`patient_visit_id`),
  ADD KEY `fk_patient_medication_history_medicine_details_id` (`medicine_details_id`);

--
-- Indexes for table `patient_visits`
--
ALTER TABLE `patient_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patients_visit_patient_id` (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowed`
--
ALTER TABLE `borrowed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `equipment_details`
--
ALTER TABLE `equipment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medicine_details`
--
ALTER TABLE `medicine_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_medication_history`
--
ALTER TABLE `patient_medication_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_visits`
--
ALTER TABLE `patient_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed`
--
ALTER TABLE `borrowed`
  ADD CONSTRAINT `borrowed_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `borrowers` (`id`),
  ADD CONSTRAINT `borrowed_ibfk_2` FOREIGN KEY (`equipment_details_id`) REFERENCES `equipment_details` (`id`);

--
-- Constraints for table `equipment_details`
--
ALTER TABLE `equipment_details`
  ADD CONSTRAINT `equipment_details_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`);

--
-- Constraints for table `medicine_details`
--
ALTER TABLE `medicine_details`
  ADD CONSTRAINT `fk_medicine_details_medicine_id` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);

--
-- Constraints for table `patient_medication_history`
--
ALTER TABLE `patient_medication_history`
  ADD CONSTRAINT `fk_patient_medication_history_medicine_details_id` FOREIGN KEY (`medicine_details_id`) REFERENCES `medicine_details` (`id`),
  ADD CONSTRAINT `fk_patient_medication_history_patients_visits_id` FOREIGN KEY (`patient_visit_id`) REFERENCES `patient_visits` (`id`);

--
-- Constraints for table `patient_visits`
--
ALTER TABLE `patient_visits`
  ADD CONSTRAINT `fk_patients_visit_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `patient_visits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
