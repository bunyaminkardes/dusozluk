<?php
    $kategori = $_GET['kategori'];
    $konuid = 25; // hoşgeldin konusu hangi id'ye sahip ise manuel olarak girelim.
    $gundemLimit = 20;
    $rastgeleLimit = 20;
    $enTazeLimit = 20;
    $secilenKategoriLimit = 20;

    $ilkgirissorgusu = $baglanti->prepare("SELECT * FROM konular WHERE id = :konuid");
    $ilkgirissorgusu->bindParam(":konuid",$konuid,PDO::PARAM_INT);
    $ilkgirissorgusu->execute();

    $ilkgirisRastgeleSorgusu = $baglanti->prepare("SELECT * FROM konular ORDER BY rand() LIMIT :limitt"); //rand fonksiyonu büyük veritabanlarında yavaşlığa sebep olur.
    $ilkgirisRastgeleSorgusu->bindParam(":limitt",$rastgeleLimit,PDO::PARAM_INT);
    $ilkgirisRastgeleSorgusu->execute();

    $gundemSorgusu = $baglanti->prepare("SELECT * FROM konular ORDER BY likesayisi*0.40 + mesajsayisi*0.20 - dislikesayisi*0.40 DESC LIMIT :limitt");
    $gundemSorgusu->bindParam(':limitt',$gundemLimit,PDO::PARAM_INT);
    $gundemSorgusu->fetch(PDO::FETCH_ASSOC);
    $gundemSorgusu->execute();

    $enTazeSorgusu = $baglanti->prepare("SELECT * FROM konular ORDER BY tarihal DESC LIMIT :limitt");
    $enTazeSorgusu->bindParam(':limitt',$enTazeLimit,PDO::PARAM_INT);
    $enTazeSorgusu->fetch(PDO::FETCH_ASSOC);
    $enTazeSorgusu->execute();

    $kategoriSecmeSorgusu = $baglanti->prepare("SELECT * FROM konular WHERE konu_turu=:konu_turu ORDER BY tarihal DESC LIMIT :limitt");
    $kategoriSecmeSorgusu->bindParam(':limitt',$secilenKategoriLimit,PDO::PARAM_INT);
    $kategoriSecmeSorgusu->bindParam(':konu_turu',$kategori,PDO::PARAM_STR);
    $kategoriSecmeSorgusu->fetch(PDO::FETCH_ASSOC);
    $kategoriSecmeSorgusu->execute();
?>

<div class="index-content-div">
    <?php
    if(!isset($kategori))
    {
        foreach($ilkgirisRastgeleSorgusu as $row)
        {
            ?>
            <div id="index-konu">
                <h3 id="index-konu-baslik">
                    <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>"><?php print_r($row['konu_baslik']); ?></a>
                </h3>
                <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                <div class="row">
                    <div class="col-12 col-sm-12 col-lg-12">
                        <div id="index-yazar-kimlik">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="index-ghost-div"></div>
            <?php
        }
    }
    if($kategori == "gundem") // gündemdeki son 10 konu gösterilecek.
    {
        if($gundemSorgusu->rowCount()>0)
        {
            foreach($gundemSorgusu as $row)
            {
                ?>
                <div id="index-konu">
                    <h3 id="index-konu-baslik">
                        <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>"><?php print_r($row['konu_baslik']);?></a>
                    </h3>
                    <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                    <div id="index-yazar-kimlik">
                        <?php
                        if(isset($_SESSION['girisyapankullanici']))
                        {
                            ?>
                            <a href="profil/<?php echo seo_link($row['user']); ?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a>
                            <?php
                        }
                        else
                        {
                            ?>
                            <a href="../giris.php?pq=0"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="index-ghost-div"></div>
                <?php
            }
        }
    }
    else if($kategori == "enTaze") // en taze 10 konu gösterilecek.
    {
        if($enTazeSorgusu->rowCount()>0)
        {
            foreach($enTazeSorgusu as $row)
            {
                ?>
                <div id="index-konu">
                    <h3 id="index-konu-baslik">
                        <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id']; ?>"><?php print_r($row['konu_baslik']);?></a>
                    </h3>
                    <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                    <div id="index-yazar-kimlik">
                        <?php 
                        if(isset($_SESSION['girisyapankullanici']))
                        {
                            ?>
                            <a href="profil/<?php echo seo_link($row['user']); ?>"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a>
                            <?php
                        }
                        else
                        {
                            ?>
                            <a href="../giris.php?pq=0"><?php echo $row['tarih']." "." "."-"." ".$row['user']; ?></a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="index-ghost-div"></div>
                <?php
            }
        }
    }
    else // hangi kategori seçilmişse ona ait konular gösterilecek.
    {
        if($kategoriSecmeSorgusu->rowCount()>0)
        {
            foreach($kategoriSecmeSorgusu as $row)
            {
                ?>
                <div id="index-konu">
                    <h3 id="index-konu-baslik">
                        <a href="konular/<?php echo seo_link($row['konu_baslik'])."/".$row['id'];?>"><?php print_r($row['konu_baslik']);?></a>
                    </h3>
                    <p id="index-konu-icerik"><?php print_r(htmlentities($row['konu_icerik']));?></p>
                    <div id="index-yazar-kimlik">
                        <?php
                        if(isset($_SESSION['girisyapankullanici']))
                        {
                            ?>
                            <a href="profil/<?php echo seo_link($row['user']); ?>"><?php echo $row['tarih']." "." "."-"." ".$row['user'];?></a>
                            <?php
                        }
                        else
                        {
                            ?>
                            <a href="../giris.php?pq=0"><?php echo $row['tarih']." "." "."-"." ".$row['user'];?></a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="index-ghost-div"></div>
                <?php
            }
        }
    }
    ?>
</div>