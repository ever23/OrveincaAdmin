<?PHP 
//funciones mysql
require_once("../clases/config_orveinca.php");
$html= new HTML();
$html->set_title("PEDIDOS");
$html->prettyPhoto();
?>
<script type='text/javascript'>
$(document).ready(function() 
{
	
	$('select[name=opcion]').change(function(e) {
        var opcion =$(this).attr('value');
		if(opcion=='estado')
		{
			 $('#text').fadeOut(function(){ $('select[name=estado]').fadeIn();});
			 $('input[name=text_date]').fadeOut(function(){  $('select[name=estado]').fadeIn();});
		}else 
		{if(opcion=='fech')
		{
			 $('#text').fadeOut(function(){ $('input[name=text_date]').fadeIn();});
			 $('select[name=estado]').fadeOut(function(){ $('input[name=text_date]').fadeIn();});
			  
		}else
		{
			 $('input[name=text_date]').fadeOut(function(){  $('#text').fadeIn(); $('select[name=estado]').fadeOut();});
		}
		}
    });
	$('input[name=text_date]').change(function(e) {
       var opcion;
		var text=$(this).attr('value');
		var like='=';
		var slike='';
		$('#pedidos').load_html(
			'ajax_pedidos.php',
			{
				'opcion':opcion,
				'texto' :slike+text+slike,
				'like'  :like,
				'pedidos':true
				<?php
				 if(!empty($_GET['redirec']))
					{
					echo ",'extern':'$_GET[redirec]'";	
					}
				?>
			}
			);
    });
	 $('select[name=estado]').change(function(e) {
        
		var opcion='esta_pedi';
		var text=$(this).attr('value');
		var like='=';
		var slike='';
		$('#pedidos').load_html(
			'ajax_pedidos.php',
			{
				'opcion':opcion,
				'texto' :slike+text+slike,
				'like'  :like,
				'pedidos':true
				<?php
				 if(!empty($_GET['redirec']))
					{
					echo ",'extern':'$_GET[redirec]'";	
					}
				?>
			}
			);	
		
    });
	$('#text').keyup(function()
	{
		var opcion=$('#opcion').attr('value');
		var text=$('#text').attr('value');
		var like='LIKE';
		var slike='%';
		$('#pedidos').load_html(
			'ajax_pedidos.php',
			{
				'opcion':opcion,
				'texto' :slike+text+slike,
				'like'  :like,
				'pedidos':true
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
		$('#pedidos').load_html(
			'ajax_pedidos.php',
			{
				'opcion':'$_GET[opcion]',
				'texto' :'$_GET[text]', 
				'like'  :'=',
				'pedidos':true
				
			});";
	}else
	{
		echo "$('#pedidos').load_html('ajax_pedidos.php',{'texto' :' ',	'pedidos':true";
		 if(!empty($_GET['redirec']))
		{
			echo ",'extern':'$_GET[redirec]'";	
		}
		echo "});";
	}
	?>
	$('.buscar_clie').tics('BUSCAR UN CLIENTE');
});
</script>
<style type="text/css">
#iframe { display: none; }
 <?php  if(!empty($_GET['redirec'])){
 echo ".edit,.elimina{ display:none;}";
}
 ?> .form_search {
 display:none;
}
a{ color:rgba(0,0,0,1.00);
}
</style>
<div align="center" class="conten_ico" > <a href="../defaut/defaut.php">
  <div class="atras" id="atras"></div>
  </a>
  <div class='buscar buscar_pedi' id="busqueuda"  ></div>
  <a href="busqueda.php">
  <div class="new2" id="new_pedi"    ></div>
  </a> </div>
<div class="form1" align="center">
  <div align="center"></div>
  <h2></h2>
  <h1>BUSCAR PEDIDOS</h1>
  <div class="form_search">
    <select name="opcion" id="opcion">
    <option value="all" selected></option>
      <option value="nume_pedi">NUMERO DE PEDIDO</option>
      <option value="clie" >CLIENTE</option>
      <option value="vend">VENDEDOR</option>
       <option value="estado">ESTADO</option>
        <option value="fech">FECHA</option>
    </select>
    <div id="id_text">
        <input type="date" name="text_date"  style="display:none;">
    <select name="estado"  style="display:none;">
    <option value=""></option>
      <option value="E">ENTREGADO</option>
      <option value="P">PENDIENTE</option>
      <option value="C">CANCELADO</option>
    </select>
      <input type="search" name="text" id="text" />
    </div>
  </div>
  <div id="pdf_blok">
    <iframe id="iframe"></iframe>
  </div>
  <div id="conten_html" style="display:block;">
    <div class="hoja" >
      <table width="950" border="0" cellspacing="1" cellpadding="0" >
      <thead>
       <tr class="col_title">
        <td width="25" scope="col">N°</td>
       <td width="381" scope="col" >CLIENTE </td>
        <td width="207" scope="col" >VENDEDOR</td>
        <td width="92" >ESTADO</td>
       <td width="126" scope="col">FECHA</td>
        <td width="65" scope="col">ACCION</td></tr>
     </thead>
     <tbody id="pedidos">
     </tbody>
      </table>
    </div>
  </div>
</div>
