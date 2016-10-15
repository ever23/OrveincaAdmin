<?php 

require_once("../clases/config_orveinca.php");



//conectar al servidor 
$database= new PRODUCTOS();
$time= new TIME();
$html= new HTML();

$COTIZACION;
//estraer la variables
if((!empty($_GET['idet_prov'])) && empty($_GET['pdf']) )
{
	
	$idet_prov=$_GET['idet_prov'];
	
	$database->consulta(PROVEDORES::PROV,"idet_prov='".$idet_prov."'");
	$provedor=$database->result();
	
}elseif(!empty($_GET['pdf']) && !empty($_GET['idet_prov']))
{
	$ORDE_COMP=$database->insertar_orden_comp($_GET['idet_prov']);
}
else
{
	$html->__destruct();
	
}

if(empty($_GET['text']) && empty($_GET['idet_prov']) && empty($_GET['pdf']) )
{
	redirec("busqueda.php");
}


if(empty($_GET['pdf']))
{
 ?>
 <div align="center" class="conten_ico" >
 <A href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><div class="atras"></div></A>
 <a href="<?php echo $_SERVER['PHP_SELF']."?pdf=true&idet_prov=".$provedor['idet_prov']; ?>">
<div class="save" id="generate">
</div></a>
<a href="busqueda.php?desechar=true"><div class="desechar"></div></a>
</div>
  <div class="impre" id="muestra">
    <div class="logo" style="height:20px;"> </div>
    <H2 align="center">VERIFICA LOS DATOS </H2>
    <H1 align="center">DATOS DEL PROVEDOR </H1>
    <div class="info" align="center">
    <table width="560" border="0" cellspacing="1" cellpadding="0">
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
              <td>   <?php
			  $database->consulta("SELECT * FROM telefonos  WHERE id_tper='prov' and idet_pers='$provedor[idet_prov]'");
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
       
          </table>
      
    </div>
    <H1 align="center">ORDEN DE COMPRA</H1>
    <div style="min-height:500px;">
      <table width="901" border="0" cellspacing="1" cellpadding="0">
       <tr class="col_title">
          <td width="68" scope="col"  >CODIGO
            </th>
          <td  width="480"  scope="col"  >DESCRIPCION
            </td>
             <td  width="100"  scope="col"  >COLOR
            </td>
            <td  width="100"  scope="col"  >MEDIDA
            </td>
             <td  width="100"  scope="col"  >PRECIO U
            </td>
             <td  width="100"  scope="col"  >CANTIDAD
            </td>
             <td width="50" scope="col" >TOTAL
            </td>
        </tr>
        
        <?php 
		$subtotal=0;
		$database->consulta(PRODUCTOS::COTI_TMP);
		for($i=0;$campo=$database->result();$i++)
		{
			if($i%2==0)
			$row_act=' row_act';
			else
			$row_act='';
			echo " <tr class='col_hov $row_act'>
          <td>".$campo['codi_clpr'].$campo['id_prod']."
            </th>
          <td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."
		  
            </td>
             <td>".$campo['desc_colo']."
            </td>
            <td>".$campo['codi_umed']." ".$campo['medi_tama']."
            </td>
             <td>".fmt_num($campo['prec_vent'])."
            </td>
             <td>".$campo['cant_coti']."
            </td>
             <td>".fmt_num($campo['totalbs'])."
            </td>
        </tr>";
			$subtotal+=$campo['totalbs'];
		}
		
		$conf=$database->config();

$iva=$conf['iva']*$subtotal;
$total=$subtotal+$iva;
?>
      </table>
      <div style=" height:2px;"></div>
      <table width="901" border="0" cellspacing="2" cellpadding="0">
        <tr >
          <th scope="col" colspan="2">&nbsp;&nbsp;&nbsp; </th>
          <th scope="col" width="97" class="row_act" >SUB-TOTAL</th>
          <th scope="col" width="71" class="row_act">
              <?PHP  echo fmt_num($subtotal );?>
           </th>
        </tr>
        <tr>
          <th scope="col" colspan="2">&nbsp;</th>
         
          <th scope="col" width="97"  class="row_act" >+I.V.A <?php echo $conf['iva']/0.01  ?>%</th>
          <th scope="col" width="71"  class="row_act">
              <?PHP  
			echo fmt_num($iva );
			    ?>
           </th>
        </tr>
        <tr>
          <th scope="col" colspan="2">&nbsp;</th>
          <th scope="col" width="97"  class="row_act" >TOTAL</th>
          <th scope="col" width="71"  class="row_act">
              <?php echo  fmt_num($total); ?>
            </th>
        </tr>
      </table>
    </div> 
  </div>
  <!--fin de impresion--> 
</div>
<div> </div>

<?php
}elseif(!empty($_GET['pdf']) && !empty($_GET['idet_prov']))
{
	if(!$database->error())
	{
		?>
<script>
var src='<?php echo "orden_compra_pdf.php?nume_orde=".$ORDE_COMP."" ?>';
$(document).ready(function(e) {
star_load_pdf();
$('#nota_pdf').load(function(e) {
stop_load_pdf();
});
$('#carta').click(function(e) {
	e.preventDefault();
    $('#nota_pdf').attr('src',src+'&style=Letter');
	star_load_pdf();
});
$('#oficio').click(function(e) {
	e.preventDefault();
    $('#nota_pdf').attr('src',src+'&style=Legal');
	star_load_pdf();
});
});

</script>
<?PHP echo "<div align='center' id='pag_pdf'><a href='' id='carta'><div>CARTA</div></a>  <a href='' id='oficio'><div>OFICIO</div></a></div>

  <iframe width='960' height='600' id='nota_pdf' src='orden_compra_pdf.php?nume_orde=".$ORDE_COMP."'></iframe>";
	}
	
}



?>
