-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 14 mai 2026 à 07:32
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lms_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `duration_hours` int(11) NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `price`, `duration_hours`, `instructor_id`, `created_at`, `updated_at`) VALUES
(1, 'Introduction à Java', 'Prêt à coder ? Plongez dans l\'univers de la programmation avec notre cours \"Introduction à Java\". En 25 heures, vous maîtriserez les fondamentaux de ce langage puissant, guidé par un formateur expert. Développez des compétences clés et donnez un coup d\'accélérateur à votre carrière. Inscrivez-vous pour bâtir votre avenir technologique !', 150000, 25, 1, '2026-05-13 01:53:45', '2026-05-13 18:10:18'),
(2, 'Développement Web PHP', 'Maîtrisez le développement web avec notre formation PHP intensive de 30 heures. Apprenez à construire des applications dynamiques et performantes, guidé par un formateur expert en PHP et Laravel. Acquérir ces compétences clés vous propulsera dans le monde professionnel. Révélez votre potentiel de développeur web !', 150000, 30, 2, '2026-05-13 01:53:45', '2026-05-14 02:17:58'),
(3, 'Laravel Framework', 'Développement moderne avec Laravel.', 180000, 35, 2, '2026-05-13 01:53:45', NULL),
(4, 'Cybersécurité Fondamentale', 'Notions essentielles de sécurité.', 200000, 40, 3, '2026-05-13 01:53:45', NULL),
(5, 'React JS Complet', 'Développement frontend avec React.', 170000, 32, 4, '2026-05-13 01:53:45', NULL),
(6, 'Administration Linux', 'Maîtrisez l\'administration Linux en 30 heures avec notre cours intensif. Apprenez les compétences clés pour gérer efficacement tout système, des bases aux configurations avancées. Notre formateur, expert en réseaux Linux, vous apportera une vision pratique et indispensable. Élevez votre expertise et devenez un administrateur Linux hors pair.', 160000, 30, 5, '2026-05-13 01:53:45', '2026-05-14 02:00:56');

-- --------------------------------------------------------

--
-- Structure de la table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','active','completed') NOT NULL,
  `payment_status` enum('unpaid','paid') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `status`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'active', 'paid', '2026-05-13 01:54:06', '2026-05-13 19:04:33'),
(2, 1, 2, 'pending', 'unpaid', '2026-05-13 01:54:06', NULL),
(3, 2, 3, 'active', 'paid', '2026-05-13 01:54:06', NULL),
(4, 3, 4, 'completed', 'paid', '2026-05-13 01:54:06', NULL),
(5, 4, 5, 'active', 'unpaid', '2026-05-13 01:54:06', NULL),
(6, 5, 6, 'pending', 'unpaid', '2026-05-13 01:54:06', NULL),
(7, 2, 1, 'completed', 'paid', '2026-05-13 01:54:06', NULL),
(8, 3, 2, 'active', 'paid', '2026-05-13 01:54:06', NULL),
(9, 11, 4, 'pending', 'unpaid', '2026-05-13 09:17:21', '2026-05-13 09:17:21');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
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
-- Structure de la table `instructors`
--

