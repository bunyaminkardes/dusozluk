<?php
    session_start();
    $kullanici = girisyapankullanici();
    $kullaniciadi = $kullanici['kullaniciadi'];
    $girisyapankullanici=$_SESSION['girisyapankullanici'];
    date_default_timezone_set('Europe/Istanbul');
    $islemTarihi = date("d-m-Y H:i");
    $mesaj = $_POST['mesajekle'];
    $konuisim = $_GET['konu'];
    $konu_id = $_GET['id'];
    $konuid = $_GET['konuid']; // eğer silinme işlemi varsa ilgili konu id'si get edilerek depolanacak.
    $mesajsil = $_GET['mesaj'];
    $like = $_GET['like'];
    $dislike = $_GET['dislike'];
    $konuid = $_GET['id']; // $konu_id ile karıştırma bunu, bu konu silinmesi için ekstra.
    $mesajid = $_GET['mesajid'];
    $likesorgusu2_degistir = "like";
    $likesorgusu3_degistir = "like";
    $likesorgusu6_degistir = "dislike";
    $likesorgusu7_degistir = "dislike";
    $konuSilme_islem = $kullanici['kullaniciadi']." "."adlı"." ".$kullanici['rutbe']." ".$_GET['konuid']." "."numaralı konuyu sildi.";

    $titleSorgusu = $baglanti->prepare("SELECT * FROM konular WHERE id = :id");
    $titleSorgusu->bindParam(':id',$konu_id,PDO::PARAM_INT);
    $titleSorgusu->fetch(PDO::FETCH_ASSOC);
    $titleSorgusu->execute();

    $konuListelemeSorgusu=$baglanti->prepare("SELECT * FROM konular WHERE id=:id");
    $konuListelemeSorgusu->bindParam(':id',$konu_id,PDO::PARAM_INT);
    $konuListelemeSorgusu->fetch(PDO::FETCH_ASSOC);
    $konuListelemeSorgusu->execute();

    $mesajEklemeSorgusu = $baglanti->prepare("INSERT INTO mesajlar(id,mesaj,konu,user,tarih) VALUES(:konuid,:mesaj,:konuisim,:userrr,:tarihbilgisi)");
    $mesajEklemeSorgusu->bindParam(':konuid',$konu_id,PDO::PARAM_STR);
    $mesajEklemeSorgusu->bindParam(':mesaj',$mesaj,PDO::PARAM_STR);
    $mesajEklemeSorgusu->bindParam(':konuisim',$konuisim,PDO::PARAM_STR);
    $mesajEklemeSorgusu->bindParam(':userrr',$girisyapankullanici,PDO::PARAM_STR);
    $mesajEklemeSorgusu->bindParam(':tarihbilgisi',$islemTarihi,PDO::PARAM_STR);

    $mesajEklemeSorgusuPart2 = $baglanti->prepare("UPDATE konular SET mesajsayisi=mesajsayisi+1 WHERE id =:konuid");
    $mesajEklemeSorgusuPart2->bindParam(":konuid",$konu_id);
    
    $konuSilmeSorgusu = $baglanti->prepare("DELETE FROM konular WHERE id = :konuid");
    $konuSilmeSorgusu->bindParam(':konuid',$konu_id,PDO::PARAM_INT);

    $konuSilmeSorgusuPart2 = $baglanti->prepare("DELETE FROM mesajlar WHERE id = :konuid");
    $konuSilmeSorgusuPart2->bindParam(':konuid',$konu_id,PDO::PARAM_STR);

    $konuSilme_log = $baglanti->prepare("INSERT INTO moderatorloglari(islem,tarih) VALUES (:islem,:tarih)");
    $konuSilme_log->bindParam(":islem",$konuSilme_islem,PDO::PARAM_STR);
    $konuSilme_log->bindParam(":tarih",$islemTarihi,PDO::PARAM_STR);

    $mesajSilmeSorgusu = $baglanti->prepare("DELETE FROM mesajlar WHERE mesajid = :mesajid");
    $mesajSilmeSorgusu->bindParam(':mesajid',$mesajid,PDO::PARAM_INT);

    $mesajSilmeSorgusuPart2 = $baglanti->prepare("UPDATE konular SET mesajsayisi=mesajsayisi-1 WHERE id =:konuid");
    $mesajSilmeSorgusuPart2->bindParam(":konuid",$konu_id,PDO::PARAM_INT);

    $likedislikekomut = $baglanti->prepare("SELECT * FROM konular WHERE id = :id");
    $likedislikekomut->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $likedislikekomut->fetch();
    $likedislikekomut->execute();

    $kullaniciLikeAtmisMi = $baglanti->prepare("SELECT * FROM likedislike WHERE konuid = :id AND kullanici = :kullaniciadi");
    $kullaniciLikeAtmisMi->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $kullaniciLikeAtmisMi->bindParam(":kullaniciadi",$kullaniciadi,PDO::PARAM_STR);
    $kullaniciLikeAtmisMi->fetch();
    $kullaniciLikeAtmisMi->execute();

    $likeatmismi = $baglanti->prepare("SELECT * FROM likedislike WHERE konuid = :id AND kullanici = :kullanici");
    $likeatmismi->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $likeatmismi->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
    $likeatmismi->execute();

    $likesorgusu = $baglanti->prepare("UPDATE konular SET likesayisi = :likeattir, dislikesayisi = :dislikeattir WHERE id=:id");
    $likesorgusu->bindParam(":likeattir",$like,PDO::PARAM_INT);
    $likesorgusu->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $likesorgusu->bindParam(":dislikeattir",$dislike,PDO::PARAM_INT);

    $likesorgusu2 = $baglanti->prepare("UPDATE likedislike SET likedislike = :likedislike WHERE konuid = :id AND kullanici = :kullanici");
    $likesorgusu2->bindParam(":likedislike",$likesorgusu2_degistir,PDO::PARAM_STR);
    $likesorgusu2->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $likesorgusu2->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);

    $likesorgusu3 = $baglanti->prepare("INSERT INTO likedislike(konuid,kullanici,likedislike) VALUES(:konuid,:kullanici,:likedislike)");
    $likesorgusu3->bindParam(":konuid",$konu_id,PDO::PARAM_INT);
    $likesorgusu3->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
    $likesorgusu3->bindParam(":likedislike",$likesorgusu3_degistir,PDO::PARAM_STR);

    $likesorgusu4 = $baglanti->prepare("UPDATE konular SET likesayisi = :likeattir WHERE id=:id");
    $likesorgusu4->bindParam(":likeattir",$like,PDO::PARAM_INT);
    $likesorgusu4->bindParam(":id",$konu_id,PDO::PARAM_INT);

    $likesorgusu5 = $baglanti->prepare("UPDATE konular SET likesayisi = :likeattir, dislikesayisi = :dislikeattir WHERE id=:id");
    $likesorgusu5->bindParam(":likeattir",$like,PDO::PARAM_INT);
    $likesorgusu5->bindParam(":dislikeattir",$dislike,PDO::PARAM_INT);
    $likesorgusu5->bindParam(":id",$konu_id,PDO::PARAM_INT);

    $likesorgusu6 = $baglanti->prepare("UPDATE likedislike SET likedislike = :likedislike WHERE konuid = :id AND kullanici = :kullanici");
    $likesorgusu6->bindParam(":likedislike",$likesorgusu6_degistir,PDO::PARAM_STR);
    $likesorgusu6->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $likesorgusu6->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);

    $likesorgusu7 = $baglanti->prepare("INSERT INTO likedislike(konuid,kullanici,likedislike) VALUES(:konuid,:kullanici,:likedislike)");
    $likesorgusu7->bindParam(":konuid",$konu_id,PDO::PARAM_INT);
    $likesorgusu7->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
    $likesorgusu7->bindParam(":likedislike",$likesorgusu7_degistir,PDO::PARAM_STR);

    $likesorgusu8 = $baglanti->prepare("UPDATE konular SET dislikesayisi = :likeattir WHERE id=:id");
    $likesorgusu8->bindParam(":likeattir",$like,PDO::PARAM_INT);
    $likesorgusu8->bindParam(":id",$konu_id,PDO::PARAM_INT);

    $silmesorgusu = $baglanti->prepare("DELETE FROM likedislike WHERE konuid = :id AND kullanici = :kullanici");
    $silmesorgusu->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $silmesorgusu->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
    
    $guncellemesorgusu = $baglanti->prepare("UPDATE konular SET likesayisi = :likesayisi WHERE id=:id");
    $guncellemesorgusu->bindParam(":likesayisi",$likesayisi,PDO::PARAM_INT);
    $guncellemesorgusu->bindParam(":id",$konu_id,PDO::PARAM_INT);

    $silmesorgusu2 = $baglanti->prepare("DELETE FROM likedislike WHERE konuid = :id AND kullanici = :kullanici");
    $silmesorgusu2->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $silmesorgusu2->bindParam(":kullanici",$kullaniciadi,PDO::PARAM_STR);
    
    $guncellemesorgusu2 = $baglanti->prepare("UPDATE konular SET dislikesayisi = :dislikesayisi WHERE id = :id");
    $guncellemesorgusu2->bindParam(":dislikesayisi",$dislikesayisi,PDO::PARAM_INT);
    $guncellemesorgusu2->bindParam(":id",$konu_id,PDO::PARAM_INT);




