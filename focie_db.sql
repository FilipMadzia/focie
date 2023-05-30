-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20220909.746d1696b7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 30 Maj 2023, 21:49
-- Wersja serwera: 10.4.24-MariaDB
-- Wersja PHP: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `focie_db`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `album`
--

CREATE TABLE `album` (
  `id_album` int(11) NOT NULL,
  `nazwa` varchar(32) DEFAULT NULL,
  `data_utworzenia` date DEFAULT NULL,
  `id_fociarz` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `album`
--

INSERT INTO `album` (`id_album`, `nazwa`, `data_utworzenia`, `id_fociarz`) VALUES
(1, 'Wakacje 2022', '2023-05-30', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fociarz`
--

CREATE TABLE `fociarz` (
  `id_fociarz` int(11) NOT NULL,
  `login` varchar(32) DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `haslo` varchar(32) DEFAULT NULL,
  `data_utworzenia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `fociarz`
--

INSERT INTO `fociarz` (`id_fociarz`, `login`, `email`, `haslo`, `data_utworzenia`) VALUES
(1, 'test', 'test@test', 'test', '2023-05-30');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecie`
--

CREATE TABLE `zdjecie` (
  `id_zdjecie` int(11) NOT NULL,
  `nazwa` varchar(64) DEFAULT NULL,
  `data_dodania` datetime DEFAULT NULL,
  `id_album` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id_album`),
  ADD KEY `id_fociarz` (`id_fociarz`);

--
-- Indeksy dla tabeli `fociarz`
--
ALTER TABLE `fociarz`
  ADD PRIMARY KEY (`id_fociarz`);

--
-- Indeksy dla tabeli `zdjecie`
--
ALTER TABLE `zdjecie`
  ADD PRIMARY KEY (`id_zdjecie`),
  ADD KEY `id_album` (`id_album`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `album`
--
ALTER TABLE `album`
  MODIFY `id_album` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `fociarz`
--
ALTER TABLE `fociarz`
  MODIFY `id_fociarz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `zdjecie`
--
ALTER TABLE `zdjecie`
  MODIFY `id_zdjecie` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`id_fociarz`) REFERENCES `fociarz` (`id_fociarz`);

--
-- Ograniczenia dla tabeli `zdjecie`
--
ALTER TABLE `zdjecie`
  ADD CONSTRAINT `zdjecie_ibfk_1` FOREIGN KEY (`id_album`) REFERENCES `album` (`id_album`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
