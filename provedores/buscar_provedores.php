<?PHP 
//funciones mysq
require("../clases/config_orveinca.php");
$html=new HTML();
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
		var text=$('#text').attr('value');
	
		
		$('#l_proved').load_html('provedor_ajax.php',
		{
			BUSCAR_PROVEEDOR:true,
			'text' :text,
			<?PHP
  if(!empty($_GET['redirec']))
	{
		echo "'extern':'$_GET[redirec]'";	
	}
			
			?>
		});
		
	});
	
	$(window).load(function(e) {
        	star_load();
    });
	<?php
	if(!empty($_GET['text']))
	{
		echo "
		$('#l_proved').load_html(
			'provedor_ajax.php',
			{
				BUSCAR_PROVEEDOR:true,
				'text' :'$_GET[text]', 
			});";
	}else
	{
		echo "$('#l_proved').load_html('provedor_ajax.php',{
			BUSCAR_PROVEEDOR:true,
			'text' :' '";
		 if(!empty($_GET['redirec']))
		{
			echo ",'extern':'$_GET[redirec]'";	
		}
		echo "});";
	}
	?>
	
});


</script>
<style type="text/css">
#iframe{
	display:none;	
}

 <?php
  if(!empty($_GET['redirec']))
	{
		echo ".edit,.elimina{ display:none;}";		
	}
	?>
.form_search {
	display:none;
}
</style>
<div align="center" class="conten_ico" >
<?php
	if(!empty($_GET['redirec']) && !empty($_SERVER['HTTP_REFERER']))
	{
		
		echo "<a href='$_SERVER[HTTP_REFERER]'>";		
	}else
	{
		echo '<a href="../defaut/defaut.php">';
	}
	?>
  <div class="atras" id="atras"></div></a>

  <div class="pdf" id="pdf" title="provedor_pdf.php"></div>
  <div class='buscar' id="busqueuda"  ></div>
  <?php
  if(!empty($_GET['redirec']))
	{
		echo "<a href='info_provedores.php?redirec=$_GET[redirec]'>";		
	}else
	{
		echo '  <a href="info_provedores.php">';
	}
  ?>
  <div class="new2" id="new_provedor"    ></div>
  </a> 
  
  
  </div>
<div class="form1 " align="center">
  
  <h1>LISTA DE PROVEDORES</h1>

 

   <div class="form_search">
    <div id="id_text">
      <input type="search" name="text" id="text" />
    </div>
   
  </div>
 <div id="pdf_blok"><iframe id="iframe"  style="display:none;"></iframe></div>
  
  <div id="conten_html" style="display:block;">
  
    
     <table width="950" border="0" cellspacing="1" cellpadding="0" id="l_proved">
  
      </table>
     
  </div>
</div>
