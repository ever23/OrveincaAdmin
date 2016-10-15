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

$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$titulo;


$time= new TIME();
$time->Set_date($_GET['fecha'].'-0');
switch($_GET['reporte'])
{
	
	case 'prov':
		$titulo="DE ".$time->mes_cadena()." ".$time->ano;
		$where_entr="fech_fact>='".$time->ano."-".$time->mes."-1' and fech_fact<'".$time->ano."-".($time->mes+1)."-1'
		and idet_prov='".$_GET['opcion2']."'";
	break;	
	
	default:
		$titulo="DE ".$time->mes_cadena()."  ".$time->ano;
		$where_entr="fech_fact>='".$time->ano."-".$time->mes."-1' and fech_fact<'".$time->ano."-".($time->mes+1)."-1'";
	break;
	
	
}
if(!$mysql->consulta(PRODUCTOS::REPORTE_ENTRADA,$where_entr." GROUP by `faco_prod`.id_prod,`faco_prod`.exad_colo,`faco_prod`.id_tama "))
{
		echo "ERRO AL CONSULTAR LA SALIDA ".$mysql->error;
		
		exit();
}

$pdf_inventario_salida=array();
$i=0;
$total_prod_sali=0;
$total_bs_sali=0;

if(!$mysql->error)
for($i=0;$campo=$mysql->result->fetch_array();$i++)
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
	$pdf_inventario_salida+=array(
	$i=>$array
	);
}

//$pdf = new MYSQL_PDF('P','mm',array(227,355.6));

$pdf = new ORVEINCA_PDF('P','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');

$pdf->titulo("PRODUCTOS COMPRADOS ".$titulo,100,'airstrike','',20);

$pdf->AddPage();

$pdf->Table($header,$pdf_inventario_salida,10,5,'ARIAL');


$header=[
'1'=>[" ",100],
'2'=>[" ",100],

];
$pdf_info=[];
if($_GET['reporte']=='prov')
{
	$mysql->consulta("SELECT * FROM provedores WHERE idet_prov='".$_GET['opcion2']."'");
	$clie=$mysql->result();
	array_push($pdf_info,['1'=>'COMPRAS AL PROVEEDOR ','2'=>$clie['codi_tide'].$clie['idet_prov']." ".$clie['nomb_prov']]);
}
array_push($pdf_info,
[
'1'=>'TOTAL PRODUCTOS COMPRADOS ',
'2'=>fmt_num($total_prod_sali)
]);
array_push($pdf_info,
[
'1'=>'TOTAL DE BOLIVARES EN COMPRAS',
'2'=>fmt_num($total_bs_sali)
]
);
$pdf->titulo("IFORMACION DE COMPRAS ".$titulo,100,'airstrike','',20);
$pdf->AddPage();
$pdf->Table($header,$pdf_info,10,5,'ARIAL');
$pdf->Output("PRODUCTOS COMPRADOS ".$titulo.'.pdf','I');


?>