//*****************************************************************************************************************************
    $bildirimSorgusu1 = $baglanti->prepare("SELECT * FROM konular WHERE id = :id");
    $bildirimSorgusu1->bindParam(":id",$konu_id,PDO::PARAM_INT);
    $bildirimSorgusu1->execute();

    $konuSahibi = "";
    $bildirim = "";
    $hangiKonu = "";

    foreach($bildirimSorgusu1 as $row)
    {
        $konuSahibi = $row['user'];
        $bildirim = $row['id']." "."numaralı konunuza gelen yeni mesajlar var.";
        $hangiKonu = $row['konu_baslik'];
    }

    $mesajBildirimSorgusu = $baglanti->prepare("INSERT INTO bildirimler(kullanici,bildirim,konu,konuid) VALUES(:kullanici,:bildirim,:konu,:konuid)");
    $mesajBildirimSorgusu->bindParam(":kullanici",$konuSahibi,PDO::PARAM_STR);
    $mesajBildirimSorgusu->bindParam(":bildirim",$bildirim,PDO::PARAM_STR);
    $mesajBildirimSorgusu->bindParam(":konu",$hangiKonu,PDO::PARAM_STR);
    $mesajBildirimSorgusu->bindParam(":konuid",$konu_id,PDO::PARAM_INT);


    $bildirimNo = $_GET['bildirimNo'];

    if(isset($bildirimNo))
    {
        $bildirimSilmeSorgusu = $baglanti->prepare("DELETE FROM bildirimler WHERE id = :bildirimNo");
        $bildirimSilmeSorgusu->bindParam(":bildirimNo",$bildirimNo,PDO::PARAM_INT);
        $bildirimSilmeSorgusu->execute();
        if($bildirimSilmeSorgusu->rowCount())
        {
            echo "<script> location=location; </script>";
        }
    }
    

