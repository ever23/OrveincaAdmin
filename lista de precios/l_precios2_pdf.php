<?php
require_once("../clases/config_orveinca.php");;
$database= new PRODUCTOS();
//OrveincaExeption::DieExeptionS();
$config=$database->config();

$Lprecios= new TablePdfIterator(5,[201,199,190],'arial',10);
$Lprecios->AddCollHead('codigo','COD',18,'C')
->AddCollHead('descripcion','DESCRIPCION',80,'')
->AddCollHead('precio1','PRECIO1',20,'C')
->AddCollHead('precio2','PRECIO2',20,'C')
->AddCollHead('precio3','PRECIO3',20,'C')
->AddCollHead('img','IMAGEN',30,'C');
$img=__AUTORIZATE_DIRNAME__."/mysql/img.php";
$pdf= new ORVEINCA_PDF('P','mm',"Letter");

$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo('',100,'airstrike','',28);
$clpr=$database->consulta("SELECT * FROM `clas_prod`");

for($ide_pro=0;$pro=$clpr->fetch_array();$ide_pro++)
{
	$Lprecios->IniBody();
	$database->consulta(PRODUCTOS::PROD_ALL,"clas_prod.codi_clpr='$pro[codi_clpr]'");
	$pdf_producto=array();
	$pdf->add_pagina($pro['desc_clpr']);
	$i=0;
	while($campo=$database->result())
	{
		if($campo['medi_tama1']!='-' && $campo['medi_tama1']!='')
		{
			$tamano=$campo['codi_umed']." ";
			$tamano.= $campo['medi_tama1'];
			if($campo['medi_tama2']!='-')
				$tamano.=  " A $campo[medi_tama2]";

		}else
			$tamano='';
			$Lprecios->AddRow(10,'arial',10,false);
			$Lprecios->AddCell('codigo',$campo['codi_clpr'].$campo['id_prod']);
			$Lprecios->AddCell('descripcion',stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ".ucwords($tamano)));
			$Lprecios->AddCell('precio1',fmt_num(($config['precio1']*$campo['cost_tama'])+$campo['cost_tama']));
			$Lprecios->AddCell('precio2',fmt_num(($config['precio2']*$campo['cost_tama'])+$campo['cost_tama']));
			$Lprecios->AddCell('precio3',fmt_num(($config['precio3']*$campo['cost_tama'])+$campo['cost_tama']));
			$Lprecios->addCellImg('img',__AUTORIZATE_DIRNAME__."/mysql/img.php?id=".$campo['id_imag_p'],'png');
		//if($campo['costo']!=0)

	}
	
	$pdf->Table($Lprecios);
}

$pdf->Output("catalogo_pdf.pdf",'I');
?>