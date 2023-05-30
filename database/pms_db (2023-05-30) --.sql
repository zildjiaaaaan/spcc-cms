-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2023 at 04:01 PM
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
  `equipment_details_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrowed`
--

INSERT INTO `borrowed` (`id`, `borrower_id`, `equipment_details_id`) VALUES
(1, 15, 6);

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
(2, 'Jane', 'Beth', 'Smith', 'Principal', 'B002', '9876543210', 0),
(3, 'David', 'Charles', 'Johnson', 'Counselor', 'B003', '5551234567', 0),
(4, 'Sarah', 'Elizabeth', 'Wilson', 'Teacher', 'B004', '9998887776', 0),
(5, 'Michael', 'James', 'Brown', 'Teacher', 'B005', '1112223334', 0),
(6, 'Emily', 'Grace', 'Taylor', 'Librarian', 'B006', '4445556667', 0),
(7, 'Daniel', 'Robert', 'Anderson', 'Teacher', 'B007', '7778889990', 0),
(8, 'Olivia', 'Rose', 'Martin', 'Teacher', 'B008', '2223334445', 0),
(9, 'Jacob', 'Matthew', 'Thomas', 'Teacher', 'B009', '8889990001', 0),
(10, 'Sophia', 'Ava', 'Walker', 'Teacher', 'B010', '6667778882', 0),
(11, 'William', 'Alexander', 'White', 'Teacher', 'B011', '3334445556', 0),
(12, 'Mia', 'Charlotte', 'Hall', 'Teacher', 'B012', '9990001112', 0),
(13, 'James', 'Benjamin', 'Clark', 'Teacher', 'B013', '5556667778', 0),
(14, 'Abigail', 'Elizabeth', 'Lee', 'Teacher', 'B014', '8889990003', 0),
(15, 'Benjamin', 'Andrew', 'Adams', 'Teacher', 'B015', '2223334449', 0);

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
(1, 'Stethoscope', 'Littmann', '2022-01-05', 5, 0),
(2, 'Blood Pressure Monitor', 'Omron', '2022-02-10', 5, 0),
(3, 'Thermometer', 'Braun', '2022-03-15', 15, 0),
(4, 'Sphygmomanometer', 'A&D Medical', '2022-04-20', 0, 0),
(5, 'Glucometer', 'Accu-Chek', '2022-05-25', 0, 0),
(6, 'Oxygen Concentrator', 'Philips', '2022-06-30', 0, 0),
(7, 'Pulse Oximeter', 'Nonin', '2022-07-05', 0, 0),
(8, 'Wheelchair', 'Invacare', '2022-08-10', 0, 0),
(9, 'Crutches', 'Drive Medical', '2022-09-15', 0, 0),
(10, 'Walker', 'Medline', '2022-10-20', 0, 0),
(11, 'Nebulizer', 'Devilbiss', '2022-11-25', 2, 0),
(12, 'Exam Table', 'Midmark', '2022-12-30', 0, 0),
(13, 'Surgical Instrument Set', 'Stryker', '2023-01-05', 0, 0),
(14, 'Operating Microscope', 'Leica', '2023-02-10', 1, 0),
(15, 'Dental Chair', 'A-dec', '2023-03-15', 2, 0),
(16, 'Disposable Syringe', 'Indoplas', '2023-05-29', NULL, 0);

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
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipment_details`
--

INSERT INTO `equipment_details` (`id`, `equipment_id`, `status`, `state`, `unavailable_since`, `unavailable_until`, `quantity`, `remarks`, `is_del`) VALUES
(1, 2, 'Available', 'Active', NULL, NULL, 1, 'In Room 101', 0),
(2, 2, 'Available', 'Active', NULL, NULL, 1, 'In Room 102', 0),
(3, 3, 'Unavailable', 'Used', '2023-05-28', '2023-05-30', 5, 'to be restocked', 0),
(4, 15, 'Available', 'Non-Borrowable', NULL, NULL, 2, 'in Dental Room', 0),
(5, 14, 'Unavailable', 'Defective', '2023-05-26', '2023-06-01', 1, 'Currently in repair', 0),
(6, 11, 'Unavailable', 'Borrowed', '2023-05-28', '2023-05-29', 2, 'test', 0),
(7, 1, 'Available', 'Active', NULL, NULL, 5, '', 0),
(8, 2, 'Available', 'Non-Borrowable', NULL, NULL, 3, '', 0),
(9, 3, 'Unavailable', 'Missing', '2023-05-26', NULL, 10, 'Last seen at Room 103', 0);

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
(1, 'Amoxicillin', 'Rhea', 0),
(2, 'Mefenamic', 'RiteMed', 0),
(3, 'Losartan', 'Generic', 0),
(4, 'Antibiotic', 'Biogesic', 0),
(5, 'Antihistamine', 'Xyzal', 0),
(6, 'Atorvastatin', 'RiteMed', 0),
(7, 'Oxymetazoline', 'Drixine', 0),
(8, 'Paracetamol', 'Biogesic', 0),
(9, 'Adderall', 'RiteMed', 1),
(12, 'Amoxicillin', 'Brand X', 1);

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
  `is_del` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medicine_details`
