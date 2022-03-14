-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 16 Ara 2021, 20:16:24
-- Sunucu sürümü: 5.7.31
-- PHP Sürümü: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `dusozluk_bunyaminkardes`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `girisloglari`
--

DROP TABLE IF EXISTS `girisloglari`;
CREATE TABLE IF NOT EXISTS `girisloglari` (
  `islem` text NOT NULL,
  `ipadresi` varchar(60) NOT NULL,
  `tarih` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `konular`
--

DROP TABLE IF EXISTS `konular`;
CREATE TABLE IF NOT EXISTS `konular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `konu_baslik` varchar(60) NOT NULL,
  `konu_icerik` text NOT NULL,
  `user` varchar(30) NOT NULL,
  `tarih` varchar(30) NOT NULL,
  `tarihal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mesajsayisi` int(11) NOT NULL DEFAULT '0',
  `likesayisi` int(11) NOT NULL DEFAULT '0',
  `dislikesayisi` int(11) NOT NULL DEFAULT '0',
  `konu_turu` varchar(60) NOT NULL DEFAULT 'tanimlanmamis',
  PRIMARY KEY (`id`),
  UNIQUE KEY `konu_baslik` (`konu_baslik`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `konular`
--

INSERT INTO `konular` (`id`, `konu_baslik`, `konu_icerik`, `user`, `tarih`, `tarihal`, `mesajsayisi`, `likesayisi`, `dislikesayisi`, `konu_turu`) VALUES
(1, 'javada ekrana çıktı verme işlemi', 'bu iş için System.out.print() veya System.out.println() metodlarından herhangi biri kullanılabilir.\r\n\r\nÖrnek :\r\n\r\nSystem.out.println(\"Merhaba Dünya\");\r\n\r\n\r\nÖrnek:\r\n\r\nint degisken = 2000;\r\n\r\nSystem.out.println(degisken);', 'duduklutencere', '04-12-2021 02:23', '2021-12-04 02:23:14', 0, 0, 0, 'Universite'),
(2, 'javada kullanıcıdan girdi alma işlemi', 'öncelikle java.util.Scanner kütüphanesini projeye import etmek gerekir.\r\n\r\nkullanıcıdan girdi alabilmek için scanner sınıfından bir nesne oluşturmak gerekir :\r\n\r\nScanner obj = new Scanner();\r\n\r\nDaha sonra kullanıcıdan alacağımız girdi türüne özel metodlar kullanırız, örneğin kullanıcıdan metinsel bir girdi almak istersek :\r\n\r\nString degisken = obj.nextLine();\r\n\r\nVeya bir tamsayı girdisi almak için :\r\n\r\nint degisken = obj.nextInt();', 'duduklutencere', '04-12-2021 02:29', '2021-12-04 02:29:31', 0, 1, 1, 'Universite'),
(3, 'javada sabit değişken oluşturma', 'bu iş için \"finally\" anahtar kelimesi kullanılır.\r\n\r\nÖrnek :\r\n\r\nfinal int degisken1 = 10;\r\nfinal float degisken2 = 3.14f;\r\nfinal double degisken3 = 400.567d;', 'duduklutencere', '04-12-2021 02:40', '2021-12-04 02:40:26', 0, 0, 0, 'Universite'),
(5, 'javada diziler', 'Dizileri tanımlarken ya ilk değer ataması yapacaksın, ya da new anahtar kelimesini kullanarak eleman sayısını da belirtecek şekilde\r\nyazacaksın. Aynı şey çok boyutlu diziler için de geçerli.\r\n\r\n	yani :\r\n	   int dizi[] = {10,20,30};   // bu şekilde ilk değer ataması yapmıyorsan aşağıdaki gibi yapman lazım.\r\n	   int dizi[] = new int[3];\r\n\r\n\r\n\r\nString[] metinseldizi = {\"elma\",\"armut\",\"muz\",\"cilek\"};\r\nint[] tamsayidizisi = {10,20,30,40};\r\n\r\n\r\nSystem.out.println(metinseldizi[0]);       // metinseldizi dizisinin 1.elemanını ekrana yazdırır.  (elma)\r\nSystem.out.println(tamsayidizisi[0]);      // tamsayidizisi dizisinin 1.elemanını ekrana yazdırır. (10)\r\n\r\n\r\nSystem.out.println(metinseldizi.length);   // metinseldizi dizisinin kaç elemanı olduğunu ekrana yazdırır.\r\nSystem.out.println(tamsayidizisi.length);  // tamsayidizisi dizisinin kaç elemanı olduğunu ekrana yazdırır.\r\n\r\n\r\nfor(int i=0; i<metinseldizi.length; i++)   // metinseldizi dizisinin tüm elemanlarını ekrana yazdıran bir for döngüsü.\r\n{\r\n  System.out.println(metinseldizi[i]);\r\n}\r\n\r\n\r\nfor (String i : metinseldizi)              // metinseldizi dizisinin tüm elemanlarını ekrana yazdıran bir foreach döngüsü.\r\n{\r\n  System.out.println(i);\r\n}\r\n\r\n\r\n--------------------------------------------------------------------------------------------------------------------------------------------------------\r\n\r\nint matris[][] = new int[3][2];   // 3x2\'lik, yani 3 satır 2 sütundan oluşan bir matris oluşturduk. toplam 3*2 = 6 tane eleman depolayabilir.\r\n\r\nmatris[0][0] = 1;                 // matrisin elemanlarını manuel olarak belirleyelim.\r\nmatris[0][1] = 2;\r\nmatris[1][0] = 3;\r\nmatris[1][1] = 4;\r\nmatris[2][0] = 5;\r\nmatris[2][1] = 6;\r\n\r\nfor(int i=0; i<3; i++)            // matrisimizin tüm elemanlarını ekrana yazdıralım.\r\n{\r\n  for(int j=0; j<2; j++)\r\n  {\r\n     System.out.print(matris[i][j]+\" \");\r\n  }\r\n  System.out.println();\r\n}\r\n\r\n\r\n--------------------------------------------------------------------------------------------------------------------------------------------------------\r\n\r\n-> 5 Elemanlı bir dizinin elemanlarını kullanıcıdan alıp ekrana yazdıran program :\r\n\r\nimport java.util.Scanner;\r\n\r\npublic class Main\r\n{\r\n  public static void main(String[] args)\r\n  {\r\n    Scanner scan = new Scanner(System.in);\r\n    int dizi[] = new int[5];\r\n\r\n    for(int i=0; i<5; i++)\r\n    {\r\n      System.out.println(\"Dizinin \"+(i+1)+\".elemanını giriniz :\");\r\n      dizi[i] = scan.nextInt();\r\n    }\r\n\r\n    System.out.println(\"\\nDizinin elemanları :\\n\");\r\n    for(int i=0; i<5; i++)\r\n    {\r\n      System.out.println(dizi[i]+\"\\n\");\r\n    }\r\n  }\r\n}\r\n\r\n--------------------------------------------------------------------------------------------------------------------------------------------------------\r\n\r\n-> 3 satır, 2 sütundan oluşan bir matrisin elemanlarını kullanıcıdan alıp ekrana yazdıran program :\r\n\r\nimport java.util.Scanner;\r\n\r\npublic class Main\r\n{\r\n  public static void main(String[] args)\r\n  {\r\n    Scanner scan = new Scanner(System.in);\r\n    int matris[][] = new int[3][2];\r\n\r\n    for(int i=0; i<3; i++)\r\n    {\r\n      for(int j=0; j<2; j++)\r\n      {\r\n         System.out.println(\"Lütfen matrisin \"+(i)+\"x\"+(j)+\".elemanını giriniz :\\n\");\r\n         matris[i][j] = scan.nextInt();\r\n      }\r\n    }\r\n\r\n    for(int i=0; i<3; i++)\r\n    {\r\n      for(int j=0; j<2; j++)\r\n      {\r\n        System.out.print(matris[i][j]+\" \");\r\n      }\r\n      System.out.println();\r\n    }\r\n\r\n  }\r\n}', 'duduklutencere', '04-12-2021 03:05', '2021-12-04 03:05:44', 3, 2, 2, 'Universite'),
(6, 'gözden ırak olan gönülden de ırak mıdır', 'bence bunun kesin bir cevabı yok...', 'tonbaliklimakarna', '04-12-2021 14:58', '2021-12-04 14:58:09', 1, 4, 0, 'Yasam'),
(7, 'naruto vs sasuke', 'kim ne derse desin seriyi ayakta tutan karakter sasuke\'dir. \r\n\r\naksini iddia edenlerle sağlam tartışabilirim :)', 'xkraltr', '04-12-2021 15:01', '2021-12-04 15:01:59', 5, 1, 4, 'Anime'),
(9, 'memory leak', 'bellek sızıntısı demek. \r\n\r\nbelleğin heap kısmında oluşturulan değişkenleri işaret eden pointer\'ların stack hafızadan kontrolsüz bir şekilde silinmesi durumunda değişken heap bellekte kalmaya devam edecek fakat onu işaret eden pointer hafızadan silindiği için heap bellekteki değişkene erişim sağlanamayacaktır.\r\n\r\nbasitçe bir örnek :\r\n\r\n#include <iostream>\r\nint main()\r\n{\r\n  for(int i=0; i<1000; i++)\r\n  {\r\n    int *ptr = new int;\r\n  }\r\nreturn 0;\r\n}\r\n\r\nfor döngüsünden çıkıldığında pointerlar hafızadan silinecek fakat heap bellekte oluşturulan değişkenler hala orada kalmaya devam edecek.\r\n\r\nbunu engellemek için delete keyword kullanılabilir :\r\n\r\n#include <iostream>\r\nint main()\r\n{\r\n  for(int i=0; i<1000; i++)\r\n  {\r\n    int *ptr = new int;\r\n    delete ptr;\r\n  }\r\nreturn 0;\r\n}', 'tonbaliklimakarna', '04-12-2021 15:12', '2021-12-04 15:12:28', 0, 0, 0, 'Universite'),
(10, 'hataylı serdar geri döndü', 'evet arkadaşlar burada eskiden bolca takılan biriydim eskiler beni tanır yeniler örnek alır :)\r\n\r\nevet arkadaşlar bir çoğunuz beni eski sevgilim melis hakkında anlattığım hikayelerden dolayı tanıyorsunuz.\r\n\r\nuzun uzun anlatmak istemiyorum, hayatımda yaşadığım bazı olaylardan dolayı kendime büyük bir çeki düzen vermeye karar verdim. inanmayacaksınız ama sigara ve alkolü bıraktım. beş vakit namaza başladım. küfürü bıraktım. karı kız işlerini bıraktım. öyle tesadüf olaylar yaşadım ki anlatsam hiçbiriniz inanmazsınız. \r\n\r\nVelhasılkelam yüce Allah herkesi affeder, merhametlidir. Hiçbir şey için geç değil arkadaşlar.\r\n\r\nHa bu arada, melis yengenizle tekrar bir araya gelmiş bulunmaktayım :)', 'hatayli_serdar', '04-12-2021 15:30', '2021-12-04 15:30:12', 1, 1, 1, 'Genel'),
(11, 'mahmut vs salih', 'mıhlamaya muhlama diyen rizeli salih, bayburtlu mahmut seni kafes dövüşüne davet ediyormuş...', '69Lu', '04-12-2021 15:40', '2021-12-04 15:40:19', 2, 0, 2, 'Genel'),
(12, 'hayatım böyle 10 numara', 'hayatım böyle 10 numara\r\nforma giymem ama 10 numara\r\nkuran çarpsın 10 numara\r\nbak keyfim meyfim 10 numara', 'xkraltr', '04-12-2021 15:56', '2021-12-04 15:56:21', 1, 0, 0, 'Yasam'),
(13, 'loldür legender', 'on yaşındaki çocuklardan envai çeşit küfürlü sözcük öğrenebileceğiniz nezih bir tartışma platformu.\r\n\r\nbu platformda centilmence tartışmanın ilk kuralı rakibinizi lobiye çağırmak veya telefon numarasını çeşitli sitelere atmaktır. ', 'poseidoN35', '04-12-2021 16:01', '2021-12-04 16:01:40', 0, 0, 1, 'Spor'),
(15, 'burger yesek', 'hayatımda yediğim en güzel burger bu mekandaydı. herkese tavsiye ederim.', 'poseidoN35', '04-12-2021 18:30', '2021-12-04 18:30:29', 1, 3, 1, 'Yasam'),
(27, 'Atakan Titan', 'çin yapımı çizgi film. xkraltr\'nin seveceği türden', 'poseidoN35', '10-12-2021 22:15', '2021-12-10 22:15:04', 0, 0, 0, 'Anime'),
(16, 'herkese selam', 'merhaba arkadaşlar şimdilerde kendisine hataylı serdar lakabını takan biri var buralarda, bir aralar benim oturma kaslarımı deleceğini iddia ediyordu. hodrimeydan diyorum lâkin görüyorum ki başkaları onun oturma kaslarını delmiş olacak ki doğru yolu bulduğunu iddia ediyor :)', 'GAMERHAKAN', '04-12-2021 19:04', '2021-12-04 19:04:45', 0, 0, 0, 'Yasam'),
(17, 'yürü bre ehli deve endamını göreyim', 'sensiz geçen günlerin ...', 'bosislermuduru', '04-12-2021 19:14', '2021-12-04 19:14:59', 1, 0, 0, 'Muzik'),
(18, 'karalahana mı pancar mı', 'mıhlama mı muhlama mı', 'bosislermuduru', '04-12-2021 19:21', '2021-12-04 19:21:30', 0, 0, 0, 'Genel'),
(19, 'Tilkinin dönüp dolaşıp geleceği yer kürkçü dükkanıdır', 'çok anlamlı bir söz.', 'charlotte Jeanne', '09-12-2021 01:37', '2021-12-09 01:37:46', 1, 2, 0, 'Yasam'),
(25, 'Düzce Sözlüğe Hoş Geldin', 'Düzce sözlük sayfasına hoş geldin.\r\n\r\nBurada diğer insanlar tarafından açılmış konuları inceleyip yorum yapabilir veya kendi konularını açabilirsin.\r\n\r\nYorum yapabilmek ve Konu açabilmek için giriş yapman gerektiğini unutma.\r\n\r\nKategorilerde bulamadığın konuları arama çubuğunu kullanarak arayabilirsin, aynı zamanda sayfadaki diğer üyeleri de arayabilir ve profillerine bakabilirsin.\r\n\r\nAklına takılan herhangi bir soru için bize iletisim@duzcesozluk.com mail adresinden ulaşabilirsin.\r\n\r\nİyi eğlenceler!', 'duzcesozluk', '06-11-2021 23:18', '2021-11-06 23:18:21', 0, 0, 0, 'Genel'),
(26, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'sadada', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '10-12-2021 18:13', '2021-12-10 18:13:05', 0, 0, 0, 'Genel');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `likedislike`
--

DROP TABLE IF EXISTS `likedislike`;
CREATE TABLE IF NOT EXISTS `likedislike` (
  `konuid` int(11) NOT NULL,
  `kullanici` varchar(60) NOT NULL,
  `likedislike` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `likedislike`
--

INSERT INTO `likedislike` (`konuid`, `kullanici`, `likedislike`) VALUES
(7, 'bunyamin', 'dislike'),
(7, 'Keorsa', 'like'),
(15, 'Keorsa', 'like'),
(2, 'Keorsa', 'like'),
(11, 'Keorsa', 'dislike'),
(5, 'Keorsa', 'dislike'),
(19, 'Keorsa', 'like'),
(6, 'Keorsa', 'like'),
(8, 'Keorsa', 'dislike'),
(6, 'Theresa', 'like'),
(15, 'duduklutencere', 'dislike'),
(6, 'duduklutencere', 'like'),
(7, 'duduklutencere', 'dislike'),
(5, 'duduklutencere', 'like'),
(10, 'duduklutencere', 'dislike'),
(13, 'duduklutencere', 'dislike'),
(15, 'bunyamin', 'like'),
(6, 'fakeElwind', 'like'),
(5, 'Theresa', 'dislike'),
(2, 'Theresa', 'dislike'),
(11, 'Theresa', 'dislike'),
(15, 'Theresa', 'like'),
(19, 'Theresa', 'like'),
(7, 'Theresa', 'dislike'),
(10, 'Theresa', 'like'),
(7, 'poseidoN35', 'dislike'),
(28, 'Theresa', 'dislike'),
(5, 'bunyamin', 'like');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mesajlar`
--

DROP TABLE IF EXISTS `mesajlar`;
CREATE TABLE IF NOT EXISTS `mesajlar` (
  `id` varchar(100) NOT NULL,
  `mesaj` text NOT NULL,
  `konu` varchar(200) NOT NULL,
  `user` varchar(30) NOT NULL,
  `tarih` varchar(30) NOT NULL,
  `tarihbilgisi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mesajid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`mesajid`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `mesajlar`
--

INSERT INTO `mesajlar` (`id`, `mesaj`, `konu`, `user`, `tarih`, `tarihbilgisi`, `mesajid`) VALUES
('10', 'tekrar hoş geldin serdar abi az şerefsiz değildin iyi ki yolu bulmuşsun sjsjsj', 'hatayli-serdar-geri-dondu', 'Theresa', '04-12-2021 15:36', '2021-12-04 12:36:57', 2),
('11', 'orangutan mahmut vs zebra salih', 'mahmut-vs-salih', 'Theresa', '04-12-2021 15:41', '2021-12-04 12:41:29', 3),
('6', 'eğer gerçekten seviyorsa mesafeler anlamsızdır', 'gozden-irak-olan-gonulden-de-irak-midir', 'Theresa', '04-12-2021 15:44', '2021-12-04 12:44:39', 4),
('15', '+1', 'burger-yesek', 'Theresa', '04-12-2021 18:36', '2021-12-04 15:36:24', 6),
('7', 'hala böyle şeylerle mi uğraşıyorsun xkraltr', 'naruto-vs-sasuke', 'Theresa', '04-12-2021 18:51', '2021-12-04 15:51:43', 7),
('7', 'seni esefle kınıyorum bu arada', 'naruto-vs-sasuke', 'Theresa', '04-12-2021 18:52', '2021-12-04 15:52:12', 8),
('12', 'xkraltr yine bildiğimiz gibi', 'hayatim-boyle-10-numara', 'Theresa', '04-12-2021 18:55', '2021-12-04 15:55:03', 9),
('5', 'bu ne', 'javada-diziler', 'Theresa', '04-12-2021 18:56', '2021-12-04 15:56:00', 10),
('19', 'ah be charlotte :(', 'tilkinin-donup-dolasip-gelecegi-yer-kurkcu-dukkanidir', 'Theresa', '10-12-2021 00:04', '2021-12-09 21:04:53', 18),
('5', ':O', 'javada-diziler', 'Theresa', '04-12-2021 18:57', '2021-12-04 15:57:35', 12),
('11', 'bak şimdi karar veremedim işte.', 'mahmut-vs-salih', 'bunyamin', '07-12-2021 01:08', '2021-12-06 22:08:30', 14),
('5', 'yakışmadı düdüklü tencere', 'javada-diziler', 'bunyamin', '07-12-2021 01:18', '2021-12-06 22:18:03', 15),
('17', 'hiç hoş değil ...', 'yuru-bre-ehli-deve-endamini-goreyim', 'bunyamin', '07-12-2021 01:26', '2021-12-06 22:26:35', 16),
('7', 'tartış benimle xkraltr', 'naruto-vs-sasuke', 'poseidoN35', '10-12-2021 22:12', '2021-12-10 19:12:34', 27),
('7', 'seni yağlı güreşe davet ediyorum xkraltr', 'naruto-vs-sasuke', 'poseidoN35', '10-12-2021 22:16', '2021-12-10 19:16:40', 28),
('7', 'kafes dövüşü de olur', 'naruto-vs-sasuke', 'poseidoN35', '10-12-2021 22:17', '2021-12-10 19:17:20', 29);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `moderatorloglari`
--

DROP TABLE IF EXISTS `moderatorloglari`;
CREATE TABLE IF NOT EXISTS `moderatorloglari` (
  `islem` text NOT NULL,
  `tarih` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `moderatorloglari`
--

INSERT INTO `moderatorloglari` (`islem`, `tarih`) VALUES
('Theresa adlı moderatör xkraltr adlı kullanıcıyı banladı.', '09-12-2021 21:28'),
('Theresa adlı moderatör xkraltr adlı kullanıcının banını kaldırdı.', '09-12-2021 21:28'),
('Keorsa adlı moderatör xkraltr adlı kullanıcıyı banladı.', '09-12-2021 23:11'),
('Keorsa adlı moderatör xkraltr adlı kullanıcının banını kaldırdı.', '09-12-2021 23:11'),
('Theresa adlı moderatör 8 numaralı konuyu sildi.', '10-12-2021 18:59'),
('Theresa adlı moderatör 28 numaralı konuyu sildi.', '10-12-2021 22:38'),
('Theresa adlı moderatör Batuhan Ko�  adlı kullanıcıyı banladı.', '10-12-2021 22:41'),
('Theresa adlı moderatör charlotte Jeanne adlı kullanıcıyı banladı.', '10-12-2021 22:41'),
('Theresa adlı moderatör AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA adlı kullanıcıyı banladı.', '10-12-2021 22:42'),
('Theresa adlı moderatör ChaosGod adlı kullanıcıyı banladı.', '12-12-2021 01:25'),
('Theresa adlı moderatör ChaosGod adlı kullanıcının banını kaldırdı.', '12-12-2021 01:25');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `profilloglari`
--

DROP TABLE IF EXISTS `profilloglari`;
CREATE TABLE IF NOT EXISTS `profilloglari` (
  `islem` text NOT NULL,
  `tarih` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uyeler`
--

DROP TABLE IF EXISTS `uyeler`;
CREATE TABLE IF NOT EXISTS `uyeler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(40) NOT NULL,
  `kullaniciadi` varchar(30) NOT NULL,
  `sifre` varchar(30) NOT NULL,
  `rutbe` varchar(9) NOT NULL DEFAULT 'uye',
  `bandurumu` tinyint(1) NOT NULL,
  `pp` text,
  `hakkinda` text NOT NULL,
  `kayitOlmaTarihi` varchar(30) NOT NULL,
  `sonGorulmeTarihi` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kullaniciadi` (`kullaniciadi`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `uyeler`
--

INSERT INTO `uyeler` (`id`, `mail`, `kullaniciadi`, `sifre`, `rutbe`, `bandurumu`, `pp`, `hakkinda`, `kayitOlmaTarihi`, `sonGorulmeTarihi`) VALUES
(1, 'bunyaminkardes@outlook.com', 'bunyamin', '0102redpenciL', 'admin', 0, 'kullanicipp/IMG_20211206_225042.jpg', 'YKTOYKBOEUXAS', '03-12-2021 17:18', '16-12-2021 22:54'),
(5, 'Mail@gmail.com', 'ChaosGod', 'Sifre123', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(6, 'awqe@gmail.com', 'sk53', '12345678Aa', 'admin', 0, 'kullanicipp/F5B20025-CFEF-42AE-BDEA-1301BE46EC60.png', 'Founder of dusozluk', '01-12-2021 21:33', '07-12-2021 17:55'),
(14, 'dogukankor@gmail.com', 'dogukan_kor', '12345678Aa', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(15, 'fabyokul@gmail.com', 'Fabyo', 'Begovic123', 'admin', 0, 'kullanicipp/3C143084-978E-4C84-A0BA-58CC535B9F3D.jpeg', 'dusozluk.com kurucu üyesi', '01-12-2021 21:33', '07-12-2021 17:55'),
(22, 'deneme@gmail.com', 'xkraltr', '0102redpenciL', 'uye', 0, 'kullanicipp/boruto-5-ways-sasuke-losing-his-rinnegan-makes-sense.jpg', 'sasuke fan', '01-12-2021 21:33', '07-12-2021 17:55'),
(23, 'poseidoN42@gmail.com', 'poseidoN35', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '10-12-2021 22:18'),
(24, 'burakkiliciletisim@gmail.com', 'burakkilic', 'B216812b', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(38, 'bosislermuduru@gmail.com', 'bosislermuduru', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(27, 'batukoc8181@gmail.com', 'Batuhan Koç ', 'Batu14768', 'uye', 1, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(31, 'theresaastur@gmail.com', 'Theresa', '0102redpenciL', 'moderator', 0, 'kullanicipp/Ekran Alıntısı.PNG', 'davulun zarı sağlam ama tokmağın değmediği yer de kalmamış.', '01-12-2021 21:33', '14-12-2021 18:07'),
(32, 'duduklutencere@gmail.com', 'duduklutencere', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '12-12-2021 23:45'),
(33, 'tonbaliklimakarna@gmail.com', 'tonbaliklimakarna', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(34, 'mahmud_69@gmail.com', '69Lu', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(37, 'gamerhakan@gmail.com', 'GAMERHAKAN', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(39, 'keorsaxrisimal@gmail.com', 'Keorsa', '0102redpenciL', 'moderator', 0, 'kullanicipp/WhatsApp Image 2021-12-06 at 22.23.57.jpeg', '~~ mocha kahperosso ~~       A.K.A   FİLİTRE KAHPE\r\n\r\n-> pussy hunter\r\n-> kemankeş\r\n-> bodybuilder\r\n-> melike\'nin biricik aşkı <3\r\n\r\n#stopracism', '01-12-2021 21:33', '10-12-2021 16:41'),
(36, 'hatayliserdar@gmail.com', 'hatayli_serdar', '0102redpenciL', 'uye', 0, 'kullanicipp/serdar.PNG', 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '07-12-2021 17:55'),
(40, 'fakeelwind@gmail.com', 'fakeElwind', '0102redpenciL', 'uye', 0, 'kullanicipp/elwind.PNG', 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '01-12-2021 21:33', '10-12-2021 18:56'),
(44, 'charlotte@gmail.com', 'charlotte Jeanne', '0102redpenciL', 'uye', 1, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '09-12-2021 01:33', '09-12-2021 01:48'),
(51, 'denemedeneme123@gmail.com', 'deneme_kullanici4', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '12-12-2021 01:26', '12-12-2021 01:26'),
(52, 'deneme_kullanici@gmail.com', 'deneme_kullanici', '0102redpenciL', 'uye', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '16-12-2021 20:21', '16-12-2021 22:54'),
(48, 'duzcesozluk@gmail.com', 'duzcesozluk', '0102redpenciL', 'moderator', 0, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '06-11-2021 23:17', '06-11-2021 23:51'),
(49, 'denemedeneme12@gmail.com', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '0102redpenciL', 'uye', 1, NULL, 'Bu kullanıcı kendisi hakkında bir şey belirtmemiş.', '10-12-2021 18:11', '10-12-2021 18:14');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ziyaretciler`
--

DROP TABLE IF EXISTS `ziyaretciler`;
CREATE TABLE IF NOT EXISTS `ziyaretciler` (
  `ipadresi` varchar(50) NOT NULL,
  `tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `ipadresi` (`ipadresi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
