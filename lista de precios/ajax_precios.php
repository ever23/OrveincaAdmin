<?php
require_once("../clases/config_orveinca.php");
$buff= new DocumentBuffer(true,true,true);
$database= new PRODUCTOS();
$Session= new SESSION(false);
if(!autenticate())
{
	exit;
}
if(!empty($_POST['l_precios']))
{
	$root='';
	if(!empty($_POST['root']))
		$root=$_POST['root'];
	$LIMIT="";
	echo '     <tr class="col_title">
        <td width="40" scope="col" >CODIGO</th>
       <td width="480" scope="col">DESCRIPCION</th>
       ';
	if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__){ 
		echo ' <td width="80" scope="col"  class="precios_action">ACCION</th>';
	}
	echo "</tr>";

	if(empty($_POST['opcion']))
	{
		$sql=" codi_clpr='$_POST[codi_clpr]'";
		$consulta=PRODUCTOS::PROD;
		$order="producto.id_prod ASC";
		//$database->consulta(PRODUCTOS::$PROD1,$sql);
		$database->consulta($consulta,$sql,$order,$LIMIT);

	}elseif(($_POST['opcion']=='desc_prod'  || $_POST['opcion']=='id_prod' || $_POST['opcion']=="desc_marc" || $_POST['opcion']=="desc_mode"))
	{
		$LIMIT=" LIMIT 0,20";
		$consulta=PRODUCTOS::PROD;
		$order="producto.id_prod ASC";
		$sql = "($_POST[opcion] $_POST[like] '$_POST[texto]')  ".(!empty($_POST['codi_clpr']) && $_POST['codi_clpr']!=''?"and codi_clpr='$_POST[codi_clpr]'":"")."";
		$database->consulta($consulta,$sql,$order,$LIMIT);
	}elseif($_POST['opcion']=='all')
	{
		//$database->busqueda_prod($_POST);

		$texto=  substr($_POST['texto'],1,strlen($_POST['texto']));
		$texto=  substr($texto,0,strlen($texto)-1);
		$campos_busq=[""=>'codi_clpr','id_prod','desc_prod','desc_mode','desc_marc','desc_clpr'];
		//	$database->busquedas_sql($texto,$campos_busq,$tablas,$SELECT,"",' LIMIT 30');
		$database->busquedas_sql(PRODUCTOS::PROD,$texto,$campos_busq,(!empty($_POST['codi_clpr']) && $_POST['codi_clpr']!=''?"and codi_clpr='".$_POST['codi_clpr']."'":""),30);
	}

	$is_row_act=false;
	$row_act;

	if(!$database->error)
		while($campo = $database->result())
	{
		if($is_row_act)
			$row_act=' row_act';
		else
			$row_act='';
		$is_row_act=!$is_row_act;

		echo "<tr class='$row_act col_hov' id='$campo[id_prod]'>
    <th scope=col>$campo[codi_clpr]$campo[id_prod]</th>
    <th scope=col>$campo[desc_prod]  $campo[desc_marc] $campo[desc_mode] ";
		echo "</th>
	";
		if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__){ 
			echo "<th width='80' scope='col'  class='precios_action'>
	 <a href= '".$root."info.php?id_prod=".$campo['id_prod']."&amp;iframe=true&amp;width=640&amp;height=430&amp;' data-gal='prettyPhoto[iframe]' >
	 <div class='buscar1 mas_info actions' ></div></a>
	 <a href='".$root."editar.php?id_prod=$campo[id_prod]' ><div class='edit editar actions'></div></a>
	 <a href='$campo[id_prod]' class='eliminar_id' ><div  class='elimina actions'></div></a>";
		}	
		if(!empty($_POST['extern']))
		{
			echo "<a href='$_POST[extern]?id_prod=$campo[id_prod]'><button>enviar</button></a>"; 
		}	
		echo "</th></tr>";
	}

	//	echo "<tr><th></th><th>".$database->sql.$database->error."</pre>$_POST[extern]ASS</th></tr>";
	echo  '<script type="text/javascript">
		$(document).ready(function() { 
		';
	if($database->error())
	{
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}
	echo " $('.eliminar_id').click(function(e) {
			e.preventDefault();
        id_prod=$(this).attr('href');
		$( '#dialog:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
		$( '#error_div' ).html('<h3>ESTA SEGURO QUE DESEA ELIMINAR EL PRODUCTO?</h3><table> '+$('#'+id_prod).html()+'</table>');
		$( '.actions').css('display','none');
		$( '#errores' ).dialog({

			height:300,
			modal: true,
			buttons: {
				'ELIMINAR': function() {
					$( '#errores' ).dialog( 'close' );
					$().load_json('ajax_precios.php',{ 'elimina_prod' : true,'id_prod':id_prod  },
					function(json){ 
					if(!json.error)
					{
						$('#'+id_prod).fadeOut(); 
					}
					else
					{
						$( '#errores' ).attr('title','ERROR');
						error('ERROR',json.error);
					}



					});

					$( '.actions').css('display','block');
				},
				Cancel: function() {
					$( '#errores' ).dialog( 'close' );
					$( '.actions').css('display','block');
				}
			}
		});

    });";
	echo '
			$(".lightbox").append("<span></span>");
			$("a[data-gal^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
			';
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
if(!empty($_POST['marca']))
{
	if($_POST['marca']=='otra_mark')
	{
		echo '<input name="new_modelo" placeholder="otro" type="text">';
	}
	else{
		$database->consulta("select * from modelos where id_marc='$_POST[marca]';");

		echo '
	<select name="modelo" id="modelo_selet"  >';
		while($campo=$database->result())
		{
			echo "<option value='$campo[id_mode]'>$campo[desc_mode]</option>";
		}
		echo "
  <option value='otro_modelo' >OTRA</option>
	</select>

	";

	}
}

if(!empty($_POST['u_medida']))
{

	$opction="";
	$order='medi_tama';
	$order='id_tama';
	$ind=$_POST['ind'];
	if($_POST['u_medida']=='t')
	{
		$database->consulta("SELECT * FROM tamanos where codi_umed='$_POST[u_medida]' ORDER BY $order");
		$select1= '<div id="div_tamano_ini'.$ind.'">desde <select name="tamano_ini['.$ind.']" id="tamano_ini'.$ind.'" >';
		$select2= '<div id="div_tamano_end'.$ind.'">hasta <select name="tamano_end['.$ind.']" id="tamano_end'.$ind.'" >';
		while($campo=$database->result())
		{
			$opction.= "<option value='$campo[id_tama]'>$campo[medi_tama]</option>";
		}
		echo $select1.$opction;

		echo "</select></div>";

		echo $select2.$opction."";

		echo "</select></div>";

	}elseif($_POST['u_medida']=='-')
	{
		$database->consulta("SELECT * FROM tamanos where codi_umed='$_POST[u_medida]'");
		$no_med=$database->result();
		echo '<div id="div_tamano_ini'.$ind.'"><input name="tamano_ini['.$ind.']" type="hidden" value="'.$no_med['id_tama'].'"></div><div id="div_tamano_end'.$ind.'"> <H4>NO POSEE</H4><input name="tamano_end['.$ind.']" type="hidden" value="'.$no_med['id_tama'].'"></div>';
		exit;
	}elseif($_POST['u_medida']=='cm2')
	{
		echo '<div id="div_tamano_ini'.$ind.'">
		<input type="text" name="otro_tam_ini['.$ind.']" id="numero" placeholder="medida1" >
		</div>
		<div id="div_tamano_end'.$ind.'"> 
		<input type="text" name="otro_tam_end['.$ind.']" id="numero"placeholder="medida2" >
		</div>';

	}
	else
	{
		echo '<div id="div_tamano_ini'.$ind.'">
		<input type="number" name="otro_tam_ini['.$ind.']" id="numero" min="0" "1" placeholder="medida1" >
		</div>
		<div id="div_tamano_end'.$ind.'"> 
		<input type="number" name="otro_tam_end['.$ind.']" id="numero" min="0" "1" placeholder="medida2" >
		</div>';
	}

}
if(!empty($_POST['elimina_cp']))
{
	$buff->SetAutoMin(false);
	if(!$database->consulta("DELETE FROM tama_prod WHERE id_tmpd='$_POST[elimina_cp]'"))
	{
		echo OrveincaExeption::GetExeptionS();
	}
}

if(!empty($_POST['del_coti_id']) && !empty($_POST['id_coti']) )
{
	$buff->SetAutoMin(false);
	if(!$database->consulta("DELETE FROM temp_coti_prod where id_coti='$_POST[id_coti]'"))
	{
		echo OrveincaExeption::GetExeptionS();
	}
}

if(!empty($_POST['elimina_prod']) && !empty($_POST['id_prod']))
{
	$buff->SetTypeMin('json');
	if(!$database->eliminar_prod($_POST['id_prod']))
	{
		echo '{"error":"'.OrveincaExeption::GetExeptionS().'"}';	
	}else
	{
		echo '{"error":false}';	
	}
}


if(!empty($_POST['elimina_img']) && !empty($_POST['id_prod']) )
{
	$buff->SetTypeMin('json');
	$database->autocommit(false);
	$database->consulta(PRODUCTOS::PROD,"id_prod='".$_POST['id_prod']."'");
	if(!$database->error())
		$CAMPO=$database->result();
	if($database->consulta("UPDATE producto SET id_imag=NULL WHERE id_prod='".$_POST['id_prod']."'"))
	{
		$database->consulta("DELETE FROM imagenes WHERE id_imag='".$CAMPO['id_imag_p']."'");
	}
	if(!$database->error())
	{
		$database->commit();
		echo '{"error":false,"src":"../mysql/img.php"}';	
	}else
	{
		$database->rollback();	
		echo '{"error":"'.$database->error().'","src":"../mysql/img.php?id='.$CAMPO['id_imag_p'].'"}';	
	}
}
if(!empty($_POST['costo']) && !empty($_POST['id_tmpd']))
{
	$buff->SetTypeMin('json');
	$TIME= new TIME();
	$database->consulta(PRODUCTOS::PROD_TC,"id_tmpd='".$_POST['id_tmpd']."'");
	$coto=$database->result();
	if($coto['cost_tama']==$_POST['costo'])
	{
		$config=$database->config();
		echo '{"error":false,"costofmt":"'.fmt_num($_POST['costo']).'",
		"costo":"'.$_POST['costo'].'",
		"precio1":"'.fmt_num(($config['precio1']*$_POST['costo'])+$_POST['costo']).'",
		"precio2":"'.fmt_num(($config['precio2']*$_POST['costo'])+$_POST['costo']).'",
		"precio3":"'.fmt_num(($config['precio3']*$_POST['costo'])+$_POST['costo']).'",
		"fecha":"'.$coto['fech_tama'].'"}';

	}else
	{
		if($database->consulta("UPDATE tama_prod SET cost_tama='".$_POST['costo']."',fech_tama='".$TIME->fecha_hora("actual")."' WHERE id_tmpd='".$_POST['id_tmpd']."'"))
		{
			$config=$database->config();
			echo '{"error":false,"costofmt":"'.fmt_num($_POST['costo']).'",
		"costo":"'.$_POST['costo'].'",
		"precio1":"'.fmt_num(($config['precio1']*$_POST['costo'])+$_POST['costo']).'",
		"precio2":"'.fmt_num(($config['precio2']*$_POST['costo'])+$_POST['costo']).'",
		"precio3":"'.fmt_num(($config['precio3']*$_POST['costo'])+$_POST['costo']).'",
		"fecha":"'.$TIME->fecha_hora("actual").'"}';
		}else
		{
			echo '{"error":"'.OrveincaExeption::GetExeptionS(true).'","costo":"'.$_POST['costo'].'"}';
		}
	}



}
?>