<?php

session_start();



function girisyapankullanici()  // bu fonksiyon giriş yapan kullanıcıya ait kullanıcı bilgilerini bir dizi olarak return eder.
{
	$baglanti = new PDO("mysql:host=localhost;dbname=id16174737_veritabanim","root","");
	//$baglanti = new PDO("mysql:host=localhost;dbname=dusozluk_bunyaminkardes","dusozluk_bunyamin","0102redpenciL");
	$kullaniciadi = @$_SESSION['girisyapankullanici'];
	$sorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
	$sorgu->bindParam(":kullaniciadi",$kullaniciadi);
	$sorgu->execute();
	$row = $sorgu->fetch();
	return $row;
}

function seo_link($s) // s isimli bir parametre gelecek. yani düzeltilmesi istenen URL
{
	$tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',' ',',','?'); // İstenmeyen Türkçe karakterler için bir dizi oluşturulmuş.
	$eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','',''); // Bu istenmeyen Türkçe karakterler bu İngilizce karakterlerle değiştirilecek.
	$s = str_replace($tr,$eng,$s);
	$s = strtolower($s);
	$s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
	$s = preg_replace('/\s+/', '-', $s);
	$s = preg_replace('|-+|', '-', $s);
	$s = preg_replace('/#/', '', $s);
	$s = str_replace('.', '', $s);
	$s = trim($s, '-');
	return $s; // Seo'ya uygun olarak düzeltilmiş url return ediliyor.
}

?>
 