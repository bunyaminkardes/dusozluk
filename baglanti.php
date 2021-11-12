<?php

try
{
	//$baglanti = new PDO("mysql:host=localhost;dbname=dusozluk_bunyaminkardes","dusozluk_bunyamin","0102redpenciL");
	$baglanti = new PDO("mysql:host=localhost;dbname=id16174737_veritabanim","root","");
}
catch(PDOException $e)
{
	echo "VERİTABANI BAĞLANTI HATASI!!! : ". $e->getMessage();
}

?>