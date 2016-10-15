<?php
class DATABASE extends MySQLi
{
	protected $errores;
	public $result;
	protected $user;
	protected $pass;
	protected $db;
	public $_ERRNO = array(
		'restricciones'=> 1451,
		'DUPLICATE_KEY'=> 1062
	);
	public $ROOT_HTML='../';
	/**
	CONSTRUCTOR DE LA CLASE 
	@param bool $autenticate INDICA SE SE AUTETICARA LA CONECCION CON COOKIE 
	@param string $db BABSE DE DATOS
	@param string $host HOSTING 
	@param string $user USUARIO 
	@param string $pass CONTRASEÑA  
	*/
	public function __construct($db,$host,$user,$pass)
	{
		$this->conectar($host,$user,$pass,$db);
		
	}
	public function __destruct()
	{

	}
	/**
	ALIAS DE MySQLi::connect($host,$user,$pass,$db);
	*/
	public final function conectar($host,$user,$pass,$db)
	{
		try
		{
			$this->user=$user;
			$this->pass=$pass;
			$this->db=$db;
			@parent::connect($host,$user,$pass,$db);
		}catch(OrveincaExeption $e) 
		{
			$e->AddMsjMysql($this->connect_error,'');
		}
		if (!$this->connect_error)
		{
			$this->result=  NULL;
		}else
		{
			$e= new SysExeption("ERROR AL CONECTAR EL SISTEMA ");
			$e->AddMsjMysql($this->connect_error,'');
		}	
	}
	public function GetDB()
	{
		return $this->db;	
	}
	/**
	VERIFICA EL VALOR A AUTOCOMMIT
	@return bool 
	*/
	public final function is_autocommit()
	{
		if(!$this->connect_error)
		{
			if(!($res=$this->query("SELECT @@autocommit")))
			{
				$e= new SysExeption("ERROR AL CONECTAR EL SISTEMA ");
				$e->AddMsjMysql($this->error,$this->errno);
			}
			$commit=$res->fetch_row();
			$res->free();
			return ((bool)$commit[0]);
		}
	}
	/**
	EN CASO DE ABER ERROR RETORNA UN STRING DE LO 
	CONTRARIO RETORNA FALSE
	*/
	public final function error()
	{
		if (!(bool)$this->connect_error)
		{
			$error=$this->errores;
			if(!SysExeption::_Empty())
			return $error." SysExeption ";
			return 	trim($error)==''?false:$error;
		}else
			return $this->connect_error;
	}
	/**
	INYECTA UN ERROR 
	*/
	public function SetError($err)
	{
		$this->errores.=$err;
	}
	/**
	RETORNA EL VALOR AUTOICREMENT DE UNA TABLA 
	@param string $tabla TABLA SQL 
	@param string $coll COLUMNA AUTOINCRMENT DE LA TABLA 
	return int valor autoincremet
	*/
	public final function GetAutoIncremet($tabla,$coll)
	{
		if(!$this->consulta("SELECT max(".$coll.") from ".$tabla))
		{
			$e= new SysExeption("ERROR NO AUTOINCREMET");
		}
		$row=$this->result->fetch_row();
		$this->result->free();
		return $row[0];
	}
	/**
	REALIZA UNA CONSULTA (query) MYQLI 
	@return object OBJETOS MySQLi_Result
	*/
	public  function consulta($consulta)
	{
	
		$sql=$consulta;
		if (!$this->connect_error)
		{
			if(!$result=$this->query($sql))
			{
				$error=" ".$this->error." numero: ".$this->errno."<br>";
				$this->errores.=$error;
				$e=new SysExeption("<H2 align=center>ERROR AL CONSULTAR LA BD!!</H2>");
				$e->AddMsjMysql("ERROR: ".$this->error,"ERRNO ".$this->errno);
			}
			$this->result=$result;
			return $result;
		}
		
		return $this->result;
	}
	public final function free()
	{
		if (!$this->connect_error)
			$this->result->free() ;
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
			//$this->free();
			return $buffer;
		}
	}
	/**
	RESULTADO LA CONSULTA GUARDADA EN RESULT 
	*/
	public function result($type=MYSQLI_ASSOC)
	{
		if (!$this->connect_error && !$this->error)
			return $this->result->fetch_array($type);
	}
