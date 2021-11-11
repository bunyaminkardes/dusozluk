<?php 

require_once("baglanti.php");
require_once("kutuphane.php");

$q = $_REQUEST['q']; // yazdığımız ajax fonksiyonu çalıştığında buraya bir q değişkeni gönderilecek. onu depolayalım.
$hint = "";



$sorgu = $baglanti->prepare("SELECT kullaniciadi FROM uyeler");
$sorgu->fetch(PDO::FETCH_ASSOC);
$sorgu->execute();
if($q !== "") //
{
    $q = strtolower($q); // strtolower fonksiyonu string bir ifadenin harflerini küçük harfe çevirir.
    $len = strlen($q);   // strlen fonksiyonu string bir ifadenin uzunluğunu return eder.
    foreach($sorgu as $row) 
    {
        $row = array_unique($row); // array_unique fonksiyonu dizide tekrar eden değerleri atarak tekrar etmeyen halini return eder.
        $row = implode(",",$row);  // implode fonksiyonu dizideki elemanları string hale getirip return eder. 
        if(stristr($q, substr($row, 0, $len))) // substr fonksiyonu string bir ifadenin istenilen bir parçasını return eder. stristr ise string bir ifadenin içinde istenilen stringi arar.
        {
            if($hint==="")
            {
                $hint=$row;
            }
            else
            {
                //burada echo işlemi yapma.
                //$hint .="<br/>$name"; //$hint değişkenini $name değişkeni ile birleştiriyor.
            }
        }
    }
}
 
 
 if($hint === "")
 {
    echo ("sonuç bulunamadı");
 }
 else
 { 

        ?>
        <a style="text-decoration: none; color:#f2058f; font-weight: bold; padding-left:5px;" href="profil/<?php echo seo_link($hint);?>">kullanıcı : <?php echo $hint; ?> </a>
        <br/>
        <?php
 }







?>