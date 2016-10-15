<?php 
require_once("../clases/config_orveinca.php");
$buff= new DocumentBuffer(true,true,true);
$database = new PRODUCTOS();

if(!empty($_POST['ORDE_COMP']))
{
	$sql=NULL;
	if(!empty($_POST['s_fact']))
	{
		$sql="esta_orde='P'";
	}
	if(!empty($_POST['opcion']) && !empty($_POST['texto']))
	{

		switch($_POST['opcion'])
		{
			case 'all':
			$sql="nume_orde like '%".$_POST['texto']."%' or nomb_prov like '%".$_POST['texto']."%'  or idet_prov like '%".$_POST['texto']."%'";
			break;
			case 'prov':
			$sql="nomb_prov like '%".$_POST['texto']."%'  or idet_prov like '%".$_POST['texto']."%'";
			break;
			case 'nume_orde':
			$sql="nume_orde = '".$_POST['texto']."'";
			break;
			case 'estado':
			$sql="esta_orde='".$_POST['texto']."'";
			break;
			default:
			$sql=$_POST['opcion']." = '".$_POST['texto']."'";
			break;
		}

	}

	$database->consulta("SELECT * FROM info_orden_compra ",$sql,NULL,"");
	for($i=0;$campo=$database->result();$i++)
	{
		if($i%2==0)
			$row_act=' row_act';
		else
			$row_act='';
		$cancel='';
		switch($campo['esta_orde'])
		{
			case 'F':$estado="FACTURADO";;break;
			case 'P':$estado="PENDIENTE";;break;
			case 'C':$estado="CANCELADO";$cancel='cancel';;break;
		}
		echo "<tr class='col_hov $row_act 	$cancel' id='$campo[nume_orde]'>
		<th>$campo[nume_orde]</th>
		<th>
		<a href='../provedores/provedor_allinfo.php?idet_prov=$campo[idet_prov]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='mas_info_prov[iframe]'>$campo[nomb_prov]</a>
		</th>
		<th id='esta_orde$campo[nume_orde]'>$estado</th>
		<th>".fmt_num($campo['total_bs'])."</th>
		<th>$campo[fech_orde]</th>
		<th><a href='info_orde.php?nume_orde=$campo[nume_orde]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='orde_comp_prod[iframe]'><div class='buscar1 info_orde'></div></a>";
		if($campo['esta_orde']=='P')
		{
			echo "<a href='facturar_orden.php?nume_orde=".$campo['nume_orde']."'><div class='entr'></div></a>
		  <a href='".$campo['nume_orde']."' class='cancelar_orde'><div class='elimina'></div></a>";
		}
		echo "</th>
		</tr>";
	}
	echo "<script>
$(document).ready(function(e) {";
	if($database->error())
	{
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}
	echo "
	$('.cancelar_orde').click(function(e) {
			e.preventDefault();
       var nume_orde=$(this).attr('href');
		$( '#dialog:ui-dialog' ).dialog('destroy' );
		$( '#errores' ).attr('title','CANCELAR ORDEN DE COMPRA');
		$( '#error_div' ).html('<h3>ESTA SEGURO QUE DESEA CANCELAR LA ORDEN DE COMPRA n°'+nume_orde+'</H3>');

		$( '#errores' ).dialog({

			height:300,
			modal: true,
			buttons: {
				'ACEPTAR': function() {
					$( '#errores' ).dialog( 'close' );
					$().load_html('ajax_compras.php',{ 'cancela_orden' : true,'nume_orde':nume_orde  },
					function(html){ 
					if(html=='')
					{
						$('#esta_orde'+nume_orde).html('CANCELADO');
						$('#'+nume_orde).addClass('cancel');
					}
					else
					{
						$( '#errores' ).attr('title','ERROR');
						error('ERROR',html);
					}
					});
				},
				'CANCELAR': function() {
					$( '#errores' ).dialog( 'close' );
				}
			}
		});

    });

		$('.lightbox').append('<span></span>');
		$('a[data-gal^=\"mas_info_prov\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
		$('a[data-gal^=\"orde_comp_prod\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
});
</script>";
}
if(!empty($_POST['cancela_orden']) && !empty($_POST['nume_orde']) )
{
	$database->autocommit(false);
	$database->consulta("UPDATE orden_comp SET esta_orde='C' WHERE nume_orde='".$_POST['nume_orde']."'");
	if(!$database->error())
	{
		$database->commit();	

	}else
	{
		$database->rollback();
		echo "ERROR INESPERADO AL CANCELAR LA ORDEN DE COMPRA";
	}
}

if(!empty($_POST['FACTURAS_COMPRAS']))
{
	$havin='';
	$sql=NULL;
	if(!empty($_POST['opcion']) && !empty($_POST['texto']))
	{

		switch($_POST['opcion'])
		{
			case 'all':
			$sql="nume_orde like '%".$_POST['texto']."%' or nume_fact like '%".$_POST['texto']."%'  or nomb_prov like '%".$_POST['texto']."%'  or idet_prov like '%".$_POST['texto']."%'";
			break;
			case 'prov':
			$sql="nomb_prov like '%".$_POST['texto']."%'  or idet_prov like '%".$_POST['texto']."%'";
			break;
			case 'nume_orde':
			$sql="nume_orde = '".$_POST['texto']."'";
			break;
			case 'nume_fact':
			$sql="nume_fact = '".$_POST['texto']."'";
			break;
			case 'estado':
			$sql=NULL;
			$havin="HAVING esta_reci ".$_POST['texto'];
			break;


			default:
			$sql=$_POST['opcion']." = '".$_POST['texto']."'";
			break;
		}

	}
	if($result=$database->consulta("select * from info_facturas_c_plus_pagado",$sql,NULL,$havin." ORDER BY (total_bs-bsf_pago) ASC,esta_reci DESC "))

	for($i=0;$campo=$result->fetch_array();$i++)
	{
		if($i%2!=0)
			$row_act=' row_act';
		else
			$row_act='';
		$estado=$campo['desc_esta_reci'];
		//$estado="PENDIENTE";

		echo " <tr class='col_hov $row_act' id='$campo[nume_orde]'>
          <td>".$campo['nume_orde']."</td>
          <td>".$campo['nume_fact']."</td>
          <td><a href='../provedores/provedor_allinfo.php?idet_prov=$campo[idet_prov]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='mas_info_prov[iframe]'>$campo[nomb_prov]</a> </td>
          <td>".$estado."</td>
          <td>".fmt_num($campo['total_bs'])."</td>
		   <td>";
		echo fmt_num($campo['bsf_pago']);
		echo "</td>
          <td>".$campo['fech_fact']."</td>
          <td><a href='info_orde.php?nume_orde=$campo[nume_orde]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='orde_comp_prod[iframe]'><div class='buscar1 info_orde'></div></a>";
		if(fmt_num($campo['bsf_pago'])!=fmt_num($campo['total_bs']))
		//if($pago['total_pag']!=$campo['total_bs'])
		{
			echo "<a href='ingr_pago.php?id_fact=$campo[nume_orde]&tipo_fact=C&amp;iframe=true&amp;width=600&amp;height=380&amp;' class='lightbox-image'  data-gal='ingr_pago[iframe]'><div class='dolar info_orde'></div></a>";
		}
		if($campo['esta_reci']!=0)
		{
			echo "<a href='entr_prod.php?nume_orde=".$campo['nume_orde']."&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='entr_prod[iframe]'>
				<div class='entr'></div></a>";
		}

		echo "</td>
        </tr>";
	}

	echo "<script>
$(document).ready(function(e) {";
	if($database->error())
	{
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}
	echo "
	   $('.lightbox').append('<span></span>');
		$('a[data-gal^=\"mas_info_prov\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
		$('a[data-gal^=\"orde_comp_prod\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
		$('a[data-gal^=\"ingr_pago\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
		$('a[data-gal^=\"entr_prod\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
		$('.dolar').tics('REGISTRAR PAGO');
});
</script>";
}


?>