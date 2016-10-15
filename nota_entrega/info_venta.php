<?php
require_once("../clases/config_orveinca.php");

$html= new INFO_HTML();
$time= new TIME();
$html->uipanel('#panel',3);

if(empty($_GET['nume_nent']))
$html->__destruct();
$database= new PRODUCTOS();
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
$('#pdf_doc').click(function(e) {
        e.preventDefault();
		if($('#iframe').css('display')=='none')
		{
			var htm=$(this).attr('href');
			var h=390;
			var w=590;
			star_load_pdf();
			$('#conten_html').fadeOut('slow','',function(){ 
			$('#iframe').fadeIn();
			$('#pag_pdf').fadeIn();
			   });
			
			$('#iframe').attr('src',htm);
			$('#iframe').attr('width',w);
			$('#iframe').attr('height',h);
		}else
		{
			$('#iframe').fadeOut('slow','',function(){
			$('#conten_html').fadeIn();
			$('#pag_pdf').fadeOut(); 
			});
			$('#iframe').attr('src','');
		}
		
    });
	$('#carta').click(function(e) {
		  e.preventDefault();
		
    $('#iframe').attr('src', $('#pdf_doc').attr('href')+'&style=Letter');
	star_load_pdf();
});
$('#oficio').click(function(e) {
	  e.preventDefault();
    $('#iframe').attr('src', $('#pdf_doc').attr('href')+'&style=Legal');
	star_load_pdf();
});
 $('#iframe').load(function(e) {
    $('#panel-1').css('height',$('#panel-1> div').height()+5);
});
});
</script>


<div id="panel">
<ul>
  <li><a href="#panel-1">DATOS </a></li>
    <li><a href="#panel-2">NOTA DE ENTREGA</a></li>
    <li><a href="#panel-3">PAGOS</a></li>
</ul>
<div id="panel-1">
  <div class="produc" >
  <?php
if(!empty($_GET['extern']))
{
	echo "<a href='$_SERVER[HTTP_REFERER]'><div class='atras' id='atras'></div></a>";
}
?><a href="nota_entrega_pdf.php?nume_nent=<?php echo $_GET['nume_nent']?>" id="pdf_doc">
<div class="pdf"></div></a>
<div align='center' id="pag_pdf"><a href='' id='carta'><div>CARTA</div></a>  <a href='' id='oficio'><div>OFICIO</div></a></div>
  <iframe id="iframe"></iframe>
  <div id="conten_html">
<h1>NOTA DE ENTREGA N°<?PHP echo $_GET['nume_nent']?></h1>
<?PHP
$database->consulta(PRODUCTOS::VENTAS,"nume_nent='". $_GET['nume_nent']."'".'GROUP BY nota_entrg.nume_nent','nume_nent DESC','LIMIT 30  ');
$nota_entrega=$database->result();
if($database->consulta(CLIENTES::CLIE,"idet_clie='".$nota_entrega['idet_clie']."'"))
	$cliente=$database->result();
?>
<table width="560" border="0" cellspacing="1" cellpadding="0">
            <tr class="col_title">
              <td>&nbsp;</td>
                         <td>&nbsp;</td>
            </tr>
            <tr class="col_hov row_act ">
              <td>RASON SOCIAL </td>
              <td><?php echo $cliente['nomb_clie']?></td>
            </tr >
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
              <td>   <?php
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
              <td>CONTACTO:</td>
              <td><?php echo $cliente['nom1_cont']." ".$cliente['nom2_cont'] ; ?></td>
            </tr>
             <tr class="col_hov row_act">
              <td>VENDEDOR:</td>
              <td><?php echo  $nota_entrega['nom1_empl']." ".$nota_entrega['ape1_empl'] ?></td>
            </tr>
              <tr class="col_hov ">
          <td>MONTO TOTAL:</td>
          <td> <?PHP  echo  fmt_num($nota_entrega['total_bs']);?></td>
        </tr>
          </table>
    
        
    
</div>
  </div>
</div>
<div id="panel-2">

 <div class="produc" >
<h1>NOTA DE ENTREGA N°<?PHP echo $_GET['nume_nent']?></h1>
<table width="560" border="0" cellspacing="1" cellpadding="0" >
  <tr class="col_title">
    <td>CODI</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
    <td>CANTIDAD</td>
    <td>TOTAL</td>
  </tr>
  <?php
  $database->consulta(PRODUCTOS::NENT," nume_nent='$_GET[nume_nent]'");
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
	<td align='center'>".fmt_num($campo['prec_vent'])."</td>
	<td align='center'>".$campo['cant_nent']."</td>
	<td align='center'>".fmt_num($campo['totalbs'])."</td>
	</tr>";
}
  ?>
</table>
</div>
</div>
<div id="panel-3">
<?php

$pagos='';
$database->consulta("SELECT *,bancos.nomb_banc FROM pagos_fact LEFT JOIN bancos USING(id_banc) "," id_fact='$_GET[nume_nent]' and tipo_fact='V'");

  $PAGADO=0;
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
<table width="600" border="0" cellspacing="1" cellpadding="0" >
 <thead><tr>
    <td>MODO DE PAGO </td>
    <td>IDENTIFICACION DEL PAGO</td>
    <td>BANCO</td>
    <td>MONTO</td>
    <td>FECHA</td>
    <?php
	if($nota_entrega['total_bs']-$PAGADO!=0)
	{
		echo "<td><a href='../orden_compra/ingr_pago.php?id_fact=$nota_entrega[nume_nent]&tipo_fact=V&amp;iframe=true&amp;width=600&amp;height=380&amp;' class='lightbox-image'  data-gal='ingr_pago[iframe]'><div class='dolar info_orde'></div></a></td>";
	}
	?>
  </tr></thead> 
  <tbody>
  <?php
  echo $pagos
  ?></tbody>
  <tfoot>
  <TR>
  <td  colspan="3"></td>
   <td  width="97"  class="row_act" >TOTAL</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($PAGADO );
			    ?>
           </td>
  </TR>
  </tfoot>
</table>
  

</div>
</div>


<div class="load_catalogo"></div>
<div id="barra_load_pdf">
  <div id="barra_load_"></div></div>