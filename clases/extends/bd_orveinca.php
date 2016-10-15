<?php
class BD_ORVEINCA extends DATABASE
{
	/**
	CONSTRUCTOR DE LA CLASE 
	@param bool $autenticate INDICA SE SE AUTETICARA LA CONECCION CON COOKIE 
	@param string $db BABSE DE DATOS
	@param string $host HOSTING 
	@param string $user USUARIO 
	@param string $pass CONTRASEÑA  
	*/
	public $sql;
	public function __construct($autenticate=true,$db=DB,$host=HOST,$user=USER,$pass=PASS)
	{
		if($autenticate)
        {
			if(!autenticate())
			{
				parent::__construct('','','','');
				$e= new OrveincaExeption("ERROR AL CONECTAR EL SISTEMA SESSION EXPIRADA");
			}else
			{
				parent::__construct($db,$host,$user,$pass);
			}
		}else
		{
			parent::__construct($db,$host,$user,$pass);
		}
		$_GET=$this->FilterSqlI($_GET);
		$_POST=$this->FilterSqlI($_POST);
	}
	
	/**
	REALIZA UNA CONSULTA (query) MYQLI 
	@return object OBJETOS MySQLi_Result
	*/
	public final function consulta($consulta,$sql_where=NULL,$sql_order=NULL,$LIMIT='')
	{
		$WHERE='';
		$ORDER='';
		if($sql_order!=NULL)
		{
			$ORDER="ORDER BY ".$sql_order;
		}
		if($sql_where!=NULL)
		{
			$WHERE="WHERE ".str_replace('-- ','',$sql_where)." ".$ORDER." ".$LIMIT;
		}else
		{
			
			$WHERE=str_replace('-- ','',$sql_where)." ".$ORDER." ".$LIMIT;
		}
		$sql=$consulta." ".$WHERE;
		if (!$this->connect_error)
		{
			if(!$result=$this->query($sql))
			{
				$error=" ".$this->error." numero: ".$this->errno."<br>";
				
				$this->errores.=$error;
				$e=new OrveincaExeption("<H2 align=center>ERROR DEL SISTEMA!!</H2>");
				$e->AddMsjMysql("ERROR: ".$this->error,"ERRNO ".$this->errno,$sql);
			}
			$this->result=$result;
			return $result;
		}
		
		return $this->result;
	}
	
	/**
	VACIA TODO EL RESULTADO DE UNA CONSULTA EN UN ARREGLO
	@return array RESULTADO DE CONSULTA
	*/
	public function result_array($type=MYSQLI_ASSOC)
	{
		if (!$this->connect_error)
		{
			$buffer=array();
			while($camp =$this->result->fetch_array($type))
			{
				array_push($buffer,$camp);
			}
			$this->free();
			return $buffer;
		}
	}
    public function array_json(array $array)
	{
		$buff="[";
		foreach($array as $i=>$var)
		{
			$buff.='"'.$var.'",';
		}
		if(count($array)>0)
		$buff=substr($buff,0,strlen($buff)-1);
		return $buff."]";	
	}
	
	/**
	CONFIGURACIONES DE LA BASE DE DATOS 
	@return array ARRAY CON LAS CONFIGURACIONES 
	*/
	public final function config()
	{
		$config=[];
		$this->consulta("SELECT * FROM config");
		while($campo=$this->result())
		{
			$config+=[$campo['desc_conf']=>$campo['valo_conf']];
		}

		return $config;
	}
	/**
	EDITA LA CONFIGURACION DEL LA BASE DE DATOS 
	@param array $_HTTP_VAR ARREGLO CON LAS CONFIGURACIONES 
	@return bool true si tubo exito
	*/
	public final function edit_config(array $_HTTP_VAR)
	{
		$this->autocommit(false);
		if(is_array($_HTTP_VAR))
		{
			foreach($_HTTP_VAR as $i=>$pos)
			{
				$config=explode('-',$i);
				
				if($config[0]=='conf')
				{
					$CONSULTA="UPDATE `config` SET `valo_conf` = '".($pos)."' WHERE `config`.`desc_conf` = '".$config[1]."';";
					if(!$this->consulta($CONSULTA))
					{
						$e= new OrveincaExeption("ERROR AL MODIFICAR LA CONFIGURACION ".$config[1]);
						break;
					}
				}	
			}

		}else
		{
			$e=new OrveincaExeption("EL PARAMETRO _HTTP_VAR DEVE SER UN ARRAY ");

			$this->autocommit(TRUE);
			return false;;
		}
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return TRUE;
		}else{
			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		} 
	}