CREATE TABLE `instructors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `email`, `specialty`, `created_at`, `updated_at`) VALUES
(1, 'Andry Rakotomalala', 'andry@formation.com', 'Java', '2026-05-13 01:53:21', NULL),
(2, 'Hery Randriamanana', 'hery@formation.com', 'PHP & Laravel', '2026-05-13 01:53:21', NULL),
(3, 'Tahina Razafy', 'tahina@formation.com', 'Cybersécurité', '2026-05-13 01:53:21', NULL),
(4, 'Miora Rasoanaivo', 'miora@formation.com', 'Frontend React', '2026-05-13 01:53:21', NULL),
(5, 'Toky Ramanitra', 'toky@formation.com', 'Réseaux Linux', '2026-05-13 01:53:21', NULL),
(6, 'Jean Dupont', 'jean.dupont@example.com', 'Développement Web Laravel', '2026-05-13 09:09:49', '2026-05-13 09:09:49'),
(7, 'Dr. Rindra', 'rindra@ecole.mg', 'Base de donnees', '2026-05-14 02:02:01', '2026-05-14 02:02:01'),
(8, 'Dr. Rindra', 'rindra@gmail.mg', 'PFE', '2026-05-14 02:19:34', '2026-05-14 02:19:34');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
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
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_11_180759_create_students_table', 1),
(5, '2026_05_11_180855_create_instructors_table', 1),
(6, '2026_05_11_180917_create_courses_table', 1),
(7, '2026_05_11_180948_create_enrollments_table', 1),
(8, '2026_05_12_081815_create_personal_access_tokens_table', 1),
(9, '2026_05_13_210857_change_description_type_in_courses', 2);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
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
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('E7IIK5F8Pf9tguaZXrIhlw0JxCk09jvS7NxkaLIg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.120.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'eyJfdG9rZW4iOiJlWngzazZxbko4T0tkdHllZnd6OTB4NnpzTXRzUEZlMDdoajAzN2FKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1778698996),
('VEJfKMXyirgskzt809q4T7wrdsXQMPvD1dMixleM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJ5VTQ4NTJ4dXpJM2ZFZ25Cd3hsNUxaZmxCQ1kxREJwS3pvNGtUMUJFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1778729765);

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `enrolled_at`, `created_at`, `updated_at`) VALUES
(1, 'Jean Rakoto', 'jeanrkt@gmail.com', '0341234567', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:25:38'),
(2, 'Marie Rasoa', 'marie.rasoa@gmail.com', '0329876543', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(3, 'Lucas Andry', 'lucas.andry@gmail.com', '0334567890', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(4, 'Sarah Ranaivo', 'sarah.ranaivo@gmail.com', '0381122334', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(5, 'Kevin Rakotomalala', 'kevin.rakoto@gmail.com', '0345566778', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-14 01:48:38'),
(7, 'Felana Andrianina', 'felana.andry@gmail.com', '0337788990', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(8, 'Tiana Rakotobe', 'tiana.rakotobe@gmail.com', '0389988776', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(9, 'Mickael Rafanomezantsoa', 'mickael.rafa@gmail.com', '0346677889', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(10, 'Vanessa Raharisoa', 'vanessa.raha@gmail.com', '0321234432', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(11, 'Patrick Ratsimba', 'patrick.ratsimba@gmail.com', '0339988112', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(12, 'Nantenaina Ravelona', 'nantenaina.ravelona@gmail.com', '0385544332', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(13, 'Lova Rakotondrazaka', 'lova.rakoto@gmail.com', '0347788991', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(14, 'Miora Razanajatovo', 'miora.razana@gmail.com', '0326677885', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(15, 'Toky Randria', 'toky.randria@gmail.com', '0332211445', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(16, 'Hery Rakotovao', 'hery.rakoto@gmail.com', '0383344556', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(17, 'Angela Ramanantsoa', 'angela.rama@gmail.com', '0349988771', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(18, 'Fitia Rasolofonirina', 'fitia.raso@gmail.com', '0325566778', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(19, 'Eric Rakotoniaina', 'eric.rakoto@gmail.com', '0338877665', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(20, 'Noah Ramiandrisoa', 'noah.rami@gmail.com', '0387766554', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(21, 'Elina Razafintsalama', 'elina.raza@gmail.com', '0344455667', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45'),
(22, 'Mamy Rakotondrainibe', 'mamy.rakoto@gmail.com', '0329988775', '2026-05-12 16:18:45', '2026-05-12 16:18:45', '2026-05-12 16:18:45');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2026-05-12 12:55:31', '$2y$12$xkqvLM2cKinbDkamQa2YpOw1tqESC9BGPoN.xywuJLfCMnFwBoLgy', 'UHkbp79qUk', '2026-05-12 12:55:31', '2026-05-12 12:55:31');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_instructor_id_foreign` (`instructor_id`);

--
-- Index pour la table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_student_id_foreign` (`student_id`),
  ADD KEY `enrollments_course_id_foreign` (`course_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructors_email_unique` (`email`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_email_unique` (`email`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);

--
-- Contraintes pour la table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
