-- phpMyAdmin SQL Dump
-- version 2.11.9.6
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 10, 2011 at 06:02 AM
-- Server version: 5.0.22
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `greedy_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `escrow`
--

CREATE TABLE IF NOT EXISTS `escrow` (
  `id` int(11) NOT NULL auto_increment,
  `offer_ID` int(11) NOT NULL,
  `status` enum('D','P','S','C','R','E') NOT NULL,
  `amount` double NOT NULL,
  `bonus` tinyint(1) NOT NULL default '0',
  `release_date` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `escrow`
--

INSERT INTO `escrow` (`id`, `offer_ID`, `status`, `amount`, `bonus`, `release_date`) VALUES


-- --------------------------------------------------------

--
-- Table structure for table `escrow_transaction`
--

CREATE TABLE IF NOT EXISTS `escrow_transaction` (
  `escrow_ID` int(11) NOT NULL,
  `paypal_transaction_ID` int(11) NOT NULL,
  PRIMARY KEY  (`escrow_ID`,`paypal_transaction_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `escrow_transaction`
--



-- --------------------------------------------------------

--
-- Table structure for table `paypal_transaction`
--

CREATE TABLE IF NOT EXISTS `paypal_transaction` (
  `paypal_transaction_ID` int(11) NOT NULL auto_increment,
  `invoice` int(10) unsigned NOT NULL,
  `receiver_email` varchar(60) default NULL,
  `item_name` varchar(100) default NULL,
  `item_number` varchar(10) default NULL,
  `quantity` varchar(6) default NULL,
  `payment_status` varchar(10) default NULL,
  `pending_reason` varchar(10) default NULL,
  `payment_date` varchar(32) default NULL,
  `mc_gross` varchar(20) default NULL,
  `mc_fee` varchar(20) default NULL,
  `tax` varchar(20) default NULL,
  `mc_currency` varchar(3) default NULL,
  `txn_id` varchar(20) default NULL,
  `txn_type` varchar(10) default NULL,
  `first_name` varchar(30) default NULL,
  `last_name` varchar(40) default NULL,
  `address_street` varchar(50) default NULL,
  `address_city` varchar(30) default NULL,
  `address_state` varchar(30) default NULL,
  `address_zip` varchar(20) default NULL,
  `address_country` varchar(30) default NULL,
  `address_status` varchar(10) default NULL,
  `payer_email` varchar(60) default NULL,
  `payer_status` varchar(10) default NULL,
  `payment_type` varchar(10) default NULL,
  `notify_version` varchar(10) default NULL,
  `verify_sign` varchar(10) default NULL,
  `referrer_id` varchar(10) default NULL,
  `custom` varchar(256) NOT NULL,
  PRIMARY KEY  (`paypal_transaction_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `paypal_transaction`
--
