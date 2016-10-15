<?php
/*******************************************************************************
* <h1>DocumentBuffer </h1>                                                     *
* FACILITA LA BUFERIZACION Y COMPRIMIDO EN GZIP DE LA SALIDA HTML E INCORPORA  *
* LA CLASE MIN_SCRIPT PARA AJUSTAR Y REDUCIR EL TAMANO DEL DOCUMENTO           *
*                                                                              *
* Version: 1.0                                                                 *
* Fecha:  2015-04-11                                                           *
* Autor:  ENYREBER FRANCO                                                      *
* Email:  enyerverfranco@gmail.com , enyerverfranco@outlook.com                *
*******************************************************************************/
//require('MinScript.php');
class DocumentBuffer
{
	protected $is_auto;
	protected $auto_compres;
	protected $nivel_compres;
	protected $modo_compres;
	protected $type_text;
	protected $min;
	protected $minifi;
	public function __construct($star=false,$compress=false,$min=false,$type='html')
	{
		
		$this->min= new MinScript();
		$this->SetAutoMin($min);
		$this->SetTypeMin($type);
		$this->SetCompres($compress);
		$this->Auto_flush($star);
		if($star)
			self::Start();
		
	}
	public function __destruct()
	{
		if($this->is_auto)
		{
			if($this->type_text=='json')
			{
				 $this->HeaderJson();
			}
			//$this->auto_compres=false;
			if($this->auto_compres)
			{
				$buffer=$this->ContenGzip($this->nivel_compres,$this->modo_compres);
				//header('Content-Type:text/html; charset=utf-8');
				$this->HeaderGzip(strlen($buffer));

			}else
			{
				if($this->minifi)
				{
					$buffer=$this->ContenMin();
				}else
				{
					$buffer=$this->Conten();
				}
				
			}
			self::End();
			echo $buffer;
			$this->is_auto=!$this->is_auto;	
			exit;
		}
	}
	public function  SetAutoMin($min)
	{
		$this->minifi=$min;
	}
	protected function HeaderJson()
	{
		 header("Content-type:  application/json");;
	}
	protected function HeaderGzip($Length)
	{
		header('Content-Encoding: gzip');
	    header('Content-Length: ' .$Length);
	}
	static function Start()
	{
		ob_start();	
	}
	static function End()
	{
		ob_end_clean();	
	}
	public function ContenMin()
	{
		//return self::Conten();
		return $this->min->Min(self::Conten(),$this->type_text);
	}
	static function Conten()
	{
		return ob_get_contents();
	}
	static function Clear()
	{
		ob_clean();	
	}
	static function EndFlush()
	{
		ob_end_flush();
	}

	public function Auto_flush($auto)
	{
		$this->is_auto=$auto;
	}
	public function GetTypeMin()
	{
		return $this->type_text;
	}
	
	public function SetCompres($compres,$nivel=9,$modo=FORCE_GZIP)
	{
		$com='';
		if($modo==FORCE_GZIP)
		{
			$com='gzip';
		}elseif($modo==FORCE_DEFLATE)
		{
			$com='deflate';
		}
		if(empty($_SERVER['HTTP_ACCEPT_ENCODING']))
		{
			$this->auto_compres=false;
			return;
		}
		$acep=explode(",",$_SERVER['HTTP_ACCEPT_ENCODING']);
		if(in_array($com,$acep))
		{
			$this->auto_compres=$compres;
			$this->nivel_compres=$nivel;
			$this->modo_compres=$modo;	
		}else
		{
			$this->auto_compres=false;
		}
		
		
	}
	public function ContenGzip($nivel=9,$modo=FORCE_GZIP)
	{
		if($this->minifi)
		{
			return gzencode($this->ContenMin(),$nivel,$modo);
		}else
		{
			return gzencode($this->Conten(),$nivel,$modo);
		}
				
		
	}
	public function SetTypeMin($type)
	{
		
		if($this->min->GetScriptAcet($type))
		{
			$this->type_text=$type;
		}else
		{
			throw new Exception("TIPO DE ARCHIVO NO SOPORTADO POR MINSCRIPT");
		}
	}

}