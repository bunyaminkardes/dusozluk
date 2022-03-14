<?php 

   // MASTERPAGE -- LAYOUT SAYFASI.

   //error_reporting(0); /* error reporting hataları göstermeyi engeller, test yaparken pasif hale getir. */
   
   require_once("kutuphane.php");

   $basehrefAnahtar = 0; // localhost için 0, dusozluk için 1.
   $kullanici = girisyapankullanici();
   $kullaniciadi = @$_SESSION['girisyapankullanici'];
   @$cikisbilgisi=$_GET['cikis']; // çıkış bilgisi default olarak 0, çıkış yap linkine tıklandığında 1 olacak.
   date_default_timezone_set('Europe/Istanbul');
   $sonGorulmeTarihiCikis = date("d-m-Y H:i");
   $kategoriKonuLimiti = 15;
   $sagbarLimit = 5;
   $title = "";
   $descriptionContent="";
   @$konununIdNumarasi = $_GET['id'];
   @$profilinAdi = $_GET['kullanici'];

   if($_SERVER['SCRIPT_NAME'] == '/index.php')
   {
      $title = "Dusozluk Anasayfa";
      $descriptionContent = "Düzcenin sözlüğü dusozluk'e hoş geldiniz.";
   }
   else if($_SERVER['SCRIPT_NAME']=='/giris.php')
   {
   	$title = "Giriş Yap";
      $descriptionContent = "dusozluk giriş yap.";
   }
   else if($_SERVER['SCRIPT_NAME']=='/kayitol.php')
   {
   	$title = "Kayıt Ol";
      $descriptionContent = "dusozluk kayıt ol.";
   }
   else if($_SERVER['SCRIPT_NAME']=='/konuac.php')
   {
   	$title = "Konu Aç";
      $descriptionContent = "dusozluk konu aç";
   }
   else if($_SERVER['SCRIPT_NAME']=='/adminpanel.php')
   {
   	$title = "Admin Paneli";
   }
   else if($_SERVER['SCRIPT_NAME']=='/konular.php')
   {
   	$konularTitleSorgusu = $baglanti->prepare("SELECT * FROM konular WHERE id = :id");
   	$konularTitleSorgusu->bindParam(":id",$konununIdNumarasi,PDO::PARAM_INT);
   	$konularTitleSorgusu->execute();
   	foreach($konularTitleSorgusu as $row)
   	{
   		$title = "Konu:"." ".$row['konu_baslik'];
         $descriptionContent = $row['konu_baslik'].", ".$row['likesayisi']." "."beğeni".", ".$row['konu_turu'].".";
   	}
   }
   else if($_SERVER['SCRIPT_NAME']=='/profil.php')
   {
   	$profilTitleSorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
   	$profilTitleSorgusu->bindParam(":kullaniciadi",$profilinAdi,PDO::PARAM_STR);
   	$profilTitleSorgusu->execute();
   	foreach($profilTitleSorgusu as $row)
   	{
   		$title = "Profil:"." ".$row['kullaniciadi'];
         $descriptionContent = "dusozluk"." ".$row['kullaniciadi']." adlı kullanıcının profili.";
   	}
   }
   else if($_SERVER['SCRIPT_NAME']=='/profilim.php')
   {
   	$title = "Profilim";
   }
   else if($_SERVER['SCRIPT_NAME'] == '/sifreyisifirla.php')
   {
      $title = "Şifreyi Sıfırla";
   }
   if($basehrefAnahtar == 0)
   {
      $basehref = "http://localhost";
   }
   else if($basehrefAnahtar == 1)
   {
      $basehref = "https://dusozluk.com";
   }

   $bansorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
   $bansorgusu->bindParam(':kullaniciadi',$kullaniciadi,PDO::PARAM_STR);
   $bansorgusu->fetch(PDO::FETCH_ASSOC);
   $bansorgusu->execute();

   $songorulmesorgusuCikis = $baglanti->prepare("UPDATE uyeler SET sonGorulmeTarihi = :sonGorulmeTarihi WHERE kullaniciadi = :kullaniciadi");
   $songorulmesorgusuCikis->bindParam(":sonGorulmeTarihi",$sonGorulmeTarihiCikis,PDO::PARAM_STR);
   $songorulmesorgusuCikis->bindParam(":kullaniciadi",$_SESSION['girisyapankullanici'],PDO::PARAM_STR);

   $sagbarSorgusu = $baglanti->prepare("SELECT * FROM uyeler ORDER BY id DESC LIMIT :limitt");
   $sagbarSorgusu->bindParam(':limitt',$sagbarLimit,PDO::PARAM_INT);
   $sagbarSorgusu->fetch(PDO::FETCH_ASSOC);
   $sagbarSorgusu->execute();

   if(isset($_SESSION['girisyapankullanici']))
   {
      $bildirimSorgusu = $baglanti->prepare("SELECT * FROM bildirimler WHERE kullanici = :kullanici");
      $bildirimSorgusu->bindParam(":kullanici",$_SESSION['girisyapankullanici'],PDO::PARAM_STR);
      $bildirimSorgusu->execute();
      $bildirimMiktari = $bildirimSorgusu->rowCount();
   }

