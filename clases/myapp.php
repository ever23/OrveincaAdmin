<?php
require("app/App.php");
class MyApp extends App
{
	
	
	public static function Start($conf='default')
	{
		self::$confg=dirname(__FILE__).'/confg/';
		self::$Sitio=dirname(__FILE__).'/sitio/';
		self::$Contrllers=dirname(__FILE__).'/controllers/';
		self::SetDirAutoload(dirname(__FILE__).'/model/',false);
		self::SetDirAutoload(dirname(__FILE__).'/app/',false);
		self::SetDirAutoload(dirname(__FILE__).'/extends/',false);
		
		return parent::Start($conf);
	}
}
//EJECUCION  GLOBAL

