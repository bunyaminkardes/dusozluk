<?php 

$kullanici = girisyapankullanici(); //giriş yapan kullanıcı bilgisini alıyoruz.
$profilKimeAit = $_GET['kullanici']; // profilin kime ait olduğunun bilgisini alıyoruz.

if(!isset($_SESSION['girisyapankullanici'])) // giriş yapmamış kullanıcılar profilim sayfasına giremeyecek.
{
	echo "<script type='text/javascript'> document.location = '404.php'; </script>";
}
else
{
	if($kullanici['kullaniciadi'] != $profilKimeAit ) // giriş yapmış olanlar da ancak kendi profiline girebilecek.
	{
		echo "<script type='text/javascript'> document.location = '404.php'; </script>";
	}
	else if ($kullanici['kullaniciadi'] == $profilKimeAit)
	{
		require_once("profil-content.php");
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="profilim-ozellestir-kapsayici">
		<h2 id="profilim-ozellestir-baslik">Profili Özelleştir</h2>
		<form method="POST" action="" enctype="multipart/form-data" id="profilim-ozellestir-form">
			<textarea maxlength="1500" placeholder="Hakkında (maksimum 1500 karakter)" name="hakkinda" id="profilim-ozellestir-textarea"></textarea>
			<div id="profilim-ozellestir-ppdegistir">
				profil resmini değiştir : <input type="file" name="profilfoto">
			</div>
			<div id="profilim-ozellestir-ppdegistir-butonkapsayici">
				<input id="profilim-ozellestir-ppdegistir-butonkapsayici-buton" type="submit" name="gonderke" value="değiştir">
			</div>
		</form>
	</div>
</body>
</html>

<?php 
	$hakkinda = $_POST['hakkinda'];
	$kullanicibilgisi = $_GET['kullanici'];

	if(isset($_FILES["profilfoto"]))
	{
		$konum = 'kullanicipp/';
		$geciciKonum = $_FILES["profilfoto"]["tmp_name"];
		$isim = $_FILES["profilfoto"]["name"];
		$dosyaKonumu = $konum;
		$finalpp = $konum.$isim;

		if(move_uploaded_file($geciciKonum, "$dosyaKonumu/$isim"))
		{
			$profilguncelleme = $baglanti->prepare("UPDATE uyeler SET pp=:pp WHERE kullaniciadi= :username");			
			$profilguncelleme->bindParam(":pp",$finalpp,PDO::PARAM_STR);
			$profilguncelleme->bindParam(":username",$kullanicibilgisi,PDO::PARAM_STR);
			$profilguncelleme->execute();
			echo "<script> setTimeout(function(){ location = location; }, 1500); </script>";
		}
		else
		{
			echo "hata";
		}
	}
	if(isset($hakkinda) && $hakkinda!= "") // kullanıcı sadece hakkındayı güncellemek isterse burası çalışacak. hakkında boş olmamalı çünkü her türlü formdan hakkında post edilecek butona basıldığında. boş gelirse güncelleme yapmasın, boşu boşuna hakkında silinir sonra.
	{
		$profilguncelleme = $baglanti->prepare("UPDATE uyeler SET hakkinda=:hakkinda  WHERE kullaniciadi=:username");
		$profilguncelleme->bindParam(":username",$kullanicibilgisi,PDO::PARAM_STR);
		$profilguncelleme->bindParam(":hakkinda",$hakkinda,PDO::PARAM_STR);
		$profilguncelleme->execute();
		echo "<script> setTimeout(function(){ location = location; }, 1500); </script>";
	}
?>