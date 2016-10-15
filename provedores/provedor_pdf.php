<?php
require_once("../clases/config_orveinca.php");
$mysql= new PROVEDORES();
OrveincaExeption::DieExeptionS();
if(!$result=$mysql->consulta(PROVEDORES::PROV))
{
    echo "ERRO AL CONSULTAR LA TABLA PROVEDORES";
    exit();
}
$Provedor= new TablePdfIterator(7,[201,199,190],'arial',12);
$Provedor->AddCollHead('descripcion',"RASON SOCIAL",100) 
->AddCollHead('t_ident_i',"RIF",25)
->AddCollHead('direccion',"DIRECCION",120)
->AddCollHead('telefono',"TELEFONO",26)
->AddCollHead('contacto',"CONTACTO",30);


$pdf = new ORVEINCA_PDF('l','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo("PROVEDORES",130,'airstrike','',28);
$pdf->AddPage();


while($campo=$result->fetch_array())
{

    $telfonos='';
    $mysql->consulta("SELECT * FROM telefonos  WHERE id_tper='prov' and idet_pers='$campo[idet_prov]'");
    while($telefono=$mysql->result())
    {
        $telfonos.=$telefono["#telf"]." ";
    }
	$Provedor->AddRow(5,'arial',10,false);
	$Provedor->AddCell('descripcion',$campo['nomb_prov'])
	->AddCell('t_ident_i',$campo['codi_tide'].$campo['idet_prov'])
	->AddCell('direccion',$campo['dire_prov'].", PARROQUIA: ".$campo['desc_parr'].", MUNICIPIO: ".$campo['desc_muni'].", ESTADO: ".$campo['desc_esta'])
	->AddCell('telefono',$telfonos)
	->AddCell('contacto',$campo['nom1_cont']." ".$campo['ape2_cont']);
}
$pdf->Table($Provedor);

$pdf->Output();


?>