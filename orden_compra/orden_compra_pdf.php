<?php
require_once("../clases/config_orveinca.php");

class NOTAS_PDF extends ORVEINCA_PDF
{
	private $cliente;
	private $fecha;
	private $cotizacion;
	private $firma1;
	private $firma2;
	public  $y_=75;
	function firmas($firma1,$firma2)
	{

		$this->firma1=$firma1;
		$this->firma2=$firma2;
	}
	public function datos($cliente,$fecha,$cotizacion)
	{
		$this->cliente=$cliente;
		$this->fecha=$fecha;
		$this->cotizacion=$cotizacion;

	}
	public function datos_nota()
	{
		$this->SetFont('arial','',10);
		$this->SetXY(10,40);

		$this->MultiCell(100,5,$this->cliente,1,'');

	}
	public function fecha_num()
	{
		$this->SetFont('arial','',10);
		$this->SetXY(130,50);

		$this->MultiCell(50,5,$this->fecha,1,'C',FALSE);
		$this->SetXY(130,65);

		$this->MultiCell(50,5,$this->cotizacion,1,'C',FALSE);

	}
	public function Header()
	{
		// Logo

		$this->Image(__LOGO_LARGO_ORVEINCA__,10,10,180,25,'png'); 
		// Arial bold 15


		$this->fecha_num();

		$y=$this->y;
		$this->datos_nota();
		if($y>$this->y)
			$this->y=$y;

		//$this->SetY(80);
		$this->Ln();
		parent::TableFpdfHeader();
		

	}
	public function Footer()
	{

		$y=$this->y_;
		$this->SetFont('arial','',10);
		$this->Line(15,230+$y,70,230+$y);
		$this->Line(140,230+$y,200,230+$y);
		$this->SetXY(15,232+$y);
		$this->MultiCell(50,5,$this->firma1,0,'C');
		$this->SetXY(145,230+$y);
		$this->MultiCell(50,5,$this->firma2,0,'C');
		$this->SetTextColor(0, 0,0);
		$this->SetFont('arial','',10);
		$this->SetXY(5,255+$y);
		$this->MultiCell(200,4,__DIRECCION_FISCAL__.__CONTACTO__,0,'C');
		parent::Footer();
	}
}
if(empty($_GET['nume_orde']))
{

	//exit();
}

$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$mysql->consulta("SELECT * FROM orden_comp WHERE nume_orde=".$_GET['nume_orde']."",NULL);
$orden=$mysql->result();
$mysql->consulta(PROVEDORES::PROV,"idet_prov='".$orden['idet_prov']."'");
$PROVEDOR=$mysql->result();


$datos=
	"PROVEDOR : ".$PROVEDOR['nomb_prov']."
RIF: ".$PROVEDOR['codi_tide'].$PROVEDOR['idet_prov']."
DIRECCION :".$PROVEDOR['dire_prov'].", PARROQUIA: ".$PROVEDOR['desc_parr'].", MUNICIPIO: ".$PROVEDOR['desc_muni'].", ESTADO: ".$PROVEDOR['desc_esta']." 
TELEFONO(S): ";
$mysql->consulta('SELECT * FROM telefonos',"id_tper='prov' and idet_pers='".$PROVEDOR['idet_prov']."'");
while($tel=$mysql->result())
{
	$datos.= $tel['#telf'].",";
}
$datos.="
CONTACTO:  ".$PROVEDOR['nom1_cont']." ".$PROVEDOR['nom2_cont']."
";

$fecha="Sabana de mendoza
".$orden['fech_orde'];
$n_prosupuesto="ORDEN DE COMPRA
N ".$_GET['nume_orde'];

$subtotal=0;
$iva=0;
$total=0;

$estado=$orden['esta_orde'];
$header = array(
	'codigo'=>array("COD",18),
	'descripcion'=>array("DESCRIPCION DEL PRODUCTO",76),
	'color'=>array("COLOR",22),
	'medida'=>array("MEDIDA",22),
	'precio_u'=>array("PREC U",20),
	'cantidad'=>array("CANTIDAD",21),
	'total'=>array("TOTAL",20)
);

$productos=array();
$style='Letter';
$foot=1;
$hojas=70;
if(!empty($_GET['style']))
{
	$style=trim($_GET['style']);
	if($style=='Letter')
	{
		$foot=1;

	}
	elseif($style=='Legal')
	{
		$foot=70;
	}

}

$pdf = new NOTAS_PDF('P','mm',$style);
$pdf->fn_footer(function(&$pdf)
{
	global $estado;
	switch($estado)
	{
		case 'F':$pdf->Image('../images/facturado.png',30,50,150);break;
		case 'C':$pdf->Image('../images/cancelado.png',30,50,150);break;
	}
});
$pdf->y_=$foot;
$pdf->SetAutoPageBreak(200,$hojas);
$pdf->AliasNbPages();

//$pdf->titulo("CLIENTES",130,'arial','',28);
$pdf->datos($datos,$fecha,$n_prosupuesto);
$pdf->firmas("DPTO: COMPRAS ","PROVEDOR");
//$pdf->SetLeftMargin(10);
$pdf->AddPage();
$mysql->consulta(PRODUCTOS::ORDE_COMP_PROD,"nume_orde=".$_GET['nume_orde']);
while($campo=$mysql->result())
{
	$array = array(
		'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
		'descripcion'=>stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc]"),
		'color'=>$campo['desc_colo']." ",
		'medida'=>$campo['codi_umed']." ".$campo['medi_tama'],
		'precio_u'=>fmt_num($campo['cost_orde'])." ",
		'cantidad'=>$campo['cant_orde']." ",
		'total'=>fmt_num($campo['totalbs'])." "
	);
	$subtotal+=$campo['totalbs'];
	array_push($productos,$array);  
}
$pdf->Table($header,$productos,10,5,'ARIAL');



$conf=$mysql->config();

$iva=$conf['iva']*$subtotal;
$total=$subtotal+$iva;
$pdf->SetFillColor(201,199,190);
$pdf->Ln();
$pdf->SetX(163);
$pdf->Cell(24,5,'SUB-TOTAL',1,0,'',true);
$pdf->Cell(24,5,fmt_num($subtotal)." bs",1,0,false);
$pdf->Ln();
$pdf->SetX(163);
$pdf->Cell(24,5,'+I.V.A '.$conf['iva']/0.01.'%',1,0,'',true);
$pdf->Cell(24,5,fmt_num($iva)." bs",1,0,false);
$pdf->Ln();
$pdf->SetX(163);
$pdf->Cell(24,5,'TOTAL',1,0,'',true);
$pdf->Cell(24,5,fmt_num($total)." bs",1,0,false);

$pdf->Output('ORDEN DE COMPRA '.$_GET['nume_orde'].'.PDF','I');
?>