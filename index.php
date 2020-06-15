<?php
require 'brain/Brain.php';

class Emojis extends Brain
{
 protected $page;

 function __construct()
 {
  parent::__construct();
  $this->incCssExt('css/main.css');
  $this->incCssExt('css/fa.css');
  $this->incJsIn('js/main.js');
  $this->incJsIn('js/ldn.js');
  $this->setPage();
  $this->printPage();
 }

 private function setPage()
 {
  $this->iniHtmls(['{logo}'=>$this->getImg('pl.png','',1), '{cat}'=>'?cat', '{val}'=>@$_POST['q']], $this->header, 'header');
  if(isset($_GET['q'], apache_request_headers()['liveS'])){$this->noPrint=1; echo $this->liveSearch($_GET['q']);}
  elseif(isset($_POST['q']) && $_POST['q']!=''){$this->addMain($this->search($_POST['q']));}
  elseif(isset($_GET['cat'])){$this->addMain($this->getCats());}
  elseif(isset($_GET['scat'])){$this->addMain($this->getSubCats());}
  elseif(isset($_GET['emjs'], $_GET['l'], $_GET['sct'])){$this->addMain($this->getAnEmoji()); $this->addMain($this->getEmojis());}
  elseif(isset($_GET['emjs'])){$this->addMain($this->getEmojis());}
  else{$this->addMain($this->allEmojis());}
 }

 private function allEmojis()
 {
  $this->incJsIn('js/home.js');
  $d = $this->getTmplt('home'); $e=''; $z=0;
  $dd = $this->getTmplt('homeEmj');
  $a = json_decode($this->getFile('data/data.json'));
  foreach($a as $b => $c)
  {
   $bb=urlencode($b);
   $c = json_decode(json_encode($c), 1);
   foreach($c as $h => $y)
   {
    $hh=urlencode($h);
    foreach($y as $v)
    {
     $x = $dd;
     $this->fltr('{a}', '?sct='.$bb.'&emjs='.$hh.'&l='.urlencode($v['name']), $x);
     $this->fltr('{b}', $v['code'][0], $x);
     $this->fltr('{c}', $v['name'], $x);
     $e.=$x; //if($z++ > 140)break;
    }
    //if($z > 140)break;
   }
   //if($z > 140)break;
  }
  $this->fltr('{emojis}', $e, $d);
  return $d;
 }

 private function search($q)
 {
  $d = $this->getTmplt('emoji'); $e=''; $z=0;
  $a = json_decode($this->getFile('data/data.json'));
  function checkCode($a,$b){foreach($b as $c){if(stripos($c, $a)!==false || stripos($a, $c)!==false)return 1;} return 0;}
  foreach($a as $b => $c)
  {
   $bb=urlencode($b);
   $c = json_decode(json_encode($c), 1);
   foreach($c as $h => $y)
   {
    $hh=urlencode($h);
    foreach($y as $v)
    {
     if(stripos($v['name'], $q)!==false || checkCode($q, $v['code']))
     {
      $f = $d; $i=''; $j=''; $z++;
      for($g=0; $g<count($v['code']); $g++){$i.=$v['code'][$g].' '; $j.=$v['raw'][$g].' ';}
      $this->fltr('{lnk}', '?sct='.$bb.'&emjs='.$hh.'&l='.urlencode($v['name']), $f);
      $this->fltr('{emj}', $i, $f);
      $this->fltr('{name}', $v['name'], $f);
      $this->fltr('{raw}', $j, $f);
      $this->fltr('{tag}', $v['tag'], $f); 
      $e.=$f; $llll=urlencode($v['name']); $lll=$hh; $ll=$bb;
     }
    }
   }
  }
  if($z==1){header("Location: ?sct=".$ll."&emjs=".$lll."&l=".$llll);}
  return '<h2>'.$z.' Results found</h2>'.$e;
 }

 private function liveSearch($q)
 {
  $e=[]; $z=0;
  $a = json_decode($this->getFile('data/data.json'));
  function checkCode($a,$b){foreach($b as $c){if(stripos($c, $a)!==false || stripos($a, $c)!==false)return 1;} return 0;}
  foreach($a as $b => $c)
  {
   $c = json_decode(json_encode($c), 1);
   foreach($c as $h => $y)
   {
    foreach($y as $v)
    {
     if(stripos($v['name'], $q)!==false || checkCode($q, $v['code']))
     {
      $i=''; $j=''; $z++;
      for($g=0; $g<count($v['code']); $g++){$i.=$v['code'][$g].' '; $j.=$v['raw'][$g].' ';}
      array_push($e, ['name'=>$v['name'], 'code'=>$i, 'raw'=>$j]);
     }if($z>=5) break;
    }if($z>=5) break;
   }if($z>=5) break;
  }
  return json_encode($e);
 }

