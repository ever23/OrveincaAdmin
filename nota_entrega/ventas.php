<?php
require_once("../clases/config_orveinca.php");
$database= new PRODUCTOS();

$html= new HTML();
$html->prettyPhoto();

?>

<script>
$(document).ready(function(e) {

	 $('#nota_entrega').load_html('ajax_ventas.php',{VENTAS:true});
	
    $('#buscar').click(function(e){
		if($('#form_searh').css('display')!='block')
		{
			$('#form_searh').fadeIn();
		}else
		{
			$('#form_searh').fadeOut();
		}
    });
	$('select[name=selct]').change(function(e) {
        var value=$(this).attr('value');
		switch(value)
		{
			case 'fech':
			 $('input[name=text]').fadeOut(function(){ $('input[name=text_date]').fadeIn();  $('select[name=vend]').fadeOut(); });
			break;
			case 'vend':
			 $('input[name=text]').fadeOut(function(){ $('input[name=text_date]').fadeOut();  $('select[name=vend]').fadeIn(); });
			break;
			default:
			 $('input[name=text_date]').fadeOut(function(){  $('input[name=text]').fadeIn(); $('select[name=vend]').fadeOut(); });
			break;
			
		}
    });
	
	$('select[name=vend]').change(function(e) {
       var	sele='vend';
		var text=$(this).attr('value');
		$('#nota_entrega').load_html('ajax_ventas.php',{VENTAS:true,'opcion':sele,'texto':text});	
	
    });	
	
	$('input[name=text]').keyup(function(e) {
        var sele=$('select[name=selct]').attr('value');
		var text=$(this).attr('value');
	 	$('#nota_entrega').load_html('ajax_ventas.php',{VENTAS:true,'opcion':sele,'texto':text});	
		
    });
	
	$('input[name=text_date]').change(function(e) {
       var	sele='fech_nent';
		var text=$(this).attr('value');
		$('#nota_entrega').load_html('ajax_ventas.php',{VENTAS:true,'opcion':sele,'texto':text});	
	
    });	
});

</script>
<style>
a { color: rgba(2,2,2,1.00); }
</style>
<div   class="conten_ico" > <a href="../orden_compra/busqueda.php">
  <div class="new2"></div>
  </a>
  <div class="buscar" id="buscar"></div>
</div>
<div align="center" >
<h1>NOTAS DE ENTREGAS</h1>
  <div id="form_searh" style="display:none;">
    <input type="search" name="text">
    <input type="date" name="text_date"  style="display:none;">
 <select name="vend" style="display:none;">
     <option value="" selected>DEPARTAMENTO DE VENTAS</option>
   <?php 
		$database->consulta("SELECT * FROM empleados where codi_carg='vend';");
		while($campo =$database->result())
		{
			
			echo " <option value='$campo[ci_empl]' 	>$campo[nom1_empl] $campo[ape1_empl] </option>";
		} 
		?>
    </select>
    <select name="selct">
      <option value="all" selected></option>
      <option value="nume_nent">NUMERO NOTA DE ENTREGA</option>
      <option value="clie">CLIENTE</option>
      <option value="vend">VENDEDOR</option>
      <option value="fech">FECHA</option>
    </select>
   
  </div>
 
</div>
<div align="center" >
  <div class="catalogo" align="center" id="muestra">
    <div class="hoja">
      <table width="900" border="0" cellspacing="1" cellpadding="1" >
        <thead >
          <tr> 
          <TD width="87">N° ENTREGA</TD>
           <TD width="88">N° FACTURA</TD>
          <td width="184">CLIENTE</td>
          <td width="137">VENDEDOR</td>
          <td width="120">MONTO</td>
          <td width="106">PAGADO</td>
          <td width="97">FECHA</td>
           <td width="56"></td>
          </tr>
        </thead>
        <tbody id="nota_entrega">
        </tbody>
      </table>
    </div>
  </div>
</div>
