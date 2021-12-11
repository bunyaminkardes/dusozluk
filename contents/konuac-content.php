<!DOCTYPE html>
<html>
	<head>
		<title>Konu aç</title>
	</head>
	<body>
		<div id="konuac">
			<form method="POST" action="">
				<h3 id="konuac-baslik">- konu aç -</h3>
				<input id="konuac-konubasligi" type="textbox" name="konu_baslikk" autofocus placeholder="konu başlığı (maksimum 60 karakter)" maxlength="60" required>
				<textarea id="konuac-konuicerigi" name="konu_icerikk" placeholder="konu içeriği (maksimum 6000 karakter)" maxlength="6000" required></textarea>
				<select id="konuac-konuturu" name="konu_turu">
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
				<input id="konuac-buton" type="submit" value="konu aç">
				<br/><br/><br/><br/><br/><br/>
				<?php

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
						$sqlkomut = $baglanti->prepare("INSERT INTO konular(konu_baslik,konu_icerik,user,tarih,konu_turu) VALUES (:kbaslik,:kicerik,:user,:tarih,:konu_turu)");
						$sqlkomut->bindParam(':kbaslik',$kbaslik,PDO::PARAM_STR);
						$sqlkomut->bindParam(':kicerik',$kicerik,PDO::PARAM_STR);
						$sqlkomut->bindParam(':user',$user,PDO::PARAM_STR);
						$sqlkomut->bindParam(':tarih',$tarih,PDO::PARAM_STR);
						$sqlkomut->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
						$sqlkomut->execute();
						echo "Yeni konu başarıyla oluşturuldu.";
						echo "<script> setTimeout(function() {window.location.href='index.php';}, 1500);</script>";
					} 
					catch (Exception $e) 
					{
						echo "Hata, büyük ihtimalle bu isimde zaten bir konu bulunuyor.";
					}
				}
				?>
			</form>
		</div>
	</body>
</html>

