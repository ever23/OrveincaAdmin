<?php
require_once("../clases/config_orveinca.php");

$database= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$header=array(
'codigo'=>array('CODIGO',20,'C'),
'descripcion'=>array('DESCRIPCION',80),
'color'=>array("COLOR",24),
'medida'=>array("MEDIDA",18),
'precio_u'=>array("PREC U",20,'C'),
'Image-1'=>array('IMAGEN',30,'','img')

);

$img=__AUTORIZATE_DIRNAME__."/mysql/img.php";
$pdf= new ORVEINCA_PDF('P','mm',"Letter");
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo('',100,'airstrike','',28);
$clpr=$database->consulta("SELECT * FROM `clas_prod`");
$pdf_producto=array();
$pdf->add_pagina("COTIZACION N-".$_GET['nume_coti']);
if(!empty($_GET['nume_coti']))
{
	$pro=$database->consulta(PRODUCTOS::COTI," nume_coti='$_GET[nume_coti]'");
	while($campo=$pro->fetch_array())
	{
		
		$database->consulta("SELECT * FROM imagenes WHERE id_imag='".$campo['id_imag']."'");
		$img=$database->result();
		 $array=array( 
			'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
			'descripcion'=>stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),
			'color'=>$campo['desc_colo'],
			'medida'=>$campo['codi_umed']." ".$campo['medi_tama'],
			'precio_u'=>fmt_num($campo['prec_vent']),
			'Image-1'=>['dir'=>__AUTORIZATE_DIRNAME__."/mysql/img.php?id=".$campo['id_imag'],'ext'=>'png']
		   );
			 array_push($pdf_producto,$array);  
	}
$pdf->SetFont('arial','',8);
$pdf->Table($header,$pdf_producto,10,20);
}


$pdf->Output("catalogo_pdf.pdf",'I');
?>