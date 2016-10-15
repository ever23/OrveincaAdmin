<?PHP 
include ("../clases/config_orveinca.php");

$html=new HTML();
$database= new BD_ORVEINCA();
$html->prettyPhoto();
$html->head()
?>
<script type='text/javascript'>

$(document).ready(function() 
{
	 $('iframe').load(function(e)
	 {
		 stop_load();
	 });
	$('#text').keyup(function()
	{
		var opcion=$('#opcion').attr('value');
		var text=$('#text').attr('value');
		
		$('#gasto').load_html('ajax_gastos.php',
		{
			'gastos' :true,
			'opcion':opcion,
			'text' :text,
			
		});
		
	});
	
	$(window).load(function(e) {
        	star_load();
    });
	<?php
	if(!empty($_GET['codi_gast']))
	{
		echo "
		$('#gastos').load_html(
			'ajax_gastos.php',
			{
				'gastos' :true,
				'opcion':'codi_gast',
				'text' :'".$_GET['codi_gast']."'
			});";
	}else
	{
		echo "$('#gastos').load_html('ajax_gastos.php',{'gastos' :true});";
	}
	?>
	$('input[name=fecha]').change(function(e) {
        var text=$('#text').attr('value');
		
		$('#gastos').load_html('ajax_gastos.php',
		{
			'gastos' :' ',
			'opcion':'fecha',
			'text' :text,
			
		});
    });
	$('select[name=opcion]').change(function(e) {
        
		if($(this).val()=='fecha')
		{
			$('input[name=text]').fadeOut(function(){$('input[name=fecha]').fadeIn();});
			$('select[name=codi_tpga]').fadeOut();
		}else
		if($(this).val()=='codi_tpga')
		{
			$('select[name=codi_tpga]').fadeIn();
			$('input[name=fecha]').fadeOut();
			$('input[name=text]').fadeOut();
		}else
		{
			$('select[name=codi_tpga]').fadeOut();
			$('input[name=fecha]').fadeOut(function(){$('input[name=text]').fadeIn();});
		}
    });
	$('select[name=codi_tpga]').change(function(e) {
          var text=$(this).val();
		
		$('#gastos').load_html('ajax_gastos.php',
		{
			'gastos' :true,
			'opcion':'codi_tpga',
			'text' :text,
			
		});
    });
	
});


</script>
<style type="text/css">
#iframe { display: none; }

.form_search { display: none; }
</style>
<div align="center" class="conten_ico" > <a href="../defaut/defaut.php">
  <div class="atras" id="atras"></div>
  </a>
  <div class='buscar' id="busqueuda"  ></div>
  <a href="ingresar_gasto.php">
  <div class="new2" id="new_gasto"    ></div>
  </a> </div>
<div class="form1 " align="center">
  <h1>GASTOS</h1>
  <div class="form_search">
    <select name="opcion" id="opcion">
     <option value="" selected></option>
    <option value="codi_reci" >NUMERO DE RECIBO</option>
      <option value="codi_tpga" >TIPO DE GASTO</option>
      <option value="desc_gast">DESCRIPCION </option>
      <option value="fecha">FECHA</option>
    </select>
    <div id="id_text">
    <input type="date" name="fecha" style="display:none;"/>
    <select name="codi_tpga" style="display:none;">
    <?php
    $database->consulta("SELECT * FROM tipogasto");
	while($campo=$database->result())
	{
		 echo "<option value='".$campo['codi_tpga']."' >".$campo['desc_tpga']."</option>";
	}
	?></select>
      <input class="input_text"  type="search" name="text" id="text"/>
    </div>
  </div>
  <div id="conten_html" style="display:block;">
    <table width="950" border="0" cellspacing="1" cellpadding="0" >
      <thead>
        <tr>
        <td>RECIBO</td>
          <td>TIPO DE GASTO</td>
          <TD>DESCRIPCION</TD>
          <TD>MONTO</TD>
          <TD>FECHA</TD>
          <TD>-</TD>
        </tr>
      </thead>
      <tbody id="gastos">
      </tbody>
    </table>
  </div>
</div>
