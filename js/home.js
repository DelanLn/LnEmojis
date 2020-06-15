window.addEventListener('DOMContentLoaded', initHome);

function initHome()
{
 var a,b,c,d,e,f,g;
 a=_i('emojis').getElementsByTagName('a'); b=a.length;
 for(c=0; c<b; c++)
 {
  a[c].addEventListener('mouseover', function(){this.scrollIntoView({block:'center',inline:'center',behaviour:'smooth'}); this.focus();});
 }
}