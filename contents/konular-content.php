<!DOCTYPE html>
<html>
<head>
  <title>
      <?php
        $id = $_GET['id'];
        $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE id = :id");
        $sorgu->bindParam(':id',$id);
        $sorgu->fetch(PDO::FETCH_ASSOC);
        $sorgu->execute();
        if ($sorgu->rowCount()>0)
        {
          foreach($sorgu as $row)
          {
            echo $row['konu_baslik']." - düzce sözlük";
          }
        }
      ?> 
    </title>
</head>
<body>
</body>
</html>


<?php
   session_start();
   $id = $_GET['id'];
   $konuid = $_GET['konuid']; // eğer silinme işlemi varsa ilgili konu id'si get edilerek depolanacak.
   $girisyapankullanici=$_SESSION['girisyapankullanici'];
   $mesajsil = $_GET['mesaj'];


   $sorgu=$baglanti->prepare("SELECT * FROM konular WHERE id=:id");
   $sorgu->bindParam(':id',$id);
   $sorgu->fetch(PDO::FETCH_ASSOC);
   $sorgu->execute();
   if ($sorgu->rowCount()>0)
   {
     	foreach($sorgu as $row)
     	{
     		?> 
          <div id="konu">
              <h1 id="konular-yazar-baslik"><?php print_r($row['konu_baslik']);?>  
                <?php 
                  //giriş yapan kullanıcı sadece admin veya moderatörse konuyu sil butonu gözüksün.
                  $kullanici = girisyapankullanici();
                if($kullanici['rutbe'] == "admin" || $kullanici['rutbe'] == "moderator")
                  {
                    ?>
                    <button id="konular-konu-sil">
                      <a href="konular/<?php echo seo_link($row['konu_baslik'])."/"; echo $row['id'];?>?konuid=<?php echo $row['id']; ?>">
                        konuyu sil
                      </a>
                    </button> 
                    <?php
                  }
                ?>
                <input readonly autofocus class="focus"> <!-- focus -->
              </h1>
              <br/>
              <p id="konular-yazar-mesaj"><?php print_r(htmlentities($row['konu_icerik']));?></p>
              <br/>
          </div>

          <div style="float:left; position: relative; width: 100%; ">
            <div id="konular-yazar-kimlik">
                <div class="konular-yazar-like-dislike">
                  <?php
                      $likedislikekomut = $baglanti->prepare("SELECT * FROM konular WHERE id = $id");
                      $likedislikekomut->fetch();
                      $likedislikekomut->execute();
                      $gecerlilikesayisi = $row['likesayisi'];
                      $gecerlidislikesayisi = $row['dislikesayisi'];
                      if($likedislikekomut->rowCount()>0)
                      {
                        foreach($likedislikekomut as $row)
                        {
                           ?>
                             <span style="display:inline-block;">
                              <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?like=<?php echo true; ?>">Like : <?php echo $row['likesayisi']; ?></a> /
                              <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?dislike=<?php echo true; ?>">Dislike : <?php echo $row['dislikesayisi']; ?></a>
                             </span>
                           <?php                           
                        }
                      }
                  ?>
                  <?php 
                      $like = $_GET['like'];
                      $dislike = $_GET['dislike'];
                      if(isset($like))
                      {
                        $likedislikeguncellemesorgusu = $baglanti->prepare("UPDATE konular SET likesayisi= $gecerlilikesayisi+1 WHERE id = $id");
                        $likedislikeguncellemesorgusu->execute();
                        echo "<script> window.history.back(); </script>";
                      }
                      if(isset($dislike))
                      {
                        $likedislikeguncellemesorgusu2 = $baglanti->prepare("UPDATE konular SET dislikesayisi= $gecerlidislikesayisi+1 WHERE id = $id");
                        $likedislikeguncellemesorgusu2->execute();
                        echo "<script> window.history.back(); </script>";                          
                      }
                  ?>
                </div>
                <h3 id="konular-yazar-kimlik-kimlik"><a href="profil/<?php echo seo_link($row['user']);?>"><?php echo  $row['tarih']." "." "."-"." ".$row['user']; ?></a></h3>
            </div>
          </div>  
          <div id="konular-yazar-ghost" style="width: 100%; height: 40px; background-color: #eeeeee; float: left;">
          </div> 
      <?php
    }
  }
      ?>




