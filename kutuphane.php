<?php
session_start();

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
	$sorgu->bindParam(":kullaniciadi",$kullaniciadi);
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
function seo_link($s) // s isimli bir parametre gelecek. yani düzeltilmesi istenen URL
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

function ters_seo_link($s) // kullanıcıların profillerinde en son 10 yorumları gözüküyor, ancak veritabanına seo linkli halleri kaydedilmiş, estetik dursun diye tekrar işlem yapalım.
{
	$degistirilecek = '-';
	$sunadegistir = " ";
	$s = str_replace($degistirilecek, $sunadegistir, $s);
	return $s;
}

?>
 