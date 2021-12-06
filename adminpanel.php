<?php

require('baglanti.php'); 
require('kutuphane.php');
$kullaniciadi = @$_SESSION['girisyapankullanici'];
     
if(!isset($kullaniciadi))
{
   echo "<script type='text/javascript'> document.location = '404.php'; </script>"; //giriş yapmayan kişilerin url kısmından vs. panele kaçak yoldan girmesini engellemek için giriş yapılmış mı diye bir ön kontrol yapalım.
}

$panelsorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi ");
$panelsorgusu->bindParam(':kullaniciadi',$kullaniciadi);
$panelsorgusu->fetch(PDO::FETCH_ASSOC);
$panelsorgusu->execute();
if($panelsorgusu->rowCount()>0)
{
   foreach($panelsorgusu as $row)
   {
      $GLOBALS['kullanici_rutbe'] = $row['rutbe']; 
   }
   if ($row['rutbe']=='admin' || $row['rutbe']=='moderator') 
   {
      //giriş yapan kullanıcı adminse veya moderatörse ekstra bir şey yapmaya gerek yok.
   }
   else //giriş yapan kullanıcı rütbesi admin veya moderatör değilse kullanıcıyı 404.php sayfasına yönlendirelim.
   {
      echo "<script type='text/javascript'> document.location = '404.php'; </script>";
   }
}
?>


<!DOCTYPE html>
<html>
<head>
   <title>Admin Paneli</title>
   <meta charset="utf-8">
   <link rel="stylesheet" type="text/css" href="css/duSozlukCss.css">
   <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
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
                  $rutbekosul = 'uye';
                  $bandurumukosul = 0;
                  $sorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE rutbe= :uye AND bandurumu= :bandurumu");
                  $sorgu->bindParam(':uye',$rutbekosul);
                  $sorgu->bindParam(':bandurumu',$bandurumukosul);
                  $sorgu->fetch(PDO::FETCH_ASSOC);
                  $sorgu->execute();
                  if($sorgu->rowCount()>0)
                  {
                     foreach($sorgu as $row)
                     {
                        ?>
                        <a>
                           <?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>"."mail : ".$row['mail']."<br/>";?>
                        </a>
                        <a>
                           Ban durumu : <?php echo $row['bandurumu']; ?>
                        </a>
                        <br/>
                        <button class="adminpanel-buton"> 
                           <a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Banla</a>
                        </button>
                        <button class="adminpanel-buton"> 
                           <a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=1">Moderatör yap</a>
                        </button>
                        <br/>
                        <hr/>
                        <?php
                     }
                  }
               ?>
         </div> 
         <!---------------------- ÜYELER LİSTESİ BİTİŞ---------------------->


          <!-- MODERATÖRLER LİSTESİ BAŞLANGIÇ -->
         <div class="col-12 col-sm-12 col-lg-3" > 
            <h3 class="adminpanel-basliklar">- moderatörler listesi -</h3>
            <?php 
               $rutbekosul = 'moderator';
               $sorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE rutbe=:moderator ");
               $sorgu->bindParam(':moderator',$rutbekosul);
               $sorgu->fetch(PDO::FETCH_ASSOC); 
               $sorgu->execute();
               if($sorgu->rowCount()>0)
               {
                  foreach($sorgu as $row)
                  {
                     ?>
                     <a>
                        <?php echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>"."mail : ".$row['mail']."<br/>";?>
                     </a>
                     <a>
                        Ban durumu : <?php echo $row['bandurumu']; ?>
                     </a>
                     <br/>
                     <?php 
                     // giriş yapan kullanıcı admin değilse moderatördür, moderatörlerin birbirini banlayamaması lazım.
                     $kullanici = girisyapankullanici();
                     if($kullanici['rutbe']!="admin")
                     {
                     }
                     else
                     {
                        ?>
                        <button class="adminpanel-buton">
                           <a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Banla</a>
                        </button>
                        <button class="adminpanel-buton">
                           <a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=0">Moderatörlüğünü al</a>
                        </button>
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
          <!-- MODERATÖRLER LİSTESİ BİTİŞ -->


          <!-- BANLILAR LİSTESİ BAŞLANGIÇ -->
          <div class="col-12 col-sm-12 col-lg-3"> 
            <h3 class="adminpanel-basliklar">- banlılar listesi -</h3>
            <?php 
               $bandurumu = 1;
               $sorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE bandurumu= :bandurumu");
               $sorgu->bindParam(':bandurumu',$bandurumu);
               $sorgu->fetch(PDO::FETCH_ASSOC);
               $sorgu->execute();
               if($sorgu->rowCount()>0)
               {
                  foreach($sorgu as $row)
                  {
                     ?>
                     <a>
                        <?php  
                           echo "kullanıcı adı : ".$row['kullaniciadi']."<br/>"."mail : ".$row['mail']."<br/>";
                        ?>
                     </a>
                     <a>
                        Ban durumu : <?php echo $row['bandurumu']; ?>
                     </a>
                     <br/>
                     <button class="adminpanel-buton">
                        <a href="adminpanel.php?kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=0">Ban kaldır</a>
                     </button>      
                     <br/>
                     <hr/>
                     <?php
                  }
               }
            ?>
          </div> 
          <!-- BANLILAR LİSTESİ BİTİŞ -->
          
      </div>
   </div>
</body>
</html>





<?php 

   // BANLAMA - BAN KALDIRMA - MODERATÖR YAPMA - MODERATÖRLÜK KALDIRMA İŞLEMLERİ
   if(isset($_GET['moderator']) && @$_GET['moderator']==1)
   {
      @$kullaniciadi = $_GET['kullaniciadi'];
      $rutbe = 'moderator';
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:moderator WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':moderator',$rutbe);
      $guncellemesorgusu->bindParam(':kullaniciadi',$kullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }


    if(isset($_GET['moderator']) && @$_GET['moderator']==0)
    {
      @$kullaniciadi = $_GET['kullaniciadi'];
      $rutbe = 'uye';
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:uye WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':uye',$rutbe);
      $guncellemesorgusu->bindParam(':kullaniciadi', $kullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    } 


   if (isset($_GET['ban']) && @$_GET['ban'] == 1)
   {
      @$kullaniciadi = $_GET['kullaniciadi'];
      $bandurumu = 1;
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':bandurumu',$bandurumu);
      $guncellemesorgusu->bindParam(':kullaniciadi',$kullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }


    if (isset($_GET['ban']) && @$_GET['ban'] == 0)
    {
      @$kullaniciadi = $_GET['kullaniciadi'];
      $bandurumu = 0;
      $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE kullaniciadi = :kullaniciadi");
      $guncellemesorgusu->bindParam(':bandurumu',$bandurumu);
      $guncellemesorgusu->bindParam(':kullaniciadi',$kullaniciadi);
      $guncellemesorgusu->execute();
      echo "<script> setTimeout(function(){ window.location.href='adminpanel.php'; }, 1000); </script>";
    }
?>