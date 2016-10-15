<?php

require_once("../clases/config_orveinca.php");
$html= new INFO_HTML();
$database= new PRODUCTOS();
$html->uipanel('#tablepanel',2);

if(empty($_GET['codi_gast']))
$html->__destruct();

 $database->consulta(PRODUCTOS::GATOS,"codi_gast='".$_GET['codi_gast']."'");
 $gasto=$database->result();
$config=$database->config();

?>
</h1>

<div id="tablepanel" >
  <ul>
    <li><a href="#tablepanel-1">INFO DE GASTO</a></li>
    <li><a href="#tablepanel-2">DESCRIPCION</a></li>
  </ul>
  <div id="tablepanel-1">
    <div class="produc">
    <h1>INFORMACION </h1>
      <table width="560" border="0" cellspacing="1" cellpadding="0">
        <tr class="col_title">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
         <tr class="col_hov  ">
          <td>NUMERO DE RECIBO </td>
          <td><?php echo $gasto['codi_gast']?></td>
        </tr >
        <tr class="col_hov  ">
          <td>TIPO DE GASTO </td>
          <td><?php echo $gasto['desc_tpga']?></td>
        </tr >
        <tr class="col_hov row_act">
          <td>MODODO DE PAGO</td>
          <td><?php
          switch($gasto['modo_pago'])
		{
			case 'E':
				echo "EFECTIVO";
				$tex_pago="";
			 break;	
			 case 'C':
				echo "CHEQUE";
				 $tex_pago="NUMERO DE CHEQUE";
			 break;
			 case 'D':
			 	echo "DEPOSITO";
				 $tex_pago="NUMERO DE DEPOSITO";
			 break;
		}?>
        
        </td>
        </tr >
        <?php
		if($gasto['modo_pago']!='E')
		{
		?>
        <tr class="col_hov  ">
          <td><?php
        echo $tex_pago;
		  ?></td>
          <td>
          <?php
		  echo $gasto['idet_pago'];
		  ?>
          </td>
        </tr>
        <tr class="col_hov row_act">
          <td>BANCO</td>
          <td><?php echo $gasto['nomb_banc']; ?></td>
        </tr>
        <?php
		}
		?>
         <tr class="col_hov ">
          <td>MONTO</td>
          <td><?php echo fmt_num($gasto['bsf_pago']); ?></td>
        </tr>
      </table>
    </div>
  </div>
  <div id="tablepanel-2">
    <div class="produc ">
    <h1>DESCRIPCION DEL GASTO </h1>
    <article class="text_plano">
    <?php
	echo $gasto['desc_gast'];
	?></article>
     </div>
  </div>
</div>
<style>
.text_plano
{
	width:500px; text-align:justify; margin:10PX;
	font:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>