<?php
require("../clases/config_orveinca.php");
$buffer= new DocumentBuffer(true,true,false);
$database= new BD_ORVEINCA();
//json
if(!empty($_POST['insert_color_pedi_p']) && !empty($_POST['id_pepr']))
{
	unset($database);
	$database= new PRODUCTOS();
	$buffer->SetTypeMin('json');
	$database->autocommit(false);
	if($_POST['desc_colo']=='')
	{
		$json= new Json();
		$json->Set('error',"PORFAVOR INGRESA LA DESCRIPCION DEL COLOR");
		echo $json;
		exit;	
	}
	$database->insertar_color($_POST);
	//$database->consulta("INSERT INTO `colores` VALUES ('".$_POST['exad']."', '".$_POST['desc_colo']."')");
	$errno=$database->error?$database->errno:'';
	$database->consulta("UPDATE .`pedi_prod` SET `exad_colo` = ".BD_ORVEINCA::sql_null($_POST['exad'])." WHERE `pedi_prod`.`id_pepr` = '".$_POST['id_pepr']."'");
	if(!$database->error())
	{
		$database->commit();
		$database->autocommit(true);
		$database->consulta("SELECT * FROM colores WHERE exad='".$_POST['exad']."'");
		echo $database->ResultJson()->Set('error',OrveincaExeption::_Empty()?false:OrveincaExeption::GetExeptionS());
	}else
	{
		$json= new Json();
		$json->Set('error',OrveincaExeption::GetExeptionS());
		$database->rollback();
		
		echo $json;
	}
	
	
}
if(!empty($_POST['edit_color_pedi_p']) && !empty($_POST['id_pepr']))
{
	$buffer->SetTypeMin('json');
	$database->consulta("UPDATE .`pedi_prod` SET `exad_colo` = ".BD_ORVEINCA::sql_null($_POST['exad'])." WHERE `pedi_prod`.`id_pepr` = '".$_POST['id_pepr']."' and cant_entr=0");
	$database->consulta("SELECT * FROM colores WHERE exad='".$_POST['exad']."'");
	$JSON=$database->ResultJson()->Set('error',OrveincaExeption::_Empty()?false:OrveincaExeption::GetExeptionS());
	if($database->result->num_rows==0)
	{
		//$R=$JSON->Get('result');
		$R=array(0=>((object)array('exad'=>'NULL','desc_colo'=>'')));
		$JSON->Set('result',$R);
	}
	
	echo $JSON;
}
if(!empty($_POST['Ccolor']))
{
	$buffer->SetTypeMin('json');
	$database->consulta("SELECT * FROM colores ");
	echo  $database->ResultJson()->Set('error',OrveincaExeption::_Empty()?false:OrveincaExeption::GetExeptionS());
}
if(!empty($_POST['bancos_json']))
{ 
	$buffer->SetTypeMin('json');
	$database->consulta("SELECT * FROM bancos");
	echo $database->result_array_json();
}

if(!empty($_POST['estados_json']))
{  
	$buffer->SetTypeMin('json');
	$database->consulta("SELECT * FROM estados ORDER BY desc_esta",NULL);
	if($database->result->num_rows>0)
	{
		echo $database->result_array_json();
	}else
	{
		echo '{"id_esta":["NULL"],"desc_esta":[""]}';
	}
	
}
if(!empty($_POST['municipios_json']) && !empty($_POST['id_esta']))
{
	$buffer->SetTypeMin('json');
	$database->consulta("SELECT * FROM municipios where id_esta='$_POST[id_esta]' ORDER BY desc_muni",NULL);
	if($database->result->num_rows>0)
	{
		echo $database->result_array_json();
	}else
	{
		echo '{"id_muni":["NULL"],"desc_muni":[]}';
	}
}else
	if(!empty($_POST['parroquias_json']) && !empty($_POST['id_muni']))
{
	$database->consulta("SELECT * FROM parroquias where id_muni='$_POST[id_muni]' ORDER BY desc_parr",NULL);
	$buffer->SetTypeMin('json');
	if($database->result->num_rows>0)
	{
		echo $database->result_array_json();
	}else
	{
		echo '{ "id_parr":[ "NULL" ],"desc_parr":[ "" ],"id_muni":[ "NULL" ]}';
	}
}

if(!empty($_POST['contactos_json']))
{
	if($_POST['contactos_json']==1)
	{
		$database->consulta("SELECT * FROM contactos",NULL);
		$buffer->SetTypeMin('json');
		echo $database->result_array_json();
	}
}
//html
if(!empty($_POST['contactos']))
{
	if($_POST['contactos']==1)
	{
		$database->consulta("SELECT * FROM contactos",NULL);
		echo "<select name='ci_cont' >
		 <option value='NULL' selected>CONTACTOS</option>";
		while($campo=$database->result())
		{
			echo "<option value='$campo[ci_cont]'>$campo[nom1_cont] $campo[ape2_cont]</option>";
		}
		echo "

		</select>

		";
	}

}
if(!empty($_POST['buscar_contacto']) && !empty($_POST['value']))
{
	$consulta="select contactos.*  FROM contactos";
	$database->busquedas_sql($consulta,$_POST['value'],['ci_cont','nom1_cont','nom2_cont','ape1_cont','ape2_cont'],'',7);
	//$database->consulta(CLIENTES::CLIE,$sql,NULL,'LIMIT 7');
	echo $database->error;//.print_r($database->GetConsultas());
	$is_row_act=true;
	while($campo=$database->result())
	{
		if($is_row_act)
			$row_act=' row_act';
		else
			$row_act='';
		$is_row_act=!$is_row_act;
		echo "<a><li class='clie_row $row_act' title='".$campo['ci_cont']."'>".$campo['nom1_cont']." ".$campo['ape1_cont']."</li></a>";
	}
	echo "<script>
$(document).ready(function(e) {
   $('.clie_row').click(function(e) {

    $('input[name=idet]').attr('value',''+$(this).attr('title'));
	}); 
});
</script>";
}
if(!empty($_POST['del_tel'])  && !empty($_POST['telf']))
{

	if(!$database->consulta("DELETE FROM telefonos WHERE `#telf`='$_POST[telf]'",NULL))
	{
		echo "ERROR AL ELIMINAL EL NUMERO DE TELEFONO";
	}

}
if(!empty($_POST['del_banc'])  && !empty($_POST['numero']))
{

	if(!$database->consulta("DELETE FROM cuent_banc WHERE `#cuenta`='$_POST[numero]'",NULL))
	{
		echo "ERROR AL ELIMINAL LA CUENTA BANCARIA";
	}

}
if(!empty($_FILES["foto"]))
{
	$temp='../temp/';
	if(!empty($_POST['root']))
	{
		$temp="temp/";
	}else
	{
		$temp="../temp/";
	}
	$foto_temporal= $_FILES['foto']['tmp_name'];
	if(!$ext=$database->img_ext($_FILES['foto']['type']))
	{
		$buffer->SetTypeMin('json');
		echo '{ "src":"../mysql/img_error.php?error=PORFAVOR SELECCIONA UNA IMAGEN .PNG" }';
		exit;
	}
	$src = (rand()).$database->img_ext($_FILES['foto']['type']);
	move_uploaded_file($foto_temporal,"../temp/".$src);
	$buffer->SetTypeMin('json');
	echo '{ "src":"'.$temp.$src.'" }';
}
?>