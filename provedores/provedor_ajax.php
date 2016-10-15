<?php

require_once("../clases/config_orveinca.php");
$buff= new DocumentBuffer(true,true,true);
$database= new PROVEDORES();
$Session= new SESSION(false);
if(!empty($_POST['BUSCAR_PROVEEDOR']) && isset($_POST['text']))
{
	echo ' <tr class="col_title">
          <td width="230" scope="col" >RASON SOCIAL</td>
          <td width="89" scope="col" >RIF</td>
          <td width="360" scope="col">DIRECCION</td>
          <td width="105" scope="col">CONTACTO</td>';
	$sql=NULL;	
	if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
	{ 
		echo ' <td width="70" scope="col"  >°</td>';
	}
	echo "</tr>";
	$is_row_act=false;
	if($database->busquedas_sql("select info_proveedores.* from info_proveedores",$_POST['text'],[''=>'codi_tide','idet_prov','nomb_prov','nom1_cont','ape1_cont']))
	while($campo =$database->result())
	{
		if($is_row_act)
			$row_act=' row_act';
		else
			$row_act='';
		$is_row_act=!$is_row_act;
		echo "
	<tr class='col_hov $row_act' id='$campo[idet_prov]'>

	<td  scope='col'>$campo[nomb_prov]</td>
	<td scope='col'>$campo[codi_tide]$campo[idet_prov]</td>
	<td  scope='col'>$campo[dire_prov], PARROQUIA: ".$campo['desc_parr'].", MUNICIPIO: ".$campo['desc_muni'].", ESTADO: ".$campo['desc_esta']."
	 </td>
	<td  scope='col'>".$campo['nom1_cont']." ".$campo['ape1_cont']."

	</td>
	";
		if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
		{

			echo "<td class='stabla' scope='col'  width='70'>
		 <a href='$campo[idet_prov]'  class='elimina_prov' ><div  class='elimina actions'></div></a>

		 <a href='editar_provedor.php?edit=basico&idet_prov=$campo[idet_prov]' >
		<div class='edit edit_prov actions'></div></a>
		<a href='provedor_allinfo.php?idet_prov=$campo[idet_prov]&amp;iframe=true&amp;width=640&amp;height=435&amp;' class='lightbox-image'  data-gal='prettyPhoto[iframe]'><div class='buscar1 mas_info actions'></div></a>";
			if(!empty($_POST['extern']))
			{
				echo "<a href='$_POST[extern]?idet_prov=$campo[idet_prov]'><button>enviar</button></a>"; 
			}	
			echo"
		</td>"; 
		}
		echo"</tr>";
	}

	echo '
<script type="text/javascript">
		$(document).ready(function() {';
	if($database->error())
	{
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}

	echo "

		 $('.elimina_prov').click(function(e) {
			e.preventDefault();
        idet_prov=$(this).attr('href');
		$( '#errores:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
		$( '#error_div' ).html('<h3>ESTA SEGURO QUE DESEA ELIMINAR EL PROVEDOR?</h3><table> '+$('#'+idet_prov).html()+'</table>');
		$( '.actions').css('display','none');
		$( '#errores' ).dialog({

			height:300,
			modal: true,
			buttons: {
				'ELIMINAR': function() {
					$( '#errores' ).dialog( 'close' );
					$( '.actions').css('display','block');
					$().load_html('provedor_ajax.php',{ 'elimina_prov' : true,'idet_prov':idet_prov  },
					function(html){
						if(html=='')
						{
							$('#l_proved').load_html('provedor_ajax.php',{'text' :' '});
						}else
						 {
							 $( '#errores' ).attr('title','ERROR');
							  error('ERROR',html)
						 }


					 });

				},
				Cancel: function() {
					$( '#errores' ).dialog( 'close' );
					$( '.actions').css('display','block');
				}
			}
		});

    });";

	echo '

	$(".edit_prov").tics("EDITAR LA INFORMACION DEL PROVEDOR");
	$(".mas_info").tics("MAS INFORMACION DEL PROVEDOR");
	$(".elimina_prov").tics("ELIMINAR EL PROVEDOR");

	$(".lightbox").append("<span></span>");
	$("a[data-gal^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
		}); 
</script> 
';		
}

if(!empty($_POST['elimina_prov']) && !empty($_POST['idet_prov']))
{
	if(!$database->eliminar_provedor($_POST['idet_prov']))
	{
		echo BD_ORVEINCA::GetErrorUser();
	}
}

if(!empty($_POST['contacto']))
{

	$database->consulta("SELECT * FROM contacto");
	echo "<select name='text'  id='text' >
	 <option>CONTACTO</option>";
	while($campo=$database->result())
	{
		echo "<option value='$campo[id]'>$campo[nombre] $campo[apellido]</option>";
	}
	echo "</select>
	";}



if(!empty($_POST['buscar_proveedor']) && !empty($_POST['value']))
{
	$sql="nomb_prov like '%".$_POST['value']."%' or idet_prov like '%".$_POST['value']."%'";
	$database->consulta(PROVEDORES::PROV,$sql,NULL,'LIMIT 7');
	$is_row_act=true;
	while($campo=$database->result())
	{
		if($is_row_act)
			$row_act=' row_act';
		else
			$row_act='';
		$is_row_act=!$is_row_act;
		echo "<a><li class='prov_row $row_act' title='".$campo['idet_prov']."'>".$campo['codi_tide'].$campo['idet_prov']."   ".$campo['nomb_prov']."</li></a>";
	}
	echo "<script>
$(document).ready(function(e) {
   $('.prov_row').click(function(e) {

    $('input[name=idet]').attr('value',''+$(this).attr('title'));
	}); 
});
</script>";
}

if(!empty($_POST['VERIFICA_PROV']) && !empty($_POST['idet_prov'])&& !empty($_POST['codi_tide']))
{
	$buff->SetTypeMin('json');
	$database->consulta("SELECT * FROM provedores WHERE idet_prov='".$_POST['idet_prov']."' and codi_tide='".$_POST['codi_tide']."'");
	if($database->result->num_rows==1)
	{
		$pro=$database->result();
		echo '{ "idet_exist":true, "idet_prov":"'.$pro['idet_prov'].'", "nomb_prov":"'.$pro['nomb_prov'].'","codi_tide":"'.$pro['codi_tide'].'"}';
	}else
	{	echo '{ "idet_exist":false, "idet_prov":"'.$_POST['idet_prov'].'"}';

	}
}
?>