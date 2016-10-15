<?php 
require_once("../clases/config_orveinca.php");
$database = new PRODUCTOS();
$buff= new DocumentBuffer(true,true,true);
if(!empty($_POST['VENTAS']))
{
	$sql =' 1 ';

	if(!empty($_POST['opcion']))
	{
		switch($_POST['opcion'])
		{
			case 'all':
			$sql="(nume_nent like '%$_POST[texto]%' or nom1_empl like '%$_POST[texto]%' or nom2_empl like '%$_POST[texto]%' or ape1_empl like '%$_POST[texto]%' or ape2_empl like '%$_POST[texto]%' or  empleados.ci_empl like '%$_POST[texto]%' or  nomb_clie like '%$_POST[texto]%' or idet_clie like '%$_POST[texto]%')";
			break;
			case 'nume_nent':
			$sql = "(nume_nent = '$_POST[texto]') ";
			break;
			case 'vend':
			$sql = "(empleados.ci_empl ".($_POST['texto']!=''?"= '$_POST[texto]'":"is NULL ").") ";
			break;
			case 'clie':
			$sql = "( nomb_clie like '%$_POST[texto]%' or idet_clie like '%$_POST[texto]%') ";
			break;
			default:
			$sql = "($_POST[opcion] like '%$_POST[texto]%') ";
			break;
		}
	}
	$result=$database->consulta("select info_nota_entrega_plus_pago.* from info_nota_entrega_plus_pago",$sql,' (total_bs-bsf_pago) ASC ','LIMIT 30  ');
	echo $database->error;
	for($i=0;$campo=$result->fetch_array();$i++)
	{
		if($i%2==0)
			$row_act=' row_act';
		else
			$row_act='';
		echo "
		<tr class='col_hov $row_act'>
		<th>$campo[nume_nent]</th>
		<th>$campo[nume_fact]</th>
		<th>
		<a href='../clientes/cliente_allinfo.php?idet_clie=$campo[idet_clie]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='info_cliente[iframe]'>$campo[nomb_clie] </a>
		</th>
		<th><a href='../empleados/empleados_allinfo.php?ci_empl=$campo[ci_empl]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='info_vendedor[iframe]'>$campo[nom1_empl] $campo[ape1_empl]</a></th>
		<th>".fmt_num($campo['total_bs'])."</th>
		<th>";
		
		echo fmt_num($campo['bsf_pago']);
		echo "</th>
		<th>$campo[fech_nent]</th>
		<th>
		<a href='info_venta.php?nume_nent=$campo[nume_nent]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='info_venta[iframe]' class ='nume_nent' > <div class='buscar1'></div></a>";
		if(number_format($campo['bsf_pago'],2 , "." ,"")<number_format($campo['total_bs'],2 , "." ,""))
		{
			echo "<a href='../orden_compra/ingr_pago.php?id_fact=$campo[nume_nent]&tipo_fact=V&amp;iframe=true&amp;width=600&amp;height=380&amp;' class='lightbox-image'  data-gal='ingr_pago[iframe]'><div class='dolar info_orde'></div></a>";
		}
		echo "
		</th>
		</tr>";
	}
	$result->free();
	//echo "<tr  class='col_hov $row_act'><th></th><th>".$database->sql.$database->errores."</th></tr>";
	echo "<script>
$(document).ready(function(e) {

		$('.lightbox').append('<span></span>');
		$('a[data-gal^=\"info_cliente\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
		$('a[data-gal^=\"info_venta\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
		$('a[data-gal^=\"ingr_pago\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});

$('a[data-gal^=\"info_vendedor\"]').prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:false, autoplay_slideshow: false});
$('.dolar').tics('REGISTRAR PAGO');
";
	if($database->error())
	{
		echo "error('ERROR','".OrveincaExeption::GetExeptionS(true)."');";
	}
	echo '$("a[data-gal^=\'info_cliente\']").tics("INFORMACION DEL CLIENTE ");
$("a[data-gal^=\'info_vendedor\']").tics("INFORMACION DEL VENDEDOR ");
$("a[data-gal^=\'info_venta\']").tics("INFORMACION DETALLADA DE LA VENTA");';
	echo "
});
</script>";
}
?>