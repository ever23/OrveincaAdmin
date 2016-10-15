<?php
require_once("../clases/config_orveinca.php");
$buff= new DocumentBuffer(true,true,true);
$database= new EMPLEADOS();
$Session= new SESSION(false);
if(!empty($_POST['text']))
{
	$sql=NULL;	

	if($_POST['text']!="")
	{
		if(!empty($_POST['opcion']) && !empty($_POST['like']))
			if($_POST['opcion']=='nom1_empl')
		{
			$sql=" (nom1_empl $_POST[like] '$_POST[text]') or (nom2_empl $_POST[like] '$_POST[text]') or (ape1_empl $_POST[like] '$_POST[text]') or (ape2_empl $_POST[like] '$_POST[text]')";	
		}else
			if($_POST['opcion']!='')
		{
			$sql="$_POST[opcion] $_POST[like] '$_POST[text]'";	
		}
	}else
	{
		$sql=NULL;	
	}

		$is_row_act=false;
		$database->consulta(EMPLEADOS::EMPL,$sql);
		//echo "<tr><td>".$database->error()."fff</td></tr>";
		while($campo =$database->result())
		{
			if($is_row_act)
				$row_act=' row_act';
			else
				$row_act='';
			$is_row_act=!$is_row_act;
			echo "
	<tr class='col_hov $row_act' id='$campo[ci_empl]'>
	<td width='89' scope='col'>$campo[ci_empl]</td>
	<td width='230' scope='col'>$campo[nom1_empl] $campo[nom2_empl]  $campo[ape1_empl]  $campo[ape2_empl]</td>
<td width='105' scope='col'>".$campo['emai_empl']." </td>

	 <td width='100' scope='col'>". strtoupper($campo['desc_carg'])."</td>
       <td width='100' scope='col'>". strtoupper($campo['desc_dept'])."</td>
	";
			if($Session->GetVar('permisos')==__AUTORIZATE_ADMIN__ || $Session->GetVar('permisos')==__AUTORIZATE_ROOT__)
			{
				echo "<td class='stabla' scope='col'  width='70'>
		 <a href='$campo[ci_empl]' class='elimina_empl'><div  class='elimina actions'></div></a>
		 <a href='editar_empleados.php?edit=basico&ci_empl=$campo[ci_empl]' >
		<div class='edit edit_vend actions'></div></a>
		<a href='empleados_allinfo.php?ci_empl=$campo[ci_empl]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='prettyPhoto[iframe]'><div class='buscar1 mas_info actions'></div></a>
		</td>
		"; 
			}
			echo"</tr>";
		}

		echo '
<script type="text/javascript">
		$(document).ready(function() {
			';
		if($database->error())
		{
			echo "error('ERROR','".OrveincaExeption::GetExeptionS()."');";
		}

		echo "
		 $('.elimina_empl').click(function(e) {
			e.preventDefault();
       var ci_empl=$(this).attr('href');
		$( '#errores:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
		$( '#error_div' ).html('<h3>ESTA SEGURO QUE DESEA ELIMINAR EL EMPLEADO?</h3>');
		$( '.actions').css('display','none');
		$( '#errores' ).dialog({

			height:300,
			modal: true,
			buttons: {
				'ELIMINAR': function() {
					$( '#errores' ).dialog( 'close' );
					$( '.actions').css('display','block');
					$().load_html('empleados_ajax.php',{ 'elimina_empl' : true,'ci_empl':ci_empl  },
					function(html){
						if(html=='')
						{
							$('#empleados').load_html('empleados_ajax.php',{'text' :' '});
						}
						 else
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

		$(".edit_empl").tics("EDITAR LA INFORMACION DEL EMPLEADO");		
		$(".mas_info").tics("MAS INFORMACION DEL EMPLEADO");	
		$(".elimina_vend").tics("ELIMINAR EL EMPLEADO");				

			$(".lightbox").append("<span></span>");
			$("a[data-gal^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
		}); 
</script> 
';		


	}

if(!empty($_POST['elimina_empl']) && !empty($_POST['ci_empl']))
{
	if(!$database->eliminar_empleado($_POST['ci_empl']))
	{
		echo OrveincaExeption::GetExeptionS();
	}
}
if(!empty($_POST['elimiar']) && !empty($_POST['codi_carg']))
{
	$buff->SetTypeMin('json');
	$database->consulta("DELETE FROM cargos WHERE codi_carg='".$_POST['codi_carg']."'");
	if(!$database->error())
	{
		echo '{"error":false}';
	}else
	{
		echo $database->result_array_json();
	}
	
}
if(!empty($_POST['elimiar']) && !empty($_POST['codi_dept']))
{
	$buff->SetTypeMin('json');
	$database->consulta("DELETE FROM departamen WHERE codi_dept='".$_POST['codi_dept']."'");
	if(!$database->error())
	{
		echo '{"error":false}';
	}else
	{
		echo $database->result_array_json();
	}
	
}
?>