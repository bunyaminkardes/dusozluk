var ipucuDiv = document.getElementById("ipucu");
var yukleniyor = document.getElementById("yukleniyor");
var aramaKutusu = document.getElementById("ara");
var mobilKategoriButonu = document.getElementById("kategoriacbutonu");
var masaustuKategoriButonu = document.getElementById("yanbar-kategori-butonu");
var mobilKategori = document.getElementById("mobilkategori"); 
var yanbarKategori = document.getElementById("yanbar-kategori");
var ajaxlivesearch = document.getElementById("ajaxlivesearch");
var bildirimZili = document.getElementById("bildirimZiliKapsayiciButon");
var bildirimZiliAcilirMenu = document.getElementById("bildirimZiliAcilirMenu");


function ipucugoster(str)
{
    if(str.length == 0)
    {
        document.getElementById("ipucu").innerHTML="";
        return;
    }
    else
    {
        var xmlhttpnesnesi = new XMLHttpRequest();
        xmlhttpnesnesi.onreadystatechange=function()
        {
            if(this.readyState == 4 && this.status == 200)
            {
                document.getElementById("ipucu").innerHTML=this.responseText;
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
}


function kategorigostergizle($goster,$gizle1,$gizle2,$gizle3,$gizle4,$gizle5,$gizle6,$gizle7,$gizle8,$gizle9) // hangi kategori seçildiyse diğerlerini göstermesin.
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

document.addEventListener('DOMContentLoaded', function () // arama kutusu boş olduğunda ekranda hala arama önerileri kutusu çıkmasını engellemek için.
{
    aramaKutusu.addEventListener("input", function() 
    { 
        if (aramaKutusu.value==="")
        {
            document.getElementById("ajaxlivesearch").style.display="none";
            document.getElementById("mobilkategori").style.display="none";
        }
        else
        {
            document.getElementById("ajaxlivesearch").style.display="block";
            document.getElementById("mobilkategori").style.display="none";
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
    //console.log("masaüstünde kategoriler butonuna basıldı.");
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
    //console.log("maobilde kategoriler butonuna basıldı.");
});