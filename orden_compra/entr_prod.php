<?php

require_once("../clases/config_orveinca.php");


$time= new TIME();
$html= new INFO_HTML();

if(empty($_GET['nume_orde']))
$html->__destruct();
$database= new PRODUCTOS();
if(!empty($_POST['insertar']))
{
		$database->recibir_producto($_POST);
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
#produc{
	padding:10px;
	width: 620px;
	}
</style>
<script>
var deuda=0;
$(document).ready(function(e) {
	
	$('.recibido').keyup(function(e) {
		var value=$(this).attr('value');
        var row=$(this).attr('title');
		var cant_faco=Number($('#cant_faco'+row).html());
		var cant_reci=Number($('#cant_reci'+row).html());
		if(value>(cant_faco-cant_reci))
		{
			alert("LA CANTIDAD QUE SE RECIBIRA SUPERA A LA DE LA FACTURA DE COMPRA");
			$(this).attr('value',(cant_faco-cant_reci));
		}
    });
	$('button').click(function(e) {
		
		var cant_faco;
		var cant_reci;
		var value;
    	for(var i=0;$('#cant_faco'+i).html()!=undefined;i++)
		{
			value=$('input[title='+i+']').attr('value');
			cant_faco=Number($('#cant_faco'+i).html());
			cant_reci=Number($('#cant_reci'+i).html());
			if(value>(cant_faco-cant_reci))
			{
				e.preventDefault();
				alert("LA CANTIDAD QUE SE RECIBIRA DE UNO DE LOS PRODUCTOS SUPERA A LA DE LA FACTURA DE COMPRA");
				break;
			}
		}
		
		
    });
});
</script>
<?php
if(!empty($_GET['extern']))
{
	echo "<a href='$_SERVER[HTTP_REFERER]'><div class='atras' id='atras'></div></a>";
}
?><div class="produc " id="produc">
<?php

	$database->consulta(PRODUCTOS::FACT_PROD," nume_orde='$_GET[nume_orde]' AND cant_faco>cant_reci");

?>
<h1>RECIBIR PRODUCTO DE PROVEEDOR</h1>

<?PHP

if(empty($_POST['boton']))
{
?>
<form action="" method="post">
<input type='hidden' name='nume_orde' value='<?php echo $_GET['nume_orde']?>'>
<table width="600" border="0" cellspacing="1" cellpadding="0" >
  <tr class="col_title">
    <td>CODIGO</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
    <td>CANTIDAD</td>
     <td>RECIBIDO</td>
    <td></td>
  </tr>
  <?php
  if($database->result->num_rows==0)
  {
	  
	 echo "<tr>
	 <td colspan='9'><H2>SE HAN RECIBIDO TODOS LOS PRODUCTOS DE ESTA FACTURA DE COMPRA</H2></td>
	 </tr>"; 
  }
 
for($i=0;$campo=$database->result();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  
	echo "<input type='hidden' name='id_faco[".$i."]' value='".$campo['id_faco']."'>
	<tr class='col_hov  $row_act' >
	<td>".$campo['codi_clpr'].$campo['id_prod']."</td>
	<td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."</td>
	<td>".$campo['desc_colo']."</td>
	<td>".$campo['codi_umed']." ".$campo['medi_tama']."</td>
	<td>".fmt_num($campo['cost_comp'])."</td>
	<td align='center' id='cant_faco".$i."'>".$campo['cant_faco']."</td>
	<td align='center' id='cant_reci".$i."'>".$campo['cant_reci']."</td>
	<td>";
	if($campo['cant_faco']>$campo['cant_reci'])
	echo  "<input name='recibido[".$i."]' class='recibido' type='text' size='7' title='".$i."'>";
	echo "</td>
	</tr>";
}
  ?><tfoot>
  <tr>
  <td colspan="8" align="center"><button class="submit" type="submit" name="boton" value="true">Enviar</button></td>
  </tr>
  </tfoot>
</table>

</form>
<?php
}elseif(!empty($_POST['boton']))
{
	echo "<h2>ESTA SEGURO DE REGISTRAR EL INGRESO DE LOS SIGUENTES PRODUCTOS</h2>";
	?>
    <form action="" method="post">
<input type='hidden' name='nume_orde' value='<?php echo $_POST['nume_orde']?>'>
<table width="600" border="0" cellspacing="1" cellpadding="0" >
  <tr class="col_title">
    <td>CODIGO</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
     <td>RECIBIDO</td>
    <td></td>
  </tr>
    <?php
	foreach($_POST['id_faco'] as $i=>$id_faco)
	{
		if($_POST['recibido'][$i]<1)
		continue;
		$database->consulta(PRODUCTOS::FACT_PROD," nume_orde='".$_POST['nume_orde']."' AND cant_faco>cant_reci and id_faco='".$id_faco."'");
		$campo=$database->result();
		  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  
	echo "<input type='hidden' name='id_faco[".$i."]' value='".$id_faco."'>
	<tr class='col_hov  $row_act' >
	<td>".$campo['codi_clpr'].$campo['id_prod']."</td>
	<td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."</td>
	<td>".$campo['desc_colo']."</td>
	<td>".$campo['codi_umed']." ".$campo['medi_tama']."</td>
	<td>".fmt_num($campo['cost_comp'])."</td>
	<td align='center' id='cant_reci".$i."'>".$_POST['recibido'][$i]."
	<input name='recibido[".$i."]' class='recibido' type='hidden' value='".$_POST['recibido'][$i]."' size='7' title='".$i."'>
	</td>
	</tr>";
	}?>
    <tfoot>
  <tr>
  <td colspan="8" align="center"><button class="submit" type="submit" name="insertar" value="true">REGISTRAR</button></td>
  </tr>
  </tfoot>
</table>
</form>
    <?php
}
?>
</div>



