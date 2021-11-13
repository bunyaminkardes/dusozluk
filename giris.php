<?php
	session_start();

	if (isset($_SESSION['girisyapankullanici'])) // giriş yapan kullanıcı varsa bu sayfaya giremesin.
	{
		echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
	}

	$content = 'contents/giris-content.php';
	@include("masterpage.php"); 
?>