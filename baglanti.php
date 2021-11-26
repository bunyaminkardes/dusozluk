<?php

try
{
	$baglanti = new PDO("mysql:host=localhost;dbname=dusozluk_bunyaminkardes","root","");
}
catch(PDOException $e)
{
	echo "VERİTABANI BAĞLANTI HATASI!!! : ". $e->getMessage();
}

?>