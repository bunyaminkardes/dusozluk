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
                        <button id="konular-konu-sil"><a href="konular/<?php echo seo_link($row['konu_baslik'])."/"; echo $row['id'];?>?konuid=<?php echo $row['id'];?>">konuyu sil</a></button> 
                        <?php
                    }
                    ?>
                    <input readonly autofocus class="focus"> <!-- focus -->
                </h1>
                <br/>
                <p id="konular-yazar-mesaj"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                <br/>
            </div>
            <div class="konular-yazar-kimlik-kapsayici">
                <div id="konular-yazar-kimlik">
                    <div class="konular-yazar-like-dislike">
                        <?php
                        $kullanici = girisyapankullanici();
                        $kullaniciadi = $kullanici['kullaniciadi'];

                        $likedislikekomut = $baglanti->prepare("SELECT * FROM konular WHERE id = $id");
                        $likedislikekomut->fetch();
                        $likedislikekomut->execute();

                        $kullaniciLikeAtmisMi = $baglanti->prepare("SELECT * FROM likedislike WHERE konuid = :id AND kullanici = :kullaniciadi");
                        $kullaniciLikeAtmisMi->bindParam(":id",$id);
                        $kullaniciLikeAtmisMi->bindParam(":kullaniciadi",$kullaniciadi);
                        $kullaniciLikeAtmisMi->fetch();
                        $kullaniciLikeAtmisMi->execute();


                        $gecerlilikesayisi = $row['likesayisi'];
                        $gecerlidislikesayisi = $row['dislikesayisi'];

  // KULLANICI LIKE ATMIS MI KONTROLU *****************************************************************************************************************************
                        if($kullaniciLikeAtmisMi->rowCount()>0)
                        {
                            foreach($likedislikekomut as $row)
                            {
                              if(isset($_SESSION['girisyapankullanici'])) // giriş yapmayanlara gözükmesin like-dislike butonları.
                              {
                                foreach($kullaniciLikeAtmisMi as $rows)
                                {
                                  if($rows['likedislike']=="like") // kullanıcı daha önce like attıysa like butonu görünmesin.
                                  {
                                    ?>
                                    <span style="display:inline-block;">
                                      <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?like=<?php echo false; ?>" style="color:#2C8E18; background-color: #C1FE8F;">Beğendim : <?php echo $row['likesayisi']; ?></a>
                                      <a style="color:#E82424;" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?dislike=<?php echo true; ?>">Beğenmedim : <?php echo $row['dislikesayisi']; ?></a>
                                    </span>
                                    <?php
                                  }
                                  else if($rows['likedislike']=="dislike") // kullanıcı daha önce dislike attıysa dislike butonu görünmesin.
                                  {
                                    ?>
                                    <span style="display:inline-block;">
                                      <a style="color:#2C8E18;" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?like=<?php echo true; ?>">Beğendim : <?php echo $row['likesayisi']; ?></a>
                                      <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?dislike=<?php echo false; ?>" style="color:#E82424; background-color: #FFCAC8;">Beğenmedim : <?php echo $row['dislikesayisi']; ?></a>
                                    </span>
                                    <?php
                                  }
                                }
                              }
                            }
                        }
                        // rowCount 0 ise bu kullanıcı bu konuya like veya dislike atmamıştır. iki butonu da gösterelim.
                        else if($kullaniciLikeAtmisMi->rowCount()==0 && isset($_SESSION['girisyapankullanici'])) 
                        {
                          ?>
                          <span style="display:inline-block;">
                            <a style="color:#2C8E18;" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?like=<?php echo true; ?>">Beğendim : <?php echo $row['likesayisi']; ?></a>
                            <a style="color:#E82424;" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?dislike=<?php echo true; ?>">Beğenmedim : <?php echo $row['dislikesayisi']; ?></a>
                          </span>
                          <?php
                        }
                        if(!isset($_SESSION['girisyapankullanici'])) // giriş yapmayan kullanıcılar sadece beğenme ve beğenmeme sayılarını görebilsin.
                        {
                          ?>
                          <span style="display:inline-block;">
                            <a style="color:#2C8E18;">Beğendim : <?php echo $row['likesayisi']; ?></a>
                            <a style="color:#E82424;">Beğenmedim : <?php echo $row['dislikesayisi']; ?></a>
                          </span>
                          <?php
                        }
