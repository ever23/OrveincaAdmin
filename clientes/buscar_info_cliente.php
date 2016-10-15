<?PHP 
require_once("../clases/config_orveinca.php");

$html= new HTML();
$html->set_title("CLIENTES");

$html->prettyPhoto();



?>

<script type='text/javascript'>

$(document).ready(function() 
{

	$('#text').keyup(function()
	{
		var opcion=$('#opcion').attr('value');
		var text=$('#text').attr('value');
		var like='LIKE';
		var slike='';
		$('#l_clientes').load_html(
			'cliente_ajax.php',
			{
				'opcion':opcion,
				'text' :slike+text+slike,
				'like'  :like
				<?php
				 if(!empty($_GET['redirec']))
					{
					echo ",'extern':'$_GET[redirec]'";	
					}
				?>
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
		$('#l_clientes').load_html(
			'cliente_ajax.php',
			{
				'opcion':'$_GET[opcion]',
				'text' :'$_GET[text]', 
				'like'  :'='
			});";
	}else
	{
		echo "$('#l_clientes').load_html('cliente_ajax.php',{'text' :'all'";
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
 


  <div class="pdf" id="pdf" title="cliente_pdf.php"></div>
  <div class='buscar buscar_clie' id="busqueuda"  ></div>
   <?php
  if(!empty($_GET['redirec']))
	{
		echo "<a href='info_cliente.php?redirec=$_GET[redirec]'>";		
	}else
	{
		echo '  <a href="info_cliente.php">';
	}
  ?>
  <div class="new2" id="new_cliente"    ></div>
  </a> 
  
  
  </div>
<div class="form1" align="center">
  <div align="center"></div>
  <h2></h2>
 
  <h1>LISTA DE CLIENTES </h1>
  <div class="form_search">
    <select name="opcion" id="opcion">
        <option value="NULL" selected>--</option>
      <option value="nomb_clie" >RASON SOCIAL</option>
      <option value="idet_clie">RIF</option>
      <option value="vendedor">VENDEDOR</option>
      <option value="contacto">CONTACTO</option>
    </select>
    <div id="id_text">
      <input type="search" name="text" id="text" />
    </div>
   
  </div>
  <div id="pdf_blok"><iframe id="iframe"></iframe></div>
  
  <div id="conten_html" style="display:block;">
    <div class="hoja" >
      
      <table width="950" border="0" cellspacing="1" cellpadding="0" id="l_clientes">
  
      </table>
       
    </div>
  </div>
</div>

