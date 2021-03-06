// Degisken tanimlamalari
var ipucuDiv = document.getElementById("ipucu");
var yukleniyor = document.getElementById("yukleniyorgif");
var aramaKutusu = document.getElementById("ara");
var mobilKategoriButonu = document.getElementById("kategoriacbutonu");
var masaustuKategoriButonu = document.getElementById("yanbar-kategori-butonu");
var mobilKategori = document.getElementById("mobilkategori"); 
var yanbarKategori = document.getElementById("yanbar-kategori");
var ajaxlivesearch = document.getElementById("ajaxlivesearch");
var bildirimZili = document.getElementById("bildirimZiliKapsayiciButon");
var bildirimZiliAcilirMenu = document.getElementById("bildirimZiliAcilirMenu");


function ipucugoster(str) //live search yapabilmek icin ajax fonksiyonu.
{
    if(str.length == 0)
    {
        ipucuDiv.innerHTML="";
        yukleniyor.style.display="none";
        return;
    }
    else
    {
        var xmlhttpnesnesi = new XMLHttpRequest();
        xmlhttpnesnesi.onreadystatechange=function()
        {
            yukleniyor.style.display="block";
            if(this.readyState == 4 && this.status == 200)
            {
                ipucuDiv.innerHTML=this.responseText;
                yukleniyor.style.display="none";
            }
        };
        xmlhttpnesnesi.open("GET","livesearch.php?q="+str,true);
        xmlhttpnesnesi.send();
    }
}

if(bildirimZili!=null) //bildirim zili sadece kullanici giris yaptiginda var olan bir sey, yani kullanici giris yaptiysa bu if blogu calisacak.
{
    bildirimZili.onclick = function () //bildirim ziline basildiysa bildirimler penceresi gosterilsin.
    {
        if(bildirimZiliAcilirMenu.style.display !== "none")
        {
            bildirimZiliAcilirMenu.style.display = "none";
        }
        else
        {
            bildirimZiliAcilirMenu.style.display = "block";
        }
    };
    bildirimZili.onblur = function() //bildirim zilinden cikildiginda bildirimler penceresi kapansin.
    {
        setTimeout(function()
        {
            bildirimZiliAcilirMenu.style.display='none';
        },200);
    }
}

aramaKutusu.onblur = function() //arama kutusundan cikildiginda arama onerileri kapansin.
{
    setTimeout(function()
    {
        ajaxlivesearch.style.display='none';
    },200);
}

mobilKategoriButonu.onblur = function() //mobil kategori butonundan cikildiginda mobil kategori kapansin.
{
    setTimeout(function()
    {
        mobilKategori.style.display='none';
    },200);
}

masaustuKategoriButonu.onblur = function() //masaustu kategori butonundan cikildiginda yanbar kategoriden cikilsin.
{
    setTimeout(function()
    {
        yanbarKategori.style.display='none';
    },200);
}

document.addEventListener('DOMContentLoaded', function () //arama kutusu bos oldugunda ekranda hala arama onerilerinin cikmasini engellemek icin.
{
    aramaKutusu.addEventListener("input", function() 
    { 
        if (aramaKutusu.value==="")
        {
            ajaxlivesearch.style.display='none';
        }
        else
        {
            ajaxlivesearch.style.display='block';
        }
    });
});

masaustuKategoriButonu.addEventListener("click",function() // masaustu kategori butonuna basildiginda yanbar kategorinin gosterilmesi icin.
{
    if (yanbarKategori.style.display !== "none") 
    {
        yanbarKategori.style.display = "none";
    } 
    else 
    {
        yanbarKategori.style.display = "block";
    }
});

mobilKategoriButonu.addEventListener("click",function() // mobil kategori butonuna basildiginda mobil kategorinin gosterilmesi icin.
{
    if (mobilKategori.style.display !== "none") 
    {
        mobilKategori.style.display = "none";
    } 
    else 
    {
        mobilKategori.style.display = "block";
    }
});

function kategorigostergizle($goster,$gizle1,$gizle2,$gizle3,$gizle4,$gizle5,$gizle6,$gizle7,$gizle8,$gizle9) //hangi kategori secildiyse digerlerini gostermesin.
{
    document.getElementById($goster).style.display="block";
    document.getElementById($gizle1).style.display="none";
    document.getElementById($gizle2).style.display="none";
    document.getElementById($gizle3).style.display="none";
    document.getElementById($gizle4).style.display="none";
    document.getElementById($gizle5).style.display="none";
    document.getElementById($gizle6).style.display="none";
    document.getElementById($gizle7).style.display="none";
    document.getElementById($gizle8).style.display="none";
    document.getElementById($gizle9).style.display="none";
    yanbarKategori.style.display="none";
};