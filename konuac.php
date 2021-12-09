<?php
	require_once("kutuphane.php");
	$kullanici = girisyapankullanici();
	
	if(!isset($_SESSION['girisyapankullanici'])) // giriş yapmayan konu da açamasın.
	{
		header("Refresh: 2; url=index.php");
		echo "Konu açabilmek için ilk önce giriş yapmanız lazım."."<br/>"."Anasayfaya yönlendiriliyorsunuz, lütfen bekleyin...";
		exit();
	}
	else if($kullanici['bandurumu']==1) // yasaklı kullanıcılar da konu açamasın.
	{
		header("Refresh: 2; url=index.php");
		echo "Yasaklı kullanıcı olduğunuz için konu açamazsınız."."<br/>"."Anasayfaya yönlendiriliyorsunuz, lütfen bekleyin...";
		exit();
	}

	$content = 'contents/konuac-content.php';
	require_once("masterpage.php");
?>