//*****************************************************************************************************************************
?>





<?php
	if($konuListelemeSorgusu->rowCount()>0)
	{
		foreach($konuListelemeSorgusu as $row)
		{
			?>
			<div id="konu">
                <h1 id="konular-yazar-baslik"><?php print_r($row['konu_baslik']);?>
				<?php
				//giriş yapan kullanıcı sadece admin veya moderatörse konuyu sil butonu gözüksün.
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
                <div class="row">
                    <div class="col-12 col-sm-12 col-lg-12">
                        <div class="konular-yazar-like-dislike">
                                        <?php
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

                                                                <a title="beğendiniz" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?like=<?php echo false; ?>" style="color:#282A35; background-color: #C1FE8F;"> <img style="width:20px; height: 100%;" src="resimler/like.png"> : <?php echo $row['likesayisi']; ?></a>
                                                                <a style="color:#282A35" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?dislike=<?php echo true; ?>"><img style="width:20px; height: 100%;" src="resimler/dislike.png"> : <?php echo $row['dislikesayisi']; ?></a>
                                                            </span>
                                                            <?php
                                                        }
                                                        else if($rows['likedislike']=="dislike") // kullanıcı daha önce dislike attıysa dislike butonu görünmesin.
                                                        {
                                                            ?>
                                                            <span style="display:inline-block;">
                                                                <a style="color:#282A35" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?like=<?php echo true; ?>"><img style="width:20px; height: 100%;" src="resimler/like.png"> : <?php echo $row['likesayisi']; ?></a>
                                                                <a title="beğenmediniz" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?dislike=<?php echo false; ?>" style="color:#282A35; background-color: #FFCAC8;"><img style="width:20px; height: 100%;" src="resimler/dislike.png"> : <?php echo $row['dislikesayisi']; ?></a>
                                                            </span>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        else if($kullaniciLikeAtmisMi->rowCount()==0 && isset($_SESSION['girisyapankullanici'])) // rowCount 0 ise bu kullanıcı bu konuya like veya dislike atmamıştır. iki butonu da gösterelim.
                                        {
                                            ?>
                                            <span style="display:inline-block;">
                                                <a style="color:#282A35;" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?like=<?php echo true; ?>"><img style="width:20px; height: 100%;" src="resimler/like.png"> : <?php echo $row['likesayisi']; ?></a>
                                                <a style="color:#282A35;" href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>?dislike=<?php echo true; ?>"><img style="width:20px; height: 100%;" src="resimler/dislike.png"> : <?php echo $row['dislikesayisi']; ?></a>
                                            </span>
                                            <?php
                                        }
                                        if(!isset($_SESSION['girisyapankullanici'])) // giriş yapmayan kullanıcılar sadece beğenme ve beğenmeme sayılarını görebilsin.
                                        {
                                            ?>
                                            <span style="display:inline-block;">
                                                <a href="giris-yap?q=0" style="color:#282A35;"><img style="width:20px; height: 100%;" src="resimler/like.png"> : <?php echo $row['likesayisi']; ?></a>
                                                <a href="giris-yap?q=0" style="color:#282A35;"><img style="width:20px; height: 100%;" src="resimler/dislike.png"> : <?php echo $row['dislikesayisi']; ?></a>
                                            </span>
                                            <?php
                                        }

                                        # ********************************************************* LIKE - DISLIKE BUTONU ISLEMLERI ********************************************************* #
                                        if(isset($_GET['like']) && $_GET['like'] == 1) // like işlemi yapılacağı zaman bu blok çalışacak.
                                        {
                                            if($likeatmismi->rowCount()>0) // hali hazırda bu konuya like veya dislike atılmışsa bu blok çalışacak.
                                            {
                                                foreach($likeatmismi as $row)
                                                {
                                                    if($row['likedislike'] == 'like') // kullanıcı bu konuya like attıysa herhangi bir işlem yaptırmayalım.
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
                                                        $likesorgusu->execute();
                                                        $likesorgusu2->execute();
                                                    }
                                                }
                                            }
                                            else if($likeatmismi->rowCount()==0) // hali hazırda bu konuya like veya dislike atılmamışsa burası çalışsın.
                                            {
                                                /*
                                                kullanıcının likedislike tablosunda bir kayıtı yoksa, ilgili konuya like veya dislike atmamış demektir. bu durumda like işlemini yapalım,
                                                likedislike tablosuna da insert işlemi yapalım ki bu kullanıcının bu konuya like attığı anlaşılsın.
                                                */
                                                $like = $gecerlilikesayisi+1;
                                                $likesorgusu3->execute();
                                                $likesorgusu4->execute();
                                            }
                                            echo "<script> window.history.back(); </script>"; // işlem sonrası url de parametre kalmasın diye bu şekilde sayfa yenileyelim.
                                        }
                                        if(isset($_GET['dislike']) && $_GET['dislike']==1) // dislike butonuna basınca burası çalışacak.
                                        {
                                            if($likeatmismi->rowCount()>0) // hali hazırda bu konuya like veya dislike atılmışsa bu blok çalışacak.
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
                                                        $likesorgusu5->execute();
                                                        $likesorgusu6->execute();
                                                    }
                                                }
                                            }
                                            else if($likeatmismi->rowCount()==0) // hali hazırda bu konuya like veya dislike atılmamışsa bu blok çalışacak.
                                            {
                                                /*
                                                kullanıcının likedislike tablosunda bir kayıtı yoksa, ilgili konuya like veya dislike atmamış demektir. bu durumda dislike işlemini yapalım,
                                                likedislike tablosuna da insert işlemi yapalım ki bu kullanıcının bu konuya dislike attığı anlaşılsın.
                                                */
                                                $like = $gecerlidislikesayisi+1;
                                                $likesorgusu7->execute();
                                                $likesorgusu8->execute();
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
                                            $silmesorgusu->execute();
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
                                            $silmesorgusu2->execute();
                                            $guncellemesorgusu2->execute();
                                            echo "<script> window.history.back(); </script>"; // işlem sonrası url de parametre kalmasın diye bu şekilde sayfa yenileyelim.
                                        }
                                        ?>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12">
                        <h3 id="konular-yazar-kimlik-kimlik">
                            <?php
                            if(isset($_SESSION['girisyapankullanici']))
                            {
                                ?>
                                <a href="profil/<?php echo seo_link($row['user']);?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a>
                                <?php
                            }
                            else
                            {
                                ?>
                                <a href="../giris.php?pq=0"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a>
                                <?php
                            }
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div id="konular-yazar-ghost"></div>
            <?php
        }
    }





