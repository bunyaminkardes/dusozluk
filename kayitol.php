<?php
	session_start();
	if(isset($_SESSION['girisyapankullanici'])) // oturum açıksa tekrar kayıt ol sayfasına girilemesin.
	{
		header("Location: index.php");
		exit();
	}
	$content = 'contents/kayitol-content.php';
	require_once("masterpage.php");
?>