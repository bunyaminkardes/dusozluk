<!DOCTYPE html>
<html>
<head>
	<title>Giriş yap</title>
</head>
<body>
	<input readonly autofocus class="focus"> <!-- focus -->
	<div class="giris">
		<div class="giris-div">
			<h3 id="giris-yazilar">Giriş Yap</h3>
			<h5 id="giris-yazilar-1">Kullanıcı adı</h5>
			<form method="POST" action="">
				<input type="textbox" name="kullaniciadi" placeholder="Lütfen kullanıcı adınızı giriniz." id="kullaniciadi" required>
				<h5 id="giris-yazilar-1">Şifre</h5>
				<input type="password" name="sifre" placeholder="Lütfen şifrenizi giriniz." id="sifre" required>
				<br/>
				<div class="hatadiv">
					<?php
					$kulladi = $_POST['kullaniciadi'];
					$sif = $_POST['sifre'];
					$sayac = 0;
					$sorgu = $baglanti->prepare("SELECT * FROM uyeler");
					$sorgu->fetch(PDO::FETCH_ASSOC);
					$sorgu->execute();
					if ($sorgu->rowCount()>0)
					{
						foreach($sorgu as $row)
						{
							if ($kulladi == $row['kullaniciadi'] && $sif == $row['sifre'])  //kullanıcı giriş yap butonuna bastığında post ile buraya veriler gelecek. bu veriler veritabanındaki verilerle eşleşiyorsa sayaç arttırılacak.
							{
								$sayac++; //eşleşen kayıt varsa sayaç 1 olucak, giriş başarılı demek bu.
								break;
							}
						}
						if ($sayac==0) //sayacı 0'dan başlatmıştık. eğer başarısız giriş olursa sayacın değerini 2 yapacağız. sayaç 2 ise giriş başarısız demek.
						{
							$sayac=$sayac+2;
						}
						if ($sayac == 1) //sayaç 1 olduğunda giriş işlemlerini yapıyoruz ve kullanıcıyı anasayfaya yönlendiriyoruz.
						{
							$_SESSION['girisyapankullanici'] = $kulladi;
							$_SESSION['kullanicibandurumu'] = $row['bandurumu'];
							$hosgeldin = "hoşgeldin";
							$hosgeldin1=$hosgeldin." ".$kulladi;
							$_SESSION['hosgeldiniz']=$hosgeldin1;
							@$kullanicibilgisi = $_SESSION['girisyapankullanici'];
							echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
						}
						else if (!$kulladi=="" && !$sif=="" && $sayac==2) //giriş başarısızsa hata mesajı gösterilsin.
						{
							?>
							<h3 id="hatamesaj">kullanıcı adı veya şifre hatalı.</h3>
							<script type="text/javascript">document.getElementById("hatamesaj").style.display="block";</script>
							<?php
						}		
					}		
					?>
					<input type="submit" value="giriş yap" id="buton">
				</div>
			</form>
		</div>
	</div>
</body>
</html>