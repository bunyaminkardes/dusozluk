<?php
@session_start();

define('CONNECTIONSTRING', 'mysql:host=localhost;dbname=dusozlu1_dusozluk; charset=utf8');
define('USERNAME','root');
define('PASSWORD','');

try
{
	$baglanti = new PDO(CONNECTIONSTRING,USERNAME,PASSWORD);
	$baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo "VERİTABANI BAĞLANTI HATASI!!! : ". $e->getMessage();
}

function baglan()
{
	try
	{
		$baglanti = new PDO(CONNECTIONSTRING,USERNAME,PASSWORD);
		$baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $baglanti;
	}
	catch(PDOException $e)
	{
		echo "VERİTABANI BAĞLANTI HATASI!!! : ". $e->getMessage();
	}
}

function girisyapankullanici()  // bu fonksiyon giriş yapan kullanıcıya ait kullanıcı bilgilerini bir dizi olarak return eder.
{
	$baglanti = baglan();
	$kullaniciadi = @$_SESSION['girisyapankullanici'];
	$sorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
	$sorgu->bindParam(":kullaniciadi",$kullaniciadi,PDO::PARAM_STR);
	$sorgu->execute();
	$row = $sorgu->fetch();
	return $row;
}
function kullanicilar() // bu fonksiyon veritabanındaki tüm kullanıcıları bir dizi olarak return eder.
{
	$baglanti = baglan();
	$sorgu = $baglanti->prepare("SELECT * FROM uyeler");
	$sorgu->execute();
	$row = $sorgu->fetchAll();
	return $row;
}
function seo_link($s) // bu fonksiyon parametre olarak gelen linki seo dostu link olarak geri return eder.
{
	$tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',' ',',','?');
	$eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','',''); 
	$s = str_replace($tr,$eng,$s);
	$s = strtolower($s);
	$s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
	$s = preg_replace('/\s+/', '-', $s);
	$s = preg_replace('|-+|', '-', $s);
	$s = preg_replace('/#/', '', $s);
	$s = str_replace('.', '', $s);
	$s = trim($s, '-');
	return $s;
}

function token_uret() // CSRF saldırılarını önlemek için token üretme fonksiyonu.
{
	if(empty($_SESSION['token']))
	{
		$_SESSION['token'] = bin2hex(random_bytes(32)); //PHP 7+
	}
	$token = $_SESSION['token'];
	return $token;
}

?>
 