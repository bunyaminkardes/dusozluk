<?php
require('kutuphane.php');

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
                     <a><?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>"."mail : ".$row['mail']."<br/>";?></a>
                     <a>Ban durumu : <?php echo $row['bandurumu']; ?></a>
                     <br/>
                     <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Banla</a></button>
                     <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=1">Moderatör yap</a></button>
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
                     <a><?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>"."mail : ".$row['mail']."<br/>";?></a>
                     <a>Ban durumu : <?php echo $row['bandurumu']; ?></a>
                     <br/>
                     <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Banla</a></button>
                     <button class="adminpanel-buton"><a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=0">Moderatörlüğünü al</a></button>
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
                     <a><?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>"."mail : ".$row['mail']."<br/>";?></a>
                     <a>Ban durumu : <?php echo $row['bandurumu']; ?></a>
                     <br/>
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

   @$hedefkullaniciadi = $_GET['kullaniciadi'];

   if(isset($_GET['moderator']) && @$_GET['moderator']==1)
   {
      $rutbe = 'moderator';
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:moderator WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':moderator',$rutbe);
      $guncellemesorgusu->bindParam(':kullaniciadi',$hedefkullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }

    if(isset($_GET['moderator']) && @$_GET['moderator']==0)
    {
      $rutbe = 'uye';
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:uye WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':uye',$rutbe);
      $guncellemesorgusu->bindParam(':kullaniciadi', $hedefkullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    } 

   if (isset($_GET['ban']) && @$_GET['ban'] == 1)
   {
      $bandurumu = 1;
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':bandurumu',$bandurumu);
      $guncellemesorgusu->bindParam(':kullaniciadi',$hedefkullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }

    if (isset($_GET['ban']) && @$_GET['ban'] == 0)
    {
      $bandurumu = 0;
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':bandurumu',$bandurumu);
      $guncellemesorgusu->bindParam(':kullaniciadi',$hedefkullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }
?>