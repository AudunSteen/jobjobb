-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 05. Des, 2023 16:52 PM
-- Tjener-versjon: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `is115DB`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `jobbannonser`
--

CREATE TABLE `jobbannonser` (
  `id` int(11) NOT NULL,
  `tittel` varchar(255) NOT NULL,
  `beskrivelse` text DEFAULT NULL,
  `publiseringsdato` date DEFAULT NULL,
  `interesse` varchar(255) NOT NULL,
  `soknadsfrist` date DEFAULT NULL,
  `arbeidsgiver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `jobbannonser`
--

INSERT INTO `jobbannonser` (`id`, `tittel`, `beskrivelse`, `publiseringsdato`, `interesse`, `soknadsfrist`, `arbeidsgiver_id`) VALUES
(10, 'IT konsulent ', 'Erfaren IT konsulent søkes, med 3 års erfaring og relevant utdanning', '2023-12-05', 'IT', '2023-12-12', 8),
(13, 'Cybersecurity Analyst', 'Utforsk nye måter å identifisere og beskytte mot cybertrusler.', '2023-12-05', 'IT', '2023-12-11', 8),
(14, 'SIEM Analyst', 'Overvåk, analyser og respondér på sikkerhetshendelser.', '2023-12-05', 'IT', '2023-12-17', 8),
(15, 'Aksjemegler', 'Søker aksjemegler med 10+ år erfaring', '2023-12-05', 'Økonomi', '2024-01-13', 9),
(16, 'Regnskapsfører', 'Søker regnskapsfører med et kaldt hode og minimum 3+ år erfaring.', '2023-12-05', 'Økonomi', '2024-02-22', 9),
(17, 'HR sjef', 'Søker ny HR sjef, krav om master innen HR og 10 år arbeidserfaring.', '2023-12-05', 'Administrasjon', '2024-03-21', 9);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `soknader`
--

CREATE TABLE `soknader` (
  `soknad_id` int(11) NOT NULL,
  `jobbannonse_id` int(11) NOT NULL,
  `jobbsoker_id` int(11) NOT NULL,
  `soknadstekst` text DEFAULT NULL,
  `soknadsdato` date DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `soknader`
--

INSERT INTO `soknader` (`soknad_id`, `jobbannonse_id`, `jobbsoker_id`, `soknadstekst`, `soknadsdato`, `pdf_path`) VALUES
(7, 13, 10, 'Som nylig utdannet innen cybersikkerhet, bringer jeg nysgjerrighet og en dyp forståelse av trusselaktørens taktikker, samtidig som jeg er dedikert til å implementere proaktive sikkerhetstiltak for å beskytte organisasjonen mot fremtidige cybertrusler.', '2023-12-05', 'soknader/656f40de8bb74_Tore CV.pdf'),
(8, 10, 10, 'Nyutdannet student søker jobb som konsulent.', '2023-12-05', 'soknader/656f412457663_Tore CV.pdf'),
(9, 14, 10, 'Med en lidenskap for cybersikkerhet og en solid bakgrunn i overvåking, analyse og respons på sikkerhetshendelser, søker jeg å implementere mine ferdigheter som Cybersecurity Analyst for å styrke digitale forsvarsstrategier og sikre organisasjonens sensitive informasjon.', '2023-12-05', 'soknader/656f41e9e0a79_CV English Tore Haarr PDF.pdf'),
(10, 13, 11, 'Med en fersk akademisk bakgrunn innen cybersikkerhet og hands-on erfaring fra relevante prosjekter, søker jeg stillingen som Cybersecurity Analyst med dedikasjon til å identifisere, analysere og håndtere komplekse sikkerhetstrusler. Jeg er motivert for å bidra med mine tekniske ferdigheter og strategiske tilnærminger for å styrke organisasjoners digitale forsvar og beskytte deres verdifulle dataressurser.', '2023-12-05', 'soknader/656f4292c10f9_Audun Steen CV-kopi.pdf'),
(11, 10, 11, 'Erfaren IT konsulent med god erfaring innen ulike IT-sektorer. Programmerer i JAVA og PYTHON.', '2023-12-05', 'soknader/656f4431257b2_Eksempel CV.pdf'),
(12, 15, 11, 'Dyktig aksjemegler søker deltidsjobb. ', '2023-12-05', 'soknader/656f445195aa6_Eksempel CV.pdf'),
(13, 16, 11, 'Jeg søker med entusiasme og solid erfaring som regnskapsfører, og ønsker å bringe mine ferdigheter innen økonomistyring, nøyaktighet og analytisk tankegang til deres team for å sikre effektiv håndtering av regnskapsoppgaver.', '2023-12-05', 'soknader/656f44b165e10_Audun Steen CV .pdf'),
(14, 13, 12, 'Erfaring som Cybersecurity analyst fra soprasteria. Har en mastergrad i Cybersecurity', '2023-12-05', 'soknader/656f45dee533f_Ola Normann CV.pdf'),
(15, 10, 12, '10 års erfaring som IT-konsulent. ', '2023-12-05', 'soknader/656f46086d6c6_Ola Normann CV.pdf'),
(16, 16, 12, 'Tidligere mastergrad i Økonomi, og 3 års erfaring som regnskapsfører.', '2023-12-05', 'soknader/656f4630db9d1_Ola Normann CV.pdf');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `userType` varchar(20) NOT NULL,
  `profilePicture` varchar(255) DEFAULT NULL,
  `information` text DEFAULT NULL,
  `cvFile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `phoneNumber`, `email`, `userType`, `profilePicture`, `information`, `cvFile`) VALUES
