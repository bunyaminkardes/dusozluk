<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Kayıt ol</title>
</head>
<body>
	<input readonly autofocus class="focus"> <!-- focus -->
	<div class="giris">
		<div class="giris-div">
			<h3 id="giris-yazilar">kayıt ol</h3>	
			<form method="POST" action="kayitol.php">
				<h5 id="giris-yazilar-1">e-mail adresi</h5>
				<input id="mail" type="" name="mail"  maxlength="100" placeholder="Mail adresinizi giriniz" required>
				<h5 id="giris-yazilar-1">kullanıcı adı</h5>
				<input type="textbox" name="kullaniciadikayit" placeholder="Kullanıcı adınızı giriniz" id="kullaniciadi" maxlength="30" required>
				<h5 id="giris-yazilar-1">şifre</h5>
				<input type="password" name="sifrekayit" placeholder="Şifrenizi giriniz" id="sifre" maxlength="30" required>
				<h5 id="giris-yazilar-1">şifrenizi tekrar giriniz</h5>
				<input type="password" name="sifrekayit2" placeholder="Şifrenizi tekrar giriniz" id="sifre" maxlength="30" required>
				<h5 id="giris-yazilar-1">Şifre :<br/>Minimum 8 karakterden oluşmalı<br/>1 adet büyük harf<br/>1 adet küçük harf<br/>1 adet rakam içermeli<br/>
				<!--<div id="sozlesme"><input type="checkbox"><a href="#">sözleşmeyi okudum ve kabul ediyorum.</a></div>-->
				<input type="submit" value="Kayıt ol" id="buton">
				<div class="hatadiv">
					<h3 id="hatamesaj">Kayıt başarısız.</h3>
					<h3 id="dogrulamamesaji">Kayıt başarılı, lütfen bekleyin.</h3>
					<h3 id="hatamesaj2">Mail veya Kullanıcı adı zaten alınmış.</h3>
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
						if (isset($_SESSION['girisyapankullanici'])) //oturum açıksa bu sayfaya girilemesin.
						{
							echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
						}
						if (isset($_POST['kullaniciadikayit']) && isset($_POST['sifrekayit']) && isset($_POST['mail']) && isset($_POST['sifrekayit2'])) // kullanıcı verileri post ettiyse kayıt işlemleri başlasın.
						{
							$sorgu=$baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi OR mail = :mail");
							$sorgu->bindParam(':kullaniciadi',$kayitkullaniciadi);
							$sorgu->bindParam(':mail',$kayitmailbilgisi);
							$sorgu->fetch(PDO::FETCH_ASSOC);
							$sorgu->execute();
							if ($sorgu->rowCount()>0) // kullanıcı kayıt olurken zaten kayıtlı mail veya kullanıcı adı bilgisi girerse veritabanından kontrol edip hata verdirttiriyoruz.
							{
								?>
								<script type="text/javascript">document.getElementById("hatamesaj2").style.display="block";</script>
							<?php
							}
							if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)+[0-9a-zA-Z][a-zA-Z0-9]{7,}$/', $kayitsifre) || !filter_var($kayitmailbilgisi, FILTER_VALIDATE_EMAIL)) // kriterlere uygun olmayan giriş yapıldıysa hata mesajı verdirelim.
							{
								?>
								<script type="text/javascript">document.getElementById("hatamesaj").style.display="block";</script>
								<?php
							}
							else // herhangi bir sıkıntı yoksa kayıt işlemleri tamamlansın.
							{
								if($kayitsifre == $onaysifre)
								{
									$komut = $baglanti->prepare("INSERT INTO uyeler(kullaniciadi,mail,sifre,bandurumu,pp,hakkinda,kayitOlmaTarihi,sonGorulmeTarihi) VALUES (:kayitkullaniciadi,:kayitmailbilgisi,:kayitsifre,:bandurumu,:pp,:hakkinda,:kayitOlmaTarihi,:sonGorulmeTarihi)");
									$komut->bindParam(':kayitkullaniciadi',$kayitkullaniciadi);
									$komut->bindParam(':kayitmailbilgisi',$kayitmailbilgisi);
									$komut->bindParam(':kayitsifre',$kayitsifre);
									$komut->bindParam(':bandurumu',$bandurumu);
									$komut->bindParam(':pp',$pp);
									$komut->bindParam(':hakkinda',$hakkinda);
									$komut->bindParam(':kayitOlmaTarihi',$kayitTarihi);
									$komut->bindParam(':sonGorulmeTarihi',$kayitTarihi);
									$komut->execute();
									if($komut->rowCount()>0) // rowCount 0'dan büyükse veriler veritabanına eklendi demektir. sıkıntı yok.
									{
										?>
										<script type="text/javascript">document.getElementById("dogrulamamesaji").style.display="block";</script>
										<?php
										echo "<script>setTimeout(function(){window.location.href='index.php';}, 1500);</script>";
									}
									else // rowCount 0 ise veriler veritabanına eklenmedi demektir.
									{
										echo "Kayıt sırasında beklenmeyen bir hata oluştu.";
									}
								}
								else
								{
									echo "girdiğiniz şifreler uyuşmuyor";
								}

							}	
						}
					?>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
