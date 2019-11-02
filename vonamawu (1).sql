-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 30 oct. 2019 à 07:49
-- Version du serveur :  10.4.8-MariaDB
-- Version de PHP :  7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `vonamawu`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifiant` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isOnline` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `nom`, `prenom`, `contact`, `identifiant`, `password`, `isOnline`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AMADOU', 'Aro', '92942601', 'Aro102', '$2y$10$Loya1lc4bV3R0Xxazf.9aeUBB9k4qAcHYeJP484qjWrDg9LdOwsVS', 1, '2019-10-15 13:43:14', '2019-10-28 17:30:01', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `bilans`
--

CREATE TABLE `bilans` (
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteStock` int(11) NOT NULL DEFAULT 0,
  `quantiteReel` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `connexions`
--

CREATE TABLE `connexions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fonction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entrees`
--

CREATE TABLE `entrees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
  `numero` int(11) NOT NULL,
  `quantiteTotale` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entree_produits`
--

CREATE TABLE `entree_produits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entree_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `magasinier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_admins`
--

CREATE TABLE `gerer_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin1_id` bigint(20) UNSIGNED NOT NULL,
  `admin2_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_categories`
--

CREATE TABLE `gerer_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_entrees`
--

CREATE TABLE `gerer_entrees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `magasinier_id` bigint(20) UNSIGNED NOT NULL,
  `entree_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_fournisseurs`
--

CREATE TABLE `gerer_fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_inventaires`
--

CREATE TABLE `gerer_inventaires` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `inventaire_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_magasiniers`
--

CREATE TABLE `gerer_magasiniers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `magasinier_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_porteurs`
--

CREATE TABLE `gerer_porteurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `porteur_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_produits`
--

CREATE TABLE `gerer_produits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_sorties`
--

CREATE TABLE `gerer_sorties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `magasinier_id` bigint(20) UNSIGNED NOT NULL,
  `sortie_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_vendeurs`
--

CREATE TABLE `gerer_vendeurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `vendeur_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerer_ventes`
--

CREATE TABLE `gerer_ventes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `magasinier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendeur_id` bigint(20) UNSIGNED NOT NULL,
  `vente_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventaires`
--

CREATE TABLE `inventaires` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `motif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isFinished` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire_produits`
--

CREATE TABLE `inventaire_produits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventaire_id` bigint(20) UNSIGNED DEFAULT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `quantiteCompte` int(11) NOT NULL DEFAULT 0,
  `quantiteStock` int(11) NOT NULL,
  `quantiteReel` int(11) NOT NULL,
  `ajust` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `magasiniers`
--

CREATE TABLE `magasiniers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifiant` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isOnline` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_10_11_140933_create_admins_table', 1),
(2, '2019_10_11_142355_create_vendeurs_table', 1),
(3, '2019_10_11_142836_create_magasiniers_table', 1),
(4, '2019_10_11_143051_create_porteurs_table', 1),
(5, '2019_10_11_143218_create_fournisseurs_table', 1),
(6, '2019_10_11_143407_create_categories_table', 1),
(7, '2019_10_11_143619_create_produits_table', 1),
(8, '2019_10_11_143843_create_inventaires_table', 1),
(9, '2019_10_11_144005_create_sorties_table', 1),
(10, '2019_10_11_144155_create_entrees_table', 1),
(11, '2019_10_11_144606_create_gerer_admins_table', 1),
(12, '2019_10_11_144824_create_gerer_vendeurs_table', 1),
(13, '2019_10_11_144929_create_gerer_magasiniers_table', 1),
(14, '2019_10_11_145042_create_gerer_porteurs_table', 1),
(15, '2019_10_11_145136_create_gerer_fournisseurs_table', 1),
(16, '2019_10_11_145940_create_gerer_categories_table', 1),
(17, '2019_10_11_150123_create_gerer_produits_table', 1),
(18, '2019_10_11_150238_create_gerer_inventaires_table', 1),
(19, '2019_10_11_150337_create_gerer_sorties_table', 1),
(20, '2019_10_11_150411_create_gerer_entrees_table', 1),
(21, '2019_10_11_151216_create_ventes_table', 1),
(22, '2019_10_11_151304_create_gerer_ventes_table', 1),
(23, '2019_10_11_151852_create_vente_produits_table', 1),
(24, '2019_10_11_152115_create_sortie_produits_table', 2),
(25, '2019_10_11_152208_create_entree_produits_table', 2),
(26, '2019_10_11_152351_create_inventaire_produits_table', 2),
(27, '2019_10_12_134702_create_connexions_table', 2),
(28, '2019_10_12_134805_create_notifications_table', 2),
(29, '2019_10_24_035353_create_bilans_table', 3),
(30, '2019_10_24_150714_create_vente_porteurs_table', 4),
(31, '2019_10_27_234311_create_valider_entrees_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registrer_id` bigint(20) NOT NULL,
  `codeOperation` tinyint(4) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `urgence` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `porteurs`
--

CREATE TABLE `porteurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteStock` int(11) NOT NULL DEFAULT 0,
  `quantiteCritique` int(11) NOT NULL DEFAULT 0,
  `quantiteReel` int(11) NOT NULL DEFAULT 0,
  `isChecking` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sorties`
--

CREATE TABLE `sorties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero` int(11) NOT NULL,
  `nomClient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactClient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteTotale` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sortie_produits`
--

CREATE TABLE `sortie_produits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sortie_id` bigint(20) UNSIGNED NOT NULL,
  `magasinier_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `valider_entrees`
--

CREATE TABLE `valider_entrees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entree_id` bigint(20) UNSIGNED NOT NULL,
  `codeOperation` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vendeurs`
--

CREATE TABLE `vendeurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifiant` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isOnline` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE `ventes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero` int(11) NOT NULL,
  `nomClient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactClient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteTotale` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) NOT NULL DEFAULT 2,
  `sortie_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vente_porteurs`
--

CREATE TABLE `vente_porteurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vente_id` bigint(20) UNSIGNED NOT NULL,
  `porteur_id` bigint(20) UNSIGNED NOT NULL,
  `isChecked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vente_produits`
--

CREATE TABLE `vente_produits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vente_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendeur_id` bigint(20) UNSIGNED NOT NULL,
  `magasinier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `produit_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sortie_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_contact_unique` (`contact`),
  ADD UNIQUE KEY `admins_identifiant_unique` (`identifiant`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_nom_unique` (`nom`);

--
-- Index pour la table `connexions`
--
ALTER TABLE `connexions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entrees`
--
ALTER TABLE `entrees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entrees_numero_unique` (`numero`);

--
-- Index pour la table `entree_produits`
--
ALTER TABLE `entree_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entree_produits_admin_id_foreign` (`admin_id`),
  ADD KEY `entree_produits_magasinier_id_foreign` (`magasinier_id`),
  ADD KEY `entree_produits_entree_id_foreign` (`entree_id`),
  ADD KEY `entree_produits_produit_id_foreign` (`produit_id`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fournisseurs_contact_unique` (`contact`);

--
-- Index pour la table `gerer_admins`
--
ALTER TABLE `gerer_admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gerer_categories`
--
ALTER TABLE `gerer_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_categories_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_categories_categorie_id_foreign` (`categorie_id`);

--
-- Index pour la table `gerer_entrees`
--
ALTER TABLE `gerer_entrees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_entrees_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_entrees_magasinier_id_foreign` (`magasinier_id`),
  ADD KEY `gerer_entrees_entree_id_foreign` (`entree_id`);

--
-- Index pour la table `gerer_fournisseurs`
--
ALTER TABLE `gerer_fournisseurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_fournisseurs_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_fournisseurs_fournisseur_id_foreign` (`fournisseur_id`);

--
-- Index pour la table `gerer_inventaires`
--
ALTER TABLE `gerer_inventaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_inventaires_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_inventaires_inventaire_id_foreign` (`inventaire_id`);

--
-- Index pour la table `gerer_magasiniers`
--
ALTER TABLE `gerer_magasiniers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_magasiniers_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_magasiniers_magasinier_id_foreign` (`magasinier_id`);

--
-- Index pour la table `gerer_porteurs`
--
ALTER TABLE `gerer_porteurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_porteurs_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_porteurs_porteur_id_foreign` (`porteur_id`);

--
-- Index pour la table `gerer_produits`
--
ALTER TABLE `gerer_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_produits_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_produits_produit_id_foreign` (`produit_id`);

--
-- Index pour la table `gerer_sorties`
--
ALTER TABLE `gerer_sorties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_sorties_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_sorties_magasinier_id_foreign` (`magasinier_id`),
  ADD KEY `gerer_sorties_sortie_id_foreign` (`sortie_id`);

--
-- Index pour la table `gerer_vendeurs`
--
ALTER TABLE `gerer_vendeurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_vendeurs_admin_id_foreign` (`admin_id`),
  ADD KEY `gerer_vendeurs_vendeur_id_foreign` (`vendeur_id`);

--
-- Index pour la table `gerer_ventes`
--
ALTER TABLE `gerer_ventes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gerer_ventes_vendeur_id_foreign` (`vendeur_id`),
  ADD KEY `gerer_ventes_vente_id_foreign` (`vente_id`),
  ADD KEY `erer_ventes_magasinier_id_foreign` (`magasinier_id`);

--
-- Index pour la table `inventaires`
--
ALTER TABLE `inventaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inventaire_produits`
--
ALTER TABLE `inventaire_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventaire_produits_inventaire_id_foreign` (`inventaire_id`),
  ADD KEY `inventaire_produits_produit_id_foreign` (`produit_id`);

--
-- Index pour la table `magasiniers`
--
ALTER TABLE `magasiniers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `magasiniers_contact_unique` (`contact`),
  ADD UNIQUE KEY `magasiniers_identifiant_unique` (`identifiant`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `porteurs`
--
ALTER TABLE `porteurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `porteurs_contact_unique` (`contact`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produits_nom_unique` (`nom`),
  ADD KEY `produits_categorie_id_foreign` (`categorie_id`);

--
-- Index pour la table `sorties`
--
ALTER TABLE `sorties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sorties_numero_unique` (`numero`);

--
-- Index pour la table `sortie_produits`
--
ALTER TABLE `sortie_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sortie_produits_admin_id_foreign` (`admin_id`),
  ADD KEY `sortie_produits_sortie_id_foreign` (`sortie_id`),
  ADD KEY `sortie_produits_magasinier_id_foreign` (`magasinier_id`),
  ADD KEY `sortie_produits_produit_id_foreign` (`produit_id`);

--
-- Index pour la table `valider_entrees`
--
ALTER TABLE `valider_entrees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `valider_entrees_admin_id_foreign` (`admin_id`),
  ADD KEY `valider_entrees_entree_id_foreign` (`entree_id`);

--
-- Index pour la table `vendeurs`
--
ALTER TABLE `vendeurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendeurs_contact_unique` (`contact`),
  ADD UNIQUE KEY `vendeurs_identifiant_unique` (`identifiant`);

--
-- Index pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ventes_numero_unique` (`numero`);

--
-- Index pour la table `vente_porteurs`
--
ALTER TABLE `vente_porteurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vente_porteurs_porteur_id_foreign` (`porteur_id`),
  ADD KEY `vente_porteurs_vente_id_foreign` (`vente_id`);

--
-- Index pour la table `vente_produits`
--
ALTER TABLE `vente_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vente_produits_admin_id_foreign` (`admin_id`),
  ADD KEY `vente_produits_vendeur_id_foreign` (`vendeur_id`),
  ADD KEY `vente_produits_vente_id_foreign` (`vente_id`),
  ADD KEY `vente_produits_produit_id_foreign` (`produit_id`),
  ADD KEY `vente_produits_magasinier_id_foreign` (`magasinier_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `connexions`
--
ALTER TABLE `connexions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entrees`
--
ALTER TABLE `entrees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entree_produits`
--
ALTER TABLE `entree_produits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_admins`
--
ALTER TABLE `gerer_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_categories`
--
ALTER TABLE `gerer_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_entrees`
--
ALTER TABLE `gerer_entrees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_fournisseurs`
--
ALTER TABLE `gerer_fournisseurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_inventaires`
--
ALTER TABLE `gerer_inventaires`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_magasiniers`
--
ALTER TABLE `gerer_magasiniers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_porteurs`
--
ALTER TABLE `gerer_porteurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_produits`
--
ALTER TABLE `gerer_produits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_sorties`
--
ALTER TABLE `gerer_sorties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_vendeurs`
--
ALTER TABLE `gerer_vendeurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerer_ventes`
--
ALTER TABLE `gerer_ventes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventaires`
--
ALTER TABLE `inventaires`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventaire_produits`
--
ALTER TABLE `inventaire_produits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `magasiniers`
--
ALTER TABLE `magasiniers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `porteurs`
--
ALTER TABLE `porteurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sorties`
--
ALTER TABLE `sorties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sortie_produits`
--
ALTER TABLE `sortie_produits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `valider_entrees`
--
ALTER TABLE `valider_entrees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vendeurs`
--
ALTER TABLE `vendeurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vente_porteurs`
--
ALTER TABLE `vente_porteurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vente_produits`
--
ALTER TABLE `vente_produits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `entree_produits`
--
ALTER TABLE `entree_produits`
  ADD CONSTRAINT `entree_produits_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `entree_produits_entree_id_foreign` FOREIGN KEY (`entree_id`) REFERENCES `entrees` (`id`),
  ADD CONSTRAINT `entree_produits_magasinier_id_foreign` FOREIGN KEY (`magasinier_id`) REFERENCES `magasiniers` (`id`),
  ADD CONSTRAINT `entree_produits_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `gerer_categories`
--
ALTER TABLE `gerer_categories`
  ADD CONSTRAINT `gerer_categories_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_categories_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `gerer_entrees`
--
ALTER TABLE `gerer_entrees`
  ADD CONSTRAINT `gerer_entrees_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_entrees_entree_id_foreign` FOREIGN KEY (`entree_id`) REFERENCES `entrees` (`id`),
  ADD CONSTRAINT `gerer_entrees_magasinier_id_foreign` FOREIGN KEY (`magasinier_id`) REFERENCES `magasiniers` (`id`);

--
-- Contraintes pour la table `gerer_fournisseurs`
--
ALTER TABLE `gerer_fournisseurs`
  ADD CONSTRAINT `gerer_fournisseurs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_fournisseurs_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`);

--
-- Contraintes pour la table `gerer_inventaires`
--
ALTER TABLE `gerer_inventaires`
  ADD CONSTRAINT `gerer_inventaires_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_inventaires_inventaire_id_foreign` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaires` (`id`);

--
-- Contraintes pour la table `gerer_magasiniers`
--
ALTER TABLE `gerer_magasiniers`
  ADD CONSTRAINT `gerer_magasiniers_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_magasiniers_magasinier_id_foreign` FOREIGN KEY (`magasinier_id`) REFERENCES `magasiniers` (`id`);

--
-- Contraintes pour la table `gerer_porteurs`
--
ALTER TABLE `gerer_porteurs`
  ADD CONSTRAINT `gerer_porteurs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_porteurs_porteur_id_foreign` FOREIGN KEY (`porteur_id`) REFERENCES `porteurs` (`id`);

--
-- Contraintes pour la table `gerer_produits`
--
ALTER TABLE `gerer_produits`
  ADD CONSTRAINT `gerer_produits_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_produits_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `gerer_sorties`
--
ALTER TABLE `gerer_sorties`
  ADD CONSTRAINT `gerer_sorties_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_sorties_magasinier_id_foreign` FOREIGN KEY (`magasinier_id`) REFERENCES `magasiniers` (`id`),
  ADD CONSTRAINT `gerer_sorties_sortie_id_foreign` FOREIGN KEY (`sortie_id`) REFERENCES `ventes` (`id`);

--
-- Contraintes pour la table `gerer_vendeurs`
--
ALTER TABLE `gerer_vendeurs`
  ADD CONSTRAINT `gerer_vendeurs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `gerer_vendeurs_vendeur_id_foreign` FOREIGN KEY (`vendeur_id`) REFERENCES `vendeurs` (`id`);

--
-- Contraintes pour la table `gerer_ventes`
--
ALTER TABLE `gerer_ventes`
  ADD CONSTRAINT `erer_ventes_magasinier_id_foreign` FOREIGN KEY (`magasinier_id`) REFERENCES `magasiniers` (`id`),
  ADD CONSTRAINT `gerer_ventes_vendeur_id_foreign` FOREIGN KEY (`vendeur_id`) REFERENCES `vendeurs` (`id`),
  ADD CONSTRAINT `gerer_ventes_vente_id_foreign` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`);

--
-- Contraintes pour la table `inventaire_produits`
--
ALTER TABLE `inventaire_produits`
  ADD CONSTRAINT `inventaire_produits_inventaire_id_foreign` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaires` (`id`),
  ADD CONSTRAINT `inventaire_produits_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `sortie_produits`
--
ALTER TABLE `sortie_produits`
  ADD CONSTRAINT `sortie_produits_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `sortie_produits_magasinier_id_foreign` FOREIGN KEY (`magasinier_id`) REFERENCES `magasiniers` (`id`),
  ADD CONSTRAINT `sortie_produits_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `sortie_produits_sortie_id_foreign` FOREIGN KEY (`sortie_id`) REFERENCES `sorties` (`id`);

--
-- Contraintes pour la table `valider_entrees`
--
ALTER TABLE `valider_entrees`
  ADD CONSTRAINT `valider_entrees_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `valider_entrees_entree_id_foreign` FOREIGN KEY (`entree_id`) REFERENCES `entrees` (`id`);

--
-- Contraintes pour la table `vente_porteurs`
--
ALTER TABLE `vente_porteurs`
  ADD CONSTRAINT `vente_porteurs_porteur_id_foreign` FOREIGN KEY (`porteur_id`) REFERENCES `porteurs` (`id`),
  ADD CONSTRAINT `vente_porteurs_vente_id_foreign` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`);

--
-- Contraintes pour la table `vente_produits`
--
ALTER TABLE `vente_produits`
  ADD CONSTRAINT `vente_produits_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `vente_produits_magasinier_id_foreign` FOREIGN KEY (`magasinier_id`) REFERENCES `magasiniers` (`id`),
  ADD CONSTRAINT `vente_produits_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `vente_produits_vendeur_id_foreign` FOREIGN KEY (`vendeur_id`) REFERENCES `vendeurs` (`id`),
  ADD CONSTRAINT `vente_produits_vente_id_foreign` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