# ********************************************************* SAYFALAMA(PAGINATION) ISLEMLERI ********************************************************* #
    $id = $_GET['id'];
    $hangisayfadayim = intval($_GET['id']); //hangi sayfada olduğumuzu bilmemiz gerekecek.
    $sayfaid=1;
    $sayfaid = intval($_GET['sayfaid']);
    if(!$sayfaid)
    {
        $sayfaid =1;
    }
    /*
    if($sayfaid>1) // çok garip bir bug olduğundan dolayı bu if'i ekledim. sayfa 2 ve sonrasında yazar kimliği ve ghost div duruyordu gereksiz yere.
    {
        echo "<script> document.getElementById('konular-yazar-kimlik').style.display='none'; </script>";
    }
    */	
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
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div id="konular-kullanici-kimlik">
                        <h3 id="konular-kullanici-kimlik-kimlik">
                            <?php
                            if(isset($_SESSION['girisyapankullanici']))
                            {
                                ?>
                                <a href="profil/<?php echo seo_link($row['user']);?>"><?php echo $row['tarih']." ".$row['user'];?></a>
                                <?php
                            }
                            else
                            {
                                ?>
                                <a href="../giris.php?pq=0"><?php echo $row['tarih']." ".$row['user'];?></a>
                                <?php
                            }
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
          </div>
          <div id="konular-kullanici-ghost"></div> 
        <?php
       }
   }
   ?>



