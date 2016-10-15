<?php

require_once("../clases/config_orveinca.php");

$header = array(
'codigo'=>array("CODIGO",20),
'descripcion'=>array("DESCRIPCION",90),
'color'=>array("COLOR",25),
'tamano'=>array("TAMANO",18),
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
$REPORTE_SALIDA="SELECT nota_entrg.*,`nent_prod`.*,producto.*,tamanos.*,modelos.*,marcas.*,colores.*,SUM(nent_prod.prec_vent*nent_prod.cant_nent)
as total_bs ,SUM(nent_prod.cant_nent) AS cantidad
 FROM  nent_prod
INNER JOIN producto USING(id_prod)
LEFT JOIN `modelos` USING(`id_mode`) 
LEFT JOIN `marcas` USING(`id_marc`)
LEFT JOIN tamanos USING(id_tama)
LEFT JOIN colores on (colores.exad=nent_prod.exad_colo)
LEFT JOIN nota_entrg USING(nume_nent)";

$time= new TIME();
$time->Set_date($_GET['fecha'].'-0');
switch($_GET['reporte'])
{
	
	case 'clie':
		$titulo="DE ".$time->mes_cadena()." ".$time->ano;
		$where_salida="fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'
		and idet_clie='".$_GET['opcion2']."'";
	break;	
	case 'vend':
		$titulo="DE ".$time->mes_cadena()." 2015 ";
		$where_salida="fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'
		and ".($_GET['opcion2']!=''?"ci_empl='".$_GET['opcion2']."'":"ci_empl is NULL");
	break;	
	default:
		$titulo="DE ".$time->mes_cadena()." 2015";
		$where_salida="fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'";
	break;
	
	
}
if(!$mysql->consulta($REPORTE_SALIDA,$where_salida." GROUP by `nent_prod`.id_prod,`nent_prod`.exad_colo,`nent_prod`.id_tama "))
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

$pdf->titulo("PRODUCTOS VENDIDOS ".$titulo,100,'airstrike','',20);

$pdf->AddPage();
$pdf->Table($header,$pdf_inventario_salida,10,5,'arial');
$header=[
'1'=>[" ",100],
'2'=>[" ",100],

];
$pdf_info=[];
if($_GET['reporte']=='clie')
{
	$mysql->consulta("SELECT * FROM clientes WHERE idet_clie='".$_GET['opcion2']."'");
	$clie=$mysql->result();
	array_push($pdf_info,['1'=>'VENTAS AL CLIENTE ','2'=>$clie['codi_tide'].$clie['idet_clie']." ".$clie['nomb_clie']]);
}elseif($_GET['reporte']=='vend')
{
	$mysql->consulta("SELECT * FROM empleados WHERE ci_empl='".$_GET['opcion2']."'");
	$empl=$mysql->result();
	array_push($pdf_info,['1'=>'VENDEDOR ','2'=>"ci: ".$empl['ci_empl']." ".$empl['nom1_empl']." ".$empl['ape1_empl']]);
}
array_push($pdf_info,
[
'1'=>'TOTAL PRODUCTOS VENDIDOS ',
'2'=>fmt_num($total_prod_sali)
]);
array_push($pdf_info,
[
'1'=>'TOTAL DE BOLIVARES VENDIDOS',
'2'=>fmt_num($total_bs_sali)
]
);
$pdf->titulo("IFORMACION DE VENTAS".$titulo,100,'airstrike','',20);
$pdf->AddPage();
$pdf->Table($header,$pdf_info,10,5,'arial');
$pdf->Output("PRODUCTOS VENDIDOS ".$titulo.'.pdf','I');


?>