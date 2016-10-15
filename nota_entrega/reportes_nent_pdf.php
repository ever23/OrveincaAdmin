<?php
require_once("../clases/config_orveinca.php");;
$header = array(
'N° NOTA'=>array("N-NOTA",20),
'N° FACT'=>array("N-FACT",20),
'CLIENTE'=>array("CLIENTE",120),
'VENDEDOR'=>array("VENDEDOR",100),
'MONTO'=>array("MONTO",20),
'PAGADO'=>array("PAGADO",20),
'FECHA'=>array("FECHA",24)
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
$REPORTE_SALIDA=PRODUCTOS::VENTAS;

$time= new TIME();
if(!empty($_GET['fecha']))
$time->Set_date($_GET['fecha'].'-0');
switch($_GET['reporte'])
{
	
	case 'clie':
		$titulo="DE ".$time->mes_cadena()." 2015 ";
		$where_salida="fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'
		and idet_clie='".$_GET['opcion2']."'";
	break;	
	case 'vend':
		$titulo="DE ".$time->mes_cadena()." 2015 ";
		$where_salida="fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'
		and ".($_GET['opcion2']!=''?"nota_entrg.ci_empl='".$_GET['opcion2']."' ":" nota_entrg.ci_empl is NULL ");
	break;	
	default:
		$titulo="DE ".$time->mes_cadena()." 2015";
		$where_salida="fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'";
	break;
	
	
}
if(!$result=$mysql->consulta($REPORTE_SALIDA,$where_salida.'GROUP BY nota_entrg.nume_nent'))
{
		echo "ERRO AL CONSULTAR LA TABLA CLIENTES ".$mysql->error;
	
		exit();
}

$pdf_inventario_salida=array();
$i=0;
$total_prod_sali=0;
$total_bs_sali=0;
$total_pag=0;
if(!$mysql->error)
for($i=0;$campo=$result->fetch_array();$i++)
{
	 $costo='';
	 $mysql->consulta(PRODUCTOS::TOTAL_PAG_V,"nume_nent='$campo[nume_nent]'",NULL,'GROUP BY nume_nent');
    $pago=$mysql->result();
		    	if(empty($pago['total_pag']))
			$pago['total_pag']=0;
	
	$array=array(
	'N° NOTA'=>$campo['nume_nent'],
	'N° FACT'=>$campo['nume_fact'],
	'CLIENTE'=>lnstring($campo['nomb_clie'],50,"\n"),
	'VENDEDOR'=>$campo['nom1_empl']." ".$campo['nom1_empl'],
	'MONTO'=>fmt_num($campo['total_bs']),
	'PAGADO'=> fmt_num($pago['total_pag']),
	'FECHA'=>$campo['fech_nent']
	
	);
	$total_bs_sali+=$campo['total_bs'];
	$total_pag+=$pago['total_pag'];
	$pdf_inventario_salida+=array(
	$i=>$array
	);
}

//$pdf = new MYSQL_PDF('P','mm',array(227,355.6));

$pdf = new ORVEINCA_PDF('l','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');

$pdf->titulo("VENTAS ".$titulo,150,'airstrike','',28);

$pdf->AddPage();

$pdf->Table($header,$pdf_inventario_salida,10,5,'arial');


$header=[
'1'=>[" ",150],
'2'=>[" ",150],

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
'1'=>'TOTAL BOLIVARES',
'2'=>fmt_num($total_bs_sali)
]);
array_push($pdf_info,
[
'1'=>'TOTAL POR COBRAR',
'2'=>fmt_num($total_bs_sali-$total_pag)
]
);
$pdf->titulo("IFORMACION DE VENTAS ".$titulo,150,'airstrike','',28);

$pdf->AddPage();
$pdf->Table($header,$pdf_info,10,5,'arial');


$pdf->Output("VENTAS ".$titulo.'.pdf','I');


?>