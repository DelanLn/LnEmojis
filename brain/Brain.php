<?php
//Ln Brain 1.0

class Brain
{
 protected $title, $cssExt, $cssIn, $jsExt, $jsExtEnd, $jsIn, $seo, $moreHd;
 protected $header, $main, $aside, $section, $nav, $footer, $noPrint;
 private $config, $baseUrl;

 function __construct($a='')
 {
  $this->initAll();
  //print_r($this->config);
  $this->title = ($a ? $a : $this->config->com);
 }

 private function initAll()
 {
  $this->configCheck();
  date_default_timezone_set($this->config->timezone);
  $this->cssExt = $this->cssIn = $this->jsExt = $this->jsExtEnd = $this->jsIn = $this->seo = $this->moreHd=[];
  $this->noPrint=0;
 }

 private function configCheck()
 {
  $this->config = json_decode($this->getFile(__DIR__.'/config.json'));
  $this->config = empty($this->config) ? (object)[] : $this->config;
  $this->a_('timezone', 'Africa/Nairobi');
  $this->a_('com', 'Ln Brain');
  $this->a_('host', '/');
  $this->a_('devMode', true);
  $this->a_('templates', 'tmplt');
  $this->a_('mainTmplt', 'index');
  $this->a_('tmpltExt', '.htm');
 }
 private function a_($a,$b){if(!isset($this->config->{$a})){$this->config->{$a} = $b;}}

 protected function setTitle($ttl,$b=0){$this->title=($b ? $ttl.' - '.$this->config->com : $ttl);}

 protected function setSeo($a,$b,$c='keywords',$d='description'){array_push($this->seo, [$c=>$a, $d=>$b]);}

 protected function setMoreHd($a){array_push($this->moreHd, $a);}

 protected function incCssExt($css)
 {
  if(!$this->config->devMode){$css=str_ireplace('.php', '.css', $css);}
  array_push($this->cssExt, $this->config->host.$css);
 }

 protected function incCssIn($css){array_push($this->cssIn, $css);}

 protected function incJsExt($js){if(!$this->devMode){$js=str_ireplace('.php', '.js', $js);} array_push($this->extJs, $this->config->host.$js);}

 protected function incJsExtEnd($js)
 {
  if(!$this->config->devMode){$js=str_ireplace('.php', '.js', $js);} 
  array_push($this->extJsEnd, $this->config->host.$js);
 }

 protected function incJsIn($js){array_push($this->jsIn, $this->getFile($js, true));}

 protected function getImg($img,$lnk='',$a=0){return $lnk ? $lnk : ($this->config->host.($a ? 'img/' : '').$img);}

 protected function getTmplt($fl)
 {
  $fl = __DIR__.'/'.$this->config->templates."/".$fl.$this->config->tmpltExt;
  if(!file_exists($fl)) return false;
  return file_get_contents($fl);
 }

 protected function getFile($path, $bool=false)
 {
  if(!file_exists($path)) return ($bool ? false : 'File '.$path.' was not found');
  $content=file_get_contents($path);
  if($content===false){return ($bool ? false : 'Failed to read file content');}
  return $content;
 }

 protected function addHeadr($c){$this->header .= $c;}

 protected function addMain($c){$this->main .= $c;}

 protected function addFootr($c){$this->footer .= $c;}

 protected function fltr($s, $r, &$v){$v = str_ireplace($s, $r, $v); return $v;}

 protected function someError($a, $b=['errLvl'=>0])
 {
  if($b['errLvl']){die($a);}
  else{echo $a;}
 }

 protected function iniHtmls($a, &$b, $c)
 {
  if(!is_array($a)){$this->someError('First argument must be an array');}
  $tmp = $this->getTmplt($c);
  foreach($a as $k => $v){$this->fltr($k, $v, $tmp);}
  $b = $tmp;
 }

 protected function printPage()
 {
  if($this->noPrint) return 0;
  //$this->iniHtmls(['{cssIn}'=>'some css'], $this->header);
  $pg = $this->getTmplt($this->config->mainTmplt);
  $this->fltr('{ttl}', $this->title, $pg);
  
  $tmp='<script type="text/javascript">'; foreach($this->jsIn as $v){$tmp.=$v;} $tmp.='</script>'; $this->fltr('{jsIn}', $tmp, $pg);

  $tmp='<style>'; foreach ($this->cssIn as $v){$tmp.=$v;} $tmp.='</style>'; $this->fltr('{cssIn}', $tmp, $pg);

  $tmp=''; foreach($this->cssExt as $v){$tmp.='<link rel="stylesheet" type="text/css" href="'.$v.'">';} $this->fltr('{cssExt}', $tmp, $pg);

  $tmp=''; foreach($this->jsExt as $js){$tmp.='<script type="text/javascript" src="'.$js.'"></script>';} $this->fltr('{jsExt}', $tmp, $pg);

  $tmp=''; foreach($this->jsExtEnd as $js){$tmp.= '<script type="text/javascript" src="'.$js.'"></script>';} $this->fltr('{jsExtEnd}', $tmp, $pg);

 $tmp=''; foreach($this->moreHd as $v){$tmp.=$v;} $this->fltr('{moreHd}', $tmp, $pg);

 $tmp=''; foreach($this->seo as $v){foreach($v as $k => $d){$tmp.= '<meta name="'.$k.'" content="'.$d.'">';}} $this->fltr('{seo}', $tmp, $pg);

 $this->fltr('{favicon}', $this->getImg('pl.png','',1), $pg);
 $this->fltr('{header}', $this->header, $pg);
 $this->fltr('{main}', $this->main, $pg);
 $this->fltr('{footer}', $this->footer, $pg);
 $this->fltr("\r\n", '', $pg);
 echo $pg;
 }
}

?>