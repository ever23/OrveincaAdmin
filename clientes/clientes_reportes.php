<?PHP
require("../clases/config_orveinca.php");
$database= new BD_ORVEINCA();
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
#idet > ul >a {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	cursor: pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function(e) {
  
   $('iframe').attr('src','cliente_pdf.php');
   
   $('select[name=opcion2]').change(function(e) {
    
	switch($(this).val())
	{
		case 'vend':
		
		$('input[name=idet]').fadeOut(function(){$('select[name=vend]').fadeIn(); $('#idet').fadeOut();});
		$('#direccion').fadeOut();
		break;
		case 'cont':
		$('select[name=vend]').fadeOut(function(){$('input[name=idet]').fadeIn(); });
		$('#idet').fadeIn();
		$('#direccion').fadeOut();
		break;
		case 'dir':
		$('#direccion').fadeIn();
		$('select[name=vend]').fadeOut(function(){$('input[name=idet]').fadeOut(); });
		$('#idet').fadeOut();
		break;
		default:
		$('select[name=vend]').fadeOut();
		$('input[name=idet]').fadeOut();
		$('#idet').fadeOut();
		$('#direccion').fadeOut();
	}
	});
	
	$('button').click(function(e) {
		
		var opcion2=$('select[name=opcion2]').val();
		
		var idet=$('input[name=idet]').val();
		var vend=$('select[name=vend]').val();
		var id_esta=$('select[name=id_estado]').val();
		var id_muni=$('select[name=id_muni]').val();
		var id_parr=$('select[name=id_parr]').val();
		 star_load_pdf();
	 switch(opcion2)
	{
		case 'vend':
		 $('iframe').attr('src','cliente_pdf.php?reporte='+opcion2+'&opcion2='+vend);
		break;
		case 'cont':
	 	 $('iframe').attr('src','cliente_pdf.php?reporte='+opcion2+'&opcion2='+idet);
		break;
		case 'dir':
		if(id_muni==="NULL" && id_parr==="NULL")
		{
			//alert("estado");
			$('iframe').attr('src','cliente_pdf.php?reporte=esta&opcion2='+id_esta);
		}else {
		if(id_muni!=="NULL" && id_parr==="NULL")
		{
			//alert("muni");
			 $('iframe').attr('src','cliente_pdf.php?reporte=muni&opcion2='+id_muni);
		}else{ if(id_muni!=="NULL" && id_parr!=="NULL")
		{
			//alert("parr");
			$('iframe').attr('src','cliente_pdf.php?reporte=parr&opcion2='+id_parr);
		}}}
		break;
		default:
		 $('iframe').attr('src','cliente_pdf.php');
		
	}
		
	});
	$('input[name=idet]').keyup(function(e) {
        var value=$(this).val();
		$('#idet >ul').load_html('../ajax/ajax.php',{buscar_contacto:true,'value':value});
    });
	
});


</script>

<div align="center" >
  <h1>REPORTES DE CLIENTES</h1>
  <div align="center" class="form_search">
    <select name="opcion2"  style="display:block;">
      <option value="all" selected></option>
      <option value="vend">VENDEDOR</option>
      <option value="cont">CONTACTO</option>
      <option value="dir">DIRECCION</option>
    </select>
    <input type="text" name="idet" placeholder="IDENTIFICACION" style="display:none;">
    <div id="idet" >
      <ul>
      </ul>
    </div>
    <div id="direccion" style="display:none;"> 
      <script>
		$(document).ready(function()
		{
			  $("select[name=id_estado]").load_json("../ajax/ajax.php",{"estados_json" : true},
		    function(json) 
			{
				var html="<option value=\'\'>ESTADO</option>";
				for(var i=0;i<json.id_esta.length;i++)
				{
					html+="<option value=\'"+json.id_esta[i]+"\'>"+json.desc_esta[i]+"</option>";
				}
				return html;
		  	});	
		});
		</script>
      <select name="id_estado" title="id_muni">
      </select>
      <select name="id_muni" title="id_parr" >
        <option value="NULL">--------</option>
      </select>
      <select name="id_parr" id="id_parr">
        <option value="NULL">--------</option>
      </select>
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
    </select>
    <br>
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
