<?php
require_once("../clases/config_orveinca.php");
$buff= new DocumentBuffer(true,true,true);
$database= new PRODUCTOS();
if(!empty($_POST['pedidos']))
{
	$root='';
	if(!empty($_POST['root']))
		$root=$_POST['root'];
	$LIMIT="";
	$sql='esta_pedi="P"';
	if(!empty($_POST['opcion']))
	{
		switch($_POST['opcion'])
		{
			case 'all':
			$sql="nomb_clie $_POST[like] '$_POST[texto]' or idet_clie $_POST[like] '$_POST[texto]' or nom1_empl $_POST[like] '$_POST[texto]' or nom2_empl $_POST[like] '$_POST[texto]' or ape1_empl $_POST[like] '$_POST[texto]' or ape2_empl $_POST[like] '$_POST[texto]' or empleados.ci_empl $_POST[like] '$_POST[texto]' or nume_pedi $_POST[like] '$_POST[texto]'";
			break;	
			case 'clie':
			$sql = "( nomb_clie $_POST[like] '$_POST[texto]' or idet_clie $_POST[like] '$_POST[texto]') ";
			break;
			case 'vend':
			$sql = "(nom1_empl $_POST[like] '$_POST[texto]' or nom2_empl $_POST[like] '$_POST[texto]' or ape1_empl $_POST[like] '$_POST[texto]' or ape2_empl $_POST[like] '$_POST[texto]' or empleados.ci_empl $_POST[like] '$_POST[texto]') ";
			break;
			default:
			$sql = "($_POST[opcion] $_POST[like] '$_POST[texto]') ";
			break;

		}
	}
	$is_row_act=false;

	$row_act;
	if($result=$database->consulta(PRODUCTOS::PEDIDOS,$sql," `pedidos`.`esta_pedi`  DESC",$LIMIT))
		while($campo =$result->fetch_array())
	{
		if($is_row_act)
			$row_act=' row_act';
		else
			$row_act='';
		$is_row_act=!$is_row_act;
		$cancel='';
		switch($campo['esta_pedi'])
		{
			case 'E':$estado="ENTREGADO";;break;
			case 'P':$estado="PENDIENTE";;break;
			case 'C':$estado="CANCELADO";$cancel='cancel';;break;
		}
		echo "<tr class='$row_act col_hov  $cancel' id='$campo[nume_pedi]'>
    <th scope=col>$campo[nume_pedi]</th>
    <th scope=col> 	<a href='../clientes/cliente_allinfo.php?idet_clie=$campo[idet_clie]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='info_cliente[iframe]'>
	$campo[nomb_clie] </a></th>
	<th scope=col><a href='../empleados/empleados_allinfo.php?ci_empl=$campo[ci_empl]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='info_vendedor[iframe]'>$campo[nom1_empl] $campo[ape1_empl]</a> </th>
	<th scope=col id='esta_pedi".$campo['nume_pedi']."' >$estado</th>
	<th  scope=col>$campo[fech_pedi]</th>
	<th>
	 <a href= 'info_pedido.php?nume_pedi=".$campo['nume_pedi']."&amp;iframe=true&amp;width=640&amp;height=430&amp;' data-gal='info_pedido[iframe]' >
	 <div class='buscar1 mas_info_pedi actions' ></div></a>";  
		if($campo['esta_pedi']=='P')
		{
			echo "<a href='entr_pedido.php?nume_pedi=".$campo['nume_pedi']."'><div class='entr'></div></a>
		  <a href='".$campo['nume_pedi']."' class='cancelar_pedi'><div class='elimina'></div></a>";
		}
		echo "
	</th>
	</tr>";

	}

	//	echo "<tr><th></th><th>".$database->sql.$database->errores."</th></tr>";
	echo  '<script type="text/javascript">
		$(document).ready(function(){ 
		';
	if($database->error())
	{
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}

	echo " $('.cancelar_pedi').click(function(e) {
			e.preventDefault();
        nume_pedi=$(this).attr('href');
		$( '#dialog:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CANCELAR PEDIDO');
		$( '#error_div' ).html('<h3>ESTA SEGURO QUE DESEA CANCELAR EL PEDIDO n°'+nume_pedi+'</H3>');

		$( '#errores' ).dialog({

			height:300,
			modal: true,
			buttons: {
				'ACEPTAR': function() {
					$( '#errores' ).dialog( 'close' );
					$().load_json('ajax_json.php',{ 'cancela_pedido' : true,'nume_pedi':nume_pedi  },
					function(json){ 
					if(!json.error)
					{
						$('#esta_pedi'+nume_pedi).html('CANCELADO');
						$('#'+nume_pedi).addClass('cancel');
					}
					else
					{
						$( '#errores' ).attr('title','ERROR');
						error('ERROR',json.error);
					}
					});
				},
				'CANCELAR': function() {
					$( '#errores' ).dialog( 'close' );
				}
			}
		});

    });";
	echo '
			$(".lightbox").append("<span></span>");
			$("a[data-gal^=\'info_pedido\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
			$("a[data-gal^=\'info_cliente\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
			$("a[data-gal^=\'info_vendedor\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});

			$("a[data-gal^=\'info_cliente\']").tics("INFORMACION DEL CLIENTE ");
			$("a[data-gal^=\'info_vendedor\']").tics("INFORMACION DEL VENDEDOR ");
			';
	echo "
			$('.entr').tics('ENTREGAR PEDIDO');
			$('.mas_info_pedi').tics('INFORMACION DENTALLADA DEL PEDIDO');
			$('.cancelar_pedi').tics('CANCELAR PEDIDO');
	";
	echo '
		}); 
</script> 
';
}

if(!empty($_POST['cancela_pedi_prod']) && !empty($_POST['id_pepr']) )
{
	$database->autocommit(false);
	if($database->consulta("UPDATE pedi_prod SET cant_entr=NULL WHERE id_pepr='".$_POST['id_pepr']."'"))
	{
		$database->consulta("SELECT * FROM pedi_prod WHERE  id_pepr='".$_POST['id_pepr']."'");
		$pedi=$database->result();
		$database->consulta("SELECT * FROM pedi_prod WHERE (cant_entr is NOT NULL ) and nume_pedi='".$pedi['nume_pedi']."'");
		if($database->result->num_rows==0)
		{
			if(!$database->consulta("UPDATE pedidos SET esta_pedi='C' WHERE nume_pedi='".$pedi['nume_pedi']."'"))
			{
				echo "ERROR INESPERADO AL CANCELAR  PEDIDO";
			}
		}else
		{
			$database->consulta("SELECT * FROM pedi_prod where cant_pedi!=cant_entr  and nume_pedi='".$pedi['nume_pedi']."'");
			if($database->result->num_rows==0)
			{
				if(!$database->consulta("UPDATE pedidos SET esta_pedi='E' WHERE nume_pedi='".$pedi['nume_pedi']."'"))
				{
					echo "ERROR INESPERADO AL entregar  PEDIDO";
				}
			}
		}
	}else
	{
		echo "ERROR INESPERADO AL CANCELAR EL PRODUCTO DEL PEDIDO";
	}
	if(!$database->error())
	{
		$database->commit();	
	}else
	{
		$database->rollback();
	}

}
if(!empty($_POST['iguala_pedi_prod']) && !empty($_POST['id_pepr']) )
{
    $buff->SetTypeMin('json');
    	$database->autocommit(false);
	 if($database->consulta("UPDATE pedi_prod SET cant_pedi=cant_entr WHERE id_pepr='".$_POST['id_pepr']."'"))
	{
         $database->consulta("SELECT * FROM pedi_prod WHERE  id_pepr='".$_POST['id_pepr']."'");
		$pedi=$database->result();
		
           $database->consulta("SELECT * FROM pedi_prod where (cant_pedi!=cant_entr AND (cant_entr is NOT NULL ))  and nume_pedi='".$pedi['nume_pedi']."'");
			if($database->result->num_rows==0)
			{
				if(!$database->consulta("UPDATE pedidos SET esta_pedi='E' WHERE nume_pedi='".$pedi['nume_pedi']."'"))
				{
                     echo '{"error":"ERROR INESPERADO AL entregar  PEDIDO"}';;
					
				}
			}
		
	}else
	{
         echo '{"error":"ERROR INESPERADO AL CANCELAR EL PRODUCTO DEL PEDIDO"}';;
	}
	if(!$database->error())
	{
		$database->commit();
        echo '{"error":false}'; 
	}else
	{
		$database->rollback();
         echo '{"error":"ERROR AL IGUALAR LAS CANTIDADES"}'; 
	}
}