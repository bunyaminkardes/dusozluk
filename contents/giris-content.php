<?php
	$kulladi = $_POST['kullaniciadi'];
	$sif = $_POST['sifre'];
	$sayac = 0;
	$_SESSION['girisyapankullanici'];
	$_SESSION['kullanicibandurumu'];
	date_default_timezone_set('Europe/Istanbul');
	$tarih = date("d-m-Y H:i");
	$token = token_uret();
	$postEdilenToken = $_POST['GIRIS_TOKEN'];
	$likeDislikeHataMesaji = $_GET['q'];
	$profilHataMesaji = $_GET['pq'];
?>


<input readonly autofocus class="focus"> <!-- focus -->
<div class="giris">
	<div class="giris-div">
		<?php
		if(isset($likeDislikeHataMesaji) && $likeDislikeHataMesaji == 0)
		{
			echo "<h3 id='hatamesaj'>Like veya Dislike atabilmek için ilk önce giriş yapmanız lazım.</h3>";
		}
		if(isset($profilHataMesaji) && $profilHataMesaji == 0)
		{
			echo "<h3 id='hatamesaj'>Profil görüntüleyebilmek için ilk önce giriş yapmanız lazım.</h3>";
		}
		?>
		<h3 id="giris-yazilar">Giriş Yap</h3>
		<h5 id="giris-yazilar-1">Kullanıcı adı</h5>
		<form method="POST" action="" autocomplete="off">
			<input type="textbox" name="kullaniciadi" placeholder="Lütfen kullanıcı adınızı giriniz." id="kullaniciadi" required>
			<h5 id="giris-yazilar-1">Şifre</h5>
			<input type="password" name="sifre" placeholder="Lütfen şifrenizi giriniz." id="sifre" required>
			<input type="hidden" name="GIRIS_TOKEN" value="<?php echo $token; ?>">
			<input type="submit" name="GIRIS_SUBMIT" value="giriş yap" id="giris-buton">
		</form>
		<a href="sifreyisifirla.php" id="giris-sifremi-unuttum">Şifremi Unuttum</a>
	</div>
</div>



<?php
if(isset($_POST['GIRIS_SUBMIT'])) // giriş yap butonuna tıklandıysa kontrollere başlayalım.
{
	if(hash_equals($token,$postEdilenToken)==false)
	{
		echo "<h3 id='hatamesaj'>HATA</h3>";
	}
	else
	{
		if(isset($kulladi) && isset($sif)) // giriş yapacak kişi gerekli yerleri doldurduysa giriş işlemlerine başlayalım.
		{
			$sorgu = $baglanti->prepare("SELECT * FROM uyeler");
			$sorgu->fetch(PDO::FETCH_ASSOC);
			$sorgu->execute();
			if ($sorgu->rowCount()>0)
			{
				foreach($sorgu as $row)
				{
					if ($kulladi == $row['kullaniciadi'] && $sif == $row['sifre'])  // uyeler tablosundaki her satıra bakılacak, kullanıcının yolladığı kullanıcı adı ve şifre ile uyuşan satır varsa sayaç 1 olacak, giriş başarılı demek bu.
					{
						$sayac++;
						break;
					}
				}
				if ($sayac==0) // sayacın değeri artmadıysa giriş başarısız demektir, hata mesajı verdirelim.
				{
					echo "<h3 id='hatamesaj'>Kullanıcı adı veya şifre hatalı.</h3>";
				}
				else if ($sayac == 1) // sayaç 1 ise giriş işlemlerini yapıyoruz ve kullanıcıyı anasayfaya yönlendiriyoruz.
				{
					$_SESSION['girisyapankullanici'] = $kulladi;
					$_SESSION['kullanicibandurumu'] = $row['bandurumu'];

					$songorulmesorgusu = $baglanti->prepare("UPDATE uyeler SET sonGorulmeTarihi = :sonGorulmeTarihi WHERE kullaniciadi = :kullaniciadi");
					$songorulmesorgusu->bindParam(":sonGorulmeTarihi",$tarih,PDO::PARAM_STR);
					$songorulmesorgusu->bindParam(":kullaniciadi",$_SESSION['girisyapankullanici'],PDO::PARAM_STR);
					$songorulmesorgusu->execute();

					$islem = $_SESSION['girisyapankullanici']." "."adlı kullanıcı"." "."giriş yaptı.";
					$ipadresi = $_SERVER['REMOTE_ADDR'];
					$girislogsorgusu = $baglanti->prepare("INSERT INTO girisloglari(islem,fail,ipadresi,tarih) VALUES(:islem,:fail,:ipadresi,:tarih)");
					$girislogsorgusu->bindParam(":islem",$islem,PDO::PARAM_STR);
					$girislogsorgusu->bindParam(":fail",$kulladi,PDO::PARAM_STR);
					$girislogsorgusu->bindParam(":ipadresi",$ipadresi,PDO::PARAM_STR);
					$girislogsorgusu->bindParam(":tarih",$tarih,PDO::PARAM_STR);
					$girislogsorgusu->execute();
					echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
				}
			}
		}
		else
		{
			echo "<h3 id='hatamesaj'>Lütfen gerekli yerleri doldurunuz.</h3>";
		}
	}
}
?>
