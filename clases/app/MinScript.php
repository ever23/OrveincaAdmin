<?php 

/*******************************************************************************
* <h1>MinScript CLASS </h1>                                                    *
* <h2>ELIMINA LOS COMENTARIOS DE UNA LINEA,ESPACIOS, TABULACIONES              *        
*  Y SALTOS DE LINEAS  SOBRANTES DE SCRIPTS HTML,CSS,JS,JSON</h2>              *
*                                                                              *
* Version: 1.0.1.0                                                             *
* Fecha:    2015-08-16                                                         *
* Autor:  ENYREBER FRANCO                                                      *
* Email:  enyerverfranco@gmail.com , enyerverfranco@outlook.com                *
*******************************************************************************/
class MinScript
{
	/*<b>1.0.1.0</b>*/
	public  $version='1.0.1.0';//version 
	protected $HTML=array('script'=>0,'style'=>0,'head'=>0,'pre'=>0);//etiquetas html 
	protected $HTML_HEAD='';
	protected $is_info=true;
	protected $script_acet=array('html','css','js','json');
	protected $scripthtml="";
	protected $scriptjs="";
	protected $scriptcss=""; 
	protected $conten='';
	/**METODOS PUBLICOS */

	public function __construct($str=NULL,$type='html')
	{
		$this->IniTag();
		if(!is_null($str))
		{
			$this->conten=$this->Min($str,$type);	
		}	
	}
	public final function GetScriptAcet($type)
	{
		if(in_array($type,$this->script_acet))
		{
			return true;
		}else
		{
			return false;
		}
	}
	public function GetHtml()
	{
		if(trim($this->scripthtml)!="")
		{
			return "<!--".$this->GetInfo()."-->".$this->scripthtml;
		}
		return "";	
	}
	public function GetJs()
	{
		if(trim($this->scriptjs)!="")
		return "/*".$this->GetInfo()."*/".$this->scriptjs;
		return "";	
	}
	public function GetCss()
	{
		if(trim($this->scriptjs)!="")
		return "/*".$this->GetInfo()."*/".$this->scriptcss;
		return "";	
	}
/**
 <b>QUITA LOS COMENTARIOS DE UNA LINEA,
 ESPACIOS, TABULACIONES Y SALTOS DE LINEAS  SOBRANTES DE UNA CADENA HTML,CSS,JS,JSON</b> 
 @param $cadena cadena de caracteres HTML,CSS,JS,JSON
 @param $type tipo de texto HTML,CSS,JS,JSON
 @return CADENA DE TEXTO
*/
	public function Min($cadena=NULL,$type='html')
	{
		if(is_null($cadena))
		{
			return $this->conten;
		}
		$buff='';
		//return $cadena;
		switch(strtolower($type))
		{
			case 'css':
			if($this->is_info==true)
				$buff="/*".$this->GetInfo()."*/";
			$buff.=$this->CssMin($cadena);
			break;	
			case 'json':
			if($this->is_info==true)
				
			$buff=$this->JsMin($cadena);
			break;
			case 'js':
			if($this->is_info==true)
				$buff="/*".$this->GetInfo()."*/";
			$buff.=$this->JsMin($cadena);
			break;
			default:
			if($this->is_info==true)
				$buff="<!--".$this->GetInfo()."-->";
			$buff.=$this->HtmlMin($cadena);
			break;
		}
		$this->conten=$buff;
		//return implode('',$palabras);
		return $this->conten;
	}
	/**
 <b>QUITA LOS COMENTARIOS DE UNA LINEA,
 ESPACIOS, TABULACIONES Y SALTOS DE LINEAS  SOBRANTES DE UN ARCHIVO HTML,CSS,JS,JSON </b>
 @param $filename ruta del archivo HTML,CSS,JS,JSON
 @param $sifij sufijo para el nombre del archivo de salida HTML,CSS,JS,JSON
 @param mixes $ouput si es booleano true indica si se guardara en un archivo con el sufijo($sifij) y false para retornar el contenido si es un string indica el nombre de archivo de salida
 @param $typo indica el tipo de archivo  HTML,CSS,JS,JSON
 @return array el indice es el nombre del archivo el valor es el nombre del archivo de salida
*/
	public function FileMin($filename,$sifij='min',$ouput=true,$typo='')
	{
		$new_dir='';
		if(!is_array($filename))
		{
			$archivos=array(0=>$filename);
		}else
		{
			$archivos=$filename;
			// throw new Exception("NO PUEDE CONCATENAR ARCHIVOS HTML");
		}
		$f='';
		if(is_string($ouput))
		{
			$new_dir=$ouput;
		}elseif(is_bool($ouput) && $ouput)
		{
			if($sifij!='')
				$new_dir=$this->SetSifij($new_fil,$sifij);
		}
		$files=implode(",\n",$archivos);
		$coment="\n".$new_dir." ".date('Y-m-d H:i:s')."\nCONTEN FILES\n" .implode("\n",$archivos)."\n";
		switch(strtolower($typo))
		{
			case 'css':
			if($this->is_info)
				$info="/*".$this->GetInfo().$coment."*/";
			break;	
			case 'js':
			if($this->is_info)
				$info="/*".$this->GetInfo().$coment."*/";
			break;
				case 'json':
				$info='';
			break;
			default:
			if($this->is_info)
				$info="<!--".$this->GetInfo()."-->";
			break;
		}
		$new='';
		$new_fil=$archivos[count($archivos)-1];
		if($typo=='')
		{
			$typo=$this->GetType($archivos[count($archivos)-1]);
		}	
		$this->is_info=false;
		foreach($archivos as $file)
		{
			if(!($conten=$this->FileRead($file)))
			{

				throw new Exception("EL ARCHIVO ".$file." NO EXISTE");
				return  array($file=>"!file_exists(".$file.")");
			}
			if($this->IsSifij($file,'min'))
			{
				$new.=$conten;
			}else
			{
				$new.=$this->Min($conten,$typo);
			}	
		}
		$new_content=$info.$new;
		$this->is_info=true;
		if(is_bool($ouput))
		{
			if($ouput)
			{
				$this->FileWrite($new_dir,$new_content);
				return array($files=>$new_dir);
			}else
			{
				return $new_content;
			}
		}elseif(is_string($ouput))
		{
			$this->FileWrite($ouput,$new_content);
			return array($files=>$ouput);
		}else
		{
			return $new_content;
		}
	}
	/**
	<b>QUITA LOS COMENTARIOS DE UNA LINEA,
 	ESPACIOS, TABULACIONES Y SALTOS DE LINEAS  SOBRANTES DE TODOS LOS ARCHIVOS  HTML,CSS,JS,JSON  DE UN DIRECTORIO  </b>
	@param string $dir directorio 
    @param string $sifij sufijo para el nombre del archivo de salida HTML,CSS,JS,JSON
	@param mixes $tipos tipos de archivos que se buscaran  HTML,CSS,JS,JSON
	@param $filesave si se le pasa un directorio de archivo almacena todo el contenido de los archivos encontrados en $dir 
	@return array el indice es el nombre de los archivo el valor es el nombre del archivo de salida
	*/
	public function DirMin($dir,$sifij='min',$tipos=NULL,$filesave=NULL)
	{
		$files=$this->DirSearch($dir,$tipos);;
		$ret_files=array();
		//return $files;
		if(!is_null($filesave))
		{
			$ret_files=$this->FileMin($files,'',$filesave,is_array($tipos)?$tipos[0]:$tipos);
		}else
		{
			foreach($files as $file)
			{
				$ret_files=array_merge($ret_files,$this->FileMin($file,$sifij,true,$this->GetType($file)));
			}	
		}
		return $ret_files;
	}

