-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-29 04:50:43
-- 服务器版本： 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anzuke`
--

-- --------------------------------------------------------

--
-- 表的结构 `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL COMMENT '收藏者用户名',
  `sell_id` int(11) NOT NULL COMMENT '房源id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `collection`
--

INSERT INTO `collection` (`id`, `username`, `sell_id`) VALUES
(15, '699393946', 20),
(16, '699393946', 19),
(19, '336501303', 20);

-- --------------------------------------------------------

--
-- 表的结构 `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `sell_id` int(11) NOT NULL COMMENT '房源id',
  `file` varchar(128) NOT NULL COMMENT '文件名',
  `time` varchar(20) NOT NULL COMMENT '上传时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `photo`
--

INSERT INTO `photo` (`id`, `sell_id`, `file`, `time`) VALUES
(6, 18, '672461e1b2811e15c6da5a3033e20149.jpg', '20180627'),
(7, 18, '4ba16493250eaa6670b5f2c699fd8b6a.jpg', '20180627'),
(8, 18, '960bf3b90e65b2f1908123fa7f1c217a.jpg', '20180627'),
(9, 20, 'c839d215d38b79b2096fd01b56096d5b.jpg', '20180627'),
(10, 20, '9f0fb42ff04663afe9b78af6632d7854.jpg', '20180627'),
(11, 20, '815bb88ab5de07cf9b6ac520216197e2.jpg', '20180627'),
(12, 20, 'a539bc9eceb814e102e15fece7c0bcf9.jpg', '20180627'),
(13, 20, '51b2b658d02e9839fccd059f7783aa9d.jpg', '20180627'),
(14, 20, 'c8ea7bbf480f597254a302c6a399f6d1.jpg', '20180627'),
(15, 20, '49a341ea72411c3463faeeac3751fe48.jpg', '20180627');

-- --------------------------------------------------------

--
-- 表的结构 `sell`
--

CREATE TABLE `sell` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `address` varchar(40) NOT NULL COMMENT '房源地址',
  `building` int(11) DEFAULT NULL COMMENT '楼栋（号）',
  `unit` int(11) DEFAULT NULL COMMENT '单元',
  `area` int(11) NOT NULL COMMENT '面积',
  `room` int(11) NOT NULL COMMENT '户型（室）',
  `hall` int(11) NOT NULL COMMENT '户型（厅）',
  `wash` int(11) NOT NULL COMMENT '户型（卫）',
  `price` int(11) NOT NULL COMMENT '租金',
  `floor` int(11) NOT NULL COMMENT '所在楼层',
  `tall` int(11) NOT NULL COMMENT '总楼层',
  `type` varchar(20) NOT NULL COMMENT '装修类型',
  `content` varchar(100) DEFAULT NULL COMMENT '业主描述（标题）',
  `seller` varchar(10) NOT NULL COMMENT '房东称呼',
  `phone` varchar(11) NOT NULL COMMENT '联系方式',
  `delete_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sell`
--

INSERT INTO `sell` (`id`, `username`, `address`, `building`, `unit`, `area`, `room`, `hall`, `wash`, `price`, `floor`, `tall`, `type`, `content`, `seller`, `phone`, `delete_time`, `create_time`, `update_time`) VALUES
(1, '', '中国计量大学现代科技学院', 0, NULL, 1000, 3, 1, 1, 2000, 3, 8, '装修', '这房子很棒', '屈先生', '15868104418', 0, 0, 0),
(2, '', '现代科技学院生活区5号楼208', 0, NULL, 30, 1, 0, 1, 800, 2, 6, '毛坯', '我的寝室，很棒', '屈先生', '15868104418', 0, 0, 0),
(3, '', 'aaa', NULL, 1, 133, 1, 1, 1, 1000, 6, 8, '', 'aaaaaaa', '帅哥', '10086', 0, 1530077443, 1530077443),
(4, '699393946', 'dadada', 2, 2, 111, 1, 1, 1, 111, 1, 1, '毛坯', '11123123123123', 'aaa', '11111', 0, 1530077703, 1530077703),
(5, '699393946', '1123123', 1, 1, 1, 1, 1, 1, 1111, 1, 1, '毛坯', '', '123', '123', 0, 1530078404, 1530078404),
(6, '699393946', 'abc', 1, 1, 1331, 1, 1, 1, 1111, 1, 1, '豪华装修', '', '123', '123', 0, 1530078476, 1530078476),
(10, '699393946', 'aaa', 0, 0, 0, 0, 0, 0, 0, 0, 0, '普通装修', 'asdasdadsswwwww', 'AA', 'aaaaa', 0, 1530080554, 1530080554),
(11, '699393946', 'aaa', 0, 0, 1, 0, 0, 0, 0, 0, 0, '普通装修', 'asdasdadsswwwww', 'AA', 'aaaaa', 0, 1530080720, 1530080720),
(12, '699393946', 'asa', 1, 1, 111, 1, 1, 1, 111, 1, 1, '普通装修', '', 'AAa', '11111', 0, 1530080771, 1530080771),
(13, '699393946', 'asa', 1, 1, 111, 1, 1, 1, 111, 1, 1, '普通装修', '', 'AAa', '11111', 0, 1530080857, 1530080857),
(14, '699393946', 'asd', 0, 0, 0, 0, 0, 0, 0, 0, 0, '装修', 'asdsss', 'asd', 'ads', 0, 1530080882, 1530080882),
(15, '699393946', 'as', 0, 0, 0, 0, 0, 0, 0, 0, 0, '普通装修', 'asdasd', 'as', 'ASd', 0, 1530080959, 1530080959),
(17, '699393946', '现代科技学院生活区5号楼208', 5, 2, 100, 1, 1, 1, 998, 2, 6, '普通装修', '海景别墅一套', '屈先生', '10086', 1, 1530081269, 1530081269),
(20, '699393946', '五号楼208', 1, 1, 111, 111, 1, 1, 111, 1, 1, '精装修', '你好吗', '啊啊', '123123', 0, 1530090706, 1530090706),
(22, '699393946', '梅花小区', 3, 3, 300, 2, 3, 2, 1000, 3, 5, '装修', '欢迎入住', '屈先生', '15868104418', 1, 1530168121, 1530168121);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `password` varchar(200) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `nickname`, `password`, `phone`, `email`, `create_time`, `update_time`) VALUES
(19, '699393946', '屈', '$2y$10$iZcqX4C/3T8Seg49SogmBO8nvYmV9NyBh37hUuNCnDF9mN8kJKRfK', '13968638309', '10086@qq.com', 1529741127, 1530168143),
(20, '164434893', '用户164434893', '$2y$10$H2MZK4a7Rv4JtMj6hQmJD.ZME4.lut2/RvaiWtgtWYrp7zNakjFnq', '15868104420', '', 1529822754, 1529822754),
(21, '336501303', '用户336501303', '$2y$10$xqj29yw8demi/eOU88r2TOMfuF32M/IKu1AEZnkUuLpfdf1Okr/R6', '15868104418', '', 1530100096, 1530100096),
(22, '605918456', '用户605918456', '$2y$10$CTGU5V8hk4DU7df8SR19i.HlK16o8oKupoxStjNcTwOtHVu3DSvlS', '15868142315', '', 1530164821, 1530164821),
(23, '207679475', '用户207679475', '$2y$10$jkdHntZpfI6z2SceUdqa.uSXf5gGLg1wh0Iij4Sxlmwc5BybFD/yG', '15868142315', '', 1530164857, 1530164857),
(24, '142358074', '用户142358074', '$2y$10$SJH1vLIWTVThG5ajcFAcE.ll19ez8pSAEg0A/OUvxVqFND3NSdXfq', '15868142316', '', 1530165828, 1530165828);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sell`
--
ALTER TABLE `sell`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `collection`
--
ALTER TABLE `collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用表AUTO_INCREMENT `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 使用表AUTO_INCREMENT `sell`
--
ALTER TABLE `sell`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
