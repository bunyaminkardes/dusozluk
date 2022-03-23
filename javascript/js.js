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
    document.getElementById("yanbar-kategori").style.display="none";
};

function ipucugoster(str)
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

if(bildirimZili!=null)
{
    bildirimZili.onclick = function ()
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
    bildirimZili.onblur = function()
    {
        setTimeout(function()
        {
            bildirimZiliAcilirMenu.style.display='none';
        },100);
    }
}

aramaKutusu.onblur = function()
{
    setTimeout(function()
    {
        ajaxlivesearch.style.display='none';
    },100);
}

mobilKategoriButonu.onblur = function()
{
    setTimeout(function()
    {
        mobilKategori.style.display='none';
    },100);
}

masaustuKategoriButonu.onblur = function()
{
    setTimeout(function()
    {
        yanbarKategori.style.display='none';
    },100);
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

masaustuKategoriButonu.addEventListener("click",function()
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

mobilKategoriButonu.addEventListener("click",function()
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