	private function DirSearch($dir,$tipo=NULL)
	{
		$piladir=array();
		$tpos=array();
		if(!is_null($tipo) && !is_array($tipo))
		{
			$tpos=array($tipo);
		}
		$direct=  dir($dir);
		while($fichero=$direct->read())
		{
			if($fichero!='.' && $fichero!='..')
			{
				$ext='';
				$fic=explode('.',$fichero);
				if(count($fic)>1)
					$ext=$this->GetType($fichero);
				if(is_null($tipo))
				{
					$a=$this->GetScriptAcet($ext);
				}else
				{
					$a=in_array($ext,$tpos);
				}
				if(count($fic)>1)
				{
					if($a)
						array_push($piladir,$dir.$fichero);
					//$this->InsetDir($dir.$fichero);
				}elseif(count($fic)==1)
				{
					$piladir=array_merge($piladir,$this->DirSearch($dir.$fichero."/",$tipo));
				}
			}
		}
		$direct->close();
		return $piladir;
	}

	private function GetType($file)
	{
		$fic=explode('.',$file);
		return  strtolower(array_pop($fic));
	}

	private function  SetSifij($file,$sufij)
	{
		$fic=explode('.',$file);
		$ext= strtolower(array_pop($fic));
		return implode('.',$fic).".".$sufij.".".$ext;
	}