?>
<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta name="google-site-verification" content="lTwKVKo33u5k4etLVokv2haUfI4nzg_OsBj_argQCX0" />
      <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3388530043982968" crossorigin="anonymous"></script>
      <base href="<?php echo $basehref; ?>">
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
      <meta name="author" content="Bunyamin Kardes" /> 
      <meta name="owner" content="Bunyamin Kardes" />
      <meta name="description" content="<?php echo $descriptionContent; ?>" />
      <meta name="keywords" content="du, sozluk, dusozluk, du sozluk, düzce, duzce" />  
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="css/duSozlukCss.css">
      <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
      <link rel='shortcut icon' type='image/x-icon' href='resimler/favicon.png'/>
      <title><?php echo $title; ?></title>
   </head>
   <body>
         <!---------------------------------------------- HEADER BASLANGIC ----------------------------------------------->
      <div class="container-fluid sticky-top"> 
         <div class="row">
            <div class="col-12 col-sm-12 col-lg-12" style="background-color: var(--temarengi);">
               <div class="header-yesilbar"></div>
            </div>
         </div>
         <div class="row" style="background-color: white;">
            <div class="col-12 col-sm-12 col-lg-4">
               <div class="header">
                  <div class="header-logo">
                     <a href="anasayfa"><img width="100%" src="resimler/logo.png" alt="resim yüklenirken hata oluştu."></a>
                  </div> 
               </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-4" style="">
                  <div class="header-aramakutusu">
                        <input id="ara" onkeyup="ipucugoster(this.value)" class="header-aramakutusu2" type="text" placeholder=" konu veya kullanıcı ara">
                        <input class="header-aramabutonu" type="button" disabled>
                     <div id="ajaxlivesearch">
                        <p>
                           <div id="ipucu"></div>
                        </p>
                     </div>
                  </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-4" >
               <div class="header-girisvekayitol" >
                  <ul>
                     <?php
                     if(empty($_SESSION['girisyapankullanici'])) // kullanıcı session bilgisi boşsa, yani giriş yapılmamışsa giriş ve kayıt ol gözükecek.
                     {
                        echo '<li><a href="kayit-ol" id="deneme1">kayıt ol</a></li><li><a href="giris-yap" id="deneme">giriş</a></li>';  
                     }
                     else // kullanıcı session bilgisi boş değilse, yani giriş yapılmışsa çıkış yap gözükecek.
                     {
                        ?>
                        <li><a href="index.php?cikis=<?php echo 1;?>">çıkış yap</a></li>
                        <?php
                        if($kullanici['bandurumu']==0) // ayrıca, kullanıcı banlıysa konu aç da gözükmesin.
                        {
                           echo '<li><a href="konu-ac">konu aç</a></li>';
                        }
                        ?>
                        <li>
                           <a id="kullanicihosgeldin" href="profilim.php?kullanici=<?php echo @$_SESSION["girisyapankullanici"];?>"><?php echo "hoşgeldin"." ".$_SESSION["girisyapankullanici"];?></a>
                           <button id="bildirimZiliKapsayiciButon">
                              <?php
                                 if($bildirimMiktari>0)
                                 {
                                    echo "<img width='25' height='90%' src='resimler/bildirimlizil.png'>";
                                    echo $bildirimMiktari;
                                 }
                                 else
                                 {
                                    echo "<img width='18' height='100%' src='resimler/zil.png'>";
                                    echo $bildirimMiktari;
                                 }
                              ?>
                              <div id="bildirimZiliAcilirMenu" style="display:none;">
                                 <?php
                                 if($bildirimSorgusu->rowCount()>0)
                                 {
                                    foreach($bildirimSorgusu as $row)
                                    {
                                       ?>
                                       <a href="konular.php?konu=<?php echo $row['konu']; ?>&id=<?php echo $row['konuid']; ?>&bildirimNo=<?php echo $row['id']; ?>"><?php echo $row['bildirim']; ?></a>
                                       <?php
                                    }
                                 }
                                 else
                                 {
                                    echo "<span>Yeni bildiriminiz yok.</span>";
                                 }
                                 ?>
                              </div>
                           </button>
                        </li>
                        <?php
                     }
                     if ($cikisbilgisi==1) // cikis bilgisi 1 ise session'lar yok edilip çıkış yapılacak ve kullanıcı anasayfaya yönlendirilecek.
                     {
                        $songorulmesorgusuCikis->execute();
                        session_unset();
                        session_destroy();
                        echo "<script type='text/javascript'> document.location = 'anasayfa'; </script>";
                     }
                     ?>
                  </ul>
               </div>
            </div>
            <div class="col-12 col-sm-12" style="height: 1px; background-color: gray;">
            </div>
         </div>
         <div class="row">
            <div class=".d-block d-sm-none col-12 col-sm-12 col-md-12 col-lg-12" style="background-color: #9b9b9b;">
               <div class="row" style="background-color:var(--navbar);">
                  <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                    <a class="yanbar-kategoriler" href="index.php/?kategori=gundem">Gündem</a>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                     <a class="yanbar-kategoriler" href="index.php/?kategori=enTaze">En Taze</a>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                     <button id="kategoriacbutonu" class="yanbar-kategoriler">Kategoriler</button>
                     <div id="mobilkategori" style="display: none;">
                        <ul>
                           <li><a href="index.php/?kategori=itiraf">İtiraf</a></li>
                           <li><a href="index.php/?kategori=Astroloji">Astroloji</a></li>
                           <li><a href="index.php/?kategori=Yasam">Yaşam</a></li>
                           <li><a href="index.php/?kategori=Spor">Spor</a></li>
                           <li><a href="index.php/?kategori=Muzik">Müzik</a></li>
                           <li><a href="index.php/?kategori=Universite">Üniversite</a></li>
                           <li><a href="index.php/?kategori=Anime">Anime/Manga</a></li>
                           <li><a href="index.php/?kategori=Genel">Genel</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div> 
      <!---------------------------------------------- HEADER BITIS ----------------------------------------------->
      <!---------------------------------------------- MAIN BASLANGIC ----------------------------------------------->
      <div class="container-fluid"> 
         <div class="row">
            <!---------------------------------------------- YANBAR BASLANGIC ----------------------------------------------->
            <div class="d-none d-sm-block col-12 col-sm-12  col-lg-3"> <!-- d-none d-sm-block : hide on screens smaller than xs -->
               <div class="yanbar">
                  <div class="yanbar-gundem-baslik" >
                     <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                        <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','itiraf','Astroloji','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                        <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','itiraf','Astroloji','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                        <button id="yanbar-kategori-butonu" class="yanbar-butonlar">Kategoriler</button>
                     </h3>
                  </div>
                  <div id="yanbar-kategori" style="display: none;">
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('itiraf','gundem','enTaze','Astroloji','yasam','spor','genel','muzik','universite','anime')">İtiraf</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('Astroloji','gundem','enTaze','itiraf','yasam','spor','genel','muzik','universite','anime')">Astroloji</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('yasam','gundem','enTaze','itiraf','Astroloji','spor','genel','muzik','universite','anime')">Yaşam</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('spor','gundem','enTaze','itiraf','Astroloji','yasam','genel','muzik','universite','anime')">Spor</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('muzik','gundem','enTaze','itiraf','Astroloji','yasam','spor','genel','universite','anime')">Müzik</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('universite','gundem','enTaze','itiraf','Astroloji','yasam','spor','genel','muzik','anime')">Üniversite</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('anime','gundem','enTaze','itiraf','Astroloji','yasam','spor','genel','muzik','universite')">Anime/Manga</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('genel','gundem','enTaze','itiraf','Astroloji','yasam','spor','muzik','universite','anime')">Genel</button>
                  </div>
                  <div class="yanbar-gundem" id="gundem">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $sorgu = $baglanti->prepare("SELECT * FROM konular ORDER BY likesayisi*0.40 + mesajsayisi*0.20 - dislikesayisi*0.40 DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                        	foreach($sorgu as $row)
                        	{
                        		?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul> 
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="enTaze" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $sorgu = $baglanti->prepare("SELECT * FROM konular ORDER BY tarihal DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                     <div class="yanbar-gundem-konular">
                        <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?></a>
                        <ul>
                           <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>"><?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                        </ul>
                     </div>
                     <?php             
                        }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="itiraf" style="display:none;">
                     
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="itiraf";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="Astroloji" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="Astroloji";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="yasam" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="Yasam";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>"> <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="spor" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="Spor";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="muzik" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="Muzik";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="universite" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="Universite";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="anime" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="Anime";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem" id="genel" style="display:none;">
                     <?php
                        $limit =$kategoriKonuLimiti;
                        $konu_turu="Genel";
                        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY mesajsayisi DESC LIMIT :limitt");
                        $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                        $sorgu->bindParam(':konu_turu',$konu_turu,PDO::PARAM_STR);
                        $sorgu->fetch(PDO::FETCH_ASSOC);
                        $sorgu->execute();
                        #gündemde 15 adet konu, mesaj sayısına göre listelensin.
                        if ($sorgu->rowCount()>0) 
                        {
                           foreach($sorgu as $row)
                           {
                              ?> 
                              <div class="yanbar-gundem-konular">
                                 <a class="yanbar-gundem-konular-mesajsayaci"><?php echo $row['mesajsayisi']; ?>
                                    
                                 </a>
                                 <ul>
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r(htmlentities($row['konu_baslik']));?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        else if($sorgu->rowCount()==0)
                        {
                           echo "Bu kategoriye ait hiç konu açılmamış.";
                        }
                        ?>
                  </div>
               </div> 
            </div> 
                             
            <!---------------------------------------------- YANBAR BITIS ----------------------------------------------->

            <!---------------------------------------------- DEGISKEN ALAN BASLANGIC ----------------------------------------------->
            <div class="col-12 col-sm-12 col-lg-7">
                <div class="main-maincont">
                    <?php
                    @include_once ($content);
                    ?>
                </div>
            </div>
            <!---------------------------------------------- DEGISKEN ALAN BITIS ----------------------------------------------->
           
            <!---------------------------------------------- SAGBAR BASLANGIC ----------------------------------------------->
            <div class="col-12 col-sm-12 col-lg-2"> 
               <div class="sagbar">
                  <div class="sagbar-div">
                     <h3 class="sagbar-yazilar-baslik">Son Üyeler</h3>
                        <?php
                        if ($sagbarSorgusu->rowCount()>0)
                        {
                           foreach($sagbarSorgusu as $row)
                           {
                              ?>
                              <div style="width: 100%; height: 30px;">
                                 <?php
                                 if(isset($_SESSION['girisyapankullanici']))
                                 {
                                    ?>
                                    <a href="profil/<?php echo seo_link($row['kullaniciadi']);?>" class="sagbar-yazilar"><?php echo htmlentities($row['kullaniciadi']);?></a>
                                    <?php
                                 }
                                 else
                                 {
                                    ?>
                                    <a href="giris.php?pq=0" class="sagbar-yazilar"><?php echo htmlentities($row['kullaniciadi']);?></a>
                                    <?php
                                 }
                                 ?>
                              </div>
                              <?php
                           }
                        }
                        ?>
                  </div>
               </div>

                  <div class="sagbar-div">
                                <h3 class="sagbar-yazilar-baslik">İstatistikler</h3>
                               <a class="sagbar-yazilar">Toplam konu sayısı :
                                 <?php 
                                    $sagbarSorgusu = $baglanti->query("SELECT id FROM konular", PDO::FETCH_ASSOC);
                                    $toplamkonusayisi=$sagbarSorgusu->rowCount();
                                    echo $toplamkonusayisi;
                                 ?>
                               </a>
                               <a class="sagbar-yazilar">Toplam mesaj sayısı :
                                 <?php
                                    $sagbarSorgusu = $baglanti->query("SELECT mesaj FROM mesajlar", PDO::FETCH_ASSOC);
                                    $toplammesajsayisi=$sagbarSorgusu->rowCount();
                                    echo $toplammesajsayisi;
                                 ?>
                               </a>
                               <a class="sagbar-yazilar">Toplam üye sayısı :
                                 <?php
                                    $sagbarSorgusu = $baglanti->query("SELECT * FROM uyeler", PDO::FETCH_ASSOC);
                                    $toplamuyesayisi=$sagbarSorgusu->rowCount();
                                    echo $toplamuyesayisi;
                                 ?>
                               </a>
                  </div>
               </div>
               
            </div> 
            <!---------------------------------------------- SAGBAR BITIS ----------------------------------------------->
         </div>
      </div>
      <!---------------------------------------------- MAIN BITIS ----------------------------------------------->


      <!---------------------------------------------- FOOTER BASLANGIC ----------------------------------------------->
      <div class="container-fluid"> 
         <div class="row">
               <div class="col-12 col-sm-12 col-lg-3">
               </div>
                <div class="col-12 col-sm-12 col-lg-7">
                     <div class="main-footer-kapsayicisi">
                        <div class="main-footer">
                           <ul>
                              <li>
                                 <a href="html/iletisim.html">iletişim</a>
                              </li>
                              <li>
                                 <a href="html/reklam.html">reklam</a>
                              </li>
                              <li>
                                 <a href="html/gizlilikpolitikamiz.html">gizlilik politikamız</a>
                              </li>
                              <li>
                                 <a href="html/sss.html">sss</a>
                              </li>
                           </ul>
                        </div>
                  </div>
               </div>
               <div class="col-12 col-sm-12 col-lg-3"></div>
         </div>  
      </div>
      <!---------------------------------------------- FOOTER BITIS ----------------------------------------------->


<?php  
         if(@$kullanici['rutbe']=="admin" || @$kullanici['rutbe']=="moderator" && $kullanici['bandurumu']!=1) // giriş yapan kullanıcı admin veya moderatörse admin paneline gitme linki gözüksün.
         {
            ?>
            <div id="adminpanelegit">
               <a href="adminpanel.php">Panele git</a>
            </div>
            <?php
         }

         if($bansorgusu->rowCount()>0)
         {
            foreach ($bansorgusu as $row) 
            {
               if($row['bandurumu'] == 0)
               {
               }
               else if($row['bandurumu']==1)
               {
                  echo "<script>document.getElementById('kullanicihosgeldin').style.textDecoration='line-through'; document.getElementById('kullanicihosgeldin').style.color='#ff0000';</script>";
               }
               if($row['rutbe']=="admin")
               {
                  echo "<script>document.getElementById('kullanicihosgeldin').style.color='#f27900';</script>";
               }
               else if($row['rutbe']=="moderator")
               {
                  echo "<script>document.getElementById('kullanicihosgeldin').style.color='#1d9bf0';</script>";
               }
               else if($row['rutbe']=="uye")
               {
                  echo "<script>document.getElementById('kullanicihosgeldin').style.color='#f2058f';</script>";
               }
            }
         }
?>
   <script type="text/javascript" src="javascript/js.js" charset="utf-8"></script>
   </body>
</html>