<?php
/*******************************************************************************
* <h1>SearchClass </h1>                                                        *
* <h2>BUSCA UNA CLASE DEFINIDA EN UN DIRECTORIO, FABRICA UN OBJETO O    PUEDE  *
*   ACTIVAR LA FUNCION __autoload            </h2>                             *
*                                                                              *
* Version: 1.0.1.5                                                             *
* Fecha:  2015-06-25                                                           *
* Autor:  ENYREBER FRANCO                                                      *
* Email:  enyerverfranco@gmail.com , enyerverfranco@outlook.com                *
*******************************************************************************/
include("SearchClassException.php");
class SearchClass
{	
	private  $dir;//directorio de busqueda
	private static $DIR_GLOBAL='';
	private static $AVANCE_GLOBAL=FALSE;
	private static $ext=array('php');//extenciones a buscar
	protected $class;//class name 
	protected $filename='';//nombre de archivo donde se encontro clase
	protected  $directorios=array();//archivos donde se busco
	protected static $DirFiles=array();
	protected $avance_searh=false;
	/**
	BUSCA UNA CLASE DEFINIDAD EN UN DIRECTORIO 
	@param string $class nombre de la clase a buscar 
	@param string $dir directorio donde se buscara la clase 
	*/
	public function __construct($class,$dir,$avance_searh=false)
	{
		$this->avance_searh=$avance_searh;
		$this->class=$class;
		$this->SetDir($dir);
		$this->directorios= array();
		
	}
	public static function GetDirFiles()
	{
		return self::$DirFiles;
	}
	/**
	FABRICA UN OBJETO DE LA CLASE PASADA EN EL PARAMETRO
	@param string $class NOMBRE DE LA CLASE 
	@param array $param PARAMETROS QUE SE LE PASARAN AL CONSTRUCTOR DE LA CLASE 
	@param string $dir DIRECTORIO DONDE SE BUSCARA LA CLASE 
	@return objet OBJETO INSTANCIADO DE LA CLASE 
*/
	public static function &Factory($class=NULL,$param=array(),$dir=NULL,$avance=NULL)
	{
		if(!self::Load($class,$dir,$avance,true,'include'))
		return NULL;
		$classname=NULL;
		$parametros='';
		foreach($param as $i=>$p)
		{
			$parametros.='$param["'.$i.'"],';
		}
		$parametros=substr($parametros,0,strlen($parametros)-1);
		$script='$classname= new '.$class.'('.$parametros.');';//instanciar el objeto
		try
		{
			eval($script);//evaluar el script
		}
		catch(SearchClassException $e)
		{
			die( $e);
		}
		return  $classname;
	}
/**
	FUNCION STATIC DEFINE LA FUNCION __autoload UNA VES LLAMADO ESTE METODO LAS CLASES SE CARGARAN DEL O LOS DIRECTORIOES ESPECIFICADOS 
	@param mixes $dir DIRECTORIO DONDE SE BUSCARA LA CLASE 
	@param bool $avance INDICA SI SEREALIZARA UNA BUSQUEDA AVANZADA
*/
	public static function StartAutoloadClass($dir,$avance=NULL)
	{
		self::$DIR_GLOBAL=$dir;
		self::$AVANCE_GLOBAL=$avance;
		if(function_exists('__autoload'))
			return;
		function __autoload($class){SearchClass::Load($class,NULL,NULL,true,'require_once');}
	}
	
/**
	BUSCA Y CARGA UNA CLASE 
	@param string $class NOMBRE SE LA CLASE A BUSCAR
	@param string $dir DIRECTORIO DONDE SE BUSCARA LA CLASE (OPCIONAL)
	@param bool $avance INDICA SI SEREALIZARA UNA BUSQUEDA AVANZADA
	@param bool $is_autoload IDICA SI SE USARA LA VARIABLE DIRECTORIO DE StartAutoLoadClass
	@return bool TRUE SI TUBO EXITO
*/
	public  static function Load($class,$dir=NULL,$avance=false,$is_autoload=false,$fnload=NULL)
	{
		if(is_null($fnload))
		{
			if(!defined('__FN_AUNTOLOAD__'))
			{
				$FN='include';
			}else
			{
				$FN=__FN_AUNTOLOAD__;
			}
		}else
		{
			$FN=$fnload;
		}
		if($is_autoload)
		{
			if(is_null($dir))
			{
				$dir=self::$DIR_GLOBAL;
			}
			if(is_null($avance))
			{
				$avance=self::$AVANCE_GLOBAL;		
			}
		}else
		{
			if(is_null($dir))
			{
				if(!defined('__DIR_AUNTOLOAD__'))
				{
					$dir=dirname(__FILE__)."/";
				}else
				{
					$dir=__DIR_AUNTOLOAD__;
				}
			}
		}
		$autoload= new SearchClass($class,$dir,$avance);
		if(($autoload->Search())==NULL && ($autoload->SetDir(NULL)->Search())==NULL)
		{
			$dir=$autoload->PrintArray($autoload->directorios);
			self::$DirFiles=array_merge(self::$DirFiles,array($class=>$autoload->directorios));;
			echo new SearchClassException(' Warning: CLASE "'.$class.'" NO ENCONTRADA O NO DEFINIDA EN EL DIRECTORIO ('.$dir.")"); 
			return false;
			
		}
		self::$DirFiles=array_merge(self::$DirFiles,array($class=>$autoload->directorios));;
	    $autoload->Include_($FN);
		return true;
	//	echo "<pre>".$class."<br>";print_r($autoload->directorios);
	}
	public function Include_($FN)
	{
		switch($FN)
		{
			case "include":
			include $this->filename;
			break;
			case "include_once":
			include_once $this->filename;
			break;
			case "require":
			require $this->filename;
			break;
			case "require_once":
			require_once $this->filename;
			break;
			default:
			echo new SearchClassException(" ".$FN." NO ES UNA FUNCION VALIDA DE CARGA DE ARCHIVOS PHP");
			return false;	  
		}
	}
	public function &SetDir($dir)
	{
		if(is_array($dir))
		{
			$this->dir=$dir;
		}else
		{
			$this->dir= is_null($dir)?dirname(__FILE__)."/":$dir;
		}
		return $this;
		
	}
	/**
	BUSCA Y RETORNA EL NOMBRE DEL ARCHIVO DONDE SE ENCUENTRA LA CLASE PASADA AL CONSTRUCTOR
	@return string NOMBRE DEL ARCHIVO DONDE SE ENCUENTA DEFINIDA LA CLASE 
*/  
	public function Search()
	{
		if(is_array($this->dir))
		{
			foreach($this->dir as $dir)
			{
				if($this->NombClassFileExists($dir['dir']))//VERIFICO SI EXISTE UN ARCHIVO CON EL NOMBRE DE LA CLASE
				{
					if($dir['avance']==true)
					{
						if($this->FileSearch($this->GetFileName()))
						{
							$file= $this->GetFileName();
							array_push($this->directorios,$file);
							return $file;
						}
					}else
					{
						$file= $this->GetFileName();
						array_push($this->directorios,$file);
						return $file;
					}
				}
			}
			foreach($this->dir as $dir)
			{
			   //busco la clase en todos los archivos .php Y SUB DIRECTORIOS
                $this->directorios=array_merge($this->directorios,$this->DirSearch($dir['dir'],$dir['avance']));
				if($this->GetFileName()!='')
				{
					return $this->GetFileName();
				}
			}
		}else
		{
			if($this->NombClassFileExists($this->dir))
			{
				
				if($this->avance_searh==true)
				{
					if($this->FileSearch($this->GetFileName()))
					{
						return $this->GetFileName();
					}
				}else
				{
					return $this->GetFileName();
				}
			}
			
			return $this->GetFileName();
			
			 $this->directorios=array_merge($this->directorios,$this->DirSearch($this->dir,$this->avance_searh));//busco la clase en todos los archivos .php
		}
		if($this->GetFileName()!='')
		{
			return $this->GetFileName();
		}
       
		return NULL;
	}

