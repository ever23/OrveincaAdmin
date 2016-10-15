<?php
require_once("../clases/config_orveinca.php");
$time= new TIME();
$html= new INFO_HTML();
$database= new EMPLEADOS();
$conf=$database->config();
if(!empty($_POST['desc_carg']))
{
  $database->InsertarCargo($_POST['codi_carg'],$_POST['desc_carg']);
}

?>
<style>
	.input_text
	{
		width: 300px;
		height: 25px;
		border-radius: 5px;
	}
</style>
<div id="conten_html" class="produc" align="center">
 <div class="head-border-baj ct_title"><b>INSERTAR CARGO </b></div> 
 <div align="center">
 <br>
 <br>

  <form action="" METHOD="post" enctype="multipart/form-data" name="frmDatos" id="frmDatos" >
  
  <input type="text" name="codi_carg" class="input_text" placeholder="CODIGO">
  <br><br>
<br>

  
  <input type="text" name="desc_carg" class="input_text" placeholder="DESCRIPCION">
  <br>
         <button class="submit" type="submit" value="entrar"> ENVIAR </button>
  </form>
</div>
<div align="center">
<table width="500">
<thead>
<tr>
<td>DESCRIPCION</td>
<td>&nbsp;</td>
</tr>
</thead>
<tbody>
<?php
$database->consulta("SELECT * FROM cargos");
$fil=true;
while($campo=$database->result())
{
	echo "
	<tr class='col_hov ".($fil=!$fil?'row_act':'')."'>
<td id='".$campo['codi_carg']."'>".$campo['desc_carg']."</td>
<td><a href='".$campo['codi_carg']."' class='elimina_carg'><div class='elimina'></div></a></td>
</tr>
	";
	
}

?>
</tbody>
</table>

</div>

</div>

</div>

<div class="load_catalogo"></div>
<div id="barra_load_pdf">
  <div id="barra_load_"></div>
</div>
<script>
$(document).ready(function(e) {
	$('.elimina_carg').click(function(e) {
        e.preventDefault();
		var codi_carg=$(this).attr('href');
		var desc_carg=$('#'+codi_carg).html();
	$( '#errores:ui-dialog' ).dialog('destroy' );
	$( '#errores' ).dialog( 'close' );
	$( '#errores' ).attr('title','<H2> </H2>');
	$( '#error_div' ).html("ESTA SEGURO DE ELIMINAR EL CARGO "+desc_carg);
	$( '#errores' ).dialog({
		height: 300,
		width:350,
		modal: true,
		buttons: {
			'Aceptar': function() 
			{
				$().load_json('empleados_ajax.php',{"elimiar":true,"codi_carg":codi_carg},function(J){
					if(J.error)
					{
						$( '#errores:ui-dialog' ).dialog('destroy' );
						$( '#errores' ).dialog( 'close' );
						error('error',J.error)	;
					}else
					{
						$('#'+codi_carg).parent().fadeOut();
						$( '#errores' ).dialog( 'close' );
						
					}
					
					 });
			},
			'Cancelar': function() 
			{
				$( '#errores' ).dialog( 'close' );
				
			}
		}
	});
		
    });
});

</script>