// KULLANICI LIKE ATMIS MI KONTROLU *****************************************************************************************************************************



# ********************************************************* LIKE - DISLIKE BUTONU ISLEMLERI ********************************************************* #
$like = $_GET['like'];
$dislike = $_GET['dislike'];
$konuid = $_GET['id'];
$kullanici = girisyapankullanici();
$kullaniciadi = $kullanici['kullaniciadi'];

if(isset($_GET['like']) && $_GET['like'] == 1) // like butonuna basınca burası çalışacak.
{
      $likeatmismi = $baglanti->prepare("SELECT * FROM likedislike WHERE konuid = :id AND kullanici = :kullanici");
      $likeatmismi->bindParam(":id",$id,PDO::PARAM_INT);
      $likeatmismi->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
      $likeatmismi->execute();
      if($likeatmismi->rowCount()>0) // hali hazırda bu konuya like veya dislike atılmışsa
      {
            foreach($likeatmismi as $row)
            {
                  if($row['likedislike'] == 'like') // like atmışsa tekrar like atamasın.
                  {

                  }
                  else // dislike atmışsa dislike sayısını 1 azaltıp like sayısını 1 arttıralım.
                  {
                        $like = $gecerlilikesayisi+1;
                        $dislike = $gecerlidislikesayisi-1;
                        if($dislike<0)
                        {
                              $dislike=0;
                        }
                        $degistir = "like";
                        $likesorgusu = $baglanti->prepare("UPDATE konular SET likesayisi = :likeattir WHERE id=:id");
                        $likesorgusu->bindParam(":likeattir",$like,PDO::PARAM_INT);
                        $likesorgusu->bindParam(":id",$id,PDO::PARAM_INT);
                        $likesorgusu2 = $baglanti->prepare("UPDATE konular SET dislikesayisi = :dislikeattir WHERE id=:id");
                        $likesorgusu2->bindParam(":dislikeattir",$dislike,PDO::PARAM_INT);
                        $likesorgusu2->bindParam(":id",$id,PDO::PARAM_INT);
                        $likesorgusu3 = $baglanti->prepare("UPDATE likedislike SET likedislike = :likedislike WHERE konuid = :id AND kullanici = :kullanici");
                        $likesorgusu3->bindParam(":likedislike",$degistir,PDO::PARAM_STR);
                        $likesorgusu3->bindParam(":id",$id,PDO::PARAM_INT);
                        $likesorgusu3->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
                        $likesorgusu->execute();
                        $likesorgusu2->execute();
                        $likesorgusu3->execute();
                  }
            }
      }
      else if($likeatmismi->rowCount()==0) // hali hazırda bu konuya like veya dislike atılmamışsa
      {
            // o zaman insert ederiz.
            $like = $gecerlilikesayisi+1;
            $degistir = "like";
            $likesorgusu = $baglanti->prepare("INSERT INTO likedislike(konuid,kullanici,likedislike) VALUES(:konuid,:kullanici,:likedislike)");
            $likesorgusu->bindParam(":konuid",$id,PDO::PARAM_INT);
            $likesorgusu->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
            $likesorgusu->bindParam(":likedislike",$degistir,PDO::PARAM_STR);
            $likesorgusu2 = $baglanti->prepare("UPDATE konular SET likesayisi = :likeattir WHERE id=:id");
            $likesorgusu2->bindParam(":likeattir",$like,PDO::PARAM_INT);
            $likesorgusu2->bindParam(":id",$id,PDO::PARAM_INT);
            $likesorgusu->execute();
            $likesorgusu2->execute();
      }
      echo "<script> window.history.back(); </script>"; // işlem sonrası url de parametre kalmasın diye bu şekilde sayfa yenileyelim.
}




