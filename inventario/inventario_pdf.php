<?php

require_once("../clases/config_orveinca.php");

$header = array(
'codigo'=>array("CODIGO",20,'C'),
'descripcion'=>array("DESCRIPCION",90),
'color'=>array("COLOR",25),
'tamano'=>array("TAMANO",18),
'cantidad'=>array("CANTIDAD",22,'C'),
'tbsf'=>array("T_BSF",25,'C')
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
//if(!$inventario=$mysql->consulta(PRODUCTOS::INVENTARIO1,'1 GROUP by id_prod,exad_colo,id_tama ',' id_prod'))
if(!$inventario=$mysql->consulta("SELECT * FROM inventario"))
{
	exit();
}
$pdf_inventario=array();
$i=0;
$total_prod=0;
$total_bs=0;
for($i=0;$campo=$inventario->fetch_array();$i++)
{
	/*$mysql->consulta("
		SELECT SUM(nent_prod.cant_nent) as cant_vend
FROM nent_prod
WHERE id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")."
GROUP by id_prod,exad_colo,id_tama 
		");
		
		$vendio=$mysql->result();
		$existencia=$campo['cant_reci']-$vendio['cant_vend'];
	if($existencia>0)
	{*/
	$existencia=$campo['existencia'];
		 $costo=0;
		 $const_comp=0;
		 $aux_exist=$existencia;
		 $mysql->sql='';
		$cost_result=$mysql->consulta("SELECT * FROM faco_prod where id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")." order by  faco_prod.nume_orde DESC ");
		//echo $mysql->sql;
		 while($cost=$mysql->result())
		 {
			 if($aux_exist>$cost['cant_reci'])
			 {
				 $aux_exist-=$cost['cant_reci'];
				 $const_comp+=$cost['cant_reci']*$cost['cost_comp'];
			 }else
			 {
				 $const_comp+=$aux_exist*$cost['cost_comp'];
				 break;
			 }
		 }
		$mysql->result->data_seek(0);
		$cost=$mysql->result();
		$mysql->result->free();
		
		$costo=$cost['cant_reci'];
		$tbs= $const_comp;
		$array=array(
		'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
		'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),35,"\n"),
		'tamano'=>$campo['codi_umed']." ".$campo['medi_tama'],
		'color'=>$campo['desc_colo'],
		'cantidad'=>$existencia,
		'tbsf'=>fmt_num($const_comp),
		);
		$total_bs+=$tbs;
		$total_prod+=$existencia;
		array_push($pdf_inventario,$array);
		
	//}
}
//$pdf = new MYSQL_PDF('P','mm',array(227,355.6));
$pdf = new ORVEINCA_PDF('P','mm','Legal');

$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo("INVENTARIO",100,'airstrike','',28);

$pdf->AddPage();
$pdf->Table($header,$pdf_inventario,10,5,'arial');
$header=[
'1'=>[" ",100],
'2'=>[" ",100],
];
$pdf_info=[
[
'1'=>'TOTAL PRODUCTOS EN INVENTARIO ',
'2'=>$total_prod
],
[
'1'=>'TOTAL DE BOLIVARES EN INVENTARIO',
'2'=>fmt_num($total_bs)
]
];
$pdf->titulo("IFORMACION DE INVENTARIO ",100,'airstrike','',20);

$pdf->AddPage();
$pdf->Table($header,$pdf_info,10,5,'arial');
$pdf->Output('INVENTARIO.pdf','I');


?>