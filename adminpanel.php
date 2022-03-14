<?php
    require_once('kutuphane.php');
    $kullanici = girisyapankullanici();
    @$hedefkullaniciadi = $_GET['kullaniciadi'];
    @$kullaniciidnumarasi = $_GET['id'];
    date_default_timezone_set('Europe/Istanbul');
    $islemTarihi = date("d-m-Y H:i");
    @$kullaniciadi = $_POST['kullaniciadi'];

    error_reporting(0);


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

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="css/duSozlukCss.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">


<style type="text/css">
    body
    {
        background-color: var(--temarengidivler);
    }
    #adminpaneli-baslik
    {
        line-height: 50px;
        padding-left:5px;
        font-size:16px;
        color:#FFF;
        font-family: var(--temayazitipi);
    }
    #adminpaneli-girisyapankullanici-baslik
    {
        display: block;
        padding-top:5px;
        padding-bottom:25px;
    }
    #adminpaneli-kullanici-islemleri-baslik
    {
        font-size:16px;
        color:black;
        padding-bottom:10px;
    }
    #adminpaneli-kullanici-islemleri-input
    {
        width: 100%;
        height: 30px;
        display: block;
    }
    #adminpaneli-kullanici-islemleri-buton
    {
        width:100px;
        height: 30px;
        margin-top:10px;
        margin-bottom:20px;
        color:#FFFFFF;
        font-family: var(--temayazitipi);
        border:none;
        background-color: var(--temarengi);
        display: block;
        float:right;
    }
    .adminpaneli-kullanicisorgusu-basliklar
    {
        font-size:14px;
        font-family: var(--temayazitipi);
    }
    .adminpaneli-kullanicisorgusu-islemler-kapsayici
    {
    }
    .adminpaneli-kullanicisorgusu-islemler-baslik
    {
        font-size:16px;
        color:black;
        padding-bottom:10px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12" style="background-color: var(--temarengi); height: 50px;">
            <h3 id="adminpaneli-baslik">Dusozluk admin paneli</h3>
        </div>
        <div class="col-12 col-sm-12 col-lg-12" style="background-color: var(--temarengidivler);">
            <span id="adminpaneli-girisyapankullanici-baslik">
                <?php echo $kullanici['kullaniciadi']." (".$kullanici['rutbe'].")"." olarak giriş yaptınız."; ?><br>
                <a href="anasayfa">Anasayfaya dönmek için tıklayın.</a>
            </span>
                <div class="row">
                    <div class="col-12 col-sm-12 col-lg-4">
                        <h3 id="adminpaneli-kullanici-islemleri-baslik">Kullanıcı işlemleri:</h3>
                        <form method="POST" action="" autocomplete="off">
                            Kullanıcı ara : <input id="adminpaneli-kullanici-islemleri-input" type="text" name="kullaniciadi">
                            <input id="adminpaneli-kullanici-islemleri-buton" type="submit" name="kullaniciara" value="Ara">
                        </form>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-4">
                        <?php
                            if(isset($_POST['kullaniciara']) || isset($_GET['kullaniciadi']))
                            {
                                $kullanicisorgusu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi = :kullaniciadi");
                                if(isset($_POST['kullaniciara']))
                                {
                                    $kullanicisorgusu->bindParam(":kullaniciadi",$kullaniciadi,PDO::PARAM_STR);
                                }
                                else if(isset($hedefkullaniciadi))
                                {
                                    $kullanicisorgusu->bindParam(":kullaniciadi",$hedefkullaniciadi,PDO::PARAM_STR);
                                }
                                
                                $kullanicisorgusu->execute();
                                if($kullanicisorgusu->rowCount()>0)
                                {
                                    foreach($kullanicisorgusu as $row)
                                    {
                                        ?>
                                        <?php 
                                        if($row['pp']==null)
                                        {
                                            ?>
                                            <h3 class="adminpaneli-kullanicisorgusu-basliklar"><img height="120" src="resimler/yenikullanicipp.jpg"></h3>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <h3 class="adminpaneli-kullanicisorgusu-basliklar"><img height="120" src="<?php echo $row['pp']; ?>"></h3>
                                            <?php
                                        }
                                        ?>
                                        
                                        <h3 class="adminpaneli-kullanicisorgusu-basliklar">Kullanıcı adı : <?php echo $row['kullaniciadi']; ?></h3>
                                        <?php 

                                        if($row['rutbe']=="admin" && $kullanici['rutbe'] == "moderator")
                                        {

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

                                        ?>
                                        
                                        <?php
                                    }
                                }
                                else
                                {
                                    echo "Aranan kayıt bulunamadı.";
                                }
                            }
                        ?>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-4">
                        <?php 
                            if(isset($_POST['kullaniciara']) || isset($hedefkullaniciadi) && $kullanicisorgusu->rowCount()>0)
                            {
                                ?>
                                <div class="adminpaneli-kullanicisorgusu-islemler-kapsayici">
                                    <h3 class="adminpaneli-kullanicisorgusu-islemler-baslik">Buradan işlem yapabilirsiniz:</h3>
                                    <?php
                                        if($row['bandurumu']==0) // kullanıcı banlı değilse
                                        {
                                            if($kullanici['rutbe']=="moderator")
                                            {
                                                if($row['rutbe']=="admin" || $row['rutbe']=="moderator") // moderatörler adminleri veya başka moderatörleri banlayamaz.
                                                {

                                                }
                                                else
                                                {
                                                    ?>
                                                    <h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Kullanıcıyı banla</a></h3>
                                                    <?php
                                                }
                                            }
                                            else if($kullanici['rutbe']=="admin") // adminler diğer adminleri banlayamaz.
                                            {
                                                if($row['rutbe']=="admin")
                                                {

                                                }
                                                else
                                                {
                                                    ?>
                                                    <h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=1">Kullanıcıyı banla</a></h3>
                                                    <?php
                                                }
                                            }
                                        }
                                        else if($row['bandurumu']==1)
                                        {
                                            ?>
                                            <h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&ban=0">Banını kaldır</a></h3>
                                            <?php
                                        }
                                        if($row['rutbe']=="uye" && $kullanici['rutbe']=="admin") // üyeleri sadece adminler moderatör yapabilir.
                                        {
                                            ?>
                                            <h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=1">Moderatör yap</a></h3>
                                            <?php
                                        }

                                        if($row['rutbe']=="moderator" && $kullanici['rutbe'] =="admin") // moderatörlükleri sadece adminler geri alabilir.
                                        {
                                            ?>
                                            <h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="adminpanel.php?id=<?php echo $row['id']; ?>&kullaniciadi=<?php echo $row['kullaniciadi']; ?>&moderator=0">Moderatörlüğünü al</a></h3>
                                            <?php
                                        }

                                        if($kullanici['rutbe'] == "admin" && $row['rutbe'] == "uye" || $row['rutbe'] == "moderator") // sadece adminler üye veya moderatörleri sistemden silebilir.
                                        {
                                            ?>
                                            <h3 class="adminpaneli-kullanicisorgusu-basliklar"><a href="">Üyeyi sistemden sil</a></h3>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
        </div>
    </div>
</div>


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