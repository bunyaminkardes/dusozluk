<?php 

	$kullanici = girisyapankullanici();
	$girisYapilmisMi = $_SESSION['girisyapankullanici'];
	if(!isset($girisYapilmisMi))
	{
		echo "Konu açabilmek için ilk önce giriş yapmanız lazım."."<br/>"."Anasayfaya yönlendiriliyorsunuz, lütfen bekleyin...";
		echo "<script> setTimeout(function() {window.location.href='index.php';}, 2500);</script>";
	}
	else if($kullanici['bandurumu']==1)
	{
		echo "Yasaklı kullanıcı olduğunuz için konu açamazsınız."."<br/>"."Anasayfaya yönlendiriliyorsunuz, lütfen bekleyin...";
		echo "<script> setTimeout(function() {window.location.href='index.php';}, 2500);</script>";
	}
	else
	{
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Konu aç</title>
		</head>
		<body>
		<div id="konuac">
		<form method="POST" action="">
			<h3 style="padding-top: 30px; text-align:center; font-size:23px; ">- konu aç -</h3>
			<input style="width:50%; height: 35px; display: block; position: relative; top:20px; margin: auto;" type="textbox" name="konu_baslikk" autofocus placeholder="konu başlığı (maksimum 60 karakter)" maxlength="60" required>
			<textarea style="width:70%; height: 100px; display: block; position: relative; top:40px; resize: none; margin: auto;" name="konu_icerikk" placeholder="konu içeriği (maksimum 6000 karakter)" maxlength="6000" required></textarea>
			<select name="konu_turu" style="display: block; position: relative; top: 60px; margin:0 auto;">
				<option value="Secilmemis">Lütfen Konu türünü seçiniz.</option>
  				<option value="Siyaset">Siyaset</option>
  				<option value="Ekonomi">Ekonomi</option>
  				<option value="Yasam">Yaşam</option>
  				<option value="Spor">Spor</option>
  				<option value="Muzik">Müzik</option>
  				<option value="Universite">Üniversite</option>
  				<option value="Anime">Anime</option>
  				<option value="Genel">Genel</option>
			</select>
			<input style="width:90px; height: 30px; display: block; position: relative; top:80px; margin: auto;" type="submit" value="konu aç">
			<br/><br/><br/><br/><br/><br/>
			<?php
				session_start();
				$kbaslik=$_POST['konu_baslikk'];
				$kicerik=$_POST['konu_icerikk'];
				$user=$_SESSION['girisyapankullanici'];
				$konu_turu = $_POST['konu_turu'];
				
				date_default_timezone_set('Europe/Istanbul');
				$tarih = date("d-m-Y H:i");


				if (isset($_POST['konu_baslikk']) && isset($_POST['konu_icerikk']) && isset($_POST['konu_turu']) ) // konu başlığı ve konu içeriği post edilmişse konu açılacak demektir, veritabanına insert edelim.
				{
					try 
					{
						$sqlkomut = $baglanti->prepare("INSERT INTO konular(konu_baslik,konu_icerik,user,tarih,konu_turu)  VALUES (:kbaslik,:kicerik,:user,:tarih,:konu_turu)");
						$sqlkomut->bindParam(':kbaslik',$kbaslik);
						$sqlkomut->bindParam(':kicerik',$kicerik);
						$sqlkomut->bindParam(':user',$user);
						$sqlkomut->bindParam(':tarih',$tarih);
						$sqlkomut->bindParam(':konu_turu',$konu_turu);
						$sqlkomut->execute();
						echo "Yeni konu başarıyla oluşturuldu.";
						echo "<script> setTimeout(function() {window.location.href='index.php';}, 1500);</script>";
					} 
					catch (Exception $e) 
					{
						echo "Hata, büyük ihtimalle bu isimde zaten bir konu bulunuyor.";
					}

				}
				else
				{
					//echo "hata";
				}
			?>
			</form>
		</div>
		</body>
		</html>
		<?php
	}

?>


