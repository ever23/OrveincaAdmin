<?php
require_once("../clases/config_orveinca.php");
$Session = new SESSION(false);
$database= new PRODUCTOS();
$get_plus='';
if(!empty($_GET['idet_clie']))
{
	$get_plus.="&idet_clie=".$_GET['idet_clie']."&";
}
if(!empty($_POST['nent']))
{
	if($database->temp_entrega($_POST))
	{
		
		redirec($_SERVER['PHP_SELF']."?".$get_plus);
	}
}
if(!empty($_GET['desechar']))
{
	$database->consulta("TRUNCATE TABLE `tem_nent_prod`");
	redirec($_SERVER['PHP_SELF']);
}

$html= new HTML();
$html->prettyPhoto();
$html->addlink_js("{src}busqueda.min.js")
?>
<script type="text/javascript">

$(document).ready(function(e) {
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
	$('#text').keyup(function(e) 
	{
		var opcion=$('#opcion').attr('value');
		var text=$('#text').attr('value');
		var like='LIKE';
		var slike='%';
		star_load();
		var _POST={
		'opcion':opcion,
		'extern':'<?php echo $_SERVER['PHP_SELF'] ?>',
		'root'  :'../inventario/',
		'texto' :slike+text+slike,
		'like'  :like, 
		'l_precios':true
		<?php
		if($get_plus!='')
		{
			echo ",'extern_get':'$get_plus '";
		}
		?>
		};
		if(text.length!=0)
		{
			
			$('#l_precios').load_html('../inventario/ajax_inventario.php',_POST);
		}else
		{
			$('#l_precios').load_html('../inventario/ajax_inventario.php',{
				 l_precios:true,
				 'extern':'<?php echo $_SERVER['PHP_SELF'] ?>',
				'root'  :'../inventario/'
				<?php
		if($get_plus!='')
		{
			echo ",'extern_get':'$get_plus '";
		}
		?>
				});
		}
	 });
	 
	
	$('.elimina_coti').click(function(e)
	{
	
	  
		e.preventDefault();
       var id_temp=$(this).attr('href');
	   var tr=$(this).closest('tr');
		$( '#dialog:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
		$( '#error_div' ).html('ESTA SEGURO DE ELIMINAR EL PRODUCTO DE LA NOTA DE ENTREGA?<table> '+$('#'+id_temp).html()+'</table>');
		$( '.actions').css('display','none');
		$( '#errores' ).dialog({
			
			height:300,
			width:500,
			modal: true,
			buttons: {
				'ELIMINAR': function() {
					$( '#errores' ).dialog( 'close' );
				
					$().load_html('../inventario/ajax_inventario.php',{ 'id_temp':id_temp,del_entr_id:true},
					function(html){
						if(html.trim()!='')
						{
							//$('#'+id_temp).html(html);
						}
						else
						{
							tr.remove();
						}
						return html;
						});
					
					$( '.actions').css('display','block');
				},
				Cancel: function() {
					$( '#errores' ).dialog( 'close' );
					$( '.actions').css('display','block');
				}
			}
		});
		
   	 });
});

</script>
<style>
.editar, .eliminar_id { display: none; }

#precio
{
	float: left;
	display: block;
}
</style>

<div align="center" class="conten_ico" > </a> <a href="busqueda.php?desechar=true">
  <div class="desechar"></div>
  </a>
  <?php
if(!empty($_POST['temp']))
{
	echo "<a href='".$_SERVER['PHP_SELF']."'><div class='atras'></div></a>";
}

?>
</div>
<div align="center" >
  <h1>INSERTAR EN NOTA DE ENTREGA </h1>
  <?php
  if(!empty($_GET['idet_clie']))
  {
	 echo " <h2><a href='nota_entrega.php?idet_clie=".$_GET['idet_clie']."'>SIQUIENTE</A></h2> "; 
  }else
  {
	 echo " <h2><a href='../clientes/info_cliente.php?redirec=../nota_entrega/nota_entrega.php'>SIQUIENTE</A></h2> "; 
  }
 
  if(empty($_POST['temp']))
  {
  ?>
  <div align="center" class="form_search">
    <input type="checkbox" name="criterio" value="1"  id="criterio_search">
    <select name="opcion1" id="opcion" class="selet_search" style="display:none">
      <option value="all" selected> </option>
      <option value="desc_prod" > DESCRIPCION </option>
      <option value="id_prod" > CODIGO </option>
      <option value="desc_marc" > MARCA </option>
      <option value="desc_mode" > MODELO </option>
    </select>
    <input type="search" class="input_search" name="texto1" value="<?php  ?>" placeholder="BUSCAR"  id="text"/>
  </div>
  <?php
  }
?>
  <table width="930" border="0" cellspacing="2" cellpadding="1"   id="l_precios">
    <tr class="col_title">
      <td width="68" scope="col"  >CODIGO
        </th>
      <td  width="480"  scope="col"  >DESCRIPCION
        </th>
      <td width="80" scope="col" >COLOR
        </th>
      <td width="80" scope="col" >MEDIDA
        </th>
      <td width="80" scope="col" >EXISTENCIA
        </th>
        <?php
         if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
		 { ?>
      <td width="80" scope="col" ></th>
        <?php }?>
    </tr>
      </tr>
    
    <?php 
if(!empty($_POST['temp']))
{
	$database->consulta(PRODUCTOS::INVENTARIO1," id_prod='$_POST[id_prod]' and  id_tama= '$_POST[id_tama]' and exad_colo ".($_POST['exad_colo']!=''?"='$_POST[exad_colo]'":" IS NULL")." GROUP by id_prod,exad_colo,id_tama");
	$inventario=$database->result();
	 $database->consulta("
		SELECT SUM(nent_prod.cant_nent) as cant_vend
FROM nent_prod
WHERE id_prod='$inventario[id_prod]' and  id_tama= '$inventario[id_tama]' and exad_colo ".($inventario['exad']!=''?"='$inventario[exad]'":" IS NULL ")."
GROUP by id_prod,exad_colo,id_tama 
		");
		
		$vendio=$database->result();
		$existencia=$inventario['cant_reci']-$vendio['cant_vend'];
	$med=$database->cost_prod($inventario['id_prod'],$inventario['medi_tama'],$inventario['id_tama'],$inventario['codi_umed']);
	echo "<tr class=' col_hov'>
    <th scope=col>$inventario[codi_clpr]$inventario[id_prod]</th>
    <th scope=col>$inventario[desc_prod]  $inventario[desc_marc] $inventario[desc_mode] </th>";
	
	
	  echo "
		 <th style='color:$inventario[exad];'>$inventario[desc_colo]</th>
		  <th>$inventario[codi_umed] $inventario[medi_tama]</th>";
			
	
	echo " 
	 	<th>$existencia</th>
		"; 
		 if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__){ 
			echo "<th  scope=col class='precios_action'>
	 <a href= '../lista%20de%20precios/info.php?id_prod=".$inventario['id_prod']."&amp;iframe=true&amp;width=640&amp;height=430&amp;' data-gal='prettyPhoto[iframe]' >
	 <div class='buscar1 mas_info' ></div></a>";
	
	
		 }	
}?>
  </table>
  <?php 
if(!empty($_POST['temp']))
{
	$d
	?>
  <br>
  <form action="" method="post" name="insert">
     <input type='hidden' name='id_prod' value='<?PHP echo $_POST['id_prod'] ?>'>
     <input type='hidden' name='id_tama' value='<?PHP echo $_POST['id_tama'] ?>'>
     <input type='hidden' name='exad_colo' value='<?PHP echo $_POST['exad_colo'] ?>'>
    <?php
  if(!empty($_GET['idet_clie']))
  {
	 echo ' <input type="hidden" formmethod="get" name="idet_clie" value="'.substr($_GET['idet_clie'],0,strlen($_GET['idet_clie'])-1).'">';
  }
  ?>
    <table width="800" border="0" cellspacing="2" cellpadding="1" >
      <tr class="col_title">
        <td>CANTIDAD </td>
        <td>PRECIO</td>
        <td>BSF</td>
        <td>TOTAL</td>
      </tr>
      <tr >
        <td><script>
				  costo=<?php echo $med['cost_tama']  ?>;
				  </script>
          <input type="text" name="cant_orde" id="cantidad" placeholder="cantidad"></td>
        <th id="costo"> <select name="precio">
            <?php
			
			  $conf=$database->config();
			  ?>
            <option value="0"></option>
            <option value="<?php echo $conf['precio1']?>">PRECIO1</option>
            <option value="<?php echo $conf['precio2']?>">PRECIO2</option>
            <option value="<?php echo $conf['precio3']?>">PRECIO3</option>
            <option value="otro">OTRO</option>
          </select>
          <input type="text" name="otro_precio" id="otro_precio" style="display:none;" placeholder="">
        </th>
        <td  ><div  id="precio" ></div>
          <div id="otro_pre" class="edit"></div></td>
        <td align="center" id="tprecio"></td>
      </tr>
    </table>
    <button class="submit" type="submit" name="nent" value="1">Enviar</button>
  </form>
  <?PHP	   
}

?>
  <br>
  <br>
  <br>
  <h3>NOTA DE ENTREGA </h3>
  <table width="800" border="0" cellspacing="2" cellpadding="1"   id="l_precios">
    <tr class="col_title">
      <td width="68" scope="col"  >CODIGO
        </th>
      <td  width="480"  scope="col"  >DESCRIPCION </td>
      <td  width="100"  scope="col"  >COLOR </td>
      <td  width="100"  scope="col"  >MEDIDA </td>
      <td  width="100"  scope="col"  >PRECIO </td>
      <td  width="100"  scope="col"  >CANTIDAD </td>
      <td width="50" scope="col" >ACCION </td>
    </tr>
    <?php

$database->consulta(PRODUCTOS::TEMP_ENTREGA);
for($i=0;$campo=$database->result();$i++)
{
	if($i%2==0)
	$row_act=' row_act';
	else
	$row_act='';
	
	echo  "<tr class='col_hov $row_act' id='$campo[id_temp]'>
	<th >$campo[codi_clpr]$campo[id_prod]</th>
	<th >$campo[desc_prod]  $campo[desc_marc] $campo[desc_mode] </th>
	 <th style='color:#$campo[exad_colo];'>$campo[desc_colo]</th>
	<th >$campo[codi_umed] $campo[medi_tama]</th>
	<th >".fmt_num($campo['cost_orde'])."</th>
	<th >$campo[cant_orde]</th>
	<th ><a href='$campo[id_temp]' class='elimina_coti'><div class='elimina actions'></div></A></th>
	</tr>";
}

?>
  </table>
 
</div>