if(isset($_GET['dislike']) && $_GET['dislike']==1) // dislike butonuna basınca burası çalışacak.
{
      $likeatmismi = $baglanti->prepare("SELECT * FROM likedislike WHERE konuid = :id AND kullanici = :kullanici");
      $likeatmismi->bindParam(":id",$id,PDO::PARAM_INT);
      $likeatmismi->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
      $likeatmismi->execute();
      if($likeatmismi->rowCount()>0) // hali hazırda bu konuya like veya dislike atılmışsa
      {
            foreach($likeatmismi as $row)
            {
                  if($row['likedislike'] == 'dislike') // dislike atmışsa tekrar like atamasın.
                  {

                  }
                  else // dislike atmışsa dislike sayısını 1 azaltıp like sayısını 1 arttıralım.
                  {
                        $like = $gecerlilikesayisi-1;
                        $dislike = $gecerlidislikesayisi+1;
                        if($like<0)
                        {
                              $like=0;
                        }
                        $degistir = "dislike";
                        $likesorgusu = $baglanti->prepare("UPDATE konular SET likesayisi = :likeattir WHERE id=:id");
                        $likesorgusu->bindParam(":likeattir",$like,PDO::PARAM_INT);
                        $likesorgusu->bindParam(":id",$id,PDO::PARAM_INT);
                        $likesorgusu2 = $baglanti->prepare("UPDATE konular SET dislikesayisi = :dislikeattir WHERE id=:id");
                        $likesorgusu2->bindParam(":dislikeattir",$dislike,PDO::PARAM_INT);
                        $likesorgusu2->bindParam(":id",$id,PDO::PARAM_INT);
                        $likesorgusu3 = $baglanti->prepare("UPDATE likedislike SET likedislike = :likedislike WHERE konuid = :id AND kullanici = :kullanici");
                        $likesorgusu3->bindParam(":likedislike",$degistir,PDO::PARAM_STR);
                        $likesorgusu3->bindParam(":id",$id,PDO::PARAM_INT);
                        $likesorgusu3->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
                        $likesorgusu->execute();
                        $likesorgusu2->execute();
                        $likesorgusu3->execute();
                  }
            }
      }
      else if($likeatmismi->rowCount()==0) // hali hazırda bu konuya like veya dislike atılmamışsa
      {
            $like = $gecerlidislikesayisi+1;
            $degistir = "dislike";
            $likesorgusu = $baglanti->prepare("INSERT INTO likedislike(konuid,kullanici,likedislike) VALUES(:konuid,:kullanici,:likedislike)");
            $likesorgusu->bindParam(":konuid",$id,PDO::PARAM_INT);
            $likesorgusu->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
            $likesorgusu->bindParam(":likedislike",$degistir,PDO::PARAM_STR);
            $likesorgusu2 = $baglanti->prepare("UPDATE konular SET dislikesayisi = :likeattir WHERE id=:id");
            $likesorgusu2->bindParam(":likeattir",$like,PDO::PARAM_INT);
            $likesorgusu2->bindParam(":id",$id,PDO::PARAM_INT);
            $likesorgusu->execute();
            $likesorgusu2->execute();
      }
      echo "<script> window.history.back(); </script>"; // işlem sonrası url de parametre kalmasın diye bu şekilde sayfa yenileyelim.               
}




if(isset($_GET['like']) && $_GET['like'] == 0) // zaten like atılmışsa, tekrar like'a bastığında beğenme işlemini kaldıralım.
{
      $likesayisi = $gecerlilikesayisi-1;
      if($likesayisi<0)
      {
            $likesayisi = 0;
      }
      $silmesorgusu = $baglanti->prepare("DELETE FROM likedislike WHERE konuid = :id AND kullanici = :kullanici");
      $silmesorgusu->bindParam(":id",$id,PDO::PARAM_INT);
      $silmesorgusu->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
      $silmesorgusu->execute();
      $guncellemesorgusu = $baglanti->prepare("UPDATE konular SET likesayisi = :likesayisi WHERE id=:id");
      $guncellemesorgusu->bindParam(":likesayisi",$likesayisi,PDO::PARAM_INT);
      $guncellemesorgusu->bindParam(":id",$id,PDO::PARAM_INT);
      $guncellemesorgusu->execute();
      echo "<script> window.history.back(); </script>"; // işlem sonrası url de parametre kalmasın diye bu şekilde sayfa yenileyelim.
}



