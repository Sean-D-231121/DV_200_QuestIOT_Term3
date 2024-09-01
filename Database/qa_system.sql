-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2024 at 01:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qa_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answerid` int(11) NOT NULL,
  `questionid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `created_at` date DEFAULT current_timestamp(),
  `like` int(11) DEFAULT NULL,
  `dislike` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answerid`, `questionid`, `userid`, `answer_text`, `created_at`, `like`, `dislike`) VALUES
(1, 8, 1, 'He could have done better', '2024-08-21', NULL, NULL),
(2, 8, 1, 'What should I do?', '2024-08-21', NULL, NULL),
(3, 13, 5, 'There is a place called BT games which only needs you to pay R8000.00 for it.', '2024-08-22', NULL, NULL),
(4, 7, 5, 'I have only just played but you need to try to constantly be behind it and find cover when it uses it\'s ranged attack', '2024-08-22', NULL, NULL),
(5, 16, 4, 'Are there any problems with it?', '2024-08-31', NULL, NULL),
(6, 16, 15, 'I am interested in this sale how do I contact you', '2024-09-01', NULL, NULL),
(7, 17, 15, 'Wow this sounds interesting!', '2024-09-01', NULL, NULL),
(8, 17, 16, 'This was eye-opening!', '2024-09-01', NULL, NULL),
(9, 22, 16, 'This was insightful!', '2024-09-01', NULL, NULL),
(10, 23, 4, 'This looks like a great laptop', '2024-09-01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoriesid` int(11) NOT NULL,
  `categorie_name` varchar(50) NOT NULL,
  `categorie_image` text DEFAULT NULL,
  `amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoriesid`, `categorie_name`, `categorie_image`, `amount`) VALUES
(1, 'Computers', 'desktops.jpg', 0),
(2, '3D design', '3d-design.jpg', 0),
(3, 'Education', 'Courses.jpg', 0),
(4, 'Gaming', 'Gaming.jpg', 0),
(5, 'Software', 'Software.jpg\r\n', 0);

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `OfferID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `OfferAmount` decimal(10,2) DEFAULT NULL,
  `OfferTime` int(11) DEFAULT NULL,
  `CreatedAt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`OfferID`, `ProductID`, `userid`, `OfferAmount`, `OfferTime`, `CreatedAt`) VALUES
(3, 3, 1, 7600.00, NULL, '2024-08-31'),
(4, 3, 4, 7700.00, NULL, '2024-08-31'),
(5, 3, 15, 7800.00, NULL, '2024-09-01'),
(6, 5, 16, 15500.00, NULL, '2024-09-01'),
(7, 7, 4, 13000.00, NULL, '2024-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(50) NOT NULL,
  `StartingPrice` decimal(10,2) NOT NULL,
  `ProductImage` text DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `CreatedAt` date DEFAULT NULL,
  `QuestionID` int(11) DEFAULT NULL,
  `CategoriesID` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `StartingPrice`, `ProductImage`, `Description`, `CreatedAt`, `QuestionID`, `CategoriesID`, `userid`) VALUES
