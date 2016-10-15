<?php
require_once("../clases/config_orveinca.php");

$html= new INFO_HTML();
$database= new PRODUCTOS();
$html->uipanel('#tablepanel',3);

if(empty($_GET['id_prod']))
	$html->__destruct();

$database->consulta(PRODUCTOS::PROD,"id_prod='$_GET[id_prod]'");
$producto=$database->result();
$img="../mysql/img.php?id=".$producto['id_imag_p']."";
$config=$database->config();

?>

<script>
	$(document).ready(function(e) {
		<?php
if(!empty($_GET['costo']))
{
	echo "$('#$_GET[id_tmpd]').addClass('col_select');
	$('#$_GET[id_tmpd]').mouseenter(function(e) {
    $('#$_GET[id_tmpd]').removeClass('col_select')
});";
}?>
		$('.costo').dblclick(function(e) {
			var id_tmpd=$(this).attr('id_tmpd');
			var cost_tama=$(this).attr('costo');
			$(this).html('<input type="text" form="preprod'+id_tmpd+'" name="costo" id="cost_prod'+id_tmpd+'" value="'+cost_tama+'" placeholder="'+cost_tama+'" size="7" >');
			$('#cost_prod'+id_tmpd).focus();

		});
		$('.costo').focusout(function(e) {
			var id_tmpd=$(this).attr('id_tmpd');
			var selct=$(this);
			$(this).load_json("ajax_precios.php",
							  {
				"id_prod":'<?php echo $_GET['id_prod']?>',
				"id_tmpd":id_tmpd,
				"costo":""+$('#cost_prod'+id_tmpd).attr('value')+""},
							  function(json){
				if(!json.error)
				{
					selct.attr('costo',json.costo);
					$('.precio1[id_tmpd='+id_tmpd+']').html(json.precio1);
					$('.precio2[id_tmpd='+id_tmpd+']').html(json.precio2);
					$('.precio3[id_tmpd='+id_tmpd+']').html(json.precio3);
					$('.fecha[id_tmpd='+id_tmpd+']').html(json.fecha);
					return json.costofmt;
				}else
				{
					error('ERROR AL EDITA EL COSTO',json.error);
				}
			});
			//location.href='info.php?id_prod='+id_prod+'&id_tmpd='+id_tmpd+'&costo='+costo;
		});
	});

</script>
</h1>

<div id="tablepanel" >
  <ul>
    <li><a href="#tablepanel-1">INFO</a></li>
    <li><a href="#tablepanel-2">EXISTENCIA</a></li>
    <li><a href="#tablepanel-3">PROVEEDORES</a></li>
  </ul>
  <div id="tablepanel-1">
    <div class="produc ">
      <h1>
        <?php

	echo " $producto[codi_clpr]$producto[id_prod] $producto[desc_prod]  $producto[desc_marc] $producto[desc_mode] "; ?>
      </h1>
      <img src="<?php echo $img; ?>" width="145" height="169" class="img">
      <div class="info">
        <table width="479" border="0" cellpadding="0">
          <thead>
            <tr class="col_title">
              <td width="61">MEDIDA</td>
              <td width="53">COSTO</td>
              <td width="64">PRECIO1</td>
              <td width="64">PRECIO2</td>
              <td width="64">PRECIO3</td>
              <td width="30">ACTUALIZADO</td>
            </tr>
          </thead>
          <tbody >
            <?php
