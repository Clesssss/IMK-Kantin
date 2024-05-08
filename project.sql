-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2023 at 08:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(300) DEFAULT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`user_id`, `menu_id`, `quantity`, `note`, `subtotal`) VALUES
(1, 41, 1, '', 65000);

-- --------------------------------------------------------

--
-- Table structure for table `kantin`
--

CREATE TABLE `kantin` (
  `kantin_id` int(11) NOT NULL,
  `name` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kantin`
--

INSERT INTO `kantin` (`kantin_id`, `name`) VALUES
(1, 'P'),
(2, 'Q'),
(3, 'W'),
(4, 'T');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(300) NOT NULL,
  `image` varchar(300) NOT NULL,
  `price` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `name`, `description`, `image`, `price`, `stall_id`) VALUES
(1, 'Peking Duck', 'Crispy roasted duck with thin, tender skin, traditionally served with hoisin sauce, thin pancakes, and julienned scallions.', 'https://www.unileverfoodsolutions.co.id/dam/ufs-id/recipes/Peking%20Duck.jpg', 150000, 2),
(2, 'Dim Sum Platter', 'A variety of bite-sized dumplings and steamed buns filled with savory ingredients such as shrimp, pork, and vegetables, offering a delightful assortment of flavors.', 'https://cdn.tatlerasia.com/asiatatler/i/ph/2020/08/17152020-xiu-dimsum-platter-top-shot_cover_2000x1333.jpg', 80000, 2),
(3, 'Mapo Tofu', 'A Sichuan specialty featuring silky tofu cubes in a spicy and numbing chili bean sauce, often paired with minced pork and green onions.', 'https://www.seriouseats.com/thmb/CaR7btHrJgEO3OKZD1Z_795VmII=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/__opt__aboutcom__coeus__resources__content_migration__serious_eats__seriouseats.com__2011__07__2021-02-12-Mapo-Tofu-MHOM-10-804db1211f1d47dbae505341d1e7b994.jpg', 60000, 2),
(4, 'Nasi Goreng', 'A flavorful Indonesian fried rice dish made with pre-cooked rice stir-fried with sweet soy sauce, shallots, garlic, tamarind, and a combination of proteins like chicken, prawns, or beef. It\'s often garnished with fried shallots, cucumber, and a fried egg.', 'https://images.getrecipekit.com/20230527000540-andy-20cooks-20-20nasi-20goreng.jpg?aspect_ratio=16:9&quality=90&', 25000, 4),
(23, 'Fried Chicken', 'A classic favorite, our Original Recipe Chicken is freshly prepared with a secret blend of 11 herbs and spices, ensuring a crispy and flavorful experience.', 'https://geekrobocook.com/wp-content/uploads/2021/04/KFC-Chicken-fry.jpg', 23000, 6),
(24, 'French Fries', 'Crispy and golden on the outside, fluffy on the inside – our French Fries are the perfect side to complement your KFC meal.', 'https://files.kfcku.com/uploads/media/dummy/food/kfc-web_frenchfrieslar_l.png', 15000, 6),
(26, 'Portobello Mushroom Burger', 'Experience the gourmet goodness of our Portobello Mushroom Burger. Grilled Portobello mushrooms, Swiss cheese, lettuce, tomato, and zesty sauce come together for a satisfying meatless option.', 'https://carlsjr.com.my/wp-content/uploads/2022/05/portobello-mushroom-burger-2.png', 50000, 7),
(27, 'Western BeefBac Burger', 'Savor the bold flavors of our BeefBac Burger, featuring a juicy beef patty topped with crispy bacon, American cheese, lettuce, tomato, and our signature sauce. A carnivore\'s delight!', 'https://hips.hearstapps.com/hmg-prod/images/copycat-western-bacon-cheeseburger-2-1649170836.jpg', 55000, 7),
(28, 'Original Vanilla Cream Puff', ' Indulge in the classic delight of our Original Vanilla Cream Puff. A light, crispy shell filled with smooth vanilla custard cream. A timeless treat.', 'https://store.sirclo.com/blog/wp-content/uploads/2022/09/274106658_1279873475866942_2195405153470945985_n-1.jpg', 20000, 8),
(29, 'Chocolate Lovers\' Delight', 'For chocolate enthusiasts, our Chocolate Lovers\' Cream Puff is a must-try. A decadent choux pastry filled with rich chocolate cream for an irresistible experience.', 'https://images.squarespace-cdn.com/content/v1/600a96cbf8894d79eb1e1b1f/1612949893234-9XIRXOO1K3XNPS7LSMSR/chocolate+shell_small.png', 25000, 8),
(30, 'Dragon Roll', 'A sushi masterpiece featuring eel, avocado, and cucumber topped with thin avocado slices, eel sauce, and tobiko.', 'https://popmenucloud.com/cdn-cgi/image/width=1920,height=1920,format=auto,fit=scale-down/cswnyzje/b2b28c77-2b72-44f1-bdf4-1fdda92bac1b.jpg', 45000, 1),
(31, 'Miso Glazed Cod', ' Succulent cod fillet marinated in a sweet and savory miso glaze, grilled to perfection. Served with steamed jasmine rice and sautéed vegetables.', 'https://bestsousviderecipes.com/wp-content/uploads/sous_vide_miso_glazed_black_cod.jpg', 85000, 1),
(32, 'Matcha Green Tea Cheesecake', 'A delightful fusion of creamy cheesecake with the earthy richness of matcha green tea, topped with a drizzle of sweet red bean sauce.', 'https://www.tasteitpresents.com/wp-content/uploads/2019/05/Green-Tea-Cheese-Cake_No-Background-header.jpg', 32000, 1),
(33, 'Spaghetti Bolognese', 'Classic Italian meat sauce made with ground beef, tomatoes, onions, garlic, and herbs, served over al dente spaghetti.', 'https://api-lotte.hollacode.com/api/static/inspirasi/1660657926365-cara-memasak-spaghetti-bolognese-yang-benar-dijamin-lezat.jpg', 45000, 3),
(34, 'Fettuccine Alfredo', 'Creamy Alfredo sauce tossed with fettuccine pasta and garnished with Parmesan cheese and parsley.', 'https://www.simplyrecipes.com/thmb/M8XKDSLRjM-zGL90tk-o2Tj6bAI=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/Simply-Recipes-Copycat-Olive-Garden-Alfredo-LEAD-4-8f75111d3a324a0abb1ee46906f27417.jpg', 50000, 3),
(35, 'Margherita Pizza', 'Traditional pizza topped with fresh tomatoes, mozzarella cheese, basil, and a drizzle of olive oil.', 'https://chatelaine.com/wp-content/uploads/2023/07/margherita-pizza.jpg', 40000, 3),
(36, 'Rendang', 'A rich and flavorful slow-cooked beef stew, simmered in coconut milk and a blend of aromatic spices.', 'https://www.astronauts.id/blog/wp-content/uploads/2023/03/Resep-Rendang-Daging-Sapi-Untuk-Lebaran-Gurih-dan-Nikmat.jpg', 65000, 4),
(37, 'Gado-Gado', 'A traditional Indonesian salad with mixed vegetables, tofu, and hard-boiled eggs, topped with peanut sauce.', 'https://www.masakapahariini.com/wp-content/uploads/2019/01/gado-gado-MAHI.jpg', 30000, 4),
(38, 'Escargots de Bourgogne', 'Burgundy snails baked in garlic, parsley, and butter, served in the shell.', 'https://images.sbs.com.au/dims4/default/628e6ba/2147483647/strip/true/crop/1200x675+0+63/resize/1280x720!/quality/90/?url=http%3A%2F%2Fsbs-au-brightspot.s3.amazonaws.com%2Fdrupal%2Ffood%2Fpublic%2Fimg_7379-snails.jpg', 65000, 5),
(39, 'Boeuf Bourguignon', 'Slow-cooked beef stew in red wine with carrots, mushrooms, and pearl onions, served with buttered noodles.', 'https://food-images.files.bbci.co.uk/food/recipes/boeuf_bourguignon_25475_16x9.jpg', 80000, 5),
(40, 'Croissant', 'Classic French butter croissant, flaky on the outside and soft on the inside.', 'https://cdn.britannica.com/65/235965-050-A5D740E2/Croissants-jar-of-jam.jpg', 18000, 5),
(41, 'Bulgogi', 'Thinly sliced, marinated beef grilled to perfection, served with a side of rice and lettuce for wrapping.', 'https://static01.nyt.com/images/2023/11/14/multimedia/JM-Bulgogi-qmfj/JM-Bulgogi-qmfj-superJumbo.jpg', 65000, 9),
(42, 'Bibimbap', 'A vibrant bowl of rice topped with assorted vegetables, a fried egg, and a choice of bulgogi or tofu, served with spicy gochujang sauce.', 'https://i.natgeofe.com/n/bd2a845a-12eb-41fa-9ada-80b14d824913/deconstruct_Recipe_shot_HR.jpg', 55000, 9),
(43, 'Jjajangmyeon', 'Chinese-style Korean black bean noodles with a rich and savory black bean sauce.', 'https://www.maangchi.com/wp-content/uploads/2007/07/jjajangmyeon-plate.jpg', 45000, 9),
(44, 'Pad Thai', 'Stir-fried rice noodles with shrimp, tofu, bean sprouts, peanuts, and tamarind sauce.', 'https://hot-thai-kitchen.com/wp-content/uploads/2021/10/Untitled-design-5.jpg', 45000, 10),
(45, 'Green Curry', 'Aromatic green curry with chicken, eggplant, bamboo shoots, and Thai basil in coconut milk.', 'https://www.foodandwine.com/thmb/CYwqpa65DfTatFI2Zmz8WTddjaI=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/Green-Curry-FT-RECIPE0523-6efaa826a9f4406cb8fbcb445667ceda.jpg', 55000, 10),
(46, 'Mango Sticky Rice', 'Sweet sticky rice topped with ripe mango slices and drizzled with coconut milk.', 'https://images.tokopedia.net/img/KRMmCm/2023/10/3/28cac63e-e520-484a-bb6b-bcb6cfe35f8e.jpg', 30000, 10);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `name`) VALUES
(1, 'Credit Card'),
(2, 'PayPal');

-- --------------------------------------------------------

--
-- Table structure for table `stall`
--

CREATE TABLE `stall` (
  `stall_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(500) NOT NULL,
  `image` varchar(500) NOT NULL,
  `kantin_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stall`
--

INSERT INTO `stall` (`stall_id`, `name`, `description`, `image`, `kantin_id`, `owner_id`) VALUES
(1, 'Tokyo Mirage', 'Japanese restaurant offering a delightful fusion of Tokyo\'s flavors. Enjoy carefully crafted sushi, flavorful miso-glazed cod, and inventive dishes for a unique dining experience.', 'https://d267qvt8mf7rfa.cloudfront.net/restaurant/128/mainImage.jpg', 1, 9),
(2, 'Golden Wok', 'Captivating Chinese eatery that brings the rich culinary heritage of China to life. Indulge in expertly crafted dim sum, savory Peking duck, and an array of authentic delicacies.', 'https://images.squarespace-cdn.com/content/v1/596fd9989f7456d6bbc11ad1/1582582081228-P0QCXXEM7P606OE2XIBO/The+Formosa+Cafe+1.jpg', 1, 9),
(3, 'Bellissimo Bistro', 'An enchanting Italian haven, beckons with a symphony of flavors. Savor the essence of Italy with delectable pizzas, exquisite pasta, and a delightful array of authentic Italian delights.', 'https://static.thehoneycombers.com/wp-content/uploads/sites/2/2022/12/italian-restaurants-singapore-kamranaydinov-freepik-900x642.jpg', 1, 9),
(4, 'Warung Kuning', 'Savor authentic Indonesian flavors, featuring traditional delights like rendang and nasi goreng. Embracing heritage in a cozy setting, it\'s a golden haven for those seeking culinary nostalgia.\r\n\r\n\r\n\r\n\r\n\r\n', 'https://www.indonesia.travel/content/dam/indtravelrevamp/en/trip-ideas/missing-the-delicious-flavours-of-indonesia-check-out-these-places/warungsiska.jpg', 2, 11),
(5, 'Brasserie du Château', 'An enchanting French restaurant. Impeccable service complements a menu featuring classic French delicacies with a modern twist. From escargot to delicate pastries, every dish is a symphony of flavor, inviting you to savor the essence of French gastronomy.\r\n\r\n\r\n\r\n\r\n\r\n', 'https://www.japantimes.co.jp/uploads/imported_images/uploads/2020/02/p2-spotlight-hotels-a-20200228.jpg', 2, 11),
(6, 'KFC', 'A globally renowned fast-food chain celebrated for its iconic crispy fried chicken. Offering a diverse menu, KFC is known for its savory flavors and distinctive dining experience.', 'https://www.foodmanufacture.co.uk/var/wrbm_gb_food_pharma/storage/images/1/5/5/1/1091551-1-eng-GB/KFC-prepares-for-growth-with-40M-investment.jpg', 4, 15),
(7, 'Carl\'s Jr.', 'A popular fast-food chain recognized for its bold, charbroiled burgers, hand-breaded chicken tenders, and indulgent milkshakes. Known for quality ingredients, Carl\'s Jr. delivers a satisfying dining experience.', 'https://logos-world.net/wp-content/uploads/2022/11/Carls-Jr.-Symbol.png', 4, 15),
(8, 'Beard Papa\'s', 'A renowned dessert franchise globally recognized for its indulgent cream puffs. Known for their crispy shells and rich fillings, Beard Papa\'s offers a delightful and unique pastry experience.', 'https://asset.kompas.com/crops/3HurX_sCDnq6Ymz3ICHkIFaOYq8=/0x0:866x577/750x500/data/photo/2022/09/05/6315d56e12180.png', 4, 15),
(9, 'Hanwoori House', 'Korean culinary haven, invites you to savor the artful fusion of traditional flavors and contemporary flair. Immerse in a cozy ambiance reminiscent of a hanok, where each dish, from Bulgogi to Bibimbap, tells a story of authentic Korean gastronomy.', 'https://seattletravel.com/wp-content/uploads/2022/12/KoreanFoodHeader.jpg', 3, 14),
(10, 'Thai Terrace', 'Where the vibrant flavors of Thailand meet the serene ambiance of a tranquil terrace. Indulge in authentic Thai cuisine crafted with precision and care, featuring classic dishes like Pad Thai, Green Curry, and Mango Sticky Rice. Immerse yourself in the enchanting Thai culinary experience at Thai Terrace.', 'https://secretnyc.co/wp-content/uploads/2023/02/AF_SOOTHR_22.jpeg', 3, 14);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `total` int(50) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `total`, `date`, `status`, `payment_id`, `user_id`, `stall_id`) VALUES
(8, 3520000, '2023-12-28', 'Order Finished', 1, 1, 2),
(10, 45000, '2023-12-30', 'Order Finished', 1, 1, 1),
(11, 50000, '2023-12-30', 'Order Finished', 1, 1, 3),
(12, 110000, '2023-12-30', 'Order Placed', 1, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_detail`
--

CREATE TABLE `transaction_detail` (
  `transaction_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(500) NOT NULL,
  `subtotal` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_detail`
--

INSERT INTO `transaction_detail` (`transaction_id`, `menu_id`, `quantity`, `note`, `subtotal`) VALUES
(8, 1, 22, '', 3300000),
(8, 2, 2, '', 160000),
(8, 3, 1, '', 60000),
(10, 30, 1, '', 45000),
(11, 34, 1, '', 50000),
(12, 45, 2, '', 110000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `pass`) VALUES
(1, 'admin', '123'),
(9, 'owner1', '123'),
(11, 'owner2', '123'),
(14, 'owner3', '123'),
(15, 'owner4', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`user_id`,`menu_id`);

--
-- Indexes for table `kantin`
--
ALTER TABLE `kantin`
  ADD PRIMARY KEY (`kantin_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `fk_stallid` (`stall_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `stall`
--
ALTER TABLE `stall`
  ADD PRIMARY KEY (`stall_id`),
  ADD KEY `fk_kantinid` (`kantin_id`),
  ADD KEY `fk_ownerid` (`owner_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_paymentid` (`payment_id`),
  ADD KEY `fk_userid` (`user_id`);

--
-- Indexes for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD PRIMARY KEY (`transaction_id`,`menu_id`),
  ADD KEY `fk_menuid` (`menu_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kantin`
--
ALTER TABLE `kantin`
  MODIFY `kantin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stall`
--
ALTER TABLE `stall`
  MODIFY `stall_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_stallid` FOREIGN KEY (`stall_id`) REFERENCES `stall` (`stall_id`);

--
-- Constraints for table `stall`
--
ALTER TABLE `stall`
  ADD CONSTRAINT `fk_kantinid` FOREIGN KEY (`kantin_id`) REFERENCES `kantin` (`kantin_id`),
  ADD CONSTRAINT `fk_ownerid` FOREIGN KEY (`owner_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_paymentid` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`),
  ADD CONSTRAINT `fk_userid` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD CONSTRAINT `fk_menuid` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`),
  ADD CONSTRAINT `fk_transactionid` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
