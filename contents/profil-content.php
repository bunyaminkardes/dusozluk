<!DOCTYPE html>
<html>
<head>
	<title>
		<?php 
			$userbilgisi = $_GET['kullanici'];
			echo "Profil: ".$userbilgisi;
		?>
	</title>
</head>
<body>
</body>
</html>

<?php 

$_GET['kullanici'];
$userbilgisi = $_GET['kullanici'];

$kullaniciprofilfotosorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
$kullaniciprofilfotosorgusu->bindParam(':kullaniciadi',$userbilgisi,PDO::PARAM_STR);
$kullaniciprofilfotosorgusu->fetch(PDO::FETCH_ASSOC);
$kullaniciprofilfotosorgusu->execute();
if ($kullaniciprofilfotosorgusu->rowCount()>0)
{
	foreach($kullaniciprofilfotosorgusu as $row)
	{
		?>		
		<div class="profil-bar">
			<input readonly autofocus class="focus"> <!-- focus --> <?php   
			if ($row['pp']==NULL) 
			{
				echo '<img class="pp" src="resimler/yenikullanicipp.jpg" alt="kullanıcı profil fotoğrafı"/>';
			}
			else
			{
				?><img class="pp" src="<?php echo $row['pp'];?>" alt="profil fotoğrafı yüklenirken hata oluştu."><?php
			}
			?>
			<div class="profil-user-kapsayici">
				<h3 class="pp-user-bilgisi-baslik"><?php echo strtoupper($userbilgisi);?></h3>
				<h3 class="profil-user-bilgisi-altbasliklar"><?php echo "Üyelik Tarihi : ".$row['kayitOlmaTarihi']; ?></h3>
				<h3 class="profil-user-bilgisi-altbasliklar"><?php echo "Son Görülme : ".$row['sonGorulmeTarihi']; ?></h3>
				<button class="profil-dmbutton">Özel mesaj</button>
			</div>
		</div>
		<div class="profil-hakkinda">
			<h2 id="profil-hakkinda-baslik">Hakkında :</h2>
			<h2 id="profil-hakkinda-hakkinda"><?php echo $row['hakkinda'];?></h2>
		</div>
		<h3 class="profil-yorum-basliklari">Bu kullanıcının son 10 yorumu :</h3>
		<?php
   	  	$limit = 10;
		$sorgu = $baglanti->prepare("SELECT * FROM mesajlar WHERE user = :userbilgisi ORDER BY tarihbilgisi DESC LIMIT :limitt");
		$sorgu->bindParam(':userbilgisi',$userbilgisi,PDO::PARAM_STR);
		$sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT); 
		$sorgu->fetch(PDO::FETCH_ASSOC);
		$sorgu->execute();
	 	if($sorgu->rowCount()>0)
	 	{
	 		foreach($sorgu as $row)
	 		{
				?>
	 			<br/>
	 			<div class="profil-yorum-div">
	 				<a href="<?php echo 'konular.php?id='.$row['id'].'&konu='.$row['konu']; ?>">
	 					<h3 class="profil-yorum-div-baslik"><?php echo ters_seo_link($row['konu']); ?></h3>
	 				</a>
	 				<br/>
	 				<h3 class="profil-yorum-div-mesaj"><?php echo htmlentities($row['mesaj']);?></h3>
	 			</div>
	 			<div class="profil-yorum-div-kimlik-kapsayici">
	 				<div class="profil-yorum-div-kimlik-kimlik">
						<h3>
							<a href="profil.php?kullanici=<?php echo $row['user']; ?>"><?php echo $row['tarih']." ".$row['user']; ?></a>
						</h3>
					</div>
				</div>
	 			<div class="profil-yorum-div-ghost"></div> 	
				<?php
	 		}
	 	}
	 	else if($sorgu->rowCount()==0)
	 	{
	 		echo("Bu kullanıcı daha önce hiçbir konuya yorum yapmamış.");
	 	}
	}
}
else
{
	echo "profili görüntülemeye çalışırken hata ile karşılaşıldı.";
}
?>

<?php  

$kullanici = girisyapankullanici();
date_default_timezone_set('Europe/Istanbul');
$islemTarihi = date("d-m-Y H:i");

if(isset($_GET['kullanici']) && isset($_SESSION['girisyapankullanici'])) // profil görüntülenmelerini veritabanına kaydetme işlemi.
{
	if($_GET['kullanici']!=$kullanici['kullaniciadi']) // kendi profilini görüntüleyenler loglanmasın.
	{
		$islem = $kullanici['kullaniciadi']." "."adlı kullanıcı"." ".$_GET['kullanici']." "."adlı kullanıcının profilini görüntüledi.";
		$logsorgusu = $baglanti->prepare("INSERT INTO profilLoglari(islem,tarih) VALUES (:islem,:tarih)");
		$logsorgusu->bindParam(":islem",$islem,PDO::PARAM_STR);
		$logsorgusu->bindParam(":tarih",$islemTarihi,PDO::PARAM_STR);
		$logsorgusu->execute();
	}
}

?>