CREATE DATABASE zenrobin

-- --------------------------------------------------------

-- 
-- Table structure for table `rr_awarded_loot`
-- 

CREATE TABLE `rr_awarded_loot` (
  `name` varchar(15) character set latin1 default NULL,
  `class` varchar(10) character set latin1 default NULL,
  `awarded` varchar(50) character set latin1 default NULL,
  `loot_type` varchar(15) character set latin1 default NULL,
  `date_awarded` date default NULL,
  `dkp_used` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

-- 
-- Table structure for table `rr_robin_table`
-- 

CREATE TABLE `rr_robin_table` (
  `name` varchar(15) character set latin1 default NULL,
  `class` varchar(10) character set latin1 default NULL,
  `rr_order` int(3) default NULL,
  `lastlooted` date default NULL,
  `firstpass` date default NULL,
  `secondpass` date default NULL,
  `added` date default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
