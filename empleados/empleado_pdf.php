<?php
require_once("../clases/config_orveinca.php");
$mysql= new EMPLEADOS();
OrveincaExeption::DieExeptionS();
$Time= new TIME();
$MES=$Time->format('m')-1;
if(!$mysql->consulta("SELECT nomina.*,empleados.* FROM `nomina` 
LEFT JOIN empleados USING(ci_empl) where 
fech_nomi>='".$Time->ano."-".($MES)."-1' and fech_nomi<'".$Time->ano."-".($MES+1)."-1'"))
{
	echo "ERRO AL CONSULTAR LA TABLA PROVEDORES".$mysql->error();
	exit();
}
$header = array(
	'NOMBER'=>array("NOMBRES Y APELLIDOS",45,'C'),
	'CI'=>array("CI",20,'C'),
	'RIF'=>array("RIF",20,'C'),
	'smensual'=>array("SALARIO MENSUAL",20,'C'),
	'sdiario'=>array("SALARIO DIARIO",20,'C'),
	'dias_lab'=>array("DIAS LABORADOS",20,'C'),
	'cesta_tike'=>array("CESTA TIKE",23,'C'),
	'tcesta_tike'=>array("TOTAL CESTA TIKE",20,'C'),
	'total_asig'=>array("TOTAL ASIGNADO",20,'C'),
	's.o.s'=>array("S.O.S",18,'C'),
	's.p.f'=>array("S.P.F",18,'C'),
	'l.p.h'=>array("L.P.H",18,'C'),
	'total_dedu'=>array("DEDUCIONES TOTALES",20,'C'),
	'total_pag'=>array("TOTAL PAGADO",30,'C'),
	'firma'=>array("FIRMA",20,'C')
);
$nomina=array();
while($campo=$mysql->result())
{
	$sueldo_m=(((float)$campo['suel_diar'])*((float)30));
	$tcestatiek=((float)$campo['cest_tike'])*((float)$campo['dias_labo']);
	$tasig=$tcestatiek+$sueldo_m;
	$total_dedu=$campo['l_p_h']+$campo['s_o_s']+$campo['s_p_f'];
	$total=$tasig-$total_dedu;
	$array=array(
		'NOMBER'=>$campo['nom1_empl'].' '.$campo['ape1_empl'],
		'CI'=>$campo['ci_empl'],
		'RIF'=>$campo['rif_empl'],
		'smensual'=>fmt_num($sueldo_m),
		'sdiario'=>fmt_num($campo['suel_diar']),
		'dias_lab'=>$campo['dias_labo'],
		'cesta_tike'=>fmt_num($campo['cest_tike']),
		'tcesta_tike'=>fmt_num($tcestatiek),
		'total_asig'=>fmt_num($tasig),
		's.o.s'=>fmt_num($campo['s_o_s']),
		's.p.f'=>fmt_num($campo['s_p_f']),
		'l.p.h'=>fmt_num($campo['l_p_h']),
		'total_dedu'=>fmt_num($total_dedu),
		'total_pag'=>fmt_num($total),
		'firma'=>''
	);
	array_push($nomina,$array);

}

$pdf = new ORVEINCA_PDF('l','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo("NOMINA DE ".$Time->mes_cadena($MES)." ".$Time->ano,140,'airstrike','',28);
if($mysql->result->num_rows==0)
{
	$pdf->AddPage();
	$pdf->SetLeftMargin(40);
	$pdf->Write(150,"NO EXISTEN DATOS DE LA NOMINA DE ".$Time->mes_cadena($MES)." ".$Time->ano."!!!");
	$pdf->Output("nomina ".$Time->mes_cadena($MES)." ".$Time->ano.".pdf",'I');
	exit;
}
$pdf->AddPage();
$pdf->TableHead($header,5,7,'arial');
$pdf->TableBody($nomina,9,6,array(224,235,255));

$pdf->Output("nomina ".$Time->mes_cadena($MES)." ".$Time->ano.".pdf",'I');


?>