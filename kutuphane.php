<?php
@session_start();

try
{
	$connectionstring = 'mysql:host=localhost;dbname=dusozluk_bunyaminkardes; charset=utf8';
	$kullaniciadi = 'root';
	$sifre = "";
	$baglanti = new PDO($connectionstring,$kullaniciadi,$sifre);
	$baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo "VERİTABANI BAĞLANTI HATASI!!! : ". $e->getMessage();
}

function girisyapankullanici()  // bu fonksiyon giriş yapan kullanıcıya ait kullanıcı bilgilerini bir dizi olarak return eder.
{
	$baglanti = new PDO("mysql:host=localhost;dbname=dusozluk_bunyaminkardes","root","");
	$kullaniciadi = @$_SESSION['girisyapankullanici'];
	$sorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
	$sorgu->bindParam(":kullaniciadi",$kullaniciadi,PDO::PARAM_STR);
	$sorgu->execute();
	$row = $sorgu->fetch();
	return $row;
}
function kullanicilar() // bu fonksiyon veritabanındaki tüm kullanıcıları bir dizi olarak return eder.
{
	$baglanti = new PDO("mysql:host=localhost;dbname=dusozluk_bunyaminkardes","root","");
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
 