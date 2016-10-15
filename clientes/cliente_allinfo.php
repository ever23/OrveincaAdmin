<?php
require_once("../clases/config_orveinca.php");

$database= new CLIENTES();
$html= new INFO_HTML();
$html->uipanel('#tablepanel',3);


if(empty($_GET['idet_clie']))
$html->__destruct();


$database->consulta(CLIENTES::CLIE,"idet_clie='$_GET[idet_clie]'");
$cliente=$database->result();

if(!empty($_GET['extern']))
{
	echo "<a href='$_SERVER[HTTP_REFERER]'><div class='atras' id='atras'></div></a>";
}

?>
<style>
.atras
{
	position: absolute;
	left: 545px;
	top: -10px;
}

</style>

<div id="tablepanel">
  <ul>
    <li><a href="#tablepanel-1">CLIENTE</a></li>
    <li><a href="#tablepanel-2">CONTACTO</a></li>
    <li><a href="#tablepanel-3">CREDITO</a></li>
    
  </ul>
  <div id="tablepanel-1">    <div class="produc">
        <h1><?php echo "$cliente[nomb_clie]"; ?> </h1>
        <table width="560" border="0" cellspacing="1" cellpadding="0">
          <tr class="col_title">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="col_hov  ">
            <td>RIF : </td>
            <td><?php echo "$cliente[codi_tide]$cliente[idet_clie]"?></td>
          </tr >
          <tr class="col_hov row_act">
            <td>EMAIL:</td>
            <td><?php echo "$cliente[emai_clie] "?></td>
          </tr >
          <tr class="col_hov  ">
            <td>TELEFONOS:</td>
            <td><?php
			  $database->consulta("SELECT * FROM telefonos  WHERE id_tper='clie' and idet_pers='$cliente[idet_clie]'");
	 while($telefono=$database->result())
	{
		echo $telefono['#telf'].", ";
	} ?></td>
          </tr>
          <tr class="col_hov row_act">
            <td>DIRECCION:</td>
            <td><?php echo "$cliente[dire_clie], PARROQUIA: ".$cliente['desc_parr'].", MUNICIPIO: ".$cliente['desc_muni'].", ESTADO: ".$cliente['desc_esta']; ?></td>
          </tr>
          <tr class="col_hov ">
            <td>VENDEDOR:</td>
            <td><?php echo "<a href='../empleados/empleados_allinfo.php?ci_empl=$cliente[ci_empl]&extern=true'>
			  $cliente[nom1_empl] $cliente[nom2_empl] $cliente[ape1_empl] $cliente[ape2_empl]
			  </a>"; ?></td>
          </tr>
        </table>
      </div></div>
  <div id="tablepanel-2"> <div class="produc">
        <h1><?php echo "$cliente[nom1_cont] $cliente[nom2_cont] $cliente[ape1_cont] $cliente[ape2_cont]"; ?> </h1>
        <table width="560" border="0" cellspacing="1" cellpadding="0">
          <tr class="col_title">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        
          <tr class="col_hov row_act">
            <td>EMAIL:</td>
            <td><?php echo $cliente['emai_cont']?></td>
          </tr>
          <tr class="col_hov  ">
            <td>TELEFONOS:</td>
            <td><?php
			  $database->consulta("SELECT * FROM telefonos  WHERE id_tper='cont' and idet_pers='$cliente[ci_cont]'");
			 
	 while($telefono=$database->result())
	{
		echo $telefono['#telf']."  , ";
	} ?></td>
          </tr>
          
        </table>
      </div></div>
 <div id="tablepanel-3"> <div class="produc">
        <h1>NOTAS DE ENTREGA POR COBRAR </h1>
        <table width="600" border="0" cellspacing="1" cellpadding="0" >
 <thead><tr>
    <td>N° NOTA</td>
    <td>N° FACT</td>
    <td>VENDEDOR</td>
    <td>MONTO</td>
    <td>PAGADO</td>
    <td>ADEUDA</td>
    <td>FECHA</td>
    
  </tr></thead> 
  <tbody>
  <?php
  $resul=$database->consulta(PRODUCTOS::VENTAS,"idet_clie='".$cliente['idet_clie']."'".' GROUP BY nota_entrg.nume_nent','nume_nent DESC');
  $tota_bs=$total_pag=0;
for($i=0;$campo=$resul->fetch_array();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  $database->consulta(PRODUCTOS::TOTAL_PAG_V,"nume_nent='$campo[nume_nent]'",NULL,'GROUP BY nume_nent');
   $pago=$database->result();
    	if(empty($pago['total_pag']))
			$pago['total_pag']=0;
   if(($campo['total_bs']-$pago['total_pag'])!=0)
   {
		echo "<tr class='col_hov  $row_act' >
		<td><a href='../nota_entrega/info_venta.php?nume_nent=".$campo['nume_nent']."'>".$campo['nume_nent']."</a></td>
		<td>".$campo['nume_fact']."</td>
		<td><a href='../empleados/empleados_allinfo.php?ci_empl=".$campo['ci_empl']."'>".$campo['nom1_empl']." ".$campo['ape1_empl']."</a> </td>
		<td>".fmt_num($campo['total_bs'])."</td>
		<td>".fmt_num($pago['total_pag'])."</td>
		<td>".fmt_num($campo['total_bs']-$pago['total_pag'])."</td>
		<td>".$campo['fech_nent']."</td>
		</tr>";
		$tota_bs+=$campo['total_bs'];
		$total_pag+=$pago['total_pag'];
   }
}
  ?></tbody>
  <tfoot>
  <tr>
    <td  colspan="6">&nbsp;</td>
  </tr>
  <TR>
 
  <td  colspan="5"> </td>
   <td  width="97"  class="row_act" >TOTAL</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($tota_bs );
			    ?>
           </td>
  </TR>
    <TR>
  <td  colspan="5"></td>
   <td  width="97"  class="row_act" >ADEUDA</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($tota_bs-$total_pag);
			    ?>
           </td>
  </TR>
  </tfoot>
</table>
      </div></div>
      
</div>