	private function IsSifij($file,$sifij)
	{
		$fic=explode('.',$file);
		$ext= $fic[count($fic)-2];
		return $ext===$sifij;
	}


	/**METODOS PRIVADOS **/

	private function HtmlMin($html_script)
	{
		$new_html='';
		$js=$css=$this->HTML_HEAD="";
		// Intérprete de HTML
		$a = preg_split('/<(.*)>/U',$html_script,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{

			if($i%2==0)
			{
				if($this->HTML['style']>0)
				{
					$css.=$e;

					//  $new_html.=$this->CssMin($e);
				}
				if($this->HTML['script']>0)
				{

					$js.=$e;
					// $new_html.=$this->JsMin($e);
				}
				if($this->HTML['pre']>0)
				{

					$new_html.=$e;
					// $new_html.=$this->JsMin($e);
				}
				if(max($this->HTML)==0)
				{
					
					$cont=explode(" ",str_replace("\n"," ",trim($e)));
					foreach($cont as $ic=>$icont)
					{
						if(trim($icont)==""  || trim($icont)=="\t")
						{
							unset($cont[$ic]);
						}else
						{
							$cont[$ic]=trim($icont);
						}
					}
					
					$new_html.=implode(' ',$cont);
				}
			}
			else
			{
				if($this->HTML['script']>0)
				{
					if(strtolower(substr($e,0,7))=='/script')
					{
						$this->CloseTag(strtolower(substr($e,1)));

					}else
					{
						$js.=$this->DetectTag($e);
					}
					// $new_html.=$this->JsMin($e);
				}elseif($this->HTML['style']>0)
				{
					if(strtolower(substr($e,0,6))=='/style')
					{
						$this->CloseTag(strtolower(substr($e,1)));
					}
				}else
					$new_html.=$this->DetectTag($e);
			}
		}
	
		$this->HTML_HEAD='';
		$this->scriptjs=$this->JsMin($js);
		$this->scriptcss=$this->CssMin($css);
		 
	//	exit;
		if(trim($css)!='')
		{
			$this->HTML_HEAD.="<style>".$this->scriptcss."</style>";
		}
		if(trim($js)!='')
		{
			$this->HTML_HEAD.="<script>/*<![CDATA[*/".$this->scriptjs."/*]]>*/</script>";

		}
		$this->scripthtml=$new_html;
		$this->IniTag();
		return $this->AddHead($new_html);
	}
	protected function AddHead($html)
	{
		$head=$this->HTML_HEAD;
		$a=explode('</head>',$html);
		$a[0].=$head;
		return implode('</head>',$a);
	}
	protected function IniTag()
	{
		foreach($this->HTML as $i=>$tag)
		{
			$this->HTML[$i]=0;
		}

	}
	protected function DetectTag($e)
	{
		$a2 = explode(' ',$e);
		$tag = strtolower(array_shift($a2));
		$attr =implode(' ',$a2);
		$etiqueta='';
		if($e[0]=='/')
		{
			$etiqueta.=$this->CloseTag(strtolower(substr($e,1)));
		}
		else
		{
			// Extraer atributos
			$etiqueta.=$this->OpenTag($tag,$attr);
		}
		return $etiqueta;
	}
	protected function OpenTag($tag, $attr)
	{
		// Etiqueta de apertura
		switch($tag)
		{
			case 'script':
			$this->HTML[$tag]++;
			$src=explode('src',strtolower($attr));
			if(count($src)==1)
			{
				return '';
			}
			else
			{
				return '<'.$tag." ".$this->Attr($attr)."></".$tag.">";
			}
			break;
			case 'style':
			$this->HTML[$tag]++;
			return '';
			break;
			case 'pre':
			$this->HTML[$tag]++;
			break;
		}
		if($attr!='')
		{
			return '<'.$tag." ".$this->Attr($attr).">";
		}
		return '<'.$tag.">";
	}
	protected function Attr($attributos)
	{
		return $this->CssMin($attributos);
	}
	protected function CloseTag($tag)
	{

		switch($tag)
		{
			case 'script':
			$this->HTML[$tag]-=($this->HTML[$tag]>0?1:0);
			break;
			case 'style':
			$this->HTML[$tag]-=($this->HTML[$tag]>0?1:0);
			break;
			case 'pre':
			$this->HTML[$tag]=0;
			break;
		}
		return '</'.$tag.">";
	}
	protected function GetInfo()
	{
		return "! MinScript v@".$this->version." 2015-06-7\nAutor:ENYREBER FRANCO\nEmail:enyerverfranco@gmail.com ,enyerverfranco@outlook.com";
	}
	private function CssMin($css_script)
	{
		$chars=array('{','}',';',':','[',']','=','"',"'",'>','/*','*/',',');
		//preg_match("/\/\*(.*)\*\//", $css_script,$preg);

		//$css_script=implode(preg_split("/\/\*.*\*\//",$css_script,-1));

		$css=preg_replace("/\/\*(.*)\*\//U","",$css_script);
		$cadena='';
		foreach($chars as $char)
		{
			$lineas=explode($char,$css);
			for($i=0;$i<count($lineas);$i++)
				$lineas[$i]=trim($lineas[$i]);
			$css=implode($char,$lineas);

		}
		$css=str_replace('﻿','',(string)$css);
		$this->scriptcss=(string)$css;
		return (string)$css;
	}
	private function JsMin($js_script)
	{
	
		$chars=array(';',':',',','[',']','(',')','=','/','{','}','|','&','+','-','*','!','?','.','>','<','%','else');
		$js='';
		$cadena='';
		
		$js_script=preg_replace("(//.*\n)","",$js_script);
		$js_script=preg_replace("/\/\*(.*)\*\//U","",$js_script);
		$js_script=str_replace("\n","",$js_script); 
		
		foreach(explode("\n",$js_script) as $scr)
		{
			$js.=(substr(trim($scr),0,2)=='if'?' ':NULL).trim($scr);
		}
		foreach($chars as $char)
		{
			$cadenas=explode($char,$js);
			for($i=0;$i<count($cadenas);$i++)
			{
				$cadenas[$i]=trim($cadenas[$i]);
				if($i<count($cadenas)-1)
				{
					if($char=='else')
					{
						$cadenas[$i+1]=' '.$cadenas[$i+1];
					}
					$csig=trim($cadenas[$i+1]);
					$sig=$this->uc($csig);
					if($char==='}'  &&  substr($csig,0,4)!=='else' &&  substr($csig,0,5)!=='catch'
					   &&((ord($sig)>=65 && ord($sig)<=90)||(ord($sig)>=97 && ord($sig)<=122)|| ord($sig)===36))
					{
						$cadenas[$i+1]='; '.$csig;
					}
					
				}
				

			}
			$js=implode($char,$cadenas);
		}
		//$js=str_replace('﻿','',(string)$js);
		$this->scriptjs=$js;
		return $js;
	}
	protected function uc($cadena)
	{
		if(strlen($cadena)>0)
		{
			$a=substr($cadena,0,1);
			if(ord($a)==0)
			{
				return $this->uc(substr($cadena,1,strlen($cadena)-1));
			}else
			{
				return $cadena;
			}	
		}

	}
	private function FileRead($filename)
	{
		if(!file_exists($filename))
		{
			return false;
		}

		$fi=fopen($filename,'r');
		$conten=fread($fi,filesize($filename));
		fclose($fi);
		return $conten;
	}
	private function FileWrite($filename,$text)
	{
		$fi=fopen($filename,'w+');
		fwrite($fi,$text);
		fclose($fi);
	}

}