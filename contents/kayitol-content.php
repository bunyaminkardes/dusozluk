<?php
	$kayitmailbilgisi = $_POST['mail'];
	$kayitkullaniciadi = $_POST['kullaniciadikayit'];
	$kayitsifre = $_POST['sifrekayit'];
	$onaysifre = $_POST['sifrekayit2'];
	$bandurumu = 0;
	$hakkinda = "Bu kullanıcı kendisi hakkında bir şey belirtmemiş.";
	date_default_timezone_set('Europe/Istanbul');
	$kayitTarihi = date("d-m-Y H:i");
	$pp = NULL;
	$token = token_uret();
	$postEdilenToken = $_POST['KAYIT_OL_TOKEN'];
?>



<input readonly autofocus class="focus"> <!-- focus -->
<div class="giris">
	<div class="giris-div">
		<h3 id="giris-yazilar">kayıt ol</h3>
		<form method="POST" action="kayitol.php" autocomplete="off" style="padding-bottom:40px;">
			<h5 id="giris-yazilar-1">e-mail adresi</h5>
			<input id="mail" type="" name="mail"  maxlength="100" placeholder="Mail adresinizi giriniz" required>
			<h5 id="giris-yazilar-1">kullanıcı adı</h5>
			<input type="textbox" name="kullaniciadikayit" placeholder="Kullanıcı adınızı giriniz" id="kullaniciadi" maxlength="30" required>
			<h5 id="giris-yazilar-1">şifre</h5>
			<input type="password" name="sifrekayit" placeholder="Şifrenizi giriniz" id="sifre" maxlength="30" required>
			<h5 id="giris-yazilar-1">şifrenizi tekrar giriniz</h5>
			<input type="password" name="sifrekayit2" placeholder="Şifrenizi tekrar giriniz" id="sifre" maxlength="30" required>
			<h5 id="giris-yazilar-1">Kullanıcı adı : <br/>Türkçe karakter veya özel karakter içermemeli<br/><br/>Şifre :<br/>Türkçe karakter veya özel karakter içermemeli<br>Minimum 8 karakterden oluşmalı<br/>1 adet büyük harf<br/>1 adet küçük harf<br/>1 adet rakam içermeli<br/></h5>
			<input type="hidden" name="KAYIT_OL_TOKEN" value="<?php echo $token; ?>">
			<input type="submit" name="KAYIT_OL_SUBMIT" value="Kayıt ol" id="buton">
		</form>
	</div>
</div>


<?php
	if(isset($_POST['KAYIT_OL_SUBMIT'])) // kayıt ol butonuna basıldıysa kontrollere başlayalım.
	{
		if(hash_equals($token,$postEdilenToken)==false)
		{
			echo "<h3 id='hatamesaj'>HATA</h3>";
		}
		else
		{
			if (isset($kayitkullaniciadi) && isset($kayitsifre) && isset($onaysifre) && isset($kayitmailbilgisi)) // kayıt olacak kişi gerekli verileri post etti mi diye bakalım.
			{
				if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)+[0-9a-zA-Z][a-zA-Z0-9]{7,}$/', $kayitsifre)==false || !filter_var($kayitmailbilgisi, FILTER_VALIDATE_EMAIL)) // kriterlere uygun olmayan giriş yapıldıysa hata mesajı verdirelim.
				{
					echo "<h3 id='hatamesaj'>Kayıt başarısız.</h3>";
				}
				else if(ctype_alnum($kayitkullaniciadi)==false) // kullanıcı adı sadece harflerden ve numaralardan mı oluşuyor diye bakalım.
				{
					echo "<h3 id='hatamesaj'>Lütfen türkçe karakter veya özel karakter içermeyen bir kullanıcı adı seçin.</h3>";
				}
				else if($kayitsifre != $onaysifre)
				{
					echo "<h3 id='hatamesaj'>Girdiğiniz şifreler uyuşmuyor.</h3>";
				}
				else // herhangi bir sıkıntı yoksa kayıt işlemleri tamamlansın.
				{
					try
					{
						$komut = $baglanti->prepare("INSERT INTO uyeler(kullaniciadi,mail,sifre,bandurumu,pp,hakkinda,kayitOlmaTarihi,sonGorulmeTarihi) VALUES (:kayitkullaniciadi,:kayitmailbilgisi,:kayitsifre,:bandurumu,:pp,:hakkinda,:kayitOlmaTarihi,:sonGorulmeTarihi)");
						$komut->bindParam(':kayitkullaniciadi',$kayitkullaniciadi,PDO::PARAM_STR);
						$komut->bindParam(':kayitmailbilgisi',$kayitmailbilgisi,PDO::PARAM_STR);
						$komut->bindParam(':kayitsifre',$kayitsifre,PDO::PARAM_STR);
						$komut->bindParam(':bandurumu',$bandurumu,PDO::PARAM_INT);
						$komut->bindParam(':pp',$pp,PDO::PARAM_STR);
						$komut->bindParam(':hakkinda',$hakkinda,PDO::PARAM_STR);
						$komut->bindParam(':kayitOlmaTarihi',$kayitTarihi,PDO::PARAM_STR);
						$komut->bindParam(':sonGorulmeTarihi',$kayitTarihi,PDO::PARAM_STR);
						$komut->execute();
						if($komut->rowCount()>0)
						{
							$_SESSION['girisyapankullanici'] = $kayitkullaniciadi;
							echo "<h3 id='dogrulamamesaji'>Kayıt başarılı, lütfen bekleyin...</h3>";
							echo "<script> window.location.href='index.php'; </script>";
						}
					}
					catch (Exception $e)
					{
						echo "<h3 id='hatamesaj'>Kayıt sırasında beklenmeyen bir hata oluştu.</h3>";
					}
				}
			}
			else
			{
				echo "<h3 id='hatamesaj'>Lütfen tüm bilgileri eksiksiz girdiğinizden emin olunuz.</h3>";
			}
		}
	}
?>
