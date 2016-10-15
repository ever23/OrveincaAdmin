<?PHP
include("../clases/config_orveinca.php");
$html= new HTML(false,false);
$Session= new SESSION(false);
$html->prettyPhoto();

?>
<style type="text/css"></style>
<script type="text/javascript">
$(document).ready(function(e) {
   
	$('#busqueuda').click(function()
	{
		if($('.form_search').css('display')=='none')
		{
			  $('.form_search').fadeIn(200);	
		}
		else
		{
		//$('.formulario').css('display','none') ;	
			$('#opcion').fadeOut(100);	
			$('.form_search').fadeOut(300);	
			$('#opcion').attr('value','all');
		}
		
	});
	
	$('#criterio_search').click(function(e) {
        
		if(e.target.checked)
		{
			$('#opcion').fadeIn(300);	

			$('.form_search').animate({'width':'274'},500)
		}else
		{
			$('#opcion').fadeOut(300);	
			$('#opcion').attr('value','all');
			$('.form_search').animate({'width':'156'},500)
		}
    });
	$(window).load(function(e) {
        	star_load();
    });
  $('#l_precios').load_html('ajax_inventario.php',{ l_precios:true} );
  
	$('#text').keyup(function(e) 
	{
		var opcion=$('#opcion').attr('value');
		var text=$('#text').attr('value');
		var like='LIKE';
		var slike='%';
		if(text.length!=0)
		{
			$('#l_precios').load_html(
				'ajax_inventario.php',
				{
					'opcion':opcion,
					'texto' :slike+text+slike,
					'like'  :like, 
					'l_precios':true
				}
			);
		}else
		{
			$('#l_precios').load_html('ajax_inventario.php',{ l_precios:true} );
		}
	 });
});


</script>
<div   class="conten_ico" >

  <div id="pdf" class="pdf" title="inventario_pdf.php"></div>

</div>

<div align="center" >
  <h1>  INVENTARIO</h1>
   <div align="center" class="form_search">
    <input type="checkbox" name="criterio" value="1"  id="criterio_search">
    <select name="opcion1" id="opcion" class="selet_search" style="display:none">
      <option value="all" selected> </option>
      <option value="desc_prod" > DESCRIPCION </option>
      <option value="id_prod" > CODIGO </option>
      <option value="desc_marc" > MARCA </option>
      <option value="desc_mode" > MODELO </option>
    </select>
    <input type="search" class="input_search" name="texto1"  placeholder="BUSCAR"  id="text"/>
  </div>
  
  <div class="center_content" id="muestra">
    <div align="center" class="stabla">
      <h2></h2>
    </div>
    <div id="pdf_blok">
    
      <iframe id="iframe" style="display:none;"></iframe>
    </div>
    <div class="hoja" id="conten_html">
      <table width="800" border="0" cellspacing="2" cellpadding="1"   id="l_precios">
      <tr class="col_title">
        <td width="68" scope="col"  >CODIGO
          </th>
        <td  width="480"  scope="col"  >DESCRIPCION
          </th>
          <?php
          if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
		  { ?>
        <td width="80" scope="col" >ACCION
          </th>
          <?php }?>
      </tr>
        </tr>
      
    </table>
      
    </div>
  </div>
</div>
