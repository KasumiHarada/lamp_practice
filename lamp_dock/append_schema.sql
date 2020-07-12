-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql
-- 生成日時: 2019 年 8 月 16 日 06:28
-- サーバのバージョン： 5.7.27
-- PHP のバージョン: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


-- --------------------------------------------------------

--
-- テーブルの構造 `history`
--

CREATE TABLE `history` (
    `history_id` int(11) AUTO_INCREMENT,
    `user_id` int (11) NOT NULL,
    `purchase_datetime` datetime,
    PRIMARY KEY(history_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `purchase_detail`
--

CREATE TABLE `purchase_detail` (
    `history_id` int(11) NOT NULL,
    `item_id` int(11) NOT NULL,
    `amount` int(11) NOT NULL,
    `price` int(11) NOT NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------