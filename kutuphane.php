<?php

session_start();

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

function seo_link($s) // bu fonksiyon querystring halindeki url'yi seo uyumlu link halinde return eder.
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

?>
 