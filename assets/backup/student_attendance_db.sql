-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 02, 2023 lúc 08:52 PM
-- Phiên bản máy phục vụ: 10.4.24-MariaDB
-- Phiên bản PHP: 7.4.29

START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `student_attendance_db`
--
CREATE DATABASE IF NOT EXISTS `student_attendance_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE student_attendance_db;

DELIMITER $$
--
-- Thủ tục
--
DROP PROCEDURE IF EXISTS `get_ReportAttendance`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ReportAttendance` (IN `MONTH_IN` INT, IN `YEAR_IN` INT, IN `CS_ID_IN` INT)   BEGIN
	
SELECT
    al.class_subject_id,
	s.id_no AS student_idno,
    s.name AS student_name,
    COUNT(CASE
        WHEN ar.type = 1 THEN 1
        ELSE NULL
    END) AS 'present',
    COUNT(CASE
        WHEN ar.type = 2 THEN 1
        ELSE NULL
    END) AS 'late',
    COUNT(CASE
        WHEN ar.type = 0 THEN 1
        ELSE NULL
    END) AS 'absent',
    YEAR(ar.date_created) year,
    MONTH(ar.date_created) month
FROM
    attendance_list AS al
        JOIN
    attendance_record AS ar ON al.id = ar.attendance_id
        JOIN
    students AS s ON ar.student_id = s.id
WHERE 
	al.class_subject_id = CS_ID_IN
    AND YEAR(ar.date_created) = YEAR_IN
	AND MONTH(ar.date_created) = MONTH_IN
GROUP BY al.class_subject_id , s.name, s.id_no , YEAR(ar.date_created) , MONTH(ar.date_created);

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendance_list`
--

DROP TABLE IF EXISTS `attendance_list`;
CREATE TABLE `attendance_list` (
  `id` int(30) NOT NULL,
  `class_subject_id` int(30) NOT NULL,
  `doc` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `note` varchar(500) NOT NULL
) ;

--
-- RELATIONSHIPS FOR TABLE `attendance_list`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendance_record`
--

DROP TABLE IF EXISTS `attendance_record`;
CREATE TABLE `attendance_record` (
  `id` int(30) NOT NULL,
  `attendance_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0=absent,1=present,2=late',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- RELATIONSHIPS FOR TABLE `attendance_record`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` int(30) NOT NULL,
  `course_id` int(30) NOT NULL,
  `level` varchar(50) NOT NULL,
  `section` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- RELATIONSHIPS FOR TABLE `class`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `class_subject`
--

DROP TABLE IF EXISTS `class_subject`;
CREATE TABLE `class_subject` (
  `id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `time_remaining` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- RELATIONSHIPS FOR TABLE `class_subject`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int(30) NOT NULL,
  `course` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- RELATIONSHIPS FOR TABLE `courses`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faculty`
--

DROP TABLE IF EXISTS `faculty`;
CREATE TABLE `faculty` (
  `id` int(30) NOT NULL,
  `id_no` varchar(50) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- RELATIONSHIPS FOR TABLE `faculty`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(30) NOT NULL,
  `id_no` varchar(50) NOT NULL,
  `class_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- RELATIONSHIPS FOR TABLE `students`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `id` int(30) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `time_subject` float NOT NULL,
  `description` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- RELATIONSHIPS FOR TABLE `subjects`:
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2=Staff',
  `faculty_id` int(30) NOT NULL COMMENT 'for faculty user only'
) ;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `attendance_list`
--
ALTER TABLE `attendance_list`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `attendance_record`
--
ALTER TABLE `attendance_record`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `class_subject`
--
ALTER TABLE `class_subject`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `attendance_list`
--
ALTER TABLE `attendance_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `attendance_record`
--
ALTER TABLE `attendance_record`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `class`
--
ALTER TABLE `class`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `class_subject`
--
ALTER TABLE `class_subject`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `students`
--
ALTER TABLE `students`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
