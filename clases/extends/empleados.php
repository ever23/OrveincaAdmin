<?php

class EMPLEADOS extends BD_ORVEINCA
{
	const VEND="SELECT vendedores.*,parroquias.*,municipios.*,estados.* 
		FROM vendedores
		LEFT JOIN parroquias USING(id_parr)
		LEFT JOIN municipios USING(id_muni)
		LEFT JOIN estados USING(id_esta)";
	const VENT_VEND="SELECT SUM(pagos_fact.bsf_pago) as total_bs
FROM nota_entrg 
LEFT JOIN pagos_fact ON(pagos_fact.tipo_fact='V' AND pagos_fact.id_fact=nota_entrg.nume_nent)
"; 
	const EMPL="SELECT empleados.*,parroquias.*,municipios.*,estados.*,cargos.desc_carg,departamen.desc_dept
FROM empleados
LEFT JOIN cargos USING(codi_carg)
LEFT JOIN departamen USING(codi_dept)
LEFT JOIN parroquias USING(id_parr)
LEFT JOIN municipios USING(id_muni)
LEFT JOIN estados USING(id_esta)";
	/*METODOS PUBLICOS */
	/*INSERTAR VENDEDOR
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE */
	public function insertar_empleado(array $_HTTP_VAR)
	{
		$this->autocommit(FALSE);
		$sql="INSERT INTO empleados VALUES (
		'".fmt_string($_HTTP_VAR['ci_empl'])."',
		'".fmt_string($_HTTP_VAR['rif_empl'])."',
		'".ucwords(fmt_string($_HTTP_VAR['nom1_empl']))."',
		'".ucwords(fmt_string($_HTTP_VAR['nom2_empl']))."', 
		'".ucwords(fmt_string($_HTTP_VAR['ape1_empl']))."',
		'".ucwords(fmt_string($_HTTP_VAR['ape2_empl']))."', 
		'".strtoupper(trim($_HTTP_VAR['emai_empl']))."',
		'".$_HTTP_VAR['sueldo']."',
		'".($_HTTP_VAR['porc_comi']*0.01)."',
		'".$_HTTP_VAR['codi_carg']."',
		'".$_HTTP_VAR['codi_dept']."',
		'".strtoupper(trim($_HTTP_VAR['dire_empl']))."',
		".$this->sql_null($_HTTP_VAR['id_parr'])."
		);
		";	
		$error="LOS DATOS NO FUERON ENVIADOS VERIFICA QUE LA IDENTIFICACION <H2>".$_HTTP_VAR['ci_empl']."</H2> NO ESTE REGISTRADO BAJO OTRO NOMBRE EN LA NOMINA ";
		if(!$this->consulta($sql))
		{
			if($this->errno==$this->_ERRNO['DUPLICATE_KEY'])
			{
				$e= new OrveincaExeption($error);
			}else
			{
				$e= new OrveincaExeption("ERROR INESPERADO AL INSERTAR UN EMPLEADO");
			}

		}
		$this->insertar_telefonos($_HTTP_VAR,'empl','ci_empl');