(1, 'HP 39 cm (15.6\") 250 G9 Intel Celeron Laptop (969M', 4999.99, 'HP 39 cm (15.6) 250 G9 Intel Celeron Laptop (969M8ET).webp', 'The HP 39 cm (15.6\") 250 G9 Laptop features an Intel Celeron processor, offering reliable performance for everyday tasks. Its compact design makes it ideal for both work and casual use.', '2024-08-31', 14, 1, 4),
(2, 'Creality Ender 3 Neo 3D Printer', 3500.00, 'Creality Ender 3 Neo 3D Printer.webp', 'The Creality Ender 3 Neo 3D Printer is a versatile, budget-friendly machine with reliable print quality, a heated bed, and a robust design. Ideal for both beginners and advanced users.', '2024-08-31', 15, 2, 4),
(3, 'Xbox Series X 1TB Console', 7500.00, 'Xbox Series X 1TB Console.webp', 'Unleash the power of next-gen gaming with the Xbox Series X 1TB Console! Experience stunning visuals, lightning-fast load times, and an expansive library of games. Elevate your gameplay and explore new worlds with the most powerful Xbox ever.', '2024-08-31', 16, 4, 1),
(5, 'Apex Pro Gaming and Design Laptop', 15000.00, 'Apex Pro Gaming and Design Laptop.jpg', 'Wonderful and cheap computer product', '2024-09-01', 20, 1, 15),
(6, 'Apex Pro Gaming and Design Laptop', 15000.00, 'Apex Pro Gaming and Design Laptop.jpg', 'The Apex Pro Gaming and Design Laptop strikes the perfect balance between performance and affordability, making it ideal for gamers and professionals alike. Powered by an AMD Ryzen 7 processor and an NVIDIA GTX 1660 Ti GPU, this laptop provides smooth gameplay and robust performance for software like Blender and AutoCAD. With 16GB of RAM and a 512GB SSD, it offers quick load times and ample storage for games, software, and design files. The 15.6-inch Full HD display ensures vibrant colors and sharp details, making it a great choice for gaming and 3D design work.', '2024-09-01', 21, 1, 15),
(7, ' NovaTech Fusion 15', 12000.00, 'NovaTech Fusion 15.jpg', 'The NovaTech Fusion 15 is a versatile laptop designed for both gaming enthusiasts and creative professionals. Featuring an Intel Core i7 processor and an AMD Radeon RX 6500M GPU, it delivers solid performance for modern games and demanding applications. The 16GB of RAM and 1TB SSD provide ample memory and storage for multitasking and large project files. The 15.6-inch Full HD display offers crisp visuals and accurate color reproduction, making it suitable for both immersive gaming and detailed 3D design work. Its sleek design and enhanced cooling system ensure reliable performance throughout extended use.', '2024-09-01', 23, 1, 16);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `questionid` int(11) NOT NULL,
  `heading` varchar(50) NOT NULL,
  `question_text` text NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `like` int(11) DEFAULT 0,
  `dislike` int(11) DEFAULT 0,
  `categoriesid` int(11) DEFAULT NULL,
  `created_at` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`questionid`, `heading`, `question_text`, `userid`, `like`, `dislike`, `categoriesid`, `created_at`) VALUES
