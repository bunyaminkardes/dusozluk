<?php 
require_once("kutuphane.php");
$kullanici = girisyapankullanici();
if(!isset($_SESSION['girisyapankullanici'])) // giriş yapmayan kullanıcılar konu açma sayfasına giremesin.
{
	header("Refresh: 3; url=/giris.php");
	echo "Profil görüntüleyebilmek için ilk önce giriş yapmanız lazım"."<br/>"."Giriş yap ekranına yönlendiriliyorsunuz, lütfen bekleyin...";
	exit();
}
else if($kullanici['bandurumu']==1) // yasaklı kullanıcılar da konu açma sayfasına giremesin.
{
	header("Refresh: 3; url=/index.php");
	echo "Yasaklı kullanıcı olduğunuz için profil görüntüleyemezsiniz."."<br/>"."Anasayfaya yönlendiriliyorsunuz, lütfen bekleyin...";
	exit();
}
$content = 'contents/profil-content.php';
@require_once("masterpage.php");
?>