<?php
require_once("../clases/config_orveinca.php");
class CATALOGO_PDF extends ORVEINCA_PDF
{
	public function __construct($orientation='P', $unit='mm', $size='A4')
	{

		parent::__construct($orientation, $unit, $size);
	}
	function add_pagina_catalogo($art,$img)
	{
		$this->add_pagina();
		foreach($art as $i=>$art)
		{
			$this->SetFont('FONT_ARTICULO','B',10);
			$this->SetXY($art['xy_title']['x'],$art['xy_title']['y']);
			$this->MultiCell(60,3,$art['titulo'],0);
			if($art['imagen']!='')
				$this->Image($img."?id=".$art['imagen'],$art['xy_img']['x'], $art['xy_img']['y'],0,0,'png');	
		}
	}

}

$database= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$productos=array(
	0=>array('titulo','imagen'),
	1=>array('titulo','imagen'),
	2=>array('titulo','imagen'),
	3=>array('titulo','imagen'),
	4=>array('titulo','imagen')
);
$xy=[
	['x'=>20,'y'=>50],
	['x'=>120,'y'=>50],
	['x'=>50,'y'=>130],
	['x'=>130,'y'=>130],
	['x'=>132,'y'=>205]
];
$xytitle=[
	['x'=>5,'y'=>30],
	['x'=>100,'y'=>30],
	['x'=>20,'y'=>110],
	['x'=>140,'y'=>110],
	['x'=>110,'y'=>190]
];
$img=__AUTORIZATE_DIRNAME__."mysql/img.php";
$pdf= new CATALOGO_PDF('P','mm',"Letter");
$pdf->SetCompression(true);
$pdf->AddFont('FONT_ARTICULO','B','airstrike.php');
$pdf->AddFont('font_articulo','','airstrike.php');

$pdf->add_pagina();
$pdf->Image("../images/catalogo/portada.png",0,0,$pdf->w,$pdf->h);
$pdf->Image(__LOGO_LARGO_ORVEINCA__,15,5,180,40);

$pdf->SetFont('FONT_ARTICULO','B',85);

$pdf->Text(18,125,"CATALOGO");

$pdf->SetY(250);
$pdf->SetFont('arial','',10);
$pdf->SetAutoPageBreak(0,0);
$pdf->MultiCell(200,5,__DIRECCION_FISCAL__.__CONTACTO__,0,'C',0);
$clas_p=$database->consulta("select * from clas_prod");

while($clas_prod=$clas_p->fetch_array())
{

	$pdf->Image_fondo($img."?id=".$clas_prod['id_imag']."");
	$pdf->titulo($clas_prod['desc_clpr'],90,'FONT_ARTICULO','',22);
	if(!$database->consulta(PRODUCTOS::PROD,"codi_clpr='$clas_prod[codi_clpr]' and (producto.id_imag is not null) "))
	{
		break;
	}
	$i=0;
	$aux_a=array();
	/*$pdf->AddPage();
	continue;*/
	while($campo=$database->result())
	{

		$aux_a+=array( $i=>array(
			'titulo'=>$campo['codi_clpr'].$campo['id_prod'].stripslashes(" $campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),
			'imagen'=>((int)$campo['id_imag_p']),
			'xy_img'=>$xy[$i],
			'xy_title'=>$xytitle[$i]
		));

		$i++;
		if($i>4)
		{
			$pdf->add_pagina_catalogo($aux_a,$img);
			$aux_a=array();
			$i=0;
		}

	}
	if($i<5 && $i>0) 
	{
		$pdf->add_pagina_catalogo($aux_a,$img);
		$aux_a=array();
		$i=0;	
	}
}
$pdf->Output("catalogo_pdf.pdf",'I');
?>