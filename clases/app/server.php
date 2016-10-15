<?php
include_once(dirname(__FILE__)."/../extern/filterxss.php");
class Server
{
	const C1='%5B';
	const C2='%5D';
	const FilterXssAll=0;
	const FilterXssGet='_GET';
	const FilterXssPost='_POST';
	const FilterXssCookie='_COOKIE';
	protected static $FilterXssExeptions=array('_GET'=>array(),'_POST'=>array(),'_COOKIE'=>array());
	public static function Redirec($url=NULL,array $get=array())
	{
		$req='';
		if(is_array($url))
		{
			$get=$url;
			$url=NULL;
		}
		if(!is_null($url))
		{
			if(count(explode('?',$url))==1)
			{
				$req='?';
			}else
			{
				$req='&';
			}
		}else
		{
			$req='?';
			$url=$_SERVER['PHP_SELF'];
		}
		$cont=self::SerializeGet($get);
		$req=($cont=='')?'':$req;
			
		header("Location: ".$url.$req.$cont);	
   		 exit;
	}
	public static function SerializeGet(array $var)
	{
		$conten='';
		foreach($var as $i=>$v)
		{
			if(is_array($v))
			{
				$conten.=self::SerializeGetArray($i,$v).'&';
			}else
			$conten.=$i.'='.$v.'&';
		}
		$conten=substr($conten,0,strlen($conten)-1);
		return $conten;
	}
	protected static function SerializeGetArray($var,$array)
	{
		$conten='';
		foreach($array as $i=>$v)
		{
			if(is_array($v))
			{
				$conten.=self::SerializeGetArray($var.self::C1.$i.self::C2,$v).'&';
			}else
			{
				$conten.=$var.self::C1.$i.self::C2.'='.$v.'&';
			}
		}
		$conten=substr($conten,0,strlen($conten)-1);
		return $conten;
	}
	public static function Protocol()
	{
		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) 
		{
			$uri= 'https://';
		}else{
			$uri=  'http://';
		}
		return $uri;
	}
	public static function FilterXssArray(array $val,array $exetion=array())
	{
        
		foreach($val as $i=>$v)
		{
			if(!in_array($i,$exetion))
			{
				if(is_array($v))
				{
					$val[$i]=self::FilterXssArray($v);
				}else
				{
					 $v = str_replace("'","\'",$v);
					$val[$i]=FilterXssVal($v);
					
				}
			}
		}
		return $val;
	}
	public static function FilterXss($tipe=0)
	{
		switch($tipe)
		{
			case self::FilterXssGet:
			$_GET=self::FilterXssArray($_GET,self::$FilterXssExeptions[self::FilterXssGet]);
			break;
			case self::FilterXssPost:
			$_POST=self::FilterXssArray($_POST,self::$FilterXssExeptions[self::FilterXssPost]);
			break;
			case self::FilterXssCookie:
			$_COOKIE=self::FilterXssArray($_COOKIE,self::$FilterXssExeptions[self::FilterXssCookie]);
			break;
			case self::FilterXssAll:
			$_GET=self::FilterXssArray($_GET,self::$FilterXssExeptions[self::FilterXssGet]);
			$_POST=self::FilterXssArray($_POST,self::$FilterXssExeptions[self::FilterXssPost]);
			$_COOKIE=self::FilterXssArray($_COOKIE,self::$FilterXssExeptions[self::FilterXssCookie]);
			break;
		}
	}
	public static function SetFilterXssExeption($tipe, $e)
	{
		if($tipe==self::FilterXssAll)
		{
			foreach(self::$FilterXssExeptions as $i=>$v)
			{
				if(!is_array($e))
				{
					array_push(self::$FilterXssExeptions[$i],$e);
				}else
				{
					self::$FilterXssExeptions[$i]=array_merge(self::$FilterXssExeptions[$i],$e);
				}
				
			}
		}else
		{
			if(!is_array($e))
			{
				array_push(self::$FilterXssExeptions[$tipe],$e);
			}else
			{
				self::$FilterXssExeptions[$tipe]=array_merge(self::$FilterXssExeptions[$tipe],$e);
			}
		}
		
	}
	
	public static function Get($ind,$filter=FILTER_DEFAULT,$option=NULL)
	{
		return filter_input(INPUT_GET,$ind, $filter,$option);
	}
	public static function Post($ind,$filter=FILTER_DEFAULT,$option=NULL)
	{
		return filter_input(INPUT_POST,$ind, $filter,$option);
	}
	public static function Cookie($ind,$filter=FILTER_DEFAULT,$option=NULL)
	{
		return filter_input(INPUT_COOKIE,$ind, $filter,$option);
	}
	
	
	
}
