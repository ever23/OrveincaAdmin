<?php 
require_once("../clases/config_orveinca.php");

//conectar al servidor 
$database= new PRODUCTOS();
$time= new TIME();
$html= new HTML();
$COTIZACION;
//estraer la variables
if((!empty($_GET['idet_clie'])) && empty($_GET['pdf']) )
{
	$idet_clie=$_GET['idet_clie'];
	$database->consulta(CLIENTES::CLIE,"idet_clie='".$idet_clie."'");
	$cliente=$database->result();
	
}elseif(!empty($_GET['pdf']) && !empty($_GET['idet_clie']))
{
	$COTIZACION=$database->insertar_cotizacion($_GET['idet_clie'],$_GET['ci_empl']);
}
else
{
	
	$html->__destruct();
	
}

if(empty($_GET['text']) && empty($_GET['idet_clie']) && empty($_GET['pdf']) )
{
	redirec("busqueda.php");
}


if(empty($_GET['pdf']))
{
 ?>
 <div align="center" class="conten_ico" >
 <A href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><div class="atras"></div></A>
 <a href="<?php echo $_SERVER['PHP_SELF']."?pdf=true&idet_clie=".$cliente['idet_clie']; ?>" id="generate_nota">
<div class="save" id="generate">
</div></a>
<a href="busqueda.php?desechar=true"><div class="desechar"></div></a>
</div>
  <div class="impre" id="muestra">
    <div class="logo" style="height:20px;"> </div>
    <H2 align="center">VERIFICA LOS DATOS </H2>
    <H1 align="center">DATOS DEL CLIENTE </H1>
    <div class="info" align="center">
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
              <td><select name="ci_empl">
              <option value="null"> departamento de ventas</option>
             <?php 
		$database->consulta("SELECT * FROM empleados where codi_carg='vend';");
		while($campo =$database->result())
		{
			$select='';
			if($cliente['ci_empl']==$campo['ci_empl'])
			$select="selected";
			echo " <option value='$campo[ci_empl]' 	$select>$campo[nom1_empl] $campo[ape1_empl] </option>";
		} 
		?>
              </select></td>
            </tr>
          </table>
      
    </div>
    <H1 align="center">COTIZACION</H1>
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
$total=$subtotal+$conf['iva'];

?>
      </table>
      <div style=" height:2px;"></div>
      <table width="901" border="0" cellspacing="2" cellpadding="0">
        <tr >
          <th scope="col" colspan="2">&nbsp;&nbsp;&nbsp; </th>
          <th scope="col" width="97" class="row_act" >SUB-TOTAL</th>
          <th scope="col" width="71" class="row_act">
              <?PHP  echo  fmt_num($subtotal);?>
           </th>
        </tr>
        <tr>
          <th scope="col" colspan="2">&nbsp;</th>
         
          <th scope="col" width="97"  class="row_act" >+I.V.A <?php echo $conf['iva']/0.01 ?>%</th>
          <th scope="col" width="71"  class="row_act">
              <?PHP  
			echo fmt_num( $iva);
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
}elseif(!empty($_GET['pdf']) && !empty($_GET['idet_clie']))
{
	if(!$database->error())
	{
			?>
<script>
var src='<?php echo "cotizacion_pdf.php?nume_coti=".$COTIZACION ?>';
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
$('.coti').click(function(e) {
    e.preventDefault();
	$('#nota_pdf').attr('src',$(this).attr('href'));
	star_load_pdf();
});
});

</script>
<a href="cotizacion_pdf.php?nume_coti=<?php echo $COTIZACION?>" class="coti"><div class="pdf"></div></a><a href="cotizacion_image_pdf.php?nume_coti=<?php echo $COTIZACION?>" class="coti"><div class="b_catalogo"></div></a>
<?PHP echo "<div align='center' id='pag_pdf'><a href='' id='carta'><div>CARTA</div></a>  <a href='' id='oficio'><div>OFICIO</div></a></div>
  <iframe width='960' height='600' id='nota_pdf' src='cotizacion_pdf.php?nume_coti=".$COTIZACION."'></iframe>";

	}
	
}


?>
