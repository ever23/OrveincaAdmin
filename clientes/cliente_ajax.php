<?php
require_once("../clases/config_orveinca.php");
$buffer= new DocumentBuffer(true,true,false);
$database= new CLIENTES();
$Session= new SESSION(false);
if(!empty($_POST['text']))
{
	$sql="";
	echo ' <tr class="col_title">
          <td width="230" scope="col" >RASON SOCIAL</td>
          <td width="89" scope="col" >RIF</td>
          <td width="360" scope="col">DIRECCION</td>
          <td width="105" scope="col">CONTACTO</td>';

	if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
	{ 
		echo ' <td width="70" scope="col"  >°</td>';
	}
	echo "</tr>";
	if($_POST['text']!="all")
	{
		if(!empty($_POST['opcion']) && !empty($_POST['like']))
			if($_POST['opcion']!='')
		{
			if($_POST['opcion']=='contacto')
			{
				$database->busquedas_sql("select info_clientes.* from info_clientes",$_POST['text'],['nom1_cont','nom2_cont','ape1_cont','ape2_cont','ci_cont']);

			}elseif($_POST['opcion']=='vendedor')
			{
				$database->busquedas_sql("select info_clientes.* from info_clientes",$_POST['text'],['nom1_empl','nom2_empl','ape1_empl','ape2_empl','ci_empl']);
			}elseif($_POST['opcion']=='NULL')
			{
				$database->busquedas_sql("select info_clientes.* from info_clientes",$_POST['text'],[""=>'codi_tide','idet_clie','nomb_clie','nom1_empl','nom2_empl','ape1_empl','ape2_empl','ci_empl','nom1_cont','nom2_cont','ape1_cont','ape2_cont','ci_cont']);
			}
			else
			{
				$database->busquedas_sql("select info_clientes.* from info_clientes",$_POST['text'],[$_POST['opcion']]);
			}
		}
	}else
	{
		$database->consulta("select * from info_clientes");
	}


	$root='';
	if(!empty($_POST['root']))
		$root=$_POST['root'];
	$is_row_act=false;
	//$database->consulta(CLIENTES::CLIE,$sql);
	if(!$database->error())
	while($campo =$database->result())
	{
		if($is_row_act)
			$row_act=' row_act';
		else
			$row_act='';
		$is_row_act=!$is_row_act;
		echo "
	<tr class='col_hov $row_act'>
	<td width='230' scope='col'><div id='sql_where'  style='display:none;'>".$sql."</div>$campo[nomb_clie]</td>
	<td width='89' scope='col'>$campo[codi_tide]$campo[idet_clie]</td>
	<td width='350' scope='col'>$campo[dire_clie], PARROQUIA: $campo[desc_parr], MINICIPIO: $campo[desc_muni], ESTADO: $campo[desc_esta]
	 </td>
	<td width='105' scope='col'>".$campo['nom1_cont']." ".$campo['ape1_cont']."
	<br> 
	</td>
	";
		if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
		{
			echo "<td class='stabla' scope='col'  width='70'>
		 <a href='$campo[idet_clie]' class='elimina_clie'><div  class='elimina '></div></a></th>
		 <a href='editar_cliente.php?edit=basico&idet_clie=$campo[idet_clie]' >
		<div class='edit edit_clie'></div></a>
		<a href='".$root."cliente_allinfo.php?idet_clie=$campo[idet_clie]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='prettyPhoto[iframe]'><div class='buscar1 mas_info'></div></a>";
			if(!empty($_POST['extern']))
			{
				echo "<a href='$_POST[extern]?idet_clie=$campo[idet_clie]'><button>enviar</button></a>"; 
			}	
			echo"
		</td>"; 
		}
		echo"</tr>";
	}

	echo '
<script type="text/javascript">
		$(document).ready(function() {
			';
	if($database->error())
	{
		
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}

	echo "
		 $('.elimina_clie').click(function(e) {
			e.preventDefault();
        idet_clie=$(this).attr('href');
		$( '#errores:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
		$( '#error_div' ).html('<h3>ESTA SEGURO QUE DESEA ELIMINAR EL CLIENTE?</h3>');
		$( '.actions').css('display','none');
		$( '#errores' ).dialog({
			height:300,
			modal: true,
			buttons: {
				'ELIMINAR': function() {
					$( '#errores' ).dialog( 'close' );

					$().load_html('cliente_ajax.php',{ 'elimina_clie' : true,'idet_clie':idet_clie  },
					function(html){
						if(html=='')
						{
							$('#l_clientes').load_html('cliente_ajax.php',{'text' :' '});
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

	$(".edit_clie").tics("EDITAR LA INFORMACION DEL CLIENTE");
	$(".mas_info").tics("MAS INFORMACION DEL CLIENTE");		


	 $(".elimina_clie").tics("ELIMINAR EL CLIENTE");	


			$(".lightbox").append("<span></span>");
			$("a[data-gal^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
		}); 
</script> 
';		


}


if(!empty($_POST['elimina_clie']) && !empty($_POST['idet_clie']))
{
	if(!$database->eliminar_cliente($_POST['idet_clie']))
	{
		echo BD_ORVEINCA::GetErrorUser();
	}
}


if(!empty($_POST['id_vendedor']))
{

	$database->consulta("SELECT * FROM vendedores");
	echo "<select name='text' id='text'>
	 <option>VENDEDOR</option>";
	while($campo=$database->result())
	{
		echo "<option value='$campo[id]'>$campo[nombre] $campo[apellido]</option>";
	}
	echo "</select>
	";
}else
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



if(!empty($_POST['buscar_cliente']) && !empty($_POST['value']))
{
	$sql="nomb_clie like '%".$_POST['value']."%' or idet_clie like '%".$_POST['value']."%'";
	$database->consulta(CLIENTES::CLIE,$sql,NULL,'LIMIT 7');
	$is_row_act=true;
	while($campo=$database->result())
	{
		if($is_row_act)
			$row_act=' row_act';
		else
			$row_act='';
		$is_row_act=!$is_row_act;
		echo "<a><li class='clie_row $row_act' title='".$campo['idet_clie']."'>".$campo['codi_tide'].$campo['idet_clie']."   ".$campo['nomb_clie']."</li></a>";
	}
	echo "<script>
$(document).ready(function(e) {
   $('.clie_row').click(function(e) {

    $('input[name=idet]').attr('value',''+$(this).attr('title'));
	}); 
});
</script>";
}
if(!empty($_POST['VERIFICA_CLIE']) && !empty($_POST['idet_clie'])&& !empty($_POST['codi_tide']))
{
	header_json();
	$database->consulta("SELECT * FROM clientes WHERE idet_clie='".$_POST['idet_clie']."' and codi_tide='".$_POST['codi_tide']."'");
	if($database->result->num_rows==1)
	{
		$clie=$database->result();
		echo '{ "idet_exist":true, "idet_clie":"'.$clie['idet_clie'].'","codi_tide":"'.$clie['codi_tide'].'","nomb_clie":"'.$clie['nomb_clie'].'"}';
	}else
	{	echo '{ "idet_exist":false, "idet_clie":"'.$_POST['idet_clie'].'"}';

	}
}
?>
