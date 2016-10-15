<?php
class CLIENTES extends BD_ORVEINCA
{
	const CLIE="SELECT clientes.*,parroquias.*,municipios.*,estados.* ,empleados.*,contactos.*,parroquias.id_parr as id_parroquia
        FROM clientes
        LEFT JOIN parroquias USING(id_parr)
        LEFT JOIN municipios USING(id_muni)
        LEFT JOIN estados USING(id_esta)
        LEFT JOIN empleados USING(ci_empl)
        LEFT JOIN contactos USING(ci_cont)";
	const ALL_CLIE="SELECT clientes.*,parroquias.*,municipios.*,estados.* ,empleados.*,contactos.*,telefonos.*, parroquias.id_parr as id_parroquia
FROM clientes
LEFT JOIN parroquias on(clientes.id_parr= parroquias.id_parr)
LEFT JOIN municipios USING(id_muni)
LEFT JOIN estados USING(id_esta)
LEFT JOIN empleados USING(ci_empl)
LEFT JOIN contactos USING(ci_cont)
LEFT JOIN telefonos on (telefonos.id_tper='clie' and telefonos.idet_pers=clientes.idet_clie) ";
	/** METODOS PUBLICOS  **/
/* INSERTAR UN REGISTRO DE CLIENTES
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE */
	public function insertar_cliente(array $_HTTP_VAR)
	{
		$this->autocommit(FALSE);
		$error='';

		$sql = "INSERT INTO clientes VALUES (
		'".fmt_string($_HTTP_VAR['idet_clie'])."',
		'".$_HTTP_VAR['codi_tide']."',
		'".ucwords(fmt_string($_HTTP_VAR['nomb_clie']))."',
		'".ucwords(fmt_string($_HTTP_VAR['emai_clie']))."',
		'".strtoupper(trim($_HTTP_VAR['dire_clie']))."',
		".$this->sql_null($_HTTP_VAR['id_parr']).",
		".$this->sql_null(fmt_string(!empty($_HTTP_VAR['ci_cont'])?$_HTTP_VAR['ci_cont']:'')).",
		".$this->sql_null($_HTTP_VAR['ci_empl'])."
		);";
		if(!$this->consulta($sql,NULL))
		{
			if($this->errno==$this->_ERRNO['DUPLICATE_KEY'])
			{
				$e= new OrveincaExeption("<h2>LOS DATOS NO FUERON ENVIADOS</h2> VERIFICA QUE LA IDENTIFICACION ".$_HTTP_VAR['codi_tide'].$_HTTP_VAR['idet_clie']." NO ESTE REGISTRADO BAJO OTRO NOMBRE EN LA BASE DE DATOS DE CLIENTES");

			}else
			$e= new OrveincaExeption("ERROR INSEPERRADO AL ISERTAR UN CLIENTE ");
		}
		$this->insertar_telefonos($_HTTP_VAR,'clie','idet_clie','telefono');
		if(!$this->error())
		{
			 $this->commit();
			 $this->autocommit(TRUE);
			return $_HTTP_VAR['idet_clie'];
		
		}else
		{
			$this->rollback();	
			$this->autocommit(TRUE);

			return FALSE;
		}	
	}
	/* EDITAR UN REGISTRO DE CLIENTES
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE */
	public function editar_cliente(array $_HTTP_VAR)
	{
		$this->autocommit(FALSE);
		$error='';
		
		$sql = "UPDATE clientes SET 
		nomb_clie='".ucwords(fmt_string($_HTTP_VAR['nomb_clie']))."',
		ci_empl=".$this->sql_null($_HTTP_VAR['ci_empl']).", 
		id_parr=".$this->sql_null($_HTTP_VAR['id_parr']).", 
		dire_clie='".strtoupper(trim($_HTTP_VAR['dire_clie']))."',
		emai_clie='".strtoupper(trim($_HTTP_VAR['emai_clie']))."', 
		ci_cont=".$this->sql_null($_HTTP_VAR['ci_cont'])."
		where idet_clie='$_HTTP_VAR[idet_clie]';";
		if(!$this->consulta($sql,NULL))
		{
			$e= new OrveincaExeption("ERROR INSEPERRADO AL EDITAR UN CLIENTE ");
		}
		
		$this->insertar_telefonos($_HTTP_VAR,'clie','idet_clie','telefono');
		if(!$this->error() && $error=='')
		 {
			 $this->commit();
			 $this->autocommit(TRUE);
			return $_HTTP_VAR['idet_clie'];
		}else
		{
			$this->rollback();	
			$this->autocommit(TRUE);
			return FALSE;
		}	
	}
	/* ELIMINAR UN REGISTRO DE CLIENTES 
	@param IDENTIFICACION DEL CLIENTE
	@return TRUE SI NO HAY ERRORES 
	*/
	public function eliminar_cliente($IDET_CLIE)
	{
		$this->autocommit(FALSE);
		$error='';
		if(!$this->consulta("DELETE FROM telefonos WHERE id_tper='clie' and idet_pers='$IDET_CLIE';",NULL))
		{
			$e= new OrveincaExeption("ERROR AL ELIMINAR LOS REGISTROS TELEFONICOS DEL CLIENTE ");
		}
		if(!$this->consulta("DELETE FROM clientes WHERE idet_clie='$IDET_CLIE';",NULL))
		{
			if($this->errno==$this->_ERRNO['restricciones'])
			{
				$e= new OrveincaExeption(" ES IMPOSIBLE ELIMINAR EL CLIENTE ");
			
			}else
			{
				$e= new OrveincaExeption("ERROR AL ELIMINAR UN REGISTRO DE  CLIENTES");
				
			}
			
		}
		if(!$this->error() && $error=='')
		 {
			 $this->commit();
			 $this->autocommit(TRUE);
			return TRUE;
		}else
		{
			$this->rollback();	
			$this->autocommit(TRUE);
			return FALSE;
		}	
	}
	
}

?>