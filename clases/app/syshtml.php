<?php
/*
require_once('DocumentBuffer.php');
*/
class SysHtml extends DocumentBuffer
{
	protected $js= array();
	protected $css= array();
	protected $titulo="Default Document SysHtml";
	protected $ico;
	protected $ROOT_HTML;
	protected $foother=false;
	protected $script_error;
	protected $jsscript;
	protected $cssscript;
	protected $errores;
	protected $diseno='main';
	protected $filejs='';
	protected $filecss='';
	protected $extracfile=false;
	protected $src=array();
	public function __construct($compress=true,$min=true)
	{
		$this->foother=false;
		$this->errores='';
		if(!defined('__DOCUMENT_ROOT__'))
		{
			$this->ROOT_HTML="";
		}else
		{
			$this->ROOT_HTML=__DOCUMENT_ROOT__;
		}
		
		$this->SetSrc("{root}",$this->ROOT_HTML);
		$this->SetSrc("{src}",'{root}/src/');
		$this->script_error="";
		
		
		parent::__construct(true,$compress,$min);
		
		
	}
	public function __destruct()
	{
		if(!$this->foother)
		$this->PrintHtml();
		parent::__destruct();
	}
	public function SetSrc($seudo,$src)
	{
		$this->src[$seudo]=$this->ReplaceSrc($src);
	}
	protected function ReplaceSrc($text,$type='')
	{
		if(count($this->src)==0)
		{
			return $text;
		}
		foreach($this->src as $i=>$v)
		{
			$text=str_replace($i,$v.$type,$text);
		}
		return $text;
	}
	
	public function PrintHtml()
	{
		$this->foother=true;
		$this->ShowError();
		$content=parent::Conten();
		parent::Clear();
		
		$name=(dirname(__FILE__).'/../layouts/'.$this->diseno.'.php');
		require_once ($name);
		if($this->extracfile)
		$this->ExtacJsCssFile();
	}
	protected function ExtacJsCssFile()
	{
		$this->ContenMin();
		$this->minifi=false;
		parent::Clear();
		$js=$this->min->GetJs();
		$css=$this->min->GetCss();
		$html=$this->min->GetHtml();
		$head="";
		if($js!="")
		{
			$this->PutFile($this->filejs,$js);
			$head.="<script src='".$this->filejs."'type='text/javascript'></script>";
		}
		if($css!="")
		{
			
			$this->PutFile($this->filecss,$css);
			$head.="<link rel='stylesheet'type='text/css'href='".$this->filecss."'media='screen'/>";
		}
		$a=explode('</head>',$html);
		$a[0].=$head;
		echo implode('</head>',$a);;	
	}

	public function EneableExtacJsCssFile( $eneable,$js=NULL,$css=NULL)
	{
		if(is_null($js))	
			$js="{src}js/syshtml.min.js";
		if(is_null($js))	
			$css="{src}css/syshtml.min.css";
		
		$js=$this->ReplaceSrc($js);
		$css=$this->ReplaceSrc($css);
		$this->filejs=$js;
		$this->filecss=$css;
		$this->extracfile=$eneable;
	}
	
	public function PutFile($file,$conten)
	{
		$f=fopen($file,'w');
		fwrite($f,$conten);
		fclose($f);
	}
	public function SetLayout($file)
	{
		$this->diseno=$file;
	}
	public function add_error($error)
	{
		$this->errores.=$error;

	}
	protected function ShowError()
	{
		$this->add_error(SysExeption::GetExeptionS());
		
		if($this->errores!='')
		{
			$this->AddJsScript($this->script_error);
		}

	}
	public function GetContenHead()	
	{
		$js=$this->GetJsScript();
		$css=$this->GetCssScript();
		$head="<link rel='shortcut icon'  href='".$this->ico."'  media='monochrome'/>
		<title>".$this->titulo."</title>".$this->link_cssjs();
		if($js!="")
			$head.="<script type='text/javascript'>".$js."</script>";
			if($css!="")
			$head.="<style type='text/css'>".$css."</style>";
		return $head;
	}
	public function &addlink_js($name,$fn='P')
	{
		if(!is_array($name))
		{
			
			$new_name=$this->ReplaceSrc($name,'js/');
			if(empty($this->js) || !in_array($new_name,$this->js))
			{
				if($fn=='P')
				{
					array_push($this->js,$new_name);
				}elseif($fn=='U')
				{
					array_unshift($this->js,$new_name);
				}
			}
		}else
		{
			foreach(array_reverse($name) as $na)
			{
				$this->addlink_js($na,$fn);
			}
		}
		return $this;

	}
	public function &AddJsScript($js)
	{
		$this->jsscript.=$js;
		return $this;
	}
	public function GetJsScript()
	{
		return $this->jsscript;
	}
	
	public function &addlink_css($name,$fn='P')
	{
		if(!is_array($name))
		{
			$new_name=$this->ReplaceSrc($name,'css/');
			if(empty($this->css) ||!in_array($new_name,$this->css))
			{
				if($fn=='P')
				{
					array_push($this->css,$new_name);
				}elseif($fn=='U')
				{
					array_unshift($this->css,$new_name);
				}
			}
		}else
		{
			foreach(array_reverse($name) as $na)
			{
				$this->addlink_css($na,$fn);
			}
		}
		return $this;
	}
	public function &AddCssScript($css)
	{
		$this->cssscript.=$css;
		return $this;
	}
	public function GetCssScript()
	{
		return $this->cssscript;
	}
	public function &set_title($title)
	{
		$this->titulo=$title;
		return $this;
	}
	public function &set_ico($ico)
	{
		$this->ico=$this->ReplaceSrc($ico);
		return $this;
	}

	public function link_cssjs()
	{
		$link='';
	
		foreach($this->js as $js)
		{
			$link.= "<script src='".$js."'type='text/javascript'></script>";
		}
		foreach($this->css as $css)
		{
			$link.=  "<link rel='stylesheet'type='text/css'href='".$css."'media='screen' />";
		}
		return $link;

	}
	public static function FormHttpVar(array $HTTP_VAR)
	{
		$html='';
		foreach($HTTP_VAR as $i =>$val)
		{
			if(!is_array($val))
			{
				$html.='<input type="hidden"name="'.$i.'"value="'.$val.'">';
			}else
			{
				$html.=self::FormArray($i,$val);
			}
		
		}
		return $html;
	}
	public static function FormArray($name,array $array)
	{
		$html='';
		foreach($array as $i => $val)
		{
			if(!is_array($val))
			{
				$html.='<input type="hidden"name="'.$name.'['.$i.']"value="'.$val.'">';
			}else
			{
				$html.self::FormArray($i,$val);
			}
		}
		return $html;
	}
}