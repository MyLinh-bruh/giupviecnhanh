-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 13, 2026 lúc 02:16 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `giupviecnhanh_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `booking_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `address` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `staff_id`, `service_id`, `customer_name`, `customer_phone`, `total_amount`, `booking_date`, `start_time`, `address`, `note`, `status`, `created_at`) VALUES
(1, 4, 2, 4, 'Mỹ Linh', '0787560943', 150000.00, '2026-01-17', '07:30:00', 'An Tân', '', 'cancelled', '2025-12-21 07:40:57'),
(2, 5, 5, 9, 'Công Chúa Bong Bóng', '0905901558', 300000.00, '2026-01-02', '06:45:00', 'Nguyễn Tất Thành', '', 'pending', '2025-12-21 07:42:29'),
(3, 6, 8, 11, 'Nhung Lê', '0773282912', 100000.00, '2025-12-08', '16:00:00', 'Hòa Phong', '', 'confirmed', '2025-12-21 07:44:18'),
(4, 4, NULL, 4, 'Mỹ Linh', '0787560943', 150000.00, '2026-01-01', '08:35:00', 'an tan', '', 'pending', '2025-12-26 18:35:32'),
(5, 4, NULL, 5, 'Mỹ Linh', '0787560943', 150000.00, '2026-01-01', '08:00:00', 'an tan', '', 'pending', '2025-12-26 18:36:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_text` varchar(50) DEFAULT NULL,
  `price_value` decimal(10,2) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `bg_color` varchar(50) DEFAULT 'bg-brand-50',
  `text_color` varchar(50) DEFAULT 'text-brand-600',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price_text`, `price_value`, `icon`, `bg_color`, `text_color`, `created_at`) VALUES
(1, 'Dọn phòng', 'Dọn phòng và vệ sinh cơ bản.', 'Từ 100.000đ', 100000.00, 'fa-broom', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(2, 'Giặt đồ', 'Giặt và phơi/quản lý quần áo.', 'Từ 80.000đ', 80000.00, 'fa-shirt', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(3, 'Nấu ăn', 'Nấu ăn gia đình theo yêu cầu.', 'Từ 120.000đ', 120000.00, 'fa-utensils', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(4, 'Chăm sóc em bé', 'Chăm sóc và hỗ trợ trông trẻ.', 'Từ 150.000đ', 150000.00, 'fa-baby', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(5, 'Chăm sóc người già', 'Hỗ trợ và chăm sóc người cao tuổi.', 'Từ 150.000đ', 150000.00, 'fa-person-walking', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(6, 'Dọn bếp', 'Vệ sinh và sắp xếp khu vực bếp.', 'Từ 90.000đ', 90000.00, 'fa-kitchen-set', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(7, 'Làm vườn', 'Chăm sóc cây cảnh và sân vườn.', 'Từ 200.000đ', 200000.00, 'fa-tree', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(8, 'Sửa chữa máy lạnh', 'Sửa chữa và vệ sinh máy lạnh.', 'Từ 250.000đ', 250000.00, 'fa-snowflake', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(9, 'Dọn hồ bơi', 'Dọn dẹp vệ sinh hồ bơi.', 'Từ 300.000đ', 300000.00, 'fa-water', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(10, 'Dọn vườn', 'Dọn dẹp và làm sạch khu vực vườn.', 'Từ 180.000đ', 180000.00, 'fa-tree', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(11, 'Dọn dẹp', 'Dọn dẹp nhà cửa tổng quát.', 'Từ 100.000đ', 100000.00, 'fa-broom', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(12, 'Vệ sinh sofa', 'Vệ sinh và làm sạch ghế sofa.', 'Từ 200.000đ', 200000.00, 'fa-couch', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(13, 'Sửa chữa nhà', 'Sửa chữa các hư hỏng trong nhà.', 'Từ 400.000đ', 400000.00, 'fa-house', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05'),
(14, 'Vệ sinh hồ bơi', 'Làm sạch hồ bơi định kỳ.', 'Từ 300.000đ', 300000.00, 'fa-water', 'bg-brand-50', 'text-brand-600', '2025-12-21 07:06:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Nam','Nữ') NOT NULL,
  `age` int(11) NOT NULL,
  `rating` decimal(2,1) DEFAULT 5.0,
  `service_names` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `staff`
--

INSERT INTO `staff` (`id`, `name`, `gender`, `age`, `rating`, `service_names`, `price`, `image`, `is_available`, `created_at`) VALUES
(1, 'Lương Yến Nhân', 'Nữ', 22, 4.8, 'Dọn phòng, Giặt đồ, nấu ăn', 220000.00, 'staff_nhan.jpg', 1, '2025-12-21 07:06:05'),
(2, 'Trần Thu Sương', 'Nữ', 30, 4.9, 'Nấu ăn ,chăm sóc em bé,chăm sóc người già', 180000.00, 'staff_suong.jpg', 1, '2025-12-21 07:06:05'),
(3, 'Nguyễn Phương Thảo', 'Nữ', 25, 4.7, 'Nấu ăn, Dọn bếp , Giặt đồ', 130000.00, 'staff_thao.jpg', 1, '2025-12-21 07:06:05'),
(4, 'Phạm Thu Uyên', 'Nữ', 30, 4.7, 'Chăm sóc em bé, Chăm sóc người già , Làm vườn', 300000.00, 'staff_thu_uyen_moi.jpg', 1, '2025-12-21 07:06:05'),
(5, 'Lê Bá Bảo Long', 'Nam', 25, 4.8, 'Sữa chữa máy lạnh,Dọn hồ bơi , Dọn vườn', 220000.00, 'staff_long.jpg', 1, '2025-12-21 07:06:05'),
(6, 'Lê Lân', 'Nam', 30, 4.6, 'Sữa chữa máy lạnh, Dọn dẹp ,Vệ sinh sopha', 200000.00, 'staff_lan.jpg', 1, '2025-12-21 07:06:05'),
(7, 'Nguyễn Hùng', 'Nam', 33, 4.5, 'Sửa chữa nhà, Dọn dẹp ,Vệ sinh sopha', 300000.00, 'staff_hung.jpg', 1, '2025-12-21 07:06:05'),
(8, 'Dương Thị Hằng', 'Nữ', 27, 4.6, 'Dọn dẹp nhà cửa , nấu ăn , chăm sóc người già', 270000.00, 'staff_hang.jpg', 1, '2025-12-21 07:06:05'),
(9, 'Trần Văn Ba', 'Nam', 40, 4.7, 'Sữa chữa máy lạnh, Vệ sinh hồ bơi ,Vệ sinh sopha', 350000.00, 'staff_ba.jpg', 1, '2025-12-21 07:06:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Quản Trị Viên', 'admin@gmail.com', '$2y$10$d/lofqWYlQDwUz/.HviufubCF/RdW83xbpQzgY2dBrW8yzt/8ewhW', 'admin', '2025-01-01 01:00:00'),
(3, 'Phương Thảo', 'thao@gmail.com', '$2y$10$83mttJq1MazNhOxG248K8.YO5k1Xmq5Zy3VZHEcfGeB9RICjYraaC', 'user', '2025-12-21 07:09:15'),
(4, 'Mỹ Linh', 'linh@gmail.com', '$2y$10$7HGB0IM2o5nIvw661C0SAu4i9UbR5L2xnq4LwLfNVdiuIZJs/2Fp6', 'user', '2025-12-21 07:40:00'),
(5, 'Công Chúa Bong Bóng', 'bong@gmail.com', '$2y$10$a1I0nSje8QQChkH8TbiWHe02NxxsnjUqOPGkiR9.ZTQvAXulY3Ax2', 'user', '2025-12-21 07:41:37'),
(6, 'Nhung Lê', 'nhung@gmail.com', '$2y$10$Pyw.a.pjffYVyVh1ciqsruNKylWwFDhTX2YayNCkKsDCtgfCRCFMW', 'user', '2025-12-21 07:43:03'),
(7, 'khuê', 'khue@gmail.com', '$2y$10$c4RQTmDOsHj.OnNOgKC7lOx08gXwqnFCC3i.NBK0FlgTg6tFqXQi2', 'user', '2026-09-04 13:12:49');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
