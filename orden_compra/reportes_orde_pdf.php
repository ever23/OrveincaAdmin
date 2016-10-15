<?php

require_once("../clases/config_orveinca.php");

$header = array(
'N° ORDE'=>array("N° ORDE",20),
'N° FACT'=>array("N° FACT",20),
'PROVEEDOR'=>array("PROVEEDOR",95),
'MONTO'=>array("MONTO",20),
'PAGADO'=>array("PAGADO",20),
'FECHA'=>array("FECHA",22)
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
if(!$result=$mysql->consulta(PRODUCTOS::COMPRAS,$where_entr.'GROUP BY fact_comp.nume_orde'))
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
	 $mysql->consulta(PRODUCTOS::TOTAL_PAG_C,"nume_orde='$campo[nume_orde]'",NULL,'GROUP BY nume_orde');
    $pago=$mysql->result();
		    	if(empty($pago['total_pag']))
			$pago['total_pag']=0;
	
	$array=array(
	'N° ORDE'=>$campo['nume_orde'],
	'N° FACT'=>$campo['nume_fact'],
	'PROVEEDOR'=>lnstring($campo['nomb_prov'],50,"\n"),
	'MONTO'=>fmt_num($campo['total_bs']),
	'PAGADO'=> fmt_num($pago['total_pag']),
	'FECHA'=>$campo['fech_fact']
	
	);
	$total_bs_sali+=$campo['total_bs'];
	$total_pag+=$pago['total_pag'];
	$pdf_inventario_salida+=array(
	$i=>$array
	);
}

//$pdf = new MYSQL_PDF('P','mm',array(227,355.6));

$pdf = new ORVEINCA_PDF('P','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');

$pdf->titulo("COMPRAS ".$titulo,100,'airstrike','',20);

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
'1'=>'TOTAL BOLIVARES',
'2'=>fmt_num($total_bs_sali)
]);
array_push($pdf_info,
[
'1'=>'TOTAL POR PAGAR',
'2'=>fmt_num($total_bs_sali-$total_pag)
]
);
$pdf->titulo("IFORMACION DE COMPRAS ".$titulo,100,'airstrike','',20);

$pdf->AddPage();
$pdf->Table($header,$pdf_info,10,5,'ARIAL');
$pdf->Output("COMPRAS ".$titulo.'.pdf','I');


?>