<?PHP 
include("../clases/config_orveinca.php");
$html=new HTML();
$html->prettyPhoto();


$html->head()
?>
<script type='text/javascript'>

$(document).ready(function() 
{
	$('#text').keyup(function()
	{
		var opcion=$('#opcion').attr('value');
		var text=$('#text').attr('value');
		var like='LIKE';
		var slike='%';
	
		
		$('#empleados').load_html(
			'empleados_ajax.php',
			{
				'opcion':opcion,
				'text' :slike+text+slike,
				'like'  :like
			}
			
		);	
	});
	$(window).load(function(e) {
        	star_load();
    });
	<?php
	if(!empty($_GET['text']))
	{
		echo "
		$('#empleados').load_html(
			'empleados_ajax.php',
			{
				'opcion':'$_GET[opcion]',
				'text' :'$_GET[text]', 
				'like'  :'='
			});";
	}else
	{
		echo "$('#empleados').load_html('empleados_ajax.php',{'text' :' '});";
	}
	if(!empty($_GET['nomina']))
	{
		echo "	$('#conten_html').fadeOut();
	$('#pdf_blok').fadeIn();
	$('#iframe').attr('src','empleado_pdf.php').attr('width',960).attr('height',600).fadeIn();";
	}
	?>

	
	$("a[data-gal^=\'config_empl\']").prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
});


</script>
<style type="text/css">
#iframe{
	display:none;	
}


.form_search, pdf_blok{
	display:none;
}
</style>
<div align="center" class="conten_ico" >
<a href='config_empl.php?iframe=true&amp;width=600&amp;height=370&amp;' class='lightbox-image'  data-gal='config_empl[iframe]'>
  <div class="conf"    ></div>
  </a>
   <div class="pdf" id="pdf" title="empleado_pdf.php"></div>
<a href="../defaut/defaut.php">
  <div class="atras" id="atras"></div></a>
  <div class='buscar' id="busqueuda"  ></div>
  <a href="info_empleados.php">
  <div class="new2" id="new_vendedor"    ></div>
  </a> 
  
  
  </div>
<div class="form1 " align="center">
  
  <h1>EMPLEADOS</h1>

 

   <div class="form_search">
    <select name="opcion" id="opcion">
       <option value="" selected></option>
      <option value="nom1_empl" selected>NOMBRE</option>
      <option value="ci_empl">CI</option>
    </select>
    <div id="id_text">
      <input type="search" name="text" id="text" />
    </div>
   
  </div>
  <div id="pdf_blok">
  <iframe id="iframe"></iframe>
</div>
  <div id="conten_html">
  
   
     <table width="950" border="0" cellspacing="1" cellpadding="0" ><thead>
     <tr class="col_title">
	  <td width="89" scope="col" >CI</td>
          <td width="230" scope="col" >NOMBRES Y APELLIDOS</td>
		   <td width="105" scope="col">EMAIL</td>
		   <td width="100" scope="col">CARGO</td>
          <td width="100" scope="col">DEPARTAMENTO</td>
            <td width="70" scope="col"></td>
     </thead>
     <tbody id="empleados"></tbody>
  
      </table>
     
  </div>
</div>
