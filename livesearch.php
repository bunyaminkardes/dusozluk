<?php 
require_once("kutuphane.php");
error_reporting(0);

$q = $_REQUEST['q'];


$konuSorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_baslik LIKE '%$q%' ORDER BY rand() DESC LIMIT 6 ");
$konuSorgu->fetch(PDO::FETCH_ASSOC);


$kullaniciSorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi LIKE '%$q%' ORDER BY rand() LIMIT 6 ");
$kullaniciSorgu->fetch(PDO::FETCH_ASSOC);


$konuSorgu->execute();
$kullaniciSorgu->execute();



if($konuSorgu->rowCount()>0)
{
    foreach($konuSorgu as $row)
    {
        ?>
        <a style="font-size:13px; text-decoration: none; color:#f2058f; font-weight: bold;  padding-left: 5px;" >Konu : </a>
        <a style="font-size:13px; text-decoration: none; color:#f2058f; " href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id'];?>"><?php echo htmlentities($row['konu_baslik']);?></a>
        <br/>
        <?php
    }
}


if($kullaniciSorgu->rowCount()>0)
{
    foreach($kullaniciSorgu as $row)
    {
        ?>
        <a style="font-size:13px; text-decoration: none; color:#282A35; font-weight: bold;  padding-left: 5px;" >Kullanıcı : </a>
        <?php 
        if(isset($_SESSION['girisyapankullanici']))
        {
            ?>
            <a style="font-size:13px; text-decoration: none; color:#282A35; " href="profil/<?php echo seo_link($row['kullaniciadi']);?>"><?php echo htmlentities($row['kullaniciadi']);?></a>
            <?php
        }
        else
        {
            ?>
            <a href="giris.php?pq=0" style="font-size:13px; text-decoration: none; color:#282A35;"><?php echo htmlentities($row['kullaniciadi']);?></a>
            <?php
        }
        ?>
        <br/>
        <?php
    }
}

if($konuSorgu->rowCount()==0 && $kullaniciSorgu->rowCount()==0) // konu veya kullanıcı tablolarında aranan kelimeye uygun kayıt yoksa
{
    echo "Sonuç Bulunamadı.";
}