/**
AGREGA COLUMNAS Y JOINS A UNA CONSULTA PREVIAMENTE DEFINIDA 
@param string $consulta CONSULTA SQL 
@param array $coll COLUMNAS A AGREGAR 
@param array $join JOINS A AGREGAR 
@return string CONSULTA SQL CON LAS COLUMNAS Y JOIS AGREGADOS 
*/
	public function AddCollConsulta($consulta,array $coll,array $join=array())
	{
		$consulta=strtolower($consulta);
		$sql1=substr(trim($consulta),6,strlen(trim($consulta)));
		//print_r($mact);
		$sql="SELECT ".implode(',',$coll).", ".$sql1;
		$sql_ex=explode('where',$sql);
		$sql_ex[0].=implode($join);
		$sql=implode('where',$sql_ex);
		return $sql;
	}
	/* utiliza real_escape_string para filtrar ataques sql inyeccion 
	puede filtra un lo elementos de un array completo*/
	
	public function FilterSqlI($val)
	{
		if(is_string($val))
		{
			return $this->real_escape_string($val);	
		}elseif(is_array($val))
		{
			foreach($val as $i=>$v)
			{
				$val[$i]=$this->FilterSqlI($v);
			}
			return $val;
		}
		return $val;
	}
/**
*   GENERA UNA BUSQUEDA SQL 
*	@param $SQL sentencia sql que se ejecutara OJO(no puede llevar clausulas where,GROUP,ORDER,LIMIT )
*	@param $cadena cadena de caracteres que se buscara 
*	@param $campos campos de la sentencia sql donde se buscara la cadena 
*	@param $PLUS_SQL sql adicional OJO(no puede llevar la clausulas ORDER,LIMIT )
*	@param $limite LIMITE DE FILAS
*   @return OBJET  MySQLi_Result
*/
	public function busquedas_sql($SQL,$cadena,$campos,$PLUS_SQL='',$limite=50)
	{
		if(is_array($cadena))
		{
			$cadena=implode(' ',$cadena);
		}
		$trozos=explode(' ',trim($cadena));
		$select='';
		$were='';
		foreach($campos as $campo)
		{
			$select.=" (".$campo." is NOT NULL AND ".$campo." like '%".$cadena."%') + 
			(".$campo." is NOT NULL AND ".$campo." like '".$cadena."%')+";
			$were.=" ( ".$campo." is NOT NULL AND ".$campo." like '%".$cadena."%') or ";
		}
		foreach($trozos as $palabra)
		{
			//if(strlen($palabra)>2 || (is_int($palabra) || is_float($palabra)))
			foreach($campos as $campo)
			{
				$select.=" (".$campo." is NOT NULL AND ".$campo." like '%".$palabra."%') + (".$campo." is NOT NULL AND ".$campo." like '".$palabra."%')+";
				$were.="(".$campo." is NOT NULL AND ".$campo." like '%".$palabra."%') or ";
			}
		}
		$solo='';
		if(count($campos)>1)
		{
			$select.="(( CONCAT(";
			$were.=" (( CONCAT(";
			foreach($campos as $i=>$campo)
			{
				!is_int($i)?$espace="''":$espace="' '";
				$select.="IF($campo IS NOT NULL,$campo,' '),".$espace.",";
				$were.="IF($campo IS NOT NULL,$campo,' '),".$espace.",";
			}
			$select.="'') like '%".$cadena."%' )+1) ";
			$were.="'') like '%".$cadena."%' ))";
		}else
		{	$select.="(0)";
			$were.="( ".$campos[0]."='".$cadena."') ";
			$solo="* (IF(".$campos[0]." = '".$cadena."',0 ,1))+(IF(".$campos[0]." = '".$cadena."',10,0))";
		
		}
		//preg_match('/select.*from/',$SQL,$NEW_SQL);
		$PLUS_SQL=strtolower($PLUS_SQL);
		$PLUS_SQL=str_replace('where','',$PLUS_SQL);

		$sql=$this->AddCollConsulta($SQL,[' ((('.$select.')) '.$solo.') as puntaje_busqueda '])." WHERE (".$were.") ".$PLUS_SQL." ORDER BY  puntaje_busqueda  DESC LIMIT ".$limite;
		
		return $this->consulta($sql);
	}
	/**
	SI LA VARIABLE ES NULL RETURNA UN NULL VALIDO SQL 
	@param strig $value valor a procesar 
	@param strig $tb  QUE INDICA  EL MODO DE RETORNO CON I => = y S is 
	@return string null validos sql
	 **/

	public static function sql_null($value,$tb='I')
	{
		if($value=='' || $value=='NULL' || $value=='null' )
		{
			switch($tb)
			{
				case 'I':return 'NULL';
				case 'S':return 'is NULL';
			}
		}
		switch($tb)
		{
			case 'I':return "'".$value."'";;
			case 'S':return "='".$value."'";
		}

	}
	
	public function &ResultJson()
	{
		$json= new Json();
		$json->Set('error',false);
		if($this->error())
		{
			$json->Set('error',sysExeption::GetExeptionS());
			return $json;
		}
		$json->Set('num_rows',$this->result->num_rows);
		if($this->result->num_rows==0)
		{
			$json->Set('result',array());
			return $json;
		}
		$array=array();
		while($obj=$this->result->fetch_object())
		{
			array_push($array,$obj);
		}
		$json->Set('result',$array);
		return $json;
	}
	/**
	COMBIERTE EL RESULTADO DE UNA CONSULTA SQL EN UN OBJETO JSON
	@return string objeto json
	*/
	public function &result_array_json()
	{
		$json = new Json();
		if($this->error())
		{
			$json->Set('error',sysExeption::GetExeptionS());
			return $json;
		}
		if($this->result->num_rows==0)
		{
			$json->Set('error',false);
			$json->Set('num_rows',$this->result->num_rows);
			return $json;
		}
		//$array=$this->result_array();
		$this->result->data_seek(0);
		$campo=$this->result();
		foreach($campo as $i=>$val)
		{
			$$i=array();//declaro la variable con el nombre de el campo
		}
		$this->result->data_seek(0);
		while($campo=$this->result())
		{
			foreach($campo as $i=>$val)
			{
				array_push($$i,$val);//le agrego los datos 
			}
		}
		$this->result->data_seek(0);
		$campo=$this->result();
		foreach($campo as $i=>$val)
		{
			$json->Set($i,$$i);
		}
		$json->Set('num_rows',$this->result->num_rows);
		$json->Set('error',false);
		return $json;
	}
	public function ExportDB($mysql='',$BD=NULL)
	{
		$bd=is_null($BD)?$this->db:$BD;
		$output='';
		if(!empty($_SERVER['MYSQL_HOME']))
		{
			$dirmysql=$_SERVER['DOCUMENT_ROOT']."/../..".$_SERVER['MYSQL_HOME']."/";
		}else
		{
			$dirmysql=$_SERVER['DOCUMENT_ROOT']."/../bin/";
		}
		
		
		$output=shell_exec($mysql."mysqldump -u ".$this->user." -p".$this->pass." --single-transaction --complete-insert --create-options --hex-blob --skip-add-locks --databases ".$bd." ");
		
		if(is_null($output))
		{
		
			$output=shell_exec($dirmysql."mysqldump -u ".$this->user." -p".$this->pass." --single-transaction --complete-insert --create-options --hex-blob --skip-add-locks --databases ".$bd." ");
			if(is_null($output))
			{
				$e = new SysExeption("ERROR AL EXPORTAR LA BASE DE DATOS");
			}
		}
		return $output;
	}
	public function ExportDBGzip($mysql='',$BD=NULL)
	{
		$conten=$this->ExportDB($mysql,$BD);
		if(is_null($conten))
		{
			return 	$conten;
		}
		return gzencode($conten,9,FORCE_GZIP);
	}
	public function ImportDBFile($file)
	{
		$cont=file_get_contents($file);
		$sql= str_replace("\n","\r\n",$cont);;
		
		return $this->ImportDB($sql);
	}
	public function ImportDBFileGzip($file)
	{
		$f=gzopen($file,"r9");
		$sql=''; //gzread($f,gzsize($file));
		while(!gzeof($f))
		{
			$sql.=gzgets($f)."\r\n";
		}
		gzclose($f);
		//echo $sql;
		//$sql= str_replace("\n","\r\n",$sql);;
		return $this->ImportDB($sql);
	}
	public function VerificaImportDB()//redefinir
	{
		if($this->error)
		{
			$this->rollback();
				$this->autocommit(true);
			$e =new SysExeption("ERROR AL IMPORTAR LA BASE DE DATOS ");	
			$e->AddMsjMysql($this->error,$this->errno);
			return FALSE;
		}else
		{
			$this->commit();
			$this->autocommit(true);
			return true;	
		}
	}
	public function ImportDB($sql)
	{
		if($sql=='')
		$this->autocommit(false);
		$this->multi_query($sql);
		return $this->VerificaImportDB();
	}
}