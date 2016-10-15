<?php
require_once("../clases/config_orveinca.php");;

$html= new HTML();

$database= new PRODUCTOS();

$html->set_title("LISTA DE PRECIOS");

$html->prettyPhoto('config_prod');
$html->AddCssScript('#Div_menu{left:337px;} ');
if(empty($text))
$text="";
?>

<script>
 var tabla=null;
$(document).ready(function(e)
{
	
	<?php
	if(!empty($_GET['codi_clpr']))
	{
		echo "$(window).load(function(e) {
        	star_load();
    });
	select_tab('".$_GET['codi_clpr']."');";
	}elseif(!empty($_GET['text']))
	{
		//echo "select_tab($tabla);";
		echo "	$(window).load(function(e) {
        	star_load();
    });
	$('#l_precios').load_html('ajax_precios.php',{'opcion':'id_prod','codi_clpr' :'','texto' :'$_GET[text]','like'  :'=','l_precios':true});
	";
		
	}
	if(!empty($_GET['action']))
	{
		
		echo "star_load_pdf();
			$('#conten_html').fadeOut(function()".
			'{'."$('#iframe').fadeIn(); });
			$('#iframe').attr('src','".($_GET['action']=='lpre_pdf'?"l_precios_pdf.php":"catalogo_pdf.php")."');
			$('#iframe').attr('width',960);
			$('#iframe').attr('height',600);";	
	}

  ?>
	$('#busqueuda').click(function()
	{
		$(this).fadeIn();
		$('#pag_pdf').css('display','none');
		if($('#conten_html').css('display')=='none')
		{	
			$('#iframe').fadeOut(function(){$('#conten_html').fadeIn();});
			$('#iframe').attr('src','');
			$('#iframe').attr('width',0);
			$('#iframe').attr('height',0);
			
		}
	});
	
	$('#criterio_search').click(function(e) {
        $('#pag_pdf').css('display','none');
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
	
	
	$('#text').keyup(function(e) 
	{
		var opcion=$('#opcion').attr('value');
		var text=$('#text').attr('value');
		var like='LIKE';
		var slike='%';
		star_load();
		var _POST={
		'opcion':opcion,
		'codi_clpr' :tabla,
		'texto' :slike+text+slike,
		'like'  :like, 
		'l_precios':true	
			
		};
		if(text.length!=0)
		{
			$('#l_precios').load_html('ajax_precios.php',_POST);
		}else
		{
			$('#l_precios').load_html('ajax_precios.php',{'codi_clpr': tabla, l_precios:true});
		}
		
	 });
	
	 $('.b_catalogo').click(function(e) {
		 $('#pag_pdf').css('display','none');
		 var htm="l_precios2_pdf.php";
        var w=960;
		var h=600;
		if($('#conten_html').css('display')!='none')
		{	
			star_load_pdf();
			$('#conten_html').fadeOut(function(){$('#iframe').fadeIn(); });
		
			$('#iframe').attr('src',htm);
			$('#iframe').attr('width',w);
			$('#iframe').attr('height',h);
		}else
		{
			$('#iframe').fadeOut(function(){$('#conten_html').fadeIn();});
			//$('#iframe').attr('src','');
			$('#iframe').attr('width',0);
			$('#iframe').attr('height',0);
		}
    });
	$('#iframe').load(function(e) {
        stop_load_pdf();
		$('#pag_pdf').fadeIn();
    });
	
	 $('#carta').click(function(e) {
		  e.preventDefault();
		
    $('#iframe').attr('src', $('#pdf').attr('title')+'?style=Letter');
	star_load_pdf();
});
$('#oficio').click(function(e) {
	  e.preventDefault();
    $('#iframe').attr('src', $('#pdf').attr('title')+'?style=Legal');
	star_load_pdf();
});
 $('#pdf').click(function(e) {
    $('#pag_pdf').css('display','none');
});
});

function select_tab(tab)
{
	$('#l_precios').load_html('ajax_precios.php',{'codi_clpr': tab, l_precios:true},
	function(html){  
	$('#boton_aparece_menu').html($('#'+tab).html());
	return html;
	});
	tabla=tab;
}


</script>
<style type="text/css">
.form_search
{
	float: right;
	width: 156px;
}

#iframe,#pag_pdf { display:none; }
</style>


<div align="center" class="conten_ico" >
<a href='config_prod.php?iframe=true&amp;width=600&amp;height=370&amp;' class='lightbox-image'  data-gal='config_prod[iframe]'>
              <div class="conf" ></div>
              </a>

  <div class="pdf" id="pdf" title="l_precios_pdf.php"></div>
  
  
  <div class="b_catalogo" id="catalogo_pdf"></div>
  <a href="insertar_nuevo.php"><div class="new2" id="new_prod"></div></a>
  <div class='buscar' id="busqueuda" > </div>
  <div align="center" class="form_search">
    <input type="checkbox" name="criterio" value="1"  id="criterio_search">
    <select name="opcion1" id="opcion" class="selet_search" style="display:none">
      <option value="all" selected> </option>
      <option value="desc_prod" > DESCRIPCION </option>
      <option value="id_prod" > CODIGO </option>
      <option value="desc_marc" > MARCA </option>
      <option value="desc_mode" > MODELO </option>
    </select>
   
    
  </div><div align='center' id="pag_pdf" style="float:left;"><a href='' id='carta'><div>CARTA</div></a>  <a href='' id='oficio'><div>OFICIO</div></a></div>
</div>

<div id="pdf_blok">
  <iframe id="iframe"></iframe>
</div>
<div id="conten_html">
  <div align="center" > <br>
    <h1>CONSULTA LISTA DE PRECIOS</h1>
    <div align="center"> <BR>
      <div id="boton_aparece_menu" onmouseover="menu(1);"   onmouseout="menu(2);" align="center"> CONSULTA LISTA DE PRECIOS </div>
      <div id="Div_menu" onmouseover="menu(3);" onmouseout="menu(2);" align="center">
        <ul style="margin: 0;padding: 0;list-style-type: none;display: inline; " class="textoGris_G">
          <li class='li_menu' id='null' onclick='select_tab(null)';  >CONSULTA LISTA DE PRECIOS</li>
          <?php
if($productos=$database->clasificacion())
foreach($productos as $ide_ => $campo)
{
	echo"<li class='li_menu' id='".$campo['codi_clpr']."' onclick='select_tab(\"".$campo['codi_clpr']."\")';  >".$campo['desc_clpr']."</li>";
}
echo $database->error() ; 
?>
        </ul>
      </div>
    </div><br>
<br>


    <center>
     <input type="search" class="input_search" name="texto1" value="<?php echo $text ?>" placeholder="BUSCAR"  id="text"/>
    </center>
    <div class="catalogo" align="center">
      <table width="800" border="0" cellspacing="2" cellpadding="1"   id="l_precios">
        <tr class="col_title">
          <td width="68" scope="col"  >CODIGO
            </th>
          <td  width="480"  scope="col"  >DESCRIPCION
            </th>
            <?php
          if(SESSION::GetVar('permisos')==__AUTORIZATE_ADMIN__ || SESSION::GetVar('permisos')==__AUTORIZATE_ROOT__){ ?>
          <td width="80" scope="col" >ACCION
            </th>
            <?php }?>
        </tr>
          </tr>
        
      </table>
    </div>
  </div>
</div>

