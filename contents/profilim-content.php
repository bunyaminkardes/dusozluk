<?php 

$kullanici = girisyapankullanici(); //giriş yapan kullanıcı bilgisini alıyoruz.
$profilKimeAit = $_GET['kullanici']; // profilin kime ait olduğunun bilgisini alıyoruz.
$hakkinda = $_POST['hakkinda'];
$kullanicibilgisi = $_GET['kullanici'];
$konum = 'kullanicipp/';
$geciciKonum = $_FILES["profilfoto"]["tmp_name"];
$isim = $_FILES["profilfoto"]["name"];
$dosyaKonumu = $konum;
$finalpp = $konum.$isim;
$token = token_uret();
$postEdilenToken = $_POST['PROFILIM_TOKEN'];
$guncelSifre = $_POST['guncelSifre'];
$yeniSifre = $_POST['yeniSifre'];
$fotoKaldir = NULL;

if(!isset($_SESSION['girisyapankullanici'])) // giriş yapmamış kullanıcılar profilim sayfasına giremeyecek.
{
	echo "<script type='text/javascript'> document.location = '404.php'; </script>";
	exit();
}
else
{
	if($kullanici['kullaniciadi'] != $profilKimeAit ) // giriş yapmış olanlar da ancak kendi profiline girebilecek.
	{
		echo "<script type='text/javascript'> document.location = '404.php'; </script>";
		exit();
	}
	else if ($kullanici['kullaniciadi'] == $profilKimeAit)
	{
		require_once("profil-content.php");
	}
}

?>
<div class="profilim-ozellestir-kapsayici">
	<h2 id="profilim-ozellestir-baslik">Profili Özelleştir</h2>
	<form method="POST" action="" enctype="multipart/form-data" id="profilim-ozellestir-form">
		<textarea maxlength="1500" placeholder="Hakkında (maksimum 1500 karakter)" name="hakkinda" id="profilim-ozellestir-textarea"></textarea>
		<div id="profilim-ozellestir-ppdegistir">
			profil resmini değiştir : <input type="file" name="profilfoto"><br><br>
		</div>
		<div id="profilim-ozellestir-ppdegistir-butonkapsayici">
			<input type="hidden" name="PROFILIM_TOKEN" value="<?php echo $token; ?>">
			<input id="profilim-ozellestir-ppdegistir-butonkapsayici-buton" type="submit" name="PROFILI_OZELLESTIR_SUBMIT" value="değiştir">
			profil resmini kaldır : <input type="submit" name="FOTO_KALDIR_SUBMIT" value=" Kaldırmak için tıkla "><br><br>
		</div>
		<div id="profilim-sifre-degistir-kapsayici">
			<h3 id="profilim-sifre-degistir-baslik">Şifreyi Değiştir :</h3>
			<input class="profilim-sifre-degistir-input-alanlari" type="password" name="guncelSifre" placeholder=" güncel şifrenizi giriniz">
			<input class="profilim-sifre-degistir-input-alanlari" type="password" name="yeniSifre" placeholder=" yeni şifrenizi giriniz">
			<input id="profilim-sifre-degistir-buton" type="submit" name="SIFREYI_DEGISTIR_SUBMIT" value=" Şifreyi Değiştir ">
		</div>
	</form>
</div>



<?php
	if(isset($_POST['PROFILI_OZELLESTIR_SUBMIT'])) // butona basılırsa
	{
		if(hash_equals($token,$postEdilenToken)==false) // csrf kontrolü geçilemezse
		{
			echo "<h3>HATA</h3>";
		}
		else // sorun yoksa işleme geç
		{
			if(isset($_FILES["profilfoto"])) // profil fotoğrafı değiştirilmek istenirse
			{
				if(move_uploaded_file($geciciKonum, "$dosyaKonumu/$isim"))
				{
					$profilguncelleme = $baglanti->prepare("UPDATE uyeler SET pp=:pp WHERE kullaniciadi= :username");			
					$profilguncelleme->bindParam(":pp",$finalpp,PDO::PARAM_STR);
					$profilguncelleme->bindParam(":username",$kullanicibilgisi,PDO::PARAM_STR);
					$profilguncelleme->execute();
					if($profilguncelleme->rowCount()>0)
					{
						echo "<script> window.location.reload(); </script>";
					}
				}
			}
			if(isset($hakkinda) && !empty($hakkinda)) // kullanıcı sadece hakkındayı güncellemek isterse burası çalışacak. hakkında boş olmamalı çünkü her türlü formdan hakkında post edilecek butona basıldığında. boş gelirse güncelleme yapmasın, boşu boşuna hakkında silinir sonra.
			{
				$profilguncelleme = $baglanti->prepare("UPDATE uyeler SET hakkinda=:hakkinda  WHERE kullaniciadi=:username");
				$profilguncelleme->bindParam(":username",$kullanicibilgisi,PDO::PARAM_STR);
				$profilguncelleme->bindParam(":hakkinda",$hakkinda,PDO::PARAM_STR);
				$profilguncelleme->execute();
				if($profilguncelleme->rowCount()>0)
				{
					echo "<script> window.location.reload(); </script>";
				}
			}
		}
	}
	else if(isset($_POST['SIFREYI_DEGISTIR_SUBMIT']))
	{
		if(isset($guncelSifre) && isset($yeniSifre))
		{
			if($guncelSifre != $kullanici['sifre'])
			{
				echo "<h3 id='hatamesaj'>Güncel şifrenizi hatalı girdiniz.</h3>";
			}
			else
			{
				if(preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)+[0-9a-zA-Z][a-zA-Z0-9]{7,}$/', $yeniSifre)==false)
				{
					echo "<h3 id='hatamesaj'>Şifre kriterlere uymuyor.</h3>";
				}
				else
				{
					$sifreGuncellemeSorgusu = $baglanti->prepare("UPDATE uyeler SET sifre = :sifre WHERE kullaniciadi = :kullaniciadi");
					$sifreGuncellemeSorgusu->bindParam(":sifre",$yeniSifre);
					$sifreGuncellemeSorgusu->bindParam(":kullaniciadi",$kullanici['kullaniciadi']);
					$sifreGuncellemeSorgusu->execute();
					if($sifreGuncellemeSorgusu->rowCount()>0)
					{
						echo "<script> window.location = window.location.href; </script>";
					}
				}
			}		
		}
	}
	else if(isset($_POST['FOTO_KALDIR_SUBMIT']))
	{
		$fotoKaldirmaSorgusu = $baglanti->prepare("UPDATE uyeler SET pp = :pp WHERE kullaniciadi = :kullaniciadi");
		$fotoKaldirmaSorgusu->bindParam(":pp",$fotoKaldir);
		$fotoKaldirmaSorgusu->bindParam(":kullaniciadi", $kullanici['kullaniciadi']);
		$fotoKaldirmaSorgusu->execute();
		if($fotoKaldirmaSorgusu->rowCount()>0)
		{
			echo "<script> window.location.reload(); </script>";
		}
	}
?>