<?php

require_once("../clases/config_orveinca.php");
$time= new TIME();
$html= new INFO_HTML();
if(empty($_GET['id_fact']) && empty($_GET['tipo_fact']))
$html->__destruct();
$database= new PRODUCTOS();
if($_POST)
{
		if($database->pagar_factura($_POST))
		{
			if($_POST['tipo_fact']=='V')
			{
				redirec("../nota_entrega/info_venta.php?nume_nent=$_POST[id_fact]");
			}elseif($_POST['tipo_fact']=='C')
			{
				redirec("info_orde.php?nume_orde=$_POST[id_fact]");
			}
		}
}
?>
<style>
.atras
{
	position: absolute;
	left: 545px;
	top: -10px;
}

#iframe, #pag_pdf { display: none; }

.load_catalogo, #barra_load_pdf { left: 140px; }
</style>
<script>
var deuda=0;
$(document).ready(function(e) {
 $('select[name=id_banc]').load_json('../ajax/ajax.php',{ 'bancos_json' : 123 },function(json) {
	var html='<option  value="null" selected></option>';
	for(var i=0;i<json.id_banc.length;i++)
	{
		html+="<option value='"+json.id_banc[i]+"'>"+json.nomb_banc[i]+"</option>";
	}
	return html;
   });
	$('select[name=modo_pago]').change(function(e) {
        var value=$(this).attr('value');
		switch(value)
		{
			case 'E':
				$('input[name=idet_pago]').fadeOut().attr('value','null');
				$('select[name=id_banc]').fadeOut().attr('value','null');
				$('#title_idet_pago').html('');
				$('#banco').html('');
			 break;	
			 case 'C':
			 	$('input[name=idet_pago]').fadeIn().attr('value','');
			 	$('select[name=id_banc]').fadeIn().attr('value','');
			 	$('#title_idet_pago').html('NUMERO DE CHEQUE');
			 	$('#banco').html('BANCO');
				
			 break;
			 case 'D':
			 	$('input[name=idet_pago]').fadeIn().attr('value','');
			 	$('select[name=id_banc]').fadeIn().attr('value','');
			  	$('#title_idet_pago').html('NUMERO DE DEPOSITO');
			   	$('#banco').html('BANCO');
			 break;
		}
    });
	
	$('button').click(function(e) {
        var bsf= $('input[name=bsf_pago]').attr('value');
		if(Number(bsf)>deuda)
		{
			e.preventDefault();
			alert('EL MONTO SUPERA LA DEUDA DE LA FACTURA');
			return 0;
		}
		if(!confirm('ESTA SEGUR@ DE REGISTRAR EL PAGO ?'))
		{
			e.preventDefault();
		}
		
		
		
		
		
    });
	$('.dolar').click(function(e) {
        $('input[name=bsf_pago]').val(deuda);
    });
	
});
</script>
<?php
if(!empty($_GET['extern']))
{
	echo "<a href='$_SERVER[HTTP_REFERER]'><div class='atras' id='atras'></div></a>";
}
?>
<div class="produc ">
  <?PHP 
if($_GET['tipo_fact']=='C')
{
	echo "<h1>PAGOS DE FACTURA DE COMPRA <BR> ORDEN DE COMPRA N° ".$_GET['id_fact']."</h1>";
	
	
	$database->consulta(PRODUCTOS::COMPRAS,"nume_orde='".$_GET['id_fact']."'",NULL,'GROUP BY nume_orde');
	$compra_venta=$database->result();
	echo $database->error;
	$database->consulta(PRODUCTOS::TOTAL_PAG_C,"nume_orde='".$_GET['id_fact']."'",NULL,'GROUP BY nume_orde');
	$pagado=$database->result();
	echo $database->error;
	echo "<script>
	deuda=".number_format((float)$compra_venta['total_bs'],2,'.','').'-'.number_format((float)$pagado['total_pag'],2,'.','').";
	</script>
	<h3>EL TOTAL DE LA FACTURA ES ".fmt_num((float)$compra_venta['total_bs'])."<BR>
	SE A PAGADO ".fmt_num((float)$pagado['total_pag'])."<BR>
	SE ADEUDA  ".fmt_num((float)$compra_venta['total_bs']-$pagado['total_pag'])."
	</h3>
	";
	
}elseif($_GET['tipo_fact']=='V')
{
	echo "<h1>PAGOS DE NOTA DE ENTREGA N° ".$_GET['id_fact']."</h1>";
	
	
	$database->consulta(PRODUCTOS::VENTAS,"nume_nent='".$_GET['id_fact']."'",NULL,'GROUP BY nume_nent');
	$compra_venta=$database->result();
	echo $database->error;
	$database->consulta(PRODUCTOS::TOTAL_PAG_V,"nume_nent='".$_GET['id_fact']."'",NULL,'GROUP BY nume_nent');
	$pagado=$database->result();
	echo $database->error;
	echo "<script>
	deuda=".number_format((float)$compra_venta['total_bs'],2,'.','').'-'.number_format((float)$pagado['total_pag'],2,'.','').";
	</script>
	<h3>EL TOTAL DE LA FACTURA ES ".fmt_num((float)$compra_venta['total_bs'])."<BR>
	SE A PAGADO ".fmt_num((float)$pagado['total_pag'])."<BR>
	SE ADEUDA  ".fmt_num((float)$compra_venta['total_bs']-$pagado['total_pag'])."
	</h3>
	";
}

 ?>
  <div id="conten_html">
    <H4>NOTA: EL MONTO REFLEJADO DE LA FACTURA ES SIN IVA <BR>
      POR LO QUE EL PAGO A REGISTRAR DEVERA SER INGRESADO RESTANDOLE EL IVA </H4>
    <form name="pagos" action="" method="post">
      <input type="hidden" name="id_fact" value="<?php echo $_GET['id_fact'] ?>">
      <input type="hidden" name="tipo_fact" value="<?php echo $_GET['tipo_fact'] ?>">
      <table width="560" border="0" cellspacing="1" cellpadding="0">
        <tr class="col_title">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="col_hov  ">
          <td>MODO DE PAGO</td>
          <td><select name="modo_pago">
              <option value="E" selected>EFECTIVO</option>
              <option value="C">CHEQUE</option>
              <option value="D">DEPOSITO</option>
            </select>
        </tr >
        <tr class="col_hov row_act">
          <td id="title_idet_pago"></td>
          <td><input name="idet_pago" type="text" style="display:none;" value="null"></td>
        </tr >
        <tr class="col_hov  ">
          <td id="banco"></td>
          <td><select name="id_banc" style="display:none;" >
            </select></td>
        </tr>
        <tr class="col_hov row_act">
          <td>MONTO BSF</td>
          <td><input name="bsf_pago" type="text"><div class="dolar" style=" float:left;"></div></td>
        </tr>
        <tr >
          <td colspan="2" align="center"><button class="submit" type="submit" name="boton" value="">Enviar</button></td>
        </tr>
      </table>
    </form>
  </div>
</div>
