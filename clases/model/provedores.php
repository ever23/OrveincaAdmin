<?php


class PROVEDORES extends BD_ORVEINCA
{
	const PROV="SELECT provedores.*,parroquias.*,municipios.*,estados.* ,contactos.*,provedores.id_parr as id_parr_prov
        FROM provedores
        LEFT JOIN parroquias USING(id_parr)
        LEFT JOIN municipios USING(id_muni)
        LEFT JOIN estados USING(id_esta)
        LEFT JOIN contactos USING(ci_cont)";
	const PROV_ALL="SELECT provedores.*,parroquias.*,municipios.*,estados.* ,contactos.*,telefonos.*, parroquias.id_parr as id_parroquia
FROM provedores
LEFT JOIN parroquias USING(id_parr)
LEFT JOIN municipios USING(id_muni)
LEFT JOIN estados USING(id_esta)
LEFT JOIN contactos USING(ci_cont)
LEFT JOIN telefonos on (telefonos.id_tper='prov' and telefonos.idet_pers=provedores.idet_prov) ";
	/*INSERTAR PROVEDOR
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE */
	public function isertar_provedor(array $_HTTP_VAR)
	{
		 $error='';
		$this->autocommit(FALSE);
		$sql = "INSERT INTO provedores VALUES (
		'".fmt_string($_HTTP_VAR['idet_prov'])."',
		'".$_HTTP_VAR['codi_tide']."',
		'".ucwords(fmt_string($_HTTP_VAR['nomb_prov']))."',
		'".fmt_string($_HTTP_VAR['emai_prov'])."',
		'".strtoupper(trim($_HTTP_VAR['dire_prov']))."',
		".$this->sql_null($_HTTP_VAR['id_parr']).",
		".$this->sql_null(fmt_string(!empty($_HTTP_VAR['ci_cont'])?$_HTTP_VAR['ci_cont']:'')).");";
		if(!$this->consulta($sql,NULL))
		{ 
			if($this->errno==$this->_ERRNO['DUPLICATE_KEY'])
			{
				$e= new OrveincaExeption("<h3>LOS DATOS NO FUERON ENVIADOS VERIFICA QUE LA IDENTIFICACION <H2>".$_HTTP_VAR['idet_prov']."</H2> NO ESTE REGISTRADO BAJO OTRO NOMBRE EN LA BASE DE DATOS DE PROVEEDORES </h3>");
				
			}else
			$e= new OrveincaExeption("ERROR INESPERADO AL INSERTAR EL PROVEEDOR");
			
		}
		$this->insertar_telefonos($_HTTP_VAR,'prov','idet_prov');
			
		$this->insertar_cuent_banc($_HTTP_VAR,'prov','idet_prov');
			
		if(!$this->error())
		{
			$this->commit();
		   $this->autocommit(TRUE);
			return $_HTTP_VAR['idet_prov'];
		
		}else
		{	
			$this->rollback();	
			$this->autocommit(TRUE);
			return FALSE;
		}
	}
	/* EDITAR UN PROVEDOR 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE */
	public function editar_provedor(array $_HTTP_VAR)
	{
		$this->autocommit(FALSE);
		
		
		$sql = "UPDATE provedores SET nomb_prov='".ucwords(fmt_string($_HTTP_VAR['nomb_prov']))."', id_parr=".$this->sql_null($_HTTP_VAR['id_parr']).", dire_prov='".strtoupper(trim($_HTTP_VAR['dire_prov']))."',emai_prov='".strtoupper(trim($_HTTP_VAR['emai_prov']))."', ci_cont=".$this->sql_null($_HTTP_VAR['ci_cont'])." where idet_prov='$_HTTP_VAR[idet_prov]';";
		if(!$this->consulta($sql,NULL))
		{
			$e= new OrveincaExeption("ERROR INESPERADO AL EDITAR EL PROVEEDOR");
			
		}
		$this->insertar_telefonos($_HTTP_VAR,'prov','idet_prov');
			
		$this->insertar_cuent_banc($_HTTP_VAR,'prov','idet_prov');
			
		if(!$this->error())
		 {
			 $this->commit();
			  $this->autocommit(TRUE);
			 return $_HTTP_VAR['idet_prov'];
		}else
		{
			$this->rollback();
		
		    $this->autocommit(TRUE);
			return FALSE;
		}
		return 0;
	}
	/* ELIMINAR UN REGISTRO DE PROVEDORES
	@param IDENTIFICACION DEL PROVEDOR
	@return TRUE SI NO HAY ERRORES 
	*/
	public function eliminar_provedor($IDET_PROV)
	{
		
		$this->autocommit(FALSE);
		
	
		if(!$this->consulta("DELETE FROM telefonos WHERE id_tper='prov' and idet_pers='$IDET_PROV';",NULL))
		{
			$e= new OrveincaExeption("ERROR AL ELIMINAR LOS REGISTROS TELEFONICOS DEL PROVEEDOR");
			
		}
		if(!$this->consulta("DELETE FROM cuent_banc WHERE id_tper='prov' and idet_pers='$IDET_PROV';",NULL))
		{
			$e= new OrveincaExeption("ERROR AL ELIMINAR LOS REGISTROS DE LAS CUENTAS BANCARIAS DEL PROVEEDOR");
		
		}
		if(!$this->consulta("DELETE FROM provedores WHERE idet_prov='$IDET_PROV';",NULL))
		{
			if($this->errno==$this->_ERRNO['restricciones'])
			{
				$e= new OrveincaExeption(" ES IMPOSIBLE ELIMINAR EL PROVEEDOR YA QUE SE ENCUENTRA VINCULADO A UNA O MAS ORDENES DE COMPRAS O FACTURAS DE COMPRAS ");
				
			}else
			{
				$e= new OrveincaExeption("ERROR AL ELIMINAR UN REGISTRO DE LOS PROVEDORES");
				
				
			}
			
		}
	
		if(!$this->error())
		{
			$this->commit();
			 $this->autocommit(TRUE);
			 return true;
		}else
		{
	
		  $this->rollback();	
		   $this->autocommit(TRUE);
			return false;
		}
		
	}
	
	
	
	
}

?>