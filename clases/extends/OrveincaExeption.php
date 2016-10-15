<?php
class OrveincaExeption extends SysExeption
{
	  public function __construct($msj=NULL,&$code=NULL,&$object=NULL)
	  {
		  parent::__construct($msj,$code,$object);
	  }
}
?>
