<?php
require_once('kutuphane.php');
$kullanici = girisyapankullanici();
@$hedefkullaniciadi = $_GET['kullaniciadi'];
@$kullaniciidnumarasi = $_GET['id'];
date_default_timezone_set('Europe/Istanbul');
$islemTarihi = date("d-m-Y H:i");
@$kullaniciadi = $_POST['kullaniciadi'];

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
error_reporting(0);

$kullaniciSorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
$kullaniciSorgusu->bindParam(":kullaniciadi",$_POST['kullaniciadi'],PDO::PARAM_STR);
$kullaniciSorgusu->execute();

$profilLogSorgusu = $baglanti->prepare("SELECT * FROM profilloglari WHERE fail = :fail ORDER BY tarih DESC LIMIT 15");
$profilLogSorgusu->bindParam(":fail",$_POST['kullaniciadi'],PDO::PARAM_STR);
$profilLogSorgusu->execute();

$girisKayitlariSorgusu = $baglanti->prepare("SELECT * FROM girisloglari WHERE fail =:fail ORDER BY tarih DESC LIMIT 15");
$girisKayitlariSorgusu->bindParam(":fail",$_POST['kullaniciadi'],PDO::PARAM_STR);
$girisKayitlariSorgusu->execute();

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="css/duSozlukCss.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <style type="text/css">
        body
        {
            background-color: var(--temarengidivler);
        }
    </style>
    <title>DuSozluk Admin Paneli</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12" style="background-color: var(--temarengi); height: 50px;">
                <h3 id="adminpaneli-baslik">Dusozluk admin paneli</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12" style="background-color: var(--temarengidivler);">
                <span id="adminpaneli-girisyapankullanici-baslik">
                    <?php echo $kullanici['kullaniciadi']." (".$kullanici['rutbe'].")"." olarak giriş yaptınız."; ?><br>
                    <a href="anasayfa">Anasayfaya dönmek için tıklayın.</a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-4" style="padding-bottom: 40px;">
                <h3 id="adminpaneli-kullanici-islemleri-baslik">Kullanıcı işlemleri:</h3>
                <form method="POST" action="" autocomplete="off">
                    Kullanıcı ara : <input id="adminpaneli-kullanici-islemleri-input" type="text" name="kullaniciadi">
                    <input id="adminpaneli-kullanici-islemleri-buton" type="submit" name="ADMINPANEL_ARA_SUBMIT" value="Ara">
                </form>
            </div>
            <div class="col-12 col-sm-12 col-lg-4" style="padding-bottom: 40px;">
                <?php 
                if(isset($_POST['ADMINPANEL_ARA_SUBMIT'])) //ara butonuna basıldıysa
                {
                    if(isset($_POST['kullaniciadi'])) //kullanıcı adı boş girilmemişse
                    {
                        if($kullaniciSorgusu->rowCount()>0)
                        {
                            foreach($kullaniciSorgusu as $row)
                            {
                                if($row['pp']==null)
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><img height="120" src="resimler/yenikullanicipp.jpg"></h3><?php
                                }
                                else
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><img height="120" src="<?php echo $row['pp']; ?>" alt='Profil resmi yüklenirken hata oluştu.'></h3><?php
                                }
                                ?><h3 class="adminpaneli-kullanicisorgusu-basliklar">Kullanıcı adı : <?php echo $row['kullaniciadi']; ?></h3><?php
                                if($row['rutbe']=="admin" && $kullanici['rutbe']=="moderator")
                                {
                                    echo "<br>"."<h3 class='adminpaneli-kullanicisorgusu-basliklar'>Rütbesi sizden daha yüksek bir kullanıcıyı görüntülemektesiniz. sadece kısıtlı bilgiler gösterilmektedir.</h3>";
                                }
                                else
                                {
                                    ?>
                                    <h3 class="adminpaneli-kullanicisorgusu-basliklar">Üye no : <?php echo $row['id']; ?></h3>
                                    <h3 class="adminpaneli-kullanicisorgusu-basliklar">Mail adresi : <?php echo $row['mail']; ?></h3>
                                    <h3 class="adminpaneli-kullanicisorgusu-basliklar">Rütbe : <?php echo $row['rutbe']; ?></h3>
                                    <h3 class="adminpaneli-kullanicisorgusu-basliklar">Ban durumu : <?php echo $row['bandurumu']; ?></h3>
                                    <h3 style="padding-bottom:25px;" class="adminpaneli-kullanicisorgusu-basliklar">Kayıt Tarihi : <?php echo $row['kayitOlmaTarihi']; ?></h3>
                                    <?php
                                }
                            }
                        }
                        else
                        {
                            echo "Aranan kayıt bulunamadı.";
                        }
                    }
                }
                ?>
            </div>
            <div class="col-12 col-sm-12 col-lg-4" style="padding-bottom: 40px;">
                <div class="adminpaneli-kullanicisorgusu-islemler-kapsayici">
                    <?php
                    if(isset($_POST['ADMINPANEL_ARA_SUBMIT']))
                    {
                        if(isset($_POST['kullaniciadi']) && $kullaniciSorgusu->rowCount()>0)
                        {
                            ?><h3 class="adminpaneli-kullanicisorgusu-islemler-baslik">Buradan işlem yapabilirsiniz:</h3><?php
                            if($kullanici['rutbe']=='moderator' && $row['rutbe']=='uye') //moderatörler üyelere karşı banlama ve ban kaldırma işlemi yapabilsin.
                            {
                                if($row['bandurumu']==0)
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Kullanıcıyı banla</a></h3><?php
                                }
                                else
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=0">Banını kaldır</a></h3><?php
                                }
                            }
                            else if($kullanici['rutbe']=='moderator' && $row['rutbe']=='moderator') //moderatörler, diğer moderatörlere karşı işlem yapamasın.
                            {
                                echo "<h3 class='adminpaneli-kullanicisorgusu-basliklar'>Hedef kullanıcının rütbesi dolayısıyla işlem yapamazsınız.</h3>";
                            }
                            else if($kullanici['rutbe']=='admin' && $row['rutbe']=='uye' || $row['rutbe']=='moderator') //adminler üyelere veya moderatörlere karşı tam yetkiye sahip olsun.
                            {
                                if($row['bandurumu']==0)
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Kullanıcıyı banla</a></h3><?php
                                }
                                else
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=0">Banını kaldır</a></h3><?php
                                }
                                if($row['rutbe']=='uye')
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=1">Moderatör yap</a></h3><?php
                                }
                                else if($row['rutbe']=='moderator')
                                {
                                    ?><h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=0">Moderatörlüğünü al</a></h3><?php
                                }
                            }
                            else
                            {
                                echo "<h3 class='adminpaneli-kullanicisorgusu-basliklar'>Hedef kullanıcının rütbesi dolayısıyla işlem yapamazsınız.</h3>";
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-4" style="padding-bottom: 40px;">
                <?php
                if(isset($_POST['ADMINPANEL_ARA_SUBMIT']) && isset($_POST['kullaniciadi']) && $kullanici['rutbe']=='admin')
                {
                    if($kullaniciSorgusu->rowCount()>0)
                    {
                        echo "<h3 id='adminpaneli-kullanici-islemleri-baslik'>Bu kullanıcı hangi profillere bakmış:</h3>";
                        if($profilLogSorgusu->rowCount()>0)
                        {
                            foreach($profilLogSorgusu as $row)
                            {
                                ?>
                                <h3 class="adminpaneli-profilloglari"><?php echo $row['islem']."<br>"."işlem tarihi : ".$row['tarih']; ?></h3>
                                <?php
                            }
                        }
                        else
                        {
                            echo "<h3 class='adminpaneli-kullanicisorgusu-basliklar'>Kayıt bulunamadı.</h3>";
                        }
                    }
                }
                ?>
            </div>
            <div class="col-12 col-sm-12 col-lg-4" style="padding-bottom: 40px;">
                <?php
                if(isset($_POST['ADMINPANEL_ARA_SUBMIT']) && isset($_POST['kullaniciadi']) && $kullanici['rutbe']=='admin')
                {
                    if($kullaniciSorgusu->rowCount()>0)
                    {
                        echo "<h3 id='adminpaneli-kullanici-islemleri-baslik'>Son giriş kayıtları:</h3>";
                        if($girisKayitlariSorgusu->rowCount()>0)
                        {
                            foreach($girisKayitlariSorgusu as $row)
                            {
                                ?>
                                <h3 class="adminpaneli-profilloglari"><?php echo $row['islem']."<br>"."ip adresi : ".$row['ipadresi']."<br>"."işlem tarihi : ".$row['tarih']; ?></h3>
                                <?php
                            }
                        }
                        else
                        {
                            echo "<h3 class='adminpaneli-kullanicisorgusu-basliklar'>Kayıt bulunamadı.</h3>";
                        }
                    }
                }
                ?>
            </div>
            <div class="col-12 col-sm-12 col-lg-4" style="padding-bottom: 40px;">
            </div>
        </div>
    </div>
</body>
</html>





<?php // BANLAMA - BAN KALDIRMA - MODERATÖR YAPMA - MODERATÖRLÜK KALDIRMA İŞLEMLERİ
    if(isset($_GET['moderator']) && isset($_GET['id']) && @$_GET['moderator']==1) // moderatörlük verir.
    {
        $rutbe = 'moderator';
        $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:moderator WHERE id = :kullaniciidnumarasi");
        $guncellemesorgusu->bindParam(':moderator',$rutbe,PDO::PARAM_STR);
        $guncellemesorgusu->bindParam(':kullaniciidnumarasi',$kullaniciidnumarasi,PDO::PARAM_STR);
        $guncellemesorgusu->execute();
        if($guncellemesorgusu->rowCount()>0)
        {
            echo "<script> window.location.href = window.location.href </script>";
        }
    }
    if(isset($_GET['moderator']) && isset($_GET['id']) && @$_GET['moderator']==0) // moderatörlüğü kaldırır.
    {
        $rutbe = 'uye';
        $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET rutbe=:uye WHERE id = :kullaniciidnumarasi");
        $guncellemesorgusu->bindParam(':uye',$rutbe,PDO::PARAM_STR);
        $guncellemesorgusu->bindParam(':kullaniciidnumarasi',$kullaniciidnumarasi,PDO::PARAM_STR);
        $guncellemesorgusu->execute();
        if($guncellemesorgusu->rowCount()>0)
        {
            echo "<script> window.location.href = window.location.href </script>";
        }
    }
    if (isset($_GET['ban']) && isset($_GET['id']) && @$_GET['ban'] == 1)  // banlama işlemi yapar.
    {
        $bandurumu = 1;
        $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE id = :kullaniciidnumarasi");
        $guncellemesorgusu->bindParam(':bandurumu',$bandurumu,PDO::PARAM_INT);
        $guncellemesorgusu->bindParam(':kullaniciidnumarasi',$kullaniciidnumarasi,PDO::PARAM_STR);
        $guncellemesorgusu->execute();

        $islem = $kullanici['kullaniciadi']." "."adlı moderatör"." ".$_GET['kullaniciadi']." "."adlı kullanıcıyı banladı.";
        $logsorgusu = $baglanti->prepare("INSERT INTO moderatorloglari(islem,tarih) VALUES (:islem,:tarih)");
        $logsorgusu->bindParam(":islem",$islem,PDO::PARAM_STR);
        $logsorgusu->bindParam(":tarih",$islemTarihi,PDO::PARAM_STR);
        $logsorgusu->execute();

        if($guncellemesorgusu->rowCount()>0 && $logsorgusu->rowCount()>0)
        {
            echo "<script> window.location.href = window.location.href </script>";
        }
    }
    if (isset($_GET['ban']) && isset($_GET['id']) && @$_GET['ban'] == 0) // ban kaldırır.
    {
        $bandurumu = 0;
        $guncellemesorgusu = $baglanti->prepare("UPDATE uyeler SET bandurumu=:bandurumu WHERE id = :kullaniciidnumarasi");
        $guncellemesorgusu->bindParam(':bandurumu',$bandurumu,PDO::PARAM_INT);
        $guncellemesorgusu->bindParam(':kullaniciidnumarasi',$kullaniciidnumarasi,PDO::PARAM_STR);
        $guncellemesorgusu->execute();

        $islem = $kullanici['kullaniciadi']." "."adlı"." ".$kullanici['rutbe']." ".$_GET['kullaniciadi']." "."adlı kullanıcının banını kaldırdı.";
        $logsorgusu = $baglanti->prepare("INSERT INTO moderatorloglari(islem,tarih) VALUES (:islem,:tarih)");
        $logsorgusu->bindParam(":islem",$islem,PDO::PARAM_STR);
        $logsorgusu->bindParam(":tarih",$islemTarihi,PDO::PARAM_STR);
        $logsorgusu->execute();

        if($guncellemesorgusu->rowCount()>0 && $logsorgusu->rowCount()>0)
        {
            echo "<script> window.location.href = window.location.href </script>";
        }
    }
?>