if(isset($_GET['dislike']) && $_GET['dislike'] == 0) // zaten dislike atılmışsa, tekrar dislike'a basıldığında beğenmeme işlemini kaldıralım.
{
      $dislikesayisi = $gecerlidislikesayisi-1;
      if($dislikesayisi<0)
      {
            $dislikesayisi = 0;
      }
      $silmesorgusu = $baglanti->prepare("DELETE FROM likedislike WHERE konuid = :id AND kullanici = :kullanici");
      $silmesorgusu->bindParam(":id",$id,PDO::PARAM_INT);
      $silmesorgusu->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
      $silmesorgusu->execute();
      $guncellemesorgusu = $baglanti->prepare("UPDATE konular SET dislikesayisi = :dislikesayisi WHERE id = :id");
      $guncellemesorgusu->bindParam(":dislikesayisi",$dislikesayisi,PDO::PARAM_INT);
      $guncellemesorgusu->bindParam(":id",$id,PDO::PARAM_INT);
      $guncellemesorgusu->execute();
      echo "<script> window.history.back(); </script>"; // işlem sonrası url de parametre kalmasın diye bu şekilde sayfa yenileyelim.
}

?>

</div>
<h3 id="konular-yazar-kimlik-kimlik"><a href="profil/<?php echo seo_link($row['user']);?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a></h3>
</div>
</div>
<div id="konular-yazar-ghost"></div>
<?php
}
}
# ********************************************************* LIKE - DISLIKE BUTONU ISLEMLERI ********************************************************* #





# ********************************************************* SAYFALAMA(PAGINATION) ISLEMLERI ********************************************************* #
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
# ********************************************************* SAYFALAMA(PAGINATION) ISLEMLERI ********************************************************* #




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
          <div class="konular-kullanici-kimlik-kapsayici">
            <div id="konular-kullanici-kimlik">
                <h3 id="konular-kullanici-kimlik-kimlik"><a href="profil/<?php echo seo_link($row['user']);?>"><?php echo $row['tarih']." ".$row['user'];?></a></h3>
            </div>
          </div>
          <div id="konular-kullanici-ghost"></div> 
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
    $kullanici = girisyapankullanici();
    if(isset($_SESSION['girisyapankullanici']) && $kullanici['bandurumu']==0)  // eğer giriş yapan kullanıcı banlı değilse sadece konulara mesaj ekleme kısmı gözüksün.
    {
        ?>
        <div id="main-mesajekle-div">
            <h3 id="main-mesajekle-baslik">-Mesaj Ekle-</h3>
            <form id="main-mesajekle-form" method="POST">
                <textarea name="mesajekle" id="main-mesajekle" maxlength="1500" placeholder="mesajınız... (maksimum 1500 karakter)" required></textarea>
                <input id="main-mesajekle-buton" type="submit" value="Gönder">
                <?php
                @$mesaj = $_POST['mesajekle'];
                @$konuid = $_GET['id'];
                @$konuisim = $_GET['konu'];
                $userrr = $_SESSION['girisyapankullanici'];
                date_default_timezone_set('Europe/Istanbul');
                $tarihbilgisi = date("d-m-Y H:i");
                if(isset($mesaj) && isset($konuid))
                {
                    $komutt = $baglanti->prepare("INSERT INTO mesajlar(id,mesaj,konu,user,tarih) VALUES(:konuid,:mesaj,:konuisim,:userrr,:tarihbilgisi)");
                    $komutt->bindParam(':konuid',$konuid);
                    $komutt->bindParam(':mesaj',$mesaj);
                    $komutt->bindParam(':konuisim',$konuisim);
                    $komutt->bindParam(':userrr',$userrr);
                    $komutt->bindParam(':tarihbilgisi',$tarihbilgisi);
                    $komutt->execute();

                    //mesaj eklendiği zaman ayrıca bir güncelleme sorgusu çalıştıracağız. konuya ait mesaj sayısını +1 arttıracağız. bu sayıyı da konuların gündeme düşmesi için kullanacağız.
                    $guncellemesorgusu = "UPDATE konular SET mesajsayisi=mesajsayisi+1 WHERE id ='$konuid'";
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
$kullanici = girisyapankullanici();
date_default_timezone_set('Europe/Istanbul');
$islemTarihi = date("d-m-Y H:i");


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

  $islem = $kullanici['kullaniciadi']." "."adlı moderatör"." ".$_GET['konuid']." "."numaralı konuyu sildi.";
  $logsorgusu = $baglanti->prepare("INSERT INTO moderatorLoglari(islem,tarih) VALUES (:islem,:tarih)");
  $logsorgusu->bindParam(":islem",$islem);
  $logsorgusu->bindParam(":tarih",$islemTarihi);
  $logsorgusu->execute();

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