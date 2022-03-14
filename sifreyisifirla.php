<?php
	session_start();
	if(isset($_SESSION['girisyapankullanici']))
	{
		header("Location: index.php");
		exit();
	}
	$content = 'contents/sifreyisifirla-content.php';
	require_once("masterpage.php");
?>