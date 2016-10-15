<?php

//include_once("../clases/lib_fun.php");
class PRODUCTOS extends BD_ORVEINCA
{
	const MAX_ROW_NOTA=40;
	/*CONSULTA BASICA DE PRODCUTOS (`producto`.*,`clas_prod`.*,`modelos`.*,`marcas`.*,`producto`.`id_imag` as id_imag_p )*/
	const  PROD="SELECT `producto`.*,`clas_prod`.*,`modelos`.*,`marcas`.*,`producto`.`id_imag` as id_imag_p 
		FROM `producto` 
		LEFT JOIN `clas_prod` USING(`codi_clpr`) 
		LEFT JOIN `modelos` USING(`id_mode`) 
		LEFT JOIN `marcas` USING(`id_marc`)";
	const  PROD_TC="SELECT tama_prod.*,t1.*,t2.*,u_medida.*,
`t1`.`medi_tama` as medi_tama1,
`t2`.`medi_tama` as medi_tama2
FROM tama_prod 
INNER JOIN `tamanos` as t1 ON (`tama_prod`.`id_tama1` = `t1`.`id_tama`)
INNER JOIN `tamanos` as t2 ON (`tama_prod`.`id_tama2` = `t2`.`id_tama`)
INNER JOIN `u_medida`  ON (`u_medida`.`codi_umed` = `t2`.codi_umed)";

	const PROD_TC_MIN="SELECT tama_prod.id_tmpd,tama_prod.cost_tama,
`t1`.`medi_tama` as medi_tama1,
`t2`.`medi_tama` as medi_tama2,
t1.codi_umed,
tama_prod.id_tama1,tama_prod.id_tama2
FROM tama_prod 
INNER JOIN `tamanos` as t1 ON (`tama_prod`.`id_tama1` = `t1`.`id_tama`)
INNER JOIN `tamanos` as t2 ON (`tama_prod`.`id_tama2` = `t2`.`id_tama`)";

	const  PROD_ALL="SELECT `producto`.*,`clas_prod`.*,`modelos`.*,`marcas`.*,`producto`.`id_imag` as id_imag_p,tama_prod.*,t1.*,t2.*,u_medida.*,

`t1`.`medi_tama` as medi_tama1,
`t2`.`medi_tama` as medi_tama2
FROM 
`producto` 
LEFT JOIN `clas_prod` USING(`codi_clpr`) 
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
INNER JOIN `tama_prod`  USING(id_prod)
INNER JOIN `tamanos` as t1 ON (`tama_prod`.`id_tama1` = `t1`.`id_tama`)
INNER JOIN `tamanos` as t2 ON (`tama_prod`.`id_tama2` = `t2`.`id_tama`)
INNER JOIN `u_medida`  ON (`u_medida`.`codi_umed` = `t2`.codi_umed)";
	const INVEN_TC="SELECT u_medida.*,tamanos.*,inventario.*,colores.desc_colo
FROM inventario 
INNER JOIN `tamanos`  USING(id_tama)
INNER JOIN `u_medida` USING(codi_umed)
LEFT JOIN colores on (colores.exad=inventario.exad_colo)";

	const INVENTARIO1="SELECT `producto`.*,`modelos`.*,`marcas`.*,colores.*,`tamanos`.*,SUM(faco_prod.cant_reci) as cant_reci, SUM(faco_prod.cost_comp*faco_prod.cant_reci) as total_bs
	 FROM faco_prod LEFT 
	 JOIN `producto` USING(id_prod) 
	 LEFT JOIN `modelos` USING(`id_mode`) 
	 LEFT JOIN `marcas` USING(`id_marc`) 
	 LEFT JOIN `tamanos` USING(id_tama) 
	 LEFT JOIN colores on (faco_prod.exad_colo=colores.exad) 
	LEFT JOIN fact_comp using(nume_orde)
	";
	const COTI_TMP="SELECT `producto`.*,`modelos`.*,`marcas`.*,tamanos.*,`temp_coti_prod`.*,colores.*,(temp_coti_prod.prec_vent*temp_coti_prod.cant_coti)
as totalbs
FROM `temp_coti_prod` 
INNER JOIN `producto` USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
INNER JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=temp_coti_prod.exad_colo)";
	const COTI="SELECT `producto`.*,`modelos`.*,`marcas`.*,tamanos.*,`coti_prod`.*,colores.*,(coti_prod.prec_vent*coti_prod.cant_coti)
as totalbs
FROM `coti_prod` 
INNER JOIN `producto` USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
INNER JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=coti_prod.exad_colo)";

	const ORDE_COMP_PROD="SELECT `producto`.*,`modelos`.*,`marcas`.*,tamanos.*,`orde_prod`.*,colores.*,(orde_prod.cost_orde*orde_prod.cant_orde)
as totalbs
FROM `orde_prod` 
INNER JOIN `producto` USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
INNER JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=orde_prod.exad_colo)";
	const ORDE_COMP="SELECT orden_comp.*,provedores.*,SUM(orde_prod.cost_orde*orde_prod.cant_orde) as total_bs FROM `orden_comp`
LEFT JOIN provedores USING(idet_prov)
LEFT JOIN orde_prod USING(nume_orde)";
	const COMPRAS="SELECT fact_comp.*,provedores.*,SUM(faco_prod.cost_comp*faco_prod.cant_faco) as total_bs,
SUM(faco_prod.cant_faco!=faco_prod.cant_reci) as esta_reci
FROM `fact_comp`
LEFT JOIN orden_comp USING(nume_orde)
LEFT JOIN provedores USING(idet_prov)
LEFT JOIN faco_prod USING(nume_orde)
";
	const REPORTE_ENTRADA="
SELECT fact_comp.*,`producto`.*,`modelos`.*,`marcas`.*,tamanos.*,faco_prod.*,colores.*,SUM(faco_prod.cost_comp*faco_prod.cant_reci)
as total_bs,SUM(faco_prod.cant_reci) AS cantidad
FROM `faco_prod` 
INNER JOIN `producto` USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
INNER JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=faco_prod.exad_colo)
LEFT JOIN fact_comp USING(nume_orde)
LEFT JOIN orden_comp USING(nume_orde)
 ";
	const TOTAL_PAG_C="SELECT fact_comp.nume_orde, SUM(pagos_fact.bsf_pago) as total_pag
