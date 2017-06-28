-- phpMyAdmin SQL Dump
-- version 4.5.3.1
-- http://www.phpmyadmin.net
--
-- Host: 186.202.152.146
-- Generation Time: 12-Maio-2017 às 10:43
-- Versão do servidor: 5.6.35-81.0-log
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `letnis_qas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_relation_tables`
--

CREATE TABLE `tb_relation_tables` (
  `name_table1` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `name_table2` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `relation` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `name_relation_table` varchar(100) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `tb_relation_tables`
--

INSERT INTO `tb_relation_tables` (`name_table1`, `name_table2`, `relation`, `name_relation_table`) VALUES
('tb_category', 'tb_tickets', 'tb_category.id_category = tb_tickets.id_category', NULL),
('tb_companies', 'tb_tickets', 'tb_companies.id_company = tb_tickets.id_company', NULL),
('tb_consultant_contract', 'tb_users', 'tb_consultant_contract.id_contract = tb_users.id_contract', NULL),
('tb_modules', 'tb_tickets', 'tb_modules.id_module = tb_tickets.id_module', NULL),
('tb_priority', 'tb_tickets', 'tb_priority.id_priority = tb_tickets.id_priority', NULL),
('tb_proposal', 'tb_proposal_type', 'tb_proposal.id_proposal_type = tb_proposal_type.id_proposal_type', NULL),
('tb_proposal', 'tb_tickets', 'tb_proposal.id_proposal = tb_tickets.id_proposal', NULL),
('tb_status', 'tb_tickets', 'tb_status.id_status = tb_tickets.id_status', NULL),
('tb_tickets', 'tb_users', 'tb_users.id_user = tb_assigned_users_tickets.id_user AND tb_tickets.id_ticket = tb_assigned_users_tickets.id_ticket', 'tb_assigned_users_tickets'),
('tb_timekeeping', 'tb_tickets', 'tb_timekeeping.id_ticket = tb_tickets.id_ticket', NULL),
('tb_timekeeping', 'tb_users', 'tb_timekeeping.id_user = tb_users.id_user', NULL),
('tb_timekeeping_type', 'tb_timekeeping', 'tb_timekeeping_type.id_timekeeping_type = tb_timekeeping.id_timekeeping_type', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_relation_tables`
--
ALTER TABLE `tb_relation_tables`
  ADD PRIMARY KEY (`name_table1`,`name_table2`),
  ADD KEY `name_table1` (`name_table1`),
  ADD KEY `name_table2` (`name_table2`);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_relation_tables`
--
ALTER TABLE `tb_relation_tables`
  ADD CONSTRAINT `tb_relation_tables_ibfk_1` FOREIGN KEY (`name_table1`) REFERENCES `tb_report_tables` (`name_table`),
  ADD CONSTRAINT `tb_relation_tables_ibfk_2` FOREIGN KEY (`name_table2`) REFERENCES `tb_report_tables` (`name_table`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
