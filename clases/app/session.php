<?php
class SESSION
{
	private static $name;
	public function __construct($star=false,$id=NULL)
	{
		//session_set_cookie_params (10,"/","localhost", true);
		if($star)
		self::Start($id);
	}
	public static function Start($id=NULL)
	{
        
        if(!is_null($id))
        {
            session_id($id);
        }else
        {
			
			if(self::$name=='')
			{
				if(defined("__SESSION_NAME__"))
				{
					self::$name=__SESSION_NAME__;
					session_name(__SESSION_NAME__);	
				}
			}
            if(!empty($_COOKIE[self::$name]))
                session_id($_COOKIE[self::$name]);
        }
        
		session_start();
		return array(self::GetName()=>self::GetId());
	}
	
	public static function SeCookie($name=NULL,$cache=NULL,$TIME=NULL,$path=NULL,$dominio=NULL,$secure=false,$httponly=false)
	{
		session_set_cookie_params($TIME,$path,$dominio,$secure,$httponly);
		session_cache_limiter($cache);
		if(!is_null($name))
		{
			self::$name=$name;
			session_name($name);	
		}else
		{
			if(defined("__SESSION_NAME__"))
			{
				self::$name=__SESSION_NAME__;
				session_name(__SESSION_NAME__);	
			}
		}
	}
	public static function Destroy()
	{
		$_SESSION=array();
        if(isset($_COOKIE[self::GetName()]))
        {
            $p=self::GetCookieParams();
			setcookie(SESSION::GetName(),'',time(),$p['path'],$p['domain'],$p['secure'],$p['httponly']);
        }
		session_destroy();
	} 
    public static function Commit()
    {
       session_write_close();
    }
	public static function Del()
	{
		$session=$_SESSION;
		foreach($session as $i => $see)
		{
			unset($_SESSION[$i]);
		}
		
		$_SESSION=array();
	}
	public static function GetVar($var)
	{
		if(!empty($_SESSION[$var]))
		{
			return $_SESSION[$var];
		}
		return NULL;
	}
	public static function SetVar($var,$value)
	{
		$_SESSION[$var]=$value;
	}
	public static function DelVar($var)
	{
		if(!empty($_SESSION[$var]))
		{
			unset($_SESSION[$var]);
		}
		
	}
	public static function _empty($var)
	{
		
		if(!empty($_SESSION[$var]))
		return  true;
		else
		return false;
	}
	public static function GetName()
	{
		return session_name();
	}
	public static function GetCookieParams()
	{
		return session_get_cookie_params();
		
	}
	public static function  GetId()
	{
		return session_id();
	}
}