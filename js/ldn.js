
window.addEventListener('load', function(){initLdn(); liveSearch();});

function liveSearch()
{
 var a,b,c,d,e,ee;
 a = _f().src; b=_i('srcQ'); c=_i('predict'); /*d=c.getElementsByTagName('button'); e=d.length;*/
 b.addEventListener('focus', function(){try{clearTimeout(ee);}catch(ex){} c.style.display='block';});
 b.addEventListener('blur', function(){ee=setTimeout(function(){c.style.display='none';}, 200);});
 b.addEventListener('keyup', function(e){e.keyCode==13 ? a.submit() : xhr(this.value);});
 a.addEventListener('click', function(e){var t=e.target; if(t.nodeName.toUpperCase()=='B' && t.parentNode.id=='predict'){b.focus(); _p(e); b.value=t.attributes.q.value; a.submit();}});

 function xhr(y)
 {
  var x,z,f,g,h;
  x =  new XMLHttpRequest();
  x.onerror = function(){console.log('Error', x);};
  x.onload = function(){
   try{z=JSON.parse(x.responseText);} catch(ex){console.log(ex); return 0;}
   f = z.length; h='';
   for(g=0; g<f; g++)
   {
    h+='<b q="'+z[g].name+'">'+z[g].code+' - '+z[g].name+'</b>';
   }
   c.innerHTML=h;
  };
  x.open("GET", '?q='+y);
  x.setRequestHeader("liveS", "predict");
  x.send();
 }
}

var ldnTm = Date.now();
function initLdn()
{
 var a,b,c,d;
 a = (Date.now() - ldnTm); b=_is('bdy'); c=_is('prlLd'); d=2100;
 if(a<d){d=setTimeout(outLd, (d-a));}
 else{outLd();}

 function outLd()
 {
  b.overflow='auto';
  c.display='none';
 }
}