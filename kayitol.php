<?php
	session_start();
	if(isset($_SESSION['girisyapankullanici'])) // oturum açıksa bu sayfaya girilemesin.
	{
		header("Location: index.php");
		exit();
	}
	$content = 'contents/kayitol-content.php';
	require_once("masterpage.php");
?>