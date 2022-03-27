<?php
	$userbilgisi = $_GET['kullanici'];
	$kullanici = girisyapankullanici();
	date_default_timezone_set('Europe/Istanbul');
	$islemTarihi = date("d-m-Y H:i");
	$islem = $kullanici['kullaniciadi']." "."adlı kullanıcı"." ".$_GET['kullanici']." "."adlı kullanıcının profilini görüntüledi.";
	$profilSonYorumLimiti = 10;

	$acilanKonuListesiSorgusu = $baglanti->prepare("SELECT * FROM konular WHERE user = :kullaniciadi ORDER BY tarihal DESC LIMIT 5");
	$acilanKonuListesiSorgusu->bindParam(":kullaniciadi",$userbilgisi,PDO::PARAM_STR);
	$acilanKonuListesiSorgusu->fetch(PDO::FETCH_ASSOC);
	$acilanKonuListesiSorgusu->execute();

	$yorumlarSorgusu = $baglanti->prepare("SELECT * FROM mesajlar WHERE user = :kullanici ORDER BY tarihbilgisi DESC LIMIT 5");
	$yorumlarSorgusu->bindParam(":kullanici",$userbilgisi,PDO::PARAM_STR);
	$yorumlarSorgusu->execute();

	$kullaniciprofilfotosorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
	$kullaniciprofilfotosorgusu->bindParam(':kullaniciadi',$userbilgisi,PDO::PARAM_STR);
	$kullaniciprofilfotosorgusu->fetch(PDO::FETCH_ASSOC);
	$kullaniciprofilfotosorgusu->execute();

	$logsorgusu = $baglanti->prepare("INSERT INTO profilloglari(fail,islem,tarih) VALUES (:fail,:islem,:tarih)");
	$logsorgusu->bindParam(":fail",$kullanici['kullaniciadi'],PDO::PARAM_STR);
	$logsorgusu->bindParam(":islem",$islem,PDO::PARAM_STR);
	$logsorgusu->bindParam(":tarih",$islemTarihi,PDO::PARAM_STR);

	if(isset($_GET['kullanici']) && isset($_SESSION['girisyapankullanici'])) // profil görüntülenmelerini veritabanına kaydetme işlemi.
	{
		if(strtolower($_GET['kullanici'])!=strtolower($kullanici['kullaniciadi'])) // kendi profilini görüntüleyenler loglanmasın.
		{
			$logsorgusu->execute();
		}
	}
	
	if ($kullaniciprofilfotosorgusu->rowCount()>0)
	{
		foreach($kullaniciprofilfotosorgusu as $row)
		{
			?>

			<div class="profil-bar">
				<input readonly autofocus class="focus"> <!-- focus -->
				<div class="profil-user-kapsayici">
					<div class="row">
						<div class="col-4 col-sm-3 col-lg-2">
							<?php
							if($row['pp']==NULL)
							{
								echo '<img class="pp" src="resimler/yenikullanicipp.jpg" alt="kullanıcı profil fotoğrafı"/>';
							}
							else
							{
								?>
								<img class="pp" src="<?php echo $row['pp'];?>" alt="profil fotoğrafı yüklenirken hata oluştu.">
								<?php
							}
							?>
						</div>
						<div class="col-8 col-sm-9 col-lg-10">
							<h3 class="pp-user-bilgisi-baslik"><?php echo $row['kullaniciadi'];?></h3>
							<h3 class="profil-user-bilgisi-altbasliklar"><?php echo "Üyelik Tarihi : ".$row['kayitOlmaTarihi']; ?></h3>
							<h3 class="profil-user-bilgisi-altbasliklar"><?php echo "Son Görülme : ".$row['sonGorulmeTarihi']; ?></h3>
						</div>
					</div>
					
				</div>
			</div>

			<div class="profil-hakkinda">
				<h2 id="profil-hakkinda-baslik">Hakkında :</h2>
				<h2 id="profil-hakkinda-hakkinda"><?php echo htmlspecialchars($row['hakkinda']);?></h2>
			</div>

			<div id="profil-alan-kapsayicisi">
				<h3 id="profil-alan-kapsayicisi-basliq">Bu profilin son açtığı konular:</h3>
				<?php
				if($acilanKonuListesiSorgusu->rowCount()>0)
				{
					foreach($acilanKonuListesiSorgusu as $row)
					{
						?>
						<div class="profil-alan-kapsayicisi-icerik-kapsayicisi">
							<div class="profil-alan-kapsayicisi-icerik-baslik">
								<a href="konular/<?php echo seo_link($row['konu_baslik'])."/"; echo $row['id'];?>"><?php echo htmlspecialchars($row['konu_baslik']); ?></a>
							</div>
							<div class="profil-alan-kapsayicisi-icerik">
								<?php echo htmlspecialchars($row['konu_icerik']); ?>
							</div>
							<div class="profil-alan-kapsayicisi-userkimlik">
								<h3 class="profil-alan-kapsayicisi-userkimlik-kimlik">
									<a href="profil/<?php echo seo_link($row['user']);?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a>
								</h3>
							</div>
						</div>
						<?php
					}
				}
				else
				{
					echo "<span style='padding-left:10px;'>Bu kullanıcı daha önce konu açmamış.</span>";
				}
				?>
			</div>
			<div id="profil-alan-kapsayicisi">
				<h3 id="profil-alan-kapsayicisi-basliq">Bu profilin son yorumları:</h3>
				<?php
					if($yorumlarSorgusu->rowCount()>0)
					{
						foreach($yorumlarSorgusu as $row)
						{
							?>
							<div class="profil-alan-kapsayicisi-icerik-kapsayicisi">
								<div class="profil-alan-kapsayicisi-icerik-baslik">
									<a href="konular/<?php echo seo_link($row['konu'])."/"; echo $row['id'];?>"><?php echo htmlspecialchars($row['konu']); ?></a>
								</div>
								<div class="profil-alan-kapsayicisi-icerik">
									<?php echo htmlspecialchars($row['mesaj']); ?>
								</div>
								<div class="profil-alan-kapsayicisi-userkimlik">
									<h3 class="profil-alan-kapsayicisi-userkimlik-kimlik"><a href="profil/<?php echo seo_link($row['user']);?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a></h3>
								</div>
							</div>
							<?php
						}
					}
					else
					{
						echo "<span style='padding-left:10px;'>Bu kullanıcı daha önce mesaj yazmamış.</span>";
					}
				?>
			</div>

			<!--
			<div id="profil-profil-yorumlari-kapsayicisi">
				<h3 id="profil-profil-yorumlari-baslik">Bu profile yapılmış yorumlar:</h3>
			</div>
		-->
			<?php
		}
	}
	else
	{
		echo "profili görüntülemeye çalışırken hata ile karşılaşıldı."."<br>"."->bu profil silinmiş olabilir.";
	}
?>
