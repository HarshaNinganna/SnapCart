-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 04:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snapcart`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(9, 2, 7, 1, '2024-09-23 10:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `discount` decimal(5,2) NOT NULL CHECK (`discount` >= 0 and `discount` <= 100),
  `valid_until` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_status` enum('pending','completed','canceled') DEFAULT 'pending',
  `placed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `order_status`, `placed_at`, `order_date`, `paid_amount`, `payment_status`, `payment_method`) VALUES
(11, 4, 89990.00, 'completed', '2024-09-23 11:16:48', '2024-09-23 11:16:48', 89.00, 'pending', 'UPI'),
(12, 4, 89990.00, 'canceled', '2024-09-23 11:20:55', '2024-09-23 11:20:55', 0.00, 'pending', 'unknown'),
(13, 4, 180.00, 'completed', '2024-09-23 11:51:06', '2024-09-23 11:51:06', 180.00, 'pending', 'unknown'),
(14, 4, 79990.00, 'pending', '2024-09-26 13:26:38', '2024-09-26 13:26:38', 0.00, 'pending', 'Online Payment'),
(15, 4, 79990.00, 'pending', '2024-09-26 13:27:12', '2024-09-26 13:27:12', 0.00, 'pending', 'COD'),
(16, 4, 79990.00, 'pending', '2024-09-26 13:31:26', '2024-09-26 13:31:26', 0.00, 'pending', 'COD'),
(17, 4, 0.00, 'pending', '2024-09-26 13:33:52', '2024-09-26 13:33:52', 0.00, 'pending', 'COD'),
(18, 4, 52999.00, 'pending', '2024-09-26 13:35:01', '2024-09-26 13:35:01', 0.00, 'pending', 'COD'),
(19, 4, 106800.00, 'canceled', '2024-09-26 13:36:34', '2024-09-26 13:36:34', 0.00, 'pending', 'COD'),
(20, 4, 79990.00, 'completed', '2024-09-26 13:44:41', '2024-09-26 13:44:41', 79.00, 'pending', 'UPI'),
(21, 4, 79990.00, 'completed', '2024-09-26 14:21:18', '2024-09-26 14:21:18', 79.00, 'pending', 'UPI'),
(22, 4, 1399.00, 'pending', '2024-09-26 14:22:49', '2024-09-26 14:22:49', 0.00, 'pending', 'Online'),
(23, 4, 52999.00, 'pending', '2024-09-26 18:09:44', '2024-09-26 18:09:44', 0.00, 'pending', 'COD');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(11, 13, 8, 1, 180.00),
(12, 14, 67, 1, 79990.00),
(13, 15, 67, 1, 79990.00),
(14, 16, 67, 1, 79990.00),
(15, 18, 68, 1, 52999.00),
(16, 19, 65, 1, 106800.00),
(17, 20, 67, 1, 79990.00),
(18, 21, 67, 1, 79990.00),
(19, 22, 29, 1, 1399.00),
(20, 23, 68, 1, 52999.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `vendor_id`, `name`, `description`, `price`, `image`, `category`, `status`) VALUES
(6, 2, 'Raymond Suites', 'Raymond Suites', 7589.00, 'uploads/suits.jpg', 'Clothing', 'active'),
(7, 3, 'Mixer Grinder', 'Sansui Plus 500 W Juicer Mixer Grinder  (Allure Plus | 3 Jars', 1199.00, 'uploads/mixer.jpg', 'Home Appliances', 'active'),
(8, 4, 'Book', 'Naanendigu Soluvudilla, Sotaradu Naanalla!  (Paperback, Rangaswamy Mookanahalli)', 180.00, 'uploads/book1.jpg', 'Books', 'active'),
(9, 5, 'Cricket Kit', 'Strauss Cricket Kit | Full Size | Color: Blue | Right Handed Complete Set of 9 Cricket Kit', 3999.00, 'uploads/sports_cricket.jpg', 'Sports', 'active'),
(10, 6, 'Toy Car', 'CADDLE & TOES Famous Car Remote Control 3D with LED Lights, Chargeable  (Black)', 444.00, 'uploads/toys1.jpg', 'Toys', 'active'),
(14, 6, 'Jumping Dog', 'BELOXY Jumping, Walking and Barking Dog Soft Toy Fantastic Puppy Battery Operated Back Flip Jumping Dog Jump Run Toy Kid (Jumping Dog)', 759.00, 'uploads/toys2.jpg', 'Toys', 'active'),
(15, 6, 'Bubbles Gun', 'GRAPHENE 32 Hole Electric Gatling Bubble machine Gun for Kids with Soap Solution Indoor and Outdoor Toys for Toddlers Bubble Launcher Machine for Girls and Boys (Color as per Stock)', 249.00, 'uploads/toys3.jpg', 'Toys', 'active'),
(16, 6, 'Multi-colors toys car', 'Galaxy Hi-Tech Mini Metal Die Cast Car Set Of-6 Toy Vehicle Play Set Free Wheel High Speed Unbreakable For Kids,Small Racing Car For Exciting Playtime Adventures,Movie Vehicle Car For Kids,Multicolor', 398.00, 'uploads/toys4.jpg', 'Toys', 'active'),
(17, 6, 'Remote Control Helicopter ', 'ToyMagic Remote Control Helicopter with Hand Gravity Sensor USB Charging Helicopter Toy| 3D Light & Safety Sensor for Kids Age 4+ Years Indoor and Outdoor Sport Toy', 479.00, 'uploads/toys5.jpg', 'Toys', 'active'),
(18, 6, 'Projector Torch for Kids ', 'Toy Imagine Projector Torch for Kids Creativity Toy Children Flashlight Projection Light Cognitive Animals Marine 1 Electric Torch with 3 Slides (24 Pictures)', 99.00, 'uploads/toys6.jpg', 'Toys', 'active'),
(19, 6, 'Toy Laptop', 'Cable World® Educational Laptop Computer Toy with Mouse for Kids Above 3 Years - 20 Fun Activity Learning Machine, Now Learn Letter, Words, Games, Mathematics, Music, Logic, Memory Tool - Blue', 999.00, 'uploads/toya7.jpg', 'Toys', 'active'),
(20, 6, 'Toy JCB for kids', 'Brand Conquer Plastic Construction Realistic Engineer Vehicle Pushdozer Excavator Bulldozer Construction Toys Truck Machine for Kids Yellow (Excavator)', 499.00, 'uploads/toys8.jpg', 'Toys', 'active'),
(21, 6, 'Octopus', 'Babique Octopus Sitting Plush Soft Toy Cute Kids Animal Home Decor Boys/Girls (17 cm)', 129.00, 'uploads/toys9.jpg', 'Toys', 'active'),
(22, 6, 'Saregama Speaker&mic ', 'Saregama Carvaan Mini Kids with Wireless Mic - 300+ Pre-Loaded Stories, Rhymes, Learnings and Mantras with Rechargeable Battery/Bluetooth/USB/Aux in-Out/Play in Loop - Baby Yellow', 3990.00, 'uploads/toys10.jpg', 'Toys', 'active'),
(23, 6, 'Dancing Cactus Toy', 'Storio Rechargeable Toys Talking Cactus Baby Toys for Kids Dancing Cactus Toys Can Sing Wriggle & Singing Recording Repeat What You Say Funny Education Toys for Children Playing Home Decor for Kids', 326.00, 'uploads/toy11.jpg', 'Toys', 'active'),
(24, 5, 'Football', 'KT Sports Black and White Football with Pump Full Size- 5 for Kids and Adult Rubber Football, Size 5, (Black, White)', 390.00, 'uploads/sports2.jpg', 'Sports', 'active'),
(25, 5, 'Argentina Football Jersey', 'Argentina Football Jersey 2024 Messii (Kids,Boys,Men)', 339.00, 'uploads/sports3.jpg', 'Sports', 'active'),
(26, 5, 'Cricket Bat(fiber)', 'Boldfit Cricket bat Full Size Plastic bat Tennis Cricket bat Turf Tennis bat Lightweight Fiber bat Hard Plastic bat Tournament Plastic Cricket bat Standard Size Cricket Bats for Adults Fiber bat', 359.00, 'uploads/sports4.jpg', 'Sports', 'active'),
(27, 5, 'Basket Ball', 'Boldfit Cricket bat Full Size Plastic bat Tennis Cricket bat Turf Tennis bat Lightweight Fiber bat Hard Plastic bat Tournament Plastic Cricket bat Standard Size Cricket Bats for Adults Fiber bat', 475.00, 'uploads/sports5.jpg', 'Sports', 'active'),
(28, 5, 'RCB Jersey', 'Boldfit Cricket bat Full Size Plastic bat Tennis Cricket bat Turf Tennis bat Lightweight Fiber bat Hard Plastic bat Tournament Plastic Cricket bat Standard Size Cricket Bats for Adults Fiber bat', 329.00, 'uploads/sports6.jpg', 'Sports', 'active'),
(29, 5, 'Magnetic Chess Board', 'LONGMIRE Magnetic Educational Toys Travel Chess Set with Folding Chess Board Prefect Gift for Kids and Adults Multicolor (Black)', 1399.00, 'uploads/sports7.jpg', 'Sports', 'active'),
(30, 5, 'Metal Dart Board', 'LONGMIRE Magnetic Educational Toys Travel Chess Set with Folding Chess Board Prefect Gift for Kids and Adults Multicolor (Black)', 1529.00, 'uploads/sports8.jpg', 'Sports', 'active'),
(31, 5, 'Volley Ball', 'Senston Volleyball Official Size 5 - Waterproof Indoor/Outdoor Soft Volleyball for Kids Youth Adults,Beach Play, Game,Gym,Training', 1529.00, 'uploads/sports9.jpg', 'Sports', 'active'),
(32, 5, 'Volley BallNet', 'Azure Sports Elite Cotton Volleyball Nets: Unmatched Quality for Professional Play', 729.00, 'uploads/sports10.jpg', 'Sports', 'active'),
(33, 5, 'Badminton Set', 'Hipkoo Sports Entire Aluminum Badminton Rackets Set of 2| Wide Body Shuttle Bat with Cover, 10 Shuttles and Net| Ideal for Beginner| Lightweight and Sturdy', 739.00, 'uploads/sports12.jpg', 'Sports', 'active'),
(34, 4, 'Bhagavad Gita', 'Bhagavad Gita As It Is (Kannada)', 165.00, 'uploads/book2.jpg', 'Books', 'active'),
(35, 4, 'Hanada Manovijnana ', 'Hanada Manovijnana (The Psychology of Money)', 195.00, 'uploads/book3.jpg', 'Books', 'active'),
(36, 4, 'Mookajjiya Kanasugalu', 'Mookajjiya Kanasugalu: Gnyaanapeeta Prashasthi Puraskrutha Kaadambar', 175.00, 'uploads/book4.jpg', 'Books', 'active'),
(37, 4, 'Ikigai ', 'Ikigai - The Japanese Secret to a Long and Happy Life', 141.00, 'uploads/book5.jpg', 'Books', 'active'),
(38, 4, 'Manku Thimmana kagga', 'Manku Thimmana kagga-by D.V.G', 168.00, 'uploads/book6.jpg', 'Books', 'active'),
(39, 4, 'Jugari Cross ', 'Jugari Cross By-K. Poornachandra Tejaswi', 246.00, 'uploads/book7.jpg', 'Books', 'active'),
(40, 4, 'Kathe Dabbi', 'Kathe Dabbi By - Rajini', 199.00, 'uploads/book8.jpg', 'Books', 'active'),
(41, 4, ' Rich Dad Poor Dad', 'Manjul Publishing House Rich Dad Poor Dad', 299.00, 'uploads/book9.jpg', 'Books', 'active'),
(42, 4, 'Mooru Hennu Aidu Jade', 'Mooru Hennu Aidu Jade- BiChi', 179.00, 'uploads/book10.jpg', 'Books', 'active'),
(43, 4, 'Kallu Karaguva Samaya matthu Ithara Kathegalu', 'Kallu Karaguva Samaya matthu Ithara Kathegalu - P.Lankesh', 125.00, 'uploads/book11.jpg', 'Books', 'active'),
(44, 3, 'Milton HotBox', 'MILTON Ernesto Inner Stainless Steel Jr. Casserole Set of 3 (420 ml, 850 ml, 1.43 litres), Grey | Easy to Carry | Serving | Stackable', 1051.00, 'uploads/home1.jpg', 'Home Appliances', 'active'),
(45, 3, 'Wooden Pooja Stand', 'Heartily® Mangal Beautiful Wooden Pooja Stand for Home Pooja Mandir for Home Temple for Home and Office Puja Mandir for Home Wall Mounted with LED Spot Light Size (H- 15.5, L- 11.5, W-11 Inch)', 1199.00, 'uploads/home2.jpg', 'Home Appliances', 'active'),
(46, 3, 'Oven', 'AGARO Marvel 9L Oven Toaster Griller, Cake Baking, Grilling, Toasting, OTG, 800 Watts, (Black).', 1818.00, 'uploads/home3.jpg', 'Home Appliances', 'active'),
(47, 3, ' Wall Mount Wooden Wall Shelf', 'Dime Store Wall Decor Wall Shelves for Home Decor Items, Living Room and Bedroom | Wall Mount Wooden Wall Shelf', 424.00, 'uploads/home4.jpg', 'Home Appliances', 'active'),
(48, 3, 'Fragrance Diffuser', 'Bare Elixir Humidifier For Room Moisture, Aroma Diffuser For Home Fragrance, Essential Oil Diffuser Electric, Fragrance Diffuser For Home Office And Car, Ultrasonic Air Humidifier, 300 Ml', 499.00, 'uploads/home5.jpg', 'Home Appliances', 'active'),
(49, 3, 'Storage Cabinet with Mirror', 'Happer Plastic Premium Multipurpose Wall Mounted Storage Cabinet with Mirror, Prime Look (White)', 1089.00, 'uploads/home6.jpg', 'Home Appliances', 'active'),
(50, 3, 'Home Theaters', 'GOVO GOSURROUND 945 | 120W Sound bar, 5.1 Channel Home Theatre with Mega subwoofer, Dual Rear Satellites, AUX, USB & Bluetooth, 3 Equalizer Modes, Stylish Remote & LED Display (Platinum Black)', 4949.00, 'uploads/home7.jpg', 'Home Appliances', 'active'),
(51, 3, 'Air Fryer', 'Pigeon Healthifry Digital Air Fryer, 360° High Speed Air Circulation Technology 1200 W with Non-Stick 4.2 L Basket - Green', 2718.00, 'uploads/home8.jpg', 'Home Appliances', 'active'),
(52, 3, 'Electric Toaster', 'MILTON Express 800 Watt Grill Sandwich Maker | Electric Toaster Griller Sandwich Maker | Non Stick Coating Grill Plates | Power Indicators | 1 Year Warranty | Black', 1266.00, 'uploads/home9.jpg', 'Home Appliances', 'active'),
(53, 3, 'Egg Boiler', 'Wipro Vesta Electric Egg Boiler, 360 Watts, 3 Boiling Modes, Stainless Steel Body and Heating Plate, Boils up to 7 Eggs at a time, Automatic Shut Down, White, Standard (VB021070)', 1135.00, 'uploads/home10.jpg', 'Home Appliances', 'active'),
(54, 2, 'BULLMER Trendy Clothing Set with Shirt & Pants Co-ords for Men', 'Product details\r\nMaterial type : Cotton Blend\r\n\r\nFit typeRegular\r\nStyle: Mens Co-ords\r\n\r\nClosure type: Button\r\n\r\nCare instructions: Hand Wash Only\r\n\r\nAge range description: Adult\r\n\r\nCountry of Origin: India\r\n', 899.00, 'uploads/cloth1.jpg', 'Clothing', 'active'),
(55, 2, 'Lymio Casual Shirt for Men|| Shirt for Men|| Men Stylish Shirt (Rib-Shirt)', 'Product details:   \r\n\r\n\r\nMaterial composition : Polyester\r\n\r\nFit type: Regular Fit\r\n\r\nSleeve type: Long Sleeve\r\n\r\nCollar style: Regular Collar\r\n\r\nLength: Standard Length\r\n\r\nNeck style: Dom\r\n\r\nCountry of Origin: India\r\n', 379.00, 'uploads/cloth2.jpg', 'Clothing', 'active'),
(56, 2, 'Wedani Women Fashion Vest', 'Product details: \r\nMaterial composition: Georgette\r\nLength: Ankle Length\r\nSleeve typeLong Sleeve\r\nNeck style: Round Neck\r\nStyle: Anarkali\r\nMaterial type: Georgette\r\nCountry of Origin: India', 810.00, 'uploads/cloth4.jpg', 'Clothing', 'active'),
(57, 2, 'Leriya Fashion Tops for Women | Crop Tops for Women | Summer Tops for Women | Floral Tops for Women', 'Product details:\r\n\r\nMaterial composition: Rayon\r\n\r\nFit type: Regular Fit\r\nSleeve type: Long Sleeve\r\nNeck style: Collared Neck\r\nStyleWestern\r\nOccasion type: Date Night, Christmas, Birthday, Thanksgiving, Memorial Day\r\nCountry of Origin: India', 429.00, 'uploads/cloth5.jpg', 'Clothing', 'active'),
(58, 2, 'NexaFlair Casual Shirt for Men||Popcorn Shirt for Men||Spread Collar|| Men Stylish Shirt', 'Product details:\r\nMaterial composition: Popcorn\r\nPattern: Solid\r\nFit type: Regular Fit\r\nSleeve typeSort\r\nCollar style: Spread Collar\r\nLength: Standard Length\r\nCountry of Origin: India', 407.00, 'uploads/cloth6.jpg', 'Clothing', 'active'),
(59, 2, 'Saree', 'Product details\r\nMaterial compositionCotton linen\r\nWeave typeLakhanavi\r\nDesign nameWoven\r\nLength6 yards\r\nOccasion typeCasual, Evening, Work, Ceremony, Festival\r\nPatternFloral\r\nCountry of OriginIndia', 859.00, 'uploads/cloth7.jpg', 'Clothing', 'active'),
(61, 2, 'KOTTY Women Polyester Blend Solid Trousers', 'Product details: \r\n\r\nMaterial type: Polyester Blend\r\nLengthFull length\r\nStyle: Casual trousers\r\nClosure type: Button\r\nOccasion type: Casual\r\nCare instructions: Machine Wash\r\nCountry of Origin: India', 259.00, 'uploads/cloth8.jpg', 'Clothing', 'active'),
(62, 2, 'straight kurta pack of 6', 'Material composition: American Crepe\r\nLength: Knee Length\r\nSleeve type: 3/4 Sleeve\r\nNeck style: Round Neck\r\nStyle: Straight\r\nMaterial type: Crepe\r\nCountry of Origin: India', 849.00, 'uploads/cloth9.jpg', 'Clothing', 'active'),
(63, 2, 'Sleeves Printed Sweatshirt and Pant Set in Multi Color', 'Product details: \r\nMaterial composition: 90% Cotton\r\nPattern: All Over Print\r\nClosure type: Pull On\r\nCare instructions: Machine Wash\r\nCountry of Origin: India', 499.00, 'uploads/cloth10.jpg', 'Clothing', 'active'),
(64, 2, 'Cotton Blend Casual Printed Short Sleeves Long Kurta and Pallazzo Set for Girls Kids', 'Product details: \r\nMaterial type: Rayon\r\nFit type: Regular\r\nStyleCasual\r\nClosure type: Zipper\r\nCare instructions: Hand Wash Only\r\nAge range description: Kid\r\nCountry of Origin: India', 468.00, 'uploads/cloth11.jpg', 'Clothing', 'active'),
(65, 1, 'Samsung Galaxy S23 Ultra 5G AI Smartphone (Green, 12GB, 256GB Storage)', 'Brand	Samsung\r\nOperating System	Android\r\nRAM Memory Installed Size	12 GB\r\nCPU Model	Snapdragon\r\nCPU Speed	2.99 GHz', 106800.00, 'uploads/phone1.jpg', 'Electronics', 'active'),
(66, 1, 'Apple iPhone 16 (256 GB)', 'Brand	Apple\r\nOperating System	iOS 17\r\nRAM Memory Installed Size	256 GB\r\nMemory Storage Capacity	256 GB\r\nScreen Size	6.1 Inches', 89900.00, 'uploads/iphone16.jpg', 'Electronics', 'active'),
(67, 1, 'Apple iPhone 15 (256 GB) - Blue', 'Brand	Apple\r\nOperating System	iOS\r\nMemory Storage Capacity	256 GB\r\nScreen Size	6.1 Inches\r\nModel Name	iPhone 15', 79990.00, 'uploads/iphone15.jpg', 'Electronics', 'active'),
(68, 1, 'Apple iPhone 13 (256GB) - Starlight', '\r\nBrand	Apple\r\nOperating System	iOS 14\r\nCPU Speed	3.2 GHz\r\nMemory Storage Capacity	256 GB\r\nScreen Size	6.1 Inches', 52999.00, 'uploads/iphone13.jpg', 'Electronics', 'active'),
(69, 1, 'oppo F25 Pro 5G (Ocean Blue, 8GB RAM, 128GB Storage) Without Offers', 'Brand	Oppo\r\nOperating System	Android 14\r\nRAM Memory Installed Size	8 GB\r\nCPU Model	Snapdragon\r\nCPU Speed	2.4 GHz', 21970.00, 'uploads/oppo.jpg', 'Electronics', 'active'),
(70, 1, 'Vivo V40 5G Smartphone (Lotus Purple, 8GB RAM, 256GB Storage)', 'Brand	Vivo\r\nItem dimensions L x W x H	7.5 x 0.8 x 16.4 Centimeters\r\nCompatible Devices	Smartphone', 37850.00, 'uploads/vivo.jpg', 'Electronics', 'active'),
(71, 1, 'Redmi 13 5G, Orchid Pink, 8GB+128GB | India Debut SD 4 Gen 2 AE | 108MP Pro Grade Camera | 6.79in Largest Display in Segment', 'Brand	Redmi\r\nOperating System	Android 14, Xiaomi HyperOS\r\nRAM Memory Installed Size	8 GB\r\nCPU Model	Snapdragon\r\nCPU Speed	2.3 GHz', 14999.00, 'uploads/redmi.jpg', 'Electronics', 'active'),
(73, 1, 'realme NARZO 70 Turbo 5G (Turbo Yellow,6GB RAM,128GB Storage)', 'realme NARZO 70 Turbo 5G (Turbo Yellow,6GB RAM,128GB Storage) | Segment&#39;s Fastest Dimensity 7300 Energy 5G Chipset | Motorsports Inspired Design\r\nRoll over image to zoom in\r\nrealme NARZO 70 Turbo 5G (Turbo Yellow,6GB RAM,128GB Storage)', 16998.00, 'uploads/realme.jpg', 'Electronics', 'active'),
(74, 1, 'Samsung 80 cm (32 inches) HD Ready Smart LED TV UA32T4380AKXXL (Glossy Black)', 'Screen Size	32 Inches\r\nBrand	Samsung\r\nDisplay Technology	LED\r\nResolution	768p\r\nRefresh Rate	60 Hz\r\nSpecial Feature	Screen Share | Music System | Content Guide | Connect Share Movie | Supported Apps : Netflix, Youtube, Prime Video, Hotstar, SonyLiv, Hungama, JioCinema, Zee5, Eros Now, Oxygen PlayScreen Share | Music System | Content Guide | Connect Share Movie | Supported Apps : Netflix, Youtube, Prime Video, Hotstar, SonyLiv, Hungama, JioCinema, Zee5, Eros Now, Oxygen Play\r\nIncluded Components	‎1 LED TV, 1 User Manual, 1 Warranty Card, 1 Remote Control, 2 AAA Batteries, Wall Mount / Table Top will be provided at the time of installation based on customer preferences.‎1 LED TV, 1 User Manual, 1 Warranty Card, 1 Remote Control, 2 AAA Batteries, Wall Mount / Table Top will be provided at the time of installation based on customer preferences.\r\nConnectivity Technology	Wi-Fi, USB, Ethernet, HDMI\r\nProduct Dimensions	8.6D x 72.3W x 72.3H Centimeters\r\nSupported Internet Services	Netflix, Prime Video, Zee5, Oxygen Play, Eros Now, JioCinema, SonyLiv, Youtube, Hungama, Hotstar', 10999.00, 'uploads/tv.jpg', 'Electronics', 'active'),
(75, 1, 'Sony Bravia 164 cm (65 inches) 4K Ultra HD Smart LED Google TV KD-65X74L (Black)', 'Screen Size	65 Inches\r\nBrand	Sony\r\nDisplay Technology	LED\r\nResolution	4K\r\nRefresh Rate	60 Hz\r\nSpecial Feature	Google TV, Watchlist, Voice Search, Google Play, Chromecast, Netflix, Amazon Prime Video, Additional Features: Apple Airplay, Apple Homekit, AlexaGoogle TV, Watchlist, Voice Search, Google Play, Chromecast, Netflix, Amazon Prime Video, Additional Features: Apple Airplay, Apple Homekit, Alexa\r\nIncluded Components	1 LED TV, 1 AC Power Cord, 1 Remote Control, 1 Table-Top Stand, 1 User Manual, 2 AAA Batteries\r\nConnectivity Technology	Wi-Fi, USB, Ethernet, HDMI\r\nAspect Ratio	16:9\r\nProduct Dimensions	8.7D x 146.3W x 85.2H Centimeters', 73990.00, 'uploads/tv1.jpg', 'Electronics', 'active'),
(76, 1, 'Fastrack New Limitless X2 Smartwatch', 'Fastrack New Limitless X2 Smartwatch|1.91\" UltraVU with Rotating Crown|60 Hz Refresh Rate|Advanced Chipset|SingleSync BT Calling|NitroFast Charge|100+ Sports Mode & Watchfaces|Upto 5 Day Battery|IP68', 1299.00, 'uploads/watch.jpg', 'Electronics', 'active'),
(77, 1, 'boAt Wave Lite Smart Watch', 'boAt Wave Lite Smart Watch w/ 1.69\" (4.2 cm) HD Display, Sleek Metal Body, HR & SpO2 Level Monitor, 140+ Watch Faces, Activity Tracker, Multiple Sports Modes, IP68 & 7 Days Battery Life(Active Black)', 1799.00, 'uploads/watch2.jpg', 'Electronics', 'active'),
(78, 1, 'JBL Live Pro 2 Premium in Ear Wireless TWS Earbud', 'JBL Live Pro 2 Premium in Ear Wireless TWS Earbuds, ANC Earbuds, 40Hr Playtime, Dual Connect, Customized Bass with Headphones App, 6 Mics for Clear Calls, Wireless Charging, Alexa Built-in (Black)', 6999.00, 'uploads/earbuds.jpg', 'Electronics', 'active'),
(79, 1, 'OnePlus Nord Buds 2r True Wireless in Ear Earbuds with Mic', 'OnePlus Nord Buds 2r True Wireless in Ear Earbuds with Mic, 12.4mm Drivers, Playback:Upto 38hr case,4-Mic Design, IP55 Rating [ Misty Grey ]', 1599.00, 'uploads/earbuds1.jpg', 'Electronics', 'active'),
(80, 1, 'boAt Rockerz 450 Bluetooth On Ear Headphones with Mi', 'boAt Rockerz 450 Bluetooth On Ear Headphones with Mic, Upto 15 Hours Playback, 40MM Drivers, Padded Ear Cushions, Integrated Controls and Dual Modes(Luscious Black)', 1299.00, 'uploads/headphones.jpg', 'Electronics', 'active'),
(81, 1, 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic, Upto 70 Hrs Playtime, Speedcharge, Google Fast Pair, Dual Pairing, BT 5.3 LE Audio, Customize on Headphones App (Black)', 5449.00, 'uploads/headphones1.jpg', 'Electronics', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `area_code` varchar(10) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `mobile_number`, `gender`, `dob`, `address1`, `address2`, `city`, `state`, `country`, `area_code`, `profile_image`, `email`, `password`, `role`) VALUES
(1, '', '', 'harsha_2001', '', '', '0000-00-00', '', '', '', '', '', '', 'uploads/harsha1.jpg', '', '$2y$10$r3jvc29xSFukwOui08GWUu8l7aoAIKqZ.bzT38Ho1SkKwd5kcizi6', 'client'),
(2, 'gopal', 'k', NULL, '9686147404', 'Male', '2010-11-30', '34, 14/2 Naganathapura', 'Electronic city post', 'BENGALURU', 'KARNATAKA', NULL, '560100', 'uploads/Screenshot (2).png', 'gopalk@gmail.com', '$2y$10$yiUWdzwNTfWirUN8IGdvDOj756bqQ9aqUYhdtFzTQ8i3wPbcCyDKi', 'customer'),
(3, 'sudeep', 'r', 'sudeep.r', '9856123144', 'Male', '2024-09-03', 'maskal', 'maskal road ', 'chitradurga', 'karnataka', NULL, '560147', 'uploads/tvs.jpg', 'sudeep45@gmail.com', '$2y$10$ghh.c6ssv/4hoOyJUcY0e.3dtjrT1fvkcSHU7UtpvwezRSkupjWYS', 'customer'),
(4, 'Harsha', 'N', 'harsha+2001', '08867132911', 'Male', '2001-12-31', '34, 14/2 Naganathapura', 'Electronic city post', 'BENGALURU', 'KARNATAKA', NULL, '560100', 'uploads/harsha1.jpg', 'hv6152239@gmail.com', '$2y$10$ydYMIsyYJInX4zH.1BHTjOfdLwzh8P286o4Xf3IeckptHr7E0mHja', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `license_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `license_no`, `email`, `mobile_no`, `password`, `category`, `created_at`) VALUES
(1, 'Dikshith', '1247/8562', 'dikshithkumarn8@gmail.com', '8867132911', '$2y$10$8xQAColscTY4/GKLR/5pVe/jKADslYXU4ThQFNHKQlHJYMoZxEMMa', 'Electronics', '2024-09-17 08:49:20'),
(2, 'Ninganna', '1245/3625', 'raju8077.rn@gmail.com', '9845186101', '$2y$10$6zFvBzV0sk90kbNImE4AeuzOQ5OoRvslEzDzvvRIA9BHOcGj..2au', 'Clothing', '2024-09-17 10:48:53'),
(3, 'Geetha', '1478/2014', 'hv6152239@gmail.com', '9741978077', '$2y$10$5ISoyx3l/2VgEdZB.3sPre2yaEUwRtJYZ9lIdaWe2aYhRgHVMiZOO', 'Home Appliances', '2024-09-19 08:27:11'),
(4, 'Nisarga', '1278/5241', 'bnisarga1@gmail.com', '7204973796', '$2y$10$ipamr1t7q1bx7ThWTN0KC.duQTh0iLROngfWJvBYudOHyM.4gi3.S', 'Books', '2024-09-19 08:34:10'),
(5, 'Giriswamy', '1354/1547', 'giriswamyl101@gmail.com', '8147290154', '$2y$10$IBQ3Rhme0URPa/6yTCvtwu1w9roA.zecUp64X4SDr2jghunvw7kbe', 'Sports', '2024-09-19 08:39:04'),
(6, 'Prakruthi', '1352/2014', 'drprakruthi10@gmail.com', '7760765711', '$2y$10$k8MPzbW7.qBnpPfAoa52B.6iZeOtSnLbY2Da87R5ZqiKqXucqnRre', 'Toys', '2024-09-19 08:43:05');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_at`) VALUES
(2, 1, 7, '2024-09-20 11:03:32'),
(8, 1, 8, '2024-09-25 12:59:16'),
(9, 4, 7, '2024-09-25 13:22:21'),
(10, 4, 80, '2024-09-26 12:57:10'),
(11, 4, 67, '2024-09-26 13:04:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
