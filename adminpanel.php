<?php
require_once('kutuphane.php');

$kullanici = girisyapankullanici();

if(!isset($kullanici['kullaniciadi']))
{
   header("location: 404.php"); 
   //giriş yapmayan kişilerin url kısmından vs. panele kaçak yoldan girmesini engellemek için giriş yapılmış mı diye bir ön kontrol yapalım.
   exit();
}
if($kullanici['rutbe']=='admin' || $kullanici['rutbe']=='moderator')
{
   // giriş yapan kullanıcı admin veya moderatörse ekstra bir şey yapmaya gerek yok.
}
else
{
   header("location: 404.php");
   // giriş yapan kullanıcı rütbesi admin veya moderatör değilse kullanıcı 404.php sayfasına yönlendirilsin.
   exit();
}
?>


<!DOCTYPE html>
<html>
<head>
   <title>Admin Paneli</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
   <meta charset="utf-8">
   <link rel="stylesheet" type="text/css" href="css/duSozlukCss.css">
   <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
   <link rel='shortcut icon' type='image/x-icon' href='resimler/favicon.png'/>
</head>
<body>
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-sm-12 col-lg-3">
            <a href="index.php">Anasayfaya dönmek için tıkla</a>
         </div>
         <!---------------------- ÜYELER LİSTESİ BAŞLANGIÇ ---------------------->
         <div class="col-12 col-sm-12 col-lg-3"> 
            <h3 class="adminpanel-basliklar">- üyeler listesi -</h3>
            <?php
               $kullanici = kullanicilar();
               foreach($kullanici as $row)
               {
                  if($row['rutbe']=="uye" && $row['bandurumu']==0)
                  {
                     ?>
                     <a><?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>";?></a>
                     <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Banla -</a></button>
                     <?php
                     if($row['rutbe']=="admin") // admin dışında kimse üyelere moderatörlük veremesin.
                     {
                        ?>
                        <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=1">Moderatör yap</a></button>
                        <?php
                     }
                     ?>
                     <br/>
                     <hr/>
                     <?php
                  }
               }
            ?>
         </div> 
         <!---------------------- ÜYELER LİSTESİ BİTİŞ---------------------->
         <!---------------------- MODERATÖRLER LİSTESİ BAŞLANGIÇ ---------------------->
         <div class="col-12 col-sm-12 col-lg-3" > 
            <h3 class="adminpanel-basliklar">- moderatörler listesi -</h3>
            <?php
               $kullanici = kullanicilar();
               foreach($kullanici as $row)
               {
                  if($row['rutbe']=="moderator" && $row['bandurumu']==0)
                  {
                     ?>
                     <a><?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>";?></a>
                     <?php
                     if($row['rutbe'] == "admin") // moderatörler birbirini banlayamasın ve moderatörlüğünü alamasın.
                     {
                        ?>
                        <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Banla -</a></button>
                        <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=0">Moderatörlüğünü al</a></button>
                        <?php
                     }
                     ?>
                     <br/>
                     <hr/>
                     <?php
                  }
               }
            ?>
         </div> 
         <!---------------------- MODERATÖRLER LİSTESİ BİTİŞ ---------------------->
         <!---------------------- BANLILAR LİSTESİ BAŞLANGIÇ ---------------------->
         <div class="col-12 col-sm-12 col-lg-3"> 
            <h3 class="adminpanel-basliklar">- banlılar listesi -</h3>
            <?php
               $kullanici = kullanicilar();
               foreach($kullanici as $row)
               {
                  if($row['bandurumu']==1)
                  {
                     ?>
                     <a><?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>";?></a>
                     <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=0">Ban kaldır</a></button>
                     <br/>
                     <hr/>
                     <?php
                  }
               }
            ?>
         </div> 
         <!---------------------- BANLILAR LİSTESİ BİTİŞ ---------------------->
      </div>
   </div>
</body>
</html>


<?php 
   // BANLAMA - BAN KALDIRMA - MODERATÖR YAPMA - MODERATÖRLÜK KALDIRMA İŞLEMLERİ

   $kullanici = girisyapankullanici();
   @$hedefkullaniciadi = $_GET['kullaniciadi'];
   date_default_timezone_set('Europe/Istanbul');
   $islemTarihi = date("d-m-Y H:i");

   if(isset($_GET['moderator']) && @$_GET['moderator']==1) // moderatörlük verir.
   {
      $rutbe = 'moderator';
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:moderator WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':moderator',$rutbe);
      $guncellemesorgusu->bindParam(':kullaniciadi',$hedefkullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }

    if(isset($_GET['moderator']) && @$_GET['moderator']==0) // moderatörlüğü kaldırır.
    {
      $rutbe = 'uye';
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:uye WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':uye',$rutbe);
      $guncellemesorgusu->bindParam(':kullaniciadi', $hedefkullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    } 

   if (isset($_GET['ban']) && @$_GET['ban'] == 1)  // banlama işlemi yapar.
   {
      $bandurumu = 1;
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':bandurumu',$bandurumu);
      $guncellemesorgusu->bindParam(':kullaniciadi',$hedefkullaniciadi);
      $guncellemesorgusu->execute();

      $islem = $kullanici['kullaniciadi']." "."adlı moderatör"." ".$_GET['kullaniciadi']." "."adlı kullanıcıyı banladı.";

      $logsorgusu = $baglanti->prepare("INSERT INTO moderatorLoglari(islem,tarih) VALUES (:islem,:tarih)");
      $logsorgusu->bindParam(":islem",$islem);
      $logsorgusu->bindParam(":tarih",$islemTarihi);
      $logsorgusu->execute();


      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }

    if (isset($_GET['ban']) && @$_GET['ban'] == 0) // ban kaldırır.
    {
      $bandurumu = 0;
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':bandurumu',$bandurumu);
      $guncellemesorgusu->bindParam(':kullaniciadi',$hedefkullaniciadi);
      $guncellemesorgusu->execute();

      $islem = $kullanici['kullaniciadi']." "."adlı moderatör"." ".$_GET['kullaniciadi']." "."adlı kullanıcının banını kaldırdı.";

      $logsorgusu = $baglanti->prepare("INSERT INTO moderatorLoglari(islem,tarih) VALUES (:islem,:tarih)");
      $logsorgusu->bindParam(":islem",$islem);
      $logsorgusu->bindParam(":tarih",$islemTarihi);
      $logsorgusu->execute();

      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }
?>