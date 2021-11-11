function goster()
{
    document.getElementById("mobilkategori").style.display="block";
}

function kategori_goster()
{
    document.getElementById("yanbar-kategori").style.display="block";
}
function kategorigostergizle($gosterilecekolan,$gizlenecekolan1,$gizlenecekolan2,$gizlenecekolan3,$gizlenecekolan4,$gizlenecekolan5,$gizlenecekolan6,$gizlenecekolan7,$gizlenecekolan8,$gizlenecekolan9)
{
    document.getElementById($gosterilecekolan).style.display="block";
    document.getElementById($gizlenecekolan1).style.display="none";
    document.getElementById($gizlenecekolan2).style.display="none";
    document.getElementById($gizlenecekolan3).style.display="none";
    document.getElementById($gizlenecekolan4).style.display="none";
    document.getElementById($gizlenecekolan5).style.display="none";
    document.getElementById($gizlenecekolan6).style.display="none";
    document.getElementById($gizlenecekolan7).style.display="none";
    document.getElementById($gizlenecekolan8).style.display="none";
    document.getElementById($gizlenecekolan9).style.display="none";
    document.getElementById("yanbar-kategori").style.display="none";
};

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


var input = document.getElementById("ara");
document.addEventListener('DOMContentLoaded', function () 
{
    input.addEventListener("input", function() 
    { 
        if (input.value==="")
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



const hedefdiv = document.getElementById("mobilkategori");
const buton = document.getElementById("kategoriacbutonu");
buton.onclick = function () 
{
    if (hedefdiv.style.display !== "none") 
    {
        hedefdiv.style.display = "none";
    } 
    else 
    {
        hedefdiv.style.display = "block";
    }
};

const hedefdiv2 = document.getElementById("yanbar-kategori");
const buton2 = document.getElementById("yanbar-kategori-butonu");
/*
    butonlara bir kere basildiginda ikinci kez basilamiyordu, onun yuzunden bunlari ekledik.
    hedefdivin display ozelligi none degilse none yapiyor yani kapatiyor. none ise de block
    yapiyor yani aciyor. divin display ozelligine none verdigimizde sorunsuz calisiyor ancak
    bunu external css olarak verdigimizde calismiyor, inline olarak verdigimizde calisiyor.
*/
buton2.onclick = function () 
{
    if (hedefdiv2.style.display !== "none") 
    {
        hedefdiv2.style.display = "none";
    } 
    else
    {
        hedefdiv2.style.display = "block";
    }
};