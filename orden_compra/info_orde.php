<?php

require_once("../clases/config_orveinca.php");


$time= new TIME();
$html= new INFO_HTML();
$html->uipanel('#panel',4);

if(empty($_GET['nume_orde']))
$html->__destruct();
$database= new PRODUCTOS();
if($database->consulta(PRODUCTOS::ORDE_COMP,"nume_orde='".$_GET['nume_orde']."'"))
{
	  $orden_comp=$database->result();
}
?>
<style>

.atras
{
	position: absolute;
	left: 545px;
	top: -10px;
}

#iframe,#pag_pdf { display:none; }
.load_catalogo,#barra_load_pdf{ left:140px;}

</style>
<script>
$(document).ready(function(e) {

	$('#carta').click(function(e) {
		  e.preventDefault();
		
    $('#iframe').attr('src', $('#pdf_frame_info').attr('href')+'&style=Letter');
	star_load_pdf();
});
$('#oficio').click(function(e) {
	  e.preventDefault();
    $('#iframe').attr('src', $('#pdf_frame_info').attr('href')+'&style=Legal');
	star_load_pdf();
});
 $('#iframe').load(function(e) {
    $('#panel-1').css('height',$('#panel-1> div').height()+5);
});

});
</script>
<?php
if(!empty($_GET['extern']))
{
	echo "<a href='$_SERVER[HTTP_REFERER]'><div class='atras' id='atras'></div></a>";
}
?>
<div id="panel">
<ul>
  <li><a href="#panel-1">DATOS </a></li>
    <li><a href="#panel-2">ORDEN DE COMPRA</a></li>
    <li><a href="#panel-3">FACTURA DE COMPRA</a></li>
    <li><a href="#panel-4">PAGOS</a></li>
</ul>
<div id="panel-1">
<div class="produc ">
<a href="orden_compra_pdf.php?nume_orde=<?php echo $_GET['nume_orde']?>" id="pdf_frame_info">
<div class="pdf"></div></a>
<div align='center' id="pag_pdf"><a href='' id='carta'><div>CARTA</div></a>  <a href='' id='oficio'><div>OFICIO</div></a></div>
  <iframe id="iframe"></iframe>
   <div id="conten_html">
<h1>ORDEN DE COMPRA N°<?PHP echo $_GET['nume_orde']?></h1>
<?PHP

$database->consulta(PROVEDORES::PROV,"idet_prov='".$orden_comp['idet_prov']."'");
  $provedor=$database->result();
?>
<table width="560" border="0" cellpadding="0">
        <tr class="col_title">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="col_hov row_act ">
          <td>PROVEEDOR</td>
          <td><?php echo $provedor['nomb_prov']?></td>
        </tr >
        <tr class="col_hov  ">
          <td>RIF : </td>
          <td><?php echo "$provedor[codi_tide]$provedor[idet_prov]"?></td>
        </tr >
        <tr class="col_hov row_act">
          <td>EMAIL:</td>
          <td><?php echo "$provedor[emai_prov] "?></td>
        </tr >
        <tr class="col_hov  ">
          <td>TELEFONOS:</td>
          <td><?php
	if($database->consulta("SELECT * FROM telefonos  WHERE id_tper='prov' and idet_pers='$provedor[idet_prov]'"))		  
	 while($telefono=$database->result())
	{
		echo $telefono['#telf'].", ";
	} ?></td>
        </tr>
        <tr class="col_hov row_act">
          <td>DIRECCION:</td>
          <td><?php echo "$provedor[dire_prov], PARROQUIA: ".$provedor['desc_parr'].", MUNICIPIO: ".$provedor['desc_muni'].", ESTADO: ".$provedor['desc_esta']; ?></td>
        </tr>
        <tr class="col_hov ">
          <td>CONTACTO:</td>
          <td><?php echo $provedor['nom1_cont']." ".$provedor['nom2_cont'] ; ?></td>
        </tr>
         <tr class="col_hov row_act">
          <td>NUMERO DE FACTURA :</td>
          <td><?php
		 
		  if($orden_comp['esta_orde']!='C')
		  {
			   $database->consulta("SELECT * FROM fact_comp where  nume_orde='$_GET[nume_orde]'");
			if($database->result->num_rows==0)
			{
				echo "NO SE FACTURADO";
			}else
			{
				$fact=$database->result();
				echo $fact['nume_fact'];
			}  
		  }else
		  {
			  echo "CANCELADO";
		  }
		$subtotal=$orden_comp['total_bs'];

 ?></td>

    <tr class="col_hov ">
    
          <td>MONTO TOTAL:</td>
          <td> <?PHP  echo  fmt_num($subtotal);?></td>
        </tr>
        </tr>
      </table>
    
        
    
</div>
</div>
</div>
<div id="panel-2">
<div class="produc ">

  
  <h1>PRODUCTOS DE LA ORDEN DE COMPRA</h1>


