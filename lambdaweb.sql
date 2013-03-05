-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Dim 03 Mars 2013 à 17:44
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `lambdaweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `access_lw`
--

CREATE TABLE IF NOT EXISTS `access_lw` (
  `id_access` int(255) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `can_create` int(255) NOT NULL,
  `can_read` int(255) NOT NULL,
  `can_update` int(255) NOT NULL,
  `can_delete` int(255) NOT NULL,
  PRIMARY KEY (`id_access`),
  UNIQUE KEY `class` (`class`),
  KEY `can_create` (`can_create`),
  KEY `can_read` (`can_read`),
  KEY `can_update` (`can_update`),
  KEY `can_delete` (`can_delete`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `access_lw`
--

INSERT INTO `access_lw` (`id_access`, `class`, `can_create`, `can_read`, `can_update`, `can_delete`) VALUES
(1, 'vehicule', 10, 10, 10, 10),
(2, 'trajet', 10, 10, 10, 10),
(3, 'membre', 9, 10, 8, 2);

-- --------------------------------------------------------

--
-- Structure de la table `appartenira`
--

CREATE TABLE IF NOT EXISTS `appartenira` (
  `id_categorie` int(11) NOT NULL DEFAULT '0',
  `id_photo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_categorie`,`id_photo`),
  KEY `id_photo` (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(255) NOT NULL AUTO_INCREMENT,
  `id_auteur` int(255) NOT NULL,
  `date_creation` date NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `id_cat` int(11) DEFAULT '5',
  `online` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id_article`),
  KEY `id_auteur` (`id_auteur`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`id_article`, `id_auteur`, `date_creation`, `titre`, `contenu`, `id_cat`, `online`) VALUES