<?php
    if(isset($_SESSION['girisyapankullanici']) && $kullanici['bandurumu']==0)  // eğer giriş yapan kullanıcı banlı değilse sadece konulara mesaj ekleme kısmı gözüksün.
    {
        ?>
        <div id="main-mesajekle-div">
            <h3 id="main-mesajekle-baslik">Yorum Yap</h3>
            <form id="main-mesajekle-form" method="POST">
                <textarea name="mesajekle" id="main-mesajekle" maxlength="1500" placeholder=" Yorum yapmak için burayı kullanabilirsiniz. " required></textarea>
                <input id="main-mesajekle-buton" type="submit" value="Gönder">
                <?php
                if(isset($mesaj) && isset($konuid))
                {
                    $mesajEklemeSorgusu->execute();
                    $mesajEklemeSorgusuPart2->execute(); //mesaj eklendiği zaman ayrıca bir güncelleme sorgusu çalıştıracağız. konuya ait mesaj sayısını +1 arttıracağız. bu sayıyı da konuların gündeme düşmesi için kullanacağız.
                    if($kullanici['kullaniciadi'] != $konuSahibi) // kullanıcı kendi konusuna mesaj yazdığında kendi bildirim kutusuna bildirim düşmesin.
                    {
                        $mesajBildirimSorgusu->execute();
                    }
                    if($mesajEklemeSorgusu->rowCount()>0 && $mesajEklemeSorgusuPart2->rowCount()>0)
                    {
                      echo "<script> window.location.href = window.location.href; </script>";
                    }
                }
                ?>
            </form>
        </div>
        <?php
    }
    else if(!isset($_SESSION['girisyapankullanici']))
    {
        echo "<h3 id='hatamesaj'>Yorum yapmak için giriş yapmanız gerek."."<br>"."Giriş yapmak için "."<a href='giris-yap'>tıklayınız.</a></h3>";
    }
    else if(isset($_SESSION['girisyapankullanici']) && $kullanici['bandurumu']==1)
    {
        echo "<h3 id='hatamesaj'>Yasaklı bir kullanıcı olduğunuz için konulara yorum yapamazsınız.</h3>";
    }
