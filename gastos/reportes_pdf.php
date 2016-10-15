<?php
require_once("../clases/config_orveinca.php");

$header = array(
'N° RECIBO'=>array("N- RECIBO",30,'C'),
'TPGA'=>array("TIPO DE GASTO",60),
'DESCRIPCION'=>array("DESCRIPCION",160),
'MONTO'=>array("MONTO",35,'C'),
'FECHA'=>array("FECHA",30,'C')
);

$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$titulo='';
$time= new TIME();
if(!empty($_GET['opcion']))
{
	if($_GET['opcion']=='mes')
	{
		$time->Set_date($_GET['value']."-01");
	$sql=" fech_pago>='".$time->ano."-".$time->mes."-1' and fech_pago<'".$time->ano."-".($time->mes+1)."-1'";
		$titulo=" DE ".$time->mes_cadena($time->mes)." ".$time->ano;
	}elseif($_GET['opcion']=='ano')
	{
		
	$sql=" fech_pago>='".$_GET['value']."-01-01' and fech_pago<'".($time->ano+1)."-01-01'";
		$titulo=' DE '.$_GET['value'];
	}
	if(!empty($_GET['codi_tpga']))
	{
		$sql.=" and codi_tpga='".$_GET['codi_tpga']."'";
	}
		
	$result=$mysql->consulta(PRODUCTOS::GATOS,$sql);
}else
{
	$titulo=' DE '.$time->ano;
	$result=$mysql->consulta(PRODUCTOS::GATOS," fech_pago>='".$time->ano."-01-01'");
}
if($mysql->error())
{
		echo "ERRO AL CONSULTAR LA TABLA CLIENTES ".OrveincaExeption::GetExeptionS();
		exit();
}

$pdf_gasto=array();
$total_bs=0;
if(!$mysql->error)
for($i=0;$campo=$result->fetch_array();$i++)
{
	$array=array(
	'N° RECIBO'=>$campo['codi_gast'],
	'TPGA'=>$campo['desc_tpga'],
	'DESCRIPCION'=>$campo['desc_gast'],
	'MONTO'=>fmt_num($campo['bsf_pago']),
	'FECHA'=>$campo['fech_pago']
	);
	$total_bs+=$campo['bsf_pago'];
	array_push($pdf_gasto,$array);
}

//$pdf = new MYSQL_PDF('P','mm',array(227,355.6));

$pdf = new ORVEINCA_PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo("GATOS ".$titulo,140,'airstrike','',30);
$pdf->AddPage();
$pdf->Table($header,$pdf_gasto,12,7,'ARIAL');
$header=[
'1'=>[" ",100],
'2'=>[" ",100],

];
$pdf_info=[];
array_push($pdf_info,
[
'1'=>'TOTAL BOLIVARES',
'2'=>fmt_num($total_bs)
]);
$pdf->titulo("IFORMACION DE GASTO ".$titulo,150,'airstrike','',35);
$pdf->AddPage();
$pdf->Table($header,$pdf_info,10,5,'ARIAL');
$pdf->Output("GATOS ".$titulo.'.pdf','I');


?>