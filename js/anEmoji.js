window.addEventListener('DOMContentLoaded', function(){_i('emoji').addEventListener('click', copyEmoji);});

function copyEmoji()
{
 var a,b,c,d;
 a=_i('code'); b=_is('copied'); c=1500;
 a.select(); 
 document.execCommand("copy");
 a.blur();
 b.display='flex';
 try{clearTimeout(d); d=setTimeout(function(){b.display='none';}, c);} catch(ex){}
}