--

INSERT INTO `medicine_details` (`id`, `medicine_id`, `packing`, `exp_date`, `quantity`, `is_del`) VALUES
(1, 4, 'Syrup', '2023-06-05', 19, 0),
(2, 4, 'Syrup', '2023-05-26', 30, 0),
(3, 5, 'Gel', '2024-07-13', 12, 0),
(4, 6, 'Tablet', '2024-05-19', 0, 0),
(5, 3, 'Tablet', '2024-05-20', 0, 0),
(6, 2, 'Capsule', '2023-12-14', 53, 0),
(7, 7, 'Spray', '2023-10-14', 33, 0),
(8, 8, 'Capsule', '2023-06-18', 5, 0),
(9, 8, 'Tablet', '2024-03-14', 0, 0),
(10, 2, 'Tablet', '2023-09-01', 50, 0),
(11, 7, 'Capsule', '2023-12-31', 300, 0);

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
(1, 'DELACRUZ, JUAN, SALVADOR', 'Sample Address 101 - Updated', '123654722', '1999-06-23', '091235649879', 'Male', 'Test', 'Test', '789456', 0),
(5, 'DOE, JOHN, GREEN', 'Caloocan City', '321654', '2000-02-14', '123456789', 'Male', 'Testt', 'Test', '456789', 0),
(6, 'DOE, JANE, FERNANDEZ', 'Caloocan City', '123456', '2000-02-03', '123456798', 'Female', 'Test', 'Test', '789733', 0),
(8, 'MAGANTE, ZILDJIAN, SANCHEZ', 'Caloocan City', '012345679', '2000-12-18', '0912345678', 'Male', 'Juan Delacruz', 'Father', '0999123457', 0);

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

--
-- Dumping data for table `patient_medication_history`
--

INSERT INTO `patient_medication_history` (`id`, `patient_visit_id`, `medicine_details_id`, `quantity`, `dosage`) VALUES
(1, 1, 1, 5, '250'),
(2, 1, 6, 2, '500'),
(3, 2, 2, 2, '250'),
(4, 2, 7, 2, '250'),
(5, 4, 2, 1, '250'),
(6, 6, 6, 3, '250'),
(7, 7, 1, 3, '250'),
(8, 7, 8, 3, '500'),
(9, 8, 4, 20, '1'),
(10, 8, 5, 9, '1'),
(11, 9, 1, 1, '500'),
(12, 9, 6, 2, '250');

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
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient_visits`
--

INSERT INTO `patient_visits` (`id`, `visit_date`, `next_visit_date`, `bp`, `weight`, `disease`, `pres_remarks`, `patient_id`) VALUES
(1, '2022-06-28', '2022-06-30', '120/80', '65 kg.', 'Wounded Arm', 'No Remarks', 1),
(2, '2022-06-30', '2022-07-02', '120/80', '65 kg.', 'Rhinovirus', 'No Remarks', 1),
(4, '2023-05-18', '2023-05-23', '120/80', '50 kg', 'Flu', 'No Remarks', 1),
(6, '2023-05-18', '2023-05-19', '120/80', '50 kg', 'Toothache', 'No Remarks', 5),
(7, '2023-05-19', '2023-05-21', '120/70', '65 kg', 'Fever', 'This is a test', 6),
(8, '2023-05-20', '2023-05-22', '123', '123', 'test', 'test', 6),
(9, '2023-05-27', '2023-05-29', '125/85', '55', 'Headache', 'Take this medicine 3x a day', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `display_name` varchar(30) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `profile_picture` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `display_name`, `user_name`, `password`, `profile_picture`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', '1656551981avatar.png '),
(4, 'Zildjian', 'zildjian-admin', '81dc9bdb52d04dc20036dbd8313ed055', '1684405733Darth-Vader-Dark-Minimal-iPhone-Wallpaper.png');

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
  ADD KEY `fk_patients_visit_patient_id` (`patient_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `equipment_details`
--
ALTER TABLE `equipment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `medicine_details`
--
ALTER TABLE `medicine_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patient_medication_history`
--
ALTER TABLE `patient_medication_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `patient_visits`
--
ALTER TABLE `patient_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `fk_patients_visit_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