		$this->insertar_cuent_banc($_HTTP_VAR,'empl','ci_empl');
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return $_HTTP_VAR['ci_empl'];
		}else
		{
			$this->rollback();
			$this->autocommit(TRUE);
			return FALSE;
		}



	}
	/* EDITAR UN VENDEDOR 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE */
	public function editar_empleado(array $_HTTP_VAR)
	{
		$this->autocommit(FALSE);

		$error='';
		$sql="UPDATE empleados SET 
		nom1_empl='".ucwords(fmt_string($_HTTP_VAR['nom1_empl']))."',
		nom2_empl='".ucwords(fmt_string($_HTTP_VAR['nom2_empl']))."', 
		ape1_empl='".ucwords(fmt_string($_HTTP_VAR['ape1_empl']))."',
		ape2_empl='".ucwords(fmt_string($_HTTP_VAR['ape2_empl']))."', 
		emai_empl='".strtoupper(trim($_HTTP_VAR['emai_empl']))."',
		dire_empl='".strtoupper(trim($_HTTP_VAR['dire_empl']))."',
		id_parr=".$this->sql_null($_HTTP_VAR['id_parr'])."
		where ci_empl='".$_HTTP_VAR['ci_empl']."';";
		if(!$this->consulta($sql,NULL))
		{
			$e= new OrveincaExeption("ERROR INESPERADO AL INSERTAR UN VENDEDOR");
		}
		$this->insertar_telefonos($_HTTP_VAR,'empl','ci_empl');
		$this->insertar_cuent_banc($_HTTP_VAR,'empl','ci_empl');

		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return $_HTTP_VAR['ci_empl'];
		}else
		{
			$this->rollback();

			$this->autocommit(TRUE);
			return FALSE;
		}


	}
	public function eliminar_empleado($ci_empl)
	{
		$this->autocommit(FALSE);
		if(!$this->consulta("DELETE FROM telefonos WHERE idet_pers='$ci_empl' and id_tper='empl'"))
		{
			$e= new OrveincaExeption("ERROR AL ELIMINAR LOS TELEFONOS DEL VENDEDOR");
		}
		if(!$this->consulta("DELETE FROM cuent_banc WHERE idet_pers='$ci_empl' and id_tper='empl'"))
		{
			$e= new OrveincaExeption("ERROR AL ELIMINAR LAS CUENTAS BANCARIAS DEL VENDEDOR");

		}
		if(!$this->consulta("DELETE FROM empleados WHERE ci_empl='$ci_empl'"))
		{
			if($this->errno==$this->_ERRNO['restricciones'])
			{

				$e= new OrveincaExeption("ERROR AL ELIMINAR EL EMPLEADO VERIFIQUE QUE NO TENGA NINGUN CLIENTE ASOCIADO EN LA BASE DE DATOS");
			}else
			{
				$e= new OrveincaExeption("ERROR INESPERADO AL ELIMINAR EL VENDEDOR");
			}
		}
		if(!$this->error())
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
	public function InsertarNomina(array $_HTTP_VAR)
	{
		$conf=$this->config();
		$Time= new TIME();
		$SQL="INSERT INTO `orveinca_2_1_2`.`nomina` 
		(`codi_nomi`, `ci_empl`, `suel_diar`, `comicion`, `dias_labo`, `s_p_f`, `l_p_h`, `s_o_s`, `cest_tike`, `fech_nomi`)
		VALUES 
		(NULL, '".$_HTTP_VAR['ci_empl']."', 
		'".$_HTTP_VAR['suel_diar']."', 
		'".$_HTTP_VAR['comicion']."', '".$_HTTP_VAR['dias_labo']."',
		 '".($_HTTP_VAR['spf']?$conf['s_p_f']:0)."', '".($_HTTP_VAR['lph']?$conf['l_p_h']:0)."', '".($_HTTP_VAR['sos']?$conf['s_o_s']:0)."',
		  '".($_HTTP_VAR['cest_tike']?$conf['cest_tike']:0)."',
		 '".$Time->ano."-".($Time->mes-1)."-".$Time->dia."')";
		if(!$this->consulta($SQL))
		{
			$E=new OrveincaExeption("ERROR INSEPERADO AL INSERTAR UN EMPLEADO EN NOMINA");
			return false;
		}else
		{
			return true;	
		}
	}
	public function NominaMensual(array $_HTTP_VAR)
	{
		$Time= new TIME();
		$this->autocommit(false);

		$this->consulta("select * from nomina where fech_nomi>'".$Time->ano."-".($Time->mes-1)."-01'");
		if($this->result->num_rows>0)
		{
			new OrveincaExeption("YA SE HA GENERADO LA NOMINA DE ".$Time->mes_cadena($Time->mes-1)." ".$Time->ano);
			$this->rollback();
			$this->autocommit(true);
			return false;
		}
		foreach($_HTTP_VAR['ci_empl'] as $i=>$ci_empl)
		{
			$this->consulta(self::EMPL,"ci_empl='".$ci_empl."'");
			$campo=$this->result();
			$this->consulta(self::VENT_VEND,"ci_empl='".$ci_empl."'
				 and (fech_nent>='".$Time->ano."-".($Time->mes-1)."-01' and  fech_nent<'".$Time->ano."-".($Time->mes)."-01')");
			$ventas=$this->result();
			$comision=$ventas['total_bs']*$campo['porc_comi'];
			$array=array(
				'ci_empl'=>$ci_empl,
				'dias_labo'=>$_POST['dias_labo'][$i],
				'spf'=>!empty($_POST['spf'][$i]),
				'lph'=>!empty($_POST['lph'][$i]),
				'sos'=>!empty($_POST['sos'][$i]),
				'cest_tike'=>!empty($_POST['cest_tike'][$i]),
				'suel_diar'=>($campo['sueldo']/30),
				'comicion'=>$comision
			);
			if(!$this->InsertarNomina($array))
			{
				break;
			}
		}
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(true);
			return true;
		}else
		{
			$this->rollback();
			$this->autocommit(true);
			return false;
		}
	}
	public function InsertarCargo($codi_carg,$cargo)
	{
		$this->consulta("INSERT INTO cargos VALUES('".$codi_carg."','".$cargo."')");	
		if(!$this->error())
		{
			return 	$codi_carg;
		}else
		{
			new OrveincaExeption("NO SE PODIDO INSERTAR EL CARGO ".$cargo);
			return false;
		}
	}
	public function InsertarDpt($codi,$desc)
	{
		$this->consulta("INSERT INTO departamen VALUES('".$codi."','".$desc."')");	
		if(!$this->error())
		{
			return 	$codi;
		}else
		{
			new OrveincaExeption("NO SE PODIDO INSERTAR EL DEPARTAMENTO ".$desc);
			return false;
		}
	}

}

?>