$meditama='';
// OrveincaExeption::DieExeptionS();
if($consulta=$database->consulta(PRODUCTOS::PROD_TC,"id_prod='$_GET[id_prod]'", "`t1`.`medi_tama` ASC"))
	for($ind=0;$campo=$consulta->fetch_array();$ind++)
{
	$meditama=$campo['medi_tama1'];
	if($ind%2==0) 
		$row_act='';
	else
		$row_act='row_act';
	echo " <tr class='$row_act col_hov' id='$campo[id_tmpd]'>
  <td id='tamano$campo[id_tmpd]'> ".ucwords($campo['codi_umed'])."  ";
	if($campo['medi_tama1']!='-' && $campo['medi_tama1']!='')
		echo $campo['medi_tama1'];
	if($campo['medi_tama2']!='-' && $campo['medi_tama2']!='')
		echo " a $campo[medi_tama2]";
	echo " </td>
  <td id_tmpd='".$campo['id_tmpd']."' costo=".$campo['cost_tama']." class='costo' >".fmt_num($campo['cost_tama'])."</td>";

	echo"
  <td id_tmpd='".$campo['id_tmpd']."' class='precio1'>".fmt_num(($config['precio1']*$campo['cost_tama'])+$campo['cost_tama'])."</td>
  <td id_tmpd='".$campo['id_tmpd']."' class='precio2'>".fmt_num(($config['precio2']*$campo['cost_tama'])+$campo['cost_tama'])."</td>
  <td id_tmpd='".$campo['id_tmpd']."' class='precio3'>".fmt_num(($config['precio3']*$campo['cost_tama'])+$campo['cost_tama'])."</td>
  <td id_tmpd='".$campo['id_tmpd']."' class='fecha' width='20'>".$campo['fech_tama']."</td>

  </tr>";
}
echo $database->error;
						?>
          </tbody>
        </table>
        <BR>
      </div>
    </div>
  </div>
  <div id="tablepanel-2">
    <div class="produc ">
      <h1>
        <?php

echo " $producto[codi_clpr]$producto[id_prod] $producto[desc_prod]  $producto[desc_marc] $producto[desc_mode] "; ?>
      </h1>
      <div class="info" align="">
        <table width="576" border="0" cellpadding="0" align="center">
          <tbody>
            <tr class="col_title">
              <td width="67">MEDIDA</td>
              <td width="81">COLOR</td>
              <td width="91">EXISTENCIA</td>
              <td width="58">COSTO</td>
              <td width="70">PRECIO1</td>
              <td width="70">PRECIO2</td>
              <td width="78">PRECIO3</td>
            </tr>
            <?php

$inve=$database->consulta("select * from inventario where id_prod='".$producto['id_prod']."'");

for($ind=0;$campo=$inve->fetch_array();$ind++)
{
	$existencia=$campo['existencia'];
	
	if($ind%2==0) 
		$row_act='';
	else
		$row_act='row_act';
	$comp='';
	
	echo "<tr class='$row_act col_hov'>
              <td width='220'>$campo[codi_umed] $campo[medi_tama] </td>
			<td width='220' style='color:$campo[exad];' >$campo[desc_colo]</td>
              <td width='20'>$existencia</td><td >".fmt_num($campo['cost_prod'])."</td>
  <td>".fmt_num(($config['precio1']*$campo['cost_prod'])+$campo['cost_prod'])."</td>
  <td>".fmt_num(($config['precio2']*$campo['cost_prod'])+$campo['cost_prod'])."</td>
  <td>".fmt_num(($config['precio3']*$campo['cost_prod'])+$campo['cost_prod'])."</td>
            </tr>";
	
}
							?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div id="tablepanel-3">
    <div class="produc">
      <h1>PROVEEDORES </h1>
      <h1>
        <?php 
$consulta=$database->AddCollConsulta(PRODUCTOS::PROD_PROV,['MAX(faco_prod.cost_comp) as precio']);
$database->consulta($consulta," id_prod='$_GET[id_prod]' GROUP BY orden_comp.idet_prov ORDER BY cantidad DESC");
echo $database->error;
				?>
      </h1>
      <table width="590" border="0" cellpadding="0" >
        <tr class="col_title">
          <td>IDENTIFICACION</td>
          <td>RASON SOCIAL</td>
          <td>ULTIMO PRECIO</td>
          <td></td>
        </tr>
        <?PHP
for($i=0;$campo=$database->result();$i++)
{
	if($i%2==0)
		$row_act='row_act';
	else
		$row_act='';
	echo "<tr class='col_hov $row_act'>
              <td>$campo[codi_tide]$campo[idet_prov]</td>
              <td>$campo[nomb_prov]</td>

			   <td>".$campo['precio']."</td>
               <td><A href='../provedores/provedor_allinfo.php?idet_prov=$campo[idet_prov]&extern=true'><div class='buscar1'></div></a></td>

            </tr>";
}
				?>
      </table>
    </div>
  </div>
</div>