?>


<ul class="pagination" id="pagi" style="float:right; padding-top:30px;">
	<li class="page-item" id="onceki">
		<a class="page-link" href="konular.php?id=<?php echo $hangisayfadayim; ?>&sayfaid=<?php echo $sayfaid-1; ?>&konu=<?php echo $row['konu']; ?>">Önceki</a>
	</li>
	<form>
		<select style="height:100%; margin-left: 5px; margin-right:5px;" onchange="document.location.href=this.value"> <!-- önemli -->
			<option selected><?php echo $sayfaid."".".sayfa"; ?></option>
			<?php
			for ($i=1; $i<=$sayfasayisi; $i++)
			{
				?><option value="konular.php?id=<?php echo $hangisayfadayim; ?>&sayfaid=<?php echo $i;?>&konu=<?php echo $row['konu'];?>"><?php echo $i;?></option><?php
			}
			?>
		</select>
	</form>
	<li class="page-item" id="sonraki">
		<a class="page-link" href="konular.php?id=<?php echo $hangisayfadayim; ?>&sayfaid=<?php echo $sayfaid+1; ?>&konu=<?php echo $row['konu']; ?>">Sonraki</a>
	</li>
</ul>


<?php
	if(isset($_GET['konuid'])) // konu id tanımlanmışsa silinme işlemi olacak demektir. bu durumda silme işlemi yapılsın ve anasayfaya yönlendirilsin.
	{
		if($konuSilmeSorgusu->execute())
		{
			$konuSilmeSorgusuPart2->execute();
		}
		$konuSilme_log->execute();
		if($konuSilmeSorgusu->rowCount()>0 && $konuSilme_log->rowCount()>0)
		{
			echo "<script> window.location.href='index.php'; </script>";
		}
	}
	if(isset($_GET['mesajid'])) // mesaj tanımlanmışsa silinme işlemi olacak demektir. bu durumda silme işlemi yapılsın ve anasayfaya yönlendirilsin.
	{
		$mesajSilmeSorgusu->execute();
		$mesajSilmeSorgusuPart2->execute();
		if($mesajSilmeSorgusu->rowCount()>0)
		{
			echo "<script> window.location = window.location.pathname; </script>";
		}
	}
	if($toplamverisayisi<=$sayfalimiti) //toplam kayıt sayısı, sayfa limitinden az ise sayfalama yapısının görünmesi mantıksız olur.
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
		echo "<script>document.getElementById('konular-yazar-ghost').style.display = 'none';</script>";
	}
	if($sayfaid==1)
	{
		echo "<script>document.getElementById('onceki').style.display = 'none';</script>";
	}
?>