(5, 1, '2012-11-03', 'Présentation du projet', '<p>L''application est un poker jouable en ligne par plusieurs joueurs via un navigateur internet.</p>\r\n<p>Les utilisateurs devront tout d''abord s''inscrire puis s''authentifier sur le site, et pourront ensuite créer une partie qui pourra être rejointe par d''autres utilisateurs. \r\nLe nombre requis d''utilisateurs pour commencer une partie devra être modifiable, mais est laissé à quatre joueurs par défaut. \r\nTant que quatres utilisateurs n''ont pas rejoint la partie, celle-ci ne pourra démarrer et affichera alors un message du type "En attente des autres joueurs".</p>\r\n\r\n<p><b>Règles:</b></p>\r\n<p>Pour cette application, nous allons utiliser la variante du poker la plus jouée et la plus connue, le poker « Texas Hold''em ». Cette variant conserve les règles de base du Poker, notamment concernant la valeurs des mains.</p>', 3, 1),
(7, 1, '2012-11-01', 'Article de test', 'Un simple article de test...g', 1, 0),
(8, 1, '2012-11-06', 'Une nouvelle histoire qui commence', 'Voici un article, qui se veut très simple, et donc le but n''est autre que de démontrer qu''il peut y avoir des articles courts, mais longs à la fois.', 3, 0),
(9, 1, '2012-11-27', 'Nouvelle version du poker en ligne !', '<p>Une nouvelle version des sources sont disponibles !\r\nDerniere version en date : v0.2 </p>\r\n<p>\r\n<a href="http://onlinepoker.lambdaweb.fr/uploads/doc/index.html">La javadoc est disponible ici</a></p>\r\n<p><a href="http://onlinepoker.lambdaweb.fr/uploads/poker_27112012.zip">Et l''archive des sources est disponible ici</a></p>', 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `as_diapo`
--

CREATE TABLE IF NOT EXISTS `as_diapo` (
  `id_diapo` int(255) NOT NULL AUTO_INCREMENT,
  `page_diapo` int(255) NOT NULL,
  `content_diapo` longtext NOT NULL,
  `id_presentation` int(255) NOT NULL,
  PRIMARY KEY (`id_diapo`),
  UNIQUE KEY `page_diapo` (`page_diapo`),
  KEY `id_presentation` (`id_presentation`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `as_diapo`
--

INSERT INTO `as_diapo` (`id_diapo`, `page_diapo`, `content_diapo`, `id_presentation`) VALUES
(1, 1, '0', 1),
(2, 2, '0', 1),
(3, 3, '0', 1),
(4, 4, '0', 1),
(5, 5, '0', 1),
(6, 6, '0', 1),
(7, 7, '0', 1);

-- --------------------------------------------------------

--
-- Structure de la table `as_presentation`
--

CREATE TABLE IF NOT EXISTS `as_presentation` (
  `id_presentation` int(255) NOT NULL AUTO_INCREMENT,
  `id_auteur` int(255) NOT NULL,
  `nom_presentation` varchar(255) DEFAULT 'Sans titre',
  `date_creation_presentation` date DEFAULT NULL,
  PRIMARY KEY (`id_presentation`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `as_presentation`
--

INSERT INTO `as_presentation` (`id_presentation`, `id_auteur`, `nom_presentation`, `date_creation_presentation`) VALUES
(1, 0, 'qzd', '2012-11-09'),
(2, 0, 'qzdqzd', '2012-11-09'),
(3, 0, 'ddqd', '2012-11-09'),
(4, 0, 'd', '2012-11-09'),
(5, 0, 'd', '2012-11-09'),
(6, 0, 'd', '2012-11-09'),
(7, 0, 'ma présentation', '2012-11-09');

-- --------------------------------------------------------

--
-- Structure de la table `as_visualisation`
--

CREATE TABLE IF NOT EXISTS `as_visualisation` (
  `id_visualisation` int(255) NOT NULL AUTO_INCREMENT,
  `id_diapo` int(255) NOT NULL,
  `date_visualisation` datetime NOT NULL,
  `code_visualisation` varchar(255) NOT NULL,
  PRIMARY KEY (`id_visualisation`),
  KEY `id_diapo` (`id_diapo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` text NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=123 ;

--
-- Contenu de la table `blog_posts`
--

INSERT INTO `blog_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1, 1, '2011-05-19 16:58:54', '2011-05-19 14:58:54', '<h2>Niark ! Ca y est !</h2>\r\nSite fini, blog fini, pub finie ! Reste plus qu''a attendre la mise en place d''un serveur web définitif  !\r\n\r\n<img class="alignnone" title="Epic Win !" src="http://lambdaweb.free.fr/images/EpicWin.jpg" alt="Epic Win !" width="500" height="620" />', 'End of beginning', '', 'publish', 'open', 'open', '', 'bonjour-tout-le-monde', '', '', '2012-07-01 21:56:50', '2012-07-01 19:56:50', '', 0, 'http://lambdaweb.free.fr/blog/?p=1', 0, 'post', '', 0),
(2, 1, '2011-05-18 16:58:54', '2011-05-18 16:58:54', 'Voici un exemple de page. Elle est différente d''un article de blog, en cela qu''elle restera à la même place, et s''affichera dans le menu de navigation de votre site (en fonction de votre thème). La plupart des gens commencent par écrire une page &laquo;&nbsp;À Propos&nbsp;&raquo; qui les présente aux visiteurs potentiels du site. Vous pourriez y écrire quelque chose de ce tenant&nbsp;:\n\n<blockquote>Bonjour&nbsp;! Je suis un mécanicien qui aspire à devenir un acteur, et ceci est mon blog. J''habite à Bordeaux, j''ai un super chien baptisé Russell, et j''aime la vodka-ananas (ainsi que perdre mon temps à regarder la pluie tomber).</blockquote>\n\n...ou bien quelque chose comme ça&nbsp;:\n\n<blockquote>La société 123 Machin Truc a été créée en 1971, et n''a cessé de proposer au public des machins-trucs de qualité depuis cette année. Située à Saint-Remy-en-Bouzemont-Saint-Genest-et-Isson, 123 Machin Truc emploie 2&nbsp;000 personnes, et fabrique toutes sortes de bidules super pour la communauté bouzemontoise.</blockquote>\n\nEtant donné que vous êtes un nouvel utilisateur de WordPress, vous devriez vous rendre sur votre <a href="http://lambdaweb.free.fr/blog/wp-admin/">tableau de bord</a> pour effacer la présente page, et créer de nouvelles pages avec votre propre contenu. Amusez-vous bien&nbsp;!', 'Page d&rsquo;exemple', '', 'trash', 'open', 'open', '', 'page-d-exemple', '', '', '2011-05-18 20:39:43', '2011-05-18 18:39:43', '', 0, 'http://lambdaweb.free.fr/blog/?page_id=2', 0, 'page', '', 0),
(28, 1, '2011-06-27 02:48:38', '2011-06-27 00:48:38', '<ul>\r\n	<li>SCARY MONSTERS &amp; NICE SPRITES (KASKADE REMIX) - SKRILLEX <a href="http://youtu.be/wvcD4wJ9vZ0">(http://youtu.be/wvcD4wJ9vZ0</a>)</li>\r\n</ul>', 'Liste LWM 2011', '', 'private', 'open', 'open', '', 'liste-lwm-2011', '', '', '2011-09-18 01:59:12', '2011-09-17 23:59:12', '', 0, 'http://lambdaweb.free.fr/blog/?p=28', 0, 'post', '', 0),
(4, 1, '2011-05-18 20:31:40', '2011-05-18 18:31:40', '<h2>Niark ! Ca y est !</h2>\nSite fini, blog fini, pub finie ! Reste plus qu''a attendre la mise en place d''un serveur web définitif  !\n\n<img class="alignnone" title="Epic Win !" src="http://lambdaweb.free.fr/images/EpicWin.jpg" alt="Epic Win !" width="500" height="620" />', 'Yeaaaah !', '', 'inherit', 'open', 'open', '', '1-autosave', '', '', '2011-05-18 20:31:40', '2011-05-18 18:31:40', '', 1, 'http://lambdaweb.free.fr/blog/?p=4', 0, 'revision', '', 0),
(5, 1, '2011-05-18 16:58:54', '2011-05-18 16:58:54', 'Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous&nbsp;!', 'Bonjour tout le monde&nbsp;!', '', 'inherit', 'open', 'open', '', '1-revision', '', '', '2011-05-18 16:58:54', '2011-05-18 16:58:54', '', 1, 'http://lambdaweb.free.fr/blog/?p=5', 0, 'revision', '', 0),
(6, 1, '2011-05-18 16:58:54', '2011-05-18 16:58:54', 'Voici un exemple de page. Elle est différente d''un article de blog, en cela qu''elle restera à la même place, et s''affichera dans le menu de navigation de votre site (en fonction de votre thème). La plupart des gens commencent par écrire une page &laquo;&nbsp;À Propos&nbsp;&raquo; qui les présente aux visiteurs potentiels du site. Vous pourriez y écrire quelque chose de ce tenant&nbsp;:\n\n<blockquote>Bonjour&nbsp;! Je suis un mécanicien qui aspire à devenir un acteur, et ceci est mon blog. J''habite à Bordeaux, j''ai un super chien baptisé Russell, et j''aime la vodka-ananas (ainsi que perdre mon temps à regarder la pluie tomber).</blockquote>\n\n...ou bien quelque chose comme ça&nbsp;:\n\n<blockquote>La société 123 Machin Truc a été créée en 1971, et n''a cessé de proposer au public des machins-trucs de qualité depuis cette année. Située à Saint-Remy-en-Bouzemont-Saint-Genest-et-Isson, 123 Machin Truc emploie 2&nbsp;000 personnes, et fabrique toutes sortes de bidules super pour la communauté bouzemontoise.</blockquote>\n\nEtant donné que vous êtes un nouvel utilisateur de WordPress, vous devriez vous rendre sur votre <a href="http://lambdaweb.free.fr/blog/wp-admin/">tableau de bord</a> pour effacer la présente page, et créer de nouvelles pages avec votre propre contenu. Amusez-vous bien&nbsp;!', 'Page d&rsquo;exemple', '', 'inherit', 'open', 'open', '', '2-revision', '', '', '2011-05-18 16:58:54', '2011-05-18 16:58:54', '', 2, 'http://lambdaweb.free.fr/blog/?p=6', 0, 'revision', '', 0),
(7, 1, '2011-05-18 22:21:44', '0000-00-00 00:00:00', '', 'marivaudages avec la segfault', '', 'draft', 'open', 'open', '', '', '', '', '2011-05-18 22:21:44', '2011-05-18 20:21:44', '', 0, 'http://lambdaweb.free.fr/blog/?page_id=7', 0, 'page', '', 0),
(8, 1, '2011-05-18 22:29:19', '2011-05-18 20:29:19', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li> Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;', 'Marivaudages avec la segfault', '', 'pending', 'open', 'open', '', 'marivaudages-avec-la-segfault', '', '', '2011-05-19 00:17:58', '2011-05-18 22:17:58', '', 0, 'http://lambdaweb.free.fr/blog/?page_id=8', 0, 'page', '', 0),
(9, 1, '2011-05-18 22:28:53', '2011-05-18 20:28:53', '<pre escaped="true" lang="php" line="1">echo ("hello");</pre>', '', '', 'inherit', 'open', 'open', '', '8-revision', '', '', '2011-05-18 22:28:53', '2011-05-18 20:28:53', '', 8, 'http://lambdaweb.free.fr/blog/?p=9', 0, 'revision', '', 0),
(10, 1, '2011-05-18 22:28:54', '2011-05-18 20:28:54', '<pre escaped="true" lang="php" line="1">echo ("hello");</pre>', '', '', 'inherit', 'open', 'open', '', '8-revision-2', '', '', '2011-05-18 22:28:54', '2011-05-18 20:28:54', '', 8, 'http://lambdaweb.free.fr/blog/?p=10', 0, 'revision', '', 0),
(11, 1, '2011-05-18 22:29:02', '2011-05-18 20:29:02', '<pre escaped="true" lang="php" line="1">echo ("hello");</pre>', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-3', '', '', '2011-05-18 22:29:02', '2011-05-18 20:29:02', '', 8, 'http://lambdaweb.free.fr/blog/?p=11', 0, 'revision', '', 0),
(12, 1, '2011-05-18 22:29:06', '2011-05-18 20:29:06', '<pre escaped="true" lang="php" line="1">echo ("hello");</pre>', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-4', '', '', '2011-05-18 22:29:06', '2011-05-18 20:29:06', '', 8, 'http://lambdaweb.free.fr/blog/?p=12', 0, 'revision', '', 0),
(13, 1, '2011-05-18 22:29:14', '2011-05-18 20:29:14', '<pre escaped="true" lang="php" line="1">echo ("hello");</pre>', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-5', '', '', '2011-05-18 22:29:14', '2011-05-18 20:29:14', '', 8, 'http://lambdaweb.free.fr/blog/?p=13', 0, 'revision', '', 0),
(14, 1, '2011-05-19 00:16:10', '2011-05-18 22:16:10', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li> Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;', 'Marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-autosave', '', '', '2011-05-19 00:16:10', '2011-05-18 22:16:10', '', 8, 'http://lambdaweb.free.fr/blog/?p=14', 0, 'revision', '', 0),
(15, 1, '2011-05-18 22:29:19', '2011-05-18 20:29:19', '<pre escaped="true" lang="php" line="1">echo ("hello");</pre>', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-6', '', '', '2011-05-18 22:29:19', '2011-05-18 20:29:19', '', 8, 'http://lambdaweb.free.fr/blog/?p=15', 0, 'revision', '', 0),
(16, 1, '2011-05-18 22:30:41', '2011-05-18 20:30:41', '<pre escaped="true" lang="php" line="1">echo ("hello");</pre>\r\n<pre escaped="true" lang="php" line="1">ca va ?</pre>', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-7', '', '', '2011-05-18 22:30:41', '2011-05-18 20:30:41', '', 8, 'http://lambdaweb.free.fr/blog/?p=16', 0, 'revision', '', 0),
(17, 1, '2011-05-18 22:54:07', '2011-05-18 20:54:07', '<h2 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h2>\r\n<h3 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir un minimum d’acquis en matière de programmation C++.</h3>\r\n<ol>\r\n	<li>- "seg fault ? Jamais entendu parler."</li>\r\n</ol>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\n&nbsp;', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-8', '', '', '2011-05-18 22:54:07', '2011-05-18 20:54:07', '', 8, 'http://lambdaweb.free.fr/blog/?p=17', 0, 'revision', '', 0),
(18, 1, '2011-05-18 23:05:37', '2011-05-18 21:05:37', '<h2 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h2>\r\n<h3 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir un minimum d’acquis en matière de programmation C++.</h3>\r\n<ol>\r\n	<li>- "seg fault ? Jamais entendu parler."</li>\r\n</ol>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<code>Program received signal SIGSEGV, Segmentation fault.</code>\r\n\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n<ul>\r\n	<li>Il est invisible pour le compilateur,</li>\r\n	<li>Il est assez dur a trouver (cette difficulté augmentant exponentiellement avec la taille de votre code)</li>\r\n	<li></li>\r\n</ul>', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-9', '', '', '2011-05-18 23:05:37', '2011-05-18 21:05:37', '', 8, 'http://lambdaweb.free.fr/blog/?p=18', 0, 'revision', '', 0),
(19, 1, '2011-05-18 23:11:14', '2011-05-18 21:11:14', '<h2 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h2>\r\n<h3 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir un minimum d’acquis en matière de programmation C++.</h3>\r\n<ol>\r\n	<li>- "seg fault ? Jamais entendu parler."</li>\r\n</ol>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\n&nbsp;', 'marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-10', '', '', '2011-05-18 23:11:14', '2011-05-18 21:11:14', '', 8, 'http://lambdaweb.free.fr/blog/?p=19', 0, 'revision', '', 0),
(20, 1, '2011-05-18 23:29:26', '2011-05-18 21:29:26', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\n</ol>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire<a title="Mémoire informatique" href="http://fr.wikipedia.org/wiki/M%C3%A9moire_informatique"></a> qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\n<ol></ol>', 'Marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-11', '', '', '2011-05-18 23:29:26', '2011-05-18 21:29:26', '', 8, 'http://lambdaweb.free.fr/blog/?p=20', 0, 'revision', '', 0),
(21, 1, '2011-05-19 00:17:38', '2011-05-18 22:17:38', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li> Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;', 'Marivaudages avec la segfault', '', 'publish', 'open', 'open', '', 'marivaudages-avec-la-segfault', '', '', '2012-05-02 19:27:01', '2012-05-02 17:27:01', '', 0, 'http://lambdaweb.free.fr/blog/?p=21', 0, 'post', '', 0),
(22, 1, '2011-05-19 00:17:16', '2011-05-18 22:17:16', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\n<ol>\n	<li>\n<h4>- "seg fault ? Jamais entendu parler."</h4>\n<ul>\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\n</ul>\n</li>\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\n\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\n\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\n	<li>\n<h4>Et il sort d''ou lui ?</h4>\n</li>\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\nPar exemple :\n<pre escaped="true" lang="c" line="1">#include \n\n int main()\n{\n     int variable_entiere; // la variable n''est pas initialisée\n     scanf("%d", variable_entiere); // on accède a cette variable\n} // -&gt; segfault a 99%</pre>\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\n	<li>\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\n<ul>\n	<li> Les vérification basiques :\n<ul>\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\n{\n    int tableau[4], i = 0;\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\n    {\n        printf("%d\\n", tableau[i]);\n    }\n    return 0;\n}</pre>\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\n</ul>\n</li>\n	<li>Si ca ne marche pas, deux options :\n<ul>\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\n</ul>\n</li>\n</ul>\n</li>\n</ol>\n&nbsp;', 'Marivaudages', '', 'inherit', 'open', 'open', '', '21-revision', '', '', '2011-05-19 00:17:16', '2011-05-18 22:17:16', '', 21, 'http://lambdaweb.free.fr/blog/?p=22', 0, 'revision', '', 0),
(23, 1, '2011-05-19 00:16:14', '2011-05-18 22:16:14', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li> Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;', 'Marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '8-revision-12', '', '', '2011-05-19 00:16:14', '2011-05-18 22:16:14', '', 8, 'http://lambdaweb.free.fr/blog/?p=23', 0, 'revision', '', 0),
(24, 1, '2011-05-19 00:17:38', '2011-05-18 22:17:38', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li> Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;', 'Marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '21-revision-2', '', '', '2011-05-19 00:17:38', '2011-05-18 22:17:38', '', 21, 'http://lambdaweb.free.fr/blog/?p=24', 0, 'revision', '', 0),
(29, 1, '2011-06-27 02:46:58', '2011-06-27 00:46:58', '', 'Brouillon auto', '', 'inherit', 'open', 'open', '', '28-revision', '', '', '2011-06-27 02:46:58', '2011-06-27 00:46:58', '', 28, 'http://lambdaweb.free.fr/blog/?p=29', 0, 'revision', '', 0),
(26, 1, '2011-05-18 20:30:36', '2011-05-18 18:30:36', '<h2>Niark ! Ca y est !</h2>\r\nSite fini, blog fini, pub finie ! Reste plus qu''a attendre la mise en place d''un serveur web définitif  !\r\n\r\n<img class="alignnone" title="Epic Win !" src="http://lambdaweb.free.fr/images/EpicWin.jpg" alt="Epic Win !" width="500" height="620" />', 'Yeaaaah !', '', 'inherit', 'open', 'open', '', '1-revision-2', '', '', '2011-05-18 20:30:36', '2011-05-18 18:30:36', '', 1, 'http://lambdaweb.free.fr/blog/?p=26', 0, 'revision', '', 0),
(27, 1, '2011-05-19 17:37:34', '2011-05-19 15:37:34', '<h2>Niark ! Ca y est !</h2>\r\nSite fini, blog fini, pub finie ! Reste plus qu''a attendre la mise en place d''un serveur web définitif  !\r\n\r\n<img class="alignnone" title="Epic Win !" src="http://lambdaweb.free.fr/images/EpicWin.jpg" alt="Epic Win !" width="500" height="620" />', 'End of beginning', '', 'inherit', 'open', 'open', '', '1-revision-3', '', '', '2011-05-19 17:37:34', '2011-05-19 15:37:34', '', 1, 'http://lambdaweb.free.fr/blog/?p=27', 0, 'revision', '', 0),
(46, 1, '2011-12-27 00:25:20', '2011-12-26 22:25:20', '<ul>\r\n	<li>sabrer une bouteille de champagne avec un sabre</li>\r\n	<li>élever un tigre</li>\r\n	<li>manger du rat, du lézard, du cafard ou d''autres curiosités du même gabarit...</li>\r\n	<li>prendre le bateau (Ferry)</li>\r\n	<li>prendre le bateau (petit bateau)</li>\r\n</ul>', 'Toutes ces petites choses qu''il faut faire avant que...', '', 'private', 'open', 'open', '', 'toutes-ces-petites-choses-quil-faut-faire-avant-que', '', '', '2012-04-02 11:29:14', '2012-04-02 09:29:14', '', 0, 'http://lambdaweb.free.fr/blog/?page_id=46', 0, 'page', '', 0),
(31, 1, '2011-09-18 01:51:30', '2011-09-17 23:51:30', '', 'Simple test, ma foi j''ai le droit après tout non ?', '', 'trash', 'open', 'open', '', 'simple-test-ma-foi-jai-le-droit-apres-tout-non', '', '', '2011-09-18 01:57:04', '2011-09-17 23:57:04', '', 0, 'http://lambdaweb.free.fr/blog/?p=31', 0, 'post', '', 0),
(32, 1, '2011-09-18 01:50:56', '2011-09-17 23:50:56', '', 'Brouillon auto', '', 'inherit', 'open', 'open', '', '31-revision', '', '', '2011-09-18 01:50:56', '2011-09-17 23:50:56', '', 31, 'http://lambdaweb.free.fr/blog/?p=32', 0, 'revision', '', 0),
(33, 1, '2011-09-18 01:51:30', '2011-09-17 23:51:30', '', 'Simple test, ma foi j''ai le droit après tout non ?', '', 'inherit', 'open', 'open', '', '31-revision-2', '', '', '2011-09-18 01:51:30', '2011-09-17 23:51:30', '', 31, 'http://lambdaweb.free.fr/blog/?p=33', 0, 'revision', '', 0),
(34, 1, '2011-06-27 02:48:38', '2011-06-27 00:48:38', '<ul>\r\n	<li>SCARY MONSTERS &amp; NICE SPRITES (KASKADE REMIX) - SKRILLEX <a href="http://youtu.be/wvcD4wJ9vZ0">(http://youtu.be/wvcD4wJ9vZ0</a>)</li>\r\n</ul>', 'Liste LWM 2011', '', 'inherit', 'open', 'open', '', '28-revision-2', '', '', '2011-06-27 02:48:38', '2011-06-27 00:48:38', '', 28, 'http://lambdaweb.free.fr/blog/?p=34', 0, 'revision', '', 0);
INSERT INTO `blog_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(35, 1, '2011-09-14 02:39:06', '2011-09-14 00:39:06', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\r\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\r\nA tous ceux qui liront ce post, si lecteurs il y a [edit : vous êtes déja 314 !] , j''aimerai m''arrêter sur la première pensée qui vous est (ou devrait vous être) naturellement venue à l''esprit :\r\n<blockquote>Mais bordel, pourquoi un mario-like ? C''est le style de jeu le moins original au monde, aucun interêt.\r\n<h4>Oui. Mais je ne parle que du point de vue caméra.</h4>\r\n</blockquote>\r\nJe vais essayer d''expliquer l''idée de base. Je propose de créer un jeu basé (presque) uniquement sur la <strong>coopération</strong>, c''est à dire que chaque joueur incarne un personnage avec des capacités <strong>spécifiques </strong>dans un monde ou on ne peut réussir qu''en <strong>s''entraidant</strong>. Le concept est merveilleux je trouve ! [pleure d''émotion].\r\nL''idée s''arrête la. J''ai évidemment des tonnes d''idées dans la tête, et le tout commence a se préciser, mais je laisse mûrir tout ça encore quelque temps.\r\n\r\nLe problème, c''est que réaliser un jeu vidéo, c''est pas une mince affaire, et seul, c''est impossible.\r\n<h3><strong>Je fais donc un appel a tous les développeurs , graphistes, designers, écrivains (et oui, il y a une histoire quand même !) et a toutes les autres personnes que ça intéresserait !</strong></h3>\r\nA cette heure, il y a encore trop de questions sans réponses, comme :\r\n<ul>\r\n	<li>Pourquoi ?</li>\r\n	<li>À qui s''adresse le jeu ?</li>\r\n	<li>Commercialisation ?</li>\r\n	<li>Fonds disponibles ?</li>\r\n	<li>Type de jeu ?</li>\r\n	<li>Apparence graphique du jeu ?</li>\r\n	<li>Personnel ?</li>\r\n	<li>Rémunération ?</li>\r\n	<li>Temps disponible ?</li>\r\n</ul>\r\n&nbsp;', 'Projet #1', '', 'publish', 'open', 'open', '', 'projet-1', '', '', '2011-09-18 02:40:34', '2011-09-18 00:40:34', '', 0, 'http://lambdaweb.free.fr/blog/?p=35', 0, 'post', '', 1),
(36, 1, '2011-09-18 02:06:07', '2011-09-18 00:06:07', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\n\nProjet de jeu d''arcade', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision', '', '', '2011-09-18 02:06:07', '2011-09-18 00:06:07', '', 35, 'http://lambdaweb.free.fr/blog/?p=36', 0, 'revision', '', 0),
(37, 1, '2011-09-18 02:07:07', '2011-09-18 00:07:07', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\n<h2 style="text-align: center;">En une phrase : <!--more--></h2>', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-2', '', '', '2011-09-18 02:07:07', '2011-09-18 00:07:07', '', 35, 'http://lambdaweb.free.fr/blog/?p=37', 0, 'revision', '', 0),
(38, 1, '2011-09-18 02:12:05', '2011-09-18 00:12:05', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\r\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\r\n<pre escaped="true" lang="php" line="1">&lt;?\r\necho("pourquoi vouloir faire un mario-like ? c''est vu, revu, et re-revu !");\r\n?&gt;</pre>', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-3', '', '', '2011-09-18 02:12:05', '2011-09-18 00:12:05', '', 35, 'http://lambdaweb.free.fr/blog/?p=38', 0, 'revision', '', 0),
(39, 1, '2011-09-18 02:13:28', '2011-09-18 00:13:28', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\r\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\r\n\r\n<blockquote>Mais bordel, pourquoi un mario-like ? c''est vu, vu et revu ! (et re-revu!)</blockquote>\r\n\r\n', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-4', '', '', '2011-09-18 02:13:28', '2011-09-18 00:13:28', '', 35, 'http://lambdaweb.free.fr/blog/?p=39', 0, 'revision', '', 0),
(40, 1, '2011-09-18 02:36:58', '2011-09-18 00:36:58', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\nA tous ceux qui liront ce post, si lecteurs il y a [edit : vous êtes déja 314 !], j''aimerai m''arrêter sur la première pensée qui vous est (ou devrait) naturellement venue à l''esprit :\n<blockquote>Mais bordel, pourquoi un mario-like ? C''est le style de jeu le moins original au monde, aucun interêt.\n<h4>Oui. Mais je ne parle que du point de vue caméra.</h4>\n</blockquote>\nJe vais essayer d''expliquer l''idée de base. Je propose de créer un jeu basé (presque) uniquement sur la coopération, c''est à dire que chaque joueur incarne un personnage avec des capacités spécifiques dans un monde ou on ne peut réussir qu''en s''entraidant. Le concept est merveilleux je trouve ! [pleure d''émotion].\nL''idée s''arrête la. J''ai évidemment des tonnes d''idées dans la tête, et le tout commence a se préciser, mais je laisse mûrir tout ça encore quelque temps.\n\nLe problème, c''est que réaliser un jeu vidéo, c''est pas une mince affaire, et seul, c''est impossible.\n<strong>Je fais donc un appel a tous les développeurs , graphistes, designers, écrivains (et oui, il y a une histoire quand même !) et a toutes les autres personnes que ça intéresserait !</strong>\n\nA cette heure, il y a encore trop de questions sans réponses, comme :\n<ul>\n	<li>Pourquoi ?</li>\n	<li>À qui s''adresse le jeu ?</li>\n	<li>Commercialisation ?</li>\n	<li>Fonds disponibles ?</li>\n	<li>Type de jeu ?</li>\n	<li>Apparence graphique du jeu ?</li>\n	<li>Personnel ?</li>\n	<li>Rémunération ?</li>\n	<li>Temps disponible ?</li>\n</ul>\n&nbsp;', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-5', '', '', '2011-09-18 02:36:58', '2011-09-18 00:36:58', '', 35, 'http://lambdaweb.free.fr/blog/?p=40', 0, 'revision', '', 0),
(41, 1, '2011-09-18 02:37:10', '2011-09-18 00:37:10', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\nA tous ceux qui liront ce post, si lecteurs il y a [edit : vous êtes déja 314 !], j''aimerai m''arrêter sur la première pensée qui vous est (ou devrait) naturellement venue à l''esprit :\n<blockquote>Mais bordel, pourquoi un mario-like ? C''est le style de jeu le moins original au monde, aucun interêt.\n<h4>Oui. Mais je ne parle que du point de vue caméra.</h4>\n</blockquote>\nJe vais essayer d''expliquer l''idée de base. Je propose de créer un jeu basé (presque) uniquement sur la coopération, c''est à dire que chaque joueur incarne un personnage avec des capacités spécifiques dans un monde ou on ne peut réussir qu''en s''entraidant. Le concept est merveilleux je trouve ! [pleure d''émotion].\nL''idée s''arrête la. J''ai évidemment des tonnes d''idées dans la tête, et le tout commence a se préciser, mais je laisse mûrir tout ça encore quelque temps.\n\nLe problème, c''est que réaliser un jeu vidéo, c''est pas une mince affaire, et seul, c''est impossible.\n\n<strong>Je fais donc un appel a tous les développeurs , graphistes, designers, écrivains (et oui, il y a une histoire quand même !) et a toutes les autres personnes que ça intéresserait !</strong>\n\nA cette heure, il y a encore trop de questions sans réponses, comme :\n<ul>\n	<li>Pourquoi ?</li>\n	<li>À qui s''adresse le jeu ?</li>\n	<li>Commercialisation ?</li>\n	<li>Fonds disponibles ?</li>\n	<li>Type de jeu ?</li>\n	<li>Apparence graphique du jeu ?</li>\n	<li>Personnel ?</li>\n	<li>Rémunération ?</li>\n	<li>Temps disponible ?</li>\n</ul>\n&nbsp;', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-6', '', '', '2011-09-18 02:37:10', '2011-09-18 00:37:10', '', 35, 'http://lambdaweb.free.fr/blog/?p=41', 0, 'revision', '', 0),
(42, 1, '2011-09-18 02:37:12', '2011-09-18 00:37:12', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\r\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\r\nA tous ceux qui liront ce post, si lecteurs il y a [edit : vous êtes déja 314 !], j''aimerai m''arrêter sur la première pensée qui vous est (ou devrait) naturellement venue à l''esprit :\r\n<blockquote>Mais bordel, pourquoi un mario-like ? C''est le style de jeu le moins original au monde, aucun interêt.\r\n<h4>Oui. Mais je ne parle que du point de vue caméra.</h4>\r\n</blockquote>\r\nJe vais essayer d''expliquer l''idée de base. Je propose de créer un jeu basé (presque) uniquement sur la coopération, c''est à dire que chaque joueur incarne un personnage avec des capacités spécifiques dans un monde ou on ne peut réussir qu''en s''entraidant. Le concept est merveilleux je trouve ! [pleure d''émotion].\r\nL''idée s''arrête la. J''ai évidemment des tonnes d''idées dans la tête, et le tout commence a se préciser, mais je laisse mûrir tout ça encore quelque temps.\r\n\r\nLe problème, c''est que réaliser un jeu vidéo, c''est pas une mince affaire, et seul, c''est impossible.\r\n\r\n<strong>Je fais donc un appel a tous les développeurs , graphistes, designers, écrivains (et oui, il y a une histoire quand même !) et a toutes les autres personnes que ça intéresserait !</strong>\r\n\r\nA cette heure, il y a encore trop de questions sans réponses, comme :\r\n<ul>\r\n	<li>Pourquoi ?</li>\r\n	<li>À qui s''adresse le jeu ?</li>\r\n	<li>Commercialisation ?</li>\r\n	<li>Fonds disponibles ?</li>\r\n	<li>Type de jeu ?</li>\r\n	<li>Apparence graphique du jeu ?</li>\r\n	<li>Personnel ?</li>\r\n	<li>Rémunération ?</li>\r\n	<li>Temps disponible ?</li>\r\n</ul>\r\n&nbsp;', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-7', '', '', '2011-09-18 02:37:12', '2011-09-18 00:37:12', '', 35, 'http://lambdaweb.free.fr/blog/?p=42', 0, 'revision', '', 0),
(43, 1, '2011-09-18 02:37:47', '2011-09-18 00:37:47', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\r\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\r\nA tous ceux qui liront ce post, si lecteurs il y a [edit : vous êtes déja 314 !] , j''aimerai m''arrêter sur la première pensée qui vous est (ou devrait vous être) naturellement venue à l''esprit :\r\n<blockquote>Mais bordel, pourquoi un mario-like ? C''est le style de jeu le moins original au monde, aucun interêt.\r\n<h4>Oui. Mais je ne parle que du point de vue caméra.</h4>\r\n</blockquote>\r\nJe vais essayer d''expliquer l''idée de base. Je propose de créer un jeu basé (presque) uniquement sur la coopération, c''est à dire que chaque joueur incarne un personnage avec des capacités spécifiques dans un monde ou on ne peut réussir qu''en s''entraidant. Le concept est merveilleux je trouve ! [pleure d''émotion].\r\nL''idée s''arrête la. J''ai évidemment des tonnes d''idées dans la tête, et le tout commence a se préciser, mais je laisse mûrir tout ça encore quelque temps.\r\n\r\nLe problème, c''est que réaliser un jeu vidéo, c''est pas une mince affaire, et seul, c''est impossible.\r\n\r\n<strong>Je fais donc un appel a tous les développeurs , graphistes, designers, écrivains (et oui, il y a une histoire quand même !) et a toutes les autres personnes que ça intéresserait !</strong>\r\n\r\nA cette heure, il y a encore trop de questions sans réponses, comme :\r\n<ul>\r\n	<li>Pourquoi ?</li>\r\n	<li>À qui s''adresse le jeu ?</li>\r\n	<li>Commercialisation ?</li>\r\n	<li>Fonds disponibles ?</li>\r\n	<li>Type de jeu ?</li>\r\n	<li>Apparence graphique du jeu ?</li>\r\n	<li>Personnel ?</li>\r\n	<li>Rémunération ?</li>\r\n	<li>Temps disponible ?</li>\r\n</ul>\r\n&nbsp;', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-8', '', '', '2011-09-18 02:37:47', '2011-09-18 00:37:47', '', 35, 'http://lambdaweb.free.fr/blog/?p=43', 0, 'revision', '', 0),
(44, 1, '2011-09-18 02:38:48', '2011-09-18 00:38:48', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\r\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\r\nA tous ceux qui liront ce post, si lecteurs il y a [edit : vous êtes déja 314 !] , j''aimerai m''arrêter sur la première pensée qui vous est (ou devrait vous être) naturellement venue à l''esprit :\r\n<blockquote>Mais bordel, pourquoi un mario-like ? C''est le style de jeu le moins original au monde, aucun interêt.\r\n<h4>Oui. Mais je ne parle que du point de vue caméra.</h4>\r\n</blockquote>\r\nJe vais essayer d''expliquer l''idée de base. Je propose de créer un jeu basé (presque) uniquement sur la <strong>coopération</strong>, c''est à dire que chaque joueur incarne un personnage avec des capacités <strong>spécifiques </strong>dans un monde ou on ne peut réussir qu''en <strong>s''entraidant</strong>. Le concept est merveilleux je trouve ! [pleure d''émotion].\r\nL''idée s''arrête la. J''ai évidemment des tonnes d''idées dans la tête, et le tout commence a se préciser, mais je laisse mûrir tout ça encore quelque temps.\r\n\r\nLe problème, c''est que réaliser un jeu vidéo, c''est pas une mince affaire, et seul, c''est impossible.\r\n<h3><strong>Je fais donc un appel a tous les développeurs , graphistes, designers, écrivains (et oui, il y a une histoire quand même !) et a toutes les autres personnes que ça intéresserait !</strong></h3>\r\nA cette heure, il y a encore trop de questions sans réponses, comme :\r\n<ul>\r\n	<li>Pourquoi ?</li>\r\n	<li>À qui s''adresse le jeu ?</li>\r\n	<li>Commercialisation ?</li>\r\n	<li>Fonds disponibles ?</li>\r\n	<li>Type de jeu ?</li>\r\n	<li>Apparence graphique du jeu ?</li>\r\n	<li>Personnel ?</li>\r\n	<li>Rémunération ?</li>\r\n	<li>Temps disponible ?</li>\r\n</ul>\r\n&nbsp;', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-9', '', '', '2011-09-18 02:38:48', '2011-09-18 00:38:48', '', 35, 'http://lambdaweb.free.fr/blog/?p=44', 0, 'revision', '', 0),
(45, 1, '2011-09-18 02:39:06', '2011-09-18 00:39:06', 'Tout d''abord, pourquoi #1  ? Simplement parce que par expérience, je sais que c''est ni le premier, ni le dernier projet que j''aimerais réaliser. Pour ce qui est de l''accomplir, c''est une autre histoire.\r\n<h2 style="text-align: center;">En une phrase : Projet de jeu d''arcade [mario-like]</h2>\r\nA tous ceux qui liront ce post, si lecteurs il y a [edit : vous êtes déja 314 !] , j''aimerai m''arrêter sur la première pensée qui vous est (ou devrait vous être) naturellement venue à l''esprit :\r\n<blockquote>Mais bordel, pourquoi un mario-like ? C''est le style de jeu le moins original au monde, aucun interêt.\r\n<h4>Oui. Mais je ne parle que du point de vue caméra.</h4>\r\n</blockquote>\r\nJe vais essayer d''expliquer l''idée de base. Je propose de créer un jeu basé (presque) uniquement sur la <strong>coopération</strong>, c''est à dire que chaque joueur incarne un personnage avec des capacités <strong>spécifiques </strong>dans un monde ou on ne peut réussir qu''en <strong>s''entraidant</strong>. Le concept est merveilleux je trouve ! [pleure d''émotion].\r\nL''idée s''arrête la. J''ai évidemment des tonnes d''idées dans la tête, et le tout commence a se préciser, mais je laisse mûrir tout ça encore quelque temps.\r\n\r\nLe problème, c''est que réaliser un jeu vidéo, c''est pas une mince affaire, et seul, c''est impossible.\r\n<h3><strong>Je fais donc un appel a tous les développeurs , graphistes, designers, écrivains (et oui, il y a une histoire quand même !) et a toutes les autres personnes que ça intéresserait !</strong></h3>\r\nA cette heure, il y a encore trop de questions sans réponses, comme :\r\n<ul>\r\n	<li>Pourquoi ?</li>\r\n	<li>À qui s''adresse le jeu ?</li>\r\n	<li>Commercialisation ?</li>\r\n	<li>Fonds disponibles ?</li>\r\n	<li>Type de jeu ?</li>\r\n	<li>Apparence graphique du jeu ?</li>\r\n	<li>Personnel ?</li>\r\n	<li>Rémunération ?</li>\r\n	<li>Temps disponible ?</li>\r\n</ul>\r\n&nbsp;', 'Projet #1', '', 'inherit', 'open', 'open', '', '35-revision-10', '', '', '2011-09-18 02:39:06', '2011-09-18 00:39:06', '', 35, 'http://lambdaweb.free.fr/blog/?p=45', 0, 'revision', '', 0),
(47, 1, '2011-12-27 00:25:16', '2011-12-26 22:25:16', '<ul>\n	<li>sabrer une bouteille de champagne avec un sabre</li>\n	<li>élever un tigre</li>\n	<li>manger du rat, du lézard, du cafard ou d''autres curiosités du même gabarit...</li>\n	<li>prendre le bateau (Ferry)</li>\n	<li>prendre le bateau (petit bateau)</li>\n</ul>', 'Toutes ces petites choses qu''il faut faire avant que...', '', 'inherit', 'open', 'open', '', '46-revision', '', '', '2011-12-27 00:25:16', '2011-12-26 22:25:16', '', 46, 'http://lambdaweb.free.fr/blog/?p=47', 0, 'revision', '', 0),
(48, 1, '2011-12-27 00:26:26', '2011-12-26 22:26:26', '<ul>\n	<li>sabrer une bouteille de champagne avec un sabre</li>\n	<li>élever un tigre</li>\n	<li>manger du rat, du lézard, du cafard ou d''autres curiosités du même gabarit...</li>\n	<li>prendre le bateau (Ferry)</li>\n	<li>prendre le bateau (petit bateau)</li>\n</ul>', 'Toutes ces petites choses qu''il faut faire avant que...', '', 'inherit', 'open', 'open', '', '46-autosave', '', '', '2011-12-27 00:26:26', '2011-12-26 22:26:26', '', 46, 'http://lambdaweb.free.fr/blog/?p=48', 0, 'revision', '', 0),
(49, 1, '2011-12-27 00:25:20', '2011-12-26 22:25:20', '<ul>\r\n	<li>sabrer une bouteille de champagne avec un sabre</li>\r\n	<li>élever un tigre</li>\r\n	<li>manger du rat, du lézard, du cafard ou d''autres curiosités du même gabarit...</li>\r\n	<li>prendre le bateau (Ferry)</li>\r\n	<li>prendre le bateau (petit bateau)</li>\r\n</ul>', 'Toutes ces petites choses qu''il faut faire avant que...', '', 'inherit', 'open', 'open', '', '46-revision-2', '', '', '2011-12-27 00:25:20', '2011-12-26 22:25:20', '', 46, 'http://lambdaweb.free.fr/blog/?p=49', 0, 'revision', '', 0),
(78, 1, '2012-04-11 20:19:24', '2012-04-11 18:19:24', 'Aujourd''hui fut une journée d''essais et d''échecs.\r\n\r\nFamy m''a expliqué le cahier des charges, le projet m''a l''air colossal et semble diverger de ce qui était initialement prévu. Il faudrait maintenant développer une application graphique en python permettant à un enseignant en gestion de créer des entreprises d''exercice pour ses élèves, et qu''il puisse ensuite créer et gérer plusieurs travaux à faire par ces derniers. Un suivi des élèves et un système de restauration des données doit également être mis en place. La concurrence est rude puisque elle possède déjà une telle application.\r\n\r\nJe précise aussi que le tout marchera avec l''ERP openErp, of course. J''ai donc essayé d''installer pyQt sur Linux, et j''ai pris la dernière version disponible sur les dépots, ainsi que les dernières versions de ses dépendances (SIP, QScintilla etc...).\r\n\r\nMa version de Qt étant trop vieille, j''ai essayé l''installation avec mon installation de Qt mise a jour depuis les dépôts officiels Nokia. Les librairies Qt n''étant pas compilées dynamiquement, j''ai du générer le MakeFile pour SIP avec l''option <code>--static</code>. Le tout ne marche pas, SIP n''est pas a la bonne version. J''ai ensuite installé pyQt et SIP depuis les dépôts Ubuntu, mais l''interpréteur python ne reconnait pas le module PyQt lors de l''importation :\r\n<pre escaped="true" lang="python" line="1"> <span style="color: #c0c0c0;">import </span><span style="color: #99cc00;">PyQt4</span>.<span style="color: #99cc00;">QtCore </span></pre>\r\nJ''ai donc essayé de faire marcher le tout sous Windows. Je savais que ça marchait, parce que j''avais déjà fait une petite application en interface graphique Qt pour générer un binaire depuis une source python. J''ai donc essayé de faire une petite application graphique pour insérer un nouveau produit dans ma base de données openErp (complété du module Products). Le client web ne veut pas se lancer, ou du moins, mon navigateur me donne une erreur 403 (forbidden). Flute ! La console m''indique que le module AutoReload ne s''initialise pas. Heureusement que le client graphique marche quand même.\r\n\r\nJe lance mon application fraîchement crée, et lors du clic sur le bouton "ajouter utilisateur", la fenêtre se bloque. Pas un message dans la console, rien. Après plusieurs tentatives, plusieurs modifications, toujours le même résultat. Le socket XMLRPC semble bloqué, et après une batterie de tests (ça ne vient pas du port, pas du serveur, pas d''un fichier de configuration, pas d''une autre application que bloque le port, pas d''une erreur de table, pas d''une boucle, pas d''une mauvaise condition, pas d''une faute d''orthographe, pas d''une malédiction vaudou, hindoue, chamane ou satanique), je constate que même le code XMLRPC basique de référence donné par la documentation officielle OpenErp ne marche pas.\r\n\r\nJe reviens alors sous Windows et j''essaie d''effectuer une purge totale des paquets SIP et PyQt, puis d''effacer le tout manuellement. Rien à faire.\r\n\r\nJe reprendrais demain, mais j''ai le sentiment que je n''avance pas.', '10 avril 2012', '', 'publish', 'open', 'open', '', '10-avril-2012', '', '', '2012-04-11 21:18:21', '2012-04-11 19:18:21', '', 0, 'http://lambdaweb.free.fr/blog/?p=78', 0, 'post', '', 0),
(51, 1, '2012-04-02 11:27:50', '2012-04-02 09:27:50', 'Début du stage.\r\n\r\nJe suis arrivé à 9h30, comme indiqué dans le mail de Famy. Je suis allé voir Jean Luc Rey de la plateforme PRISM au deuxième étgae, qui m''a donné une salle a temps pleins, la A18, dans laquelle je me suis installé. J''ai découvert que mon ubuntu était mort, donc je me suis mis sur une machine virtuelle sous windows. Je ne sais pas encore si je vais switcher pour windowsw ou si je vais me tarabiscoter a installer un nouveau linux.\r\n\r\nJe lis la doc de OpenErp. Avant de me mettre à apprendre python, je voudrais installer et essayer cette plateforme. J''ai donc installé PosgresSql 8.4, OpenErp et PgAdmin sous forme de paquets Debian.\r\n\r\nAprès plusieurs tentatives, je me perds dans les comptes utilisateurs openerp, postgres et je confonds tous les mots de passe. Le wifi très instable ne m''aide pas.\r\n\r\nJe choisis d''installer Openerp sous Windows, au moins pour tester, et je ramènerai ma machine demain. Ca marche, et je crée mon première module en modifiant celui de google maps. Ça ne marche pas, car OpenErp ne détecte pas mon module dans sa liste.\r\n\r\nWilliam Famy arrive, je lui explique mon problème et il me dit que je suis sur la "nouvelle" version de Openerp, la 6.1, et qu''il ne la connait pas. Il m''explique comment faire pour rafraîchir les modules dans la version 6.0, car le fait de relancer le serveur ne rafraîchit rien du tout. Il faut soit passer par le client web, soit donner un argument lors du lancement du serveur en ligne de commande.\r\n\r\nEnsuite il m''explique comment marche la création de modules, j''avoue être un peu perdu mais ça doit être normal, j''ai encore le temps d''apprendre.\r\n\r\nIl m''a aussi expliqué que je devrais sans doute développer un client en python pour permettre à des enseignants de se connecter a un serveur OpenErp et de pouvoir gérer leurs petites affaires, ce qui ne devrait pas poser de problèmes..', '2 avril 2012', '', 'publish', 'open', 'open', '', '2-avril-2012', '', '', '2012-04-03 11:47:05', '2012-04-03 09:47:05', '', 0, 'http://lambdaweb.free.fr/blog/?p=51', 0, 'post', '', 0),
(52, 1, '2012-04-02 11:27:02', '2012-04-02 09:27:02', 'Début du stage.\n\nJe suis arrivé à 9h30, comme indiqué dans le mail de Famy. Je suis allé voir Jean Luc Rey de la plateforme PRISM au deuxième étgae, qui m''a donné une salle a temps pleins, la A18, dans laquelle je me suis installé. J''ai découvert que mon ubuntu était mort, donc je me suis mis sur une machine virtuelle sous windows. Je ne sais pas encore si je vais switcher pour windowsw ou si je vais me tarabiscoter a installer un nouveau linux.\n\nJe lis la doc de OpenErp. Avant de me mettre à apprendre python, je voudrais installer et essayer cette plateforme. J''ai donc installé PosgresSql 8.4, OpenErp et PgAdmin sous forme de paquets Debian.', '2 avril 2012', '', 'inherit', 'open', 'open', '', '51-revision', '', '', '2012-04-02 11:27:02', '2012-04-02 09:27:02', '', 51, 'http://lambdaweb.free.fr/blog/?p=52', 0, 'revision', '', 0),
(53, 1, '2012-04-03 11:46:15', '2012-04-03 09:46:15', 'Début du stage.\n\nJe suis arrivé à 9h30, comme indiqué dans le mail de Famy. Je suis allé voir Jean Luc Rey de la plateforme PRISM au deuxième étgae, qui m''a donné une salle a temps pleins, la A18, dans laquelle je me suis installé. J''ai découvert que mon ubuntu était mort, donc je me suis mis sur une machine virtuelle sous windows. Je ne sais pas encore si je vais switcher pour windowsw ou si je vais me tarabiscoter a installer un nouveau linux.\n\nJe lis la doc de OpenErp. Avant de me mettre à apprendre python, je voudrais installer et essayer cette plateforme. J''ai donc installé PosgresSql 8.4, OpenErp et PgAdmin sous forme de paquets Debian.\n\nAprès plusieurs tentatives, je me perds dans les comptes utilisateurs openerp, postgres et je confonds tous les mots de passe. Le wifi très instable ne m''aide pas.\n\nJe choisis d''installer Openerp sous Windows, au moins pour tester, et je ramènerai ma machine demain. Ca marche, et je crée mon première module en modifiant celui de google maps. Ça ne marche pas, car OpenErp ne détecte pas mon module dans sa liste.\n\nWilliam Famy arrive, je lui explique mon problème et il me dit que je suis sur la "nouvelle" version de Openerp, la 6.1, et qu''il ne la connait pas. Il m''explique comment faire pour rafraîchir les modules dans la version 6.0, car le fait de relancer le serveur ne rafraîchit rien du tout. Il faut soit passer par le client web, soit donner un argument lors du lancement du serveur en ligne de commande.\n\nEnsuite il m''explique comment marche la création de modules, j''avoue être un peu perdu mais ça doit être normal, j''ai encore le temps d''apprendre.\n\nIl m''a aussi expliqué que je devrais sans doute développer un client en python pour permettre à des enseignants de se connecter a un serveur openErp et de pouvoir gé', '2 avril 2012', '', 'inherit', 'open', 'open', '', '51-autosave', '', '', '2012-04-03 11:46:15', '2012-04-03 09:46:15', '', 51, 'http://lambdaweb.free.fr/blog/?p=53', 0, 'revision', '', 0),
(54, 1, '2012-02-11 11:13:35', '2012-02-11 09:13:35', '<ul>\n	<li>sabrer une bouteille de champagne avec un sabre</li>\n	<li>élever un tigre</li>\n	<li>manger du rat, du lézard, du cafard ou d''autres curiosités du même gabarit...</li>\n	<li>prendre le bateau (Ferry)</li>\n	<li>prendre le bateau (petit bateau)</li>\n</ul>', 'Toutes ces petites choses qu''il faut faire avant que...', '', 'inherit', 'open', 'open', '', '46-revision-3', '', '', '2012-02-11 11:13:35', '2012-02-11 09:13:35', '', 46, 'http://lambdaweb.free.fr/blog/?p=54', 0, 'revision', '', 0),
(55, 1, '2012-04-02 11:27:50', '2012-04-02 09:27:50', 'Début du stage.\r\n\r\nJe suis arrivé à 9h30, comme indiqué dans le mail de Famy. Je suis allé voir Jean Luc Rey de la plateforme PRISM au deuxième étgae, qui m''a donné une salle a temps pleins, la A18, dans laquelle je me suis installé. J''ai découvert que mon ubuntu était mort, donc je me suis mis sur une machine virtuelle sous windows. Je ne sais pas encore si je vais switcher pour windowsw ou si je vais me tarabiscoter a installer un nouveau linux.\r\n\r\nJe lis la doc de OpenErp. Avant de me mettre à apprendre python, je voudrais installer et essayer cette plateforme. J''ai donc installé PosgresSql 8.4, OpenErp et PgAdmin sous forme de paquets Debian.', '2 avril 2012', '', 'inherit', 'open', 'open', '', '51-revision-2', '', '', '2012-04-02 11:27:50', '2012-04-02 09:27:50', '', 51, 'http://lambdaweb.free.fr/blog/?p=55', 0, 'revision', '', 0),
(56, 1, '2012-04-03 11:56:13', '2012-04-03 09:56:13', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. Une fois cela modifié, le liste s''affiche correctement. Je sélectionne donc mon module dans la liste des modules précédemment mise a jour, et j''obtiens une autre erreur, encore plus étrange :\r\n\r\n<code>\r\nTraceback (most recent call last): </code>\r\n\r\n<code> </code>\r\n\r\n<code>File "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch  result = ExportService.getService(service_name).dispatch(method, auth, params)\r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch    res = fn(db, uid, *params)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper    return f(self, dbname, *args, **kwargs)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute    res = self.execute_cr(cr, uid, obj, method, *args, **kw)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr    return getattr(object, method)(cr, uid, *args, **kw)\r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write    self.datas[object_id].update(vals2)\r\nKeyError: 1</code>\r\n\r\nA ce stade là, j''essaie alors toutes les solutions envisageables.\r\n<ul>\r\n	<li>Je regarde sur internet : rien, enfin rien de compréhensible. J''ai l''impression d''être le seul a parler français.</li>\r\n	<li>Je me penche sur le système d''internationalisation, qui semble récupérer toutes les phrases présentes dans les fichiers et proposer une traduction dans la langue du fichier d''inter choisi, ça aurait pu venir du fait qu''il n''arrivait pas à internationaliser le titre de l''application puisque je l''ai modifié, mais ce n''est pas ça.</li>\r\n	<li>Les fichiers auraient ils des dépendances non résolues, ou des liens dépendant du nom du module, qui une fois modifiés ne marchent plus ? Non plus.</li>\r\n	<li>J''en ai conclu que ça ne pouvait venir que du numéro de série invalide, qui bloque toute installation.</li>\r\n</ul>\r\nFlûte ! Et les développeurs, ils font comment pour tester des modules ? Ils achète un numéro de série valide pour s''assurer que leur propre code ne contient pas de virus ? de produits malveillants ? ou même de gros mots ?\r\n\r\nJ''ai donc décidé de modifier directement un add-on existant, et si je fait des bêtises, tant pis. J''ai choisi le module <strong>Idea</strong>, car il est mieux documenté que les autres modules et possède une structure très complète. J''ai lu l''arbre XML, décomposé le code,apporté quelques modifications et essayé de comprendre quelle partie du code correspondait à quel endroit dans l''interface graphique. Ce fut un lamentable échec, encore une fois. Je n''arrive pas, en mettant côte à côte mon code xml et ma fenêtre, à comprendre qu''est-ce qui représente quoi.\r\n\r\nAu final j''ai quand même réussi a rajouter un bouton, mais pas à modifier un texte. Je verrai ça demain.', '3 avril 2012', '', 'publish', 'open', 'open', '', '3-avril-2012', '', '', '2012-04-04 10:27:46', '2012-04-04 08:27:46', '', 0, 'http://lambdaweb.free.fr/blog/?p=56', 0, 'post', '', 0),
(57, 1, '2012-04-03 11:55:23', '2012-04-03 09:55:23', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\n\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\n\nEnsuite j''ai configuré le serveur postgres', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision', '', '', '2012-04-03 11:55:23', '2012-04-03 09:55:23', '', 56, 'http://lambdaweb.free.fr/blog/?p=57', 0, 'revision', '', 0),
(58, 1, '2012-04-03 16:09:46', '2012-04-03 14:09:46', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\n\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\n\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\n\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\n\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\n\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\n\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. Une fois cela modifié, le liste s''affiche correctement. Je sélectionne donc mon module dans la liste des modules précédemment mise a jour, et j''obtiens une autre erreur, encore plus étrange :\n\n<code>\nTraceback (most recent call last): </code>\n\n<code> </code>\n\n<code>File "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch  result = ExportService.getService(service_name).dispatch(method, auth, params)\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch    res = fn(db, uid, *params)\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper    return f(self, dbname, *args, **kwargs)\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute    res = self.execute_cr(cr, uid, obj, method, *args, **kw)\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr    return getattr(object, method)(cr, uid, *args, **kw)\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write    self.datas[object_id].update(vals2)\nKeyError: 1</code>\n\nA ce stade là, j''essaie alors toutes les solutions envisageables.\n<ul>\n	<li>Je regarde sur internet : rien, enfin rien de compréhensible. J''ai l''impression d''être le seul a parler français.</li>\n	<li>Je me penche sur le système d''internationalisation, qui semble récupérer toutes les phrases présentes dans les fichiers et proposer une traduction dans la langue du fichier d''inter choisi, ça aurait pu venir du fait qu''il n''arrivait pas à internationaliser le titre de l''application puisque je l''ai modifié, mais ce n''est pas ça.</li>\n	<li>Les fichiers auraient ils des dépendances non résolues, ou des liens dépendant du nom du module, qui une fois modifiés ne marchent plus ? Non plus.</li>\n	<li>J''en ai conclu que ça ne pouvait venir que du numéro de série invalide, qui bloque toute installation.</li>\n</ul>\nFlûte ! Et les développeurs, ils font comment pour tester des modules ? Ils achète un numéro de série valide pour s''assurer que leur propre code ne contient pas de virus ? de produits malveillants ? ou même de gros mots ?\n\nJ''ai donc décidé de modifier directement un add-on existant, et si je fait des bêtises, tant pis. J''ai choisi le module <strong>Idea</strong>, car il est mieux documenté que les autres modules et possède une structure très complète. J''ai lu l''arbre XML, décomposé le code,apporté quelques modifications et essayé de comprendre quelle partie du code correspondait à quel endroit dans l''interface graphique. Ce fut un lamentable échec, encore une fois. Je n''arrive pas, en mettant côte à côte mon code xml et ma fenêtre, à comprendre qu''est-ce qui représente quoi.\n\nAu final j''ai quand même réussi a rajouter un bouton, mais pas à modifier un texte. Je verrai ça demain.', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-autosave', '', '', '2012-04-03 16:09:46', '2012-04-03 14:09:46', '', 56, 'http://lambdaweb.free.fr/blog/?p=58', 0, 'revision', '', 0),
(59, 1, '2012-04-03 11:56:13', '2012-04-03 09:56:13', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-2', '', '', '2012-04-03 11:56:13', '2012-04-03 09:56:13', '', 56, 'http://lambdaweb.free.fr/blog/?p=59', 0, 'revision', '', 0),
(60, 1, '2012-04-03 11:57:00', '2012-04-03 09:57:00', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-3', '', '', '2012-04-03 11:57:00', '2012-04-03 09:57:00', '', 56, 'http://lambdaweb.free.fr/blog/?p=60', 0, 'revision', '', 0),
(61, 1, '2012-04-03 15:19:55', '2012-04-03 13:19:55', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. En modifiant ce certificat, j''obtiens une autre erreur, lors de l''installation du module lui même qui a bien été détecté et rajouté à la liste des modules :\r\n\r\n<code>\r\nTraceback (most recent call last):  \r\n<!--more-->\r\nFile "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch    result = ExportService.getService(service_name).dispatch(method, auth, params)  \r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch    res = fn(db, uid, *params)  \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper    return f(self, dbname, *args, **kwargs) \r\n File "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute    res = self.execute_cr(cr, uid, obj, method, *args, **kw)  \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr    return getattr(object, method)(cr, uid, *args, **kw)  \r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write    self.datas[object_id].update(vals2)KeyError: 1</code>', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-4', '', '', '2012-04-03 15:19:55', '2012-04-03 13:19:55', '', 56, 'http://lambdaweb.free.fr/blog/?p=61', 0, 'revision', '', 0),
(62, 1, '2012-04-03 15:21:15', '2012-04-03 13:21:15', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. En modifiant ce certificat, j''obtiens une autre erreur, lors de l''installation du module lui même qui a bien été détecté et rajouté à la liste des modules :\r\n\r\n<pre escaped="true" lang="python" line="1">\r\nTraceback (most recent call last):  \r\n<!--more-->\r\nFile "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch    result = ExportService.getService(service_name).dispatch(method, auth, params)  \r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch    res = fn(db, uid, *params)  \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper    return f(self, dbname, *args, **kwargs) \r\n File "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute    res = self.execute_cr(cr, uid, obj, method, *args, **kw)  \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr    return getattr(object, method)(cr, uid, *args, **kw)  \r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write    self.datas[object_id].update(vals2)KeyError: 1</pre>', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-5', '', '', '2012-04-03 15:21:15', '2012-04-03 13:21:15', '', 56, 'http://lambdaweb.free.fr/blog/?p=62', 0, 'revision', '', 0);
INSERT INTO `blog_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(63, 1, '2012-04-03 15:23:13', '2012-04-03 13:23:13', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. En modifiant ce certificat, j''obtiens une autre erreur, lors de l''installation du module lui même qui a bien été détecté et rajouté à la liste des modules :\r\n<pre escaped="true" lang="python">Traceback (most recent call last):  \r\n\r\nFile "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch  result = ExportService.getService(service_name).dispatch(method, auth, params)\r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch    res = fn(db, uid, *params)  \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper    return f(self, dbname, *args, **kwargs) \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute    res = self.execute_cr(cr, uid, obj, method, *args, **kw)  \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr    return getattr(object, method)(cr, uid, *args, **kw)  \r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write    self.datas[object_id].update(vals2)KeyError: 1</pre>', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-6', '', '', '2012-04-03 15:23:13', '2012-04-03 13:23:13', '', 56, 'http://lambdaweb.free.fr/blog/?p=63', 0, 'revision', '', 0),
(64, 1, '2012-04-03 15:23:48', '2012-04-03 13:23:48', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. En modifiant ce certificat, j''obtiens une autre erreur, lors de l''installation du module lui même qui a bien été détecté et rajouté à la liste des modules :\r\n\r\n<pre escaped="true" lang="bash" line="1">\r\nTraceback (most recent call last):&nbsp; \r\n\r\nFile "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch  result = ExportService.getService(service_name).dispatch(method, auth, params)\r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch&nbsp; &nbsp; res = fn(db, uid, *params)&nbsp; \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper&nbsp; &nbsp; return f(self, dbname, *args, **kwargs)&nbsp;\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute&nbsp; &nbsp; res = self.execute_cr(cr, uid, obj, method, *args, **kw)&nbsp; \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr&nbsp; &nbsp; return getattr(object, method)(cr, uid, *args, **kw)&nbsp; \r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write&nbsp; &nbsp; self.datas[object_id].update(vals2)\r\nKeyError: 1</pre>', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-7', '', '', '2012-04-03 15:23:48', '2012-04-03 13:23:48', '', 56, 'http://lambdaweb.free.fr/blog/?p=64', 0, 'revision', '', 0),
(65, 1, '2012-04-03 15:24:27', '2012-04-03 13:24:27', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. En modifiant ce certificat, j''obtiens une autre erreur, lors de l''installation du module lui même qui a bien été détecté et rajouté à la liste des modules :\r\n\r\n<code>\r\nTraceback (most recent call last):&nbsp; \r\n\r\nFile "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch  result = ExportService.getService(service_name).dispatch(method, auth, params)\r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch&nbsp; &nbsp; res = fn(db, uid, *params)&nbsp; \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper&nbsp; &nbsp; return f(self, dbname, *args, **kwargs)&nbsp;\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute&nbsp; &nbsp; res = self.execute_cr(cr, uid, obj, method, *args, **kw)&nbsp; \r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr&nbsp; &nbsp; return getattr(object, method)(cr, uid, *args, **kw)&nbsp; \r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write&nbsp; &nbsp; self.datas[object_id].update(vals2)\r\nKeyError: 1</code>', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-8', '', '', '2012-04-03 15:24:27', '2012-04-03 13:24:27', '', 56, 'http://lambdaweb.free.fr/blog/?p=65', 0, 'revision', '', 0),
(66, 1, '2012-04-03 15:39:47', '2012-04-03 13:39:47', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. Une fois cela modifié, le liste s''affiche correctement. Je sélectionne donc mon module dans la liste des modules précédemment mise a jour, et j''obtiens une autre erreur, encore plus étrange :\r\n\r\n<code>\r\nTraceback (most recent call last): </code>\r\n\r\n<code> </code>\r\n\r\n<code>File "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch  result = ExportService.getService(service_name).dispatch(method, auth, params)\r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch    res = fn(db, uid, *params)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper    return f(self, dbname, *args, **kwargs)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute    res = self.execute_cr(cr, uid, obj, method, *args, **kw)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr    return getattr(object, method)(cr, uid, *args, **kw)\r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write    self.datas[object_id].update(vals2)\r\nKeyError: 1</code>\r\n\r\nA ce stade là, j''essaie alors toutes les solutions envisageables.\r\n<ul>\r\n	<li>Je regarde sur internet : rien, enfin rien de compréhensible. J''ai l''impression d''être le seul a parler français.</li>\r\n	<li>Je me penche sur le système d''internationalisation, qui semble récupérer toutes les phrases présentes dans les fichiers et proposer une traduction dans la langue du fichier d''inter choisi, ça aurait pu venir du fait qu''il n''arrivait pas à internationaliser le titre de l''application puisque je l''ai modifié, mais ce n''est pas ça.</li>\r\n	<li>Les fichiers auraient ils des dépendances non résolues, ou des liens dépendant du nom du module, qui une fois modifiés ne marchent plus ? Non plus.</li>\r\n	<li>J''en ai conclu que ça ne pouvait venir que du numéro de série invalide, qui bloque toute installation.</li>\r\n</ul>\r\nFlûte ! Et les développeurs, ils font comment pour tester des modules ? Ils achète un numéro de série valide pour s''assurer que leur propre code ne contient pas de virus ? de produits malveillants ? ou même de gros mots ?\r\n\r\nJ''ai donc décidé de modifier directement un add-on existant, et si je fait des bêtises, tant pis. J''ai choisi le module <strong>Idea</strong>, car il est mieux documenté que les autres modules et possède une structure très complète. J''ai lu l''arbre XML, décomposé le code,apporté quelques modifications et essayé de comprendre quelle partie du code correspondait à quel endroit dans l''interface graphique. Ce fut un lamentable échec, encore une fois. Je n''arrive pas, en mettant côte à côte mon code xml et ma fenêtre, à comprendre qu''est-ce qui représente quoi.', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-9', '', '', '2012-04-03 15:39:47', '2012-04-03 13:39:47', '', 56, 'http://lambdaweb.free.fr/blog/?p=66', 0, 'revision', '', 0),
(67, 1, '2012-04-03 16:08:42', '2012-04-03 14:08:42', 'J''arrive en retard, j''ai demandé a Raphaël de m''aider à amener mon pc (Pat) à l''IUT. On arrive sur place à 10h, et une fois mon nouveau poste installé, je vais voir David Boyer pour qu''il m''ouvre une connexion réseau.\r\n\r\nUne fois le reseau configuré, je télécharge le client et le serveur openErp, postgreSql 8.4 (Famy m''a dit de prendre au moins la 8.1) et Python 2.65 (je ne me suis pas senti de compiler la 2.7 a la main, donc j''ai pris la version dispo dans les dépôts).\r\n\r\nEnsuite j''ai configuré le serveur postgres en ajoutant un user openErp, et j''ai lancé le client. On me demande de créer une entreprise, j''en fais une bidon et j''ajoute quelques modules de base pour commencer. L''install est vachement longue...\r\n\r\nÇa y est, tout est installé. C''est la première fois que je vois le client graphique, et pas le client web. Apparemment il a l''air d''être exactement comme celui sur navigateur. C''est bien fait.\r\n\r\nÇa me fait penser que j''ai pas installé le client web, donc je le fait, puis j''attaque mon premier module en 6.0.\r\n\r\nJe trouve pas le répertoire d''installation, en farfouillant un peu, je constate que tout est dans "<code>/usr/share/pyshared/openerp-server</code>" et je récupère le module google maps.\r\n\r\nJe crée un clone de google maps en ne modifiant que le nom du module, pour le différencier. Dans le client web (que j''ai aussi mis du temps à trouver sur mon localhost à cause du port 8080 qu''il fallait mettre à la fin de l''url) je rafraîchit la liste des modules et je me prends une erreur en pleine figure : <code>ERREUR: la valeur d''une clé dupliquée rompt la contrainte unique « ir_module_module_certificate_uniq »</code>. J''en ai déduit que j''avais utilisé la même clé de certificat que le module copié, puisque je n''avais touché qu''au nom du module. Une fois cela modifié, le liste s''affiche correctement. Je sélectionne donc mon module dans la liste des modules précédemment mise a jour, et j''obtiens une autre erreur, encore plus étrange :\r\n\r\n<code>\r\nTraceback (most recent call last): </code>\r\n\r\n<code> </code>\r\n\r\n<code>File "/usr/share/pyshared/openerp-server/netsvc.py", line 489, in dispatch  result = ExportService.getService(service_name).dispatch(method, auth, params)\r\nFile "/usr/share/pyshared/openerp-server/service/web_services.py", line 599, in dispatch    res = fn(db, uid, *params)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 122, in wrapper    return f(self, dbname, *args, **kwargs)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 176, in execute    res = self.execute_cr(cr, uid, obj, method, *args, **kw)\r\nFile "/usr/share/pyshared/openerp-server/osv/osv.py", line 167, in execute_cr    return getattr(object, method)(cr, uid, *args, **kw)\r\nFile "/usr/share/pyshared/openerp-server/osv/orm.py", line 1984, in write    self.datas[object_id].update(vals2)\r\nKeyError: 1</code>\r\n\r\nA ce stade là, j''essaie alors toutes les solutions envisageables.\r\n<ul>\r\n	<li>Je regarde sur internet : rien, enfin rien de compréhensible. J''ai l''impression d''être le seul a parler français.</li>\r\n	<li>Je me penche sur le système d''internationalisation, qui semble récupérer toutes les phrases présentes dans les fichiers et proposer une traduction dans la langue du fichier d''inter choisi, ça aurait pu venir du fait qu''il n''arrivait pas à internationaliser le titre de l''application puisque je l''ai modifié, mais ce n''est pas ça.</li>\r\n	<li>Les fichiers auraient ils des dépendances non résolues, ou des liens dépendant du nom du module, qui une fois modifiés ne marchent plus ? Non plus.</li>\r\n	<li>J''en ai conclu que ça ne pouvait venir que du numéro de série invalide, qui bloque toute installation.</li>\r\n</ul>\r\nFlûte ! Et les développeurs, ils font comment pour tester des modules ? Ils achète un numéro de série valide pour s''assurer que leur propre code ne contient pas de virus ? de produits malveillants ? ou même de gros mots ?\r\n\r\nJ''ai donc décidé de modifier directement un add-on existant, et si je fait des bêtises, tant pis. J''ai choisi le module <strong>Idea</strong>, car il est mieux documenté que les autres modules et possède une structure très complète. J''ai lu l''arbre XML, décomposé le code,apporté quelques modifications et essayé de comprendre quelle partie du code correspondait à quel endroit dans l''interface graphique. Ce fut un lamentable échec, encore une fois. Je n''arrive pas, en mettant côte à côte mon code xml et ma fenêtre, à comprendre qu''est-ce qui représente quoi.\r\n\r\nAu final j''ai quand même réussi a rajouter un bouton, mais pas à modifier un texte. Je verrai ça demain.', '3 avril 2012', '', 'inherit', 'open', 'open', '', '56-revision-10', '', '', '2012-04-03 16:08:42', '2012-04-03 14:08:42', '', 56, 'http://lambdaweb.free.fr/blog/?p=67', 0, 'revision', '', 0),
(68, 1, '2012-04-04 18:27:42', '2012-04-04 16:27:42', 'Aujourd’hui j''ai rendez-vous avec Famy vers midi, en l''attendant je continue à explorer le code sombre et chinois d''un module openErp - toujours le même d''ailleurs, Idea - et je comprends de mieux en mieux. Je me penche plus sur les relation entre les changements d''états (idea_workflow.xml). Je vois que certains champs, certaines zones, changent, apparaissent ou se mettent en lecture seulement en fonction de l''état actuel.\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg"><img class="alignnone size-full wp-image-70" title="05042012010" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg" alt="" width="500" height="145" /></a>\r\n\r\nJe commence aussi à comprendre comment marche tout ce petit bazar. En mettant côte à côte le client web et le XML, j''arrive à peu près à deviner qu''est ce qui affiche quoi, qui fait quoi, pourquoi ça s''affiche comme ça et vers ou ce bouton t''emmène.\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-full wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg" alt="" width="500" height="281" /></a>\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg"><img class="alignnone size-full wp-image-71" title="05042012012" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg" alt="" width="500" height="178" /></a>\r\n\r\nFamy est arrivé, il m''a expliqué comme il a pu les liaisons relationnelles qui opéraient dans ce souk et que je devais trouver un moyen de créer un fichier python en exécutable. Un fois parti, je me suis penché sur py2exe, puis sur pyinstaller, et j''ai envoyé une version binaire d''une simple boite de dialogue faite en pyQt et hébergée sur Google Docs à Famy. Je me suis ensuite penché sur une version graphique de pyinstaller, que je n''ai pas eu le temps de terminer.\r\n\r\n&nbsp;\r\n\r\n&nbsp;', '4 avril 2012', '', 'publish', 'open', 'open', '', '4-avril-2012', '', '', '2012-04-05 13:54:25', '2012-04-05 11:54:25', '', 0, 'http://lambdaweb.free.fr/blog/?p=68', 0, 'post', '', 0),
(69, 1, '2012-04-05 13:26:17', '2012-04-05 11:26:17', '', '05042012011', '', 'inherit', 'open', 'open', '', '05042012011', '', '', '2012-04-05 13:26:17', '2012-04-05 11:26:17', '', 68, 'http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg', 0, 'attachment', 'image/jpeg', 0),
(70, 1, '2012-04-05 13:26:57', '2012-04-05 11:26:57', '', '05042012010', '', 'inherit', 'open', 'open', '', '05042012010', '', '', '2012-04-05 13:26:57', '2012-04-05 11:26:57', '', 68, 'http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg', 0, 'attachment', 'image/jpeg', 0),
(71, 1, '2012-04-05 13:27:21', '2012-04-05 11:27:21', '', '05042012012', '', 'inherit', 'open', 'open', '', '05042012012', '', '', '2012-04-05 13:27:21', '2012-04-05 11:27:21', '', 68, 'http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg', 0, 'attachment', 'image/jpeg', 0),
(72, 1, '2012-04-05 13:26:50', '2012-04-05 11:26:50', 'Aujourd’hui j''ai rendez-vous avec Famy vers midi, en l''attendant je continue à explorer le code sombre et chinois d''un module openErp - toujours le même d''ailleurs, Idea - et je comprends de mieux en mieux. Je me penche plus sur les relation entre les changements d''états (idea_workflow.xml). Je vois que certains champs, certaines zones, changent, apparaissent ou se mettent en lecture seulement en fonction de l''état actuel.\n\n&nbsp;\n\nJe commence aussi à comprendre comment marche tout ce petit bazar. En mettant côte à côte le client web et le XML, j''arrive à peu près à deviner qu''est ce qui affiche quoi, qui fait quoi, pourquoi ça s''affiche comme ça et vers ou ce bouton t''emmène.\n\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-full wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg" alt="" width="500" height="281" /></a>\n\nFamy est arrivé, il m''a expliqué comme il a pu les liaisons relationnelles qui opéraient dans ce souk et\n\n&nbsp;\n\n&nbsp;', '4 avril 2012', '', 'inherit', 'open', 'open', '', '68-revision', '', '', '2012-04-05 13:26:50', '2012-04-05 11:26:50', '', 68, 'http://lambdaweb.free.fr/blog/?p=72', 0, 'revision', '', 0),
(73, 1, '2012-04-05 13:48:44', '2012-04-05 11:48:44', 'Aujourd’hui j''ai rendez-vous avec Famy vers midi, en l''attendant je continue à explorer le code sombre et chinois d''un module openErp - toujours le même d''ailleurs, Idea - et je comprends de mieux en mieux. Je me penche plus sur les relation entre les changements d''états (idea_workflow.xml). Je vois que certains champs, certaines zones, changent, apparaissent ou se mettent en lecture seulement en fonction de l''état actuel.\n\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg"><img class="alignnone size-full wp-image-70" title="05042012010" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg" alt="" width="500" height="145" /></a>\n\nJe commence aussi à comprendre comment marche tout ce petit bazar. En mettant côte à côte le client web et le XML, j''arrive à peu près à deviner qu''est ce qui affiche quoi, qui fait quoi, pourquoi ça s''affiche comme ça et vers ou ce bouton t''emmène.\n\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-full wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg" alt="" width="500" height="281" /></a>\n\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg"><img class="alignnone size-full wp-image-71" title="05042012012" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg" alt="" width="500" height="178" /></a>\n\nFamy est arrivé, il m''a expliqué comme il a pu les liaisons relationnelles qui opéraient dans ce souk et que je devais trouver un moyen de créer un fichier python en exécutable. Un fois parti, je me suis penché sur py2exe, puis sur pyinstaller, et j''ai envoyé une version binaire d''une simple boite de dialogue faite en pyQt et hébergée sur Google docs à Famy. Je me suis ensuite penché sur une version graphique de pyinstaller, que je n''ai pas eu le temps de terminer.\n\n&nbsp;\n\n&nbsp;', '4 avril 2012', '', 'inherit', 'open', 'open', '', '68-autosave', '', '', '2012-04-05 13:48:44', '2012-04-05 11:48:44', '', 68, 'http://lambdaweb.free.fr/blog/?p=73', 0, 'revision', '', 0),
(74, 1, '2012-04-05 13:27:42', '2012-04-05 11:27:42', 'Aujourd’hui j''ai rendez-vous avec Famy vers midi, en l''attendant je continue à explorer le code sombre et chinois d''un module openErp - toujours le même d''ailleurs, Idea - et je comprends de mieux en mieux. Je me penche plus sur les relation entre les changements d''états (idea_workflow.xml). Je vois que certains champs, certaines zones, changent, apparaissent ou se mettent en lecture seulement en fonction de l''état actuel.\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg"><img class="alignnone size-full wp-image-70" title="05042012010" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg" alt="" width="500" height="145" /></a>\r\n\r\nJe commence aussi à comprendre comment marche tout ce petit bazar. En mettant côte à côte le client web et le XML, j''arrive à peu près à deviner qu''est ce qui affiche quoi, qui fait quoi, pourquoi ça s''affiche comme ça et vers ou ce bouton t''emmène.\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-full wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg" alt="" width="500" height="281" /></a>\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg"><img class="alignnone size-full wp-image-71" title="05042012012" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg" alt="" width="500" height="178" /></a>\r\n\r\nFamy est arrivé, il m''a expliqué comme il a pu les liaisons relationnelles qui opéraient dans ce souk et\r\n\r\n&nbsp;\r\n\r\n&nbsp;', '4 avril 2012', '', 'inherit', 'open', 'open', '', '68-revision-2', '', '', '2012-04-05 13:27:42', '2012-04-05 11:27:42', '', 68, 'http://lambdaweb.free.fr/blog/?p=74', 0, 'revision', '', 0),
(75, 1, '2012-04-05 13:51:32', '2012-04-05 11:51:32', 'Je suis arrivé à mon stage, j''ai continué ma version graphique en pyQt de pyinstaller, et je l''ai hébergée sur sourceForge. J''ai envoyé le tout à Famy. Je suis ensuite allé signer ma convention de stage.', '5 avril 2012', '', 'publish', 'open', 'open', '', '5-avril-2012', '', '', '2012-04-05 13:51:32', '2012-04-05 11:51:32', '', 0, 'http://lambdaweb.free.fr/blog/?p=75', 0, 'post', '', 0),
(76, 1, '2012-04-05 13:50:34', '2012-04-05 11:50:34', 'Je suis arrivé à mon stage, j''ai continué ma version graphique en pyQt de pyinstaller, et', '5 avril 2012', '', 'inherit', 'open', 'open', '', '75-revision', '', '', '2012-04-05 13:50:34', '2012-04-05 11:50:34', '', 75, 'http://lambdaweb.free.fr/blog/?p=76', 0, 'revision', '', 0),
(77, 1, '2012-04-05 13:48:59', '2012-04-05 11:48:59', 'Aujourd’hui j''ai rendez-vous avec Famy vers midi, en l''attendant je continue à explorer le code sombre et chinois d''un module openErp - toujours le même d''ailleurs, Idea - et je comprends de mieux en mieux. Je me penche plus sur les relation entre les changements d''états (idea_workflow.xml). Je vois que certains champs, certaines zones, changent, apparaissent ou se mettent en lecture seulement en fonction de l''état actuel.\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg"><img class="alignnone size-full wp-image-70" title="05042012010" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012010.jpg" alt="" width="500" height="145" /></a>\r\n\r\nJe commence aussi à comprendre comment marche tout ce petit bazar. En mettant côte à côte le client web et le XML, j''arrive à peu près à deviner qu''est ce qui affiche quoi, qui fait quoi, pourquoi ça s''affiche comme ça et vers ou ce bouton t''emmène.\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-full wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg" alt="" width="500" height="281" /></a>\r\n\r\n<a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg"><img class="alignnone size-full wp-image-71" title="05042012012" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012012.jpg" alt="" width="500" height="178" /></a>\r\n\r\nFamy est arrivé, il m''a expliqué comme il a pu les liaisons relationnelles qui opéraient dans ce souk et que je devais trouver un moyen de créer un fichier python en exécutable. Un fois parti, je me suis penché sur py2exe, puis sur pyinstaller, et j''ai envoyé une version binaire d''une simple boite de dialogue faite en pyQt et hébergée sur Google Docs à Famy. Je me suis ensuite penché sur une version graphique de pyinstaller, que je n''ai pas eu le temps de terminer.\r\n\r\n&nbsp;\r\n\r\n&nbsp;', '4 avril 2012', '', 'inherit', 'open', 'open', '', '68-revision-3', '', '', '2012-04-05 13:48:59', '2012-04-05 11:48:59', '', 68, 'http://lambdaweb.free.fr/blog/?p=77', 0, 'revision', '', 0),
(79, 1, '2012-04-11 20:18:16', '2012-04-11 18:18:16', '', 'Brouillon auto', '', 'inherit', 'open', 'open', '', '78-revision', '', '', '2012-04-11 20:18:16', '2012-04-11 18:18:16', '', 78, 'http://lambdaweb.free.fr/blog/?p=79', 0, 'revision', '', 0),
(80, 1, '2012-04-11 21:18:19', '2012-04-11 19:18:19', 'Aujourd''hui fut une journée d''essais et d''échecs.\n\nFamy m''a expliqué le cahier des charges, le projet m''a l''air colossal et semble diverger de ce qui était initialement prévu. Il faudrait maintenant développer une application graphique en python permettant à un enseignant en gestion de créer des entreprises d''exercice pour ses élèves, et qu''il puisse ensuite créer et gérer plusieurs travaux à faire par ces derniers. Un suivi des élèves et un système de restauration des données doit également être mis en place. La concurrence est rude puisque elle possède déjà une telle application.\n\nJe précise aussi que le tout marchera avec l''ERP openErp, of course. J''ai donc essayé d''installer pyQt sur Linux, et j''ai pris la dernière version disponible sur les dépots, ainsi que les dernières versions de ses dépendances (SIP, QScintilla etc...).\n\nMa version de Qt étant trop vieille, j''ai essayé l''installation avec mon installation de Qt mise a jour depuis les dépôts officiels Nokia. Les librairies Qt n''étant pas compilées dynamiquement, j''ai du générer le MakeFile pour SIP avec l''option <code>--static</code>. Le tout ne marche pas, SIP n''est pas a la bonne version. J''ai ensuite installé pyQt et SIP depuis les dépôts Ubuntu, mais l''interpréteur python ne reconnait pas le module PyQt lors de l''importation :\n<pre escaped="true" lang="python" line="1"> <span style="color: #c0c0c0;">import </span><span style="color: #99cc00;">PyQt4</span>.<span style="color: #99cc00;">QtCore </span></pre>\nJ''ai donc essayé de faire marcher le tout sous Windows. Je savais que ça marchait, parce que j''avais déjà fait une petite application en interface graphique Qt pour générer un binaire depuis une source python. J''ai donc essayé de faire une petite application graphique pour insérer un nouveau produit dans ma base de données openErp (complété du module Products). Le client web ne veut pas se lancer, ou du moins, mon navigateur me donne une erreur 403 (forbidden). Flute ! La console m''indique que le module AutoReload ne s''initialise pas. Heureusement que le client graphique marche quand même.\n\nJe lance mon application fraîchement crée, et lors du clic sur le bouton "ajouter utilisateur", la fenêtre se bloque. Pas un message dans la console, rien. Après plusieurs tentatives, plusieurs modifications, toujours le même résultat. Le socket XMLRPC semble bloqué, et après une batterie de tests (ça ne vient pas du port, pas du serveur, pas d''un fichier de configuration, pas d''une autre application que bloque le port, pas d''une erreur de table, pas d''une boucle, pas d''une mauvaise condition, pas d''une faute d''orthographe, pas d''une malédiction vaudou, hindoue, chamane ou satanique), je constate que même le code XMLRPC basique de référence donné par la documentation officielle OpenErp ne marche pas.\n\nJe reviens alors sous Windows et j''essaie d''effectuer une purge totale des paquets SIP et PyQt, puis d''effacer le tout manuellement. Rien à faire.\n\nJe reprendrais demain, mais j''ai le sentiment que je n''avance pas.', '10 avril 2012', '', 'inherit', 'open', 'open', '', '78-autosave', '', '', '2012-04-11 21:18:19', '2012-04-11 19:18:19', '', 78, 'http://lambdaweb.free.fr/blog/?p=80', 0, 'revision', '', 0),
(81, 1, '2012-04-11 20:19:24', '2012-04-11 18:19:24', '', '10 avril 2012', '', 'inherit', 'open', 'open', '', '78-revision-2', '', '', '2012-04-11 20:19:24', '2012-04-11 18:19:24', '', 78, 'http://lambdaweb.free.fr/blog/?p=81', 0, 'revision', '', 0),
(82, 1, '2012-04-11 21:16:51', '2012-04-11 19:16:51', 'Je reprends mes recherches là ou je les avais laissées hier à propos de cette foutue installation de PyQt.\r\n\r\nAu bout de quelques heures, je me décide à passer à PySide, le binding python pour Qt développé par Nokia en personne. La documentation semble être beaucoup plus rare, mais la syntaxe beaucoup plus proche de celle de Qt. J''installe donc ce module directement depuis les dépôts Nokia et recommence une nouvelle interface, cette fois pour ajouter des cours pour le module openErp "académie", simple et léger, développé par Famy.\r\n\r\nAprès plusieurs expérimentations, j''arrive enfin à insérer un nouveau cours, à lui donner le nom que je veux grâce un <span style="color: #99cc00;"><code>QLineEdit</code></span>. Le reste des valeurs est pour l''instant tapé en dur dans le script s''occupant d''effectuer la liaison XMLRPC.\r\n\r\nIl faudra donc ensuite créer plusieurs menus déroulants comportant la liste des matières, des salles et des enseignants, et donc les charger dynamiquement depuis la base de données. Je commence donc à créer de QComboBox à foison, puis je crée une méthode permettant de récupérer la liste des enseignants, puis deux autres pour les salles et les matières.\r\n\r\nEt j''y arrive enfin, même si j''ai eu un grand souci de cast, car python ne voyait pas <code><span style="color: #99cc00;">QtGui</span>.<span style="color: #99cc00;">QComboBox</span>.<span style="color: #ff99cc;">currentIndex</span></code> comme un int, mais comme un pointeur sur objet indéfini, alors qu''il fallait tout simplement mettre des parenthèses...\r\n\r\nJ''essaie ensuite de modifier le module "académie" en rajoutant une case à cocher "est un contrôle" lors de l''ajout d''un cours, mais rien à faire, openErp refuse d''ajouter mon nouveau champ dans la base de données.\r\n\r\nJe verrai ça demain...', '11 avril 2012', '', 'publish', 'open', 'open', '', '11-avril-2012', '', '', '2012-04-11 21:16:51', '2012-04-11 19:16:51', '', 0, 'http://lambdaweb.free.fr/blog/?p=82', 0, 'post', '', 0),
(83, 1, '2012-04-11 21:16:22', '2012-04-11 19:16:22', 'Je reprends mes recherches là ou je les avais laissées hier à propos de cette foutue installation de PyQt.\n\nAu bout de quelques heures, je me décide à passer à PySide, le binding python pour Qt développé par Nokia en personne. La documentation semble être beaucoup plus rare, mais la syntaxe beaucoup plus proche de celle de Qt. J''installe donc ce module directement depuis les dépôts Nokia et recommence une nouvelle interface, cette fois pour ajouter des cours pour le module openErp "académie", simple et léger, développé par Famy.\n\nAprès plusieurs expérimentations, j''arrive enfin à insérer un nouveau cours, à lui donner le nom que je veux grâce un <span style="color: #99cc00;"><code>QLineEdit</code></span>. Le reste des valeurs est pour l''instant tapé en dur dans le script s''occupant d''effectuer la liaison XMLRPC. Il faudra donc ensuite créer plusieurs menus déroulants comportant la liste des matières, des salles et des enseignants, et donc les charger dynamiquement depuis la base de données. Je commence donc à créer de QComboBox à foison, puis je crée une méthode permettant de récupérer la liste des enseignants, puis deux autres pour les salles et les matières.\n\nEt j''y arrive enfin, même si j''ai eu un grand souci de cast, car python ne voyait pas <code><span style="color: #99cc00;">QtGui</span>.<span style="color: #99cc00;">QComboBox</span>.<span style="color: #ff99cc;">currentIndex</span></code> comme un int, mais comme un pointeur sur objet indéfini, alors qu''il fallait tout simplement mettre des parenthèses...\n\nJ''essaie ensuite de modifier le module "académie" en rajoutant une case à cocher "est un contrôle" lors de l''ajout d''un cours, mais rien à faire, openErp refuse d''ajouter mon nouveau champ dans la base de', '11 avril 2012', '', 'inherit', 'open', 'open', '', '82-revision', '', '', '2012-04-11 21:16:22', '2012-04-11 19:16:22', '', 82, 'http://lambdaweb.free.fr/blog/?p=83', 0, 'revision', '', 0),
(84, 1, '2012-04-11 20:42:13', '2012-04-11 18:42:13', 'Aujourd''hui fut une journée d''essais et d''échecs.\r\n\r\nFamy m''a expliqué le cahier des charges, le projet m''a l''air colossal et semble diverger de ce qui était initialement prévu. Il faudrait maintenant développer une application graphique en python permettant à un enseignant en gestion de créer des entreprises d''exercice pour ses élèves, et qu''il puisse ensuite créer et gérer plusieurs travaux à faire par ces derniers. Un suivi des élèves et un système de restauration des données doit également être mis en place. La concurrence est rude puisque elle possède déjà une telle application.\r\n\r\nJe précise aussi que le tout marchera avec l''ERP openErp, of course. J''ai donc essayé d''installer pyQt sur Linux, et j''ai pris la dernière version disponible sur les dépots, ainsi que les dernières versions de ses dépendances (SIP, QScintilla etc...).\r\n\r\nMa version de Qt étant trop vieille, j''ai essayé l''installation avec mon installation de Qt mise a jour depuis les dépôts officiels Nokia. Les librairies Qt n''étant pas compilées dynamiquement, j''ai du générer le MakeFile pour SIP avec l''option <code>--static</code>. Le tout ne marche pas, SIP n''est pas a la bonne version. J''ai ensuite installé pyQt et SIP depuis les dépôts Ubuntu, mais l''interpréteur python ne reconnait pas le module PyQt lors de l''importation :\r\n<pre escaped="true" lang="python" line="1"> <span style="color: #333333;">import </span><span style="color: #008000;">PyQt4</span>.<span style="color: #008000;">QtCore </span></pre>\r\nJ''ai donc essayé de faire marcher le tout sous Windows. Je savais que ça marchait, parce que j''avais déjà fait une petite application en interface graphique Qt pour générer un binaire depuis une source python. J''ai donc essayé de faire une petite application graphique pour insérer un nouveau produit dans ma base de données openErp (complété du module Products). Le client web ne veut pas se lancer, ou du moins, mon navigateur me donne une erreur 403 (forbidden). Flute ! La console m''indique que le module AutoReload ne s''initialise pas. Heureusement que le client graphique marche quand même.\r\n\r\nJe lance mon application fraîchement crée, et lors du clic sur le bouton "ajouter utilisateur", la fenêtre se bloque. Pas un message dans la console, rien. Après plusieurs tentatives, plusieurs modifications, toujours le même résultat. Le socket XMLRPC semble bloqué, et après une batterie de tests (ça ne vient pas du port, pas du serveur, pas d''un fichier de configuration, pas d''une autre application que bloque le port, pas d''une erreur de table, pas d''une boucle, pas d''une mauvaise condition, pas d''une faute d''orthographe, pas d''une malédiction vaudou, hindoue, chamane ou satanique), je constate que même le code XMLRPC basique de référence donné par la documentation officielle OpenErp ne marche pas. Je reviens alors sous Windows et j''essaie d''effectuer une purge totale des paquets SIP et PyQt, puis d''effacer le tout manuellement. Rien à faire.\r\n\r\nJe reprendrais demain, mais j''ai le sentiment que je n''avance pas.', '10 avril 2012', '', 'inherit', 'open', 'open', '', '78-revision-3', '', '', '2012-04-11 20:42:13', '2012-04-11 18:42:13', '', 78, 'http://lambdaweb.free.fr/blog/?p=84', 0, 'revision', '', 0),
(85, 1, '2012-05-02 03:56:19', '0000-00-00 00:00:00', '', 'Brouillon auto', '', 'auto-draft', 'open', 'open', '', '', '', '', '2012-05-02 03:56:19', '0000-00-00 00:00:00', '', 0, 'http://lambdaweb.free.fr/blog/?p=85', 0, 'post', '', 0),
(86, 2, '2012-05-02 03:57:57', '0000-00-00 00:00:00', '', 'Brouillon auto', '', 'auto-draft', 'open', 'open', '', '', '', '', '2012-05-02 03:57:57', '0000-00-00 00:00:00', '', 0, 'http://lambdaweb.free.fr/blog/?p=86', 0, 'post', '', 0),
(87, 2, '2012-05-02 03:58:13', '2012-05-02 01:58:13', 'un simple article de test.', 'simple test', '', 'trash', 'open', 'open', '', 'simple-test', '', '', '2012-05-03 10:07:57', '2012-05-03 08:07:57', '', 0, 'http://lambdaweb.free.fr/blog/?p=87', 0, 'post', '', 0),
(88, 2, '2012-05-02 03:58:05', '2012-05-02 01:58:05', '', 'simple test', '', 'inherit', 'open', 'open', '', '87-revision', '', '', '2012-05-02 03:58:05', '2012-05-02 01:58:05', '', 87, 'http://lambdaweb.free.fr/blog/?p=88', 0, 'revision', '', 0),
(89, 2, '2012-05-02 03:59:14', '2012-05-02 01:59:14', 'un simple article de test.', 'simple test', '', 'inherit', 'open', 'open', '', '87-autosave', '', '', '2012-05-02 03:59:14', '2012-05-02 01:59:14', '', 87, 'http://lambdaweb.free.fr/blog/?p=89', 0, 'revision', '', 0),
(90, 2, '2012-05-02 04:28:46', '2012-05-02 02:28:46', 'Je viens d''écrire ca sur mon mobile!', 'Test mobile', '', 'trash', 'open', 'open', '', 'test-mobile', '', '', '2012-05-03 10:07:57', '2012-05-03 08:07:57', '', 0, 'http://lambdaweb.free.fr/blog/?p=90', 0, 'post', '', 2),
(91, 2, '2012-05-02 04:28:46', '2012-05-02 02:28:46', 'Je viens d''écrire ca sur mon mobile!', 'Test mobile', '', 'inherit', 'open', 'open', '', '90-revision', '', '', '2012-05-02 04:28:46', '2012-05-02 02:28:46', '', 90, 'http://lambdaweb.free.fr/blog/?p=91', 0, 'revision', '', 0),
(92, 2, '2012-05-02 04:31:12', '2012-05-02 02:31:12', 'Je viens d''écrire ca sur mon mobile!', 'Test mobile', '', 'inherit', 'open', 'open', '', '90-revision-2', '', '', '2012-05-02 04:31:12', '2012-05-02 02:31:12', '', 90, 'http://lambdaweb.free.fr/blog/?p=92', 0, 'revision', '', 0),
(93, 2, '2012-05-02 04:48:12', '2012-05-02 02:48:12', 'moi aussi j''ai écrit queque <strong>chose, mais </strong>sur mon <em>ordi</em> cette <span style="text-decoration: underline;">fois !</span>', 'éh', '', 'trash', 'open', 'open', '', 'eh', '', '', '2012-05-03 10:07:57', '2012-05-03 08:07:57', '', 0, 'http://lambdaweb.free.fr/blog/?p=93', 0, 'post', '', 0),
(94, 2, '2012-05-02 04:47:34', '2012-05-02 02:47:34', '', 'éh', '', 'inherit', 'open', 'open', '', '93-revision', '', '', '2012-05-02 04:47:34', '2012-05-02 02:47:34', '', 93, 'http://lambdaweb.free.fr/blog/?p=94', 0, 'revision', '', 0),
(95, 2, '2012-05-02 04:49:15', '2012-05-02 02:49:15', 'moi aussi j''ai écrit queque <strong>chose, mais </strong>sur mon <em>ordi</em> cette <span style="text-decoration: underline;">fois !</span>', 'éh', '', 'inherit', 'open', 'open', '', '93-autosave', '', '', '2012-05-02 04:49:15', '2012-05-02 02:49:15', '', 93, 'http://lambdaweb.free.fr/blog/?p=95', 0, 'revision', '', 0),
(96, 2, '2012-05-02 09:27:41', '2012-05-02 07:27:41', 'yo yow yow, youpie hey, yeepe hooow ! <a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-medium wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011-300x168.jpg" alt="" width="300" height="168" /></a>', 'yo', '', 'trash', 'open', 'open', '', 'yo', '', '', '2012-05-03 10:07:57', '2012-05-03 08:07:57', '', 0, 'http://lambdaweb.free.fr/blog/?p=96', 0, 'post', '', 0),
(97, 2, '2012-05-02 09:27:13', '2012-05-02 07:27:13', '', 'yo', '', 'inherit', 'open', 'open', '', '96-revision', '', '', '2012-05-02 09:27:13', '2012-05-02 07:27:13', '', 96, 'http://lambdaweb.free.fr/blog/?p=97', 0, 'revision', '', 0),
(98, 2, '2012-05-02 09:28:44', '2012-05-02 07:28:44', 'yo yow yow, youpie hey, yeepe hooow ! <a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-medium wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011-300x168.jpg" alt="" width="300" height="168" /></a>', 'yo', '', 'inherit', 'open', 'open', '', '96-autosave', '', '', '2012-05-02 09:28:44', '2012-05-02 07:28:44', '', 96, 'http://lambdaweb.free.fr/blog/?p=98', 0, 'revision', '', 0),
(99, 2, '2012-05-02 19:26:27', '0000-00-00 00:00:00', '', 'Brouillon auto', '', 'auto-draft', 'open', 'open', '', '', '', '', '2012-05-02 19:26:27', '0000-00-00 00:00:00', '', 0, 'http://lambdaweb.free.fr/blog/?p=99', 0, 'post', '', 0);
INSERT INTO `blog_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(100, 2, '2012-05-02 19:26:54', '2012-05-02 17:26:54', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li> Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;', 'Marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '21-autosave', '', '', '2012-05-02 19:26:54', '2012-05-02 17:26:54', '', 21, 'http://lambdaweb.free.fr/blog/?p=100', 0, 'revision', '', 0),
(101, 2, '2011-05-19 16:42:11', '2011-05-19 14:42:11', '<h3 style="font-size: 1.5em;"><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir <span style="text-decoration: underline;">un minimum d’acquis</span> en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li> Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;', 'Marivaudages avec la segfault', '', 'inherit', 'open', 'open', '', '21-revision-3', '', '', '2011-05-19 16:42:11', '2011-05-19 14:42:11', '', 21, 'http://lambdaweb.free.fr/blog/?p=101', 0, 'revision', '', 0),
(102, 2, '2012-05-02 19:27:37', '2012-05-02 17:27:37', '<h3><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir un minimum d’acquis en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li>Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%dn", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;\r\n\r\n&nbsp;', 'Marivaudages 2 :', '', 'trash', 'open', 'open', '', 'marivaudages-2', '', '', '2012-05-03 10:07:57', '2012-05-03 08:07:57', '', 0, 'http://lambdaweb.free.fr/blog/?p=102', 0, 'post', '', 0),
(103, 2, '2012-05-02 19:27:32', '2012-05-02 17:27:32', '', 'Marivaudages 2 :', '', 'inherit', 'open', 'open', '', '102-revision', '', '', '2012-05-02 19:27:32', '2012-05-02 17:27:32', '', 102, 'http://lambdaweb.free.fr/blog/?p=103', 0, 'revision', '', 0),
(104, 2, '2012-05-02 19:28:39', '2012-05-02 17:28:39', '<h3><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir un minimum d’acquis en matière de programmation C++.</h4>\n<ol>\n	<li>\n<h4>- "seg fault ? Jamais entendu parler."</h4>\n<ul>\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\n</ul>\n</li>\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\n\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\n\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\n	<li>\n<h4>Et il sort d''ou lui ?</h4>\n</li>\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\nPar exemple :\n<pre escaped="true" lang="c" line="1">#include \n\n int main()\n{\n     int variable_entiere; // la variable n''est pas initialisée\n     scanf("%d", variable_entiere); // on accède a cette variable\n} // -&gt; segfault a 99%</pre>\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\n	<li>\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\n<ul>\n	<li>Les vérification basiques :\n<ul>\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\n{\n    int tableau[4], i = 0;\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\n    {\n        printf("%d\\n", tableau[i]);\n    }\n    return 0;\n}</pre>\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\n</ul>\n</li>\n	<li>Si ca ne marche pas, deux options :\n<ul>\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\n</ul>\n</li>\n</ul>\n</li>\n</ol>\n&nbsp;\n\n&nbsp;', 'Marivaudages 2 :', '', 'inherit', 'open', 'open', '', '102-autosave', '', '', '2012-05-02 19:28:39', '2012-05-02 17:28:39', '', 102, 'http://lambdaweb.free.fr/blog/?p=104', 0, 'revision', '', 0),
(105, 2, '2012-05-02 21:42:03', '2012-05-02 19:42:03', 'Bonjour lucille ! :D', 'kshfksl', '', 'trash', 'open', 'open', '', 'kshfksl', '', '', '2012-05-03 10:07:57', '2012-05-03 08:07:57', '', 0, 'http://lambdaweb.free.fr/blog/?p=105', 0, 'post', '', 0),
(106, 2, '2012-05-02 21:41:56', '2012-05-02 19:41:56', '', 'kshfksl', '', 'inherit', 'open', 'open', '', '105-revision', '', '', '2012-05-02 21:41:56', '2012-05-02 19:41:56', '', 105, 'http://lambdaweb.free.fr/blog/?p=106', 0, 'revision', '', 0),
(107, 2, '2012-05-02 21:43:06', '2012-05-02 19:43:06', 'Bonjour lucille ! :D', 'kshfksl', '', 'inherit', 'open', 'open', '', '105-autosave', '', '', '2012-05-02 21:43:06', '2012-05-02 19:43:06', '', 105, 'http://lambdaweb.free.fr/blog/?p=107', 0, 'revision', '', 0),
(108, 2, '2012-05-02 21:42:03', '2012-05-02 19:42:03', 'Bonjour lucille ! :D', 'kshfksl', '', 'inherit', 'open', 'open', '', '105-revision-2', '', '', '2012-05-02 21:42:03', '2012-05-02 19:42:03', '', 105, 'http://lambdaweb.free.fr/blog/?p=108', 0, 'revision', '', 0),
(109, 2, '2012-05-02 19:27:37', '2012-05-02 17:27:37', '<h3><em>Ce dossier explique les erreurs de type "seg fault" couramment rencontrées en c++.</em></h3>\r\n<h4 lang="php">Bien que ce dossier soit destiné aux développeurs débutants ou intermédiaires, il est quand même recommandé d''avoir un minimum d’acquis en matière de programmation C++.</h4>\r\n<ol>\r\n	<li>\r\n<h4>- "seg fault ? Jamais entendu parler."</h4>\r\n<ul>\r\n	<li>Il est invisible pour le compilateur, et n’apparaît que lors de l’exécution du programme.</li>\r\n	<li>Il a la coutume d''apparaître au moment ou on s''y attend le moins.</li>\r\n	<li>Il est assez dur a dénicher (cette difficulté augmentant exponentiellement avec la taille de votre code :) )</li>\r\n</ul>\r\n</li>\r\nUne seg fault, plus communément appelée segmentation fault, ou encore erreur de segmentation, c''est un type d''erreur renvoyé lors de l’exécution du programme. Plus précisément, c''est un <strong>très très méchant</strong> type d''erreur.\r\n\r\nOn le reconnait grâce a son sympathique message de bienvenue, qui, a défaut d''être (extrêmement) rabat-joie, est très clair.\r\n<pre escaped="false" lang="bash" line="1">Program received signal SIGSEGV, Segmentation fault.</pre>\r\nLe segfault, en plus d’être vil, fourbe et perfide, est (par définition) imprévisible. Pourquoi ?\r\n\r\nPersonnellement, quand j''en vois un, je met tout de suite la cafetière en marche :P\r\n	<li>\r\n<h4>Et il sort d''ou lui ?</h4>\r\n</li>\r\nLa seg fault se pointe quand le programme tente d''accéder à un emplacement mémoire qui ne lui est pas alloué. Plus précisement :\r\n<blockquote>"Les applications, lorsqu''elles s''exécutent, ont besoin de mémoire vive, allouée par le système d''exploitation. Une fois allouée à l''application, aucune autre application ne peut avoir accès à cette zone ; cela garantit une sûreté de fonctionnement pour chaque application contre les erreurs des autres. Ainsi, si une application tente le moindre accès à une zone mémoire qui ne lui est pas allouée, le système d''exploitation le détecte et stoppe immédiatement son exécution." - Wikipédia</blockquote>\r\nAutrement dit, lorsque vous accédez a une case d''un tableau qui n''est pas définie, que vous modifiez dans une variable non-initialisée ou que vous regardez une adresse qui ne vous appartient pas, ça coince.\r\nPar exemple :\r\n<pre escaped="true" lang="c" line="1">#include \r\n\r\n int main()\r\n{\r\n     int variable_entiere; // la variable n''est pas initialisée\r\n     scanf("%d", variable_entiere); // on accède a cette variable\r\n} // -&gt; segfault a 99%</pre>\r\nla valeur de <code>variable_entiere</code> n''est pas initialisée et a donc une valeur quelconque. La fonction <em>scanf</em> tente alors d''accéder à la zone mémoire représentée par la valeur contenue dans <code>variable_entiere</code> et provoquera fort probablement une erreur de segmentation. Nous voulions stocker la valeur récupérée par <em>scanf</em> dans <code>variable_entiere</code> et nous devions donc passer en argument l''adresse de notre variable (en utilisant <em>&amp;</em> devant le nom de la variable) et non sa valeur.\r\n	<li>\r\n<h4>Quelques astuces pour trouver l''origine d''une seg fault</h4>\r\n<ul>\r\n	<li>Les vérification basiques :\r\n<ul>\r\n	<li>Reprendre votre code en initialisant toutes les variables que vous déclarez.</li>\r\n	<li>Vérifiez que la case du tableau auquel vous accédez appartient bien au tableau ! Une segfault vient souvent de la !</li>\r\n	<li>Vérifiez les boucles ! Une simple erreur de conditionnement et c''est foutu !  Typiquement :</li>\r\n<pre escaped="true" lang="c" line="1">int main(int argc, char *argv[])\r\n{\r\n    int tableau[4], i = 0;\r\n    for (i = 0 ; i &lt;= 4 ; i++) /* boucle archi-fausse, car la case [4] du tableau n''est pas définie ! -&gt; segfault */\r\n    {\r\n        printf("%d\\n", tableau[i]);\r\n    }\r\n    return 0;\r\n}</pre>\r\n	<li>Attention à vos pointeurs ! Quand vous désallouez, faites le proprement :) ! (NULL)</li>\r\n</ul>\r\n</li>\r\n	<li>Si ca ne marche pas, deux options :\r\n<ul>\r\n	<li>Mettre des <em>cout </em>un peu partout dans le code, pour cerner le problème et essayer de comprendre (a peu près) d''ou il vient. C''est l''option simple mais longue, peu efficace et (surtout) très moche :P.</li>\r\n	<li>Le débugger (de préférence à la main). <a title="Documentation GDB" href="http://doc.ubuntu-fr.org/gdb" target="_blank">GDB</a> le fait très bien (debugger par défaut de gcc <strong>sous linux</strong>), un peu complexe à prendre en main pour les grands débutants.</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n&nbsp;\r\n\r\n&nbsp;', 'Marivaudages 2 :', '', 'inherit', 'open', 'open', '', '102-revision-2', '', '', '2012-05-02 19:27:37', '2012-05-02 17:27:37', '', 102, 'http://lambdaweb.free.fr/blog/?p=109', 0, 'revision', '', 0),
(110, 2, '2012-05-02 09:27:41', '2012-05-02 07:27:41', 'yo yow yow, youpie hey, yeepe hooow ! <a href="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011.jpg"><img class="alignnone size-medium wp-image-69" title="05042012011" src="http://lambdaweb.free.fr/blog/wp-content/uploads/05042012011-300x168.jpg" alt="" width="300" height="168" /></a>', 'yo', '', 'inherit', 'open', 'open', '', '96-revision-2', '', '', '2012-05-02 09:27:41', '2012-05-02 07:27:41', '', 96, 'http://lambdaweb.free.fr/blog/?p=110', 0, 'revision', '', 0),
(111, 2, '2012-05-02 04:48:12', '2012-05-02 02:48:12', 'moi aussi j''ai écrit queque <strong>chose, mais </strong>sur mon <em>ordi</em> cette <span style="text-decoration: underline;">fois !</span>', 'éh', '', 'inherit', 'open', 'open', '', '93-revision-2', '', '', '2012-05-02 04:48:12', '2012-05-02 02:48:12', '', 93, 'http://lambdaweb.free.fr/blog/?p=111', 0, 'revision', '', 0),
(112, 2, '2012-05-02 04:31:21', '2012-05-02 02:31:21', 'Je viens d''écrire ca sur mon mobile!', 'Test mobile', '', 'inherit', 'open', 'open', '', '90-revision-3', '', '', '2012-05-02 04:31:21', '2012-05-02 02:31:21', '', 90, 'http://lambdaweb.free.fr/blog/?p=112', 0, 'revision', '', 0),
(113, 2, '2012-05-02 03:58:13', '2012-05-02 01:58:13', 'un simple article de test.', 'simple test', '', 'inherit', 'open', 'open', '', '87-revision-2', '', '', '2012-05-02 03:58:13', '2012-05-02 01:58:13', '', 87, 'http://lambdaweb.free.fr/blog/?p=113', 0, 'revision', '', 0),
(114, 2, '2011-05-19 17:38:44', '2011-05-19 15:38:44', '<h2>Niark ! Ca y est !</h2>\r\nSite fini, blog fini, pub finie ! Reste plus qu''a attendre la mise en place d''un serveur web définitif  !\r\n\r\n<img class="alignnone" title="Epic Win !" src="http://lambdaweb.free.fr/images/EpicWin.jpg" alt="Epic Win !" width="500" height="620" />', 'End of beginning', '', 'inherit', 'open', 'open', '', '1-revision-4', '', '', '2011-05-19 17:38:44', '2011-05-19 15:38:44', '', 1, 'http://lambdaweb.free.fr/blog/?p=114', 0, 'revision', '', 0),
(115, 2, '2012-05-03 10:11:59', '2012-05-03 08:11:59', 'Voila, une simple petite news de test, avec ma foi pas grand chose à mettre dedans. Qu''est ce que je pourrais bien mettre... <strong>Du gras ? </strong>Un peu classique quand même. Je me déçois moi même... Une musique ? Ah ça ouais ! <a href="http://lambdaweb.free.fr/blog/wp-content/uploads/Georges-Brassens-La-pucelle1.mp3">Georges Brassens - La pucelle</a>', 'News de test', '', 'publish', 'open', 'open', '', 'news-de-test', '', '', '2012-05-03 10:11:59', '2012-05-03 08:11:59', '', 0, 'http://lambdaweb.free.fr/blog/?p=115', 0, 'post', '', 0),
(116, 2, '2012-05-03 10:11:11', '2012-05-03 08:11:11', 'Une très belle chanson de Georges Brassens. Oui, il fallait mettre quelque chose...', 'Georges Brassens - La pucelle', 'Georges Brassens - La pucelle', 'inherit', 'open', 'open', '', 'georges-brassens-la-pucelle1', '', '', '2012-05-03 10:11:11', '2012-05-03 08:11:11', '', 115, 'http://lambdaweb.free.fr/blog/wp-content/uploads/Georges-Brassens-La-pucelle1.mp3', 0, 'attachment', 'audio/mpeg', 0),
(117, 2, '2012-05-03 10:09:15', '2012-05-03 08:09:15', 'Voila, une simple petite news de test, avec ma foi pas grand chose à mettre dedans. Qu''est ce que je pourrais bien mettre... <strong>Du gras ? </strong>Un peu c', 'News de test', '', 'inherit', 'open', 'open', '', '115-revision', '', '', '2012-05-03 10:09:15', '2012-05-03 08:09:15', '', 115, 'http://lambdaweb.free.fr/blog/?p=117', 0, 'revision', '', 0),
(118, 2, '2012-05-03 12:45:30', '2012-05-03 10:45:30', 'Voila, une simple petite news de test, avec ma foi pas grand chose à mettre dedans. Qu''est ce que je pourrais bien mettre... <strong>Du gras ? </strong>Un peu classique quand même. Je me déçois moi même... Une musique ? Ah ça ouais ! <a href="http://lambdaweb.free.fr/blog/wp-content/uploads/Georges-Brassens-La-pucelle1.mp3">Georges Brassens - La pucelle</a>', 'News de test', '', 'inherit', 'open', 'open', '', '115-autosave', '', '', '2012-05-03 12:45:30', '2012-05-03 10:45:30', '', 115, 'http://lambdaweb.free.fr/blog/?p=118', 0, 'revision', '', 0),
(119, 2, '2012-05-03 19:43:49', '2012-05-03 17:43:49', '', '03052012021.jpg', '', 'inherit', 'open', 'open', '', '03052012021-jpg', '', '', '2012-05-03 19:43:49', '2012-05-03 17:43:49', '', 120, 'http://lambdaweb.free.fr/blog/wp-content/uploads/03052012021.jpg', 0, 'attachment', '', 0),
(120, 2, '2012-05-03 19:42:05', '2012-05-03 17:42:05', 'Kikoolol !\n<p><a href="http://lambdaweb.free.fr/blog/wp-content/uploads/03052012021.jpg"><img class="alignnone size-full wp-image-6" src="http://lambdaweb.free.fr/blog/wp-content/uploads/03052012021.jpg" /></a></p>', '', '', 'publish', 'open', 'open', '', '120', '', '', '2012-05-03 19:44:02', '2012-05-03 17:44:02', '', 0, 'http://lambdaweb.free.fr/blog/?p=120', 0, 'post', '', 0),
(121, 2, '2012-05-03 19:42:05', '2012-05-03 17:42:05', 'Kikoolol !\n<p><a href="http://lambdaweb.free.fr/blog/wp-content/uploads/03052012021.jpg"><img class="alignnone size-full wp-image-6" src="http://lambdaweb.free.fr/blog/wp-content/uploads/03052012021.jpg" /></a></p>', '', '', 'inherit', 'open', 'open', '', '120-revision', '', '', '2012-05-03 19:42:05', '2012-05-03 17:42:05', '', 120, 'http://lambdaweb.free.fr/blog/?p=121', 0, 'revision', '', 0),
(122, 1, '2012-05-03 10:07:57', '2012-05-03 08:07:57', '<h2>Niark ! Ca y est !</h2>\r\nSite fini, blog fini, pub finie ! Reste plus qu''a attendre la mise en place d''un serveur web définitif  !\r\n\r\n<img class="alignnone" title="Epic Win !" src="http://lambdaweb.free.fr/images/EpicWin.jpg" alt="Epic Win !" width="500" height="620" />', 'End of beginning', '', 'inherit', 'open', 'open', '', '1-revision-5', '', '', '2012-05-03 10:07:57', '2012-05-03 08:07:57', '', 1, 'http://lambdaweb.free.fr/blog/?p=122', 0, 'revision', '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int(255) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id_categorie`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom`) VALUES
(4, 'Code'),
(3, 'Compte Rendu'),
(5, 'Indéfini'),
(1, 'Schéma');

-- --------------------------------------------------------

--
-- Structure de la table `categoriea`
--

CREATE TABLE IF NOT EXISTS `categoriea` (
  `id_categorie` int(11) NOT NULL AUTO_INCREMENT,
  `titre_categorie` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_categorie`),
  KEY `FK_categ_utili` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Contenu de la table `categoriea`
--

INSERT INTO `categoriea` (`id_categorie`, `titre_categorie`, `id_user`) VALUES
(9, 'Arbre', 11),
(10, 'Chouette', 11),
(11, 'Petits animaux de la forêt', 11),
(13, 'Marseille', 11),
(14, 'Animaux sauvage', 11),
(16, 'Mallox', 11),
(17, 'Choc', 11),
(23, 'flute', 11);

-- --------------------------------------------------------

--
-- Structure de la table `categorie_page_lw`
--

CREATE TABLE IF NOT EXISTS `categorie_page_lw` (
  `id_categorie_page` int(11) NOT NULL AUTO_INCREMENT,
  `nom_categorie_page` varchar(255) NOT NULL,
  `description_categorie_page` varchar(255) DEFAULT 'Aucune description',
  PRIMARY KEY (`id_categorie_page`),
  UNIQUE KEY `nom_categorie_page` (`nom_categorie_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `categorie_page_lw`
--

INSERT INTO `categorie_page_lw` (`id_categorie_page`, `nom_categorie_page`, `description_categorie_page`) VALUES
(1, 'Aucune', 'Aucune description'),
(2, 'Profil', 'Votre profil'),
(3, 'Gestion', 'Gestion administrative de lambdaweb');

-- --------------------------------------------------------

--
-- Structure de la table `client_lw`
--

CREATE TABLE IF NOT EXISTS `client_lw` (
  `id_client` int(255) NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(255) NOT NULL,
  `entreprise_client` varchar(255) NOT NULL,
  `adresse_client` varchar(255) NOT NULL,
  `site_web_client` varchar(255) DEFAULT NULL,
  `email_client` varchar(255) NOT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(255) NOT NULL AUTO_INCREMENT,
  `auteur` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `date_creation` datetime NOT NULL,
  `id_article` int(255) NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `id_auteur` (`auteur`),
  KEY `id_article` (`id_article`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `auteur`, `commentaire`, `date_creation`, `id_article`) VALUES
(10, 'andré', 'Joli !', '2012-11-03 08:23:57', 7);

-- --------------------------------------------------------

--
-- Structure de la table `commentairea`
--

CREATE TABLE IF NOT EXISTS `commentairea` (
  `id_commentaire` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` text,
  `date_commentaire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_user` int(11) DEFAULT NULL,
  `id_photo` int(11) NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `FK_comment_utili` (`id_user`),
  KEY `FK_comment_photo` (`id_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `commentairea`
--

INSERT INTO `commentairea` (`id_commentaire`, `commentaire`, `date_commentaire`, `id_user`, `id_photo`) VALUES
(3, 'Quelle fleur magnifique !', '2012-12-02 17:27:23', 11, 34);

-- --------------------------------------------------------

--
-- Structure de la table `contrat_lw`
--

CREATE TABLE IF NOT EXISTS `contrat_lw` (
  `id_contrat` int(255) NOT NULL AUTO_INCREMENT,
  `nom_contrat` varchar(255) NOT NULL,
  `description_contrat` text NOT NULL,
  `date_contrat` date NOT NULL,
  `etat_contrat` int(255) NOT NULL,
  `prix_contrat` double NOT NULL,
  `id_client_contrat` int(255) NOT NULL,
  PRIMARY KEY (`id_contrat`),
  KEY `id_client_contrat` (`id_client_contrat`),
  KEY `etat_contrat` (`etat_contrat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `countries_lw`
--

CREATE TABLE IF NOT EXISTS `countries_lw` (
  `id_pays` int(255) NOT NULL AUTO_INCREMENT,
  `code` int(3) NOT NULL,
  `alpha2` varchar(2) NOT NULL,
  `alpha3` varchar(3) NOT NULL,
  `langCS` varchar(45) NOT NULL,
  `langDE` varchar(45) NOT NULL,
  `langEN` varchar(45) NOT NULL,
  `langES` varchar(45) NOT NULL,
  `langFR` varchar(45) NOT NULL,
  `langIT` varchar(45) NOT NULL,
  `langNL` varchar(45) NOT NULL,
  PRIMARY KEY (`id_pays`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=242 ;

--
-- Contenu de la table `countries_lw`
--

INSERT INTO `countries_lw` (`id_pays`, `code`, `alpha2`, `alpha3`, `langCS`, `langDE`, `langEN`, `langES`, `langFR`, `langIT`, `langNL`) VALUES
(1, 4, 'AF', 'AFG', 'Afghanistán', 'Afghanistan', 'Afghanistan', 'Afganistán', 'Afghanistan', 'Afghanistan', 'Afghanistan'),
(2, 8, 'AL', 'ALB', 'Albánie', 'Albanien', 'Albania', 'Albania', 'Albanie', 'Albania', 'Albanië'),
(3, 10, 'AQ', 'ATA', 'Antarctica', 'Antarktis', 'Antarctica', 'Antartida', 'Antarctique', 'Antartide', 'Antarctica'),
(4, 12, 'DZ', 'DZA', 'Alžírsko', 'Algerien', 'Algeria', 'Argelia', 'Algérie', 'Algeria', 'Algerije'),
(5, 16, 'AS', 'ASM', 'Americká Samoa', 'Amerikanisch-Samoa', 'American Samoa', 'Samoa americana', 'Samoa Américaines', 'Samoa Americane', 'Amerikaans Samoa'),
(6, 20, 'AD', 'AND', 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'Andorre', 'Andorra', 'Andorra'),
(7, 24, 'AO', 'AGO', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola'),
(8, 28, 'AG', 'ATG', 'Antigua a Barbuda', 'Antigua und Barbuda', 'Antigua and Barbuda', 'Antigua y Barbuda', 'Antigua-et-Barbuda', 'Antigua e Barbuda', 'Antigua en Barbuda'),
(9, 31, 'AZ', 'AZE', 'Azerbajdžán', 'Aserbaidschan', 'Azerbaijan', 'Azerbaiyán', 'Azerbaïdjan', 'Azerbaijan', 'Azerbeidzjan'),
(10, 32, 'AR', 'ARG', 'Argentina', 'Argentinien', 'Argentina', 'Argentina', 'Argentine', 'Argentina', 'Argentinië'),
(11, 36, 'AU', 'AUS', 'Austrálie', 'Australien', 'Australia', 'Australia', 'Australie', 'Australia', 'Australië'),
(12, 40, 'AT', 'AUT', 'Rakousko', 'Österreich', 'Austria', 'Austria', 'Autriche', 'Austria', 'Oostenrijk'),
(13, 44, 'BS', 'BHS', 'Bahamy', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahama''s'),
(14, 48, 'BH', 'BHR', 'Bahrajn', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahreïn', 'Bahrain', 'Bahrein'),
(15, 50, 'BD', 'BGD', 'Bangladéš', 'Bangladesch', 'Bangladesh', 'Bangladesh', 'Bangladesh', 'Bangladesh', 'Bangladesh'),
(16, 51, 'AM', 'ARM', 'Arménie', 'Armenien', 'Armenia', 'Armenia', 'Arménie', 'Armenia', 'Armenië'),
(17, 52, 'BB', 'BRB', 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'Barbade', 'Barbados', 'Barbados'),
(18, 56, 'BE', 'BEL', 'Belgie', 'Belgien', 'Belgium', 'Bélgica', 'Belgique', 'Belgio', 'België'),
(19, 60, 'BM', 'BMU', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermudes', 'Bermuda', 'Bermuda'),
(20, 64, 'BT', 'BTN', 'Bhután', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhoutan', 'Bhutan', 'Bhutan'),
(21, 68, 'BO', 'BOL', 'Bolívie', 'Bolivien', 'Bolivia', 'Bolivia', 'Bolivie', 'Bolivia', 'Bolivia'),
(22, 70, 'BA', 'BIH', 'Bosna a Hercegovina', 'Bosnien und Herzegowina', 'Bosnia and Herzegovina', 'Bosnia y Herzegovina', 'Bosnie-Herzégovine', 'Bosnia Erzegovina', 'Bosnië-Herzegovina'),
(23, 72, 'BW', 'BWA', 'Botswana', 'Botswana', 'Botswana', 'Botswana', 'Botswana', 'Botswana', 'Botswana'),
(24, 74, 'BV', 'BVT', 'Bouvet Island', 'Bouvetinsel', 'Bouvet Island', 'Isla Bouvet', 'Île Bouvet', 'Isola di Bouvet', 'Bouvet'),
(25, 76, 'BR', 'BRA', 'Brazílie', 'Brasilien', 'Brazil', 'Brasil', 'Brésil', 'Brasile', 'Brazilië'),
(26, 84, 'BZ', 'BLZ', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize'),
(27, 86, 'IO', 'IOT', 'Britské Indickooceánské teritorium', 'Britisches Territorium im Indischen Ozean', 'British Indian Ocean Territory', 'Territorio Oceánico de la India Británica', 'Territoire Britannique de l''Océan Indien', 'Territori Britannici dell''Oceano Indiano', 'British Indian Ocean Territory'),
(28, 90, 'SB', 'SLB', 'Šalamounovy ostrovy', 'Salomonen', 'Solomon Islands', 'Islas Salomón', 'Îles Salomon', 'Isole Solomon', 'Salomonseilanden'),
(29, 92, 'VG', 'VGB', 'Britské Panenské ostrovy', 'Britische Jungferninseln', 'British Virgin Islands', 'Islas Vírgenes Británicas', 'Îles Vierges Britanniques', 'Isole Vergini Britanniche', 'Britse Maagdeneilanden'),
(30, 96, 'BN', 'BRN', 'Brunej', 'Brunei Darussalam', 'Brunei Darussalam', 'Brunei Darussalam', 'Brunéi Darussalam', 'Brunei Darussalam', 'Brunei'),
(31, 100, 'BG', 'BGR', 'Bulharsko', 'Bulgarien', 'Bulgaria', 'Bulgaria', 'Bulgarie', 'Bulgaria', 'Bulgarije'),
(32, 104, 'MM', 'MMR', 'Myanmar', 'Myanmar', 'Myanmar', 'Mianmar', 'Myanmar', 'Myanmar', 'Myanmar'),
(33, 108, 'BI', 'BDI', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi'),
(34, 112, 'BY', 'BLR', 'Bělorusko', 'Belarus', 'Belarus', 'Belarus', 'Bélarus', 'Bielorussia', 'Wit-Rusland'),
(35, 116, 'KH', 'KHM', 'Kambodža', 'Kambodscha', 'Cambodia', 'Camboya', 'Cambodge', 'Cambogia', 'Cambodja'),
(36, 120, 'CM', 'CMR', 'Kamerun', 'Kamerun', 'Cameroon', 'Camerún', 'Cameroun', 'Camerun', 'Kameroen'),
(37, 124, 'CA', 'CAN', 'Kanada', 'Kanada', 'Canada', 'Canadá', 'Canada', 'Canada', 'Canada'),
(38, 132, 'CV', 'CPV', 'Ostrovy Zeleného mysu', 'Kap Verde', 'Cape Verde', 'Cabo Verde', 'Cap-vert', 'Capo Verde', 'Kaapverdië'),
(39, 136, 'KY', 'CYM', 'Kajmanské ostrovy', 'Kaimaninseln', 'Cayman Islands', 'Islas Caimán', 'Îles Caïmanes', 'Isole Cayman', 'Caymaneilanden'),
(40, 140, 'CF', 'CAF', 'Středoafrická republika', 'Zentralafrikanische Republik', 'Central African', 'República Centroafricana', 'République Centrafricaine', 'Repubblica Centroafricana', 'Centraal-Afrikaanse Republiek'),
(41, 144, 'LK', 'LKA', 'Srí Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka'),
(42, 148, 'TD', 'TCD', 'Čad', 'Tschad', 'Chad', 'Chad', 'Tchad', 'Ciad', 'Tsjaad'),
(43, 152, 'CL', 'CHL', 'Chile', 'Chile', 'Chile', 'Chile', 'Chili', 'Cile', 'Chili'),
(44, 156, 'CN', 'CHN', 'Čína', 'China', 'China', 'China', 'Chine', 'Cina', 'China'),
(45, 158, 'TW', 'TWN', 'Tchajwan', 'Taiwan', 'Taiwan', 'Taiwán', 'Taïwan', 'Taiwan', 'Taiwan'),
(46, 162, 'CX', 'CXR', 'Christmas Island', 'Weihnachtsinsel', 'Christmas Island', 'Isla Navidad', 'Île Christmas', 'Isola di Natale', 'Christmaseiland'),
(47, 166, 'CC', 'CCK', 'Kokosové ostrovy', 'Kokosinseln', 'Cocos (Keeling) Islands', 'Islas Cocos (Keeling)', 'Îles Cocos (Keeling)', 'Isole Cocos', 'Cocoseilanden'),
(48, 170, 'CO', 'COL', 'Kolumbie', 'Kolumbien', 'Colombia', 'Colombia', 'Colombie', 'Colombia', 'Colombia'),
(49, 174, 'KM', 'COM', 'Komory', 'Komoren', 'Comoros', 'Comoros', 'Comores', 'Comore', 'Comoren'),
(50, 175, 'YT', 'MYT', 'Mayotte', 'Mayotte', 'Mayotte', 'Mayote', 'Mayotte', 'Mayotte', 'Mayotte'),
(51, 178, 'CG', 'COG', 'Konžská republika Kongo', 'Republik Kongo', 'Republic of the Congo', 'Congo', 'République du Congo', 'Repubblica del Congo', 'Republiek Congo'),
(52, 180, 'CD', 'COD', 'Demokratická republika Kongo Kongo', 'Demokratische Republik Kongo', 'The Democratic Republic Of The Congo', 'República Democrática del Congo', 'République Démocratique du Congo', 'Repubblica Democratica del Congo', 'Democratische Republiek Congo'),
(53, 184, 'CK', 'COK', 'Cookovy ostrovy', 'Cookinseln', 'Cook Islands', 'Islas Cook', 'Îles Cook', 'Isole Cook', 'Cookeilanden'),
(54, 188, 'CR', 'CRI', 'Kostarika', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica'),
(55, 191, 'HR', 'HRV', 'Chorvatsko', 'Kroatien', 'Croatia', 'Croacia', 'Croatie', 'Croazia', 'Kroatië'),
(56, 192, 'CU', 'CUB', 'Kuba', 'Kuba', 'Cuba', 'Cuba', 'Cuba', 'Cuba', 'Cuba'),
(57, 196, 'CY', 'CYP', 'Kypr', 'Zypern', 'Cyprus', 'Chipre', 'Chypre', 'Cipro', 'Cyprus'),
(58, 203, 'CZ', 'CZE', 'Česko', 'Tschechische Republik', 'Czech Republic', 'Chequia', 'République Tchèque', 'Repubblica Ceca', 'Tsjechië'),
(59, 204, 'BJ', 'BEN', 'Benin', 'Benin', 'Benin', 'Benin', 'Bénin', 'Benin', 'Benin'),
(60, 208, 'DK', 'DNK', 'Dánsko', 'Dänemark', 'Denmark', 'Dinamarca', 'Danemark', 'Danimarca', 'Denemarken'),
(61, 212, 'DM', 'DMA', 'Dominika', 'Dominica', 'Dominica', 'Dominica', 'Dominique', 'Dominica', 'Dominica'),
(62, 214, 'DO', 'DOM', 'Dominikánská republika', 'Dominikanische Republik', 'Dominican Republic', 'República Dominicana', 'République Dominicaine', 'Repubblica Dominicana', 'Dominicaanse Republiek'),
(63, 218, 'EC', 'ECU', 'Ekvádor', 'Ecuador', 'Ecuador', 'Ecuador', 'Équateur', 'Ecuador', 'Ecuador'),
(64, 222, 'SV', 'SLV', 'Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador'),
(65, 226, 'GQ', 'GNQ', 'Rovníková Guinea', 'Äquatorialguinea', 'Equatorial Guinea', 'Guinea Ecuatorial', 'Guinée Équatoriale', 'Guinea Equatoriale', 'Equatoriaal Guinea'),
(66, 231, 'ET', 'ETH', 'Etiopie', 'Äthiopien', 'Ethiopia', 'Etiopía', 'Éthiopie', 'Etiopia', 'Ethiopië'),
(67, 232, 'ER', 'ERI', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea', 'Érythrée', 'Eritrea', 'Eritrea'),
(68, 233, 'EE', 'EST', 'Estonsko', 'Estland', 'Estonia', 'Estonia', 'Estonie', 'Estonia', 'Estland'),
(69, 234, 'FO', 'FRO', 'Faerské ostrovy', 'Färöer', 'Faroe Islands', 'Islas Faroe', 'Îles Féroé', 'Isole Faroe', 'Faeröer'),
(70, 238, 'FK', 'FLK', 'Falklandské ostrovy', 'Falklandinseln', 'Falkland Islands', 'Islas Malvinas', 'Îles (malvinas) Falkland', 'Isole Falkland', 'Falklandeilanden'),
(71, 239, 'GS', 'SGS', 'Jižní Georgie a Jižní Sandwichovy ostrovy', 'Südgeorgien und die Südlichen Sandwichinseln', 'South Georgia and the South Sandwich Islands', 'Georgia del Sur e Islas Sandwich del Sur', 'Géorgie du Sud et les Îles Sandwich du Sud', 'Sud Georgia e Isole Sandwich', 'Zuid-Georgië en de Zuidelijke Sandwicheilande'),
(72, 242, 'FJ', 'FJI', 'Fidži', 'Fidschi', 'Fiji', 'Fiji', 'Fidji', 'Fiji', 'Fiji'),
(73, 246, 'FI', 'FIN', 'Finsko', 'Finnland', 'Finland', 'Finlandia', 'Finlande', 'Finlandia', 'Finland'),
(74, 248, 'AX', 'ALA', 'Åland Islands', 'Åland-Inseln', 'Åland Islands', 'IslasÅland', 'Îles Åland', 'Åland Islands', 'Åland Islands'),
(75, 250, 'FR', 'FRA', 'Francie', 'Frankreich', 'France', 'Francia', 'France', 'Francia', 'Frankrijk'),
(76, 254, 'GF', 'GUF', 'Francouzská Guayana', 'Französisch-Guayana', 'French Guiana', 'Guinea Francesa', 'Guyane Française', 'Guyana Francese', 'Frans-Guyana'),
(77, 258, 'PF', 'PYF', 'Francouzská Polynésie', 'Französisch-Polynesien', 'French Polynesia', 'Polinesia Francesa', 'Polynésie Française', 'Polinesia Francese', 'Frans-Polynesië'),
(78, 260, 'TF', 'ATF', 'Francouzská jižní teritoria', 'Französische Süd- und Antarktisgebiete', 'French Southern Territories', 'Territorios Sureños de Francia', 'Terres Australes Françaises', 'Territori Francesi del Sud', 'Franse Zuidelijke en Antarctische gebieden'),
(79, 262, 'DJ', 'DJI', 'Džibutsko', 'Dschibuti', 'Djibouti', 'Djibouti', 'Djibouti', 'Gibuti', 'Djibouti'),
(80, 266, 'GA', 'GAB', 'Gabon', 'Gabun', 'Gabon', 'Gabón', 'Gabon', 'Gabon', 'Gabon'),
(81, 268, 'GE', 'GEO', 'Gruzínsko', 'Georgien', 'Georgia', 'Georgia', 'Géorgie', 'Georgia', 'Georgië'),
(82, 270, 'GM', 'GMB', 'Gambie', 'Gambia', 'Gambia', 'Gambia', 'Gambie', 'Gambia', 'Gambia'),
(83, 275, 'PS', 'PSE', 'Palestinská území', 'Palästinensische Autonomiegebiete', 'Occupied Palestinian Territory', 'Palestina', 'Territoire Palestinien Occupé', 'Territori Palestinesi Occupati', 'Palestina'),
(84, 276, 'DE', 'DEU', 'Německo', 'Deutschland', 'Germany', 'Alemania', 'Allemagne', 'Germania', 'Duitsland'),
(85, 288, 'GH', 'GHA', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana'),
(86, 292, 'GI', 'GIB', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibilterra', 'Gibraltar'),
(87, 296, 'KI', 'KIR', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati'),
(88, 300, 'GR', 'GRC', 'Řecko', 'Griechenland', 'Greece', 'Grecia', 'Grèce', 'Grecia', 'Griekenland'),
(89, 304, 'GL', 'GRL', 'Grónsko', 'Grönland', 'Greenland', 'Groenlandia', 'Groenland', 'Groenlandia', 'Groenland'),
(90, 308, 'GD', 'GRD', 'Grenada', 'Grenada', 'Grenada', 'Granada', 'Grenade', 'Grenada', 'Grenada'),
(91, 312, 'GP', 'GLP', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadalupe', 'Guadeloupe', 'Guadalupa', 'Guadeloupe'),
(92, 316, 'GU', 'GUM', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam'),
(93, 320, 'GT', 'GTM', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala'),
(94, 324, 'GN', 'GIN', 'Guinea', 'Guinea', 'Guinea', 'Guinea', 'Guinée', 'Guinea', 'Guinee'),
(95, 328, 'GY', 'GUY', 'Guyana', 'Guyana', 'Guyana', 'Guayana', 'Guyana', 'Guyana', 'Guyana'),
(96, 332, 'HT', 'HTI', 'Haiti', 'Haiti', 'Haiti', 'Haití', 'Haïti', 'Haiti', 'Haiti'),
(97, 334, 'HM', 'HMD', 'Heardův ostrov a McDonaldovy ostrovy', 'Heard und McDonaldinseln', 'Heard Island and McDonald Islands', 'Islas Heard e Islas McDonald', 'Îles Heard et Mcdonald', 'Isola Heard e Isole McDonald', 'Heard- en McDonaldeilanden'),
(98, 336, 'VA', 'VAT', 'Vatikán', 'Vatikanstadt', 'Vatican City State', 'Estado Vaticano', 'Saint-Siège (état de la Cité du Vatican)', 'Città del Vaticano', 'Vaticaanstad'),
(99, 340, 'HN', 'HND', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras'),
(100, 344, 'HK', 'HKG', 'Hong Kong', 'Hongkong', 'Hong Kong', 'Hong Kong', 'Hong-Kong', 'Hong Kong', 'Hongkong'),
(101, 348, 'HU', 'HUN', 'Maďarsko', 'Ungarn', 'Hungary', 'Hungría', 'Hongrie', 'Ungheria', 'Hongarije'),
(102, 352, 'IS', 'ISL', 'Island', 'Island', 'Iceland', 'Islandia', 'Islande', 'Islanda', 'IJsland'),
(103, 356, 'IN', 'IND', 'Indie', 'Indien', 'India', 'India', 'Inde', 'India', 'India'),
(104, 360, 'ID', 'IDN', 'Indonésie', 'Indonesien', 'Indonesia', 'Indonesia', 'Indonésie', 'Indonesia', 'Indonesië'),
(105, 364, 'IR', 'IRN', 'Írán', 'Islamische Republik Iran', 'Islamic Republic of Iran', 'Irán', 'République Islamique d''Iran', 'Iran', 'Iran'),
(106, 368, 'IQ', 'IRQ', 'Irák', 'Irak', 'Iraq', 'Irak', 'Iraq', 'Iraq', 'Irak'),
(107, 372, 'IE', 'IRL', 'Irsko', 'Irland', 'Ireland', 'Irlanda', 'Irlande', 'Eire', 'Ierland'),
(108, 376, 'IL', 'ISR', 'Izrael', 'Israel', 'Israel', 'Israel', 'Israël', 'Israele', 'Israël'),
(109, 380, 'IT', 'ITA', 'Itálie', 'Italien', 'Italy', 'Italia', 'Italie', 'Italia', 'Italië'),
(110, 384, 'CI', 'CIV', 'Pobřeží slonoviny', 'Côte d''Ivoire', 'Côte d''Ivoire', 'Costa de Marfil', 'Côte d''Ivoire', 'Costa d''Avorio', 'Ivoorkust'),
(111, 388, 'JM', 'JAM', 'Jamajka', 'Jamaika', 'Jamaica', 'Jamaica', 'Jamaïque', 'Giamaica', 'Jamaica'),
(112, 392, 'JP', 'JPN', 'Japonsko', 'Japan', 'Japan', 'Japón', 'Japon', 'Giappone', 'Japan'),
(113, 398, 'KZ', 'KAZ', 'Kazachstán', 'Kasachstan', 'Kazakhstan', 'Kazajstán', 'Kazakhstan', 'Kazakhistan', 'Kazachstan'),
(114, 400, 'JO', 'JOR', 'Jordánsko', 'Jordanien', 'Jordan', 'Jordania', 'Jordanie', 'Giordania', 'Jordanië'),
(115, 404, 'KE', 'KEN', 'Keňa', 'Kenia', 'Kenya', 'Kenia', 'Kenya', 'Kenya', 'Kenia'),
(116, 408, 'KP', 'PRK', 'Severní Korea', 'Demokratische Volksrepublik Korea', 'Democratic People''s Republic of Korea', 'Corea', 'République Populaire Démocratique de Corée', 'Corea del Nord', 'Noord-Korea'),
(117, 410, 'KR', 'KOR', 'Jižní Korea', 'Republik Korea', 'Republic of Korea', 'Corea', 'République de Corée', 'Corea del Sud', 'Zuid-Korea'),
(118, 414, 'KW', 'KWT', 'Kuvajt', 'Kuwait', 'Kuwait', 'Kuwait', 'Koweït', 'Kuwait', 'Koeweit'),
(119, 417, 'KG', 'KGZ', 'Kyrgyzstán', 'Kirgisistan', 'Kyrgyzstan', 'Kirgistán', 'Kirghizistan', 'Kirghizistan', 'Kirgizië'),
(120, 418, 'LA', 'LAO', 'Laos', 'Demokratische Volksrepublik Laos', 'Lao People''s Democratic Republic', 'Laos', 'République Démocratique Populaire Lao', 'Laos', 'Laos'),
(121, 422, 'LB', 'LBN', 'Libanon', 'Libanon', 'Lebanon', 'Líbano', 'Liban', 'Libano', 'Libanon'),
(122, 426, 'LS', 'LSO', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesoto', 'Lesotho', 'Lesotho', 'Lesotho'),
(123, 428, 'LV', 'LVA', 'Lotyšsko', 'Lettland', 'Latvia', 'Letonia', 'Lettonie', 'Lettonia', 'Letland'),
(124, 430, 'LR', 'LBR', 'Libérie', 'Liberia', 'Liberia', 'Liberia', 'Libéria', 'Liberia', 'Liberia'),
(125, 434, 'LY', 'LBY', 'Libye', 'Libysch-Arabische Dschamahirija', 'Libyan Arab Jamahiriya', 'Libia', 'Jamahiriya Arabe Libyenne', 'Libia', 'Libië'),
(126, 438, 'LI', 'LIE', 'Lichtenštejnsko', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein'),
(127, 440, 'LT', 'LTU', 'Litva', 'Litauen', 'Lithuania', 'Lituania', 'Lituanie', 'Lituania', 'Litouwen'),
(128, 442, 'LU', 'LUX', 'Lucembursko', 'Luxemburg', 'Luxembourg', 'Luxemburgo', 'Luxembourg', 'Lussemburgo', 'Groothertogdom Luxemburg'),
(129, 446, 'MO', 'MAC', 'Macao', 'Macao', 'Macao', 'Macao', 'Macao', 'Macao', 'Macao'),
(130, 450, 'MG', 'MDG', 'Madagaskar', 'Madagaskar', 'Madagascar', 'Madagascar', 'Madagascar', 'Madagascar', 'Madagaskar'),
(131, 454, 'MW', 'MWI', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi'),
(132, 458, 'MY', 'MYS', 'Malajsie', 'Malaysia', 'Malaysia', 'Malasia', 'Malaisie', 'Malesia', 'Maleisië'),
(133, 462, 'MV', 'MDV', 'Maledivy', 'Malediven', 'Maldives', 'Maldivas', 'Maldives', 'Maldive', 'Maldiven'),
(134, 466, 'ML', 'MLI', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali'),
(135, 470, 'MT', 'MLT', 'Malta', 'Malta', 'Malta', 'Malta', 'Malte', 'Malta', 'Malta'),
(136, 474, 'MQ', 'MTQ', 'Martinik', 'Martinique', 'Martinique', 'Martinica', 'Martinique', 'Martinica', 'Martinique'),
(137, 478, 'MR', 'MRT', 'Mauretánie', 'Mauretanien', 'Mauritania', 'Mauritania', 'Mauritanie', 'Mauritania', 'Mauritanië'),
(138, 480, 'MU', 'MUS', 'Mauricius', 'Mauritius', 'Mauritius', 'Mauricio', 'Maurice', 'Maurizius', 'Mauritius'),
(139, 484, 'MX', 'MEX', 'Mexiko', 'Mexiko', 'Mexico', 'México', 'Mexique', 'Messico', 'Mexico'),
(140, 492, 'MC', 'MCO', 'Monako', 'Monaco', 'Monaco', 'Mónaco', 'Monaco', 'Monaco', 'Monaco'),
(141, 496, 'MN', 'MNG', 'Mongolsko', 'Mongolei', 'Mongolia', 'Mongolia', 'Mongolie', 'Mongolia', 'Mongolië'),
(142, 498, 'MD', 'MDA', 'Moldavsko', 'Moldawien', 'Republic of Moldova', 'Moldavia', 'République de Moldova', 'Moldavia', 'Republiek Moldavië'),
(143, 500, 'MS', 'MSR', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat'),
(144, 504, 'MA', 'MAR', 'Maroko', 'Marokko', 'Morocco', 'Marruecos', 'Maroc', 'Marocco', 'Marokko'),
(145, 508, 'MZ', 'MOZ', 'Mosambik', 'Mosambik', 'Mozambique', 'Mozambique', 'Mozambique', 'Mozambico', 'Mozambique'),
(146, 512, 'OM', 'OMN', 'Omán', 'Oman', 'Oman', 'Omán', 'Oman', 'Oman', 'Oman'),
(147, 516, 'NA', 'NAM', 'Namíbie', 'Namibia', 'Namibia', 'Namibia', 'Namibie', 'Namibia', 'Namibië'),
(148, 520, 'NR', 'NRU', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru'),
(149, 524, 'NP', 'NPL', 'Nepál', 'Nepal', 'Nepal', 'Nepal', 'Népal', 'Nepal', 'Nepal'),
(150, 528, 'NL', 'NLD', 'Nizozemsko', 'Niederlande', 'Netherlands', 'Holanda', 'Pays-Bas', 'Paesi Bassi', 'Nederland'),
(151, 530, 'AN', 'ANT', 'Nizozemské Antily', 'Niederländische Antillen', 'Netherlands Antilles', 'Antillas Holandesas', 'Antilles Néerlandaises', 'Antille Olandesi', 'Nederlandse Antillen'),
(152, 533, 'AW', 'ABW', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba'),
(153, 540, 'NC', 'NCL', 'Nová Kaledonie', 'Neukaledonien', 'New Caledonia', 'Nueva Caledonia', 'Nouvelle-Calédonie', 'Nuova Caledonia', 'Nieuw-Caledonië'),
(154, 548, 'VU', 'VUT', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu'),
(155, 554, 'NZ', 'NZL', 'Nový Zéland', 'Neuseeland', 'New Zealand', 'Nueva Zelanda', 'Nouvelle-Zélande', 'Nuova Zelanda', 'Nieuw-Zeeland'),
(156, 558, 'NI', 'NIC', 'Nikaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua'),
(157, 562, 'NE', 'NER', 'Niger', 'Niger', 'Niger', 'Níger', 'Niger', 'Niger', 'Niger'),
(158, 566, 'NG', 'NGA', 'Nigérie', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigéria', 'Nigeria', 'Nigeria'),
(159, 570, 'NU', 'NIU', 'Niue', 'Niue', 'Niue', 'Niue', 'Niué', 'Niue', 'Niue'),
(160, 574, 'NF', 'NFK', 'Norfolk Island', 'Norfolkinsel', 'Norfolk Island', 'Islas Norfolk', 'Île Norfolk', 'Isola Norfolk', 'Norfolkeiland'),
(161, 578, 'NO', 'NOR', 'Norsko', 'Norwegen', 'Norway', 'Noruega', 'Norvège', 'Norvegia', 'Noorwegen'),
(162, 580, 'MP', 'MNP', 'Severomariánské ostrovy', 'Nördliche Marianen', 'Northern Mariana Islands', 'Islas de Norte-Mariana', 'Îles Mariannes du Nord', 'Isole Marianne Settentrionali', 'Noordelijke Marianen'),
(163, 581, 'UM', 'UMI', 'United States Minor Outlying Islands', 'Amerikanisch-Ozeanien', 'United States Minor Outlying Islands', 'Islas Ultramarinas de Estados Unidos', 'Îles Mineures Éloignées des États-Unis', 'Isole Minori degli Stati Uniti d''America', 'United States Minor Outlying Eilanden'),
(164, 583, 'FM', 'FSM', 'Mikronésie', 'Mikronesien', 'Federated States of Micronesia', 'Micronesia', 'États Fédérés de Micronésie', 'Stati Federati della Micronesia', 'Micronesië'),
(165, 584, 'MH', 'MHL', 'Marshallovy ostrovy', 'Marshallinseln', 'Marshall Islands', 'Islas Marshall', 'Îles Marshall', 'Isole Marshall', 'Marshalleilanden'),
(166, 585, 'PW', 'PLW', 'Palau', 'Palau', 'Palau', 'Palau', 'Palaos', 'Palau', 'Palau'),
(167, 586, 'PK', 'PAK', 'Pakistán', 'Pakistan', 'Pakistan', 'Pakistán', 'Pakistan', 'Pakistan', 'Pakistan'),
(168, 591, 'PA', 'PAN', 'Panama', 'Panama', 'Panama', 'Panamá', 'Panama', 'Panamá', 'Panama'),
(169, 598, 'PG', 'PNG', 'Papua Nová Guinea', 'Papua-Neuguinea', 'Papua New Guinea', 'Papúa Nueva Guinea', 'Papouasie-Nouvelle-Guinée', 'Papua Nuova Guinea', 'Papoea-Nieuw-Guinea'),
(170, 600, 'PY', 'PRY', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay'),
(171, 604, 'PE', 'PER', 'Peru', 'Peru', 'Peru', 'Perú', 'Pérou', 'Perù', 'Peru'),
(172, 608, 'PH', 'PHL', 'Filipíny', 'Philippinen', 'Philippines', 'Filipinas', 'Philippines', 'Filippine', 'Filippijnen'),
(173, 612, 'PN', 'PCN', 'Pitcairn', 'Pitcairninseln', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairneilanden'),
(174, 616, 'PL', 'POL', 'Polsko', 'Polen', 'Poland', 'Polonia', 'Pologne', 'Polonia', 'Polen'),
(175, 620, 'PT', 'PRT', 'Portugalsko', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portogallo', 'Portugal'),
(176, 624, 'GW', 'GNB', 'Guinea-Bissau', 'Guinea-Bissau', 'Guinea-Bissau', 'Guinea-Bissau', 'Guinée-Bissau', 'Guinea-Bissau', 'Guinee-Bissau'),
(177, 626, 'TL', 'TLS', 'Východní Timor', 'Timor-Leste', 'Timor-Leste', 'Timor Leste', 'Timor-Leste', 'Timor Est', 'Oost-Timor'),
(178, 630, 'PR', 'PRI', 'Portoriko', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Porto Rico', 'Porto Rico', 'Puerto Rico'),
(179, 634, 'QA', 'QAT', 'Katar', 'Katar', 'Qatar', 'Qatar', 'Qatar', 'Qatar', 'Qatar'),
(180, 638, 'RE', 'REU', 'Reunion', 'Réunion', 'Réunion', 'Reunión', 'Réunion', 'Reunion', 'Réunion'),
(181, 642, 'RO', 'ROU', 'Rumunsko', 'Rumänien', 'Romania', 'Rumanía', 'Roumanie', 'Romania', 'Roemenië'),
(182, 643, 'RU', 'RUS', 'Rusko', 'Russische Föderation', 'Russian Federation', 'Rusia', 'Fédération de Russie', 'Federazione Russa', 'Rusland'),
(183, 646, 'RW', 'RWA', 'Rwanda', 'Ruanda', 'Rwanda', 'Ruanda', 'Rwanda', 'Ruanda', 'Rwanda'),
(184, 654, 'SH', 'SHN', 'Svatá Helena', 'St. Helena', 'Saint Helena', 'Santa Helena', 'Sainte-Hélène', 'Sant''Elena', 'Sint-Helena'),
(185, 659, 'KN', 'KNA', 'Svatý Kitts a Nevis', 'St. Kitts und Nevis', 'Saint Kitts and Nevis', 'Santa Kitts y Nevis', 'Saint-Kitts-et-Nevis', 'Saint Kitts e Nevis', 'Saint Kitts en Nevis'),
(186, 660, 'AI', 'AIA', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla'),
(187, 662, 'LC', 'LCA', 'Svatá Lucie', 'St. Lucia', 'Saint Lucia', 'Santa Lucía', 'Sainte-Lucie', 'Santa Lucia', 'Saint Lucia'),
(188, 666, 'PM', 'SPM', 'Svatý Pierre a Miquelon', 'St. Pierre und Miquelon', 'Saint-Pierre and Miquelon', 'San Pedro y Miquelon', 'Saint-Pierre-et-Miquelon', 'Saint Pierre e Miquelon', 'Saint-Pierre en Miquelon'),
(189, 670, 'VC', 'VCT', 'Svatý Vincenc a Grenadiny', 'St. Vincent und die Grenadinen', 'Saint Vincent and the Grenadines', 'San Vincente y Las Granadinas', 'Saint-Vincent-et-les Grenadines', 'Saint Vincent e Grenadine', 'Saint Vincent en de Grenadines'),
(190, 674, 'SM', 'SMR', 'San Marino', 'San Marino', 'San Marino', 'San Marino', 'Saint-Marin', 'San Marino', 'San Marino'),
(191, 678, 'ST', 'STP', 'Svatý Tomáš a Princův ostrov', 'São Tomé und Príncipe', 'Sao Tome and Principe', 'Santo Tomé y Príncipe', 'Sao Tomé-et-Principe', 'Sao Tome e Principe', 'Sao Tomé en Principe'),
(192, 682, 'SA', 'SAU', 'Saudská Arábie', 'Saudi-Arabien', 'Saudi Arabia', 'Arabia Saudí', 'Arabie Saoudite', 'Arabia Saudita', 'Saoedi-Arabië'),
(193, 686, 'SN', 'SEN', 'Senegal', 'Senegal', 'Senegal', 'Senegal', 'Sénégal', 'Senegal', 'Senegal'),
(194, 690, 'SC', 'SYC', 'Seychely', 'Seychellen', 'Seychelles', 'Seychelles', 'Seychelles', 'Seychelles', 'Seychellen'),
(195, 694, 'SL', 'SLE', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leona', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone'),
(196, 702, 'SG', 'SGP', 'Singapur', 'Singapur', 'Singapore', 'Singapur', 'Singapour', 'Singapore', 'Singapore'),
(197, 703, 'SK', 'SVK', 'Slovensko', 'Slowakei', 'Slovakia', 'Eslovaquia', 'Slovaquie', 'Slovacchia', 'Slowakije'),
(198, 704, 'VN', 'VNM', 'Vietnam', 'Vietnam', 'Vietnam', 'Vietnam', 'Viet Nam', 'Vietnam', 'Vietnam'),
(199, 705, 'SI', 'SVN', 'Slovinsko', 'Slowenien', 'Slovenia', 'Eslovenia', 'Slovénie', 'Slovenia', 'Slovenië'),
(200, 706, 'SO', 'SOM', 'Somálsko', 'Somalia', 'Somalia', 'Somalia', 'Somalie', 'Somalia', 'Somalië'),
(201, 710, 'ZA', 'ZAF', 'Jižní Afrika', 'Südafrika', 'South Africa', 'Sudáfrica', 'Afrique du Sud', 'Sud Africa', 'Zuid-Afrika'),
(202, 716, 'ZW', 'ZWE', 'Zimbabwe', 'Simbabwe', 'Zimbabwe', 'Zimbabue', 'Zimbabwe', 'Zimbabwe', 'Zimbabwe'),
(203, 724, 'ES', 'ESP', 'Španělsko', 'Spanien', 'Spain', 'España', 'Espagne', 'Spagna', 'Spanje'),
(204, 732, 'EH', 'ESH', 'Západní Sahara', 'Westsahara', 'Western Sahara', 'Sáhara Occidental', 'Sahara Occidental', 'Sahara Occidentale', 'Westelijke Sahara'),
(205, 736, 'SD', 'SDN', 'Súdán', 'Sudan', 'Sudan', 'Sudán', 'Soudan', 'Sudan', 'Sudan'),
(206, 740, 'SR', 'SUR', 'Surinam', 'Suriname', 'Suriname', 'Surinám', 'Suriname', 'Suriname', 'Suriname'),
(207, 744, 'SJ', 'SJM', 'Špicberky a Jan Mayen', 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen', 'Esvalbard y Jan Mayen', 'Svalbard etÎle Jan Mayen', 'Svalbard e Jan Mayen', 'Svalbard'),
(208, 748, 'SZ', 'SWZ', 'Svazijsko', 'Swasiland', 'Swaziland', 'Suazilandia', 'Swaziland', 'Swaziland', 'Swaziland'),
(209, 752, 'SE', 'SWE', 'Švédsko', 'Schweden', 'Sweden', 'Suecia', 'Suède', 'Svezia', 'Zweden'),
(210, 756, 'CH', 'CHE', 'Švýcarsko', 'Schweiz', 'Switzerland', 'Suiza', 'Suisse', 'Svizzera', 'Zwitserland'),
(211, 760, 'SY', 'SYR', 'Sýrie', 'Arabische Republik Syrien', 'Syrian Arab Republic', 'Siria', 'République Arabe Syrienne', 'Siria', 'Syrië'),
(212, 762, 'TJ', 'TJK', 'Tadžikistán', 'Tadschikistan', 'Tajikistan', 'Tajikistán', 'Tadjikistan', 'Tagikistan', 'Tadzjikistan'),
(213, 764, 'TH', 'THA', 'Thajsko', 'Thailand', 'Thailand', 'Tailandia', 'Thaïlande', 'Tailandia', 'Thailand'),
(214, 768, 'TG', 'TGO', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo'),
(215, 772, 'TK', 'TKL', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau -eilanden'),
(216, 776, 'TO', 'TON', 'Tonga', 'Tonga', 'Tonga', 'Tongo', 'Tonga', 'Tonga', 'Tonga'),
(217, 780, 'TT', 'TTO', 'Trinidad a Tobago', 'Trinidad und Tobago', 'Trinidad and Tobago', 'Trinidad y Tobago', 'Trinité-et-Tobago', 'Trinidad e Tobago', 'Trinidad en Tobago'),
(218, 784, 'AE', 'ARE', 'Spojené Arabské Emiráty', 'Vereinigte Arabische Emirate', 'United Arab Emirates', 'EmiratosÁrabes Unidos', 'Émirats Arabes Unis', 'Emirati Arabi Uniti', 'Verenigde Arabische Emiraten'),
(219, 788, 'TN', 'TUN', 'Tunisko', 'Tunesien', 'Tunisia', 'Túnez', 'Tunisie', 'Tunisia', 'Tunesië'),
(220, 792, 'TR', 'TUR', 'Turecko', 'Türkei', 'Turkey', 'Turquía', 'Turquie', 'Turchia', 'Turkije'),
(221, 795, 'TM', 'TKM', 'Turkmenistán', 'Turkmenistan', 'Turkmenistan', 'Turmenistán', 'Turkménistan', 'Turkmenistan', 'Turkmenistan'),
(222, 796, 'TC', 'TCA', 'Turks a ostrovy Caicos', 'Turks- und Caicosinseln', 'Turks and Caicos Islands', 'Islas Turks y Caicos', 'Îles Turks et Caïques', 'Isole Turks e Caicos', 'Turks- en Caicoseilanden'),
(223, 798, 'TV', 'TUV', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu'),
(224, 800, 'UG', 'UGA', 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'Ouganda', 'Uganda', 'Oeganda'),
(225, 804, 'UA', 'UKR', 'Ukrajina', 'Ukraine', 'Ukraine', 'Ucrania', 'Ukraine', 'Ucraina', 'Oekraïne'),
(226, 807, 'MK', 'MKD', 'Makedonie', 'Ehem. jugoslawische Republik Mazedonien', 'The Former Yugoslav Republic of Macedonia', 'Macedonia', 'L''ex-République Yougoslave de Macédoine', 'Macedonia', 'Macedonië'),
(227, 818, 'EG', 'EGY', 'Egypt', 'Ägypten', 'Egypt', 'Egipto', 'Égypte', 'Egitto', 'Egypte'),
(228, 826, 'GB', 'GBR', 'Velká Británie', 'Vereinigtes Königreich von Großbritannien und', 'United Kingdom', 'Reino Unido', 'Royaume-Uni', 'Regno Unito', 'Verenigd Koninkrijk'),
(229, 833, 'IM', 'IMN', 'Ostrov Man', 'Insel Man', 'Isle of Man', 'Isla de Man', 'Île de Man', 'Isola di Man', 'Eiland Man'),
(230, 834, 'TZ', 'TZA', 'Tanzánie', 'Vereinigte Republik Tansania', 'United Republic Of Tanzania', 'Tanzania', 'République-Unie de Tanzanie', 'Tanzania', 'Tanzania'),
(231, 840, 'US', 'USA', 'USA', 'Vereinigte Staaten von Amerika', 'United States', 'Estados Unidos', 'États-Unis', 'Stati Uniti d''America', 'Verenigde Staten'),
(232, 850, 'VI', 'VIR', 'Americké Panenské ostrovy', 'Amerikanische Jungferninseln', 'U.S. Virgin Islands', 'Islas Vírgenes Estadounidenses', 'Îles Vierges des États-Unis', 'Isole Vergini Americane', 'Amerikaanse Maagdeneilanden'),
(233, 854, 'BF', 'BFA', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso'),
(234, 858, 'UY', 'URY', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay'),
(235, 860, 'UZ', 'UZB', 'Uzbekistán', 'Usbekistan', 'Uzbekistan', 'Uzbekistán', 'Ouzbékistan', 'Uzbekistan', 'Oezbekistan'),
(236, 862, 'VE', 'VEN', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela'),
(237, 876, 'WF', 'WLF', 'Wallis a Futuna', 'Wallis und Futuna', 'Wallis and Futuna', 'Wallis y Futuna', 'Wallis et Futuna', 'Wallis e Futuna', 'Wallis en Futuna'),
(238, 882, 'WS', 'WSM', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa'),
(239, 887, 'YE', 'YEM', 'Jemen', 'Jemen', 'Yemen', 'Yemen', 'Yémen', 'Yemen', 'Jemen'),
(240, 891, 'CS', 'SCG', 'Serbia and Montenegro', 'Serbien und Montenegro', 'Serbia and Montenegro', 'Serbia y Montenegro', 'Serbie-et-Monténégro', 'Serbia e Montenegro', 'Servië en Montenegro'),
(241, 894, 'ZM', 'ZMB', 'Zambie', 'Sambia', 'Zambia', 'Zambia', 'Zambie', 'Zambia', 'Zambia');

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE IF NOT EXISTS `departements` (
  `num_departement` varchar(2) NOT NULL,
  `num_region` varchar(2) NOT NULL,
  `nom` char(32) NOT NULL,
  PRIMARY KEY (`num_departement`),
  KEY `FK_DEPARTEMENT_REGION` (`num_region`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `departements`
--

INSERT INTO `departements` (`num_departement`, `num_region`, `nom`) VALUES
('1', '22', 'Ain'),
('10', '8', 'Aube'),
('11', '13', 'Aude'),
('12', '16', 'Aveyron'),
('13', '18', 'Bouches du rhône'),
('14', '4', 'Calvados'),
('15', '3', 'Cantal'),
('16', '21', 'Charente'),
('17', '21', 'Charente maritime'),
('18', '7', 'Cher'),
('19', '14', 'Corrèze'),
('2', '20', 'Aisne'),
('21', '5', 'Côte d''or'),
('22', '6', 'Côtes d''Armor'),
('23', '14', 'Creuse'),
('24', '2', 'Dordogne'),
('25', '10', 'Doubs'),
('26', '22', 'Drôme'),
('27', '11', 'Eure'),
('28', '7', 'Eure et Loir'),
('29', '6', 'Finistère'),
('2a', '9', 'Corse du Sud'),
('2b', '9', 'Haute Corse'),
('3', '3', 'Allier'),
('30', '13', 'Gard'),
('31', '16', 'Haute garonne'),
('32', '16', 'Gers'),
('33', '2', 'Gironde'),
('34', '13', 'Hérault'),
('35', '6', 'Ile et Vilaine'),
('36', '7', 'Indre'),
('37', '7', 'Indre et Loire'),
('38', '22', 'Isère'),
('39', '10', 'Jura'),
('4', '18', 'Alpes de haute provence'),
('40', '2', 'Landes'),
('41', '7', 'Loir et Cher'),
('42', '22', 'Loire'),
('43', '3', 'Haute loire'),
('44', '19', 'Loire Atlantique'),
('45', '7', 'Loiret'),
('46', '16', 'Lot'),
('47', '2', 'Lot et Garonne'),
('48', '13', 'Lozère'),
('49', '19', 'Maine et Loire'),
('5', '18', 'Hautes alpes'),
('50', '4', 'Manche'),
('51', '8', 'Marne'),
('52', '8', 'Haute Marne'),
('53', '19', 'Mayenne'),
('54', '15', 'Meurthe et Moselle'),
('55', '15', 'Meuse'),
('56', '6', 'Morbihan'),
('57', '15', 'Moselle'),
('58', '5', 'Nièvre'),
('59', '17', 'Nord'),
('6', '18', 'Alpes maritimes'),
('60', '20', 'Oise'),
('61', '4', 'Orne'),
('62', '17', 'Pas de Calais'),
('63', '3', 'Puy de Dôme'),
('64', '2', 'Pyrénées Atlantiques'),
('65', '16', 'Hautes Pyrénées'),
('66', '13', 'Pyrénées Orientales'),
('67', '1', 'Bas Rhin'),
('68', '1', 'Haut Rhin'),
('69', '22', 'Rhône'),
('7', '22', 'Ardèche'),
('70', '10', 'Haute Saône'),
('71', '5', 'Saône et Loire'),
('72', '19', 'Sarthe'),
('73', '22', 'Savoie'),
('74', '22', 'Haute Savoie'),
('75', '12', 'Paris'),
('76', '11', 'Seine Maritime'),
('77', '12', 'Seine et Marne'),
('78', '12', 'Yvelines'),
('79', '21', 'Deux Sèvres'),
('8', '8', 'Ardennes'),
('80', '20', 'Somme'),
('81', '16', 'Tarn'),
('82', '16', 'Tarn et Garonne'),
('83', '18', 'Var'),
('84', '18', 'Vaucluse'),
('85', '19', 'Vendée'),
('86', '21', 'Vienne'),
('87', '14', 'Haute Vienne'),
('88', '15', 'Vosge'),
('89', '5', 'Yonne'),
('9', '16', 'Ariège'),
('90', '10', 'Territoire de Belfort'),
('91', '12', 'Essonne'),
('92', '12', 'Haut de seine'),
('93', '12', 'Seine Saint Denis'),
('94', '12', 'Val de Marne'),
('95', '12', 'Val d''Oise');

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

CREATE TABLE IF NOT EXISTS `document` (
  `id_document` int(255) NOT NULL AUTO_INCREMENT,
  `nom_document` varchar(255) NOT NULL,
  `description_document` varchar(255) DEFAULT 'Aucune description',
  `url_document` varchar(255) NOT NULL,
  `type_document` varchar(255) DEFAULT 'Indefini',
  `categorie_document` int(255) NOT NULL DEFAULT '5',
  `date_creation_document` date NOT NULL,
  PRIMARY KEY (`id_document`),
  UNIQUE KEY `url_document` (`url_document`),
  KEY `categorie` (`categorie_document`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `document`
--

INSERT INTO `document` (`id_document`, `nom_document`, `description_document`, `url_document`, `type_document`, `categorie_document`, `date_creation_document`) VALUES
(5, 'Cahier_des_charges.pdf', 'Le cahier des charges de l''application', '/homez.166/lambdawe/www/pp2/ci/uploads/Cahier_des_charges.pdf', 'application/pdf', 3, '2012-11-06'),
(9, 'Poker_14112012.zip', 'Début du développement du poker.', '/homez.166/lambdawe/www/pp2/ci/uploads/Poker_14112012.zip', 'application/zip', 4, '2012-11-14'),
(7, 'UseCases.jpg', 'Les uses cases de l''application', '/homez.166/lambdawe/www/pp2/ci/uploads/UseCases.jpg', 'image/jpeg', 1, '2012-11-06'),
(11, 'Rapport_préliminaire.pdf', 'Le rapport préliminaire du projet', '/homez.166/lambdawe/www/pp2/ci/uploads/Rapport_préliminaire.pdf', 'application/pdf', 3, '2012-11-16'),
(10, 'diagramme_de_classes.png', 'Nouveau diagramme de classes', '/homez.166/lambdawe/www/pp2/ci/uploads/diagramme_de_classes.png', 'image/png', 1, '2012-11-16'),
(13, 'poker_27112012.zip', 'Version du projet du 27 novembre 2012', '/homez.166/lambdawe/www/pp2/ci/uploads/poker_27112012.zip', 'application/zip', 4, '2012-11-27');

-- --------------------------------------------------------

--
-- Structure de la table `etat_contrat_lw`
--

CREATE TABLE IF NOT EXISTS `etat_contrat_lw` (
  `id_etat_contrat` int(255) NOT NULL AUTO_INCREMENT,
  `nom_etat_contrat` varchar(255) NOT NULL,
  `description_etat_contrat` text NOT NULL,
  `progression_etat_contrat` int(255) DEFAULT '0',
  PRIMARY KEY (`id_etat_contrat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `infos_membres_lw`
--

CREATE TABLE IF NOT EXISTS `infos_membres_lw` (
  `id_membre` int(255) NOT NULL,
  `id_pays_membre` int(255) DEFAULT NULL,
  `dept_membre` int(255) DEFAULT NULL,
  `date_naissance_membre` date NOT NULL,
  `site_web_membre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_membre`),
  KEY `id_pays_membre` (`id_pays_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `membre_lw`
--

CREATE TABLE IF NOT EXISTS `membre_lw` (
  `id_membre` int(255) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `id_role` int(255) DEFAULT '9',
  `photo_profil` varchar(255) DEFAULT NULL,
  `id_pays_membre` int(255) DEFAULT NULL,
  `dept_membre` varchar(2) DEFAULT NULL,
  `date_naissance_membre` date NOT NULL,
  `site_web_membre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_membre`),
  KEY `id_role` (`id_role`),
  KEY `id_pays_membre` (`id_pays_membre`),
  KEY `dept_membre` (`dept_membre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `membre_lw`
--

INSERT INTO `membre_lw` (`id_membre`, `pseudo`, `pass`, `email`, `nom`, `prenom`, `id_role`, `photo_profil`, `id_pays_membre`, `dept_membre`, `date_naissance_membre`, `site_web_membre`) VALUES
(1, 'lambda2', '55317ed193fb0c37e13bed6ff1ec0c15c06db491', 'andre.aubin.ldaw@gmail.com', 'Aubin', 'André', 8, NULL, 75, '13', '0000-00-00', 'http://www.lambdaweb.fr'),
(3, 'lucille', 'b52ea99ed594006b281372ae0dd6afc2bc862494', 'arragon.lucille@gmail.com', 'Arragon', 'Lucille', 1, NULL, NULL, NULL, '0000-00-00', NULL),
(4, 'john', 'a51dda7c7ff50b61eaea0444371f4a6a9301e501', 'john.doe@gmail.com', 'Doe', 'John', 2, NULL, NULL, NULL, '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `pages_lw`
--

CREATE TABLE IF NOT EXISTS `pages_lw` (
  `id_page` int(11) NOT NULL AUTO_INCREMENT,
  `nom_page` varchar(255) NOT NULL,
  `nom_complet_page` varchar(255) NOT NULL,
  `description_page` text,
  `droit_page` int(255) DEFAULT '0',
  `categorie_page` int(255) DEFAULT NULL,
  PRIMARY KEY (`id_page`),
  UNIQUE KEY `nom` (`nom_page`),
  KEY `categorie_page` (`categorie_page`),
  KEY `droit_page` (`droit_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `pages_lw`
--

INSERT INTO `pages_lw` (`id_page`, `nom_page`, `nom_complet_page`, `description_page`, `droit_page`, `categorie_page`) VALUES
(11, 'accueil', 'Accueil', 'La page d''accueil', 10, 1),
(12, 'accueil_m', 'Accueil', 'La page d''accueil', 9, 1),
(13, 'profil', 'Votre profil', 'Voir et modifier les informations concernant votre profil', 9, 2),
(15, 'trajets', 'Trajets', 'Ajouter et consulter les différents trajets effectués pour Lambdaweb', 8, 3),
(16, 'contrats', 'Contrats', 'Les différents contrats de Lambdaweb', 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `photoa`
--

CREATE TABLE IF NOT EXISTS `photoa` (
  `id_photo` int(11) NOT NULL AUTO_INCREMENT,
  `nom_fichier` varchar(100) NOT NULL,
  `titre_photo` varchar(35) NOT NULL,
  `date_photo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `FK_photo_utili` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Contenu de la table `photoa`
--

INSERT INTO `photoa` (`id_photo`, `nom_fichier`, `titre_photo`, `date_photo`, `description`, `id_user`) VALUES
(33, 'f3992d6009c3abde6a2a47f85185237879fe58bb.jpg', 'Un beau désert', '2012-12-02 17:13:57', 'Un désert magnifique se trouvant dans le névada', 11),
(34, 'e26ece49e5cae4e54e80ba62584148c0470a09d2.jpg', 'Une belle fleur', '2012-12-02 17:23:28', 'Une très jolie fleur', 11),
(35, '660f1791638bb77c051889deafd47c9f9f4fe7f0.jpg', 'Un charmant koala', '2012-12-02 17:23:55', 'Un charmant koala', 11),
(36, 'f075d394b45a19af5cd0b5a157a16fe628737c97.jpg', 'De belles tulipes', '2012-12-02 17:24:25', 'De magnifiques tulipes', 11),
(37, '214efd4bba7b2ca107f3db42f849cc11894da3bc.jpg', 'Un phare', '2012-12-02 17:25:23', 'Un phare se trouvant a Eon. Ce qui nous donne un phare a eon. Un pharaon.', 11),
(38, '2a42f5c8d1ae0d57b40c8e96dc93a1b10fc49e89.jpg', 'Une grande méduse', '2012-12-02 17:26:01', 'Quelle méduse !\r\n', 11);

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE IF NOT EXISTS `projets` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'id unique',
  `pseudo` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT 'no_pseudo' COMMENT 'nom raccourci',
  `nom` varchar(255) COLLATE latin1_general_ci NOT NULL COMMENT 'nom du projet',
  `description` text COLLATE latin1_general_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `licence` varchar(255) COLLATE latin1_general_ci DEFAULT 'Open source',
  `url` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `url_source` varchar(255) COLLATE latin1_general_ci DEFAULT NULL COMMENT 'l''adresse ou se trouve les codes sources',
  `url_demo` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `projets`
--

INSERT INTO `projets` (`id`, `pseudo`, `nom`, `description`, `date_debut`, `date_fin`, `licence`, `url`, `url_source`, `url_demo`) VALUES
(1, 'cardull', 'Carnelian Dulled', 'Un site web coopératif pour rassembler plusieurs équipes de développeurs autour d''un même projet.', '2011-12-31', NULL, 'Open source', 'http://localhost/PC/D/', 'http://localhost/PC/D/', NULL),
(2, 'virtualartexpo', 'Virtual Art Expo', 'Un logiciel permettant de créer une salle d''exposition en la dessinant, d''y importer des images, de les plaquer sur les murs et d''effectuer un rendu en trois dimensions de l''exposition.', '2011-06-01', '2012-03-30', 'Libre', 'qzdqzdqzd', 'qzdqzdqzdqzz', 'qzdqzdqz');

-- --------------------------------------------------------

--
-- Structure de la table `realisations`
--

CREATE TABLE IF NOT EXISTS `realisations` (
  `id_realisation` int(255) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `nombre_litres` int(255) NOT NULL,
  `nombre_jours` int(255) NOT NULL,
  `nombre_idees` int(255) NOT NULL,
  `description` text NOT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'termine',
  `plateforme` varchar(255) NOT NULL DEFAULT 'web',
  PRIMARY KEY (`id_realisation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `realisations`
--

INSERT INTO `realisations` (`id_realisation`, `type`, `nom`, `client`, `url`, `nombre_litres`, `nombre_jours`, `nombre_idees`, `description`, `statut`, `plateforme`) VALUES
(3, 'site', 'martek', 'Martek International S.A.S', 'www.sasmartek.com', 14, 16, 35, '', 'termine', 'web'),
(4, 'site', 'cardull', 'Carnelian Dulled', 'www.cardull.lambdaweb.fr', 65, 80, 122, 'Canelian Dulled est un site communautaire permettant à plusieurs développeurs de se consacrer à un même projet.', 'en cours', 'web'),
(5, 'app', 'vae', 'Virtual Art Expo', 'www.enp-arles.com', 88, 150, 354, 'Un logiciel permettant de créer une salle d''exposition en la dessinant, d''y importer des images, de les plaquer sur les murs et d''effectuer un rendu en trois dimensions de l''exposition.\r\nIl a été réalisé avec le framework Qt pour l''interface graphique et le moteur 3D OGRE pour le rendu en temps réel.', 'termine', 'windows&&mac&&linux'),
(6, 'app', 'opeda-client', 'Générateur OpenERP-péda', 'www.we2bs.com/education/pgipeda/generateur-d-activites-et-de-societes', 112, 98, 45, '<p>Dans le cadre des référentiels de bacs tertiaires liés à l''enseignement des programmes de gestion intégrés (P.G.I.), WE2BS vous propose un serveur OpenERP qui s’intègre parfaitement dans l''architecture réseau du lycée.</p>\r\n\r\n<p>OpenERP est recommandé par le Centre de Ressources pour l''Enseignement Professionnel en Economie - Gestion (CERPEG) et permet de simuler des sociétés ou cas dans un cadre pédagogique. OpenERP est labellisé SIALLE.</p>\r\n<p>Lambdaweb a réalisé pour We2bs un client Linux et Windows pour permettre aux enseignants de gérer facilement et rapidement les activitées sur plusieurs bases de données OpenERP simultanément.</p>', 'termine', 'linux&&windows'),
(7, 'app', 'bluconceptor', 'Blu Conceptor', '', 10, 10, 10, 'Permet de créer un shéma relationel avec un fichier de type XML. Une fois crée, ce fichier est lu et interprété afin de générer tous les fichiers des classes (pour l''instant PHP) et le schéma relationel de la base de données.', 'en cours', 'windows&&mac&&linux');

-- --------------------------------------------------------

--
-- Structure de la table `role_lw`
--

CREATE TABLE IF NOT EXISTS `role_lw` (
  `id_role` int(255) NOT NULL AUTO_INCREMENT,
  `nom_role` varchar(255) NOT NULL,
  `desc_role` text NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `role_lw`
--

INSERT INTO `role_lw` (`id_role`, `nom_role`, `desc_role`) VALUES
(1, 'admin', 'L''administrateur a tous les droits'),
(2, 'membre', ''),
(8, 'Moderateur', 'Moderateur'),
(9, 'Membre', 'Membre connecté'),
(10, 'Tout le monde', 'N''importe quel internaute');

-- --------------------------------------------------------

--
-- Structure de la table `tp4_candidature`
--

CREATE TABLE IF NOT EXISTS `tp4_candidature` (
  `id_candidature` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `motivations` text NOT NULL,
  `date_depot` date NOT NULL,
  `emploi_id` int(11) NOT NULL,
  PRIMARY KEY (`id_candidature`),
  KEY `emploi_id` (`emploi_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `tp4_candidature`
--

INSERT INTO `tp4_candidature` (`id_candidature`, `nom`, `prenom`, `email`, `telephone`, `motivations`, `date_depot`, `emploi_id`) VALUES
(1, 'martin', 'jean', 'j.martin@example.com', '04.67.21.56.58', 'Je suis un excellent développeur PHP et très motivé pour vous rejoindre.', '2012-10-29', 1),
(2, 'doe', 'john', 'j.doe@example.com', NULL, 'J''ai développé de nombreux sites Web en PHP et ma connaissance d''autres langages comme Java est un réel plus.', '2012-10-30', 1),
(3, 'roux', 'sophie', 's.roux@example.com', '04.21.36.58.12', 'J''ai réalisé plusieurs projets avec le Framework Symfony. J''adore l''utiliser au quotidien.', '2012-11-01', 2);

-- --------------------------------------------------------

--
-- Structure de la table `tp4_emploi`
--

CREATE TABLE IF NOT EXISTS `tp4_emploi` (
  `id_emploi` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `societe` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `departement` varchar(3) NOT NULL,
  `type_contrat` enum('CDI','CDD','Alternance','Stage') NOT NULL,
  `salaire` int(8) DEFAULT NULL,
  `description` text NOT NULL,
  `date_ajout` date NOT NULL,
  PRIMARY KEY (`id_emploi`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `tp4_emploi`
--

INSERT INTO `tp4_emploi` (`id_emploi`, `code`, `titre`, `societe`, `email`, `departement`, `type_contrat`, `salaire`, `description`, `date_ajout`) VALUES
(1, 'kg4z7un3qn', 'Développeur PHP', 'New Web', 'newweb@example.org', '34', 'CDI', 25000, 'La société NewWeb SSII basée à Montpellier recrute un développeur PHP.\r\n\r\nTitulaire d''un BAC+2 vous êtes passionné par Internet, alors rejoignez nous.', '2012-10-09'),
(2, 'sn9j948zy7', 'Développeur PHP spécialiste de Symfony', 'SymY', 'symy@example.org', '13', 'CDI', 27000, 'Notre société est située dans le centre ville de Marseille dans un bâtiment moderne flambant neuf.\r\n\r\nNous recherchons un développeur PHP qui maîtrise le framework Symfony afin de maintenir les applications de plusieurs de nos clients. Une première expérience du développement sur tablettes et smartphones serait appréciée.', '2012-10-15'),
(3, 'f8ntugw55d', 'Infographiste spécialisé dans le Web', 'Zrcom', 'zrcom@example.org', '30', 'CDD', NULL, 'En tant que nouvelle agence de communication située à Nîmes nous recherchons un infographiste confirmé pour réaliser la charte graphique des sites Web que nous réalisons.\r\n\r\nLa connaissance de langage HTML5 serait un plus appréciée. \r\nLe poste est à pourvoir immédiatement.', '2012-10-21'),
(5, 'avbl3fun2s', 'Manager commercial', 'Lambdaweb', 'andre.aubin@lambdaweb.fr', '13', 'Stage', 0, 'Rattaché(e) au Directeur de la Business Unit, vous participez, en collaboration avec l\\''équipe commerciale à la définition des solutions techniques en adéquation avec les besoins du client.\r\n\r\nDans ce cadre, vos principales missions consistent à :\r\n\r\n- Identifier de manière efficace et pertinente les besoins opérationnels / techniques du client afin de lui apporter une solution optimisée.\r\n- Analyser les besoins puis recommander des solutions adaptées aux clients.\r\n- Coopérer avec les équipes commerciales concernant les aspects techniques des offres en cours, contribuer à la conclusion des affaires à travers des démonstrations, ou d\\''autres actions de conseil et d\\''expertise technique.\r\n- Démontrer les capacités des produits en vue de convaincre les prospects de choisir la solution technique proposée adaptée à leurs problématiques opérationnelles.\r\n- Promouvoir l\\''offre au travers d\\''actions d\\''avant-vente chez les partenaires (Salons, animation d\\''ateliers/séminaires, support aux revendeurs).\r\n- Préparer ou créer des outils d\\''aide à la vente (démonstration, analyses de la concurrence...) en relation avec le service mercatique.\r\n- Animer des formations à destination des revendeurs.', '2012-11-15');

-- --------------------------------------------------------

--
-- Structure de la table `trajet_lw`
--

CREATE TABLE IF NOT EXISTS `trajet_lw` (
  `id_trajet` int(255) NOT NULL AUTO_INCREMENT,
  `nom_origine` varchar(255) NOT NULL,
  `nom_destination` varchar(255) NOT NULL,
  `distance_trajet` int(255) NOT NULL,
  `id_vehicule` int(255) NOT NULL,
  `date_trajet` date DEFAULT NULL,
  `can_read` int(255) NOT NULL,
  `can_write` int(255) NOT NULL,
  `can_update` int(255) NOT NULL,
  `can_delete` int(255) NOT NULL,
  PRIMARY KEY (`id_trajet`),
  KEY `id_vehicule` (`id_vehicule`),
  KEY `can_read` (`can_read`),
  KEY `can_write` (`can_write`),
  KEY `can_update` (`can_update`),
  KEY `can_delete` (`can_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateura`
--

CREATE TABLE IF NOT EXISTS `utilisateura` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `mdp` varchar(40) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(100) DEFAULT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `sexe` enum('H','F') NOT NULL DEFAULT 'H',
  `date_naissance` date DEFAULT NULL,
  `bio` text,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `utilisateura`
--

INSERT INTO `utilisateura` (`id_user`, `login`, `mdp`, `mail`, `date_ajout`, `avatar`, `nom`, `prenom`, `sexe`, `date_naissance`, `bio`) VALUES
(11, 'andre', '55317ed193fb0c37e13bed6ff1ec0c15c06db491', 'andre.aubin.ldaw@gmail.com', '2012-12-02 17:10:21', './webroot/res/avatar_nd.png', NULL, NULL, 'H', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vehicule_lw`
--

CREATE TABLE IF NOT EXISTS `vehicule_lw` (
  `id_vehicule` int(255) NOT NULL AUTO_INCREMENT,
  `nom_vehicule` varchar(255) NOT NULL,
  `modele_vehicule` varchar(255) NOT NULL,
  `consommation_vehicule` double NOT NULL,
  `annee_vehicule` int(255) NOT NULL,
  PRIMARY KEY (`id_vehicule`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `vehicule_lw`
--

INSERT INTO `vehicule_lw` (`id_vehicule`, `nom_vehicule`, `modele_vehicule`, `consommation_vehicule`, `annee_vehicule`) VALUES
(1, 'Clio', 'Renault Clio', 6, 2006),
(2, 'Kia', 'Kia Picanto', 14, 2008);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `access_lw`
--
ALTER TABLE `access_lw`
  ADD CONSTRAINT `access_lw_ibfk_1` FOREIGN KEY (`can_create`) REFERENCES `role_lw` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `access_lw_ibfk_2` FOREIGN KEY (`can_read`) REFERENCES `role_lw` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `access_lw_ibfk_3` FOREIGN KEY (`can_update`) REFERENCES `role_lw` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `access_lw_ibfk_4` FOREIGN KEY (`can_delete`) REFERENCES `role_lw` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `appartenira`
--
ALTER TABLE `appartenira`
  ADD CONSTRAINT `appartenir_ibfk_1` FOREIGN KEY (`id_photo`) REFERENCES `photoa` (`id_photo`),
  ADD CONSTRAINT `appartenir_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categoriea` (`id_categorie`);

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_auteur`) REFERENCES `membre_lw` (`id_membre`),
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`id_cat`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `categoriea`
--
ALTER TABLE `categoriea`
  ADD CONSTRAINT `FK_categ_utili` FOREIGN KEY (`id_user`) REFERENCES `utilisateura` (`id_user`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`);

--
-- Contraintes pour la table `commentairea`
--
ALTER TABLE `commentairea`
  ADD CONSTRAINT `FK_comment_photo` FOREIGN KEY (`id_photo`) REFERENCES `photoa` (`id_photo`),
  ADD CONSTRAINT `FK_comment_utili` FOREIGN KEY (`id_user`) REFERENCES `utilisateura` (`id_user`);

--
-- Contraintes pour la table `contrat_lw`
--
ALTER TABLE `contrat_lw`
  ADD CONSTRAINT `contrat_lw_ibfk_2` FOREIGN KEY (`etat_contrat`) REFERENCES `etat_contrat_lw` (`id_etat_contrat`),
  ADD CONSTRAINT `contrat_lw_ibfk_3` FOREIGN KEY (`id_client_contrat`) REFERENCES `client_lw` (`id_client`);

--
-- Contraintes pour la table `membre_lw`
--
ALTER TABLE `membre_lw`
  ADD CONSTRAINT `membre_lw_ibfk_10` FOREIGN KEY (`id_pays_membre`) REFERENCES `countries_lw` (`id_pays`),
  ADD CONSTRAINT `membre_lw_ibfk_11` FOREIGN KEY (`dept_membre`) REFERENCES `departements` (`num_departement`),
  ADD CONSTRAINT `membre_lw_ibfk_9` FOREIGN KEY (`id_role`) REFERENCES `role_lw` (`id_role`);

--
-- Contraintes pour la table `pages_lw`
--
ALTER TABLE `pages_lw`
  ADD CONSTRAINT `pages_lw_ibfk_2` FOREIGN KEY (`droit_page`) REFERENCES `role_lw` (`id_role`),
  ADD CONSTRAINT `pages_lw_ibfk_3` FOREIGN KEY (`categorie_page`) REFERENCES `categorie_page_lw` (`id_categorie_page`);

--
-- Contraintes pour la table `photoa`
--
ALTER TABLE `photoa`
  ADD CONSTRAINT `FK_photo_utili` FOREIGN KEY (`id_user`) REFERENCES `utilisateura` (`id_user`);

--
-- Contraintes pour la table `tp4_candidature`
--
ALTER TABLE `tp4_candidature`
  ADD CONSTRAINT `tp4_candidature_ibfk_1` FOREIGN KEY (`emploi_id`) REFERENCES `tp4_emploi` (`id_emploi`);

--
-- Contraintes pour la table `trajet_lw`
--
ALTER TABLE `trajet_lw`
  ADD CONSTRAINT `trajet_lw_ibfk_1` FOREIGN KEY (`id_vehicule`) REFERENCES `vehicule_lw` (`id_vehicule`),
  ADD CONSTRAINT `trajet_lw_ibfk_2` FOREIGN KEY (`can_read`) REFERENCES `role_lw` (`id_role`),
  ADD CONSTRAINT `trajet_lw_ibfk_3` FOREIGN KEY (`can_write`) REFERENCES `role_lw` (`id_role`),
  ADD CONSTRAINT `trajet_lw_ibfk_4` FOREIGN KEY (`can_update`) REFERENCES `role_lw` (`id_role`),
  ADD CONSTRAINT `trajet_lw_ibfk_5` FOREIGN KEY (`can_delete`) REFERENCES `role_lw` (`id_role`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
