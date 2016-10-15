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
   	
		
		$this->fecha_num();
		$y=$this->y;
		$this->datos_nota();
		if($y>$this->y)
		$this->y=$y;
		
		//$this->SetY(80);
		$this->Ln();
		if($this->page_thead)
		$this->TableHead();
		
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
		 //$this->msj_footer();
		 $this->SetTextColor(0, 0,0);
		 $this->SetFont('arial','',10);
		// $this->SetXY(5,220+$y);
		// $this->MultiCell(200,4,$this->text_footer,0,'J');
		 $this->SetXY(5,255+$y);
		 $this->MultiCell(200,4,__DIRECCION_FISCAL__.__CONTACTO__,0,'C');
		parent::Footer();
	}
}


	


if(empty($_GET['nume_nent']) || !((int)$_GET['nume_nent']))
{
	//exit;
}
$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$mysql->consulta("SELECT nota_entrg.*,empleados.* FROM nota_entrg LEFT JOIN empleados USING(ci_empl) WHERE nume_nent='".$_GET['nume_nent']."'");
$nota_entrega=$mysql->result();
if($mysql->result->num_rows==0)
{
	//exit;
}


$mysql->consulta(CLIENTES::CLIE,"idet_clie='".$nota_entrega['idet_clie']."'");
$cliente=$mysql->result();


$datos=
"RASON SOCIAL : ".$cliente['nomb_clie']."
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
VENDEDOR(A): ".$nota_entrega['nom1_empl']." ".$nota_entrega['ape1_empl']."
";

$fecha="Sabana de mendoza
".$nota_entrega['fech_nent'];
$n_prosupuesto="NOTA DE ENTREGA
N-".$nota_entrega['nume_nent'];

$subtotal=0;
$iva=0;
$total=0;


$header = array(

'codigo'=>array("COD",15),
'descripcion'=>array("DESCRIPCION DEL PRODUCTO",80),
'color'=>array("COLOR",24),
'medida'=>array("MEDIDA",22),
'precio_u'=>array("PREC U",20),
'cantidad'=>array("CANTIDAD",22),
'total'=>array("TOTAL",18)
);

$cotizacion=array();

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
$mysql->consulta(PRODUCTOS::NENT,"nume_nent=".$_GET['nume_nent']);
while($campo=$mysql->result())
{
	$array = array(
'codigo'=>$campo['codi_clpr'].$campo['id_prod'],
'descripcion'=>lnstring(stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] "),55,"\n"),
'color'=>$campo['desc_colo']."",
'medida'=>$campo['codi_umed']." ".$campo['medi_tama'],
'precio_u'=>fmt_num($campo['prec_vent']),
'cantidad'=>$campo['cant_nent'],
'total'=>fmt_num($campo['totalbs'])
);
$subtotal+=$campo['totalbs'];
	 array_push($cotizacion,$array);  
}
$mysql->consulta(PRODUCTOS::TOTAL_PAG_V,"nume_nent='".$_GET['nume_nent']."'",NULL,'GROUP BY nume_nent');
$pagado=$mysql->result();
if(empty($pagado['total_pag']))
$pagado['total_pag']=0;
if($pagado['total_pag']==$subtotal)
{
	$estado=true;
}else
{
	$estado=false;
}
function estado(&$pdf)
{
	global $estado;
	if($estado)
	{
		$pdf->Image('../images/pagado.png',30,50,150);
	}
}
$pdf = new NOTAS_PDF('P','mm',$style);
$pdf->fn_footer('estado');
$pdf->y_=$foot;
$pdf->SetAutoPageBreak(200,$hojas);
$pdf->AliasNbPages();

//$pdf->titulo("CLIENTES",130,'arial','',28);
$pdf->datos($datos,$fecha,$n_prosupuesto);
$pdf->firmas("CLIENTE","VENDEDOR ");
$pdf->SetLeftMargin(10);
$pdf->SetFont('arial','',10);
$pdf->AddPage();

$pdf->Table($header,$cotizacion,8,5,'arial');

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
$pdf->Output('NOTA DE ENTREGA_'.$_GET['nume_nent'].'.PDF','I');
?>