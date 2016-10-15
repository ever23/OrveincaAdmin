<?php

require_once("../clases/config_orveinca.php");
$mysql= new CLIENTES();
OrveincaExeption::DieExeptionS();
if(empty($_GET['reporte']))
{
	$sql=NULL;
	
}else
{
	$Mas_info= new TablePdfIterator(6,[201,199,190],'arial',10);
	$Mas_info->AddCollHead('a',' ',100)
	->AddCollHead('b',' ',200);
	switch($_GET['reporte'])
	{
		case 'vend':
			$sql=" ci_empl='$_GET[opcion2]'";
			$mysql->consulta("SELECT * FROM empleados WHERE ".$sql);
			$empl=$mysql->result();
			$Mas_info->AddRow(5,'arial',10,false)
			->AddCell('a','VENDEDOR')
			->AddCell('b','ci: '.$empl['ci_empl'].' '.$empl['nom1_empl'].' '.$empl['nom2_empl']);
			;
		break;
		case 'cont':
			$sql=" ci_cont='$_GET[opcion2]'";
			$mysql->consulta("SELECT * FROM contactos WHERE ".$sql);
			$empl=$mysql->result();
			$Mas_info->AddRow(5,'arial',10,false)
			->AddCell('a','CONTACTO')
			->AddCell('b','ci: '.$empl['ci_cont'].' '.$empl['nom1_cont'].' '.$empl['nom2_cont']);
			
		break;
		case 'esta':
			$sql=" id_esta='$_GET[opcion2]'";
			$mysql->consulta("SELECT * FROM estados WHERE id_esta='$_GET[opcion2]'");
			$empl=$mysql->result();
			$Mas_info->AddRow(5,'arial',10,false)
			->AddCell('a','ESTADO')
			->AddCell('b',$empl['desc_esta'].' ');
		break;
		case 'muni':
			$sql=" municipios.id_muni='$_GET[opcion2]'";
			$mysql->consulta("SELECT estados.*,municipios.* FROM municipios left join estados using(id_esta) WHERE municipios.id_muni='$_GET[opcion2]'");
			$empl=$mysql->result();
				$Mas_info->AddRow(5,'arial',10,false)
			->AddCell('a','DIRECCION')
			->AddCell('b',' ESTADO :'.$empl['desc_esta'].' MINICIPIO: '.$empl['desc_muni']);
			
		break;
		case 'parr':
			$sql=" clientes.id_parr='$_GET[opcion2]'";
			$mysql->consulta("SELECT estados.*,municipios.*,parroquias.* FROM parroquias left join municipios using(id_muni) left join estados using(id_esta)  WHERE parroquias.id_parr='$_GET[opcion2]'");
		
			$empl=$mysql->result();
			$Mas_info->AddRow(5,'arial',10,false)
			->AddCell('a','DIRECCION')
			->AddCell('b',' ESTADO :'.$empl['desc_esta'].' MINICIPIO: '.$empl['desc_muni'].' PARROQUIA : '.$empl['desc_parr']);

		break;
		default:
		$sql=NULL;
		
	}
	
}
if(!$result=$mysql->consulta(CLIENTES::CLIE,$sql))
{
	echo "ERRO AL CONSULTAR LA TABLA CLIENTES ".$mysql->error();
	exit();
}
$Clientes= new TablePdfIterator(8,[201,199,190],'arial',12);
$Clientes->AddCollHead('descripcion','RASON SOCIAL',100)
->AddCollHead('t_ident_i','RIF',27)
->AddCollHead('direccion','DIRECCION',120)
->AddCollHead('telefono','TELEFONOS',26)
->AddCollHead('contacto','CONTACTO',50);

$pdf = new ORVEINCA_PDF('l','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');

$pdf->titulo("CLIENTES",130,'airstrike','',28);

$pdf->SetFont('arial','',10);

$pdf->AddPage();

while($campo=$result->fetch_array())
{
	$telfonos='';
	 $mysql->consulta("SELECT * FROM telefonos  WHERE id_tper='clie' and idet_pers='$campo[idet_clie]'");
	 while($telefono=$mysql->result())
	{
		$telfonos.=$telefono['#telf']." ";
	}
	$Clientes->AddRow(5,'arial',10,false);
	$Clientes->AddCell('descripcion',$campo['nomb_clie'])
	->AddCell('t_ident_i',$campo['codi_tide'].$campo['idet_clie'])
	->AddCell('direccion',$campo['dire_clie'].", PARROQUIA: ".$campo['desc_parr'].", MUNICIPIO: ".$campo['desc_muni'].", ESTADO: ".$campo['desc_esta'])
	->AddCell('telefono',$telfonos)
	->AddCell('contacto',$campo['nom1_cont']." ".$campo['ape1_cont']);
	
}
$pdf->Table($Clientes);


if(!empty($_GET['reporte']))
{
	$pdf->titulo(" ",130,'airstrike','',28);
	$pdf->AddPage();
	$pdf->Table($Mas_info);
	
}
$pdf->Output();


?>