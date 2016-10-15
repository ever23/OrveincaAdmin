<?php

require_once("../clases/config_orveinca.php");
$html=new HTML();
$database= new PRODUCTOS();
if($_POST)
{
	if($gasto=$database->IngresarGasto($_POST))
	{
		redirec("index.php?codi_gast=".$gasto);
	}
}
$html->set_title("INGRESAR GASTO");

?>
<div align="center" class="conten_ico" >

 <a href="index.php">
  <div class="atras" id="atras"></div>
  </a> </div>
<div  align="center">
  <div  class="form1  form" >
    <div align="center"></div>
    <h1>REGISTRAR GASTO</h1>
    <form class="contact_form" action="" METHOD="post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
      <ul>
        <li class="form_row">
          <label>TIPO DE GASTO</label>
          <select name="codi_tpga">
          <?php
		  $database->consulta("SELECT * FROM tipogasto");
		  while($campo=$database->result())
		  {
			 echo "<option value='".$campo['codi_tpga']."' >".$campo['desc_tpga']."</option>"; 
		  }
		  ?>
          </select>
        </li>
        <li class="form_row">
         <label>NUMERO DE RECIBO</label>
         <input name="nume_reci" required type="text">
        </li>
       
         <li class="form_row">
         <label for="PAGO">MODO DE PAGO</label>
         <select name="modo_pago" required>
          <option value="E" selected>EFECTIVO</option>
           <option value="C">CHEQUE</option>
            <option value="D">DEPOSITO</option>
          </select>
          </li>
        <li class="form_row" id="idet_pago"  style="display:none;">
          <label for="PAGO" id="title_idet_pago"></label>
         <input name="idet_pago" type="text" value="null">
        </li>
        <li class="form_row" style="display:none;" id="banco_cont">
          <label id="banco"></label>
          <select name="id_banc"></select>
        </li>
        <li class="form_row">
         <label>MONTO</label>
         <input name="bsf_pago" type="text" required>
        </li>
        <li class="form_row">
        <label>DESCRIPCION  DEL GASTO </label>
        <textarea name="desc_gast" required></textarea>
        </li>
          <li class="form_row">
         <button class="submit" form="frmDatos" type="submit" name="boton">ENVIAR</button>
</li>
      </ul>
    </form>
  </div>
</div>
<script>

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
				$('input[name=idet_pago]').attr('value','null');
				$('#idet_pago').fadeOut();
				$('select[name=id_banc]').attr('value','null');
				$('#banco_cont').fadeOut();
				$('#title_idet_pago').html('');
				$('#banco').html('');
			 break;	
			 case 'C':
			 	$('input[name=idet_pago]').attr('value','');
				$('#idet_pago').fadeIn();
			 	$('select[name=id_banc]').attr('value','');
				$('#banco_cont').fadeIn();
			 	$('#title_idet_pago').html('NUMERO DE CHEQUE');
			 	$('#banco').html('BANCO');
				
			 break;
			 case 'D':
			 	$('input[name=idet_pago]').attr('value','');
					$('#idet_pago').fadeIn();
			 	$('select[name=id_banc]').attr('value','');
				$('#banco_cont').fadeIn();
			  	$('#title_idet_pago').html('NUMERO DE DEPOSITO');
			   	$('#banco').html('BANCO');
			 break;
		}
    });
	
	$('button').click(function(e) {
       
		if(!confirm("ESTA SEGURO DE RESGITAR EL GASTO"))
		{
			e.preventDefault();
			
		}
    });
	
});
</script>
