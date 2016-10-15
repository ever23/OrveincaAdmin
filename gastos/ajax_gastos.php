<?php
require_once("../clases/config_orveinca.php");
$database= new PRODUCTOS();
$time= new TIME();
$buff= new DocumentBuffer(true,true,true);
if(!empty($_POST['gastos']))
{
	
	$LIMIT="";

	$consulta=PRODUCTOS::GATOS;
	if(!empty($_POST['opcion']) && $_POST['opcion']!='')
	{
		switch($_POST['opcion'])
		{
			case 'fecha':
			$database->consulta($consulta,"fech_pago='".$_POST['text']."'");
			break;
			case 'codi_gast':
			$database->consulta($consulta,"codi_gast='".$_POST['text']."'");
			break;
			case 'codi_tpga':
			$database->consulta($consulta," gastos.codi_tpga='".$_POST['text']."'");
			break;
			default:
			$database->busquedas_sql($consulta,$_POST['text'],[$_POST['opcion']]);
			break;
		}
	}else
	{
		if(empty($_POST['text']))
		{
			
			$database->consulta($consulta," fech_pago>".$time->ano."-01-01");
		}else
		{
			$database->busquedas_sql($consulta,$_POST['text'],['desc_tpga','desc_pago','nume_reci']," and fech_pago>".$time->ano."-".($time->mes)."-01");
		}

	}
	$is_row_act=false;
	$row_act;
	
	
	if(!$database->error)
	
	for($i=0;$campo=$database->result();$i++)
	{
		if($i%2==0)
		$row_act=' row_act';
		else
		$row_act='';
		echo "
		<tr class='$row_act col_hov'>
		<td>".$campo['codi_gast']."</td>
		<td>".$campo['desc_tpga']."</td>
		<td><text  id='mas".$i."'>".(strlen($campo['desc_gast'])>60?substr($campo['desc_gast'],0,60)."...":$campo['desc_gast'])."</text> </td>
		<script>
		$(document).ready(function(e)
		{
			$('#mas".$i."').tics('".$campo['desc_gast']."',{'width':'400px','text-align':'justify'});
		});
		</script>
		<td>".fmt_num($campo['bsf_pago'])."</td>
		<td>".$campo['fech_pago']."</td>
		<td  >
	 <a href= 'info.php?codi_gast=".$campo['codi_gast']."&amp;iframe=true&amp;width=640&amp;height=430&amp;' data-gal='prettyPhoto[iframe]' >
	 <div class='buscar1 mas_info' ></div></a>
		</td>
		</tr>
		";
	}
	//echo "<tr><th></th><th>".$database->sql.$database->errores."</pre>$_POST[extern]ASS</th></tr>";
	echo  '<script type="text/javascript">
		$(document).ready(function() { 
			$("a[data-gal^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
			';
			if($database->error())
			{
				echo "error('ERROR','".OrveincaExeption::GetExeptionS()."');";
			}
	echo "
			$('.mas_info').tics('MAS INFORMACION DEL GASTO')
	";
	echo '
		}); 
</script> 
';
}


?>