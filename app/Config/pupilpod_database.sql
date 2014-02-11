-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 11, 2014 at 12:17 PM
-- Server version: 5.5.33
-- PHP Version: 5.4.4-14+deb7u7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mis`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `activity` mediumtext NOT NULL,
  `area_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `pupil_id` int(11) DEFAULT NULL,
  `hide` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pupil_id` (`pupil_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE IF NOT EXISTS `attendances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pupil_id` int(11) NOT NULL,
  `week` date NOT NULL,
  `date` date NOT NULL,
  `dayindex` int(11) NOT NULL,
  `actual` int(11) NOT NULL,
  `potential` int(11) NOT NULL,
  `code` varchar(1) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `outcomes_id` int(11) NOT NULL,
  `notes` varchar(280) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pupil_id` (`pupil_id`),
  KEY `week` (`week`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27226 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(60) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `phone` varchar(13) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(60) DEFAULT NULL,
  `pupil_id` int(11) DEFAULT NULL,
  `report` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `pupil_id` int(11) DEFAULT NULL,
  `note` varchar(5000) NOT NULL,
  `date` datetime NOT NULL,
  `dayindex` int(11) DEFAULT NULL,
  `tcomment` varchar(5000) DEFAULT NULL,
  `pcomment` varchar(5000) DEFAULT NULL,
  `reportstatus` int(12) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  KEY `reportstatus` (`reportstatus`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=876 ;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `list_id` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1058 ;

-- --------------------------------------------------------

--
-- Table structure for table `outcomes`
--

CREATE TABLE IF NOT EXISTS `outcomes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pupil_id` int(11) NOT NULL,
  `attendance_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `expected` varchar(2) NOT NULL,
  `outcomes` varchar(1000) DEFAULT NULL,
  `nextsteps` varchar(1000) DEFAULT NULL,
  `target_id` int(11) NOT NULL,
  `award` varchar(11) NOT NULL,
  `baseline` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pupil_id` (`pupil_id`,`attendance_id`,`activity_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `pupils`
--

CREATE TABLE IF NOT EXISTS `pupils` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `gender` tinyint(1) NOT NULL,
  `ethnicity` varchar(150) DEFAULT NULL,
  `address_1` varchar(60) DEFAULT NULL,
  `address_2` varchar(60) DEFAULT NULL,
  `address_3` varchar(250) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `postcode` varchar(12) DEFAULT NULL,
  `sen_statement` tinyint(1) DEFAULT NULL,
  `sen` int(11) NOT NULL DEFAULT '0',
  `school_id` int(11) unsigned NOT NULL,
  `lea_id` int(11) DEFAULT NULL,
  `option_id` int(11) unsigned NOT NULL,
  `dialysis` tinyint(1) DEFAULT NULL,
  `staff_id` int(11) unsigned DEFAULT NULL,
  `sibling` tinyint(1) DEFAULT NULL,
  `child_of_staff` tinyint(1) DEFAULT NULL,
  `photo_consent` tinyint(1) DEFAULT NULL,
  `internet_agreement` tinyint(1) DEFAULT NULL,
  `notes` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13239 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `pupilsessions`
--
CREATE TABLE IF NOT EXISTS `pupilsessions` (
`pupil_id` int(11)
,`actual` decimal(32,0)
,`option_id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `pupils_sens`
--

CREATE TABLE IF NOT EXISTS `pupils_sens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pupil_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `pupils_targets`
--

CREATE TABLE IF NOT EXISTS `pupils_targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_id` int(11) NOT NULL,
  `pupil_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `address_1` varchar(100) DEFAULT NULL,
  `address_3` varchar(60) DEFAULT NULL,
  `locality` varchar(60) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `county` varchar(40) DEFAULT NULL,
  `postcode` varchar(12) DEFAULT NULL,
  `edubase_number` int(11) DEFAULT NULL,
  `la_name` varchar(100) DEFAULT NULL,
  `lea_id` int(11) DEFAULT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `phone_area_code` int(11) DEFAULT NULL,
  `school_type` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `administrative_ward` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67975 ;

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE IF NOT EXISTS `staffs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `role` varchar(200) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `list_id` (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `targets`
--

CREATE TABLE IF NOT EXISTS `targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `target` mediumtext,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `fullname` varchar(250) NOT NULL,
  `jobtitle` varchar(250) DEFAULT NULL,
  `option_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure for view `pupilsessions`
--
DROP TABLE IF EXISTS `pupilsessions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pupilsessions` AS select `attendances`.`pupil_id` AS `pupil_id`,sum(`attendances`.`actual`) AS `actual`,`attendances`.`option_id` AS `option_id` from `attendances` where (`attendances`.`date` between '2014-01-05' and '2014-02-15') group by `attendances`.`pupil_id` order by sum(`attendances`.`actual`) desc;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
