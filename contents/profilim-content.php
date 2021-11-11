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
	<div style="width:100%; min-height: 300px; padding-top:10px; margin-top:30px; clear:both; float:left; border-style: solid; border-width: 1px; border-color:#9b9b9b; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
		<h2 style="font-size:24px;  text-align: center; padding-top:15px; font-family:var(--temayazitipi);">Profili Özelleştir</h2>
		<form method="POST" action="" enctype="multipart/form-data" style="width:100%; display: block;">
			<textarea maxlength="1500" placeholder="Hakkında (maksimum 1500 karakter)" name="hakkinda" style="resize:none; width:90%; display:block; margin:0 auto; height: 150px;"></textarea>
			<div style="width:90%; margin:0 auto; margin-top:10px;">
				profil resmini değiştir : <input style="" type="file" name="profilfoto">
			</div>
			<div style="width:90%; display: block; margin:0 auto;">
				<input style="width:60px; height:35px; margin-bottom:10px; float:right; display:inline-block; margin-top:10px;" type="submit" name="gonderke" value="değiştir">
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
		$Final = $konum.$isim;

		if(move_uploaded_file($geciciKonum, "$dosyaKonumu/$isim"))
		{
			$profilguncelleme = $baglanti->prepare("UPDATE uyeler SET pp=:pp WHERE kullaniciadi= :username");			
			$profilguncelleme->bindParam(":pp",$Final,PDO::PARAM_STR);
			$profilguncelleme->bindParam(":username",$kullanicibilgisi,PDO::PARAM_STR);
			$profilguncelleme->execute();
			echo "<script> setTimeout(function(){ location = location; }, 1500); </script>";
		}
		else
		{
			echo "başarısızlık";
		}
	}
	if(isset($hakkinda) && $hakkinda!= "") // kullanıcı sadece hakkındayı güncellemek isterse burası çalışacak. hakkında boş olmamalı çünkü her türlü formdan hakkında post edilecek butona basıldığında. boş gelirse güncelleme yapmasın, boşu boşuna hakkında silinir sonra.
	{
		$profilguncelleme = $baglanti->prepare("  UPDATE uyeler SET hakkinda=:hakkinda  WHERE kullaniciadi=:username  ");
		$profilguncelleme->bindParam(":username",$kullanicibilgisi,PDO::PARAM_STR);
		$profilguncelleme->bindParam(":hakkinda",$hakkinda,PDO::PARAM_STR);
		$profilguncelleme->execute();
		echo "<script> setTimeout(function(){ location = location; }, 1500); </script>";
	}


?>