	public function GetFileName()
	{
		return $this->filename;
	}
	protected function NombClassFileExists($dir)
	{
		//return false;
		foreach(self::$ext as $ext)
		{
			$classfile1=$dir.$this->class.".".$ext;
      	 	$classfile2=$dir.strtolower($this->class).".".$ext;
			if(($cf1=file_exists($classfile1)) || file_exists($classfile2))
			{
				 $this->filename=$cf1?$classfile1:$classfile2;
				 return true;
			}
		
		}
		return false;
	}
	/** 
	BUCA LA DEFINICION DE LA  CLASE EN UN ARCHIVO
	@param string $file NOMBRE DEL ARCHIVO
	@return bool true si se encontro 
	*/
   
	protected function FileSearch($file)
	{
		$cadena= file_get_contents($file);
		$cadena=str_replace("\n"," ",$cadena);
		return $this->SearchString($cadena);	
	}
	private function SearchString($cadena)
	{
		if(preg_match_all("/(class .* \{)|(class .* extends)/U",trim($cadena),$cad,PREG_OFFSET_CAPTURE))
		{
			
			foreach($cad[0] as $A)
			{
				//echo "<pre>";print_r($A);
				$C=explode(" ",$A[0]);
				if(trim($C[0])=='class' && trim($C[1])==$this->class &&(( trim($C[2])=='{' || trim($C[2])=='extends')))
				{
					//print_r($cad);
					unset($cadena);
					unset($cad);
					return true;
					break;
				}
			}
		}
		return false;	
	}
	public static function PushExt($ext)
	{
		if(!is_array($ext))
		{
			array_push(self::$ext,$ext);
		}else
		{
			self::$ext=array_merge(self::$ext,$ext);	
		}
	}
	private function GetType($file)
	{
		$fic=explode('.',$file);
		return  strtolower(array_pop($fic));
	}
	private function DirSearch($dir,$avance)
	{
		$directorios=array();
		$carpetas=array();
		$direct=  dir($dir);
		array_push($directorios,$dir);
		while($fichero=$direct->read())
		{
			if($fichero!='.' && $fichero!='..')
			{
				$directorio=($dir=='.')?'':$dir;
				$ext='';
				$fic=explode('.',$fichero);
				if(count($fic)>1)
					$ext=$this->GetType($fichero);

				if(count($fic)>1)
				{
					if(in_array($ext,self::$ext))
					{
						if($avance==true)
						{
							
							array_push($directorios,$directorio.$fichero);
							if($this->FileSearch($directorio.$fichero))
							{
								$this->filename=$directorio.$fichero;
								return $directorios;
							}
						}
						
					}
					
				}elseif(count($fic)==1)
				{
					if($this->NombClassFileExists($directorio.$fichero."/"))
					{
						if($avance==true)
						{
							if($this->FileSearch($this->GetFileName()))
							{
								array_push($directorios,$this->filename);
								return $directorios;
							}
						}else
						{
							array_push($directorios,$this->filename);
							return $directorios;
						}
					}	
					array_push($carpetas,$directorio.$fichero."/");
				}
			}
		}
		$direct->close();
		foreach($carpetas as $carpeta)
		{
			array_push($directorios,$this->DirSearch($carpeta,$avance));
		}
		return $directorios;
	}
	
	private function PrintArray($array)
	{
		
		$text="";
		//return @print_r($array);
		$text.="dir[";
		foreach($array as $a)
		{
			
			if(is_array($a))
			{
				
				$text.=$this->PrintArray($a);
			}else
			{
				
				$text.=$a.",\n";
			}
		}
		$text=$text."]\n";
		
		return $text;
	}
}