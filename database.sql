CREATE DATABASE IF NOT EXISTS `movies`;
USE `movies`;

CREATE TABLE IF NOT EXISTS `asiakas` (
  `asiakasid` int(11) NOT NULL AUTO_INCREMENT,
  `henkilotunnus` varchar(50) NOT NULL,
  `etunimi` varchar(100) NOT NULL,
  `sukunimi` varchar(100) NOT NULL,
  `sposti` varchar(50) NOT NULL,
  `puhelinnro` varchar(15) NOT NULL,
  `lahiosoite` varchar(50) NOT NULL,
  `postinumero` varchar(5) NOT NULL,
  `postitoimipaikka` varchar(50) NOT NULL,
  PRIMARY KEY (`asiakasid`),
  UNIQUE KEY `ssn` (`henkilotunnus`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `asiakas` (`henkilotunnus`, `etunimi`, `sukunimi`, `sposti`, `puhelinnro`, `lahiosoite`, `postinumero`, `postitoimipaikka`) VALUES
	('100102A123A', 'Matti', 'Meikäläinen', 'matti.meikäläinen@gmail.com', '0401234567', 'Vistantie 18', '21530', 'Paimio');


CREATE TABLE IF NOT EXISTS `elokuva` (
  `videoid` int(11) NOT NULL AUTO_INCREMENT,
  `nimi` varchar(100) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `kesto` varchar(100) NOT NULL,
  `ikaraja` varchar(100) NOT NULL,
  `julkaisupaiva` date NOT NULL,
  `tuotantovuosi` year(4) NOT NULL,
  `kuvaus` TEXT NOT NULL,
  `ohjaaja` varchar(50) NOT NULL,
  `nayttelijat` text NOT NULL,
  `kuva` text NOT NULL,
  PRIMARY KEY (`videoid`),
  UNIQUE KEY `nimi` (`nimi`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `elokuva` (`nimi`, `genre`, `kesto`, `ikaraja`, `julkaisupaiva`, `tuotantovuosi`, `kuvaus`, `ohjaaja`, `nayttelijat`, `kuva`) VALUES
	('Joker', 'Draama, Jännitys, Rikoselokuva', '120', '16', '2019-08-09', '2019', 'Väkijoukossakin itsensä yksinäiseksi tunteva Arthur Fleck etsii edes jonkinlaista yhteyttä. Kulkiessaan pitkin Gotham Cityn nokisia katuja, hän pitää yllään kahta naamiota.', 'Todd Phillips', 'Joaquin Phoenix, Robert De Niro, Zazie Beetz', 'https://media.finnkino.fi/1012/Event_12622/portrait_medium/Joker_1080.jpg'),
	('Zombieland: Double Tap', 'Komedia, Toiminta, Kauhu', '93', '16', '2019-10-25', '2019', 'Vuosikymmen sen jälkeen, kun Zombieland-elokuvasta tuli hitti ja kulttiklassikko, sen pääosanesittäjät Woody Harrelson, Jesse Eisenberg, Abigail Breslin ja Emma Stone palaavat yhteen ohjaaja Ruben Fleischerin (Venom) sekä alkuperäisten kirjoittajien Rhett Reesen ja Paul Wernickin (Deadpool) kanssa jatko-osassa Zombieland: Double Tap', 'Ruben Fleischer', 'Woody Harrelson, Jesse Eisenberg, Emma Stone', 'https://media.finnkino.fi/1012/Event_12661/portrait_medium/Zombieland_1080.jpg'),
	('Dumbo', 'Fantasia, Perhe-elokuva', '112', '7', '2019-03-29', '2019', 'Disney ja mielikuvituksen mestari Tim Burton tuovat valkokankaille uuden live-action seikkailun DUMBO. Elokuva laajentaa rakastetun klassikon tarinaa, joka juhlii erilaisuutta, vaalii perhettä ja siivittää unelmat lentoon.', 'Tim Burton', 'Colin Farrell, Michael Keaton, Danny DeVito', 'https://media.finnkino.fi/1012/Event_12378/portrait_medium/Dumbo_1080.jpg'),
	('Fingerpori', 'Komedia, Kotimainen', '85', '12', '2019-10-16', '2019', 'Fingerpori on komedia ikiaikaisesta vitsauksesta nimeltä rakkaus. Neljän toisiinsa niveltyvän episodin kautta näemme, kuinka rakkauden nälkä sysää korruptoituneen kaupunginjohtajan Aulis Homeliuksen, saamattoman tutkijan Heimo Vesan, rääväsuisen baarinpitäjän Rivo-Riitan ja päihdeongelmaisen lähihoitajan Krapula-Päivin toimintaan, josta ei absurdia Fingerpori-komiikkaa saati noloja tilanteita puutu. Kaikella on hintansa, mutta mikä on rakkauden?', 'Mikko Kouki', 'Kari Väänänen, Jenni Kokander, Santtu Karvonen', 'https://media.finnkino.fi/1012/Event_12619/portrait_medium/Fingerpori_1080.jpg');

CREATE TABLE IF NOT EXISTS `myyja` (
  `myyjaid` int(11) NOT NULL AUTO_INCREMENT,
  `kayttajanimi` varchar(50) DEFAULT NULL,
  `etunimi` varchar(50) DEFAULT NULL,
  `sukunimi` varchar(50) DEFAULT NULL,
  `lahiosoite` varchar(50) DEFAULT NULL,
  `postinumero` varchar(5) DEFAULT NULL,
  `postitoimipaikka` varchar(50) DEFAULT NULL,
  `puhelinnro` varchar(15) DEFAULT NULL,
  `sposti` varchar(100) DEFAULT NULL,
  `salasana` varchar(255) DEFAULT NULL,
  `rooli` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`myyjaid`),
  UNIQUE KEY `kayttaja_nimi` (`kayttajanimi`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `myyja` (`kayttajanimi`, `etunimi`, `sukunimi`, `lahiosoite`, `postinumero`, `postitoimipaikka`, `puhelinnro`, `sposti`, `salasana`, `rooli`) VALUES
	('Admin', 'Matti', 'Meikäläinen', 'Vistantie 18', '21530', 'Paimio', '0401234567', 'matti.meikäläinen@gmail.com', '$2y$10$qRRiygtw9hGIht5hfWyTCe.EjCPw.gK3456cNjs1MgUZ8jSdJbNkq', 'Admin');

CREATE TABLE IF NOT EXISTS `vuokraus` (
  `vuokrausID` int(11) NOT NULL AUTO_INCREMENT,
  `asiakasID` int(11) NOT NULL,
  `videoID` int(11) NOT NULL,
  `vuokrauspvm` date NOT NULL,
  `palautuspvm` date NOT NULL,
  `kokonaishinta` float NOT NULL,
  PRIMARY KEY (`vuokrausID`),
  KEY `asiakasID` (`asiakasID`),
  KEY `videoID` (`videoID`),
  CONSTRAINT `asiakasID` FOREIGN KEY (`asiakasID`) REFERENCES `asiakas` (`asiakasid`),
  CONSTRAINT `videoID` FOREIGN KEY (`videoID`) REFERENCES `elokuva` (`videoid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;