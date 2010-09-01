-- phpMyAdmin SQL Dump
-- version 3.3.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 18, 2010 at 01:08 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `phpmybitcoin`
--

-- --------------------------------------------------------

--
-- Table structure for table `cake_sessions`
--

CREATE TABLE IF NOT EXISTS `cake_sessions` (
  `id` varchar(255) NOT NULL,
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `model` varchar(255) NOT NULL,
  `model_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `change` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created` (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `port` smallint(5) NOT NULL DEFAULT '8332',
  `uri` varchar(255) DEFAULT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `version` smallint(5) unsigned DEFAULT NULL,
  `balance` decimal(8,2) DEFAULT NULL,
  `blocks` mediumint(8) unsigned DEFAULT NULL,
  `connections` smallint(5) unsigned DEFAULT NULL,
  `generate` tinyint(1) DEFAULT NULL,
  `hashespersec` int(11) unsigned DEFAULT NULL,
  `pending_blocks` smallint(5) unsigned DEFAULT NULL,
  `generated_blocks` smallint(5) unsigned DEFAULT NULL,
  `status` varchar(32) DEFAULT NULL,
  `last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(64) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
