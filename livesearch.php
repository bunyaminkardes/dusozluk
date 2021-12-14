<?php 
require_once("kutuphane.php");
error_reporting(0);

$q = $_REQUEST['q'];

$konuSorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_baslik LIKE '%$q%' ORDER BY mesajsayisi DESC LIMIT 6 ");
$konuSorgu->fetch(PDO::FETCH_ASSOC);
$konuSorgu->execute();
if($konuSorgu->rowCount()>0)
{
    foreach($konuSorgu as $row)
    {
        ?>
        <a style="text-decoration: none; color:#f2058f; font-weight: bold;" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id'];?>">
            konu : <?php echo $row['konu_baslik']; ?> 
        </a>
        <br/>
        <?php
    }
}
$kullaniciSorgu = $baglanti->prepare("SELECT * FROM uyeler WHERE kullaniciadi LIKE '%$q%' LIMIT 6 ");
$kullaniciSorgu->fetch(PDO::FETCH_ASSOC);
$kullaniciSorgu->execute();
if($kullaniciSorgu->rowCount()>0)
{
    foreach($kullaniciSorgu as $row)
    {
        ?>
        <a style="text-decoration: none; color:#282A35; font-weight: bold;" href="profil/<?php echo seo_link($row['kullaniciadi']);?>">
            kullanıcı : <?php echo $row['kullaniciadi']; ?> 
        </a>
        <br/>
        <?php
    }
}
if($konuSorgu->rowCount()==0 && $kullaniciSorgu->rowCount()==0) // konu veya kullanıcı tablolarında aranan kelimeye uygun kayıt yoksa
{
    echo "Sonuç Bulunamadı.";
}