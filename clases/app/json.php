<?php
/*******************************************************************************
* Json php class                                                               *
* FACILITA LA ESCRITURA DE CODIGO JSON script SIN NESECIDAD DE                 *
* TENER CONOCIMIENTO DE EL LENGUAJE PROVEE UN TOTAL SOPORTE A LA SINTAXIS      *
* SOPORTA TODO TIPO DE VARIABLE PHP INCLUSO OBJETOS DE LOS CUALES SON          *
* TOMADOS LOS ATRIBUTOS PUBLICOS PARA GENERAR UN OBJETO JSON                   *
*                                                                              *
* Version: 1.1                                                                 *
* Fecha:  2015-08-26                                                           *
* Autor:  ENYREBER FRANCO                                                      *
* Email:  enyerverfranco@gmail.com , enyerverfranco@outlook.com                *
*******************************************************************************/
class Json
{
	private $vars=array();
	protected $autoprint;
/**
 CONSTRUCTOR DE LA CLASE 
  @param bool $auto  INDICA SI DE INPRIMIRA EL CONTENIDO EN EL DESTRUCTOR
*/
	public function __construct($auto=false)
	{
		$this->AutoPrint($auto);
		$this->vars=array();
	}
/**
 DESTRUCTOR DE LA CLASE 
 SI AUTOPRINT ESTA ACTIVO IMPRIME EL CONTENIDO JSON CON PrintJson
*/
	public function __destruct()
	{
		if($this->autoprint)
		{
			$this->PrintJson();	
		}
		unset($this->vars);
		unset($this->autoprint);
	}
/**
 INDICA SI SE INPRIMIRA EL CONTENIDO EN EL DESTRUCTOR 
  @param bool $auto 
*/
	public function AutoPrint($auto)
	{
		$this->autoprint=$auto;
	}
/**
 REALIZA UNA COPIA DE UN OBJETO JSON
 @param Object $j  objeto json
*/
	public function Copy(Json &$j)
	{
		$this->vars=$j->vars;
	}
/**
 AGREGA UNA VARIABLE AL OBJETO JSON 
 @param string $name  nombre de la varialbe json 
 @param mixes $conten el contenido de la variable puede ser cualquier tipo de variable php tales como  string,bool,int,float,Array,Object,NULL
 @return $this para encadenado de metodos
*/
	public function &Set($name,$conten=NULL)
	{
		if(is_object($conten))
		{
			if(method_exists($conten,'GetString'))
			{
				$this->vars[$name]= $conten->Get();
			}else
			{
				$a=(object)array();
				foreach($conten as $attr=>$v)
				{
					$a->$attr=$v;
				}
				$this->vars[$name]=$a;
			}
			
		}else
		{
			$this->vars[$name]=$conten;
		}
		return $this;
	}
/**
INSERTA UNA CADENA JSON Y LA AGREGA A LAS VARIABLES 
DE LA CLASE 
@param string $stringJson CADENA DE TECTO JSON 
@param bool $is_new TRUE PARA INSERTAR REEMPLAZAR EL OBBJETO DE LA CLASE ,FALSE PARA CONCATENAR EL OBJETO POR DEFECTO ES TRUE
@return $this para encadenado de metodos
*/
	public function &SetJson($stringJson,$is_new=true)
	{
		if($is_new)
		{
			$this->vars=$this->Decode($stringJson,true);	
		}else
		{
			$this->vars=array_merge($this->vars,$this->Decode($stringJson,TRUE));
		}
		return $this;
	}
/**
 AGREGA UNA VARIABLE AL OBJETO JSON 
 @param string $name  nombre de la varialbe json 
 @return mixes el contenido de la variable puede ser cualquier tipo de variable php tales como  string,bool,int,float,Array,Object,NULL
 segun se aya definido en el metodo Set de no pasarse nungun parametro retornara un array con todo el contenido json
*/
	public function Get($name=NULL)
	{
		if(is_null($name))
		{
			return $this->vars;
		}else
		{
			return $this->vars[$name];
		}
	}
	public function Jempty($var)
	{
		return isset($this->vars[$var]);
	}
	/**
 ELIMINA UNA VARIABLE DEL OBJETO JSON 
 @param string $name  nombre de la varialbe json 
 */
	public function UnsetVar($name)
	{
		unset($this->vars[$name]);
	}
/**
 RETORNA EL NOMBRE DEL TIPO DE VARIALBLE  
 @param mixes $var  variable a revisar
 @return string el nombre del tipo de variable  string,bool,int,float,Array,Object,NULL
 segun se aya definido en el metodo Set
*/
	public function TypeVar($var)
	{
		if(is_null($var))
		{
			return 'NULL';
		}elseif(is_int($var))
		{
			return 'int';
		}elseif(is_float($var))
		{
			return 'float';
		}elseif(is_bool($var))
		{
			return 'bool';
		}elseif(is_array($var))
		{
			return 'Array';
		}elseif(is_object($var))
		{
			return 'Object';
		}else
		{
			if($var=='NULL' || $var=='null')
			{
				return 'NULL';
			}else
			{
				return 'string';
			}
			
		}
	}
	/**
 FORMATEA LA VARIALBLE SEGUN SU TiPO JSON  
 @param mixes $var  variable a formartear
 @return string  contenido formateado para json
*/
	public function FmtTypeVar($var)
	{
		switch($this->TypeVar($var))
		{
			case 'NULL':
			return 'null';
			case 'int':
			case 'float':
			return $var;
			case 'bool':
			return $var?'true':'false';
			case 'Array':
			return $this->ArrayJson($var);
			case 'Object':
				return self::ObjectJson($var)->Encode();
			case 'string':
		
			$str=str_replace("\r","\\r",$var);
			$str=str_replace("\n","\\n",$str);
			$str=str_replace("\t","\\t",$str);
			$str=addcslashes($str,"\\");
			return "\"".trim($str)."\"";
			
		}
	}
		/**
 CONVIERTE UN ARRAY PHP EN UN ARRAY JSON   
 @param array $array  array 
 @return string  contenido  del array
*/
	public function ArrayJson(array $array)
	{
		$buff="[";
		foreach($array as $i=>$var)
		{
			$buff.=$this->FmtTypeVar($var).",";
		}
		if(count($array)>0)
		$buff=substr($buff,0,strlen($buff)-1);
		return $buff."]";	
	}
	/**
 TOMA UN objeto PHP CUALQUIERA Y factoriza UN objeto JSON DE SUS ATRIBUTOS PUBLICOS  
 @param Object $objeto  array 
 @return Object  objeto json
*/
	public static function &ObjectJson( &$objeto)
	{
		if(method_exists($objeto,'Encode'))
		{
			return $objeto;
		}else
		{
			$json= new Json();
			foreach($objeto as $attr=>$value)
			{
				$json->Set($attr,$value);
			}
			return $json;
		}	
	}
    private function Json_encode($var)
    {
        $json='{';
        foreach($var as $i=>$v)
        {
            $json.='"'.$i.'":'.$this->FmtTypeVar($v).',';
        }
        return substr($json,0,-1).'}';
        
    }
	/** 
	RETORNA UNA CADENA DE TEXTO JSON  
	*/
	public function Encode()
	{
        if($js=json_encode($this->vars))
        {
           return $js; 
        }
        return $this->Json_encode($this->vars);//'{"error":"ERRO JSON"}';
	}
	public function Decode($stringJson,$array=false)
	{
		return json_decode($stringJson,$array);	
	}
	public function __toString()//function que se ejecuara cuado el objeto sea tratado como un texto
	{
		return $this->Encode();
	}
	/** 
	IMPRIME EL TEXTO JSON Y EJECUTA LOS HEADERS CORRESPONDIENTE EN CASO DE SER ACEPTADO LA COMPRESION GZIP SE EJECUTA   
	*/
	public function PrintJson()
	{
		$json=$this->Encode();
		header("Content-type:  application/json");;
		if(empty($_SERVER['HTTP_ACCEPT_ENCODING']))
		{
			echo $json;
			exit;
		}
		$acep=explode(",",$_SERVER['HTTP_ACCEPT_ENCODING']);
		if(in_array('gzip',$acep) || in_array('deflate',$acep ))
		{
			header('Content-Encoding: gzip');
	  		 header('Content-Length: ' .strlen($json));
			$modo=in_array('gzip',$acep)?FORCE_GZIP:FORCE_DEFLATE;
			echo  gzencode($json,9,$modo);
		}else
		{
			echo $json;
		}
		exit;
	}
}
?>