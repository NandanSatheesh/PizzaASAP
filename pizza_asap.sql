-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Set 06, 2018 alle 14:45
-- Versione del server: 10.1.32-MariaDB
-- Versione PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizza_asap`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `email` varchar(128) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`email`, `password`) VALUES
('admin@pizzaasap.it', 'adminpassword');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `bibite`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `bibite` (
`ID` int(11)
,`nome` varchar(32)
,`prezzo` decimal(10,2)
,`id_categoria` int(11)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `calzoni`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `calzoni` (
`ID` int(11)
,`nome` varchar(32)
,`descrizione` varchar(256)
,`prezzo` decimal(10,2)
,`id_categoria` int(11)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `carrelli`
--

CREATE TABLE `carrelli` (
  `email` varchar(128) NOT NULL,
  `id_oggetto` int(11) NOT NULL,
  `qta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE `categorie` (
  `ID` int(11) NOT NULL,
  `nome` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `categorie`
--

INSERT INTO `categorie` (`ID`, `nome`) VALUES
(1, 'classiche'),
(2, 'speciali'),
(3, 'calzoni'),
(4, 'bibite');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `classiche`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `classiche` (
`ID` int(11)
,`nome` varchar(32)
,`descrizione` varchar(256)
,`prezzo` decimal(10,2)
,`id_categoria` int(11)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `menu`
--

CREATE TABLE `menu` (
  `ID` int(11) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `descrizione` varchar(256) DEFAULT NULL,
  `prezzo` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `menu`
--

INSERT INTO `menu` (`ID`, `nome`, `descrizione`, `prezzo`, `id_categoria`) VALUES
(1, 'Spianata', 'Olio, rosmarino', '3.00', 1),
(2, 'Marinara', 'Pomodoro, aglio, origano, olio', '4.00', 1),
(3, 'Rossa', 'Pomodoro, basilico', '4.00', 1),
(4, 'Margherita', 'Pomodoro, fior di latte, basilico', '4.50', 1),
(5, 'Americana', 'Pomodoro, fior di latte, patatine fritte, wurstel', '5.50', 1),
(6, 'Salsiccia', 'Pomodoro, fior di latte, salsiccia', '5.50', 1),
(7, 'Diavola', 'Pomodoro, fior di latte, salame piccante, olive nere', '6.50', 1),
(8, '4 Stagioni', 'Pomodoro, fior di latte, prosciutto cotto, funghi, carciofi, salsiccia', '7.00', 1),
(9, 'Calzone Fungaiolo', 'Pomodoro, fior di latte, funghi', '8.00', 3),
(10, 'Calzone Napoletano', 'Pomodoro, fior di latte, prosciutto cotto', '8.00', 3),
(11, 'Calzone \"Leggero\"', 'Esterno margherita, ripieno con pomodoro, fior di latte, salame piccante, prosciutto cotto, funghi', '10.00', 3),
(12, 'Esplosione', 'Pomodoro, fior di latte, salame piccante, salsiccia, funghi, cipolla, olio tartufato', '7.50', 2),
(13, 'Contadina', 'Pomodoro, fior di latte, salsiccia, funghi, cipolla', '6.80', 2),
(14, 'Gustosa', 'Pomodoro, fior di latte, bresaola, rucola, scaglie di grana', '7.00', 2),
(15, 'Vivace', 'Pomodoro, fior di latte, prosciutto crudo, scaglie di grana, rucola', '7.00', 2),
(16, 'Acqua naturale 1L', NULL, '2.00', 4),
(17, 'Birra 66cL', NULL, '3.50', 4),
(18, 'Coca Cola lattina 33cL', NULL, '2.50', 4),
(62, 'Acqua Frizzante 1L', NULL, '2.00', 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `notifiche_admin`
--

CREATE TABLE `notifiche_admin` (
  `ID` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `tipo` int(11) NOT NULL,
  `testo` varchar(512) NOT NULL,
  `giorno` datetime NOT NULL,
  `nuova` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `notifiche_utente`
--

CREATE TABLE `notifiche_utente` (
  `ID` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `tipo` int(11) NOT NULL,
  `testo` varchar(512) NOT NULL,
  `giorno` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nuova` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE `ordini` (
  `ID` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `nome` varchar(64) NOT NULL,
  `cognome` varchar(128) NOT NULL,
  `tempo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `pagamento`
--

CREATE TABLE `pagamento` (
  `email` varchar(128) NOT NULL,
  `numero_carta` char(16) DEFAULT NULL,
  `scadenza` char(5) DEFAULT NULL,
  `cvv` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `speciali`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `speciali` (
`ID` int(11)
,`nome` varchar(32)
,`descrizione` varchar(256)
,`prezzo` decimal(10,2)
,`id_categoria` int(11)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `email` varchar(128) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `cognome` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `indirizzo` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura per vista `bibite`
--
DROP TABLE IF EXISTS `bibite`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bibite`  AS  select `menu`.`ID` AS `ID`,`menu`.`nome` AS `nome`,`menu`.`prezzo` AS `prezzo`,`menu`.`id_categoria` AS `id_categoria` from `menu` where (`menu`.`id_categoria` = 4) ;

-- --------------------------------------------------------

--
-- Struttura per vista `calzoni`
--
DROP TABLE IF EXISTS `calzoni`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `calzoni`  AS  select `menu`.`ID` AS `ID`,`menu`.`nome` AS `nome`,`menu`.`descrizione` AS `descrizione`,`menu`.`prezzo` AS `prezzo`,`menu`.`id_categoria` AS `id_categoria` from `menu` where (`menu`.`id_categoria` = 3) ;

-- --------------------------------------------------------

--
-- Struttura per vista `classiche`
--
DROP TABLE IF EXISTS `classiche`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `classiche`  AS  select `menu`.`ID` AS `ID`,`menu`.`nome` AS `nome`,`menu`.`descrizione` AS `descrizione`,`menu`.`prezzo` AS `prezzo`,`menu`.`id_categoria` AS `id_categoria` from `menu` where (`menu`.`id_categoria` = 1) ;

-- --------------------------------------------------------

--
-- Struttura per vista `speciali`
--
DROP TABLE IF EXISTS `speciali`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `speciali`  AS  select `menu`.`ID` AS `ID`,`menu`.`nome` AS `nome`,`menu`.`descrizione` AS `descrizione`,`menu`.`prezzo` AS `prezzo`,`menu`.`id_categoria` AS `id_categoria` from `menu` where (`menu`.`id_categoria` = 2) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `carrelli`
--
ALTER TABLE `carrelli`
  ADD KEY `FK_email_cart` (`email`),
  ADD KEY `FK_id_item_menu` (`id_oggetto`);

--
-- Indici per le tabelle `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indici per le tabelle `notifiche_admin`
--
ALTER TABLE `notifiche_admin`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `email` (`email`);

--
-- Indici per le tabelle `notifiche_utente`
--
ALTER TABLE `notifiche_utente`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `email` (`email`);

--
-- Indici per le tabelle `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`,`tempo`);

--
-- Indici per le tabelle `pagamento`
--
ALTER TABLE `pagamento`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `menu`
--
ALTER TABLE `menu`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT per la tabella `notifiche_admin`
--
ALTER TABLE `notifiche_admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT per la tabella `notifiche_utente`
--
ALTER TABLE `notifiche_utente`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT per la tabella `ordini`
--
ALTER TABLE `ordini`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `carrelli`
--
ALTER TABLE `carrelli`
  ADD CONSTRAINT `FK_email_cart` FOREIGN KEY (`email`) REFERENCES `utenti` (`email`),
  ADD CONSTRAINT `FK_id_item_menu` FOREIGN KEY (`id_oggetto`) REFERENCES `menu` (`ID`);

--
-- Limiti per la tabella `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorie` (`id`);

--
-- Limiti per la tabella `notifiche_admin`
--
ALTER TABLE `notifiche_admin`
  ADD CONSTRAINT `notifiche_admin_ibfk_1` FOREIGN KEY (`email`) REFERENCES `admin` (`email`);

--
-- Limiti per la tabella `notifiche_utente`
--
ALTER TABLE `notifiche_utente`
  ADD CONSTRAINT `notifiche_utente_ibfk_1` FOREIGN KEY (`email`) REFERENCES `utenti` (`email`);

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`email`) REFERENCES `utenti` (`email`);

--
-- Limiti per la tabella `pagamento`
--
ALTER TABLE `pagamento`
  ADD CONSTRAINT `pagamento_ibfk_1` FOREIGN KEY (`email`) REFERENCES `utenti` (`email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
