<?php
require_once("../clases/config_orveinca.php");
$buff= new DocumentBuffer(true,true,true);
$database= new PRODUCTOS();
$Session= new SESSION(false);
if(!empty($_POST['l_precios']))
{
	$var_get='';
	if(!empty($_POST['extern_get']))
		$var_get=$_POST['extern_get'];
	$root='';
	if(!empty($_POST['root']))
		$root=$_POST['root'];
	if(!empty($_POST['extern']))
	{
		$action_anc=70;
	}else
	{
		$action_anc=30;
	}
	$LIMIT="";
	echo '     <tr class="col_title">
        <td width="60" scope="col" >CODIGO</th>
       <td width="230" scope="col">DESCRIPCION</th>
	  <td width="150" scope="col">COLOR </th>
	  <td width="100" scope="col">TAMAÑO </th>

	   <td width="30" scope="col">CANTIDAD </th>

       ';
	if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
	{ 
		echo ' <td width="'.$action_anc.'" scope="col"  class="precios_action">ACCION</th>';
	}
	echo "</tr>";
	if(empty($_POST['opcion']) && !empty($_POST['codi_clpr']))
	{
		$sql="codi_clpr='$_POST[codi_clpr]'";


	}elseif(!empty($_POST['opcion']) && ($_POST['opcion']=='desc_prod'  || $_POST['opcion']=='id_prod' || $_POST['opcion']=="desc_marc" || $_POST['opcion']=="desc_mode" || $_POST['opcion']=="id_inve"))
	{
		$LIMIT=" LIMIT 0,20";
		if($_POST['codi_clpr']!=NULL)
		{
			$sql = "($_POST[opcion] $_POST[like] '$_POST[texto]') and codi_clpr='$_POST[codi_clpr]'";
		}else
		{
			$sql = "($_POST[opcion] $_POST[like] '$_POST[texto]')";
		}
		$resul=$database->consulta("SELECT * FROM inventario",$sql,' codi_clpr');


	}elseif(!empty($_POST['opcion']) && $_POST['opcion']=='all')
	{
		$texto=  substr($_POST['texto'],1,strlen($_POST['texto']));
		$texto=  substr($texto,0,strlen($texto)-1);
		$campos_busq=[""=>'codi_clpr','id_prod','desc_prod','desc_mode','desc_marc','desc_colo','codi_umed','medi_tama'];

		$resul=$database->busquedas_sql("SELECT inventario.* FROM inventario",$texto,$campos_busq);
	}
	if(empty($_POST['opcion']) && empty($_POST['codi_clpr']) && empty($_POST['texto']))
	{
		$sql=NULL;
		$resul=$database->consulta("SELECT * FROM inventario",$sql,' codi_clpr');
	}



	$is_row_act=false;
	$row_act;
	if(!$database->error)
	while($campo=$resul->fetch_array())
	{
		$existencia=$campo['existencia'];
	
			if($is_row_act)
				$row_act=' row_act';
			else
				$row_act='';
			$is_row_act=!$is_row_act;

			echo "<tr class='$row_act col_hov'>
    <th scope=col>$campo[codi_clpr]$campo[id_prod]</th>
    <th scope=col>$campo[desc_prod]  $campo[desc_marc] $campo[desc_mode] </th>";


			echo "
		 <th style='color:$campo[exad];'>$campo[desc_colo]</th>
		  <th>$campo[codi_umed] $campo[medi_tama]</th>";

			echo " 
	 	<th>$existencia</th>
		";
			if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__){ 
				echo "<th  scope=col class='precios_action'>
	 <a href= '../lista%20de%20precios/info.php?id_prod=".$campo['id_prod']."&amp;iframe=true&amp;width=640&amp;height=430&amp;' data-gal='prettyPhoto[iframe]' >
	 <div class='buscar1 mas_info' ></div></a>";


			}	
			if(!empty($_POST['extern']))
			{
				echo "
			 <form action='' method='post' name='$campo[id_prod]'>
    <input type='hidden' name='id_prod' value='$campo[id_prod]'>
     <input type='hidden' name='id_tama' value='$campo[id_tama]'>
     <input type='hidden' name='exad_colo' value='$campo[exad]'>
	 <button name='temp' value='1'>enviar</button>
    </form>"; 
			}	
			echo "</th></tr>";
		

	}
	//echo "<tr><th></th><th>".$database->sql.$database->errores."</pre>$_POST[extern]ASS</th></tr>";
	echo  '<script type="text/javascript">
		$(document).ready(function() { 
			$(".lightbox").append("<span></span>");
			$("a[data-gal^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
			';
	if($database->error())
	{
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}
	echo "
			$('.mas_info').tics('MAS INFORMACION DEL PRODUCTO');
			$('.editar').tics('EDITAR LA INFORMACION DEL PRODUCTO');
			$('.eliminar_id').tics('ELIMINAR EL PRODUCTO DEL SISTEMA');
			$('.mas_info').tics('MAS INFORMACION DEL PRODUCTO');
	";
	echo '
		}); 
</script> 
';
}
if(!empty($_POST['del_orde_id']) && !empty($_POST['id_temp']))
{
	$buff->SetAutoMin(false);
	if(!$database->consulta("DELETE FROM tem_orden_compra where id_temp='".$_POST['id_temp']."'"))
	{
		echo "ERROR ALELIMINAR EL PRODUCTO DE LA NOTA DE ENTREGA";
	}
}
if(!empty($_POST['del_entr_id']) && !empty($_POST['id_temp']))
{
	$buff->SetAutoMin(false);
	if(!$database->consulta("DELETE FROM tem_nent_prod where id_temp='".$_POST['id_temp']."'"))
	{
		echo "ERROR ALELIMINAR EL PRODUCTO DE LA NOTA DE ENTREGA";
	}
}

?>