FROM `fact_comp`
LEFT JOIN pagos_fact on(pagos_fact.tipo_fact='C' AND pagos_fact.id_fact=fact_comp.nume_orde)";
	/*PRODUCTOS DE LAS FACTURAS DE COMPRAS 
CAMPOS:
producto`.*,`modelos`.*,`marcas`.*,tamanos.*,faco_prod.*,
colores.*,(faco_prod.cost_comp*faco_prod.cant_faco)as totalbs
*/
	const FACT_PROD="SELECT `producto`.*,`modelos`.*,`marcas`.*,tamanos.*,faco_prod.*,colores.*,(faco_prod.cost_comp*faco_prod.cant_faco)
as totalbs
FROM `faco_prod` 
INNER JOIN `producto` USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
INNER JOIN tamanos USING(id_tama)
INNER JOIN fact_comp USING(nume_orde)
INNER JOIN orden_comp USING(nume_orde)
LEFT JOIN colores on (colores.exad=faco_prod.exad_colo)";
	/**
* SELECCION DE PROVEDORES PRODUCTOS Y FAVTURAS DE COMPRAS
* CAMPOS:
* faco_prod.*,fact_comp.*,provedores.*,parroquias.*,municipios.*,estados.*
*/
	const PROD_PROV="SELECT faco_prod.*,fact_comp.*,provedores.*,parroquias.*,municipios.*,estados.*,SUM(faco_prod.cant_faco) as cantidad from faco_prod
LEFT JOIN fact_comp USING(nume_orde)
LEFT JOIN orden_comp USING(nume_orde)
LEFT JOIN provedores USING(idet_prov)
 LEFT JOIN parroquias USING(id_parr)
        LEFT JOIN municipios USING(id_muni)
        LEFT JOIN estados USING(id_esta)
	";
	const TEMP_ENTREGA="SELECT `producto`.*,`modelos`.*,`marcas`.*,tamanos.*,`tem_nent_prod`.*,colores.*,(tem_nent_prod.cost_orde*tem_nent_prod.cant_orde)
as totalbs
FROM `tem_nent_prod` 
INNER JOIN `producto` USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
INNER JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=tem_nent_prod.exad_colo)";
	const NENT="SELECT `nent_prod`.*,producto.*,tamanos.*,modelos.*,marcas.*,colores.*,(nent_prod.prec_vent*nent_prod.cant_nent)
as totalbs  FROM  nent_prod
INNER JOIN producto USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
LEFT JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=nent_prod.exad_colo)";
	const PEDI="SELECT pedidos.*,`producto`.*,`modelos`.*,`marcas`.*,tamanos.*,`pedi_prod`.*,colores.*,(pedi_prod.prec_vent*pedi_prod.cant_pedi)
as totalbs
FROM `pedi_prod` 
LEFT JOIN `pedidos` USING(nume_pedi)
LEFT JOIN `producto` USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
LEFT JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=pedi_prod.exad_colo)
";
	const VENTAS="SELECT nota_entrg.*,clientes.*, clientes.ci_empl as clie_ci_emp,empleados.* ,SUM(nent_prod.prec_vent*nent_prod.cant_nent) as total_bs 
FROM nota_entrg 
LEFT JOIN nent_prod USING(nume_nent) 
LEFT JOIN clientes USING(idet_clie) 
LEFT JOIN empleados on(empleados.ci_empl=nota_entrg.ci_empl)
";
	const REPORTE_SALIDA="SELECT nota_entrg.*,`nent_prod`.*,producto.*,tamanos.*,modelos.*,marcas.*,colores.*,SUM(nent_prod.prec_vent*nent_prod.cant_nent)
as total_bs ,SUM(nent_prod.cant_nent) AS cantidad
 FROM  nent_prod
INNER JOIN producto USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
LEFT JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=nent_prod.exad_colo)
LEFT JOIN nota_entrg USING(nume_nent)";

	const TOTAL_PAG_V="SELECT nota_entrg.nume_nent, SUM(pagos_fact.bsf_pago) as total_pag
FROM `nota_entrg`
LEFT JOIN pagos_fact on(pagos_fact.tipo_fact='V' AND pagos_fact.id_fact=nota_entrg.nume_nent)";
	const PEDIDOS="SELECT pedidos.*,clientes.*,empleados.* FROM 
	pedidos LEFT JOIN clientes USING(idet_clie) LEFT JOIN empleados ON(pedidos.ci_empl=empleados.ci_empl)";
	const GATOS="SELECT gastos.*,pagos_fact.*,tipogasto.desc_tpga,bancos.nomb_banc FROM gastos