(8, 'NetSecurity', '$2y$10$OtXDb7YcteWqUfW61JbCt.EE8/AMIBarDwxGCTO9O.vB6EO5OhIb.', '123456789', 'arbeidsgiver_1@example.com', 'arbeidsgiver', NULL, NULL, NULL),
(9, 'Sparebank1', '$2y$10$AoQY6lo4pL4p8vZHQaBaJezDgq/MTxiGa6fWx8NqkyHJ.wE1dCd1i', '12345678', 'Sparebank1@bank.no', 'arbeidsgiver', NULL, NULL, NULL),
(10, 'Tore', '$2y$10$oT4912zz8xm0L/0oK6k2.e.Ngi5d/4.unC4NIch/eWucKTMTrP50W', '987654321', 'eksempel@uia.no', 'jobbsoker', NULL, NULL, NULL),
(11, 'Audun', '$2y$10$Ow/4OssY/cznoDdZVb1BP.Q8r811SL36VglKqMLCgcZH1gE8gf73q', '987654322', 'eksemple@uia.com', 'jobbsoker', NULL, NULL, NULL),
(12, 'eksempelbruker', '$2y$10$i01BfXfHz8JgrKy./uEYM.JqS.Angd9VAfV76F4uA9ooU4hqYykfi', '95122555', 'testing@gmail.com', 'jobbsoker', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobbannonser`
--
ALTER TABLE `jobbannonser`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arbeidsgiver_id` (`arbeidsgiver_id`);

--
-- Indexes for table `soknader`
--
ALTER TABLE `soknader`
  ADD PRIMARY KEY (`soknad_id`),
  ADD KEY `jobbannonse_id` (`jobbannonse_id`),
  ADD KEY `jobbsoker_id` (`jobbsoker_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobbannonser`
--
ALTER TABLE `jobbannonser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `soknader`
--
ALTER TABLE `soknader`
  MODIFY `soknad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `jobbannonser`
--
ALTER TABLE `jobbannonser`
  ADD CONSTRAINT `jobbannonser_ibfk_1` FOREIGN KEY (`arbeidsgiver_id`) REFERENCES `users` (`id`);

--
-- Begrensninger for tabell `soknader`
--
ALTER TABLE `soknader`
  ADD CONSTRAINT `soknader_ibfk_1` FOREIGN KEY (`jobbannonse_id`) REFERENCES `jobbannonser` (`id`),
  ADD CONSTRAINT `soknader_ibfk_2` FOREIGN KEY (`jobbsoker_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
