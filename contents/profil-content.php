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
		<div style="width:100%; min-height: 130px; margin-left:10px; margin-top:20px; float:right; border-style: solid; border-width: 1px; border-color:#9b9b9b; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
			<div style="width:100%; height:130px; overflow: hidden; float:left; padding:5px;">
			<input readonly autofocus class="focus"> <!-- focus --> <?php   
				if ($row['pp']==NULL) 
				{

					echo '<img style="width:120px; height:120px; object-fit:cover; object-position: 50% 15%; float:left; border-style:solid; border-width:2px; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;  display: block;  " src="resimler/yenikullanicipp.jpg" alt="kullanıcı profil fotoğrafı"/>';
				}
				else
				{
					?> <img style="width:120px; height:120px; object-fit:cover; object-position: 50% 15%; float:left; border-style:solid; border-width:2px; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;  display: block; border-radius: 0%;" src="<?php echo $row['pp']; ?>"> <?php
				}?>
				<h3 style='text-align:center; font-family:var(--temayazitipi); font-size:22px; padding-left:20px; color:var(--temarengi); float:right;'><?php echo strtoupper($userbilgisi);?></h3>
			</div>
		</div>
		<div style="width:100%; min-height: 200px; padding-top:10px;  margin-top:30px; clear:both; float:left; border-style: solid; border-width: 1px; border-color:#9b9b9b; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
			<div style="width:100%; float:left;">
				<h2 style="font-size:18px; text-align: center; color:var(--temarengi); font-family:var(--temayazitipi);">
					Hakkında :
				</h2>
				<h2 style="font-size:15px; padding-left:10px; padding-top:10px; color:black; word-wrap: break-word; white-space: pre-wrap; font-family:var(--temayazitipi); font-weight: lighter; "><?php echo $row['hakkinda'];?></h2>
			</div>
		</div>

<?php /**********************************************************************************************************************************************************/ ?>
		<h3 class="profil-yorum-basliklari" style="clear:both;">Bu kullanıcının son 10 yorumu :</h3>
<?php
   	  	$limit = 10;
		$sorgu = $baglanti->prepare("SELECT * FROM mesajlar WHERE user = :userbilgisi ORDER BY tarihbilgisi DESC LIMIT :limitt ");
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
	 					<h3 class="profil-yorum-div-baslik"><?php echo $row['konu']; ?></h3>
	 				</a>
	 				<br/>
	 				<h3 class="profil-yorum-div-mesaj"><?php echo $row['mesaj'];?></h3>
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
?>
<?php
	}
}
else
{
	echo "hata";
}
?>
<?php /**********************************************************************************************************************************************************/ ?>