LEFT JOIN tipogasto USING(codi_tpga)
LEFT JOIN pagos_fact on(pagos_fact.tipo_fact='G' and gastos.codi_gast=pagos_fact.id_fact)
LEFT JOIN bancos USING(id_banc)";
	
	/*METODOS PUBLICOS */

	/**
* INSERTAR PRODUCTOS 
* @param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
* @param $_FILE ARREGLO PROVENIENTE DE $_FILES
* @return SI NO HAY ERRORES ID DEL PRODUCTO INSERTADO
*/
	public function insertar_prod(array $_HTTP_VAR,array $_FILE)
	{
		$this->autocommit(FALSE);
		$modelo='NULL';
		if($_HTTP_VAR['marca']!='NULL')
		{
			if(empty($_HTTP_VAR['modelo']))
			{
				if(!$modelo=$this->mode_marc($_HTTP_VAR))
				{
					$this->rollback();
					$this->autocommit(TRUE);
					return false;
				}
			}
			else
			{
				$modelo=$_HTTP_VAR['modelo'];
			}
		}

		$id_imag='NULL';
		if(!empty($_HTTP_VAR['is_imagen']))
		{
			if(!$id_imag=$this->isertar_imagen($_FILE,142,142))
			{
				$this->rollback();
				$this->autocommit(TRUE);
				return false;
			}
		}
		//cambiar las letras a mayusculas
		$descripcion=trim(strtoupper($_HTTP_VAR['desc_prod']));
		$sql = "INSERT INTO producto VALUES ('','$descripcion','$_HTTP_VAR[clas_prod]',$modelo,$id_imag) ;";
		if($this->consulta($sql))
		{
			$procx=$this->GetAutoIncremet('producto','id_prod');
			$this->insertar_tama_pord($_HTTP_VAR,$procx);
			if(!$this->error())
			{
				$this->commit();
				$this->autocommit(TRUE);
				return $procx;
			}else{
				$e= new OrveincaExeption("ERROR AL INSERTAR EL PRODUCTO EN LA BASE DE DATOS");

				$this->rollback();
				$this->autocommit(TRUE);
				return false;
			} 
		}else
		{
			//$database->consulta("DELETE FROM imagenes WHERE id = '$img[id]'");
			$e= new OrveincaExeption("ERROR AL INSERTAR EL PRODUCTO EN LA BASE DE DATOS");

			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		}
		return false;
	}

	/**
*	EDITAR PRODUCTOS
*	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
*	@param $_FILE ARREGLO PROVENIENTE DE $_FILES
*	@return SI NO HAY ERRORES ID DEL PRODUCTO INSERTADO
*/
	public function editar_prod(array $_HTTP_VAR,array $_FILE)
	{
		$this->autocommit(FALSE);
		$modelo=NULL;
		$modelo='NULL';
		if($_HTTP_VAR['marca']!='NULL')
		{
			if(empty($_HTTP_VAR['modelo']))
			{
				if(!$modelo=$this->mode_marc($_HTTP_VAR))
				{
					$this->rollback();
					$this->autocommit(TRUE);
					return false;
				}
			}
			else
			{
				$modelo=$_HTTP_VAR['modelo'];
			}
		}

		$edit_imagen='';
		if(!empty($_HTTP_VAR['is_imagen']))
		{
			$id_imag=NULL;
			$this->consulta("SELECT producto.id_imag FROM producto WHERE id_prod='".$_HTTP_VAR['id_prod']."'");
			$img=$this->result();
			if(!$id_imag=$this->isertar_imagen($_FILE,142,142,$img['id_imag']))
			{
				$this->rollback();
				$this->autocommit(TRUE);
				return false;
			}
			$edit_imagen=", id_imag='$id_imag'";
		}
		$sql = "UPDATE producto SET desc_prod='".$_HTTP_VAR['desc_prod']."', id_mode=$modelo $edit_imagen  where id_prod='".$_HTTP_VAR['id_prod']."';";
		if($this->consulta($sql))
		{
			$this->insertar_tama_pord($_HTTP_VAR,$_HTTP_VAR['id_prod']);
			if(!$this->error())
			{
				$this->commit();
				$this->autocommit(TRUE);
				return $_HTTP_VAR['id_prod'];
			}else{

				$e= new OrveincaExeption("ERROR AL EDITAL EL PRODUCTO ");
				$this->rollback();
				$this->autocommit(TRUE);
				return false;
			}    
		}else
		{
			$e= new OrveincaExeption("ERROR AL EDITAL EL PRODUCTO");
			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		}
		return false;	
	}
	/**
*	ELIMINAR  PRODUCTOS
*	@param $ID_PROD ID DEL PRODUCTO A ELIMINAR 
*	@return SI NO HAY ERRORES RETORNA TRUE
*/
	public function eliminar_prod($ID_PROD)
	{
		$this->autocommit(FALSE);
		$this->consulta("SELECT producto.id_imag FROM producto WHERE id_prod='".$ID_PROD."'");
		if(!$this->error())
			$img=$this->result();


		$this->consulta("DELETE FROM tama_prod WHERE id_prod='$ID_PROD';");
		if(!$this->error())
			$this->consulta("DELETE FROM imagenes WHERE id_imag='$img[id_imag]'");

		if(!$this->error())
			if(!$this->consulta("DELETE FROM producto WHERE id_prod='$ID_PROD';" ))
		{
			if($this->errno==$this->_ERRNO['restricciones'])
			{
				$e= new OrveincaExeption("<h1>ERROR AL ELIMINAR EL EL PRODUCTO</h1> ES POSIBLE QUE SE ENCUENTRE UNO O MAS REGISTROS DEL PRODUCTO EN  INVENTARIO
				, COTIZACIONES O VENTAS SI ES EL CASO ES IMPOSIBLE ELIMINARLO");

			}else
			{
				$e= new OrveincaExeption(" ERROR INESPERADO AL ELIMINAR EL PRODUCTO DE LA BASE DE DATOS");
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
	/**
*   INSERTAR COTIZACION TEMPORAL
*	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
*	@return SI NO HAY ERRORES true
*/
	public function temp_contizacion(array $_HTTP_VAR)
	{

		$this->consulta("SELECT * FROM temp_coti_prod");
		if(PRODUCTOS::MAX_ROW_NOTA<=$this->result->num_rows)
		{
			$e= new OrveincaExeption("!ATENCION NO PUEDE AGREGAR MAS REGISTROS  SOLO SE PERMITEN ".PRODUCTOS::MAX_ROW_NOTA." REGISTROS ");
			$this->SetError($e->getMessage());
			return false;
		}
		$this->autocommit(FALSE);
		if($_POST['exad_colo']=='otro')
		{
			$exad_colo=$this->insertar_color($_POST);	
		}else
		{
			$exad_colo=$_POST['exad_colo'];
		}
		$id_tama=$_HTTP_VAR['id_tama'];
		if($_HTTP_VAR['id_tama']=='otro')
		{
			$id_tama=$this->insert_tamano($_HTTP_VAR['otro_tamano'],$_HTTP_VAR['codi_umed']);
			$tamano=$_HTTP_VAR['otro_tamano'];
		}else
		{
			$this->consulta("SELECT medi_tama FROM tamanos WHERE id_tama='$_HTTP_VAR[id_tama]' ;",NULL);
			$aux_tam=$this->result();
			$tamano=$aux_tam['medi_tama'];
		}	

		if(!empty($_HTTP_VAR['prec_venta']))
		{
			$prec_venta=$_HTTP_VAR['prec_venta'];
		}else
		{
			$costo=$this->cost_prod($_HTTP_VAR['id_prod'],$tamano,$id_tama,$_HTTP_VAR['codi_umed']);
			$prec_venta=((float)$_HTTP_VAR['precio'])*((float)$costo['cost_tama']);
			$prec_venta+=((float)$costo['cost_tama']);
		}

		$this->consulta("SELECT * FROM temp_coti_prod WHERE id_prod='$_HTTP_VAR[id_prod]' and id_tama='$id_tama' and exad_colo ".$this->sql_null($exad_colo,'S')."");
		if($this->result->num_rows==0)
		{
			if(!$this->consulta("INSERT INTO temp_coti_prod VALUES('','$_HTTP_VAR[id_prod]','$id_tama',".$this->sql_null($exad_colo).",'$prec_venta','$_HTTP_VAR[cant_coti]')",NULL))
			{
				$e= new OrveincaExeption("ERROR AL INSERTAR UN PRODUCTO ");
			}

		}else
		{
			$prec=$this->result();
			if($prec['prec_vent']==$prec_venta)
			{
				$cant=$prec['cant_coti']+$_HTTP_VAR['cant_coti'];
				if(!$this->consulta("UPDATE temp_coti_prod SET cant_coti='$cant' where id_coti='$prec[id_coti]'"))
				{
					$e= new OrveincaExeption("ERROR INESPERADO DEL SISTEMA ");	
				}
			}else
			{
				$e= new OrveincaExeption("ERROR YA INSERTO UN PRODUCTO CON LAS MISMAS ESPECIFICACIONES Y UN PRECIO DIFERENTE ");
				$this->SetError($e->getMessage());	
			}

		}
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return true;
		}else{

			$this->rollback();
			$this->autocommit(TRUE);

			return false;
		} 
	}
	/**
	INSERTAR COTIZACION 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return SI NO HAY ERRORES true
	*/
	public function insertar_cotizacion($idet_clie,$ci_empl)
	{
		$time= new TIME();
		$this->autocommit(FALSE);
		if(!$this->consulta("INSERT INTO cotizacion values(NULL,'".$time->fecha()."','".$idet_clie."',".$this->sql_null($ci_empl).");"))
		{
			$e= new OrveincaExeption(" ERROR AL GENERAR LA COTIZACION ");	
		}else
		{	
			$this->consulta("select * from cotizacion",NULL,'nume_coti DESC','LIMIT 1');
			$num_coti=$this->result();
			$this->consulta(PRODUCTOS::COTI_TMP);
			$coti_tmp='';
			while($campo=$this->result())
			{
				$coti_tmp.="('".$campo['id_prod']."',".$campo['id_tama'].",".$this->sql_null($campo['exad_colo']).",'".$campo['prec_vent']."','".$campo['cant_coti']."',".$num_coti['nume_coti'].") ,";
			}
			$coti_tmp= substr($coti_tmp,0,strlen($coti_tmp)-1);
			if(!$this->consulta("INSERT  INTO coti_prod (`id_prod`, `id_tama`, `exad_colo`, `prec_vent`, `cant_coti`, `nume_coti`) VALUES ".$coti_tmp))
			{
				$e= new OrveincaExeption(" ERROR EN LA TRANSACCION ES POSIBLE QUE LA COTIZACION ESTE VACIA  ");	
			}
		}
		if(!$this->error())
			$this->consulta("TRUNCATE TABLE `temp_coti_prod`");

		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return $num_coti['nume_coti'];
		}else{

			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		} 
	}
	/**
	INSERTAR PEDIDO 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return SI NO HAY ERRORES true
	*/
	public function insertar_pedido($idet_clie,$ci_vend,$nped_manu)
	{
		$time= new TIME();
		$this->autocommit(FALSE);
		if(!$this->consulta("INSERT INTO pedidos values(NULL,'".$time->fecha()."','".$idet_clie."',".$this->sql_null($ci_vend).",'P',".$this->sql_null($nped_manu).");"))
		{
			$e= new OrveincaExeption(" ERROR AL GENERAR EL PEDIDO ");	

		}else
		{	
			$this->consulta("select * from pedidos",NULL,'nume_pedi DESC','LIMIT 1');
			$num_pedi=$this->result();
			$this->consulta(PRODUCTOS::COTI_TMP);
			$coti_tmp='';
			while($campo=$this->result())
			{
				$coti_tmp.="(NULL,'".$campo['id_prod']."',".$campo['id_tama'].",".$this->sql_null($campo['exad_colo']).",'".$campo['prec_vent']."','".$campo['cant_coti']."',0,".$num_pedi['nume_pedi'].") ,";
			}
			$coti_tmp= substr($coti_tmp,0,strlen($coti_tmp)-1);
			if(!$this->consulta("INSERT  INTO pedi_prod (`id_pepr`, `id_prod`, `id_tama`, `exad_colo`, `prec_vent`, `cant_pedi`, `cant_entr`,  `nume_pedi`) VALUES ".$coti_tmp))
			{
				$e= new OrveincaExeption(" ERROR EN LA TRANSACCION ES POSIBLE QUE EL PEDIDO ESTE VACIO ");	

			}
		}
		if(!$this->error())
			$this->consulta("TRUNCATE TABLE `temp_coti_prod`");

		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return $num_pedi['nume_pedi'];
		}else{

			$this->rollback();
			$this->autocommit(TRUE);

			return false;
		} 
	}
	/**
	INSERTAR ORDEN DE COMPRA 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return SI NO HAY ERRORES true
	*/
	public function insertar_orden_comp($idet_prov)
	{
		$time= new TIME();
		$this->autocommit(FALSE);
		if(!$this->consulta("INSERT INTO orden_comp values(NULL,'".$time->fecha()."','".$idet_prov."','P');"))
		{
			$e= new OrveincaExeption(" ERROR AL GENERAR LA LA ORDEN DE COMPRA ");	

		}else
		{	
			$this->consulta("select * from orden_comp",NULL,'nume_orde DESC','LIMIT 1');
			if(!$this->error())
				$num_coti=$this->result();
			$this->consulta(PRODUCTOS::COTI_TMP);
			$coti_tmp='';
			if(!$this->error())
				while($campo=$this->result())
			{
				$coti_tmp.="('','".$campo['id_prod']."',".$campo['id_tama'].",".$this->sql_null($campo['exad_colo']).",'".$campo['prec_vent']."','".$campo['cant_coti']."','".$num_coti['nume_orde']."') ,";
			}
			$coti_tmp= substr($coti_tmp,0,strlen($coti_tmp)-1);
			if(!$this->consulta("INSERT  INTO orde_prod (`id_orpr`,`id_prod`, `id_tama`, `exad_colo`, `cost_orde`, `cant_orde`, `nume_orde`) VALUES ".$coti_tmp))
			{
				$e= new OrveincaExeption(" ERROR EN LA TRANSACCION ES POSIBLE QUE LA ORDEN DE COMPRA ESTE VACIA  ");


			}
		}
		if(!$this->error())
			$this->consulta("TRUNCATE TABLE `temp_coti_prod`");

		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return $num_coti['nume_orde'];
		}else{

			$this->rollback();
			$this->autocommit(TRUE);

			return false;
		} 
	}
	/**
	INSERTAR NOTA DE ENTREGA TEMORAL
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return SI NO HAY ERRORES true
	*/
	public function temp_entrega(array $_HTTP_VAR,$commit=TRUE)
	{
		$this->consulta("SELECT * FROM temp_coti_prod");
		if(PRODUCTOS::MAX_ROW_NOTA<=$this->result->num_rows)
		{
			$e= new OrveincaExeption("!ATENCION NO PUEDE AGREGAR MAS REGISTROS A LA NOTA DE ENTREGA SOLO SE PERMITEN ".MAX_ROW_NOTA." REGISTROS ");

			return false;
		}
		if($commit!=NULL)
			$this->autocommit(FALSE);

		$this->consulta("select * from inventario where id_prod='$_HTTP_VAR[id_prod]' and  id_tama= '$_HTTP_VAR[id_tama]' and exad_colo ".($_HTTP_VAR['exad_colo']!=''?"='$_HTTP_VAR[exad_colo]'":" IS NULL ")."");
		$inventario=$this->result();
		$existencia=$inventario['existencia'];
		
		if($_HTTP_VAR['cant_orde']>$existencia)
		{
			$e= new OrveincaExeption("ERROR LA CANTIDAD INGRESADA SUPERA A LA EXISTENTE EN EL INVENTARIO");
			$this->SetError($e->getMessage());

			if($commit!=NULL)
			{
				$this->rollback();
				$this->autocommit(TRUE);
			}
			return false;
		}
		if(!empty($_HTTP_VAR['prec_venta']))
		{
			$prec_venta=$_HTTP_VAR['prec_venta'];
		}else
		{
			$costo=$this->cost_prod($inventario['id_prod'],$inventario['medi_tama'],$inventario['id_tama'],$inventario['codi_umed']);
			$prec_venta=((float)$_HTTP_VAR['precio'])*((float)$costo['cost_tama']);
			$prec_venta+=((float)$costo['cost_tama']);
		}

		if(empty($_HTTP_VAR['nume_pedi']))
		{
			$_HTTP_VAR['nume_pedi']='NULL';
		}
		$this->consulta("SELECT * FROM tem_nent_prod WHERE id_prod='$inventario[id_prod]' and  id_tama= '$inventario[id_tama]' and exad_colo ".($inventario['exad']!=''?"='$inventario[exad]'":" IS NULL ")."");
		if($this->result->num_rows==0)
		{
			if(!$this->consulta("INSERT INTO tem_nent_prod VALUES('','$_HTTP_VAR[id_prod]','$_HTTP_VAR[id_tama]',".$this->sql_null($_HTTP_VAR['exad_colo']).",$_HTTP_VAR[nume_pedi],'$_HTTP_VAR[cant_orde]','$prec_venta')",NULL))
			{
				$e= new OrveincaExeption("ERROR AL INSERTAR UN PRODUCTO");

			}

		}else
		{
			$prec=$this->result();
			if($prec['nume_pedi']!=NULL)
			{
				$e= new OrveincaExeption("EL PRODUCTO YA EXISTE Y NO SE PUEDE MODIFICAR LA CANTIDAD ");
				$this->SetError($e->getMessage());
			}else
				if($prec['cost_orde']==$prec_venta)
			{
				$cant=(int)$prec['cant_orde']+$_HTTP_VAR['cant_orde'];
				if($cant>$existencia)
				{
					$e= new OrveincaExeption(" ERROR LA CANTIDAD INGRESADA SUPERA A LA EXISTENTE EN EL INVENTARIO");
					$this->errores.=1;
					if($commit!=NULL)
					{
						$this->rollback();
						$this->autocommit(TRUE);
					}
					return false;
				}

				$this->consulta("UPDATE tem_nent_prod SET cant_orde='$cant' where id_temp='$prec[id_temp]'");
			}else
			{
				$e= new OrveincaExeption("ERROR YA INSERTO UN PRODUCTO CON LAS MISMAS ESPECIFICACIONES Y UN PRECIO DIFERENTE");
				$this->SetError($e->getMessage());
			}

		}
		if(!$this->error())
		{
			if($commit!=NULL)
			{
				$this->commit();
				$this->autocommit(TRUE);
			}
			return true;
		}else{
			if($commit!=NULL)
			{
				$this->rollback();
				$this->autocommit(TRUE);
			}
			return false;
		} 
	}

	/**
	INSERTAR NOTA DE ENTREGA 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return SI NO HAY ERRORES true
	*/
	public function nota_entrega($idet_clie,$ci_vend,$nume_fact)
	{
		$time= new TIME();
		$this->autocommit(FALSE);
		if(!$this->consulta("INSERT INTO nota_entrg values(NULL,".$this->sql_null($nume_fact).",'".$time->fecha()."','".$idet_clie."',".$this->sql_null($ci_vend).");"))
		{
			$e= new OrveincaExeption("ERROR AL GENERAR LA NOTA DE ENTREGA");

		}else
		{	
			$this->consulta("select * from nota_entrg",NULL,'nume_nent DESC','LIMIT 1');
			$num_nent=$this->result();

			$coti_tmp='';

			$result=$this->consulta(PRODUCTOS::TEMP_ENTREGA);
			while($campo=$result->fetch_array())
			{
				$coti_tmp.="('".$campo['id_prod']."',".$campo['id_tama'].",".$this->sql_null($campo['exad']).",'".$campo['cost_orde']."','".$campo['cant_orde']."',".$num_nent['nume_nent'].") ,";


				if(!$this->mod_pedido($campo,$idet_clie))
				{
					break;	
				}

			}

			$coti_tmp= substr($coti_tmp,0,strlen($coti_tmp)-1);
			if(!$this->consulta("INSERT  INTO  nent_prod (`id_prod`, `id_tama`, `exad_colo`, `prec_vent`, `cant_nent`, `nume_nent`) VALUES ".$coti_tmp.";"))
			{
				$e= new OrveincaExeption("ERROR EN LA TRANSACCION ES POSIBLE QUE LA NOTA DE ENTREGA ESTE VACIA ESTE VACIA");

			}

			if(!$this->error())
				$this->consulta("TRUNCATE TABLE `tem_nent_prod`");

		}
		$result->free();
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return $num_nent['nume_nent'];
		}else{

			$this->rollback();
			$this->autocommit(TRUE);

			return false;
		} 
	}

	/**
	MODIFICAR EL PEDIDO A ENTREGAR 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
	@return SI NO HAY ERRORES true
	*/
	public function mod_pedido(array $_HTTP_VAR,$idet_clie)
	{
		if($_HTTP_VAR['nume_pedi']!=NULL)
		{
			$this->consulta(PRODUCTOS::PEDI,"nume_pedi='".$_HTTP_VAR['nume_pedi']."' and  id_prod='$_HTTP_VAR[id_prod]' and id_tama='$_HTTP_VAR[id_tama]' and exad_colo ".$this->sql_null($_HTTP_VAR['exad_colo'],'S'));
			$pedido=$this->result();
			if($idet_clie!=$pedido['idet_clie'])
			{
				$e= new OrveincaExeption("ERROR NO SE PUEDE ENTREGAR UN PRODUCTO DE UN PEDIDO A UN CLIENTE DIFERENTE");
				$this->SetError($e->getMessage());
				return false;
			}
			if($_HTTP_VAR['cant_orde']>($pedido['cant_pedi']-$pedido['cant_entr']))
			{
				$cantidad=$pedido['cant_pedi'];
			}else
			{
				$cantidad=$_HTTP_VAR['cant_orde']+$pedido['cant_entr'];
			}
			if(!$this->consulta("UPDATE pedi_prod SET cant_entr='$cantidad' where id_pepr='".$pedido['id_pepr']."'"))
			{

				$e= new OrveincaExeption("ERROR AL ENTREGAR EL PEDIDO");
				return false;
			}
			$this->consulta("SELECT * FROM pedi_prod WHERE pedi_prod.cant_pedi>pedi_prod.cant_entr and pedi_prod.nume_pedi='".$_HTTP_VAR['nume_pedi']."'");
			if($this->result->num_rows==0)
			{
				if(!$this->consulta("UPDATE pedidos SET esta_pedi='E' WHERE nume_pedi='".$_HTTP_VAR['nume_pedi']."'"))
				{
					$e= new OrveincaExeption("ERROR AL ENTREGAR EL PEDIDO");

					return false;
				}
			}
		}	
		if(!$this->error())
		{
			return true;
		}else
		{
			return FALSE;	
		}

	}


	public function facturar_compra(array $_HTTP_VAR)
	{
		$time= new TIME();
		$this->autocommit(FALSE);
		if(!$this->consulta("INSERT INTO fact_comp values('".$_HTTP_VAR['nume_orde']."','".$_HTTP_VAR['nume_fac']."','".$time->fecha('actual')."')"))
		{
			$e= new OrveincaExeption("ERROR AL INSERTAR EN FRACTURAS DE COMPRAS ");
		}

		/*if(!$this->consulta("UPDATE orden_comp SET esta_orde = 'F' WHERE nume_orde = '".$_HTTP_VAR['nume_orde']."'"))
		{
			$e= new OrveincaExeption("ERROR AL MODIFICAR LA ORDEN DE ENTREGA ");
		}*/



		foreach($_HTTP_VAR['id_orpr'] as $i=>$id_orpr)
		{
			if(!$this->consulta("SELECT * FROM orde_prod where nume_orde='".$_HTTP_VAR['nume_orde']."' and id_orpr='".$id_orpr."'"))
				break;

			$producto=$this->result();
			if(!$this->consulta("INSERT INTO faco_prod  VALUES (NULL, '".$producto['id_prod']."', '".$producto['id_tama']."', ".BD_ORVEINCA::sql_null($producto['exad_colo']).", '".$_HTTP_VAR['cost_orde'][$i]."', '".$_HTTP_VAR['cant_orde'][$i]."', '".$_HTTP_VAR['cant_reci'][$i]."', '".$_HTTP_VAR['nume_orde']."')"))
			{
				$e= new OrveincaExeption("ERROR AL INSERTAR UN PRODUCTO EN LA FACTURA DE COMPRA ");

				break;
			}
		}
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return $_HTTP_VAR['nume_orde'];
		}else{

			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		} 

	}
	public function pagar_factura(array $HTTP_VAR)
	{
		$TIME=new TIME;
		$this->autocommit(false);	
		if($HTTP_VAR['tipo_fact']=='C')
		{
			$this->consulta(self::COMPRAS,"nume_orde='".$HTTP_VAR['id_fact']."'",NULL,'GROUP BY nume_orde');
			$compra_venta=$this->result();
			$this->consulta(self::TOTAL_PAG_C,"nume_orde='".$_GET['id_fact']."'",NULL,'GROUP BY nume_orde');
			$pagado=$this->result();
			
		
		}else
		{
			$this->consulta(self::VENTAS,"nume_nent='".$_GET['id_fact']."'",NULL,'GROUP BY nume_nent');
			$compra_venta=$this->result();
			$this->consulta(self::TOTAL_PAG_V,"nume_nent='".$_GET['id_fact']."'",NULL,'GROUP BY nume_nent');
			$pagado=$this->result();	
			
		}
		$deuda=$compra_venta['total_bs']-$pagado['total_pag'];
		if(number_format($deuda,2,'.','')==0)
		{
			$e= new OrveincaExeption("NO SE PUEDE REGISTRAR UN PAGO YA QUE NO EXISTE DEUDA");
			$this->autocommit(TRUE);
			return false;
		}	
		if(!$this->consulta("INSERT INTO pagos_fact VALUES(NULL, '".$HTTP_VAR['id_fact']."', '".$HTTP_VAR['tipo_fact']."', '".$HTTP_VAR['modo_pago']."',  ".BD_ORVEINCA::sql_null($HTTP_VAR['idet_pago']).",".BD_ORVEINCA::sql_null($HTTP_VAR['id_banc']).", '".$HTTP_VAR['bsf_pago']."', '".$TIME->fecha('actual')."')"))
		{
			$e= new OrveincaExeption("ERROR INESPERADO AL REGISTRAR UN PAGO ");
		}

		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return true;
		}else{

			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		} 

	}

	public function recibir_producto(array $HTTP_VAR)
	{
		$this->autocommit(FALSE);
		foreach($HTTP_VAR['id_faco'] as $i=>$id_prod)
		{
			if(!empty($HTTP_VAR['recibido'][$i]) && $HTTP_VAR['recibido'][$i]>0)
			{
				if(!$this->consulta("UPDATE faco_prod SET cant_reci=cant_reci+".$HTTP_VAR['recibido'][$i]." WHERE id_faco='".$id_prod."' and nume_orde='".$HTTP_VAR['nume_orde']."'"))
				{
					$e= new OrveincaExeption("ERROR INESPERADO AL REGISTRAR EL INGRESO DE UN PRODUCTO ");

					break;
				}

			}
		}

		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return true;
		}else{

			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		} 
	}


	public function IngresarGasto(array $_HTTP_VAR)
	{
		$this->autocommit(false);
		if(!$this->consulta("INSERT INTO `gastos` (`codi_gast`, `desc_gast`, `codi_tpga`) 
		VALUES ('".$_HTTP_VAR['nume_reci']."', '".$_HTTP_VAR['desc_gast']."', '".$_HTTP_VAR['codi_tpga']."')"))
		{
			if($this->_ERRNO['DUPLICATE_KEY'])
			{
				$e= new OrveincaExeption("EL NUMERO DE RECIBO YA ESTA REGISTRADO PORFAVOR VERIFIQUE LOS DATOS");
			}else
			{
				$e= new OrveincaExeption("ERROR INESPERADO AL INSERTAR UN GASTO ");

			}


		}
		$gasto=$this->GetAutoIncremet('gastos','codi_gast');
		$this->pagar_factura(array_merge($_HTTP_VAR,['id_fact'=>$gasto,'tipo_fact'=>'G']));
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(true);
			return $gasto;
		}else
		{
			$this->rollback();
			$this->autocommit(true);
			return false;
		}

	}
	/**
	CONSUTALA CLASIFICACION DE PRODUCTOS 
	@param $sqlwhere SENTENCIA WHERE SQL 

	@return ARREGLO CON EL RESULTADO COMPLETO 
	*/
	public function clasificacion($sqlwhere=NULL)
	{
		$WHERE='';
		if($sqlwhere!=NULL)
		{
			$WHERE="WHERE ".$sqlwhere;
		}
		if(!$this->consulta("SELECT * FROM clas_prod $WHERE ",NULL))
			return 0;
		else
			return $this->result_array();
	}
	/**
	OBTENER EL COSTO DE UN PRODUCTO SEGUN SU medida 
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST 

	@return SI NO HAY ERRORES RETORNA EL ID DEL MODELO
	*/
	function cost_prod($id_prod,$medida,$id_tama,$codi_umed)
	{
		$ERROR="NO SE ENCONTRO UN PRECIO PARA EL PRODUCTO ".$id_prod." CON  ESTA MEDIDA ".$codi_umed." ".$medida."  EN LA BASE DE DATOS PORFAVOR SELECCIONE UNA DE O EDITE EL PRODUCTO Y AGREGE EL PRECIO CORRESPONDIENTE A ESTA MEDIDA";
		switch($codi_umed)
		{
			case 't':
			{
				$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod' and (`t1`.`id_tama`<=$id_tama and `t2`.`id_tama`>=$id_tama)","`t1`.`medi_tama` ASC");
				if($this->result->num_rows==0)
				{
					$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod'  and (`t1`.`medi_tama`='-' and `t2`.`medi_tama`='-')");
					if($this->result->num_rows!=1)
					{
						if($this->result->num_rows==0)
						{
							$e= new OrveincaExeption($ERROR);


						}

					}
				}
			}break;
			case '-':
			{
				$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod'  and `t1`.`medi_tama`='$medida' ");
				if($this->result->num_rows==0)
				{
					$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod'  and `t1`.`medi_tama`='$medida'  ");
					if($this->result->num_rows!=1)
					{

						$e= new OrveincaExeption($ERROR);

					}

				}

			}break;
			case 't_n':
			{

				$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod' and (`t1`.`medi_tama`<=$medida  and `t2`.`medi_tama`>=$medida)","`t1`.`medi_tama` ASC");

				if($this->result->num_rows==0)
				{
					$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod' and `t1`.`medi_tama`=$medida");
					if($this->result->num_rows==0)
					{
						$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod' and (`t1`.`medi_tama`='-' and `t2`.`medi_tama`='-')");
						if($this->result->num_rows!=1)
						{
							$e= new OrveincaExeption($ERROR);
						}	
					}
				}

			}break;
			default:
			{
				$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod' and (`t1`.`medi_tama`<=".(is_int($medida)||is_float($medida)?"$medida ":"'$medida' ")." and `t2`.`medi_tama`>=".(is_int($medida)?"$medida ":"'$medida' ").")","`t1`.`medi_tama` ASC");

				if($this->result->num_rows==0)
				{
					$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod' and `t1`.`medi_tama`=".(is_int($medida)||is_float($medida)?"$medida ":"'$medida' ")." ");
					if($this->result->num_rows==0)
					{
						$this->consulta(PRODUCTOS::PROD_TC_MIN,"id_prod='$id_prod' and (`t1`.`medi_tama`='-' and `t2`.`medi_tama`='-')");
						if($this->result->num_rows!=1)
						{
							$e= new OrveincaExeption($ERROR);
						}	
					}
				}

			}	
		}
		if(!$this->error)
		{

			return $this->result();
		}
		else return false;
	}
	/*METODOS PRIVADOS */



	/**
INSERTAR COLORES
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST 

	@return SI NO HAY ERRORES RETORNA EL ID DEL MODELO
	*/
	public function insertar_color(array $_HTTP_VAR)
	{

		if(!$this->consulta("INSERT INTO colores VALUES('$_HTTP_VAR[exad]','".fmt_string($_HTTP_VAR['desc_colo'])."')",NULL))
		{
			if($this->errno==$this->_ERRNO['DUPLICATE_KEY'])
				$e= new OrveincaExeption("ERROR AL INSERTAR UN COLOR EN LA BASE DE DATOS ES POSIBLE QUE EL COLOR <div style='color:$_HTTP_VAR[exad]'> ||||||</div> YA SE ENCUENTRE EN LA BASE DE DATOS CON OTRA DESCRIPCION");

			else
				$e= new OrveincaExeption("ERROR INESPERADO AL INSERTAR UN COLOR EN EL SISTEMA ");
			return false;
		}else
		{
			return $_HTTP_VAR['exad'];
		}

	}


	/**
	INSERTAR MODELOS Y MARCAS
	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST 

	@return SI NO HAY ERRORES RETORNA EL ID DEL MODELO
	*/
	private function mode_marc(array $_HTTP_VAR)
	{
		if(!empty($_HTTP_VAR['new_modelo']) && !empty($_HTTP_VAR['new_marca']))
		{
			if($_HTTP_VAR['new_modelo']!='' && $_HTTP_VAR['new_marca']!='')
			{
				$this->consulta("INSERT INTO marcas VALUES ('','".strtoupper(fmt_string($_HTTP_VAR['new_marca']))."')",NULL);
				if(!$this->error)
					$this->consulta("SELECT * FROM marcas ORDER BY  `id_marc` DESC ",NULL);

				if(!$this->error)
				{
					$mark=$this->result();	
				}else
				{
					$e= new OrveincaExeption(" ERROR DESCONOCIDO AL INSERTAR MODELO Y MARCA ");

					return false;
				}

				$this->consulta("INSERT INTO modelos VALUES ('','".strtoupper(fmt_string($_HTTP_VAR['new_modelo']))."','$mark[id_marc]')",NULL);
				if(!$this->error)
					$this->consulta("SELECT * FROM modelos ORDER BY  `id_mode` DESC ",NULL);

				if(!$this->error)
				{
					$mod=$this->result();
					return $mod['id_mode'];
				}else
				{
					$e= new OrveincaExeption("ERROR DESCONOCIDO AL INSERTAR MODELO Y MARCA ");

					return false;
				}

			}
		}elseif(!empty($_HTTP_VAR['new_modelo']) && empty($_HTTP_VAR['new_marca']))
		{
			if($_HTTP_VAR['new_modelo']!='')
			{
				$this->consulta("INSERT INTO modelos VALUES ('','".fmt_string($_HTTP_VAR['new_modelo'])."','$_HTTP_VAR[marca]')",NULL);
				if(!$this->error)
					$this->consulta("SELECT * FROM modelos ORDER BY  `id_mode` DESC ",NULL);
				if(!$this->error())
				{
					$mod=$this->result();
					return $mod['id_mode'];
				}else
				{
					$e= new OrveincaExeption(" ERROR DESCONOCIDO AL INSERTAR MODELO ");
					return false;
				}
			}	
		}

	}

	/**
	INSERTAR Y MODIFICAR TAMAÑOS Y PRECIOS

	@param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST 
	@param $id_prod ID DEL PRODUCTO AL QUE SE ASOCIARA EL REGISTRO
	@return SI NO HAY ERRORES RETORNA TRUE
	*/
	private function insertar_tama_pord(array $_HTTP_VAR,$id_prod)
	{
		$time= new TIME();
		$fecha_h=$time->fecha_hora('actual');
		foreach($_HTTP_VAR['costo'] as $i=>$cost)
		{
			if(empty($_HTTP_VAR['id_tam_pro'][$i]))
			{
				/*INSERTAR TAMAÑOS PRECIOS*/
				if(!empty($_HTTP_VAR['tamano_ini'][$i]) && !empty($_HTTP_VAR['tamano_end'][$i]))
				{
					$this->consulta("INSERT INTO tama_prod VALUES('".$id_prod."','".$_HTTP_VAR['tamano_ini'][$i]."', '".$_HTTP_VAR['tamano_end'][$i]."','".$_HTTP_VAR['costo'][$i]."','$fecha_h','')",NULL);

				}else
				{
					if(empty($_HTTP_VAR['otro_tam_ini'][$i]) || $_HTTP_VAR['otro_tam_ini'][$i]=="")
					{
						if($_HTTP_VAR['id_u_medida']=='-')
						{
							$_HTTP_VAR['otro_tam_ini'][$i]='';
						}else
						{
							$_HTTP_VAR['otro_tam_ini'][$i]='-';
						}

					}
					if(empty($_HTTP_VAR['otro_tam_end'][$i]) || $_HTTP_VAR['otro_tam_end'][$i]=="")
					{
						if($_HTTP_VAR['id_u_medida']=='-')
						{
							$_HTTP_VAR['otro_tam_end'][$i]='';
						}else
						{
							$_HTTP_VAR['otro_tam_end'][$i]='-';
						}
					}


					$medi_ini=$this->insert_tamano($_HTTP_VAR['otro_tam_ini'][$i],$_HTTP_VAR['id_u_medida']);
					if($this->error)
					{
						break;
					}
					$medi_end=$this->insert_tamano($_HTTP_VAR['otro_tam_end'][$i],$_HTTP_VAR['id_u_medida']);
					if($this->error)
					{
						break;
					}
					$this->consulta("INSERT INTO tama_prod VALUES('".$id_prod."','".$medi_ini."','".$medi_end."','".$_HTTP_VAR['costo'][$i]."','$fecha_h','')",NULL);
				}

			}else
			{

				/*para modificar tamanos y precios */
				if(!empty($_HTTP_VAR['tamano_ini'][$i]) && !empty($_HTTP_VAR['tamano_end'][$i]))
				{	
					$this->consulta("UPDATE tama_prod SET  cost_tama='".$_HTTP_VAR['costo'][$i]."', fech_tama='".$fecha_h."',id_tama1='".$_HTTP_VAR['tamano_ini'][$i]."',id_tama2='".$_HTTP_VAR['tamano_end'][$i]."' WHERE id_tmpd='".$_HTTP_VAR['id_tam_pro'][$i]."'; ",NULL);

				}else
				{
					if(empty($_HTTP_VAR['otro_tam_ini'][$i]) || $_HTTP_VAR['otro_tam_ini'][$i]=="")
					{
						if($_HTTP_VAR['id_u_medida']=='-')
						{
							$_HTTP_VAR['otro_tam_ini'][$i]='';
						}else
						{
							$_HTTP_VAR['otro_tam_ini'][$i]='-';
						}
					}
					if(empty($_HTTP_VAR['otro_tam_end'][$i]) || $_HTTP_VAR['otro_tam_end'][$i]=="")
					{
						if($_HTTP_VAR['id_u_medida']=='-')
						{
							$_HTTP_VAR['otro_tam_end'][$i]='';
						}else
						{
							$_HTTP_VAR['otro_tam_end'][$i]='-';
						}
					}
					$medi_ini=$this->insert_tamano($_HTTP_VAR['otro_tam_ini'][$i],$_HTTP_VAR['id_u_medida']);
					if($this->error)
					{
						break;
					}
					$medi_end=$this->insert_tamano($_HTTP_VAR['otro_tam_end'][$i],$_HTTP_VAR['id_u_medida']);
					if($this->error)
					{
						break;
					}
					$this->consulta("UPDATE tama_prod SET  cost_tama='".$_HTTP_VAR['costo'][$i]."', fech_tama='".$fecha_h."',id_tama1='".$medi_ini."',id_tama2='".$medi_end."' WHERE id_tmpd='".$_HTTP_VAR['id_tam_pro'][$i]."'; ",NULL);

				} 
			}

			if($this->error)
				break;	
			if(!empty($_HTTP_VAR['tamano_ini'][$i]))
			{
				$this->consulta("SELECT medi_tama FROM tamanos WHERE id_tama='".$_HTTP_VAR['tamano_ini'][$i]."'");
				$tam=$this->result();
			}else
			{
				$tam['medi_tama']=$_HTTP_VAR['otro_tam_ini'][$i];
			}

			if($tam['medi_tama']=='-' || $tam['medi_tama']=='')
			{
				if($i>0)
				{
					$e= new OrveincaExeption("ERROR FATAL  EL LOS TAMAÑOS SOLO SELECCIONA ( - ) PARA LA PRIMERA SELECCION SI NO HAY DIFERENTES PRECIOS ENTRE TAMAÑOS ");

				}
				break;
			} 
		}
		if(!$this->error())
		{
			return true;
		}else
		{
			if($this->errno==1062)
			{
				$e= new OrveincaExeption(" ERROR YA EXISTE ESTA RANGO DE MEDIDA");
			}else
			{
				$e= new OrveincaExeption(" ERROR INESPERADO AL INSERTAR TALLAS Y PRECIOS");
			}
			
			return false;
		}
	}	
}

?>