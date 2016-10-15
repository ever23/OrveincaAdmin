<?php
require_once("../clases/config_orveinca.php");
$database= new PRODUCTOS();
$Session= new SESSION(false);
if($_POST)
{
	if($database->temp_contizacion($_POST))
	{
		redirec($_SERVER['PHP_SELF']);
	}
}
if(!empty($_GET['desechar']))
{
	$database->consulta("TRUNCATE TABLE `temp_coti_prod`");
}
$html= new HTML();
$html->prettyPhoto();
$html->addlink_js("{src}busqueda.min.js")
?>
<script type="text/javascript">

$(document).ready(function(e) {
    <?php
	 if(!empty($_GET['id_prod']))
	 {
		 echo "	$(window).load(function(e) {
        	star_load();
    });
	$('#l_precios').load('../lista%20de%20precios/ajax_precios.php',{'opcion':'id_prod',
	'texto' :'$_GET[id_prod]','like'  :'=','l_precios':true,
	'root'  : '../lista%20de%20precios/',},function(){stop_load();});
	";
	 }
	 ?>
	
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
		'root'  :'../lista%20de%20precios/',
		'texto' :slike+text+slike,
		'like'  :like, 
		'l_precios':true
		};
		if(text.length!=0)
		{
			
			$('#l_precios').load_html('../lista%20de%20precios/ajax_precios.php',_POST);
		}else
		{
			$('#l_precios').load_html('../lista%20de%20precios/ajax_precios.php',{ l_precios:true});
		}
	 });
	$('.elimina_coti').click(function(e)
	{
		e.preventDefault();
       var id_coti=$(this).attr('href');
		$( '#dialog:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
		$( '#error_div' ).html('ESTA SEGURO DE ELIMINAR EL PRODUCTO DE LA COTIZACION?<table> '+$('#'+id_coti).html()+'</table>');
		$( '.actions').css('display','none');
		$( '#errores' ).dialog({
			
			height:300,
			width:500,
			modal: true,
			buttons: {
				'ELIMINAR': function() {
					$( '#errores' ).dialog( 'close' );
				
					$('#'+id_coti).load_html('../lista%20de%20precios/ajax_precios.php',{ 'id_coti':id_coti,del_coti_id:true},
					function(html){
						$('#'+id_coti).fadeOut();
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
<div align="center" class="conten_ico" > <a href="busqueda.php?desechar=true">
  <div class="desechar" id="desechar_coti"></div>
  </a>
  <?php
if(!empty($_GET['id_prod']))
{
	echo "<a href='".$_SERVER['PHP_SELF']."'><div class='atras'></div></a>";
}

?>
</div>
<div align="center" >
  <h1>INSERTAR EN COTIZACION </h1>
  <h2><a href='../clientes/info_cliente.php?redirec=../cotizacion/cotizacion.php'>SIQUIENTE</A></h2>
  <?php
  echo $database->sql;
  if(empty($_GET['id_prod']))
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
  <?php 
if(!empty($_GET['id_prod']))
{
	?>
  <br>
  <form action="" method="post" >
    <input type="hidden" name="id_prod"  value="<?php echo $_GET['id_prod'] ?>">
    <table width="800" border="0" cellspacing="2" cellpadding="1" >
      <tr class="col_title">
        <td width="194">MEDIDAS </td>
        <td width="146">COLOR </td>
        <td width="126">CANTIDAD </td>
        <td width="99">PRECIO</td>
        <td width="113">BSF</td>
        <td width="96">TOTAL</td>
      </tr>
      <tr >
        <th class="col_hov" id="tamano_th"> <?php
	   $medida=$costo=$codi_umed=$desc_umed='';
	   $input=false;
	 
	  $tam=$tam1=$tam2=$cost=array();
	 
	 $tamanos=$database->consulta(PRODUCTOS::PROD_TC,"id_prod='$_GET[id_prod]'", " `t1`.`medi_tama` ASC");
	 
	
	  while($tamano=  $tamanos->fetch_array())
	  {
		  $tam1[]=$tam[]=$tamano['medi_tama1'];
		  $tam2[]=$tam[]=$tamano['medi_tama2'];
		  $cost[]=$tamano['cost_tama'];
		  
		  $codi_umed=$tamano['codi_umed'];
		  $desc_umed=$tamano['desc_umed'];
		 if($tamano['codi_umed']=='-')
		 {
			  $database->consulta("SELECT * FROM tamanos WHERE codi_umed='$tamano[codi_umed]'");
			  $tallas=$database->result();
			 $medida=" <option title='$tamano[cost_tama]' value='$tallas[id_tama]' selected>no posee</option>";
			 $costo=$tamano['cost_tama'];
			break;
		 }elseif($tamano['codi_umed']=='t')
	  	{
			 if($tamano['medi_tama1']=='-')
			 {
				  $where="";
			 }elseif($tamano['medi_tama2']=='-')
			 {
				$where="and (id_tama=$tamano[id_tama1])"; 
			 }else
			 {
				 $where="and (id_tama>=$tamano[id_tama1] and id_tama<=$tamano[id_tama2]) "; 
			 }
			 $database->consulta("SELECT * FROM tamanos WHERE codi_umed='t'   $where ORDER BY id_tama");
			 while($tallas=$database->result())
			 {
				  $medida.=" <option title='$tamano[cost_tama]' value='$tallas[id_tama]'>".$tallas['medi_tama']."</option>";
			 }
	 	 }else
		 {
			$input=true;
			 if($tamano['medi_tama1']=='-')
			 {
				   $costo=$tamano['cost_tama'];
				 break;
			 }elseif($tamano['medi_tama2']=='-')
			 {
				
				$tamano['medi_tama2']=$tamano['medi_tama1'];
			 }
		 }
	  }
	  $display='none';
	  echo $desc_umed;
	  if(!$input)
	  {
		  echo "<select name='id_tama' id='medida'> <option  value='NULL'></option>".$medida;
		  if($codi_umed!='t' && $codi_umed!='-')
		  echo "<option  value='otro'>OTRO</option>";
		  echo "</select>"; 
	  }else
	  {
		  $display='block';
		  echo " <input type='hidden'  name='id_tama' value='otro'>";
	  }
	$script='';
	$script.="var tam1=".$database->array_json($tam1).";";
	$script.="var tam2=".$database->array_json($tam2).";";
	$script.="var cost=".$database->array_json($cost).";";
	 
		if($codi_umed=='')
		{
			$html->add_error("ERROR EN LA BASE DE DATOS EL PRODUCTO NO POSEE REGISTRO ALGUNO DE MEDIDAS O TALLAS PORFAVOR EDITELO Y SELECCIONE UNA UNIDAD DE MEDIDA NO LO INSERTE O PROVOCARA UN ERROR EN EL SISTEMA ");
		}
	  ?>
          <script>
	   <?php echo $script?>
      costo=<?php echo ((float)$costo) ?>;
      </script>
          <?php
	  if($codi_umed=='cm2')
	  {
		 ?>
          <input type="text" name="otro_tamano" id="otro_tamano" value="0" style="display:<?php echo $display?>;">
          <?php 
	  }else{
	  ?>
          <input type="number" name="otro_tamano" id="otro_tamano" value="0" style="display:<?php echo $display?>;">
          <?php }?>
          <input type="hidden"  name="codi_umed" value='<?php echo $codi_umed?>'>
        </th>
        <th class="col_hov"><select name="exad_colo">
            <option  value="#">NUNGUNO</option>
            <?php
	  $database->consulta("SELECT * FROM colores ");
	  while($campo=$database->result())
	  {
		  echo " <option value='$campo[exad]'  style='color:$campo[exad];'>$campo[desc_colo]</option>";
	  }
	  ?>
            <option  value="otro">otro</option>
          </select>
          <div id="otro_color" style="display:none;">
            <input type="color" name="exad"  >
            <input type="text" name="desc_colo" placeholder="descripcion del color">
          </div></th>
        <td><input type="text" name="cant_coti" id="cantidad" placeholder="cantidad"></td>
        <th id="costo"> <select name="precio">
            <?php
			
			  $conf=$database->config();
			  ?>
            <option value="0"></option>
            <option value="<?php echo $conf['precio1']?>">PRECIO1</option>
            <option value="<?php echo $conf['precio2']?>">PRECIO2</option>
            <option value="<?php echo $conf['precio3']?>">PRECIO3</option>
           
          </select>
         
        </th>
        <th><div id="precio"><?php echo fmt_num(((float)$costo)) ?> bsf </div>
          <div id="otro_pre" class="edit"></div></th>
        <th align="center" id="tprecio"></th>
      </tr>
    </table>
    <button class="submit" type="submit" name="boton" value="">Enviar</button>
  </form>
  <?PHP	   
}

?>
  <br>
  <br>
  <br>
  <h3>EN COTIZACION </h3>
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

$database->consulta(PRODUCTOS::COTI_TMP);
for($i=0;$campo=$database->result();$i++)
{
	if($i%2==0)
	$row_act=' row_act';
	else
	$row_act='';
	
	echo  "<tr class='col_hov $row_act' id='$campo[id_coti]'>
	<th >$campo[codi_clpr]$campo[id_prod]</th>
	<th >$campo[desc_prod]  $campo[desc_marc] $campo[desc_mode] </th>
	 <th style='color:#$campo[exad_colo];'>$campo[desc_colo]</th>
	<th >$campo[codi_umed] $campo[medi_tama]</th>
	<th >".fmt_num($campo['prec_vent'])."</th>
	<th >$campo[cant_coti]</th>
	<th ><a href='$campo[id_coti]' class='elimina_coti'><div class='elimina actions'></div></A></th>
	</tr>";
}

?>
  </table>

</div>
