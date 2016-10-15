<?PHP
require_once("../clases/config_orveinca.php");
$database= new PRODUCTOS();
$html= new HTML();
$html->prettyPhoto();

$time= new TIME()
?>
<style type="text/css">
#idet {

	width: 238px;
	margin-top: 6px;
	margin-right: 6px;
	margin-bottom: 6px;
	margin-left: 6px;
	border-radius: 2px;
}
#idet > ul >a >li {
	height: 15px;
	border: 1px solid rgba(252,229,229,1.00);
	border-radius: 2px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
	margin-left: 1px;
	color: rgba(3,3,3,1.00);
	display: block;
}
#idet > ul >a
{
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;	
	cursor:pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function(e) {
  
	// star_load_pdf();
   $('iframe').attr('src','reportes_nent_pdf.php?reporte=all&fecha=<?php echo $time->format("Y-m"); ?>&opcion2=');
   $('select[name=opcion2]').change(function(e) {
    
	switch($(this).val())
	{
		case 'vend':
		$('input[name=idet]').fadeOut(function(){$('select[name=vend]').fadeIn(); $('#idet').fadeOut();});
		
		break;
		case 'clie':
		$('select[name=vend]').fadeOut(function(){$('input[name=idet]').fadeIn(); });
		$('#idet').fadeIn();
		break;
		default:
		$('select[name=vend]').fadeOut();
		$('input[name=idet]').fadeOut();
		$('#idet').fadeOut();
	}
	});
	
	$('button').click(function(e) {
		var opcion=$('select[name=opcion]').val();
		var opcion2=$('select[name=opcion2]').val();
		var value=$('input[name=text_date]').val();
		var idet=$('input[name=idet]').val();
		var vend=$('select[name=vend]').val();
		 star_load_pdf();
		 if(opcion=='prod')
		 {
			 if(opcion2=='clie')
			 {
				  $('iframe').attr('src','reportes_pdf.php?reporte='+opcion2+'&fecha='+value+'&opcion2='+idet);
			 }else
			 {
				 $('iframe').attr('src','reportes_pdf.php?reporte='+opcion2+'&fecha='+value+'&opcion2='+vend);
			 }
			 
			 
		 }else
		 {
			 if(opcion2=='clie')
			 {
				  $('iframe').attr('src','reportes_nent_pdf.php?reporte='+opcion2+'&fecha='+value+'&opcion2='+idet);
			 }else
			 {
				 $('iframe').attr('src','reportes_nent_pdf.php?reporte='+opcion2+'&fecha='+value+'&opcion2='+vend);
			 }
		 } 	
	});
	$('input[name=idet]').keyup(function(e) {
        var value=$(this).val();
		$('#idet >ul').load_html('../clientes/cliente_ajax.php',{buscar_cliente:true,'value':value});
    });
	
});


</script>

<div align="center" >


  <h1>REPORTES DE VENTAS</h1>
   <div align="center" class="form_search">
     
   <input type="month"name="text_date" value="<?php echo $time->ano.'-'.$time->mes ?>" >

    
    <select name="opcion"  style="display:block;">
    <option value="all" selected></option>
    <option value="nent" selected>POR NOTAS DE ENTREGA</option>
    <option value="prod">POR PRODUCTOS</option>
    
    </select>
        <select name="opcion2"  style="display:block;">
          <option value="all" selected></option>
      <option value="vend">VENDEDOR</option>
    <option value="clie">CLIENTE</option>
     </select>
        <input type="text" name="idet" placeholder="IDENTIFICACION" style="display:none;">
     <div id="idet" >
        <ul>
       
       </ul>
        </div>
   
    <select name="vend" style="display:none;">
     <option value="" selected>DEPARTAMENTO DE VENTAS</option>
   <?php 
		$database->consulta("SELECT * FROM empleados where codi_carg='vend';");
		while($campo =$database->result())
		{
			
			echo " <option value='$campo[ci_empl]' 	>$campo[nom1_empl] $campo[ape1_empl] </option>";
		} 
		?>
    </select><br>
      <button class="submit" type="submit" name="boton" value="">GENERAR REPORTE</button>
  </div>
  
  <div class="center_content" id="muestra">
    <div align="center" class="stabla">
      <h2></h2>
    </div>
    <div id="pdf_blok">
    
      <iframe id="iframe"  width="960" height="600" ></iframe>
    </div>
    
  </div>
</div>
