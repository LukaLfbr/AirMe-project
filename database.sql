-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 06 fév. 2025 à 11:36
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `airme_database`
--

-- --------------------------------------------------------

--
-- Structure de la table `car_pooling_offer`
--

CREATE TABLE `car_pooling_offer` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `departure_location` varchar(255) NOT NULL,
  `arrival_location` varchar(255) NOT NULL,
  `departure_time` varchar(255) NOT NULL,
  `seats_available` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `car_pooling_offer`
--

INSERT INTO `car_pooling_offer` (`id`, `creator_id`, `event_id`, `name`, `description`, `departure_location`, `arrival_location`, `departure_time`, `seats_available`) VALUES
(1, 22, 12, 'Offre de covoiturage des mecs sympas', 'ils sont super', 'Lille', 'Marseille', 'midi', NULL),
(2, 22, 16, 'TOUS EN SELLE', 'YES', 'Calais', 'Palavas', '12h61', 5),
(3, 22, 12, 'En avant pour Lyon', 'Lille - Lyon', 'Lille', 'Lyon', '12H00', NULL),
(4, 22, 14, 'POUR LA BATAILLE', 'Vive l\'airsoft', 'Chartres', 'Luxembourg', '-45H', 5),
(5, 22, 15, 'TITRE', 'FILM', 'Maubeuge', 'Roubaix', '18H00', 2),
(6, 22, 28, 'sdfsdfsd', 'sdfdsfd', 'Lille', 'Lyon', '12H00', 3);

-- --------------------------------------------------------

--
-- Structure de la table `coordinates`
--

