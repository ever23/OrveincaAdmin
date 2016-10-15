<?php

require_once("../clases/config_orveinca.php");

$header = array(
'codigo'=>array("CODIGO",18),
'descripcion'=>array("DESCRIPCION",90),
'color'=>array("COLOR",25),
'tamano'=>array("TAMANO",22),
'cantidad'=>array("CANTIDAD",22),
'tbsf'=>array("T_BSF",20)
);

if(!empty($where))
{
	$WHERE=" ".stripslashes($where);
}else
{
	$WHERE=NULL;
}
$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$titulo;

$time= new TIME();
$time->Set_date($_GET['value'].'-0');
switch($_GET['reporte'])
{
	case 'mes':
		$titulo="DE ".$time->mes_cadena()." ".$time->ano;
		$where_entr=" fech_fact>='".$time->ano."-".$time->mes."-1' and fech_fact<'".$time->ano."-".($time->mes+1)."-1'";
		$where_salida=" fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'";
		$inve1=" fech_fact<'".$time->ano."-".($time->mes)."-1'";
		$inve2=" fech_nent<'".$time->ano."-".($time->mes)."-1'";
		$inve3="fech_fact<'".$time->ano."-".($time->mes+1)."-1'";
	break;	
	case 'ano':
		$titulo="DEL ANO ".$time->ano;
		$where_entr=" fech_fact>='".$time->ano."-1-1' and fech_fact<'".($time->ano+1)."-1-1'";
		$where_salida=" fech_nent>='".$time->ano."-1-1' and fech_nent<'".($time->ano+1)."-1-1'";
		$inve1=" fech_fact<'".$time->ano."-1-1'";
		$inve2=" fech_nent<'".$time->ano."-1-1'";
		$inve3="fech_fact<'".($time->ano+1)."-1-1'";
	break;	
	
	
}

if(!$mysql->consulta(PRODUCTOS::REPORTE_ENTRADA,$where_entr." GROUP by faco_prod.id_prod,faco_prod.exad_colo,faco_prod.id_tama "))
{
		echo "ERRO AL CONSULTAR LA ENTRADA ".$mysql->error;;
		exit();
}
$pdf_inventario_entrada=array();
$i=0;
$total_prod_entr=0;
$total_bs_entr=0;
foreach($mysql->result_array() as $i=>$campo)
{
	$array=array(
	'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
	'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),35,"\n"),
	'tamano'=>$campo['codi_umed']." ".$campo['medi_tama'],
	'color'=>$campo['desc_colo'],
	
	'cantidad'=>$campo['cantidad'],
	'tbsf'=>fmt_num($campo['total_bs']),
	);
	$total_bs_entr+=$campo['total_bs'];
	$total_prod_entr+=$campo['cantidad'];
	array_push($pdf_inventario_entrada,$array);
	
}


if(!$mysql->consulta(PRODUCTOS::REPORTE_SALIDA,$where_salida." GROUP by `nent_prod`.id_prod,`nent_prod`.exad_colo,`nent_prod`.id_tama "))
{
		echo "ERRO AL CONSULTAR LA SALIDA ".$mysql->error;
		exit();
}
$pdf_inventario_salida=array();
$i=0;
$total_prod_sali=0;
$total_bs_sali=0;
foreach($mysql->result_array() as $i=>$campo)
{
	 $costo='';
	

	$array=array(
	'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
	'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),35,"\n"),
	'tamano'=>$campo['codi_umed']." ".$campo['medi_tama'],
	'color'=>$campo['desc_colo'],
	
	'cantidad'=>$campo['cantidad'],
	'tbsf'=>fmt_num($campo['total_bs']),
	);
	$total_bs_sali+=$campo['total_bs'];
	$total_prod_sali+=$campo['cantidad'];
	array_push($pdf_inventario_salida,$array);
	
}

