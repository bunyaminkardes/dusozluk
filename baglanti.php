<?php

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

?>