<table width="560" border="0"  cellpadding="0" >
<thead>
  <tr class="col_title">
    <td>CODIGO</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
    <td>CANTIDAD</td>
   
    <td>TOTAL</td>
  </tr></thead>
  <tbody >
  
 <?php
  
  $database->consulta(PRODUCTOS::ORDE_COMP_PROD," nume_orde='$_GET[nume_orde]'");
for($i=0;$campo=$database->result();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  
	echo "<tr class='col_hov  $row_act' >
	<td>".$campo['codi_clpr'].$campo['id_prod']."</td>
	<td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."</td>
	<td>".$campo['desc_colo']."</td>
	<td>".$campo['codi_umed']." ".$campo['medi_tama']."</td>
	<td align='center'>".fmt_num($campo['cost_orde'])."</td>
	<td align='center'>".$campo['cant_orde']."</td>
	
	<td align='center'>".fmt_num($campo['totalbs'])."</td>
	</tr>";
}
  ?> </tbody>
</table>

</div>
</div>
<div id="panel-3">
<div class="produc ">

<?php

if($orden_comp['esta_orde']=='P')
{
	echo "<H2>NO SE A FACTURADO ESTA ORDEN DE COMPRA</H2>";
}else
{
	$database->consulta(PRODUCTOS::FACT_PROD," nume_orde='$_GET[nume_orde]'");
?>
<h1>PRODUCTOS DE LA FACTURA DE COMPRA</h1>
<table width="600" border="0"  cellpadding="0" >
  <tr class="col_title">
    <td>CODIGO</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
    <td>CANTIDAD</td>
      <td>RECIBIDO</td>
    <td>TOTAL</td>
  </tr>
  <?php
for($i=0;$campo=$database->result();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  
	echo "<tr class='col_hov  $row_act' >
	<td>".$campo['codi_clpr'].$campo['id_prod']."</td>
	<td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."</td>
	<td>".$campo['desc_colo']."</td>
	<td>".$campo['codi_umed']." ".$campo['medi_tama']."</td>
	<td align='center'>".fmt_num($campo['cost_comp'])."</td>
	<td align='center'>".$campo['cant_faco']."</td>
	<td align='center'>".$campo['cant_reci']."</td>
	<td align='center'>".fmt_num($campo['totalbs'])."</td>
	</tr>";
}
  ?>
</table>
<?php
}//if($database->result->num_rows==0)
?>
</div>
</div>

<div id="panel-4">
<div class="produc ">

<?php

if($orden_comp['esta_orde']=='P')
{
	echo "<H2>NO SE A FACTURADO ESTA ORDEN DE COMPRA</H2>";
}else
{
	//$database->consulta(PRODUCTOS::TOTAL_PAG_C,"nume_orde='$_GET[nume_orde]'",NULL,'GROUP BY nume_orde');
	$database->consulta("SELECT *,bancos.nomb_banc FROM pagos_fact LEFT JOIN bancos USING(id_banc) "," id_fact='$_GET[nume_orde]' and tipo_fact='C'");
	  $PAGADO=0;
	  $pagos='';
for($i=0;$campo=$database->result();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  switch($campo['modo_pago'])
		{
			case 'E':
				$modo_pago="EFECTIVO";
			 break;	
			 case 'C':
				 $modo_pago="CHEQUE";
			 break;
			 case 'D':
			 	 $modo_pago="DEPOSITO";
			 break;
		}
	$pagos.="<tr class='col_hov  $row_act' >
	<td>".$modo_pago."</td>
	<td>".$campo['idet_pago']."</td>
	<td>".$campo['nomb_banc']." </td>
	<td>".fmt_num($campo['bsf_pago'])."</td>
	<td>".$campo['fech_pago']."</td>
	</tr>";
	$PAGADO+=$campo['bsf_pago'];
}
?>
<h1>PAGOS DE FACTURA</h1>
<table width="600" border="0"  cellpadding="0" >
 <thead><tr>
    <td>MODO DE PAGO </td>
    <td>IDENTIFICACION DEL PAGO</td>
    <td>BANCO</td>
    <td>MONTO</td>
    <td>FECHA</td>
     <?php
	if(fmt_num($orden_comp['total_bs']-$PAGADO)!='0.00')
	{
		echo "<td><a href='../orden_compra/ingr_pago.php?id_fact=$_GET[nume_orde]&tipo_fact=C&amp;iframe=true&amp;width=600&amp;height=380&amp;' class='lightbox-image'  data-gal='ingr_pago[iframe]'><div class='dolar info_orde'></div></a></td>";
	}
	?>
  </tr></thead> 
  <tbody>
  <?php
	echo $pagos;
  ?></tbody>
  <tfoot>
  <TR>
  <td  colspan="3"></td>
   <td  width="97"  class="row_act" >TOTAL</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($PAGADO);
			    ?>
           </td>
  </TR>
  </tfoot>
</table>
  
<?php
}//if($database->result->num_rows==0)
?>
</div>
</div>
</div>


<div class="load_catalogo"></div>
<div id="barra_load_pdf">
  <div id="barra_load_"></div></div>