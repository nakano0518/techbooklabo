-- MySQL dump 10.13  Distrib 5.5.62, for Linux (x86_64)
--
-- Host: localhost    Database: techbooklabo
-- ------------------------------------------------------
-- Server version	5.5.62
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,POSTGRESQL' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table "t_users"
--

DROP TABLE IF EXISTS t_users;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE t_users (
  userId varchar(255) NOT NULL,
  email varchar(32) NOT NULL,
  "password" varchar(1024) NOT NULL,
  "name" varchar(32) NOT NULL,
  imageUrl varchar(255) NOT NULL,
  workYear integer NOT NULL,
  "language" varchar(255) NOT NULL,
  "comment" varchar(255) NOT NULL,
   PRIMARY KEY (userId),
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "t_users"
--

/*!40000 ALTER TABLE t_users DISABLE KEYS */;
INSERT INTO t_users (userId, email, password, name, imageUrl, workYear, language, comment) VALUES ('nakano','m15sc018.prog@gmail.com','$2y$10$/Z674hRtyMYcxJAFOndUyuzQuaLUgagu9qDti3i2i505pfqnh6yXW','nakano','https://techbooklabo.s3.ap-northeast-1.amazonaws.com/profileImages/img_1582685779377.jpeg',0,'php、java、','本レビューサイトの作成者のnakanoです。転職用のポートフォリオとして学習開始5か月目に作成しました。勉強のためフレームワークを使用せず素のPHPで開発し、AWS(EC2/RDS/S3/ALB/Route53)でデプロイしました。よろしくお願いしま す。');
INSERT INTO t_users (userId, email, password, name, imageUrl, workYear, language, comment) VALUES ('nonameUser1','','$2y$10$PR0zz.f6aNtV1kELarH.kO/V0Myw5s2SXQgqPAEL93O9KODUYyY3W','','',0,'','');
INSERT INTO t_users (userId, email, password, name, imageUrl, workYear, language, comment) VALUES ('testUser1','test1@test1.com','$2y$10$7MY7dJ.UOgYL1kNRGnh9/.iC49/53fmif3uK8rs6gItLcO4CEWstS','testUser1','',0,'Java','テストユーザー1のコメント');
INSERT INTO t_users (userId, email, password, name, imageUrl, workYear, language, comment) VALUES ('testUser2','','$2y$10$8uUS5RFWrxrZY0CoTnvko.Q6neB8YgdnFe4GNK4oyCuwPu.zn9QH2','','',0,'','');
INSERT INTO t_users (userId, email, password, name, imageUrl, workYear, language, comment) VALUES ('testUser3','','$2y$10$uiiMsMe6ZhVyeysogjgcveaFi7wd5prwvN0ODrcZ/4gbCnQxw2iLu','','',0,'','');
INSERT INTO t_users (userId, email, password, name, imageUrl, workYear, language, comment) VALUES ('testUser4','','$2y$10$WFF0KCK2WaI3oDi6Lz6iE.za5Hb/p6CwKsY.mepYGzDPP1YI3eujK','','',0,'','');
/*!40000 ALTER TABLE t_users ENABLE KEYS */;

--
-- Table structure for table "t_book"
--

DROP TABLE IF EXISTS t_book;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE t_book (
  "no" varchar(13) NOT NULL,
  bookTitle varchar(255) NOT NULL,
  imageUrl varchar(255) NOT NULL,
  affiliateUrl varchar(255) NOT NULL,
  category varchar(32) NOT NULL,
  price integer NOT NULL,
  pages integer NOT NULL,
  description text NOT NULL,
  register varchar(255) NOT NULL,
  created_at timestamp NOT NULL DEFAULT,
  modified_at timestamp NOT NULL DEFAULT,
  PRIMARY KEY ("no")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "t_book"
--

/*!40000 ALTER TABLE t_book DISABLE KEYS */;
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784048913911','Web制作の現場で使うjQueryデザイン入門改訂新版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/3911/9784048913911.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F12236298%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D12236298%26surl%3Dbook','jquery',3278,311,'Ｗｅｂ制作に必要なｊＱｕｅｒｙの使い方を効率よくしっかり学 べる。スマートフォン、ＨＴＭＬ５、ｊＱｕｅｒｙ　２．０など最新のトレンドにも 対応。商用利用ＯＫ・改変自由・著作権表示不要のサンプルを１００本超提供。デス クサイドですぐ見られるｊＱｕｅｒｙ　１．９対応特製チートシート付き。','nakano','2020-02-13 10:09:11','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784295002352','Web制作者の ためのSassの教科書改訂2版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/2352/9784295002352.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15109710%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D15109710%26surl%3Dbook','sass',2640,296,'ＣＳＳをより便利に、効率的に書ける！基本から実践テクニックまで、この一冊で完全網羅。タスクランナー「ｇｕｌｐ」での導入方法から 、ＧＵＩでの導入方法、著者が実際に仕事の現場で使っているテクニックまで徹底解 説！','nakano','2020-02-22 03:45:28','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784295007807','スッキリわかるJava入門第3版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/7807/9784295007807.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F16099007%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D16099007%26surl%3Dbook','java',2860,768,'圧倒的人気Ｎｏ．１入門書の増補改訂版！コレクシ ョンを追加！基本文法やオブジェクト指向の「なぜ？」が必ずわかる！','nakano','2020-02-13 10:12:59','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784295007876','かんたん合格基本情報技術者過去問題集(令和2年度春期)','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/7876/9784295007876.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F16117584%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D16117584%26surl%3Dbook','基本情報,基本情報技術者',1628,576,'“巻頭特集”春試験か ら追加、Ｐｙｔｈｏｎ問題で点を取る！！合格力に差がつく３ステップ構成。計１８ 回分、紙面で４回＋ＰＤＦで１４回。','testUser1','2020-03-28 09:30:32','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784296105441','Amazon Web Services 基礎からのネットワーク&サーバー構築 改訂3版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/5441/9784296105441.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F16215301%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D16215301%26surl%3Dbook','vi,amazon',2970,0,'ネットワーク技術のハンズオン教材。ＡＷＳも一緒に学べるお得な１冊。アプリ開発者に大人気！インフラ構築の第１歩に最適。','nakano','2020-02-13 10:17:12','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784297106614','ゼロからわかるAmazon Web Services超入門 はじめてのクラウド','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/6614/9784297106614.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15921188%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D15921188%26surl%3Dbook','vi,amazon',2948,311,'ＡＷＳでＷｅｂサイトを構築・運用！実際に動かして学べる１冊。練習問題＆解答・解説集付き。','nakano','2020-02-13 10:17:14','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784297110215','キタミ式イラストIT塾 基本情報技術者 令和02年','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/0215/9784297110215.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F16144545%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D16144545%26surl%3Dbook','基本情報,基本情報技術者',2178,720,'','testUser1','2020-03-28 09:20:31','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784297110550','みんなのPHP 現場で役立つ最新ノウハウ!','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/0550/9784297110550.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F16117979%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D16117979%26surl%3Dbook','php',2398,0,'開発環境構築から活用テクニックまで。','nakano','2019-12-30 08:04:56','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784774144375','パーフェクトPHP','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/4375/9784774144375.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F6794779%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D6794779%26surl%3Dbook','php',3960,591,'ＰＨＰのセオリーを徹底解説。基本からＷｅｂアプリケーション開発、セキュリティまで完全網羅。','nakano','2019-12-29 03:18:43','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784774153773','JUnit実践入門','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/3773/9784774153773.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F12058050%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D12058050%26surl%3Dbook','ユニットテスト,junit',3630,455,'Ｊａｖａプログラマを対象としたＪＵｎｉｔによるユニットテストの実践ガイド。ユニットテストの基本概念から、テストコードの記述方法や拡張機能ま で、ＪＵｎｉｔに関する内容をほぼ網羅。テスト駆動開発などユニットテストを基盤 とする開発手法やツールも紹介。本書で解説するユニットテスト技法を実践し、身に 付けるための演習問題を収録した。チートシート付き。','nakano','2020-02-22 09:21:02','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784774163666','GitHub実践入門 ～Pull Request による開発の変革','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/3666/9784774163666.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F12688220%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D12688220%26surl%3Dbook','git,github',2838,285,'良いコードを迅速に生み出す快適な共同開発。手を動かし て身に付ける、実用的なワークフロー。','nakano','2020-02-13 10:10:22','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784774184111','JavaScript本格入門改訂新版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/4111/9784774184111.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F14433718%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D14433718%26surl%3Dbook','javascript,java,ip',3278,455,'ＥＣＭＡＳｃｒｉｐｔ２０１５によって、いっそう安全で便利な言語へと進化をつづけ るＪａｖａＳｃｒｉｐｔのプログラミングスタイルを基礎から解説。手軽さゆえに油 断しがちな正しい文法から、進化を遂げた新記法、オブジェクト指向構文、実際の開 発に欠かせない知識まで身につけられます。','nakano','2020-02-13 10:08:39','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784774189284','Jenkins実践入門改訂第3版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/9284/9784774189284.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F14917354%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D14917354%26surl%3Dbook','jenkins',3278,398,'継続的イン テグレーションに欠かせないツールであるＪｅｎｋｉｎｓ。その導入から運用管理ま でを解説した定番書として大好評の『Ｊｅｎｋｉｎｓ実践入門』が、ついに２系に対 応しました。生みの親である川口耕介氏監修のもと、近年の開発環境の変化に合わせ て内容を一新。インストールなどの基本から、ＪＵｎｉｔによるテストといった内容 はもちろんのこと、さまざまなソースコード管理システムとの連携やおすすめプラグ インの紹介、さらには認定試験についても説明します。チームの一員として上手に迎 えるための実開発のポイントがわかります。','nakano','2020-02-24 03:10:14','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784774193977','プロを目指す人のためのRuby入門','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/3977/9784774193977.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15209044%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D15209044%26surl%3Dbook','ruby',3278,455,'Ｒａｉｌｓをやる前に、Ｒｕｂｙを知ろう。みなさんが「Ｒｕｂｙをちゃんと理解しているＲ ａｉｌｓプログラマ」になれるように、Ｒｕｂｙの基礎知識から実践的な開発テクニ ックまで、丁寧に解説します。','nakano','2020-02-13 10:14:32','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784797327038','Java言語で学ぶデザインパターン入門増補改訂版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/7038/9784797327038.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F1683430%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D1683430%26surl%3Dbook','java',4180,528,'ＧｏＦの『デザインパターン』で紹介された２３個のパターンを、オブジェクト指向の初心者 にもわかるようにやさしく解説。すべてのパターンについて、Ｊａｖａのサンプルプ ログラムを掲載。「デザインパターンＱ＆Ａ」を新たに加筆。','nakano','2020-02-13 10:13:21','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784797372212','jQuery最高の教科書','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/2212/9784797372212.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F12557168%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D12557168%26surl%3Dbook','jquery',2838,0,'とことん丁寧×ステップアップ解説。だから、知識ゼロからでも本当によくわかる！ｊＱ ｕｅｒｙをこよなく愛するトップクリエイターが基本的な仕組みから、実務で活かせ る珠玉のテクニックまでを徹底詳解！経験に裏付けられた「わかりにくいポイント」 を押さえた解説だから確かな基礎力と、実務で活かせる実践力を身につけられる！','nakano','2020-02-13 10:09:25','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784797380941','新しいLinuxの教科書','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/0941/9784797380941.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F13241689%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D13241689%26surl%3Dbook','linux',2970,425,'ＭＳ-ＤＯＳを知らない世代のエンジニアに向けたＬｉｎｕｘ入門書の決定版。Ｌｉｎｕｘ自身の機能だけでなく、シェルスクリプトを使ったプログラミ ングや、Ｇｉｔによるソフトウェア開発のバージョン管理など、イマドキのエンジニ アなら知っておくべき知識についても、丁寧に解説しました！Ｒｅｄｈａｔ系、Ｄｅ ｂｉａｎ系に対応。','nakano','2020-02-13 10:16:41','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784797390513','新・明解 Javaで学ぶアルゴリズムとデータ構造','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/0513/9784797390513.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F14897416%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D14897416%26surl%3Dbook','java',2640,392,'すべてのＪａｖａ プログラマに贈る！アルゴリズムとデータ構造入門書の最高峰！！豊富なプログラム ８８編と分かりやすい図表２２９点でアルゴリズムとデータ構造の基礎をマスターし て、問題解決能力を身につけよう。（社）日本工学教育協会著作賞受賞。','nakano','2020-02-13 10:13:37','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784798059075','PHPフレームワ ークLaravel実践開発','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/9075/9784798059075.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15956681%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D15956681%26surl%3Dbook','php,laravel,ユニットテスト',3300,354,'「ＭＶＣ以外」の使いこなしもできる！『ＰＨＰフレームワーク　Ｌａｒａｖｅｌ入門』を読み終わった方にお勧め！「も っと知りたかったこと」を本書で詳細に解説！Ｌａｒａｖｅｌ５．８．９準拠。本書 で取り上げる主なテーマ：コア機能（ルーティング～ファイルアクセス）の詳細から 「サービス」、ＤＢクラスとＥｌｏｑｕｅｎｔ、ジョブ管理、フロントエンド（Ｖｕ ｅ．ｊｓ、Ｒｅａｃｔ、Ａｎｇｕｌａｒ）との連携、ユニットテスト、Ａｒｔｉｓａ ｎコマンドまで…。','nakano','2020-02-22 08:35:08','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784798060996','PHPフレームワークLaravel入門第2版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/0996/9784798060996.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F16164261%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D16164261%26surl%3Dbook','php,laravel',3300,368,'人気Ｎｏ．１フレームワークのロングセラー定番解説書が、新バージョン対応で改訂！Ｌａｒａｖｅｌ６、 ＰＨＰ７．２対応。','nakano','2020-02-13 10:05:15','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784798144450','SQL 第2版 ゼロからはじめるデータベース操作','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/4450/9784798144450.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F14244722%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D14244722%26surl%3Dbook','sql',2068,0,'本書は、「データベー スやＳＱＬがはじめて」という初心者を対象に、プロのデータベース（ＤＢ）エンジ ニアである著者がＳＱＬの基礎とコツをやさしく丁寧に教える入門書です。第２版で は、解説・サンプルコードを最新ＤＢのＳＱＬに対応したほか、（ＰｏｓｔｇｒｅＳ ＱＬを例に）アプリケーションプログラムからＳＱＬを実行する方法の解説章を新設 。ＳＱＬの書き方からアプリケーションでの利用方法までフォローします。','nakano','2020-02-13 10:09:52','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784800711304','詳細!PHP7+MySQL入門ノート','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/1304/9784800711304.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F14293014%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D14293014%26surl%3Dbook','php,sql,mysql',3278,528,'こんにちはＰＨＰ７。Ｗｅｂ新世代、セブンの誕生！初心 者にやさしく、経験者にも納得の１冊ができました。基本シンタックスからＭｙＳＱ Ｌデータベース連携まで、注釈付きのコードと手順を追った図で詳しく丁寧に解説し ました。豊富なサンプルを積み重ねて確実にスキルアップしましょう。約１０年ぶり のメジャーアップデート！ＰＨＰをはじめるなら今が最高のタイミングです。一押し ！ＰＨＰ７定番本！','nakano','2020-02-13 10:05:13','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784800712462','いちばんやさしい Git 入門教室','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/2462/9784800712462.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15981554%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D15981554%26surl%3Dbook','git',2398,240,'バージョン管理から運用ルールまでチーム開発の基本がしっかり学べます。誰が何をすべきなのか？チームを組んで仕事 をするすべてのクリエイターに最良の入門書！','nakano','2020-02-13 10:10:09','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784815601577','確かな力が身につくJavaScript「超」入門 第2版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/1577/9784815601577.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F16014712%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D16014712%26surl%3Dbook','javascript,java,ip',2728,336,'','nakano','2020-02-13 10:08:46','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784822283117','ネットワークはなぜつながるのか第2版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/8222/82228311.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F4312716%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D4312716%26surl%3Dbook','',2640,445,'ブラウザにＵＲＬを入力してからＷｅｂページが表示されるまでの道筋を探検。ネットワーク技術に関する説明内容を全 面的に見直し、基礎的な解説を大幅に加筆した改訂版。','testUser1','2020-03-26 14:43:27','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784822292508','Amazon Web Services 定番業務システム14パターン 設計ガイド','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/2508/9784822292508.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15610237%2F','vi,amazon',2750,208,'','testUser1','2020-12-22 07:45:45','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784839962227','現場で使える Ruby on Rails 5速習実践ガイド','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/2227/9784839962227.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15628625%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D15628625%26surl%3Dbook','ruby',3828,480,'ステップバイステップで学ぶ。Ｒａｉｌｓアプリの基本から開発プロジェクトで求められる実践的なノウハウまでこの１冊で！','nakano','2020-02-13 10:14:28','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784839965471','よくわかるHTML5+CSS3の教科書【第3版】','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/5471/9784839965471.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F15665218%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D15665218%26surl%3Dbook','html,css',3080,336,'楽しく学んで、基礎力をしっかり固める！「ＨＴＭＬ５＋ＣＳＳ３」を学習する人のための定番書籍。レスポンシブレイアウト、フレックスボックスレ イアウト、グリッドレイアウト対応。「今から始める人」にも「最新でおさらいした い人」にも最適の１冊！ＨＴＭＬ５．２対応。','nakano','2020-02-13 10:06:50','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784844336778','スッキリわかるJava入門(実践編)第2版','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/6778/9784844336778.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F12919823%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D12919823%26surl%3Dbook','java',3080,628,'','nakano','2020-02-13 10:13:02','0000-00-00 00:00:00');
INSERT INTO t_book (no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, created_at, modified_at) VALUES ('9784863542174','わかばちゃんと学ぶGit使い方入門','https://thumbnail.image.rakuten.co.jp/@0_mall/book/cabinet/2174/9784863542174.jpg','https://hb.afl.rakuten.co.jp/hgc/g00q0727.6bbv087e.g00q0727.6bbv1c0b/?pc=https%3A%2F%2Fbooks.rakuten.co.jp%2Frb%2F14904582%2F&m=http%3A%2F%2Fm.rakuten.co.jp%2Frms%2Fmsv%2FItem%3Fn%3D14904582%26surl%3Dbook','git',2453,245,'マンガ＋実践でＧｉｔの使い方がよくわかる！ＩＴエ ンジニアはもちろん、Ｗｅｂデザイナーにも最適な入門書！ＳｏｕｒｃｅＴｒｅｅ対 応。Ｗｉｎｄｏｗｓ　Ｍａｃ対応！','nakano','2020-02-13 10:10:05','0000-00-00 00:00:00');
/*!40000 ALTER TABLE t_book ENABLE KEYS */;

--
-- Table structure for table "t_review"
--

DROP TABLE IF EXISTS t_review;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE t_review (
  reviewId integer NOT NULL,
  userId varchar(32) NOT NULL,
  "no" varchar(13) NOT NULL,
  reviewContent varchar(255) NOT NULL,
  PRIMARY KEY (reviewId)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "t_review"
--

/*!40000 ALTER TABLE t_review DISABLE KEYS */;
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (1,'nakano','9784774144375','【読了】\r\n基礎的な知識がついた後に読んだが、更なる知識を身につけられた。リファレンス的にも活 用できる。フレームワークを作る章があり、laravelを学ぶ前に読むことで理解が深まった。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (2,'nakano','9784295007807','【読了】\r\nオブジェクト指向の概念が非 常にわかりやすい。とっかかりとして最高の一冊だと感じた。言語関係なく読んでお くべき一冊だと感じた。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (3,'nakano','9784863542174','【読了】\r\nGitに関して全く知識のない状態で読んだがかなり理解しやすかった。そのような初心者に最適な 一冊だと感じた。GitをCUIで操作する方法に関しては掲載されていないため、他の本 で学習する必要があると思う。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (4,'nakano','9784296105441','【読了】\r\nAWSを学びたい初心者が最初に読むべき一冊だと思った。ハンズオン形式で手を動かしなが ら基本的なネットワークとサーバー構築を一通り学ぶことができた。その際、必要な ネットワークの基礎知識なども紹介されるので、初心者でも無理なく学べると思う。 ただし、ネットワークの構築とEC2を用いたサーバーやDBの構築といった内容のみなので他の本で補完したほうがいいと思う。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (5,'nakano','9784297106614','【読了】\r\nAWSを学びたい初心者が読むべき一冊。EC2をはじめ、RDS、S3、ALBなど基本的なサービスの使い方を順を追って説明されている。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (6,'nakano','9784797380941','【 読了】\r\nLinux上にサーバーを構築し操作するようになりそのタイミングで読んだ。私のような初心者でも理解しやすかった。ただ、コマンドの説明が425ページに渡るので途中で飽きるかもしれない。私は実際に使ってから読んだためサクサク読み進める ことができた。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (7,'nakano','9784798060996','【読了】\r\nlaravelの基本的な知識、MVCの知識が不足している場合には読む価値のある本だと感じた。リファレンス形式の本であるため、実践的ではない。TODOリストなどを作成しまずフレームワークを 使って何か作る経験をし使える状態にする必要があると感じた。その際、リファレン ス的に使うと便利だと思った。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (8,'nakano','9784798059075','【読了】\r\nこの 本を読むよりは、作品を作成しながらそれに必要な知識を学ぶほうがいいと感じた。laravelのリファレンスも十分充実しているので、個人的にはこのようなリファレンス 形式な本は不要であったなと思う。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (13,'nakano','9784297110215','IT系の用語が非常にわかりやすく解説されていて、実務未経験である私でもしっかり理解しながら 読み進められた。');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (15,'testUser1','9784844336778','テストレビュー');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (16,'testUser1','9784295007807','テストレビュー');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (17,'testUser1','9784797327038','テストレビュー');
INSERT INTO t_review (reviewId, userId, no, reviewContent) VALUES (18,'testUser1','9784774153773','テストレビュー');
/*!40000 ALTER TABLE t_review ENABLE KEYS */;

--
-- Table structure for table "t_good"
--

DROP TABLE IF EXISTS t_good;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE t_good (
  goodId integer NOT NULL,
  "no" varchar(255) NOT NULL,
  userId varchar(32) NOT NULL,
  delete_flg smallint NOT NULL DEFAULT '0',
  created_date timestamp NOT NULL DEFAULT,
  update_date timestamp NOT NULL DEFAULT,
  PRIMARY KEY (goodId)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "t_good"
--

/*!40000 ALTER TABLE t_good DISABLE KEYS */;
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (1,'9784797327038','nakano',1,'0000-00-00 00:00:00','2020-02-13 10:18:15');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (4,'9784297110215','nakano',1,'0000-00-00 00:00:00','2020-03-29 14:07:15');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (5,'9784844336778','testUser1',1,'0000-00-00 00:00:00','2020-03-30 03:53:48');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (7,'9784774153773','testUser1',1,'0000-00-00 00:00:00','2020-03-30 03:54:05');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (8,'9784797327038','testUser1',1,'0000-00-00 00:00:00','2020-03-30 03:54:17');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (9,'9784800711304','testUser1',1,'0000-00-00 00:00:00','2020-03-30 03:54:53');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (10,'9784774144375','testUser1',1,'0000-00-00 00:00:00','2020-03-30 03:55:03');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (11,'9784297110550','testUser1',1,'0000-00-00 00:00:00','2020-03-30 03:55:16');
INSERT INTO t_good (goodId, no, userId, delete_flg, created_date, update_date) VALUES (13,'9784295007807','testUser1',1,'0000-00-00 00:00:00','2020-07-04 07:10:00');
/*!40000 ALTER TABLE t_good ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-23  1:58:52
