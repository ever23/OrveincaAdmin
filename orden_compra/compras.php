<?php
require_once("../clases/config_orveinca.php");

$html= new HTML();
$html->prettyPhoto();
$html->addlink_js('../src/js/compras.min.js');

?>
<style>
a { color: rgba(2,2,2,1.00); }
</style>
<script>
$(document).ready(function(e) {
    
	<?php if(!empty($_GET['nume_orde']))
	{?>
	 $('#factura_compras').load_html('ajax_compras.php',{FACTURAS_COMPRAS:true,'opcion':'nume_orde','texto':'<?php echo $_GET['nume_orde'] ?>'});	
	 <?php }?>
});
</script>
<div   class="conten_ico" > <a href="../orden_compra/busqueda.php">
  <div class="new2"></div>
  </a>
  <div class="buscar" id="buscar"></div>
</div>
<div align="center" >
  <div id="form_searh" style="display:none;">
    <input type="search" name="text">
    <input type="date" name="text_date"  style="display:none;">
    <select name="estado"  style="display:none;">
      <option value=""></option>
      <option value="=0">RECIBIDO</option>
      <option value="!=0">PENDIENTE</option>
    </select>
    <select name="selct">
      <option value="all" selected></option>
      <option value="nume_orde">NUMERO DE ORDEN DE COMPRA</option>
      <option value="nume_fact">NUMERO DE FACTURA DE COMPRA</option>
      <option value="prov">PROVEEDOR</option>
      <option value="fech">FECHA</option>
      <option value="estado">ESTADO DE LOS PRODUCTOS</option>
    </select>
  </div>
  <?php
echo $html->menu(array('FACTURAS DE COMPRAS'=>'fact_comp','ORDENES POR FACTURAR'=>'orde_comp'),'FACTURAS DE COMPRAS');
?>
</div>
<div align="center" >
  <div class="catalogo" align="center" id="muestra">
    <div class="hoja">
      <table width="900" border="0" cellspacing="1" cellpadding="1" >
        <thead id="thead-fact">
          <tr> </tr>
        </thead>
        <tbody id="factura_compras">
        </tbody>
      </table>
    </div>
  </div>
</div>