CREATE TABLE `coordinates` (
  `id` int(11) NOT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `coordinates`
--

INSERT INTO `coordinates` (`id`, `longitude`, `latitude`) VALUES
(1, 2.3522, 48.8566),
(2, 4.8357, 45.764),
(3, -1.5536, 47.2184),
(4, 5.3698, 43.2965),
(5, 1.4442, 43.6047),
(6, 7.262, 43.7102),
(7, 1.1511, 49.4432),
(8, 3.8767, 43.6119),
(9, -0.5792, 44.8378),
(10, 4.0763, 48.8014),
(13, 2.3200410217201, 48.8588897),
(14, 5.3699525, 43.2961743),
(15, 13.3888599, 52.5170365),
(16, 2.8117941, 50.5249389),
(17, 2.627472, 50.53101),
(18, 2.3200410217201, 48.8588897),
(20, 2.3200410217201, 48.8588897),
(21, 4.8320114, 45.7578137);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20241216145500', '2024-12-16 15:56:57', 37);

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `referent_id` int(11) NOT NULL,
  `coordinates_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(750) NOT NULL,
  `location` varchar(255) NOT NULL,
  `paf` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `duration` varchar(255) DEFAULT NULL,
  `terrain_type` varchar(255) DEFAULT NULL,
  `weather` varchar(255) DEFAULT NULL,
  `temperature` varchar(255) DEFAULT NULL,
  `beginner_friendly` tinyint(1) DEFAULT NULL,
  `equipement_rental` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `referent_id`, `coordinates_id`, `name`, `description`, `location`, `paf`, `date`, `duration`, `terrain_type`, `weather`, `temperature`, `beginner_friendly`, `equipement_rental`, `created_at`, `updated_at`) VALUES
(11, 1, 1, 'Bataille Urbaine à Paris', 'Une bataille intense au cœur de Paris avec des scénarios tactiques variés.', 'Paris', 20, '2024-10-10 10:00:00', '3', 'Urbain', 'Ensoleillé', '25°C', 1, 0, '2024-09-20 10:00:00', '2024-09-20 10:00:00'),
(12, 2, 2, 'Conquête de Lyon', 'Une partie d’airsoft épique dans les ruelles de Lyon, préparez-vous à l’action!', 'Lyon', 25, '2024-10-12 14:00:00', '4', 'Forêt', 'Nuageux', '22°C', 1, 1, '2024-09-20 11:00:00', '2024-09-20 11:00:00'),
(13, 3, 3, 'Assaut sur Nantes', 'Plongez dans un environnement de forêt à Nantes, où chaque coin cache une embuscade.', 'Nantes', 30, '2024-10-15 09:00:00', '2', 'Mixte', 'Pluvieux', '18°C', 0, 1, '2024-09-20 12:00:00', '2024-09-20 12:00:00'),
(14, 4, 4, 'La bataille de Marseille', 'Affrontez-vous sur un terrain ensoleillé avec vue sur la mer, à Marseille.', 'Marseille', 40, '2024-10-18 16:00:00', '3', 'Urbain', 'Ensoleillé', '28°C', 1, 0, '2024-09-20 13:00:00', '2024-09-20 13:00:00'),
(15, 5, 5, 'Stratégie à Toulouse', 'Utilisez les techniques de camouflage dans un environnement verdoyant à Toulouse.', 'Toulouse', 35, '2024-10-22 11:00:00', '4', 'Forêt', 'Nuageux', '21°C', 0, 1, '2024-09-20 14:00:00', '2024-09-20 14:00:00'),
(16, 6, 6, 'Guérilla à Nice', 'Profitez de la diversité des terrains montagneux pour des tactiques avancées.', 'Nice', 50, '2024-10-25 09:00:00', '5', 'Mixte', 'Nuageux', '24°C', 0, 1, '2024-09-20 15:00:00', '2024-09-20 15:00:00'),
(17, 7, 7, 'Escarmouche à Rouen', 'Partez à l’assaut dans le cadre historique de Rouen, ambiance garantie!', 'Rouen', 30, '2024-10-27 13:00:00', '3', 'Forêt', 'Pluvieux', '17°C', 1, 0, '2024-09-20 16:00:00', '2024-09-20 16:00:00'),
(18, 8, 8, 'Raid sur Montpellier', 'Préparez-vous pour un raid rapide et dynamique sur un terrain varié.', 'Montpellier', 20, '2024-10-29 15:00:00', '2', 'Mixte', 'Ensoleillé', '26°C', 1, 0, '2024-09-20 17:00:00', '2024-09-20 17:00:00'),
(19, 9, 9, 'Confrontation à Bordeaux', 'Un rendez-vous pour les amateurs, un moment de convivialité à Bordeaux.', 'Bordeaux', 15, '2024-11-01 12:00:00', '3', 'Forêt', 'Nuageux', '20°C', 1, 1, '2024-09-20 18:00:00', '2024-09-20 18:00:00'),
(20, 10, 10, 'Embuscade à Chartres', 'Découvrez les plaisirs de l’airsoft dans un cadre rural et accueillant.', 'Chartres', 10, '2024-11-03 10:00:00', '2', 'Urbain', 'Nuageux', '15°C', 1, 1, '2024-09-20 19:00:00', '2024-09-20 19:00:00'),
(23, 22, 13, 'TOUTE LES GAMES', 'TRES FORT', 'paris', NULL, '2024-11-15 11:11:00', NULL, NULL, NULL, NULL, 0, 0, '2024-11-08 11:06:47', '2024-11-08 11:06:47'),
(24, 22, 14, 'TOMORROW', 'JE SAIS PLUS !', 'Marseille', NULL, '2035-06-07 11:11:00', NULL, NULL, NULL, NULL, 0, 0, '2024-11-08 11:07:43', '2024-11-08 11:33:49'),
(25, 22, 15, 'DAMN SO FREE !', 'CELEBRERATE !', 'Berlin', NULL, '2025-12-09 00:00:00', NULL, NULL, NULL, NULL, 0, 0, '2024-11-08 11:12:40', '2024-12-13 09:52:32'),
(26, 22, 16, 'Camion Rouge', 'Chêne', '38 Cité Albert Camus Douvrin', 0, '2024-12-21 11:11:00', '30', 'Plat', 'Bonne', '22', 1, 0, '2024-12-16 15:45:04', '2024-12-16 15:45:04'),
(27, 22, 17, 'Terre', 'Eau', '575 Avenue Sully Béthune', 0, '2025-02-12 11:30:00', '90', 'Montage', 'Mauvaise', '55', 1, 1, '2024-12-16 15:49:54', '2024-12-16 15:49:54'),
(28, 22, 18, 'Feu', 'Super', 'Paris', 12, '2025-02-11 15:00:00', '20', NULL, NULL, NULL, 1, 0, '2024-12-16 15:51:09', '2025-01-10 11:52:22'),
(30, 22, 20, 'Partie 3WA', 'Super partie pour l\'école', 'Paris', 0, '2025-01-16 18:00:00', '1H10', 'Ville', 'Superbes', '18', 1, 1, '2025-01-07 11:27:27', '2025-01-07 11:27:27'),
(31, 22, 21, 'Partie de bienvenue', 'Partie pour s\'amuser', 'Lyon', 1, '2025-01-30 14:00:00', '2H00', NULL, NULL, NULL, 1, 0, '2025-01-07 11:36:23', '2025-01-07 11:39:19');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `phone_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `roles`, `phone_number`) VALUES
(1, 'Alice@Alice.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345678),
(2, 'Bob@Bob.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345679),
(3, 'Charlie@Charlie.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345680),
(4, 'David@David.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345681),
(5, 'Eve@Eve.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345682),
(6, 'Frank@Frank.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345683),
(7, 'Grace@Grace.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345684),
(8, 'Heidi@Heidi.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345685),
(9, 'Ivan@Ivan.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345686),
(10, 'Judy@Judy.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345687),
(11, 'Alice@Alice.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345678),
(12, 'Bob@Bob.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345679),
(13, 'Charlie@Charlie.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345680),
(14, 'David@David.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345681),
(15, 'Eve@Eve.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345682),
(16, 'Frank@Frank.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345683),
(17, 'Grace@Grace.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345684),
(18, 'Heidi@Heidi.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345685),
(19, 'Ivan@Ivan.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345686),
(20, 'Judy@Judy.fr', '$2y$13$mSaaFLRc747bDyp87dJmNOPoRaumleJwejZBw4o0hm7MLn5.nMYTS', '[\"ROLE_USER\"]', 612345687),
(22, 'test@test.fr', '$2y$13$0UZ9IEKKSmNBey889Ri/NeUkP9MCsGc.JP0d30mEi4WtThEph5qkW', '[\"ROLE_USER\"]', 1212121212),
(100, 'admin@admin.fr', '$2y$13$gDPra.1HsGMFDDx9hmjZyO5EiC0l03AojrxvE5mu4T48bqVFVBPNe', '[\"ROLE_ADMIN\"]', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `car_pooling_offer`
--
ALTER TABLE `car_pooling_offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_20A5C19661220EA6` (`creator_id`),
  ADD KEY `IDX_20A5C19671F7E88B` (`event_id`);

--
-- Index pour la table `coordinates`
--
ALTER TABLE `coordinates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5387574A158B0682` (`coordinates_id`),
  ADD KEY `IDX_5387574A35E47E35` (`referent_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `car_pooling_offer`
--
ALTER TABLE `car_pooling_offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `coordinates`
--
ALTER TABLE `coordinates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `car_pooling_offer`
--
ALTER TABLE `car_pooling_offer`
  ADD CONSTRAINT `FK_20A5C19661220EA6` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_20A5C19671F7E88B` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`referent_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`coordinates_id`) REFERENCES `coordinates` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
