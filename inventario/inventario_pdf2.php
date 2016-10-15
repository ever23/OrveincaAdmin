<?php
require_once("../clases/config_orveinca.php");

$header = array(
'codigo'=>array("CODIGO",18,'C'),
'descripcion'=>array("DESCRIPCION",80),
'color'=>array("COLOR",25),
'tamano'=>array("TAMANO",18),
'cantidad'=>array("CANTIDAD",22,'C'),
'precio'=>array("PRECIO U",20,'C'),
'tbsf'=>array("T_BSF",18,'C')
);

$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
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
			   $const_comp=$cost['cant_reci']*$cost['cost_comp'];
				$tbs= $const_comp;
				$array=array(
				'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
				'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),35,"\n"),
				'tamano'=>$campo['codi_umed']." ".$campo['medi_tama'],
				'color'=>$campo['desc_colo'],
				'cantidad'=>$cost['cant_reci'],
				'precio'=>fmt_num($cost['cost_comp']),
				'tbsf'=>fmt_num($const_comp),
				);
				$total_bs+=$tbs;
				$total_prod+=$cost['cant_reci'];
				array_push($pdf_inventario,$array);
				
			 }else
			 {
			    $const_comp=$aux_exist*$cost['cost_comp'];
				$tbs= $const_comp;
				$array=array(
				'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
				'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),35,"\n"),
				'tamano'=>$campo['codi_umed']." ".$campo['medi_tama'],
				'color'=>$campo['desc_colo'],
				'cantidad'=>$aux_exist,
				'precio'=>fmt_num($cost['cost_comp']),
				'tbsf'=>fmt_num($const_comp),
				);
				
				$total_bs+=$tbs;
				$total_prod+=$aux_exist;
				array_push($pdf_inventario,$array);
				 break;
			 }
		 
		//$mysql->result->free();
		//$med=$campo['cost_comp'];/*$mysql->cost_prod($campo['id_prod'],$campo['medi_tama'],$campo['id_tama'],$campo['codi_umed'])*/;
		
	}
}
//$pdf = new MYSQL_PDF('P','mm',array(227,355.6));
$pdf = new ORVEINCA_PDF('P','mm','Legal');

$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo("INVENTARIO POR PRECIO DE COMPRA",100,'airstrike','',20);

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