<?php
  ########################## SAYFALAMA (PAGINATION) İŞLEMLERİ ##########################
  $id = $_GET['id'];
  $hangisayfadayim = intval($_GET['id']); //hangi sayfada olduğumuzu bilmemiz gerekecek.
  $sayfaid=1;
  $sayfaid = intval($_GET['sayfaid']);
  if(!$sayfaid)
  {
    $sayfaid =1;
  }
  if($sayfaid>1) // çok garip bir bug olduğundan dolayı bu if'i ekledim. sayfa 2 ve sonrasında yazar kimliği ve ghost div duruyordu gereksiz yere.
  {
    echo "<script> document.getElementById('konular-yazar-kimlik').style.display='none'; </script>";
  }
  $kayitsorgusu = $baglanti->query("SELECT * FROM mesajlar WHERE id = '$hangisayfadayim'");
  $toplamverisayisi=$kayitsorgusu->rowCount(); // mesajlar tablosunda kaç adet kayıt varmış onu bulacağız. toplam kayıt sayısını da sayfa başına gösterilecek kayıt sayısına böldüğümüzde sayfa sayısını belirleriz.
  $sayfalimiti = 8; //sayfa başına göstermek istediğimiz kayıt sayısını belirleyelim.
  $sayfasayisi = ceil($toplamverisayisi/$sayfalimiti);
  //$goster = $hangisayfadayim*$sayfalimiti-$sayfalimiti; //sayfalama mantığının önemli kısımlarından biri burası. şimdi 1.sayfadayız diyelim. 1*10-10 = 0 çıkıyor. sayfa limitimiz de 10 olduğuna göre 0-10 arası kayıtları gösterecek. 2.sayfada olduğumuzda 2*10-10 = 10 çıkıyor. sayfa limitimiz de 10 olduğuna göre 10-20 arası kayıtları gösterecek, anladın mı?
  $goster = $sayfaid*$sayfalimiti-$sayfalimiti;
  //echo "toplam veri sayısı :".$toplamverisayisi.","."toplam sayfa sayısı :".$sayfasayisi."sayfaid :".$sayfaid; #debug amaçlı.





   @$mesaj = $_POST['mesajekle'];
   $konuid = $_GET['id'];

   $sorgu = $baglanti->query("SELECT * FROM mesajlar WHERE id = '$konuid' ORDER BY tarihbilgisi ASC  LIMIT $goster,$sayfalimiti ", PDO::FETCH_ASSOC);
   	if($sorgu->rowCount()>0)
   	{
   		foreach($sorgu as $row)
   		{
   			?> 
          <div class="konular-kullanicimesajkapsayicisi">
              <h3 id="konular-kullanicimesaj"><?php echo $row['mesaj'];?></h3>
                <?php
                //giriş yapan kullanıcı sadece admin veya moderatörse mesaj sil butonu gözüksün.
                $kullanici = girisyapankullanici();
                if($kullanici['rutbe'] == "admin" || $kullanici['rutbe'] == "moderator")
                {
                  ?>
                <button id="konular-mesaj-sil">
                 <a href="konular/<?php echo seo_link($row['konu'])."/"; echo $row['id'];?>?mesajid=<?php echo $row['mesajid']; ?>">
                    mesajı sil
                 </a>
                </button>
                <?php
                }
                ?>
          </div>
          <div style="float:left; position: relative; width:100%; height: 20px; background-color: #eeeeee; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 5px;">
            <div id="konular-kullanici-kimlik">
                <h3 id="konular-kullanici-kimlik-kimlik"><a href="profil/<?php echo seo_link($row['user']);?>"><?php echo $row['tarih']." ".$row['user'];?></a></h3>
            </div>
          </div>
          <div style="width: 100%; height: 40px; background-color: #eeeeee; float: left;"></div> 
        <?php
       }
   }
   ?>



    <ul class="pagination" id="pagi" style="float:right; padding-top:30px;">
        <li class="page-item" id="onceki"><a class="page-link" href="konular.php?id=<?php echo $hangisayfadayim; ?>&sayfaid=<?php echo $sayfaid-1; ?>&konu=<?php echo $row['konu']; ?>">Önceki</a></li>
        <form>
          <select style="height:100%; margin-left: 5px; margin-right:5px;" onchange="document.location.href=this.value"> <!-- önemli -->
            <option selected><?php echo $sayfaid."".".sayfa"; ?></option>
            <?php 


            for ($i=1; $i<=$sayfasayisi; $i++) 
            {  
              ?>

               <option value="konular.php?id=<?php echo $hangisayfadayim; ?>&sayfaid=<?php echo $i; ?>&konu=<?php echo $row['konu']; ?> "  ><?php echo $i; ?></option>

              <?php
            }
            ?>
          </select>

        </form>
        <li class="page-item" id="sonraki"><a class="page-link" href="konular.php?id=<?php echo $hangisayfadayim; ?>&sayfaid=<?php echo $sayfaid+1; ?>&konu=<?php echo $row['konu']; ?>">Sonraki</a></li>
    </ul>



