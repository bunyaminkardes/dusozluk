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
$kullaniciprofilfotosorgusu->bindParam(':kullaniciadi',$userbilgisi);
$kullaniciprofilfotosorgusu->fetch(PDO::FETCH_ASSOC);
$kullaniciprofilfotosorgusu->execute();
if ($kullaniciprofilfotosorgusu->rowCount()>0)
{
	foreach($kullaniciprofilfotosorgusu as $row)
	{?>		
		<div class="profil-bar">
			<input readonly autofocus class="focus"> <!-- focus --> <?php   
			if ($row['pp']==NULL) 
			{
				echo '<img class="pp" src="resimler/yenikullanicipp.jpg" alt="kullanıcı profil fotoğrafı"/>';
			}
			else
			{
				?><img class="pp" src="<?php echo $row['pp'];?>"><?php
			}
		?>
			<h3 class="pp-user-bilgisi"><?php echo strtoupper($userbilgisi);?></h3>
		</div>
		<div class="profil-hakkinda">
			<h2 id="profil-hakkinda-baslik">Hakkında :</h2>
			<h2 id="profil-hakkinda-hakkinda"><?php echo $row['hakkinda'];?></h2>
		</div>
		<h3 class="profil-yorum-basliklari">Bu kullanıcının son 10 yorumu :</h3>
		<?php
   	  	$limit = 10;
		$sorgu = $baglanti->prepare("SELECT * FROM mesajlar WHERE user = :userbilgisi ORDER BY tarihbilgisi DESC LIMIT :limitt");
		$sorgu->bindParam(':userbilgisi',$userbilgisi);
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
	}
}
else
{
	echo "hata";
}
?>