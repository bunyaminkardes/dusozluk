<?php
	session_start();
	if(isset($_SESSION['girisyapankullanici'])) // giriş yapan kullanıcı varsa tekrar giriş yapma sayfasına giremesin.
	{
		header("Location: index.php");
		exit();
	}
	$content = 'contents/giris-content.php';
	require_once("masterpage.php");
?>