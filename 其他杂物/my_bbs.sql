-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-01-09 02:00:55
-- 服务器版本： 10.1.13-MariaDB
-- PHP Version: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_bbs`
--

-- --------------------------------------------------------

--
-- 表的结构 `mybbs_follow`
--

CREATE TABLE `mybbs_follow` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cardid` int(11) NOT NULL,
  `content` varchar(64) NOT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `floor` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mybbs_links`
--

CREATE TABLE `mybbs_links` (
  `id` tinyint(4) NOT NULL,
  `title` varchar(8) NOT NULL,
  `link` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mybbs_sections`
--

CREATE TABLE `mybbs_sections` (
  `id` int(11) NOT NULL,
  `parent_Id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `administrators` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mybbs_sections`
--

INSERT INTO `mybbs_sections` (`id`, `parent_Id`, `name`, `status`, `administrators`, `path`) VALUES
(1, 0, '1', 1, '1', '1'),
(2, 0, '2', 1, '1', '1'),
(3, 0, '3', 1, '1', '1'),
(4, 0, '4', 1, '1', '1'),
(5, 0, '5', 1, '1', '1'),
(6, 0, '6', 1, '1', '1'),
(7, 0, '7', 1, '1', '1'),
(8, 0, '8', 1, '1', '1'),
(9, 0, '9', 1, '1', '1'),
(10, 0, '10', 1, '1', '1'),
(11, 0, '11', 1, '1', '1'),
(12, 0, '12', 1, '1', '1');

-- --------------------------------------------------------

--
-- 表的结构 `mybbs_subject`
--

CREATE TABLE `mybbs_subject` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mybbs_subject`
--

INSERT INTO `mybbs_subject` (`id`, `section_id`, `name`, `uid`, `content`, `addtime`, `edittime`, `status`) VALUES
(4, 12, '234', 345, '56547678787686576563', '2017-01-06 09:18:21', '2017-01-06 11:21:45', 0),
(3, 2, '234', 345, '56547678787686y576t56676', '2017-01-05 16:00:00', '2017-01-06 13:21:18', 0);

-- --------------------------------------------------------

--
-- 表的结构 `mybbs_users`
--

CREATE TABLE `mybbs_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(16) NOT NULL,
  `userpass` varchar(8) NOT NULL,
  `nickname` varchar(16) DEFAULT NULL,
  `image` varchar(8) DEFAULT NULL,
  `address` varchar(16) DEFAULT NULL,
  `email` varchar(16) DEFAULT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(8) DEFAULT NULL,
  `integral` int(11) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `sign` varchar(16) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mybbs_follow`
--
ALTER TABLE `mybbs_follow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mybbs_links`
--
ALTER TABLE `mybbs_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mybbs_sections`
--
ALTER TABLE `mybbs_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mybbs_subject`
--
ALTER TABLE `mybbs_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mybbs_users`
--
ALTER TABLE `mybbs_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usercode` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `mybbs_follow`
--
ALTER TABLE `mybbs_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `mybbs_links`
--
ALTER TABLE `mybbs_links`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `mybbs_sections`
--
ALTER TABLE `mybbs_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `mybbs_subject`
--
ALTER TABLE `mybbs_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `mybbs_users`
--
ALTER TABLE `mybbs_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
