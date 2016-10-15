<?PHP
require_once("../clases/config_orveinca.php");
$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$config=$mysql->config();
$Lprecios= new TablePdfIterator(8,[201,199,190],'arial',10);
$Lprecios->AddCollHead('codigo','COD',18,'C')
->AddCollHead('descripcion','DESCRIPCION',120,'')/*
->AddCollHead('precio1','PRECIO1',20,'C')
->AddCollHead('precio2','PRECIO2',20,'C')*/
->AddCollHead('precio3','PRECIO',20,'C');
//$mysql->set_charset('utf8');
$style='Letter';
if(!empty($_GET['style']))
{
	$style=$_GET['style'];
}
$pdf = new ORVEINCA_PDF('P','mm',$style);
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo('',100,'airstrike','',28);
$mysql->consulta("SELECT * FROM `clas_prod`");
$pdf_producto=array();
$clpr=$mysql->consulta("SELECT * FROM `clas_prod`");

for($ide_pro=0;$pro=$clpr->fetch_array();$ide_pro++)
{
	$Lprecios->IniBody();
	$mysql->consulta(PRODUCTOS::PROD_ALL,"clas_prod.codi_clpr='$pro[codi_clpr]'");
	$pdf_producto=array();
	$pdf->add_pagina($pro['desc_clpr']);
	$i=0;
	while($campo=$mysql->result())
	{

		if($campo['cost_tama']<=0)
			continue;

		if($campo['medi_tama1']!='-' && $campo['medi_tama1']!='')
		{
			$tamano=$campo['codi_umed']." ";
			$tamano.= $campo['medi_tama1'];
			if($campo['medi_tama2']!='-')
				$tamano.=  " A $campo[medi_tama2]";

		}else
		
			$tamano='';
			$Lprecios->AddRow(5,'arial',10,false);
			$Lprecios->AddCell('codigo',$campo['codi_clpr'].$campo['id_prod']);
			$Lprecios->AddCell('descripcion',$campo['desc_prod']." ".$campo['desc_mode']." ".$campo['desc_marc']." ".ucwords($tamano));
			//$Lprecios->AddCell('precio1',fmt_num(($config['precio1']*$campo['cost_tama'])+$campo['cost_tama']));
			//$Lprecios->AddCell('precio2',fmt_num(($config['precio2']*$campo['cost_tama'])+$campo['cost_tama']));
			$Lprecios->AddCell('precio3',fmt_num(($config['precio3']*$campo['cost_tama'])+$campo['cost_tama']));
	}

	$pdf->Table($Lprecios);
}


$pdf->Output('LISTA_DE_PRECIOS.pdf','I');

?>