-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2020-12-16 04:37:16
-- サーバのバージョン： 10.4.17-MariaDB
-- PHP のバージョン: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `original_php`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `age`
--

CREATE TABLE `age` (
  `id` int(11) NOT NULL,
  `age` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `age`
--

INSERT INTO `age` (`id`, `age`) VALUES
(1, '0歳児'),
(2, '1歳児'),
(3, '2歳児');

-- --------------------------------------------------------

--
-- テーブルの構造 `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, '手作りおもちゃ'),
(2, '製作'),
(3, '絵本');

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trouble_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `comment` varchar(256) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='悩み内容へのコメント情報';

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `trouble_id`, `name`, `comment`, `create_at`) VALUES
(1, 2, 3, 'いちご', 'テストコメント', '2020-12-03 19:00:17'),
(2, 2, 3, 'メロン', 'テストコメント', '2020-12-03 19:22:40'),
(4, 10, 1, 'マスカット', 'テストコメント', '2020-12-03 19:27:11'),
(5, 10, 4, 'バナナ', 'テストコメント', '2020-12-03 19:35:00'),
(6, 8, 4, 'レモン', 'テストコメント', '2020-12-06 20:19:44');

-- --------------------------------------------------------

--
-- テーブルの構造 `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `idea_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `favorites`
--

INSERT INTO `favorites` (`user_id`, `idea_id`) VALUES
(11, 11),
(11, 6);

-- --------------------------------------------------------

--
-- テーブルの構造 `ideas`
--

CREATE TABLE `ideas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `age_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='アイデア情報';

--
-- テーブルのデータのダンプ `ideas`
--

INSERT INTO `ideas` (`id`, `user_id`, `title`, `age_id`, `category_id`, `image`, `comment`) VALUES
(4, 8, 'ドーナツ型動物おもちゃ', 0, 1, '0omocha3.jpg', '用意するもの\r\n・フェルト\r\n・ホース(100均に売っています)\r\n・鈴\r\n\r\n１．ホースを適当な長さに切ります。\r\n２．切ったホースに鈴を入れて輪っかにし、テープで固定します。\r\n３．フェルトで包み好きな動物の顔をつけ可愛くしたら完成です！\r\n'),
(5, 8, 'testtitle', 0, 2, '0seisaku2.jpg', '手型、足型を使った製作です。'),
(6, 8, 'testtitle', 0, 3, '0ehon1.jpg', '色々な表情の顔が出てきて楽しい絵本です。'),
(7, 4, 'testtitle', 1, 1, '1omocha2.jpg', 'サイコロ型の動物パズルです。'),
(8, 4, 'testtitle', 1, 2, '1seisaku1.jpg', '個性あふれるお弁当が出来上がりました。'),
(9, 4, 'testtitle', 1, 3, '1ehon1.jpeg', 'だるまさんの動きを真似して楽しんでいます。'),
(10, 2, 'testtitle', 2, 1, '2omocha3.jpeg', '指の力を育てるおもちゃです。'),
(11, 2, 'testtitle', 2, 2, '2seisaku2.jpg', '絵の具を使ってアジサイ製作をしました。'),
(12, 2, 'testtitle', 2, 3, '2ehon1.jpg', '絵本の中のセリフを覚えて一緒に声に出して楽しんでいます。'),
(14, 8, 'test', 2, 3, '2ehon2.jpg', 'ワンピースが着たくなる一冊です。'),
(16, 11, 'フェルトのおもちゃ', 0, 1, '0omocha1.jpg', '子どもが引っ張ったり、なめたりして存分に楽しめるようパペット・ボタンかけ・ひも通しなど色々なコーナーを組み合わせて作りました。\r\n作るのは少々大変だと思いますが、自由遊びの時間に出してみたところ０歳クラスの子どもたちだけでなく１歳や２歳クラスの子どもまで興味を持って遊んでくれたので頑張って作って良かったです！'),
(17, 11, '節分製作', 0, 2, '0seisaku1.jpg', '子どもたちの足型を使って節分のオニを作りました。');

-- --------------------------------------------------------

--
-- テーブルの構造 `troubles`
--

CREATE TABLE `troubles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  `category` varchar(32) NOT NULL,
  `trouble` varchar(256) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `troubles`
--

INSERT INTO `troubles` (`id`, `user_id`, `name`, `title`, `category`, `trouble`, `create_at`) VALUES
(1, 6, 'りんご', '偏食の1歳児', '食事', '緑の野菜を全く食べません', '2020-11-26 17:18:30'),
(3, 1, 'もも', 'クリスマス会の出し物', 'その他', 'なにか良いものがあれば教えてください', '2020-11-27 19:22:26'),
(4, 7, 'いちご', '２歳児のあそび', 'あそび', '楽しんで体を動かせるあそびはありますか？', '2020-11-27 19:22:26'),
(5, 1, 'ぶどう', 'test', '生活習慣', 'テストコメント', '2020-11-30 19:32:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `kana` varchar(32) NOT NULL,
  `mail` varchar(256) NOT NULL,
  `password` varchar(255) NOT NULL,
  `idea_id` int(11) DEFAULT NULL,
  `role` int(1) DEFAULT 1 COMMENT '0=管理者、1=一般ユーザー',
  `delflag` int(11) NOT NULL DEFAULT 1 COMMENT '0=削除済,1=デフォルト'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ユーザー情報';

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `kana`, `mail`, `password`, `idea_id`, `role`, `delflag`) VALUES
(2, '宮瀬豪', 'ミヤセゴウ', 'bbb@test.jp', 'umehara', NULL, 1, 1),
(3, '今大路峻', 'イマオオジシュン', 'ccc@test.jp', 'namikawa', NULL, 1, 1),
(4, '日向志音', 'ヒナタシオン', 'xxx@tir-group.jp', 'kenn44', NULL, 1, 1),
(5, '夏目春', 'ナツメハル', 'a.kayama@tir-group.jp', '$2y$10$XrkOrxjH2cDl3xJnJvdaA.CEtw7hT6iFoqNoUd49IzDLQkNIm1Hwm', NULL, 1, 1),
(7, '可愛ひかる', 'カワイヒカル', 'ddd@tir-group.jp', 'simono03', NULL, 1, 1),
(8, '春原百瀬', 'スノハラモモセ', 'momo@tir-group.jp', 'darin111', NULL, 1, 1),
(9, '九条天', 'クジョウテン', 'aaa@tir-group.jp', 'souma079', NULL, 1, 1),
(10, '山崎カナメ', 'ヤマザキカナメ', 'kkk@test.jp', 'kana831', NULL, 1, 1),
(11, '山田一郎', 'ヤマダイチロウ', 'rrr@tir-group.jp', '$2y$10$Nfv6BZXWv//LmFbaLYusc.r3jTSvO0c/9rB.58uHNsM9ZvA941vRS', NULL, 1, 1),
(12, '加山愛', 'カヤマアイ', 'testmail@tir-group.jp', '$2y$10$dNaNDd.G2shGpUKdJsBAq.WK95gRYboHo8ZaEbiPPDXKos7aFxaFa', NULL, 0, 1),
(13, '鈴木太郎', 'スズキタロウ', 'xxx@testmail.com', '$2y$10$ySqJoIb0bAMmUXZmvFZXdu47o677KWUSFXlP.qMYilX9W5pDM77lC', NULL, 1, 1),
(14, '山田花子', 'ヤマダハナコ', 'aaa@testmail.com', '$2y$10$v1gI9X3OdE.VhO96TqhHTOvRt.6NXVEBliaWt5YDRbjURIpOi3.Eq', NULL, 0, 1);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `age`
--
ALTER TABLE `age`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `ideas`
--
ALTER TABLE `ideas`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `troubles`
--
ALTER TABLE `troubles`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `age`
--
ALTER TABLE `age`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `ideas`
--
ALTER TABLE `ideas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- テーブルの AUTO_INCREMENT `troubles`
--
ALTER TABLE `troubles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