(7, 'How to defeat the erdtree?', 'How do you learn the attack pattern and where to go?', 1, 3, 1, 4, '2024-08-21'),
(8, 'New Computer science discovery?', 'I was looking on the internet and found this', 1, 0, 0, 3, '2024-08-21'),
(9, 'Where are the best games for the best price?', 'I have been looking around and I have not found any good shops or online stores for good games.', 1, 3, 2, 4, '2024-08-21'),
(10, 'What can I do to get a new bat at a cheap price?', 'If anyone wants to give me their cheap bats please call 053040303', 1, 1, 0, 5, '2024-08-21'),
(11, 'What is the best computer I can get at the moment ', 'I was wondering  if anyone knew what I can get with $1500.', 1, 0, 0, 1, '2024-08-21'),
(12, 'What does the fox say?', 'qwqdefrgthyjui', 1, 0, 0, 1, '2024-08-21'),
(13, 'Where can I find the new Xbox at an affordable pri', 'I have been looking around but can\'t find anything which I can afford?', 5, 3, 2, 4, '2024-08-22'),
(14, 'Here is a new HP 39 cm (15.6\") 250 G9 Intel Celero', 'I tried this pc but I am thinking of getting a new so I want to sell it.', 4, 1, 0, 1, '2024-08-31'),
(15, 'I am looking to sell this Creality Ender 3 Neo 3D ', 'The motor is pretty old and may need replacing and it sometimes fails when printing', 4, 2, 1, 2, '2024-08-31'),
(16, 'New Xbox Series X 1TB Console on sale!', 'Here is the new Xbox series X I want to buy  PlayStation instead.', 1, 2, 1, 4, '2024-08-31'),
(17, 'How to Implement Real-Time Data Sync in a Multi-Us', 'I\'m currently working on a multi-user application that requires real-time data synchronization across different devices. The application allows users to make offers on products, and I want to ensure that any changes made by one user are instantly reflected to all other users currently viewing the same product page.', 15, 1, 0, 5, '2024-09-01'),
(20, 'Apex Pro Gaming and Design Laptop wondrous develop', 'I\'m looking for a laptop that can serve as both a gaming machine and a tool for my 3D design projects. I’ve been eyeing the Apex Pro Gaming and Design Laptop due to its specifications and price point. Has anyone used this laptop for gaming and design software? How does it perform under heavy use, like gaming on high settings or rendering complex 3D models?', 15, 1, 0, 1, '2024-09-01'),
(21, 'Can the Apex Pro Gaming and Design Laptop handle b', 'I\'m looking for a laptop that can serve as both a gaming machine and a tool for my 3D design projects. I’ve been eyeing the Apex Pro Gaming and Design Laptop due to its specifications and price point. Has anyone used this laptop for gaming and design software? How does it perform under heavy use, like gaming on high settings or rendering complex 3D models?', 15, 1, 0, 1, '2024-09-01'),
(22, 'What Are the Best Practices for Handling User Auth', 'I’m developing a web application in PHP and need to implement user authentication and authorization. My app requires different levels of access depending on user roles (e.g., admin, regular user, guest).\r\n\r\nWhat are the best practices for securely handling user authentication (e.g., login, password hashing) and authorization (e.g., role-based access control) in PHP? Are there any recommended libraries or frameworks that can simplify this process? Additionally, how should I handle session management and ensure that user data remains secure?', 16, 1, 0, 5, '2024-09-01'),
(23, 'How does the NovaTech Fusion 15 perform with high-', 'I\'m considering the NovaTech Fusion 15 for both gaming and 3D design tasks. It seems like a good balance of price and performance, but I’m curious about its real-world capabilities. Can it handle high-end games at decent settings and also manage resource-intensive 3D design software without issues? Any feedback on its performance in these areas would be greatly appreciated.', 16, 0, 0, 1, '2024-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(50) DEFAULT NULL,
  `userImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `usertype`, `userImage`) VALUES
(1, 'JamesDay', 'james.carter@gmail.com', 'james123', 'Customer', 'JamesCarter.jpg'),
(4, 'JohnDoe', 'John.Doe@gmail.com', 'John123', NULL, 'JohnDoe.jpg'),
(5, 'MarkMarkle', 'Mark.Mar@gmail.com', 'Mark123', NULL, 'MarkMarkle.jpg'),
(15, 'RogerMclain', 'RogMclain@gmail.com', 'rog123', NULL, 'RogerMaclain.jpg'),
(16, 'JamesChetty', 'James.Chetty@gmail.com', 'James123', NULL, 'JamesChetty.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answerid`),
  ADD KEY `QuestionID` (`questionid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoriesid`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`OfferID`),
  ADD KEY `userid` (`userid`),
  ADD KEY `ProductID` (`ProductID`) USING BTREE;

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `QuestionID` (`QuestionID`),
  ADD KEY `CategoriesID` (`CategoriesID`),
  ADD KEY `FK_product_user` (`userid`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`questionid`),
  ADD KEY `question_FK_1` (`userid`),
  ADD KEY `question_FK_2` (`categoriesid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoriesid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `OfferID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `questionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`questionid`) REFERENCES `question` (`questionid`),
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `offer_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_product_user` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`QuestionID`) REFERENCES `question` (`questionid`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`CategoriesID`) REFERENCES `categories` (`categoriesid`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_FK_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `question_FK_2` FOREIGN KEY (`categoriesid`) REFERENCES `categories` (`categoriesid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