if(!$inventario=$mysql->consulta('select * from inventario'))
{
	echo "ERRO AL CONSULTAR LA TABLA CLIENTES ";
	exit();
}
$pdf_inventario=array();
$i=0;
$total_prod=0;
$total_bs=0;
for($i=0;$campo=$inventario->fetch_array();$i++)
{
	$mysql->consulta(PRODUCTOS::INVENTARIO1,"id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")." and ".$inve1.' GROUP by id_prod,exad_colo,id_tama ',' id_prod');
	
	$resul_inve1=$mysql->result();
	$mysql->consulta("
		SELECT SUM(nent_prod.cant_nent) as cant_vend
FROM nent_prod
LEFT JOIN nota_entrg using(nume_nent)
WHERE id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]' ":" IS NULL ")." and ".$inve2." 
GROUP by id_prod,exad_colo,id_tama 
		");
		
	$resul_inve2=$mysql->result();
	
	$mysql->consulta(PRODUCTOS::INVENTARIO1,
	"id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")." and ".$where_entr.'  GROUP by id_prod,exad_colo,id_tama ',' id_prod');
	
	$entr=$mysql->result();
	
	$mysql->consulta("
		SELECT SUM(nent_prod.cant_nent) as cant_vend
FROM nent_prod
LEFT JOIN nota_entrg using(nume_nent)
WHERE id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")." and ".$where_salida."
GROUP by id_prod,exad_colo,id_tama 
		");
		
		$sali=$mysql->result();
		$exist_ant=$resul_inve1['cant_reci']-$resul_inve2['cant_vend'];
		$exist_act=$entr['cant_reci']-($sali['cant_vend']-$exist_ant);
		
		
		$existencia=$exist_act;
	if($existencia>0)
	{
		 $costo='';
		 $const_comp=0;
		 $aux_exist=$existencia;
		 $mysql->sql='';
		$cost_result=$mysql->consulta("SELECT * FROM faco_prod
		left join fact_comp using(nume_orde)
		 where id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")."  and ".$inve3."  order by  faco_prod.nume_orde DESC ");
		//echo $mysql->sql;
		 while($cost=$mysql->result())
		 {
			 if($aux_exist>$cost['cant_reci'])
			 {
				 $aux_exist-=$cost['cant_reci'];
				 $const_comp+=$cost['cant_reci']*$cost['cost_comp'];
				// echo $cost['cant_reci'].'*'.$cost['cost_comp'].'='.$cost['cant_reci']*$cost['cost_comp'].'<br>';
			 }else
			 {
				 $const_comp+=$aux_exist*$cost['cost_comp'];
				 //  echo $aux_exist.'*'.$cost['cost_comp'].'='.$aux_exist*$cost['cost_comp'].'<br>';
				 break;
			 }
		 }
		$mysql->result->data_seek(0);
		$cost=$mysql->result();
		$mysql->result->free();
		$tbs=$const_comp;
		$array=array(
		'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
		'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),35,"\n"),
		'tamano'=>$campo['codi_umed']." ".$campo['medi_tama'],
		'color'=>$campo['desc_colo'],
		'cantidad'=>$existencia,
		'tbsf'=>fmt_num($tbs),
		);
		$total_bs+=$tbs;
		$total_prod+=$existencia;
		array_push($pdf_inventario,$array);
		
	}
}
//$pdf = new MYSQL_PDF('P','mm',array(227,355.6));

$pdf = new ORVEINCA_PDF('P','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo("ENTRADA AL INVENTARION ".$titulo,100,'airstrike','',20);

$pdf->AddPage();
$pdf->Table($header,$pdf_inventario_entrada,10,5,'arial');

$pdf->titulo("SALIDA DEL INVENTARION ".$titulo,100,'airstrike','',20);
$pdf->AddPage();
$pdf->Table($header,$pdf_inventario_salida,10,5,'arial');;


$pdf->titulo(" INVENTARION ".$titulo,100,'airstrike','',20);

$pdf->AddPage();
$pdf->Table($header,$pdf_inventario,10,5,'arial');;

$header=[
'1'=>[" ",100],
'2'=>[" ",100],

];
$pdf_info=[

[
'1'=>'TOTAL PRODUCTOS COMPRADOS ',
'2'=>$total_prod_entr
],
[
'1'=>'TOTAL DE BOLIVARES COMPRADOS',
'2'=>fmt_num($total_bs_entr)
],
[
'1'=>'TOTAL PRODUCTOS VENDIDOS ',
'2'=>$total_prod_sali
],
[
'1'=>'TOTAL DE BOLIVARES VENDIDOS',
'2'=>fmt_num($total_bs_sali)
],
[
'1'=>'TOTAL PRODUCTOS EN INVENTARIO ',
'2'=>$total_prod
],
[
'1'=>'TOTAL DE BOLIVARES EN INVENTARIO',
'2'=>fmt_num($total_bs)
]


];
$pdf->titulo("IFORMACION DE INVENTARIO ".$titulo,100,'airstrike','',20);

$pdf->AddPage();
$pdf->Table($header,$pdf_info,10,5);;


$pdf->Output('REPORTE DE INVENTARIO '.$titulo.'.pdf','I');


?>