/**
ELIMINA EL CONTENIDO DE LAS TABLAS TEMPORALES 
*/
	public function  obtimizar()
	{
		$_TMP_TABLES=['tem_nent_prod','temp_coti_prod'];
		foreach($_TMP_TABLES as $table)
		{
			$this->query("TRUNCATE TABLE `".$table."`");
		}
		$contactos=$this->query("SELECT * FROM contactos");
		while($cont=$contactos->fetch_array())
		{
			if($this->query("DELETE FROM contactos WHERE ci_cont='".$cont['ci_cont']."'"))
			{
				$this->query("DELETE FROM telefonos WHERE idet_pers='".$cont['ci_cont']."'  and id_tper='cont'");	
			}
		}
		$contactos->free();

	}

	
	/**
REALIZAR UNA CONSULTA SQL EN LA BSASE DE DATOS DE ORVEINCA
@param string $consulta consulta sql 
@param string $sql_where clausula where 
@return object MYSQLI_RESULT
*/


	public function editar_img_clpr($codi_clpr,$_FILE)
	{
		$this->autocommit(FALSE);
		$this->consulta("SELECT * FROM clas_prod WHERE codi_clpr='$codi_clpr'");
		$clpr=$this->result();
		if(!$this->consulta("UPDATE clas_prod SET id_imag='".$this->isertar_imagen($_FILE,NULL,NULL,$clpr['id_imag'])."' WHERE codi_clpr='$codi_clpr'"))
		{
			$e= new OrveincaExeption("ERROR AL EDITAR LA IMAGEN ");
		}
		if(!$this->error())
		{
			$this->commit();
			$this->autocommit(TRUE);
			return TRUE;
		}else{

			$this->rollback();
			$this->autocommit(TRUE);
			return false;
		} 
	}
	/** 
* INSERTAR NUMEROS DE TELEFONOS 
* @param $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
* @param $T_PERS CODIGO DEL TIPO DE PERSONA 
* @param $_IDENT IDENTIFICACION DE LA PERSONA 
* @return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE 
*/
	public function insertar_telefonos($_HTTP_VAR,$T_PERS,$_IDENT,$_VAR_NUM='telefono')
	{
		for($i=0;$i<sizeof($_HTTP_VAR[$_VAR_NUM]);$i++)
		{
			if($_HTTP_VAR[$_VAR_NUM][$i]!='')
				if(!$this->consulta("INSERT INTO telefonos VALUES ('".fmt_string($_HTTP_VAR[$_VAR_NUM][$i])."','".$_HTTP_VAR[$_IDENT]."','$T_PERS') "))
			{
				if($this->errno==$this->_ERRNO['DUPLICATE_KEY'])
					$e= new OrveincaExeption("ERROR AL INSERTAR UN REGISTRO DE TELEFONO ASEGURESE QUE EL NUMERO DE TELEFONO <H2>".$_HTTP_VAR[$_VAR_NUM][$i]."</H2> NO ESTE REGISTRADO BAJO OTRO NOMBRE");

				else
					$e= new OrveincaExeption("ERROR INESPERADO EN EL SISTEMA ");
				return false;
			}
		}
		if(!$this->error())
			return true;

	}
	/*
* INSERTAR CUENTAS BANCARIAS 
* @param array $_HTTP_VAR ARREGLO PROVENIENTE DE $_GET O $_POST
* @param $T_PERS CODIGO DEL TIPO DE PERSONA 
* @param $_IDENT IDENTIFICACION DE LA PERSONA 
* @return TRUE SI NO HAY ERRORES DE LO CONTRARIO FALSE 
*/
	public function insertar_cuent_banc($_HTTP_VAR,$T_PERS,$_IDENT)
	{
		$i=0;
		for($i=0;$i < sizeof($_HTTP_VAR['nro_cuenta']);$i++)
		{
			if($_HTTP_VAR['nro_cuenta'][$i]!="")
				if(!$this->consulta("INSERT INTO cuent_banc VALUES('".fmt_string($_HTTP_VAR['nro_cuenta'][$i])."','".$_HTTP_VAR['banco'][$i]."','".$_HTTP_VAR['t_cuenta'][$i]."','".fmt_string($_HTTP_VAR[$_IDENT])."','$T_PERS');",NULL))
			{
				if($this->errno==$this->_ERRNO['DUPLICATE_KEY'])
					$e= new OrveincaExeption("ERROR AL INSERTAR UN REGISTRO DE CUENTA BANCARIA ASEGURESE QUE EL NUMERO DE CUENTA <H2>".$_HTTP_VAR['nro_cuenta'][$i]."</H2> NO ESTE REGISTRADO BAJO OTRO NOMBRE");
				else
					$e= new OrveincaExeption("ERROR INESPERADO EN EL SISTEMA ");


				return false;
			}
		}	
		if(!$this->error())
			return TRUE;
	}

	/*SELECCIONAR CUENTA BANCARIAS*/
	public function cuent_banc($sql_where=NULL,$array=false)
	{
		$WHERE='';
		if($sql_where!=NULL)
		{
			$WHERE="WHERE ".$sql_where;
		}
		if($result=$this->consulta("SELECT `cuent_banc`.*,bancos.* 
		FROM `cuent_banc`
		LEFT JOIN bancos USING(id_banc)
		".$WHERE,NULL))
		{

			if(!$array)
			{
				return $result;
			}else
			{
				return $this->result_array();
			}	
		}else{
			$e= new OrveincaExeption("ERROR INESPERADO EN EL SISTEMA ");

			return FALSE;
		}	
	}
	/**
* INSERTA UNA IMAGEN EL LA TABLA IMAGENES 
* @param array $_FILES
* @param $h y $w para redimecionar 
* @param $id_imag EL ID DE LA IMAGEN EL LA BASE DE DATOS EN CASO DE EXISTIR
* @return ID DE LA IMAGEN 
*/
	public function isertar_imagen($_FILE,$w=NULL,$h=NULL,$id_imag=NULL)
	{
		$foto_temporal= $_FILE['foto']['tmp_name'];
		$foto_type= $_FILE['foto']['type'];
		$foto_size=$_FILE['foto']['size'];
		if(!$extension=$this->img_ext($foto_type))
			return false;

		if($foto_size != 0)
		{
			if($w!=NULL)
			{
				$name=$this->redime_img($foto_temporal,$foto_type,$w,$h);
			}else
			{
				$name=$foto_temporal;
			}
			try
			{
				$f1= fopen($name,"rb");
				$imagen = fread($f1, $foto_size);
				fclose($f1);
				$imagen=addslashes($imagen);
			}catch(Exeption $e) {
				$e= new OrveincaExeption("ERRO DESCONOCIDO AL ENVIAR LA IMAGEN",$e);
				$this->errores.=' Caught exception: '. $e->getMessage(). " ";

			}
			if($id_imag==NULL || $id_imag==0 )
			{
				$sql = "INSERT INTO  imagenes values('','$imagen',  '$foto_type',  '$extension',  '$foto_size')";

				$this->consulta($sql,NULL);

				$this->consulta("SELECT * FROM  imagenes ORDER BY  `id_imag` DESC  LIMIT 1",NULL);
				if(!$this->error)
				{
					$img=$this->result();
					return $img['id_imag'];
				}
			}else
			{
				$sql="UPDATE imagenes SET img='$imagen', tama_imag='$foto_size', fmt='$foto_type', ext='$extension' WHERE id_imag='$id_imag'";
				$this->consulta($sql,NULL);
				if(!$this->error)
				{
					return $id_imag;
				}
			}
			if($this->error)
			{
				$e= new OrveincaExeption("ERROR DESCONOCIDO AL ENVIAR LA IMAGEN  AL SISTEMA");

				return false;
			}
		}else
		{
			$e= new OrveincaExeption("No ha podido transferirse el fichero ");
			return false;
		}
	}
	/**
* INSERTAR TAMAÑOS 
* @param $tamano EL TAMAÑO A INSERTAR
* @param $id_umedida CODIGO DE LA UNIDAD DE MEDIDA 
* @return SI NO HAY ERRORES RETORNA EL ID DEL TAMAÑO
*/
	public function insert_tamano($tamano,$id_umedida)
	{
		$CONS_TAM="SELECT * FROM tamanos WHERE medi_tama='$tamano' and codi_umed='$id_umedida'";
		if(!$this->consulta($CONS_TAM,NULL))
		{
			$e= new OrveincaExeption("ERROR AL BUSCAR  EL TAMAÑO");


			return false;
		}
		if($this->result->num_rows==0)
		{

			if($this->consulta("INSERT INTO tamanos VALUES('','$tamano','$id_umedida')",NULL))
			{
				$this->consulta("SELECT tamanos.id_tama FROM tamanos ORDER BY  `id_tama` DESC  ");
				$medida=$this->result();
				return $medida['id_tama'];
			}else
			{ 
				$e= new OrveincaExeption("ERROR AL ISERTAR EL TAMAÑO");


				return false;
			}
		}else
		{
			$medida=$this->result();
			return $medida['id_tama'];
		}
	}


	/*
* RETORNA LA EXTENCION SEGUN RL TIPO
* @param TIPO DE IMAGEN 
* @return EXTENCION IMAGEN
*/
	public function img_ext($foto_type)
	{
		if ($foto_type=="image/x-png" || $foto_type=="image/png" )
		{

			return '.png';
		}else
		{
			$e= new OrveincaExeption("$foto_type FORMATO DE IMAGEN NO ADMITIDO");

			return false;
		}

	}
/**
* REDIMENCION UN IMAGEN Y LA GUARDA EN ARCHIVOS TEMPORALES
* @param $imagen NOMBRE DEL ARCHIVO 
* @param $imagen_type TIPO DE IMAGEN
* @param $h y $w EL TAMAÑO FINAL  
* @return NOMBRE DEL ARCCHIVO TEMPORAL
*/
	private function redime_img($imagen,$imagen_type,$w,$h)
	{
		$extension=$this->img_ext($imagen_type);
		$ext=substr($extension,1,strlen($extension));

		$temp= $this->ROOT_HTML."temp/".rand();
		try
		{
			$IMG= new IMG($w,$h,'image/png');
			$IMG->importar_img('imagen',$imagen,$ext);
			$IMG->print_img_import('imagen',0,0,0,0,$IMG->w,$IMG->h);
			$IMG->Output($temp,"F");
		}
		catch(Exception $e) {
			new OrveincaExeption("ERRO DESCONOCIDO AL ENVIAR LA IMAGEN",$e);
			$this->errores.=' Caught exception: '. $e->getMessage(). " ";
			//    $this->error=$e->getMessage();
		}
		return $temp.$extension;	
	}
}
?>