<?php
    // eğer giriş yapan kullanıcı banlı değilse sadece konulara mesaj ekleme kısmı gözüksün.
    $kullanici = girisyapankullanici();
    if($kullanici['bandurumu']==0)
    {
      ?>
      <div id="main-mesajekle-div">
        <?php 
        if(isset($_SESSION['girisyapankullanici']))
        {
          echo " <script type='text/javascript'>document.getElementById('main-mesajekle-div').style.display='block';</script>";
        }
        ?>
        <h3 id="main-mesajekle-baslik">-Mesaj Ekle-</h3>
        <form id="main-mesajekle-form" method="POST">
          <textarea name="mesajekle" id="main-mesajekle" maxlength="1500" placeholder="mesajınız... (maksimum 1500 karakter)" required></textarea>
          <input style="float:right; width:80px; border:none; height: 30px; background-color: var(--temarengi); color:#ffffff;" type="submit" value="Gönder">
          <?php 
          @$mesaj = $_POST['mesajekle'];                            
          @$konuid = $_GET['id'];
          @$konuisim = $_GET['konu'];
          $userrr = $_SESSION['girisyapankullanici'];
          date_default_timezone_set('Europe/Istanbul');
          $tarihbilgisi = date("d-m-Y H:i");
          if (isset($mesaj) && isset($konuid)) 
          {
            $komutt = $baglanti->prepare("INSERT INTO mesajlar(id,mesaj,konu,user,tarih) VALUES(:konuid,:mesaj,:konuisim,:userrr,:tarihbilgisi)");
            $komutt->bindParam(':konuid',$konuid);
            $komutt->bindParam(':mesaj',$mesaj);
            $komutt->bindParam(':konuisim',$konuisim);
            $komutt->bindParam(':userrr',$userrr);
            $komutt->bindParam(':tarihbilgisi',$tarihbilgisi);
            $komutt->execute();
                                   
            $guncellemesorgusu = "UPDATE konular SET mesajsayisi=mesajsayisi+1 WHERE id ='$konuid'"; //mesaj eklendiği zaman ayrıca bir güncelleme sorgusu çalıştıracağız. konuya ait mesaj sayısını +1 arttıracağız. bu sayıyı da konuların gündeme düşmesi için kullanacağız.
            $prepareislemi = $baglanti->prepare($guncellemesorgusu);
            $prepareislemi->execute();
            echo "<script> location = location; </script>";
          }
          ?>
        </form>
      </div>
      <?php
    }
?>

<?php 
$mesajid = $_GET['mesajid'];
if(isset($_GET['konuid']))
{
  // konu id tanımlanmışsa silinme işlemi olacak demektir. bu durumda silme işlemi yapılsın ve anasayfaya yönlendirilsin.
  $silmesorgusu = $baglanti->prepare("DELETE FROM konular WHERE id = :konuid");
  $silmesorgusu->bindParam(':konuid',$konuid);
  //$silmesorgusu->execute();
  if($silmesorgusu->execute())
  {
    $silmesorgusu2 = $baglanti->prepare("DELETE FROM mesajlar WHERE id = :konuid");
    $silmesorgusu2->bindParam(':konuid',$konuid);
    $silmesorgusu2->execute();
  }

  echo "<script> setTimeout(function(){ window.location.href='index.php'; }, 1500); </script>";
}

if(isset($_GET['mesajid']))
{
  // mesaj tanımlanmışsa silinme işlemi olacak demektir. bu durumda silme işlemi yapılsın ve anasayfaya yönlendirilsin.
  $silmesorgusu = $baglanti->prepare("DELETE FROM mesajlar WHERE mesajid = :mesajid");
  $silmesorgusu->bindParam(':mesajid',$mesajid);
  $silmesorgusu->execute();
  

  $guncellemesorgusu = "UPDATE konular SET mesajsayisi=mesajsayisi-1 WHERE id ='$konuid'"; 
  $prepareislemi = $baglanti->prepare($guncellemesorgusu);
  $prepareislemi->execute();
  echo "<script> setTimeout(function(){ window.location.href='index.php'; }, 1500); </script>";
}
?>

<?php 
# DISPLAY - VISIBILITY ISLEMLERI #

  if($toplamverisayisi<$sayfalimiti) //toplam kayıt sayısı, sayfa limitinden az ise sayfalama yapısının görünmesi mantıksız olur.
  {
    echo "<script>document.getElementById('pagi').style.display = 'none';</script>";
  } 
  if($sayfaid>$sayfasayisi)
  {
    $sayfaid=1;
    echo "<script>document.getElementById('sonraki').style.display = 'none';</script>";
  }
  if($sayfaid==$sayfasayisi) 
  {
   echo "<script>document.getElementById('sonraki').style.display = 'none';</script>";
  }
  if($sayfaid>1)
  {
    echo "<script>document.getElementById('konu').style.display = 'none';</script>";
  }
  if($sayfaid==1)
  {
    echo "<script>document.getElementById('onceki').style.display = 'none';</script>";
  }
?>