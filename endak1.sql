-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 09:23 PM
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
-- Database: `endak1`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('featured_services', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1757675735),
('latest_services', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1757675435),
('main_categories', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:14:{i:0;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:27;s:4:\"name\";s:17:\"نقل العفش\";s:7:\"name_en\";s:16:\"Furniture Moving\";s:11:\"description\";s:43:\"خدمات نقل العفش والأثاث\";s:14:\"description_ar\";s:43:\"خدمات نقل العفش والأثاث\";s:5:\"image\";s:56:\"departments/JhetHybFBdaTFyMRQ4YZltDJAmpHFWm5fV5mnCCK.png\";s:10:\"sort_order\";i:1;s:4:\"slug\";s:11:\"nkl-alaafsh\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:27;s:4:\"name\";s:17:\"نقل العفش\";s:7:\"name_en\";s:16:\"Furniture Moving\";s:11:\"description\";s:43:\"خدمات نقل العفش والأثاث\";s:14:\"description_ar\";s:43:\"خدمات نقل العفش والأثاث\";s:5:\"image\";s:56:\"departments/JhetHybFBdaTFyMRQ4YZltDJAmpHFWm5fV5mnCCK.png\";s:10:\"sort_order\";i:1;s:4:\"slug\";s:11:\"nkl-alaafsh\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:1;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:30;s:4:\"name\";s:27:\"صيانة السيارات\";s:7:\"name_en\";s:16:\"Cars Maintanence\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/qJ1S1NwB1hgXAT9xuKZdv6jyaRL3awPXpUeE2hbk.png\";s:10:\"sort_order\";i:2;s:4:\"slug\";s:13:\"syan-alsyarat\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:30;s:4:\"name\";s:27:\"صيانة السيارات\";s:7:\"name_en\";s:16:\"Cars Maintanence\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/qJ1S1NwB1hgXAT9xuKZdv6jyaRL3awPXpUeE2hbk.png\";s:10:\"sort_order\";i:2;s:4:\"slug\";s:13:\"syan-alsyarat\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:2;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:31;s:4:\"name\";s:15:\"قطع غيار\";s:7:\"name_en\";s:18:\"Cars`s Spare parts\";s:11:\"description\";s:305:\"The Spare Parts Department is responsible for providing all the components and materials needed for the maintenance and repair of equipment and machinery. Its goal is to ensure the availability of original and replacement parts with high quality and on time to support uninterrupted operational processes.\";s:14:\"description_ar\";s:366:\"قسم قطع الغيار مسؤول عن توفير جميع المكونات والمواد اللازمة لصيانة وإصلاح المعدات والآلات. يهدف إلى ضمان توافر القطع الأصلية والبديلة بجودة عالية وفي الوقت المناسب لدعم العمليات التشغيلية دون انقطاع.\";s:5:\"image\";s:56:\"departments/2h8J6DrD6fHoq8YhiknP4IwPhRcSZQat5T1WUe02.png\";s:10:\"sort_order\";i:3;s:4:\"slug\";s:10:\"ktaa-ghyar\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:31;s:4:\"name\";s:15:\"قطع غيار\";s:7:\"name_en\";s:18:\"Cars`s Spare parts\";s:11:\"description\";s:305:\"The Spare Parts Department is responsible for providing all the components and materials needed for the maintenance and repair of equipment and machinery. Its goal is to ensure the availability of original and replacement parts with high quality and on time to support uninterrupted operational processes.\";s:14:\"description_ar\";s:366:\"قسم قطع الغيار مسؤول عن توفير جميع المكونات والمواد اللازمة لصيانة وإصلاح المعدات والآلات. يهدف إلى ضمان توافر القطع الأصلية والبديلة بجودة عالية وفي الوقت المناسب لدعم العمليات التشغيلية دون انقطاع.\";s:5:\"image\";s:56:\"departments/2h8J6DrD6fHoq8YhiknP4IwPhRcSZQat5T1WUe02.png\";s:10:\"sort_order\";i:3;s:4:\"slug\";s:10:\"ktaa-ghyar\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:3;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:32;s:4:\"name\";s:21:\"معدات ثقيلة\";s:7:\"name_en\";s:17:\"Heavy a Equipment\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/BcFbyOlvFdSTpXydkn6Qk7R8GQk87XNSOfVrfjyt.png\";s:10:\"sort_order\";i:4;s:4:\"slug\";s:12:\"maadat-thkyl\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:32;s:4:\"name\";s:21:\"معدات ثقيلة\";s:7:\"name_en\";s:17:\"Heavy a Equipment\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/BcFbyOlvFdSTpXydkn6Qk7R8GQk87XNSOfVrfjyt.png\";s:10:\"sort_order\";i:4;s:4:\"slug\";s:12:\"maadat-thkyl\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:4;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:33;s:4:\"name\";s:12:\"شاحنات\";s:7:\"name_en\";s:12:\"Heavy Trucks\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/c6noFg0l6DziLgjRlizjRLHwb9XoqNJKTm5wv2EM.png\";s:10:\"sort_order\";i:5;s:4:\"slug\";s:7:\"shahnat\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:33;s:4:\"name\";s:12:\"شاحنات\";s:7:\"name_en\";s:12:\"Heavy Trucks\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/c6noFg0l6DziLgjRlizjRLHwb9XoqNJKTm5wv2EM.png\";s:10:\"sort_order\";i:5;s:4:\"slug\";s:7:\"shahnat\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:5;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:36;s:4:\"name\";s:19:\"صهريج مياه\";s:7:\"name_en\";s:11:\"Water\'s Car\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/bJuDgRoOMdrZFNKJtOlhh7Dz8UTpXbPOeB8FLybz.png\";s:10:\"sort_order\";i:6;s:4:\"slug\";s:10:\"shryg-myah\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:36;s:4:\"name\";s:19:\"صهريج مياه\";s:7:\"name_en\";s:11:\"Water\'s Car\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/bJuDgRoOMdrZFNKJtOlhh7Dz8UTpXbPOeB8FLybz.png\";s:10:\"sort_order\";i:6;s:4:\"slug\";s:10:\"shryg-myah\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:6;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:37;s:4:\"name\";s:8:\"سطحة\";s:7:\"name_en\";s:10:\"Big A cars\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/wF6CCtMQimNZ7qCRrAN5ZFfO5mnY9Qlou5DDUyHk.png\";s:10:\"sort_order\";i:7;s:4:\"slug\";s:3:\"sth\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:37;s:4:\"name\";s:8:\"سطحة\";s:7:\"name_en\";s:10:\"Big A cars\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/wF6CCtMQimNZ7qCRrAN5ZFfO5mnY9Qlou5DDUyHk.png\";s:10:\"sort_order\";i:7;s:4:\"slug\";s:3:\"sth\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:7;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:38;s:4:\"name\";s:26:\"اكلا اسر منتجة\";s:7:\"name_en\";s:11:\"Family food\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/7hl1uE3u7hnQ3xrONdQLNqbHJX9Y1dwFTvBddXgY.png\";s:10:\"sort_order\";i:8;s:4:\"slug\";s:13:\"akla-asr-mntg\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:38;s:4:\"name\";s:26:\"اكلا اسر منتجة\";s:7:\"name_en\";s:11:\"Family food\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/7hl1uE3u7hnQ3xrONdQLNqbHJX9Y1dwFTvBddXgY.png\";s:10:\"sort_order\";i:8;s:4:\"slug\";s:13:\"akla-asr-mntg\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:8;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:39;s:4:\"name\";s:25:\"اصلاح التكييف\";s:7:\"name_en\";s:24:\"Air condition mantinance\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/bJAhQ7FQhHKeeVHRfx1yYZY0OPJQoFG2G08j4WWB.png\";s:10:\"sort_order\";i:9;s:4:\"slug\";s:13:\"aslah-altkyyf\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:39;s:4:\"name\";s:25:\"اصلاح التكييف\";s:7:\"name_en\";s:24:\"Air condition mantinance\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/bJAhQ7FQhHKeeVHRfx1yYZY0OPJQoFG2G08j4WWB.png\";s:10:\"sort_order\";i:9;s:4:\"slug\";s:13:\"aslah-altkyyf\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:9;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:40;s:4:\"name\";s:10:\"تنظيف\";s:7:\"name_en\";s:9:\"Cleanings\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/9Smb1XZLiXF2oMKMHZhMnDq8VKRRRE8hCH5MG3sc.png\";s:10:\"sort_order\";i:10;s:4:\"slug\";s:6:\"tnthyf\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:40;s:4:\"name\";s:10:\"تنظيف\";s:7:\"name_en\";s:9:\"Cleanings\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/9Smb1XZLiXF2oMKMHZhMnDq8VKRRRE8hCH5MG3sc.png\";s:10:\"sort_order\";i:10;s:4:\"slug\";s:6:\"tnthyf\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:10;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:41;s:4:\"name\";s:21:\"دروس خصوصية\";s:7:\"name_en\";s:15:\"Private Teacher\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/RlAMGCXlmFMgdZ0nu3BBFGLVdc3tKJv8UbuMfK3j.png\";s:10:\"sort_order\";i:11;s:4:\"slug\";s:11:\"dros-khsosy\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:41;s:4:\"name\";s:21:\"دروس خصوصية\";s:7:\"name_en\";s:15:\"Private Teacher\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/RlAMGCXlmFMgdZ0nu3BBFGLVdc3tKJv8UbuMfK3j.png\";s:10:\"sort_order\";i:11;s:4:\"slug\";s:11:\"dros-khsosy\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:11;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:42;s:4:\"name\";s:27:\"كاميرات مراقبة\";s:7:\"name_en\";s:20:\"Surveillance Cameras\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/j9A4doAEU1AySRV8BGdJD8Tl0Fqgbbx5pKF9QqIm.png\";s:10:\"sort_order\";i:12;s:4:\"slug\";s:13:\"kamyrat-mrakb\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:42;s:4:\"name\";s:27:\"كاميرات مراقبة\";s:7:\"name_en\";s:20:\"Surveillance Cameras\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/j9A4doAEU1AySRV8BGdJD8Tl0Fqgbbx5pKF9QqIm.png\";s:10:\"sort_order\";i:12;s:4:\"slug\";s:13:\"kamyrat-mrakb\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:12;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:43;s:4:\"name\";s:25:\"تجيهز الحفلات\";s:7:\"name_en\";s:7:\"Party A\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/AnK3obwnxnArX42jWCcmgiT5jbUrDYhByKH29LRX.png\";s:10:\"sort_order\";i:13;s:4:\"slug\";s:13:\"tgyhz-alhflat\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:43;s:4:\"name\";s:25:\"تجيهز الحفلات\";s:7:\"name_en\";s:7:\"Party A\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/AnK3obwnxnArX42jWCcmgiT5jbUrDYhByKH29LRX.png\";s:10:\"sort_order\";i:13;s:4:\"slug\";s:13:\"tgyhz-alhflat\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:13;O:19:\"App\\Models\\Category\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:44;s:4:\"name\";s:25:\"تنسيق الحدائق\";s:7:\"name_en\";s:14:\"Design Gardens\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/YHZQNln0FdbWBmbjbiZKPKo9S7lH0YfmZZDm7cON.png\";s:10:\"sort_order\";i:14;s:4:\"slug\";s:13:\"tnsyk-alhdayk\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:44;s:4:\"name\";s:25:\"تنسيق الحدائق\";s:7:\"name_en\";s:14:\"Design Gardens\";s:11:\"description\";N;s:14:\"description_ar\";N;s:5:\"image\";s:56:\"departments/YHZQNln0FdbWBmbjbiZKPKo9S7lH0YfmZZDm7cON.png\";s:10:\"sort_order\";i:14;s:4:\"slug\";s:13:\"tnsyk-alhdayk\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:18:\"voice_note_enabled\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:13:\"subCategories\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:13:{i:0;s:4:\"name\";i:1;s:7:\"name_en\";i:2;s:11:\"description\";i:3;s:14:\"description_ar\";i:4;s:4:\"icon\";i:5;s:5:\"image\";i:6;s:9:\"parent_id\";i:7;s:9:\"is_active\";i:8;s:18:\"voice_note_enabled\";i:9;s:10:\"sort_order\";i:10;s:10:\"meta_title\";i:11;s:16:\"meta_description\";i:12;s:4:\"slug\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1757676635);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'fas fa-folder',
  `image` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `voice_note_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `name_en`, `description`, `description_ar`, `icon`, `image`, `parent_id`, `is_active`, `voice_note_enabled`, `sort_order`, `meta_title`, `meta_description`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(27, 'نقل العفش', 'Furniture Moving', 'خدمات نقل العفش والأثاث', 'خدمات نقل العفش والأثاث', 'fas fa-folder', 'departments/JhetHybFBdaTFyMRQ4YZltDJAmpHFWm5fV5mnCCK.png', NULL, 1, 1, 1, NULL, NULL, 'nkl-alaafsh', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(30, 'صيانة السيارات', 'Cars Maintanence', NULL, NULL, 'fas fa-folder', 'departments/qJ1S1NwB1hgXAT9xuKZdv6jyaRL3awPXpUeE2hbk.png', NULL, 1, 1, 2, NULL, NULL, 'syan-alsyarat', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(31, 'قطع غيار', 'Cars`s Spare parts', 'The Spare Parts Department is responsible for providing all the components and materials needed for the maintenance and repair of equipment and machinery. Its goal is to ensure the availability of original and replacement parts with high quality and on time to support uninterrupted operational processes.', 'قسم قطع الغيار مسؤول عن توفير جميع المكونات والمواد اللازمة لصيانة وإصلاح المعدات والآلات. يهدف إلى ضمان توافر القطع الأصلية والبديلة بجودة عالية وفي الوقت المناسب لدعم العمليات التشغيلية دون انقطاع.', 'fas fa-folder', 'departments/2h8J6DrD6fHoq8YhiknP4IwPhRcSZQat5T1WUe02.png', NULL, 1, 1, 3, NULL, NULL, 'ktaa-ghyar', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(32, 'معدات ثقيلة', 'Heavy a Equipment', NULL, NULL, 'fas fa-folder', 'departments/BcFbyOlvFdSTpXydkn6Qk7R8GQk87XNSOfVrfjyt.png', NULL, 1, 1, 4, NULL, NULL, 'maadat-thkyl', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(33, 'شاحنات', 'Heavy Trucks', NULL, NULL, 'fas fa-folder', 'departments/c6noFg0l6DziLgjRlizjRLHwb9XoqNJKTm5wv2EM.png', NULL, 1, 1, 5, NULL, NULL, 'shahnat', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(36, 'صهريج مياه', 'Water\'s Car', NULL, NULL, 'fas fa-folder', 'departments/bJuDgRoOMdrZFNKJtOlhh7Dz8UTpXbPOeB8FLybz.png', NULL, 1, 1, 6, NULL, NULL, 'shryg-myah', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(37, 'سطحة', 'Big A cars', NULL, NULL, 'fas fa-folder', 'departments/wF6CCtMQimNZ7qCRrAN5ZFfO5mnY9Qlou5DDUyHk.png', NULL, 1, 1, 7, NULL, NULL, 'sth', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(38, 'اكلا اسر منتجة', 'Family food', NULL, NULL, 'fas fa-folder', 'departments/7hl1uE3u7hnQ3xrONdQLNqbHJX9Y1dwFTvBddXgY.png', NULL, 1, 1, 8, NULL, NULL, 'akla-asr-mntg', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(39, 'اصلاح التكييف', 'Air condition mantinance', NULL, NULL, 'fas fa-folder', 'departments/bJAhQ7FQhHKeeVHRfx1yYZY0OPJQoFG2G08j4WWB.png', NULL, 1, 1, 9, NULL, NULL, 'aslah-altkyyf', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(40, 'تنظيف', 'Cleanings', NULL, NULL, 'fas fa-folder', 'departments/9Smb1XZLiXF2oMKMHZhMnDq8VKRRRE8hCH5MG3sc.png', NULL, 1, 1, 10, NULL, NULL, 'tnthyf', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(41, 'دروس خصوصية', 'Private Teacher', NULL, NULL, 'fas fa-folder', 'departments/RlAMGCXlmFMgdZ0nu3BBFGLVdc3tKJv8UbuMfK3j.png', NULL, 1, 1, 11, NULL, NULL, 'dros-khsosy', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(42, 'كاميرات مراقبة', 'Surveillance Cameras', NULL, NULL, 'fas fa-folder', 'departments/j9A4doAEU1AySRV8BGdJD8Tl0Fqgbbx5pKF9QqIm.png', NULL, 1, 1, 12, NULL, NULL, 'kamyrat-mrakb', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(43, 'تجيهز الحفلات', 'Party A', NULL, NULL, 'fas fa-folder', 'departments/AnK3obwnxnArX42jWCcmgiT5jbUrDYhByKH29LRX.png', NULL, 1, 1, 13, NULL, NULL, 'tgyhz-alhflat', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL),
(44, 'تنسيق الحدائق', 'Design Gardens', NULL, NULL, 'fas fa-folder', 'departments/YHZQNln0FdbWBmbjbiZKPKo9S7lH0YfmZZDm7cON.png', NULL, 1, 1, 14, NULL, NULL, 'tnsyk-alhdayk', '2025-09-09 17:59:36', '2025-09-09 17:59:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category_cities`
--

CREATE TABLE `category_cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_cities`
--

INSERT INTO `category_cities` (`id`, `category_id`, `city_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 27, 1, 1, 0, '2025-09-12 09:04:01', '2025-09-12 09:04:01'),
(2, 27, 6, 1, 0, '2025-09-12 09:04:01', '2025-09-12 09:04:01');

-- --------------------------------------------------------

--
-- Table structure for table `category_fields`
--

CREATE TABLE `category_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `type` enum('title','text','number','select','checkbox','textarea','image','date','time') NOT NULL,
  `value` text DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `input_group` varchar(255) DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_repeatable` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_fields`
--

INSERT INTO `category_fields` (`id`, `category_id`, `sub_category_id`, `name`, `name_ar`, `name_en`, `type`, `value`, `options`, `input_group`, `is_required`, `is_repeatable`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 27, NULL, 'furniture_type', 'نوع العفش', 'Furniture Type', 'select', 'كنبة', '[\"\\u0643\\u0646\\u0628\\u0629\",\"\\u062a\\u0633\\u0631\\u064a\\u062d\\u0629\",\"\\u0645\\u0643\\u064a\\u0641\\u0627\\u062a \\u0633\\u0628\\u0644\\u064a\\u062a\"]', '1', 0, 1, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-13 13:45:46'),
(2, 27, NULL, 'quantity', 'عدد', 'Quantity', 'number', NULL, NULL, '1', 0, 1, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-13 13:45:57'),
(3, 27, NULL, 'disassemble', 'فك', 'Disassemble', 'checkbox', '0', NULL, '1', 0, 1, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-13 13:46:05'),
(4, 27, NULL, 'install', 'تركيب', 'Install', 'checkbox', '0', NULL, '1', 0, 1, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-13 13:46:15'),
(5, 27, NULL, 'من_الحي', 'من الحي', 'From Neighborhood', 'text', NULL, NULL, NULL, 0, 0, NULL, 5, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(6, 27, NULL, 'من_الدور', 'من الدور', 'From Floor', 'select', NULL, '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]', NULL, 0, 0, NULL, 6, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(7, 27, NULL, 'الي_المدينة', 'الي المدينة', 'To City', 'select', NULL, '[\"\\u0645\\u0643\\u0629\",\"\\u0627\\u0644\\u0631\\u064a\\u0627\\u0636\",\"\\u0627\\u0644\\u0645\\u062f\\u064a\\u0646\\u0629\"]', NULL, 0, 0, NULL, 7, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(8, 27, NULL, 'الي_الحي', 'الي الحي', 'To Neighborhood', 'text', NULL, NULL, NULL, 0, 0, NULL, 8, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(9, 27, NULL, 'الي_الدور', 'الي الدور', 'To Floor', 'select', NULL, '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\"]', NULL, 0, 0, NULL, 9, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(11, 30, NULL, 'نوع السيارة', 'نوع السيارة', 'Car`s Model', 'text', NULL, NULL, NULL, 1, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(12, 30, NULL, 'سنة الصنع', 'سنة الصنع', 'Made year', 'number', NULL, NULL, NULL, 1, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(13, 30, NULL, 'شرح_عن_الخلل', 'شرح عن الخلل', 'Describe Damages', 'textarea', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(14, 30, NULL, 'صور السيارة', 'صور السيارة', 'Car Images', 'image', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(15, 31, NULL, 'الفئة', 'الفئة', 'Model', 'text', NULL, NULL, NULL, 1, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(16, 31, NULL, 'سنة الصنع', 'سنة الصنع', 'year made', 'number', NULL, NULL, NULL, 1, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(17, 31, NULL, 'رقم_القطعة_المطلوبة', 'رقم القطعة المطلوبة', 'Part Number', 'text', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(18, 31, NULL, 'اسم_القطعة', 'اسم القطعة', 'Part Name', 'text', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(19, 31, NULL, 'ارفاق_صورة', 'ارفاق صورة', 'Part Image', 'image', NULL, NULL, NULL, 0, 0, NULL, 5, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(20, 31, NULL, 'ملاحظة', 'ملاحظة', 'Note', 'textarea', NULL, NULL, NULL, 0, 0, NULL, 6, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(21, 32, NULL, 'ملاحظة', 'ملاحظة', 'Note', 'textarea', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(22, 32, NULL, 'ارفاق_صورة', 'ارفاق صورة', 'Attach Image', 'image', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(23, 32, NULL, 'الوقت', 'الوقت', 'Time', 'time', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(24, 32, NULL, 'التاريخ', 'التاريخ', 'Date', 'date', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(25, 33, NULL, 'من_الحي', 'من الحي', 'From Neighborhood', 'text', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(26, 33, NULL, 'الي_المدينة', 'الي المدينة', 'To City', 'text', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(27, 33, NULL, 'الي_الحي', 'الي الحي', 'To Neighborhood', 'text', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(28, 33, NULL, 'ملاحظة', 'ملاحظة', 'Note', 'textarea', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(29, 36, NULL, 'مياه_صالحة_للشرب', 'مياه صالحة للشرب', 'Drinking Water', 'select', NULL, '[\"18 \\u0637\\u0646\",\"32 \\u0637\\u0646\"]', NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(30, 36, NULL, 'مياه_صرف_صحي', 'مياه صرف صحي', 'Sewage Water', 'select', NULL, '[\"18 \\u0637\\u0646\",\"32 \\u0637\\u0646\"]', NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(31, 37, NULL, 'الموقع', 'الموقع', 'Location', 'text', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(32, 37, NULL, 'نوع_السيارة', 'نوع السيارة', 'Car Type', 'text', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(33, 37, NULL, 'ارفاق_صورة', 'ارفاق صورة', 'Attach Images', 'image', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(34, 37, NULL, 'ملاحظة', 'ملاحظة', 'Note', 'textarea', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(35, 38, NULL, 'نوع_الاكل', 'نوع الاكل', 'Food Type', 'text', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(36, 38, NULL, 'الوقت', 'الوقت', 'Time', 'time', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(37, 38, NULL, 'التاريخ', 'التاريخ', 'Date', 'date', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(38, 38, NULL, 'ملاحظة', 'ملاحظة', 'Note', 'textarea', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(39, 39, NULL, 'نوع التكييف', 'نوع التكييف', 'Air type', 'select', NULL, '[\"\\u0633\\u0628\\u0644\\u064a\\u062a\",\"\\u0634\\u0628\\u0627\\u0643\"]', NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(40, 39, NULL, 'نوع الخدمة', 'نوع الخدمة', 'Service type', 'title', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(41, 39, NULL, 'تنظيف', 'تنظيف', 'clean', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(42, 39, NULL, 'فيريون', 'فيريون', 'feroun', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(43, 39, NULL, 'صيانة', 'صيانة', 'Maintenance', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 5, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(44, 39, NULL, 'الماركة', 'الماركة', 'Model', 'text', NULL, NULL, NULL, 0, 0, NULL, 6, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(45, 39, NULL, 'العدد', 'العدد', 'Quantity', 'number', NULL, NULL, NULL, 0, 0, NULL, 7, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(46, 39, NULL, 'صورة', 'صورة', 'images', 'image', NULL, NULL, NULL, 0, 0, NULL, 8, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(47, 39, NULL, 'التاريخ', 'التاريخ', 'Date', 'date', NULL, NULL, NULL, 0, 0, NULL, 9, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(48, 40, NULL, 'الوقت', 'الوقت', 'TIme', 'date', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(49, 41, NULL, 'ذكر', 'ذكر', 'Male', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(50, 41, NULL, 'انثي', 'انثي', 'Female', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(51, 41, NULL, 'الوقت', 'الوقت', 'TIme', 'time', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(52, 41, NULL, 'التاريخ', 'التاريخ', 'Date', 'date', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(53, 42, NULL, 'نوع الكاميرا', 'نوع الكاميرا', 'Camera type', 'title', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(54, 42, NULL, 'بصمة', 'بصمة', 'Finger print', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(55, 42, NULL, 'كاميرات مراقبة', 'كاميرات مراقبة', 'Surveillance Cameras', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(56, 42, NULL, 'سمارت', 'سمارت', 'Smart', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 4, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(57, 42, NULL, 'أنظمة إطفاء حرائق', 'أنظمة إطفاء حرائق', 'Fire System', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 5, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(58, 42, NULL, 'شبكات', 'شبكات', 'Networks', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 6, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(59, 42, NULL, 'أنظمة أمنية', 'أنظمة أمنية', 'Security Systems', 'checkbox', NULL, NULL, NULL, 0, 0, NULL, 7, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(60, 43, NULL, 'ارفاق صورة', 'ارفاق صورة', 'Attach image', 'image', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(61, 43, NULL, 'الوقت', 'الوقت', 'TIme', 'time', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(62, 43, NULL, 'التاريخ', 'التاريخ', 'Date', 'date', NULL, NULL, NULL, 0, 0, NULL, 3, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(63, 44, NULL, 'ارفاق صورة', 'ارفاق صورة', 'Attach image', 'image', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(64, 44, NULL, 'التاريخ', 'التاريخ', 'Date', 'date', NULL, NULL, NULL, 0, 0, NULL, 2, 1, '2025-09-09 17:59:36', '2025-09-09 17:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name_ar`, `name_en`, `slug`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'الرياض', 'Riyadh', 'riyadh', 1, 1, NULL, NULL),
(2, 'جدة', 'Jeddah', 'jeddah', 1, 2, NULL, NULL),
(3, 'مكة المكرمة', 'Makkah', 'makkah', 1, 3, NULL, NULL),
(4, 'المدينة المنورة', 'Madinah', 'madinah', 1, 4, NULL, NULL),
(5, 'الدمام', 'Dammam', 'dammam', 1, 5, NULL, NULL),
(6, 'الخبر', 'Khobar', 'khobar', 1, 6, NULL, NULL),
(7, 'الظهران', 'Dhahran', 'dhahran', 1, 7, NULL, NULL),
(8, 'تبوك', 'Tabuk', 'tabuk', 1, 8, NULL, NULL),
(9, 'أبها', 'Abha', 'abha', 1, 9, NULL, NULL),
(10, 'حائل', 'Hail', 'hail', 1, 10, NULL, NULL),
(11, 'القصيم', 'Qassim', 'qassim', 1, 11, NULL, NULL),
(12, 'جازان', 'Jazan', 'jazan', 1, 12, NULL, NULL),
(13, 'نجران', 'Najran', 'najran', 1, 13, NULL, NULL),
(14, 'الباحة', 'Baha', 'baha', 1, 14, NULL, NULL),
(15, 'الجوف', 'Jouf', 'jouf', 1, 15, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `city_service`
--

CREATE TABLE `city_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `reply_to_message_id` varchar(255) DEFAULT NULL,
  `conversation_id` varchar(255) DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `media` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`media`)),
  `content` text DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `voice_note_path` varchar(255) DEFAULT NULL,
  `message_type` enum('text','image','voice','file','location','contact') NOT NULL DEFAULT 'text',
  `service_offer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `created_at`, `updated_at`, `sender_id`, `receiver_id`, `message`, `is_read`, `read_at`, `is_deleted`, `deleted_at`, `metadata`, `reply_to_message_id`, `conversation_id`, `service_id`, `media`, `content`, `media_path`, `voice_note_path`, `message_type`, `service_offer_id`, `file_name`, `file_size`) VALUES
(1, '2025-09-14 10:13:50', '2025-09-14 10:23:13', 3, 7, NULL, 1, '2025-09-14 10:23:13', 0, NULL, '{\"file_size\":35761,\"file_name\":\"410179368_880500867413603_7664735357642872678_n.jpg\",\"mime_type\":\"image\\/jpeg\"}', NULL, 'conv_3_7', NULL, NULL, NULL, 'messages/media/XpkDy5GAQX8rtWMVE3Qiq1auijD0jslYvNscsnJJ.jpg', NULL, 'image', NULL, '410179368_880500867413603_7664735357642872678_n.jpg', 35761),
(2, '2025-09-27 16:43:57', '2025-09-27 17:47:53', 8, 7, NULL, 1, '2025-09-27 17:47:53', 0, NULL, NULL, NULL, 'conv_7_8', NULL, NULL, 'gjhghjg', NULL, NULL, 'text', NULL, NULL, NULL),
(3, '2025-09-27 16:45:18', '2025-09-27 17:47:53', 8, 7, NULL, 1, '2025-09-27 17:47:53', 0, NULL, '{\"file_size\":104472,\"file_name\":\"WhatsApp Image 2025-09-21 at 16.33.21_57ca1404.jpg\",\"mime_type\":\"image\\/jpeg\"}', NULL, 'conv_7_8', NULL, NULL, NULL, 'messages/media/Z3Rf0WNZKxR9IReqFoPIcc0JOK99ooAF3oxcCs1r.jpg', NULL, 'image', NULL, 'WhatsApp Image 2025-09-21 at 16.33.21_57ca1404.jpg', 104472);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000007_add_is_admin_to_users_table', 1),
(5, '2024_01_01_000008_add_user_type_and_phone_to_users_table', 1),
(6, '2024_01_01_000011_create_notifications_table', 1),
(7, '2024_01_01_000012_create_categories_table', 1),
(8, '2024_01_01_000013_create_services_table', 1),
(9, '2024_01_01_000014_create_service_offers_table', 1),
(10, '2024_01_01_000015_create_orders_table', 1),
(11, '2024_01_01_000016_create_category_fields_table', 1),
(12, '2024_01_01_000017_add_custom_fields_to_services_table', 1),
(13, '2025_08_29_154402_create_provider_profiles_table', 1),
(14, '2025_08_29_154409_create_provider_categories_table', 1),
(15, '2025_08_29_154415_create_provider_cities_table', 1),
(16, '2025_08_29_154546_create_system_settings_table', 1),
(17, '2025_08_29_160646_create_cities_table', 1),
(18, '2025_08_29_160810_add_city_id_to_services_table', 1),
(19, '2025_08_29_160908_update_provider_cities_to_use_city_id', 1),
(20, '2025_08_29_162750_add_name_en_and_description_ar_to_categories_table', 1),
(21, '2025_08_30_115309_add_voice_note_to_services_table', 1),
(22, '2025_08_30_115536_add_voice_note_enabled_to_categories_table', 1),
(23, '2025_08_30_123947_create_messages_table', 1),
(24, '2025_08_30_130846_add_media_to_messages_table', 1),
(25, '2025_08_30_150826_fix_provider_cities_city_name_nullable', 1),
(26, '2025_08_31_125253_update_messages_table_with_new_features', 1),
(27, '2025_08_31_131753_add_missing_columns_to_messages_table', 1),
(28, '2025_08_31_141816_fix_messages_table_structure', 1),
(29, '2025_08_31_142137_make_service_id_nullable_in_messages_table', 1),
(30, '2025_08_31_151424_create_city_service_table', 1),
(31, '2025_08_31_151445_update_cities_table_structure', 1),
(32, '2025_09_01_120652_create_category_cities_table', 1),
(33, '2025_09_01_145417_create_sub_categories_table', 1),
(34, '2025_09_01_151742_add_status_to_sub_categories_table', 1),
(35, '2025_09_01_151908_add_sub_category_id_to_category_fields_table', 1),
(36, '2025_09_01_152756_add_sub_category_id_to_services_table', 1),
(37, '2025_09_01_160000_check_sub_category_id_in_services_table', 1),
(38, '2025_09_03_165403_add_provider_id_to_orders_table', 1),
(39, '2025_09_03_171051_add_status_to_service_offers_table', 1),
(40, '2025_09_03_171659_add_completion_fields_to_service_offers_table', 1),
(41, '2025_09_04_094304_update_service_offers_status_enum', 1),
(42, '2025_09_04_111335_add_delivery_and_rating_fields_to_service_offers_table', 1),
(43, '2025_09_08_132814_add_delivered_at_to_service_offers_table', 1),
(44, '2025_09_12_112915_modify_users_table_for_phone_auth', 2),
(45, '2025_09_13_161006_modify_voice_note_column_in_services_table', 3),
(47, '2025_09_13_175317_fix_messages_table_field_name', 4),
(48, '2025_09_16_131529_create_phone_numbers_table', 4),
(49, '2025_09_16_131537_create_whatsapp_messages_table', 4),
(50, '2025_09_27_205022_add_image_to_users_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 6, 'offer_received', 'عرض جديد لخدمتك', 'قدم {provider} عرضاً جديداً لخدمتك: {service}', '{\"service_id\":4,\"offer_id\":1,\"provider_id\":5,\"price\":\"22.00\"}', NULL, '2025-09-12 09:36:44', '2025-09-12 09:36:44'),
(2, 7, 'offer_received', 'عرض جديد لخدمتك', 'قدم {provider} عرضاً جديداً لخدمتك: {service}', '{\"service_id\":5,\"offer_id\":2,\"provider_id\":3,\"price\":\"222.00\"}', NULL, '2025-09-13 14:13:18', '2025-09-13 14:13:18'),
(3, 7, 'offer_received', 'عرض جديد لخدمتك', 'قدم {provider} عرضاً جديداً لخدمتك: {service}', '{\"service_id\":5,\"offer_id\":4,\"provider_id\":8,\"price\":\"1232.00\"}', NULL, '2025-09-27 16:39:10', '2025-09-27 16:39:10'),
(4, 6, 'offer_received', 'عرض جديد لخدمتك', 'قدم {provider} عرضاً جديداً لخدمتك: {service}', '{\"service_id\":4,\"offer_id\":5,\"provider_id\":8,\"price\":\"1232.00\"}', NULL, '2025-09-27 16:42:55', '2025-09-27 16:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_numbers`
--

CREATE TABLE `phone_numbers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL DEFAULT '+20',
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phone_numbers`
--

INSERT INTO `phone_numbers` (`id`, `name`, `phone_number`, `country_code`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Omar Bek', '1065686522', '+20', 'asdasd', 1, '2025-09-16 11:01:27', '2025-09-16 11:01:27'),
(2, 'أحمد محمد', '1234567890', '+20', 'عميل مميز', 1, '2025-09-16 11:04:49', '2025-09-16 11:04:49'),
(3, 'فاطمة علي', '9876543210', '+966', 'عميلة من السعودية', 1, '2025-09-16 11:04:49', '2025-09-16 11:04:49'),
(4, 'محمد حسن', '5555555555', '+971', 'عميل من الإمارات', 0, '2025-09-16 11:04:49', '2025-09-16 11:04:49');

-- --------------------------------------------------------

--
-- Table structure for table `provider_categories`
--

CREATE TABLE `provider_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_categories`
--

INSERT INTO `provider_categories` (`id`, `user_id`, `category_id`, `is_active`, `description`, `hourly_rate`, `experience_years`, `created_at`, `updated_at`) VALUES
(4, 5, 27, 1, NULL, NULL, NULL, '2025-09-12 09:36:22', '2025-09-12 09:36:22'),
(5, 5, 33, 1, NULL, NULL, NULL, '2025-09-12 09:36:22', '2025-09-12 09:36:22'),
(6, 5, 38, 1, NULL, NULL, NULL, '2025-09-12 09:36:22', '2025-09-12 09:36:22'),
(7, 3, 27, 1, NULL, NULL, NULL, '2025-09-13 14:12:53', '2025-09-13 14:12:53'),
(8, 3, 32, 1, NULL, NULL, NULL, '2025-09-13 14:12:53', '2025-09-13 14:12:53'),
(18, 8, 27, 1, NULL, NULL, NULL, '2025-09-27 17:51:27', '2025-09-27 17:51:27'),
(19, 8, 30, 1, NULL, NULL, NULL, '2025-09-27 17:51:27', '2025-09-27 17:51:27'),
(20, 8, 32, 1, NULL, NULL, NULL, '2025-09-27 17:51:27', '2025-09-27 17:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `provider_cities`
--

CREATE TABLE `provider_cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_cities`
--

INSERT INTO `provider_cities` (`id`, `user_id`, `city_id`, `city_name`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(4, 5, 1, NULL, 1, NULL, '2025-09-12 09:36:22', '2025-09-12 09:36:22'),
(5, 5, 4, NULL, 1, NULL, '2025-09-12 09:36:22', '2025-09-12 09:36:22'),
(6, 5, 7, NULL, 1, NULL, '2025-09-12 09:36:22', '2025-09-12 09:36:22'),
(7, 5, 10, NULL, 1, NULL, '2025-09-12 09:36:22', '2025-09-12 09:36:22'),
(8, 3, 1, NULL, 1, NULL, '2025-09-13 14:12:53', '2025-09-13 14:12:53'),
(9, 3, 4, NULL, 1, NULL, '2025-09-13 14:12:53', '2025-09-13 14:12:53'),
(22, 8, 1, NULL, 1, NULL, '2025-09-27 17:51:27', '2025-09-27 17:51:27'),
(23, 8, 4, NULL, 1, NULL, '2025-09-27 17:51:27', '2025-09-27 17:51:27'),
(24, 8, 7, NULL, 1, NULL, '2025-09-27 17:51:27', '2025-09-27 17:51:27'),
(25, 8, 10, NULL, 1, NULL, '2025-09-27 17:51:27', '2025-09-27 17:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `provider_profiles`
--

CREATE TABLE `provider_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bio` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `max_categories` int(11) NOT NULL DEFAULT 3,
  `max_cities` int(11) NOT NULL DEFAULT 5,
  `working_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`working_hours`)),
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `completed_services` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_profiles`
--

INSERT INTO `provider_profiles` (`id`, `user_id`, `bio`, `phone`, `address`, `is_verified`, `is_active`, `max_categories`, `max_cities`, `working_hours`, `rating`, `completed_services`, `created_at`, `updated_at`) VALUES
(1, 5, 'sadasd', '01065686522', 'qweqw', 0, 0, 3, 5, NULL, 0.00, 0, '2025-09-12 09:35:57', '2025-09-12 09:35:57'),
(2, 3, 'asda', '+966507654321', 'asd', 0, 0, 3, 5, NULL, 0.00, 0, '2025-09-13 14:12:53', '2025-09-13 14:12:53'),
(3, 8, 'dasdasdasd', '111222', 'asdasd', 0, 0, 3, 5, NULL, 0.00, 0, '2025-09-27 16:38:44', '2025-09-27 16:38:44');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
  `voice_note` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `price`, `category_id`, `sub_category_id`, `city_id`, `user_id`, `is_active`, `is_featured`, `image`, `meta_title`, `meta_description`, `slug`, `location`, `contact_phone`, `contact_email`, `custom_fields`, `voice_note`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'طلب خدمة - نقل العفش - الرياض', '123', 0.00, 27, NULL, 1, 6, 1, 0, NULL, NULL, NULL, 'tlb-khdm-nkl-alaafsh-alryad', NULL, NULL, NULL, '{\"\\u0646\\u0648\\u0639_\\u0627\\u0644\\u0639\\u0641\\u0634\":[\"\\u062a\\u0633\\u0631\\u064a\\u062d\\u0629\"],\"\\u0639\\u062f\\u062f\":[\"12\"],\"\\u0641\\u0643\":[\"1\"],\"\\u0645\\u0646_\\u0627\\u0644\\u062d\\u064a\":[\"213\"],\"\\u0645\\u0646_\\u0627\\u0644\\u062f\\u0648\\u0631\":[\"6\"],\"\\u0627\\u0644\\u064a_\\u0627\\u0644\\u0645\\u062f\\u064a\\u0646\\u0629\":[\"\\u0645\\u0643\\u0629\"],\"\\u0627\\u0644\\u064a_\\u0627\\u0644\\u062d\\u064a\":[\"123\"],\"\\u0627\\u0644\\u064a_\\u0627\\u0644\\u062f\\u0648\\u0631\":[\"8\"],\"\\u0645\\u0644\\u0627\\u062d\\u0638\\u0629_\\u0639\\u0646_\\u0627\\u0644\\u0639\\u0645\\u0644\":[\"123\"]}', NULL, '2025-09-12 09:32:05', '2025-09-12 09:32:05', NULL);
INSERT INTO `services` (`id`, `title`, `description`, `price`, `category_id`, `sub_category_id`, `city_id`, `user_id`, `is_active`, `is_featured`, `image`, `meta_title`, `meta_description`, `slug`, `location`, `contact_phone`, `contact_email`, `custom_fields`, `voice_note`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'طلب خدمة - نقل العفش - الرياض', '123', 0.00, 27, NULL, 1, 7, 1, 0, NULL, NULL, NULL, 'tlb-khdm-nkl-alaafsh-alryad-1', NULL, NULL, NULL, '{\"\\u0646\\u0648\\u0639_\\u0627\\u0644\\u0639\\u0641\\u0634\":[\"\\u0643\\u0646\\u0628\\u0629\"],\"\\u0639\\u062f\\u062f\":[\"22\"],\"\\u0641\\u0643\":[\"1\"],\"\\u062a\\u0631\\u0643\\u064a\\u0628\":[\"1\"],\"\\u0645\\u0646_\\u0627\\u0644\\u062d\\u064a\":[\"123\"],\"\\u0645\\u0646_\\u0627\\u0644\\u062f\\u0648\\u0631\":[\"1\"],\"\\u0627\\u0644\\u064a_\\u0627\\u0644\\u0645\\u062f\\u064a\\u0646\\u0629\":[\"\\u0645\\u0643\\u0629\"],\"\\u0627\\u0644\\u064a_\\u0627\\u0644\\u062d\\u064a\":[\"213\"],\"\\u0627\\u0644\\u064a_\\u0627\\u0644\\u062f\\u0648\\u0631\":[\"8\"],\"\\u0645\\u0644\\u0627\\u062d\\u0638\\u0629_\\u0639\\u0646_\\u0627\\u0644\\u0639\\u0645\\u0644\":[\"1231\"]}', 'data:audio/wav;base64,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', '2025-09-13 13:16:03', '2025-09-13 13:16:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_offers`
--

CREATE TABLE `service_offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected','expired') NOT NULL DEFAULT 'pending',
  `accepted_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_offers`
--

INSERT INTO `service_offers` (`id`, `service_id`, `provider_id`, `price`, `notes`, `status`, `accepted_at`, `delivered_at`, `rating`, `review`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 22.00, 'qwe', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-12 09:36:44', '2025-09-12 09:36:44'),
(2, 5, 3, 222.00, 'asd', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-13 14:13:18', '2025-09-13 14:13:18'),
(3, 4, 3, 150.00, 'عرض تجريبي للتعديل', 'pending', NULL, NULL, NULL, NULL, '2025-09-30 05:33:07', '2025-09-23 05:33:07', '2025-09-23 05:33:07'),
(4, 5, 8, 1232.00, 'qweqweqwe', 'pending', NULL, NULL, NULL, NULL, '2025-09-27 19:39:00', '2025-09-27 16:39:10', '2025-09-27 16:39:10'),
(5, 4, 8, 1232.11, 'fgdgfdgfdgfdgالالالا', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-27 16:42:55', '2025-09-27 17:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2AAqj4yz66PLlNcj2mQZFeS6sARggimXOgQgvTRi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTlVNVZCc0VzSjVrSjVZTWVGS3RCZFdMQ21kcUJiZ3owWXpDUTA4WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ub3RpZmljYXRpb25zL3VucmVhZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759061043),
('NVm6PFnrbAYccBNip09ItrLwAS5e9NE0lFzmMhk4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXA3S05ZUmtiR1d2bGFDdDdSZGVBeEllczlKeUVzV25veHNSazFTQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ub3RpZmljYXRpb25zL3VucmVhZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759061522);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name_ar`, `name_en`, `category_id`, `image`, `description_ar`, `description_en`, `status`, `created_at`, `updated_at`) VALUES
(1, 'مرسيدس', 'Mercedes-Benz', 30, 'sub_categories/eSrzAhPAbP0rGn6DPsJaQXO01Kw7Q217nywMzFt1.jpg', NULL, NULL, 1, '2025-09-12 08:56:50', '2025-09-12 08:56:50');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `type`, `group`, `description`, `created_at`, `updated_at`) VALUES
(1, 'provider_max_categories', '3', 'integer', 'provider', 'الحد الأقصى لعدد الأقسام التي يمكن لمزود الخدمة العمل فيها', NULL, NULL),
(2, 'provider_max_cities', '5', 'integer', 'provider', 'الحد الأقصى لعدد المدن التي يمكن لمزود الخدمة العمل فيها', NULL, NULL),
(3, 'provider_verification_required', 'true', 'boolean', 'provider', 'هل يتطلب التحقق من مزود الخدمة قبل تفعيل الحساب', NULL, NULL),
(4, 'provider_auto_approve', 'false', 'boolean', 'provider', 'هل يتم الموافقة التلقائية على مزودي الخدمة', NULL, NULL),
(5, 'default_service_image', 'services/kaJh8SJ72ngIOcd6LuT0UsKQnCdFmYC2she4t2LC.jpg', 'string', 'general', 'الصورة الافتراضية للخدمات', '2025-09-14 10:21:23', '2025-09-14 10:21:23'),
(6, 'default_service_image_enabled', '1', 'boolean', 'general', 'تفعيل الصورة الافتراضية للخدمات', '2025-09-14 10:21:23', '2025-09-14 10:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `user_type` enum('customer','provider','admin') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `user_type`, `phone`, `image`, `phone_verified_at`, `bio`, `avatar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'مدير النظام', 'admin@endak.com', 1, 'admin', '123456333', NULL, NULL, NULL, NULL, '2025-09-09 17:59:35', '$2y$12$DPq2yTPTdWyCmXXgpmGZaubfdvrqMrg4LIMXFZvW5Wejw7kDRouNy', 'RupmVdLyyFjOT6iRJgKwkUoKKFsHWX2Nvuj8Ov58ssWXFGdBi6fSTn7if0JU', '2025-09-09 17:59:35', '2025-09-12 08:34:20'),
(2, 'مستخدم تجريبي', 'user@endak.com', 0, 'customer', '+966501234567', NULL, NULL, NULL, NULL, '2025-09-09 17:59:36', '$2y$12$uXaF7LD3fzsqCeHGptRM/OAwus7FExhRmOnbNDOtDtSe/j/Ej6w8a', 'zZZ010f3BP', '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(3, 'أحمد محمد', 'provider@endak.com', 0, 'provider', '+966507654321', NULL, NULL, 'مزود خدمات تقنية متخصص في تطوير المواقع والتطبيقات', NULL, '2025-09-09 17:59:36', '$2y$12$HxVpeKQ3HpTxgqYWXB7imOPgr1GldojUk5SK/dJ8Xf9LJKjpZ70fe', 'o7LJNM1Fir81R0lPdpe4HXTkX5g4dUhkaX7jCEYuswuuYFkzZaHbMs8P335h', '2025-09-09 17:59:36', '2025-09-09 17:59:36'),
(4, 'Omar Bek', 'omarbek9156@gmail.com', 0, 'customer', '01065686522', NULL, NULL, NULL, NULL, NULL, '$2y$12$SLJz4K5jPdAW0PC1d3sde.Kb8fNgAtZQ5.Mqk35.R9CxwDUQ2Rdz2', NULL, '2025-09-12 08:06:19', '2025-09-12 08:06:19'),
(5, 'omar', NULL, 0, 'provider', '01065686555', NULL, '2025-09-12 08:36:05', NULL, NULL, NULL, '$2y$12$3ndWR5/ayMcUIpRKTW74WOD3PryTGR8p4uy9ggMM8QyJdacHWVdYO', NULL, '2025-09-12 08:36:05', '2025-09-12 08:36:05'),
(6, 'PROVIDER', NULL, 0, 'customer', '123123123', NULL, '2025-09-12 08:46:13', NULL, NULL, NULL, '$2y$12$ztxtvZZzVRLdeeeRo8M4C.tM/scOWhAhU5ZmVHQxEMJmdO47QppAW', NULL, '2025-09-12 08:46:13', '2025-09-12 08:46:13'),
(7, 'a1', NULL, 0, 'customer', '1002098132', NULL, '2025-09-13 13:15:22', NULL, NULL, NULL, '$2y$12$gieRo/Y8ahoS.eiRdqpjZuhO2TBv5d0N2xARvQZDRgPRgTfDmuVCK', 'AoWPwZgwBlNLhquF7lTIgT6mF0QWAuh9P69eoNt32IpDEuHMpLc8WVGyjbcK', '2025-09-13 13:15:22', '2025-09-13 13:15:22'),
(8, 'Omar Bek', NULL, 0, 'provider', '111222', 'users/F0qpfLQukkBfQKJOBBEyqnyffk2tifs47bv1EQjk.png', '2025-09-27 16:38:04', NULL, NULL, NULL, '$2y$12$7fXkRXybTWyDX4ajlL0By.KRgzcMJMxk4bHcJX.LjVlqZJDcSCHiu', NULL, '2025-09-27 16:38:04', '2025-09-27 17:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_messages`
--

CREATE TABLE `whatsapp_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `phone_numbers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`phone_numbers`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `error_message` text DEFAULT NULL,
  `sent_count` int(11) NOT NULL DEFAULT 0,
  `failed_count` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `category_cities`
--
ALTER TABLE `category_cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_cities_category_id_city_id_unique` (`category_id`,`city_id`),
  ADD KEY `category_cities_category_id_is_active_index` (`category_id`,`is_active`),
  ADD KEY `category_cities_city_id_is_active_index` (`city_id`,`is_active`),
  ADD KEY `category_cities_sort_order_index` (`sort_order`);

--
-- Indexes for table `category_fields`
--
ALTER TABLE `category_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_fields_category_id_name_unique` (`category_id`,`name`),
  ADD KEY `category_fields_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cities_slug_unique` (`slug`),
  ADD KEY `cities_is_active_sort_order_index` (`is_active`,`sort_order`);

--
-- Indexes for table `city_service`
--
ALTER TABLE `city_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
  ADD KEY `messages_receiver_id_is_read_index` (`receiver_id`,`is_read`),
  ADD KEY `messages_conversation_id_index` (`conversation_id`),
  ADD KEY `messages_service_id_index` (`service_id`),
  ADD KEY `messages_created_at_index` (`created_at`),
  ADD KEY `messages_is_deleted_index` (`is_deleted`),
  ADD KEY `messages_service_offer_id_foreign` (`service_offer_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_service_id_foreign` (`service_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `phone_numbers`
--
ALTER TABLE `phone_numbers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_numbers_phone_number_unique` (`phone_number`);

--
-- Indexes for table `provider_categories`
--
ALTER TABLE `provider_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_categories_user_id_category_id_unique` (`user_id`,`category_id`),
  ADD KEY `provider_categories_user_id_is_active_index` (`user_id`,`is_active`),
  ADD KEY `provider_categories_category_id_is_active_index` (`category_id`,`is_active`);

--
-- Indexes for table `provider_cities`
--
ALTER TABLE `provider_cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_cities_user_id_city_id_unique` (`user_id`,`city_id`),
  ADD KEY `provider_cities_user_id_is_active_index` (`user_id`,`is_active`),
  ADD KEY `provider_cities_city_name_is_active_index` (`city_name`,`is_active`),
  ADD KEY `provider_cities_city_id_foreign` (`city_id`),
  ADD KEY `provider_cities_user_id_city_id_index` (`user_id`,`city_id`);

--
-- Indexes for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_profiles_user_id_is_active_index` (`user_id`,`is_active`),
  ADD KEY `provider_profiles_is_verified_is_active_index` (`is_verified`,`is_active`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`),
  ADD KEY `services_category_id_foreign` (`category_id`),
  ADD KEY `services_user_id_foreign` (`user_id`),
  ADD KEY `services_city_id_is_active_index` (`city_id`,`is_active`),
  ADD KEY `services_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `service_offers`
--
ALTER TABLE `service_offers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_offers_service_id_provider_id_unique` (`service_id`,`provider_id`),
  ADD KEY `service_offers_provider_id_foreign` (`provider_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whatsapp_messages_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `category_cities`
--
ALTER TABLE `category_cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category_fields`
--
ALTER TABLE `category_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `city_service`
--
ALTER TABLE `city_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_numbers`
--
ALTER TABLE `phone_numbers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `provider_categories`
--
ALTER TABLE `provider_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `provider_cities`
--
ALTER TABLE `provider_cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_offers`
--
ALTER TABLE `service_offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_cities`
--
ALTER TABLE `category_cities`
  ADD CONSTRAINT `category_cities_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_cities_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_fields`
--
ALTER TABLE `category_fields`
  ADD CONSTRAINT `category_fields_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_fields_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_service_offer_id_foreign` FOREIGN KEY (`service_offer_id`) REFERENCES `service_offers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_categories`
--
ALTER TABLE `provider_categories`
  ADD CONSTRAINT `provider_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_cities`
--
ALTER TABLE `provider_cities`
  ADD CONSTRAINT `provider_cities_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_cities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  ADD CONSTRAINT `provider_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `services_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_offers`
--
ALTER TABLE `service_offers`
  ADD CONSTRAINT `service_offers_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_offers_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  ADD CONSTRAINT `whatsapp_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
