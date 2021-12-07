<?php 
   // MAHMUT CAN GENCER
   //error_reporting(0); /* error reporting hataları göstermeyi engeller, test yaparken pasif hale getir. */
   require_once("baglanti.php");
   require_once("kutuphane.php");
?>
<!DOCTYPE html>
<html lang="tr">
   <head>
      <base href="http://localhost">
      <!--<base href="https://dusozluk.com">-->
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="css/duSozlukCss.css">
      <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
      <link rel='shortcut icon' type='image/x-icon' href='resimler/favicon.png'/>
   </head>
   <body>
         <!---------------------------------------------- HEADER BASLANGIC ----------------------------------------------->
      <div class="container-fluid"> 
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
                     <form autocomplete="off">
                        <input id="ara" onkeydown="goster('ipucu')" onkeyup="ipucugoster(this.value)" class="header-aramakutusu2" type="search" placeholder=" konu veya kullanıcı ara">
                        <input class="header-aramabutonu" type="button" disabled>
                     </form>
                     <div id="ajaxlivesearch">
                        <p>
                           <div id="ipucu">
                           </div>
                        </p>
                     </div>
                  </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-4" >
               <div class="header-girisvekayitol" >
                  <ul>
                     <?php
                     $cikisbilgisi=0;
                     if(@$_SESSION['hosgeldiniz']=="") // kullanıcı session bilgisi boşsa, yani giriş yapılmamışsa giriş ve kayıt ol gözükecek.
                     {
                        echo '<li><a href="kayit-ol" id="deneme1">kayıt ol</a></li><li><a href="giris-yap" id="deneme">giriş</a></li>';  
                     }
                     else // kullanıcı session bilgisi boş değilse, yani giriş yapılmışsa çıkış yap gözükecek.
                     {
                        ?>
                        <li><a href="index.php?cikis=<?php echo 1; ?>">çıkış yap</a></li>
                        <?php
                        $kullanici = girisyapankullanici(); // ayrıca, kullanıcı banlıysa konu aç da gözükmesin.
                        if($kullanici['bandurumu']==0)
                        {
                           ?>
                           <li><a href="konu-ac">konu aç</a></li>
                           <?php
                        }
                     }
                     @$cikisbilgisi=$_GET['cikis'];
                     if ($cikisbilgisi==1) // cikis bilgisi 1 ise session'lar yok edilip çıkış yapılacak ve kullanıcı anasayfaya yönlendirilecek.
                     {
                        echo "<script type='text/javascript'> document.location = 'anasayfa'; </script>";
                        session_unset();
                        session_destroy();
                     }
                     ?>
                     <li><a href="profilim.php?kullanici=<?php echo @$_SESSION['girisyapankullanici']; ?>" id="kullanicihosgeldin"><?php echo @$_SESSION['hosgeldiniz'];?></a></li>
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
                     <button id="kategoriacbutonu" class="yanbar-kategoriler" onclick="goster()">Kategoriler</button>
                     <div id="mobilkategori" style="display: none;">
                        <ul>
                           <li><a href="index.php/?kategori=Siyaset">Siyaset</a></li>
                           <li><a href="index.php/?kategori=Ekonomi">Ekonomi</a></li>
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
            <div class="d-none d-sm-block col-12 col-sm-12  col-lg-3 "> <!-- d-none d-sm-block : hide on screens smaller than xs -->
               <div class="yanbar" >
                  <div id="yanbar-kategori" style="display:none;">
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('siyaset','gundem','enTaze','ekonomi','yasam','spor','genel','muzik','universite','anime')">Siyaset</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('ekonomi','gundem','enTaze','siyaset','yasam','spor','genel','muzik','universite','anime')">Ekonomi</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('yasam','gundem','enTaze','siyaset','ekonomi','spor','genel','muzik','universite','anime')">Yaşam</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('spor','gundem','enTaze','siyaset','ekonomi','yasam','genel','muzik','universite','anime')">Spor</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('muzik','gundem','enTaze','siyaset','ekonomi','yasam','spor','genel','universite','anime')">Müzik</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('universite','gundem','enTaze','siyaset','ekonomi','yasam','spor','genel','muzik','anime')">Üniversite</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('anime','gundem','enTaze','siyaset','ekonomi','yasam','spor','genel','muzik','universite')">Anime/Manga</button>
                     <button class="yanbar-kategoriler" onclick="kategorigostergizle('genel','gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime')">Genel</button>
                  </div>
                  <div class="yanbar-gundem"  id="gundem" >
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
                        $sorgu = $baglanti->prepare("SELECT * FROM konular ORDER BY mesajsayisi DESC LIMIT :limitt");
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
                  <div class="yanbar-gundem"  id="enTaze"       style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c;  text-align:left; line-height: 50px;  background-color:#eeeeee;">
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =20;
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
                           <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>"><?php print_r($row['konu_baslik']);?></a></li>
                        </ul>
                     </div>
                     <?php             
                        }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="siyaset"      style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
                        $konu_turu="Siyaset";
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="ekonomi"      style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
                        $konu_turu="Ekonomi";
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="yasam"        style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="spor"         style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="muzik"        style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="universite"   style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="anime"        style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div class="yanbar-gundem"  id="genel"        style="display:none;">
                     <div class="yanbar-gundem-baslik" >
                        <h3 style="font-family:var(--temayazitipi); font-weight:lighter; font-size:14px; color:#6c6c6c; text-align:left; line-height: 50px; background-color:#eeeeee;" >
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('gundem','enTaze','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">Gündem</button> 
                           <button class="yanbar-butonlar" onclick="kategorigostergizle('enTaze','gundem','siyaset','ekonomi','yasam','spor','muzik','universite','anime','genel')">En taze</button>
                           <button id="yanbar-kategori-butonu" class="yanbar-butonlar" onclick="kategori_goster()">Kategoriler</button>
                        </h3>
                     </div>
                     <?php
                        $limit =15;
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
                                    <li><a href="konular/<?php echo seo_link($row['konu_baslik']).'/'.$row['id'];?>">  <?php print_r($row['konu_baslik']);?></a></li>
                                 </ul>
                              </div><?php             
                           }
                        }
                        ?>
                  </div>
                  <div id="reklam-1" class="d-none">
                   <img alt="Reklam 1" src="resimler/reklam5.jpg">
                  </div>
               </div> 
            </div> 
                             
            <!---------------------------------------------- YANBAR BITIS ----------------------------------------------->


            <!---------------------------------------------- SABIT ALAN BASLANGIC ----------------------------------------------->
            <div class="col-12 col-sm-12 col-lg-7"> 
               <div class="main-maincont"> 
                  <div id="reklam-2" class="d-none">
                     <img alt="Reklam 2" src="resimler/reklam5.jpg">
                  </div>
                  <?php 
                     @include_once ($content);
                  ?>
               </div> 
            </div>
            <!---------------------------------------------- SABIT ALAN BITIS ----------------------------------------------->
           
            <!---------------------------------------------- SAGBAR BASLANGIC ----------------------------------------------->
            <div class="col-12 col-sm-12 col-lg-2"> 

               <div class="sagbar">

                  <div id="reklam-3" class="d-none">
                        <img alt="Reklam 3" src="resimler/reklam5.jpg">
                  </div>

                  <div class="sagbar-div">
                              <h5 class="sagbar-yazilar-baslik">Son Mesajlar</h5>
                              <?php 
                                    
                                    $limit=15;
                                    $sorgu = $baglanti->prepare("SELECT * FROM mesajlar ORDER BY mesajid DESC LIMIT :limitt");
                                    $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
                                    $sorgu->fetch(PDO::FETCH_ASSOC);
                                    $sorgu->execute();

                                    if ($sorgu->rowCount()>0)
                                    {
                                       foreach($sorgu as $row)
                                       {
                                          
                                         ?> 
                                         <div style="width: 100%; height: 30px;">
                                            <a href="konular/<?php echo seo_link($row['konu']);?>/<?php echo $row['id']; ?>" class="sagbar-yazilar"><?php echo $row['mesaj'];?></a>
                                         </div>
                                         
                                         <?php
                                         
                                       }
                                    }
                                 ?>
                  </div>

                  <div class="sagbar-div">
                                <h5 class="sagbar-yazilar-baslik">İstatistikler</h5>
                               <a class="sagbar-yazilar">Toplam konu sayısı :
                                 <?php 
                                    $sorgu = $baglanti->query("SELECT id FROM konular", PDO::FETCH_ASSOC);
                                    $toplamkonusayisi=$sorgu->rowCount();
                                    echo $toplamkonusayisi;
                                 ?>
                               </a>
                               <a class="sagbar-yazilar">Toplam mesaj sayısı :
                                 <?php
                                    $sorgu = $baglanti->query("SELECT mesaj FROM mesajlar", PDO::FETCH_ASSOC);
                                    $toplammesajsayisi=$sorgu->rowCount();
                                    echo $toplammesajsayisi;
                                 ?>
                               </a>
                               <a class="sagbar-yazilar">Toplam üye sayısı :
                                 <?php
                                    $sorgu = $baglanti->query("SELECT * FROM uyeler", PDO::FETCH_ASSOC);
                                    $toplamuyesayisi=$sorgu->rowCount();
                                    echo $toplamuyesayisi;
                                 ?>
                               </a>
                  </div>
               </div>
               
                <div id="reklam-4" class="d-none">
                   <img alt="Reklam 4" src="resimler/reklam5.jpg">
               </div>

            </div> 
            <!---------------------------------------------- SAGBAR BITIS ----------------------------------------------->
         </div>
      </div>
      <!---------------------------------------------- MAIN BITIS ----------------------------------------------->


      <!---------------------------------------------- FOOTER BASLANGIC ----------------------------------------------->
      <div class="container-fluid" > 
         <div class="row" style="" >
               <div class="col-12 col-sm-12 col-lg-3">
               </div>
                <div class="col-12 col-sm-12 col-lg-7">
                     <div class="main-footer-kapsayicisi">
                        <div id="reklam-5" class="d-none">
                           <img alt="Reklam 5" src="resimler/reklam5.jpg">
                        </div>
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
         // site ziyaretçilerinin ip adreslerini veritabanına kaydetme işlemi.
         /*
         $ziyaretciipadres = $_SERVER['REMOTE_ADDR'];
         $komutt = $baglanti->prepare("INSERT INTO ziyaretciler(ipadresi) VALUES(:ziyaretciipadres)");
         $komutt->bindParam('ziyaretciipadres',$ziyaretciipadres);
         $komutt->execute();
         */

         // giriş yapan kullanıcı admin veya moderatörse admin paneline gitme linki gözüksün.
         $kullanici = girisyapankullanici();
         if($kullanici['rutbe']=="admin" || $kullanici['rutbe']=="moderator")
         {?>
            <div id="adminpanelegit">
               <a href="adminpanel.php">Panele git</a>
            </div><?php
         }

         /*
            kullanıcı isimlerinin üyelerde, banlı üyelerde, moderatörlerde ve adminlerde hangi renkte vs.
            görüneceğini belirleyen kozmetik işlemler. çok da önemli bir şey değil.
         */
         $kullaniciadi = @$_SESSION['girisyapankullanici'];
         $bansorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi ");
         $bansorgusu->bindParam(':kullaniciadi',$kullaniciadi);
         $bansorgusu->fetch(PDO::FETCH_ASSOC);
         $bansorgusu->execute();
         if ($bansorgusu->rowCount()>0)
         {
            foreach ($bansorgusu as $row) 
            {
            }
            if ($row['bandurumu'] == 0)
            {
            }
            else if($row['bandurumu']==1)
            {?>
               <script type="text/javascript">
                  document.getElementById("kullanicihosgeldin").style.textDecoration="line-through";
                  document.getElementById("kullanicihosgeldin").style.color="#ff0000";
               </script><?php
            }
            if($row['rutbe']=="admin")
            {?>
               <script type="text/javascript">
                  document.getElementById("kullanicihosgeldin").style.color="#f27900";
               </script><?php
            }
            else if($row['rutbe']=="moderator")
            {?>
               <script type="text/javascript">
                  document.getElementById("kullanicihosgeldin").style.color="#1d9bf0";
               </script><?php
            }
         }
?>


   <script type="text/javascript" src="javascript/js.js"></script>
   </body>
</html>