<?php
require_once("../clases/config_orveinca.php");
$Database= new EMPLEADOS();
$Time= new TIME();
$Html= new HTML();
$Html->set_title("NOMINA");

$config=$Database->config();
if(!empty($_POST['aceptar']))
{
	if($Database->NominaMensual($_POST))
	{
		//redirec("buscar_empleados.php?nomina=1");
	}
}
$MES=$Time->format('m')-1;
$script='';
$Database->consulta("SELECT nomina.*,empleados.* FROM `nomina` 
LEFT JOIN empleados USING(ci_empl) where 
fech_nomi>='".$Time->ano."-".($MES)."-1' and fech_nomi<'".$Time->ano."-".($MES+1)."-1'");
if($Database->result->num_rows!=0)
	{
		$script= "	$('#conten_html').fadeOut();
	$('#pdf_blok').fadeIn();
	$('#iframe').attr('src','empleado_pdf.php').attr('width',960).attr('height',600).fadeIn();";
	}
	
	?>
<script>
$(document).ready(function(e) {
   <?php  echo $script ?>
	
});
</script>
<style>
#iframe,#pdf_blok{
	display:none; 
}
</style>

<div align="center" class="conten_ico" > </div>
<div id="pdf_blok">
  <iframe id="iframe"></iframe>
</div>
<div align="center" id="conten_html">
  <H1>NOMINA DE
    <?php
echo strtoupper($Time->mes_cadena($Time->mes-1)).' '.$Time->ano;
		?>
  </H1>
  <?php
if(!empty($_POST['env']))
{
	echo "<h2>¿¿ESTA SEGURO DE ENVIAR LOS SIGUIENTES DATOS??</h2>";
}
	?>
  <form  action="" METHOD="post"  enctype="multipart/form-data"  name="frmDatos" target="_self" >
    <table  width="950" border="0" cellspacing="1" cellpadding="0">
      <thead>
        <tr>
          <td>CI</td>
          <td>NOMBRE</td>
          <td>DIAS LABORADOS</td>
          <td>S.O.S</td>
          <td>S.P.F</td>
          <TD>L.P.H</TD>
          <TD>CESTA TIKE</TD>
        </tr>
      </thead>
      <tbody>
        <?php
if(empty($_POST['env']))
{


	$result=$Database->consulta(EMPLEADOS::EMPL);
	for($i=0;$campo=$result->fetch_array();$i++)
	{
		if($i%2!=0)
			$row_act='row_act';
		else
			$row_act='';
		echo "<tr class='col_hov ".$row_act."'>
		<td><input type='hidden' name='ci_empl[".$i."]' value='".$campo['ci_empl']."'> ".$campo['ci_empl']."</td>
		<td>".$campo['nom1_empl']." ".$campo['ape1_empl']."</td>
		<td><input type='number' name='dias_labo[".$i."]' value='21'></td>
		<td><input type='checkbox' checked name='sos[".$i."]'></td>
		<td><input type='checkbox' checked name='spf[".$i."]'></td>
		<td><input type='checkbox' checked name='lph[".$i."]'></td>
		<td><input type='checkbox' checked name='cest_tike[".$i."]'></td>
		</tr>";
	}
}else
{
	foreach($_POST['ci_empl'] as $i=>$ci_empl)
	{
		$Database->consulta(EMPLEADOS::EMPL,"ci_empl='".$ci_empl."'");
		$campo=$Database->result();
		if($i%2!=0)
			$row_act='row_act';
		else
			$row_act='';
		echo "<tr class='col_hov ".$row_act."'>
		<td><input type='hidden' name='ci_empl[".$i."]' value='".$ci_empl."'> ".$ci_empl."</td>
		<td>".$campo['nom1_empl']." ".$campo['ape1_empl']."</td>
		<td><input type='hidden' name='dias_labo[".$i."]' value='".$_POST['dias_labo'][$i]."'>".$_POST['dias_labo'][$i]."</td>
		<td><input type='hidden' value='".!empty($_POST['sos'][$i])."' name='sos[".$i."]'>".(!empty($_POST['sos'][$i])?'SI':'NO')."</td>
		<td><input type='hidden' value='".!empty($_POST['spf'][$i])."' name='spf[".$i."]'>".(!empty($_POST['spf'][$i])?'SI':'NO')."</td>
		<td><input type='hidden' value='".!empty($_POST['lph'][$i])."' name='lph[".$i."]'>".(!empty($_POST['lph'][$i])?'SI':'NO')."</td>
		<td><input type='hidden' value='".!empty($_POST['cest_tike'][$i])."' name='cest_tike[".$i."]'>".(!empty($_POST['cest_tike'][$i])?'SI':'NO')."</td>
		</tr>";
	}	
}
				?>
      </tbody>
    </table>
    <?php
if(empty($_POST['env']))
{
	echo '<button class="submit" type="submit" name="env" value="true">Enviar</button>';
}else
{
	echo '<button class="submit" type="submit" name="aceptar" value="true">Enviar</button>';
}
		?>
  </form>
</div>
