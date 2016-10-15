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
		$this->SetXY(130,40);
		$this->MultiCell(50,5,"N.I.L: ".__NILL__,1,'C');
		$this->SetXY(130,50);
		$this->MultiCell(50,4,$this->fecha,1,'C');
		$this->SetXY(130,65);
		$this->MultiCell(50,4,$this->cotizacion,1,'C');

	}
	public function msj_footer()
	{
		$this->SetFont('arial','I',14);

		$this->SetTextColor(255, 0,0);
		$this->SetTextColor(255, 0,0);
		$this->SetXY(5,210+$this->y_);
		$this->MultiCell(200,4,__SLOGAN__,0,'C');

	}

	public function Header()
	{

		// Logo
		$this->Image(__LOGO_LARGO_ORVEINCA__,10,10,180,25,'png'); 
		// Arial bold 15

		// Movernos a la derecha

		//$this->Cell(50);
		// Título
		$this->fecha_num();
		$y=$this->y;
		$this->datos_nota();
		if($y>$this->y)
			$this->y=$y;


		$this->Ln();
		if($this->page_thead)
			$this->TableHead();

	}
	public function Footer()
	{

		$y=$this->y_;
		$this->SetFont('arial','',10);

		$this->Line(15,200+$y,70,200+$y);
		$this->Line(140,200+$y,200,200+$y);
		$this->SetXY(15,202+$y);
		$this->MultiCell(50,5,$this->firma1,0,'C');
		$this->SetXY(145,202+$y);
		$this->MultiCell(50,5,$this->firma2,0,'C');
		$this->msj_footer();
		$this->SetTextColor(0, 0,0);
		$this->SetFont('arial','',10);
		$this->SetXY(5,220+$y);
		$this->MultiCell(200,4,__PDF_MSJ_FOOTHER__,0,'J');
		$this->SetXY(5,255+$y);
		$this->MultiCell(200,4,__DIRECCION_FISCAL__.__CONTACTO__,0,'C');
		parent::Footer();
	}
}
if(empty($_GET['nume_pedi']))
{
	$pdf = new ORVEINCA_PDF('P','mm','Legal');
	//$pdf->SetAutoPageBreak(200,120);
	$pdf->AddPage();
	$pdf->Output();
	exit();
}
$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$mysql->consulta("SELECT pedidos.*,empleados.* FROM pedidos LEFT JOIN empleados USING(ci_empl) WHERE nume_pedi=".$_GET['nume_pedi']."");
$pedido=$mysql->result();
$mysql->consulta(CLIENTES::CLIE,"idet_clie='".$pedido['idet_clie']."'");
$cliente=$mysql->result();
$datos="RASON SOCIAL : ".$cliente['nomb_clie']."
RIF: ".$cliente['codi_tide'].$cliente['idet_clie']."
DIRECCION :".$cliente['dire_clie'].", PARROQUIA: ".$cliente['desc_parr'].", MUNICIPIO: ".$cliente['desc_muni'].", ESTADO: ".$cliente['desc_esta']." 
TELEFONO(S): ";
$mysql->consulta('SELECT * FROM telefonos',"id_tper='clie' and idet_pers='".$cliente['idet_clie']."'");
while($tel=$mysql->result())
{
	$datos.= $tel['#telf'].",";
}
$datos.="
CONTACTO:  ".$cliente['nom1_cont']." ".$cliente['nom2_cont']."  
VENDEDOR(A): ".$pedido['nom1_empl']." ".$pedido['ape1_empl']."
";

$fecha="Sabana de mendoza
".$pedido['fech_pedi'];
$n_prosupuesto="Pedido
N ".$pedido['nume_pedi'];

$subtotal=0;
$iva=0;
$total=0;


$header = array(
	'codigo'=>array("COD",17),
	'descripcion'=>array("DESCRIPCION DEL PRODUCTO",75),
	'color'=>array("COLOR",22),
	'medida'=>array("MEDIDA",22),
	'precio_u'=>array("PREC U",20),
	'cantidad'=>array("CANTIDAD",22),
	'total'=>array("TOTAL",22)
);
$estado=$pedido['esta_pedi'];
$pedido=array();

$style='Letter';
$foot=0;
$hojas=100;

if(!empty($_GET['style']))
{
	$style=$_GET['style'];
	if($style=='Letter')
	{
		$foot=0;

	}
	elseif($style=='Legal')
	{
		$foot=70;

	}
}
function estado(&$pdf)
{
	global $estado;
	switch($estado)
	{
		case 'E':$pdf->Image('../images/entregado.png',30,50,150);break;
		case 'C':$pdf->Image('../images/cancelado.png',30,50,150);break;
	}
}

$pdf = new NOTAS_PDF('P','mm',$style);
$pdf->y_=$foot;
$pdf->SetAutoPageBreak(200,$hojas);
$pdf->AliasNbPages();
//$pdf->titulo("CLIENTES",130,'arial','',28);

$pdf->fn_footer('estado');
$pdf->datos($datos,$fecha,$n_prosupuesto);
$pdf->firmas("CLIENTE","VENDEDOR");
$pdf->SetLeftMargin(10);
$pdf->SetFont('arial','',10);
$pdf->AddPage();
$mysql->consulta(PRODUCTOS::PEDI,"nume_pedi=".$_GET['nume_pedi']);
while($campo=$mysql->result())
{
	$array = array(
		'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
		'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),55,"\n"),
		'color'=>$campo['desc_colo'],
		'medida'=>$campo['codi_umed']." ".$campo['medi_tama'],
		'precio_u'=>fmt_num($campo['prec_vent']),
		'cantidad'=>$campo['cant_pedi'],
		'total'=>fmt_num($campo['totalbs'])
	);
	$subtotal+=$campo['totalbs'];
	array_push($pedido,$array);  
}


$pdf->Table($header,$pedido,10,5,'arial');

$conf=$mysql->config();
//print_r($conf);
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
$pdf->Output('PEDIDO_'.$_GET['nume_pedi'].'.PDF','I');
?>