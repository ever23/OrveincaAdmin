<?php
class SysExeption extends Exception
{
	const USER=0;
    const DEBUNG=1;
    const DEBUNG_DATABASE=2;
    private static $MODE=1;
    private static $msjs=array();
    public function __construct($msj=NULL,&$code=NULL,&$object=NULL)
    {
	
        if(is_object($code))
        {
            $object=$code;
            $code=NULL;
            if(method_exists($object,'getMessage'))
            {
                $mysql_error=$object->getMessage();
            }
        }
        parent::__construct((is_array($msj)?$msj['msj']:$msj),$code);
        $mysql_error=NULL;
        $mysql_errno=NULL;
        if(is_object($object))
        {

            if(method_exists($object,'connect_error') && method_exists($object,'error'))
            {
                $mysql_error=$object->connect_error?$object->connect_error:$object->error;
            }else
            {
                if(method_exists($object,'error'))
                {
                    $mysql_error=$object->error;
                }
                if(method_exists($object,'errores'))
                {
                    $object->errores.=',';
                }

            }

            if(method_exists($object,'errno'))
            {
                $mysql_errno=$object->errno;
            }
        }else
        {
            $mysql_error=$object;
        }
		$trace=$this->getTrace();
		//array_unshift($trace,array('file'=>$this->getFile(),'line'=>$this->getLine(),'function'=>'','class'=>'','args'=>array()));
		
        $this->PushMsj($this->getMessage(),$trace,$mysql_error,$mysql_errno);
		
		
    }
	public static function GetMode()
	{
		return self::$MODE;	
	}
	public static function SetMode($md)
	{
		self::$MODE=$md;	
	}
    protected function PushMsj($msj,$trace,$mysql_error,$mysql_errno)
    {
        if(is_array($msj))
        {
            $mesaje=array_merge([
			
			'error'=>$mysql_error!=''?"error: ".$mysql_error:NULL,
             'errno'=>$mysql_errno!=''?"errno: ".$mysql_errno:NULL,
			
			'trace'=>$trace,
			
			],$msj);
        }else
        {
            $mesaje=[
                'msj'=>$msj,
                'error'=>$mysql_error!=''?"error: ".$mysql_error:NULL,
                'errno'=>$mysql_errno!=''?"errno: ".$mysql_errno:NULL,
				'trace'=>$trace,
			
				
            ];
        }
        array_push(self::$msjs,$mesaje);
    }

    public  static function _Empty()
    {
        return empty(self::$msjs);
    }
    public static  function GetExeptionS($str=false)
    {
		
		switch(self::$MODE)
		{
			case self::USER:
				$msj='';
				foreach(self::$msjs as $msjs)
				{
					$msj.=$msjs['msj']."<br>";
				}
				break;
			case self::DEBUNG_DATABASE:
				$msj='';
				foreach(self::$msjs as $exti)
				{
					
					if(empty($exti['msj']))
					{
						$exti['msj']='';
					}
					if(empty($exti['error']))
					{
						$exti['error']='';
					}
					if(empty($exti['errno']))
					{
						$exti['errno']='';
					}
					
					$msj.=self::TraceAsString($exti['msj'].'<br>'.$exti['error']." ".$exti['errno']."",$exti['trace']).'<br>';
				}
				break;
			case self::DEBUNG:
			default:
				$msj='';
				foreach(self::$msjs as $msjs)
				{
					$msj.=self::TraceAsString($msjs['msj'],$msjs['trace']).'<br>';
				}
				break;
				
				
		}
		if($str)
		{
			$msj=str_replace("'","\"",$msj);
			$msj=addcslashes($msj,"\\");
			
		}
		return $msj;
    }
	public static function DieExeptionS()
	{
		self::_Empty() || die(self::GetExeptionS());
	}
    public function NumExeption()
    {
        return count(self::$msjs);
    }

    public function AddMsjMysql($error,$errno)
    {
        self::$msjs[$this->NumExeption()-1]['error']=$error;
        self::$msjs[$this->NumExeption()-1]['errno']=$errno;
		
    }
 
	protected static function TraceAsString($mjs=NULL,$trace=NULL)
	{
		
		if(is_null($mjs))
		{
			$mjs=self::$msjs[count(self::$msjs)-1]['msj'];	
		}
		if(is_null($trace))
		{
			$trace=self::$msjs[count(self::$msjs)-1]['trace'];		
		}
		$c=count($trace);
		$a="<pre><br/><font size='1'><table class='xdebug-error xe-notice'dir='ltr'border='1'cellspacing='0'cellpadding='1'><tr><th align='left' bgcolor='#f57900' colspan='5'>";
		$a.="<span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span><b style='font-size:18px;'>SysExetion </b><bR>";
		$a.="<i>".$mjs."</i></th></tr><tr><th align='left' bgcolor='#e9b96e' colspan='5'>Pila de llamadas</th></tr><tr><th align='center' bgcolor='#eeeeec' width='10'>#</th>";
		$a.="<th align='left' bgcolor='#eeeeec'>function</th><th align='left' bgcolor='#eeeeec'>archivo</th><th align='left' bgcolor='#eeeeec'>linea</th></tr>";
			  
		foreach($trace as $i=>$trance)
		{
			  
			$a.="<tr><td bgcolor='#eeeeec' align='center' width='10'>".$i."</td>";
			$a.="<td bgcolor='#eeeeec'>".(!empty($trance['class'])?$trance['class']."::":'').$trance['function']."(".self::Implode($trance['args']).")</td>";
			$a.="<td title='".$trance['file']."' bgcolor='#eeeeec'>".$trance['file']."</td><th align='left' bgcolor='#eeeeec'>".$trance['line']."</th></tr>";
		}
		$a.="</table></font>";
		return $a;
		
	}
	
	
	private static function Implode(array $array)
	{
		$a='';
		foreach($array as $i=>$v)
		{
			if(is_array($v))
			{
				$a.='Array,';
			}elseif(is_object($v))
			{
				$a.='Object,';
			}else
			{
				$a.=$v.',';
			}
		}
		return substr($a,0,strlen($a)-1);
	}
	public function __toString()
	{
		return self::TraceAsString();
	}
}