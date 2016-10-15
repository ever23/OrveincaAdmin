<?php
/*******************************************************************************
* JsonBuffer php class                                                         *
* BUFFERIZA LA SALIDA DE TEXTO PARA EVITAR ERRORES DE SINTAXIS EN EL           *
* PROCESAMIENTO DE PARTE DEL CLIENTE O ERRORES EN DECODIFICACION AL COMPRIMIR  *
* CON GZIP, VACIADO TODO EL TEXTO INPRESO FUERA DE EL OBJETO                   *
* JSON EN UNA VARIABLE JSON QUE POR DEFECTO SERA TextBufer                     *
*                                                                              *
* Version: 1.0                                                                 *
* Fecha:  2015-08-31                                                           *
* Autor:  ENYREBER FRANCO                                                      *
* Email:  enyerverfranco@gmail.com , enyerverfranco@outlook.com                *
*******************************************************************************/
//require('json.php');
//require('DocumentBuffer.php');
class JsonBuffer extends Json 
{
	private $textJsonBuffer='TextBufer';
	public function AutoPrint($auto)
	{
		if($auto)
		DocumentBuffer::Start();
		parent::AutoPrint($auto);
	}
	public function DefineVarBuffer($tex)
	{
		$this->textJsonBuffer=$tex;
	}
	public function  __destruct()
	{
		if($this->autoprint)
		{
			$text=DocumentBuffer::Conten();
			DocumentBuffer::End();
			if($text!="")
			$this->Set($this->textJsonBuffer,$text);
		}
		parent::__destruct();
	}
}