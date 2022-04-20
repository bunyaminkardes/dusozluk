<?php

$konuBasligi = $_POST['konuBasligi'];
$konuIcerigi = $_POST['konuIcerigi'];
$konuTuru = $_POST['konuTuru'];
$kullanici = $_SESSION['girisyapankullanici'];
date_default_timezone_set('Europe/Istanbul');
$tarih = date("d-m-Y H:i");
$token = token_uret();
$postEdilenToken = $_POST['postEdilenToken'];
$unixtimestamp = time();

?>
<div id="konuac">
	<form method="POST" action="" autocomplete="off">
		<h3 id="konuac-baslik">Konu Aç</h3>
		<input id="konuac-konubasligi" type="textbox" name="konuBasligi" autofocus placeholder=" Lütfen konu başlığını giriniz. " maxlength="60" required>
		<textarea id="konuac-konuicerigi" name="konuIcerigi" placeholder=" Lütfen konu içeriğini giriniz. " maxlength="6000" required></textarea>
		<select id="konuac-konuturu" name="konuTuru">
			<option value="Secilmemis">Lütfen Konu türünü seçiniz.</option>
			<option value="itiraf">İtiraf</option>
			<option value="Astroloji">Astroloji</option>
			<option value="Yasam">Yaşam</option>
			<option value="Spor">Spor</option>
			<option value="Muzik">Müzik</option>
			<option value="Universite">Üniversite</option>
			<option value="Anime">Anime</option>
			<option value="Genel">Genel</option>
		</select>
		<input type="hidden" name="KONU_AC_TOKEN" value="<?php echo $token; ?>">
		<input id="konuac-buton" type="submit" name="KONU_AC_SUBMIT" value="konu aç">
	</form>
</div>

<?php


if(isset($_POST['KONU_AC_SUBMIT'])) //ilk olarak konu aç butonuna tıklanmış mı diye kontrol edelim. tıklama işlemi yapılmadıysa boşuna işlem yapılmasın.
{
	if(hash_equals($token,$postEdilenToken==false))
	{
		echo "<h3 id='hatamesaj'>HATA</h3>";
	}
	else
	{
		if(isset($konuBasligi) && isset($konuIcerigi) && isset($konuTuru)) //ikinci olarak konu başlığı, konu içeriği ve konu türü değişkenleri tanımlı mı diye bakalım.
		{
			if($konuTuru=="Secilmemis") //konu türü seçilmediyse uyarı verdirelim.
			{
				echo "<h3 id='hatamesaj'>Lütfen konu türü seçiniz.</h3>";
			}
			else if(ctype_space($konuBasligi)==true || ctype_space($konuIcerigi)==true) //konu başlığı veya konu içeriği sadece boşluklardan oluşamasın.
			{
				echo "<h3 id='hatamesaj'>Konu başlığı veya konu içeriği boş girilemez.</h3>";
			}
			else if(preg_replace("/[^0-9a-zA-Z ]/", "", $konuBasligi)==false) //konu başlığı sadece harf ve numaralardan mı oluşuyor diye kontrol edelim. bu işlemi ctype_alnum() fonksiyonu ile de yapabilirdik ama o zaman türkçe karakter sorunu ortaya çıkardı.
			{
				echo "<h3 id='hatamesaj'>Lütfen konu ismi seçerken sadece harf veya numara kullanmaya dikkat edin.</h3>";
			}
			else // herhangi bir sıkıntı yoksa konu açma işlemine başlayalım.
			{
				try //halihazırda var olan bir konu insert edilmeye çalışılırsa diye insert işlemini try catch blokları içerisine alarak yaptıralım.
				{
					$sqlkomut = $baglanti->prepare("INSERT INTO konular(konu_baslik,konu_icerik,konu_turu,user,tarih,unixtimestamp) VALUES (:konuBasligi,:konuIcerigi,:konuTuru,:kullanici,:tarih,:unixtimestamp)");
					$sqlkomut->bindParam(':konuBasligi',$konuBasligi,PDO::PARAM_STR);
					$sqlkomut->bindParam(':konuIcerigi',$konuIcerigi,PDO::PARAM_STR);
					$sqlkomut->bindParam(':konuTuru',$konuTuru,PDO::PARAM_STR);
					$sqlkomut->bindParam(':kullanici',$kullanici,PDO::PARAM_STR);
					$sqlkomut->bindParam(':tarih',$tarih,PDO::PARAM_STR);
					$sqlkomut->bindParam(":unixtimestamp",$unixtimestamp,PDO::PARAM_INT);
					$sqlkomut->execute();					
					if($sqlkomut->rowCount()>0)
					{
						echo "Yeni konu başarıyla oluşturuldu.";
						echo "<script> setTimeout(function() {window.location.href='index.php';}, 1500);</script>";
					}
				}
				catch(Exception $e)
				{
					echo "<h3 id='hatamesaj'>Hata, böyle bir konu zaten bulunuyor.</h3>";
				}
			}
		}
		else
		{
			echo "<h3 id='hatamesaj'>Lütfen gerekli yerleri eksiksiz doldurun.</h3>";
		}
	}
}

?>