 private function getEmojis()
 {
  $d = $this->getTmplt('emoji'); $e='';
  $z = $this->getTmplt('nav');
  $za = $this->getTmplt('nava'); $zc = $zb = $za;
  $this->fltr('{lnk}', '?cat', $za);
  $this->fltr('{nm}', 'Categories', $za);
  $this->fltr('{lnk}', '?scat='.urlencode($_GET['sct']), $zb);
  $this->fltr('{nm}', ucfirst($_GET['sct']), $zb);
  $this->fltr('{lnk}', '?sct='.urlencode($_GET['sct']).'&emjs='.urlencode($_GET['emjs']), $zc);
  $this->fltr('{nm}', ucfirst($_GET['emjs']), $zc);
  $this->fltr('{nav}', $za.$zb.$zc, $z);
  $this->addMain($z);
  $a = json_decode($this->getFile('data/data.json'))->{urldecode($_GET['sct'])}->{urldecode($_GET['emjs'])};
  foreach($a as $b => $c)
  {
   $f = $d; $i=''; $j=''; $c = json_decode(json_encode($c), 1);
   $m=urlencode($_GET['sct']); $n=urlencode($_GET['emjs']);
   for($g=0; $g<count($c['code']); $g++){$i.=$c['code'][$g].' '; $j.=$c['raw'][$g].' ';}
   $this->fltr('{lnk}', '?sct='.$m.'&emjs='.$n.'&l='.urlencode($c['name']), $f);
   $this->fltr('{emj}', $i, $f);
   $this->fltr('{name}', $c['name'], $f);
   $this->fltr('{raw}', $j, $f);
   $this->fltr('{tag}', $c['tag'], $f);
   $e.=$f;
  }
  return $e;
 }

 private function getAnEmoji()
 {
  $this->incJsIn('js/anEmoji.js');
  $d = $this->getTmplt('anEmoji'); $e='';
  $z = $this->getTmplt('nav');
  $za = $this->getTmplt('nava'); $zd = $zc = $zb = $za;
  $this->fltr('{lnk}', '?cat', $za);
  $this->fltr('{nm}', 'Categories', $za);
  $this->fltr('{lnk}', '?scat='.urlencode($_GET['sct']), $zb);
  $this->fltr('{nm}', ucfirst($_GET['sct']), $zb);
  $this->fltr('{lnk}', '?sct='.urlencode($_GET['sct']).'&emjs='.urlencode($_GET['emjs']), $zc);
  $this->fltr('{nm}', ucfirst($_GET['emjs']), $zc);
  $this->fltr('{lnk}', '?sct='.urlencode($_GET['sct']).'&emjs='.urlencode($_GET['emjs']).'&l='.urlencode($_GET['l']), $zd);
  $this->fltr('{nm}', ucfirst($_GET['l']), $zd);
  $this->fltr('{nav}', $za.$zb.$zc.$zd, $z);
  $this->addMain($z);
  $a = json_decode($this->getFile('data/data.json'))->{urldecode($_GET['sct'])}->{urldecode($_GET['emjs'])};
  foreach($a as $b => $c)
  {
   if($c->name == $_GET['l']){
    $f = $d; $i=''; $j=''; $c = json_decode(json_encode($c), 1);
    $m=urlencode($_GET['sct']); $n=urlencode($_GET['emjs']);
    for($g=0; $g<count($c['code']); $g++){$i.=$c['code'][$g].' '; $j.=$c['raw'][$g].' ';}
    $this->fltr('{lnk}', '?sct='.$m.'&emjs='.$n.'&l='.urlencode($c['name']), $f);
    $this->fltr('{emj}', $i, $f);
    $this->fltr('{name}', $c['name'], $f);
    $this->fltr('{raw}', $j, $f);
    $this->fltr('{cat}', ucfirst($_GET['sct']), $f);
    $this->fltr('{lcat}', '?scat='.urlencode($_GET['sct']), $f);
    $this->fltr('{scat}', ucfirst($_GET['emjs']), $f);
    $this->fltr('{lscat}', '?sct='.urlencode($_GET['sct']).'&emjs='.urlencode($_GET['emjs']), $f);
    $this->fltr('{tag}', $c['tag'], $f);
    $e.=$f;
   }
  }
  return $e;
 }

 private function getSubCats()
 {
  $d = $this->getTmplt('scat'); $e='';
  $z = $this->getTmplt('nav');
  $za = $this->getTmplt('nava'); $zb = $za;
  $this->fltr('{lnk}', '?cat', $za);
  $this->fltr('{nm}', 'Categories', $za);
  $this->fltr('{lnk}', '?scat='.urlencode($_GET['scat']), $zb);
  $this->fltr('{nm}', $_GET['scat'], $zb);
  $this->fltr('{nav}', $za.$zb, $z);
  $this->addMain($z);
  $a = json_decode($this->getFile('data/data.json'))->{urldecode($_GET['scat'])};
  foreach($a as $b => $c)
  {
   $f = $d; $c = json_decode(json_encode($c), 1);
   $this->fltr('{cats}', '?emjs='.urlencode($b).'&sct='.urlencode($_GET['scat']), $f);
   foreach($c as $h => $i){$this->fltr('{emj}', $i['code'][0], $f); break;}
   $this->fltr('{name}', $b, $f);
   $this->fltr('{count}', count($c), $f);
   $e.=$f;
  }
  return $e;
 }

 private function getCats()
 {
  $d = $this->getTmplt('cat'); $e='';
  $z = $this->getTmplt('nav');
  $za = $this->getTmplt('nava');
  $this->fltr('{lnk}', '?cat', $za);
  $this->fltr('{nm}', 'Categories', $za);
  $this->fltr('{nav}', $za, $z);
  $this->addMain($z);
  $a = json_decode($this->getFile('data/data.json'));
  foreach($a as $b => $c)
  {
   $f = $d; $c = json_decode(json_encode($c), 1);
   $this->fltr('{cats}', '?scat='.urlencode($b), $f);
   foreach($c as $h => $i){ $this->fltr('{emj}', $i[0]['code'][0], $f); break;}
   $this->fltr('{name}', $b, $f);
   $this->fltr('{count}', count($c), $f);
   $e.=$f;
  }
  return $e;
 }
}
new Emojis();
?>