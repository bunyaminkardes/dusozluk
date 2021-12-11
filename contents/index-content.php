<!DOCTYPE html>
<html>
   <head>
      <title>DuSözlük Anasayfa</title>
   </head>
   <body>
      <div class="index-content-div">
         <?php  
         $kategori = $_GET['kategori'];

         if(!isset($kategori)) // mobilde sayfaya ilk girenlere bu konu gösterilecek. tekrardan gündeme basılınca falan kaybolacak.
         {
            $konuid = 25;
            $ilkgirissorgusu = $baglanti->prepare("SELECT * FROM konular WHERE id = :konuid");
            $ilkgirissorgusu->bindParam(":konuid",$konuid);
            $ilkgirissorgusu->execute();
            foreach($ilkgirissorgusu as $row)
            {
               ?>
               <div id="konu">
                  <h3 id="index-konu-baslik"><a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>"><?php print_r($row['konu_baslik']); ?></a></h3>
                  <br/>
                  <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                  <br/>
                  <div id="index-yazar-kimlik"><a href="profil/<?php echo seo_link($row['user']); ?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a></div>
               </div>
               <div style="width: 100%; height: 40px; background-color: #eeeeee; float: left;"></div>
               <?php
            }
         }

         if($kategori == "gundem" || !isset($kategori))
         {
            // gündemdeki konular gösterilecek.
            $limit = 15;
            $sorgu = $baglanti->prepare("SELECT * FROM konular ORDER BY likesayisi*0.40 + mesajsayisi*0.20 - dislikesayisi*0.40 DESC LIMIT :limitt");
            $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
            $sorgu->fetch(PDO::FETCH_ASSOC);
            $sorgu->execute();
            if ($sorgu->rowCount()>0)
            {
               foreach($sorgu as $row)
               {
                  ?> 
                  <div id="konu">
                     <h3 id="index-konu-baslik"><a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>"><?php print_r($row['konu_baslik']); ?></a></h3>
                     <br/>
                     <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                     <br/>
                     <div id="index-yazar-kimlik"><a href="profil/<?php echo seo_link($row['user']); ?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a></div>
                  </div>
                  <div style="width: 100%; height: 40px; background-color: #eeeeee; float: left;"></div><?php
               }
            }
            else if($sorgu->rowCount()==0)
            {
               echo "Bu kategoriye ait hiç konu açılmamış.";
            }
         }
         else if($kategori == "enTaze")
         {
            // gündemdeki konular gösterilecek.
            $limit = 15;
            $sorgu = $baglanti->prepare("SELECT * FROM konular ORDER BY tarihal DESC LIMIT :limitt");
            $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
            $sorgu->fetch(PDO::FETCH_ASSOC);
            $sorgu->execute();
            if ($sorgu->rowCount()>0)
            {
               foreach($sorgu as $row)
               {
                  ?> 
                  <div id="konu">
                     <h3 id="index-konu-baslik"><a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>"><?php print_r($row['konu_baslik']); ?></a></h3>
                     <br/>
                     <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                     <br/>
                     <div id="index-yazar-kimlik"><a href="profil/<?php echo seo_link($row['user']); ?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a></div>
                  </div>
                  <div style="width: 100%; height: 40px; background-color: #eeeeee; float: left;"></div><?php
               }
            }
            else if($sorgu->rowCount()==0)
            {
               echo "Bu kategoriye ait hiç konu açılmamış.";
            }
         }
         else
         {
            // en yeni açılmış konular gösterilecek.
            $limit = 15;
            $sorgu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY tarihal DESC LIMIT :limitt");
            $sorgu->bindParam(':limitt',$limit,PDO::PARAM_INT);
            $sorgu->bindParam(':konu_turu',$kategori,PDO::PARAM_STR);
            $sorgu->fetch(PDO::FETCH_ASSOC);
            $sorgu->execute();
            if ($sorgu->rowCount()>0)
            {
               foreach($sorgu as $row)
               {
                  ?> 
                  <div id="konu">
                     <h3 id="index-konu-baslik"><a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>"><?php print_r($row['konu_baslik']); ?></a></h3>
                     <br/>
                     <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                     <br/>
                     <div id="index-yazar-kimlik"><a href="profil/<?php echo seo_link($row['user']); ?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a></div>
                  </div>
                  <div style="width: 100%; height: 40px; background-color: #eeeeee; float: left;"></div><?php
               }
            }
            else if($sorgu->rowCount()==0)
            {
               echo "Bu kategoriye ait hiç konu açılmamış.";
            }
         }
         ?>
      </div>
   </body>
</html>