<?php


class CONTACTOS extends BD_ORVEINCA
{
	const CONTAC="SELECT contactos.* FROM contactos ";
	function editar_contacto(array $_HTTP_VAR)
	{
		$error='';
		$this->autocommit(FALSE);
		$sql="UPDATE  contactos SET 
		nom1_cont='".ucwords(fmt_string($_HTTP_VAR['nom1_cont']))."',
	    nom2_cont='".ucwords(fmt_string($_HTTP_VAR['nom2_cont']))."',
		ape1_cont='".ucwords(fmt_string($_HTTP_VAR['ape1_cont']))."', 
		ape2_cont='".ucwords(fmt_string($_HTTP_VAR['ape2_cont']))."', 
		emai_cont='".fmt_string($_HTTP_VAR['emai_cont'])."'
		 where ci_cont='".$_HTTP_VAR['ci_cont']."';
		";
		
		if(!$this->consulta($sql))
		{
			$e= new OrveincaExeption("ERROR INESPERADO AL EDITAR UN CONTACTO ");
		
			
		}
		$this->insertar_telefonos($_HTTP_VAR,'cont','ci_cont','contacto_telefono');
		
		if($error=='' && !$this->error())
		{
			$this->commit();
		 	$this->autocommit(TRUE);
			return $_HTTP_VAR['ci_cont'];
				
		}else
		{
			$this->rollback();	
			$this->autocommit(TRUE);
			return false;
		}
	}
	function insertar_contacto(array $_HTTP_VAR)
	{	
		$error='';
		$commit=$this->is_autocommit();
		
		$this->autocommit(FALSE);
		$sql="INSERT INTO contactos VALUES (
		'', 
	 	'".ucwords(fmt_string($_HTTP_VAR['nom1_cont']))."', 
	 	'".ucwords(fmt_string($_HTTP_VAR['nom2_cont']))."',
     	'".ucwords(fmt_string($_HTTP_VAR['ape1_cont']))."',
	 	'".ucwords(fmt_string($_HTTP_VAR['ape2_cont']))."',
	 	'".fmt_string($_HTTP_VAR['emai_cont'])."');";	
		if(!$this->consulta($sql,NULL))
		{
			
			$e= new OrveincaExeption("ERROR INESPERADO AL EDITAR UN CONTACTO ");
		}else
		{
			
			$this->consulta("SELECT max(ci_cont) as ci_cont FROM contactos");
			$cont=$this->result();
			$_HTTP_VAR=array_merge($cont,$_HTTP_VAR);
			$this->insertar_telefonos($_HTTP_VAR,'cont','ci_cont','contacto_telefono');
		}
		if(!$this->error())
		{
			
			$this->commit();
			
			$this->autocommit(TRUE);
			return $cont['ci_cont'];
		}else
		{
			$this->rollback();	
			
			$this->autocommit(TRUE);
			return false;
			
		}
	}
}

?>