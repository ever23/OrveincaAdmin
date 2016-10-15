<?php
require_once("../clases/config_orveinca.php");
$html= new INFO_HTML();
$html->uipanel("#tablepanel",4);
if(empty($_GET['idet_prov']))
$html->__destruct();
$database= new PROVEDORES();
$database->consulta(PROVEDORES::PROV," idet_prov='$_GET[idet_prov]'");
$provedor=$database->result();
?>
<div id="tablepanel">
  <ul>
    <li><a href="#tablepanel-1">PROVEDOR</a></li>
    <li><a href="#tablepanel-2">CONTACTO</a></li>
    <li><a href="#tablepanel-4">PRODUCTOS QUE DISTRIBULLE </a></li>
    <li><a href="#tablepanel-3">CREDITO</a></li>
    
  </ul>
  <div id="tablepanel-1">
    <div class="produc">
      <h1><?php echo "$provedor[nomb_prov]"; ?> </h1>
      <table width="560" border="0" cellspacing="1" cellpadding="0">
        <tr class="col_title">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="col_hov  ">
          <td>RIF : </td>
          <td><?php echo "$provedor[codi_tide]$provedor[idet_prov]"?></td>
        </tr >
        <tr class="col_hov row_act">
          <td>EMAIL:</td>
          <td><?php echo "$provedor[emai_prov] "?></td>
        </tr >
        <tr class="col_hov  ">
          <td>TELEFONOS:</td>
          <td><?php
			  $database->consulta("SELECT * FROM telefonos  WHERE id_tper='prov' and idet_pers='$provedor[idet_prov]'");
	 while($telefono=$database->result())
	{
		echo $telefono['#telf']."  , ";
	} ?></td>
        </tr>
        <tr class="col_hov row_act">
          <td>DIRECCION:</td>
          <td><?php echo "$provedor[dire_prov], PARROQUIA: ".$provedor['desc_parr'].", MUNICIPIO: ".$provedor['desc_muni'].", ESTADO: ".$provedor['desc_esta']; ?></td>
        </tr>
      </table>
      <div>
        <h3>CUENTAS BANCARIAS</h3>
        <table width="400" border="0" cellspacing="1" cellpadding="0" >
          <tr class="col_title">
            <td>BANCO</td>
            <td>TIPO DE CUENTA</td>
            <td> NUMERO DE CUENTA</td>
          </tr >
          <?php
			  if($database->cuent_banc("idet_pers='".$provedor['idet_prov']."' and id_tper='prov'"))
              for($i=0;$campo=$database->result();$i++)
			  {
				  if($i%2==0)
				$row_act='row_act';
				else
				$row_act='';
				 echo "<tr class='col_hov $row_act'>
              	<td>".$campo['nomb_banc']."</td>
                <td>".$campo['tipo_cuet']."</td>
                <td>".$campo['#cuenta']."</td>
           	    </tr >";
			  }?>
        </table>
      </div>
    </div>
  </div>
  <div id="tablepanel-2">
    <div class="produc">
      <h1><?php echo "$provedor[nom1_cont] $provedor[nom2_cont] $provedor[ape1_cont] $provedor[ape2_cont]"; ?> </h1>
      <table width="560" border="0" cellspacing="1" cellpadding="0">
        <tr class="col_title">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      
        <tr class="col_hov row_act">
          <td>EMAIL:</td>
          <td><?php echo "$provedor[emai_cont] "?></td>
        </tr>
        <tr class="col_hov  ">
          <td>TELEFONOS:</td>
          <td><?php
			  $database->consulta("SELECT * FROM telefonos  WHERE id_tper='cont' and idet_pers='$provedor[ci_cont]'");
			 
	 while($telefono=$database->result())
	{
		echo $telefono['#telf']."  , ";
	} ?></td>
        </tr>
        
      </table>
    </div>
  </div>
  <div id="tablepanel-3"> <div class="produc">
        <h1>FACTURAS POR PAGAR </h1>
        <table width="600" border="0" cellspacing="1" cellpadding="0" >
 <thead><tr>
    <td>N° ORDE</td>
    <td>N° FACT</td>
    <td>MONTO</td>
    <td>PAGADO</td>
    <td>ADEUDA</td>
    <td>FECHA</td>
    
  </tr></thead> 
  <tbody>
  <?php

  $result=$database->consulta(PRODUCTOS::COMPRAS,"orden_comp.idet_prov='".$provedor['idet_prov']."' ",NULL,"GROUP BY nume_orde   ORDER BY esta_reci DESC ");
  $tota_bs=$total_pag=0;
for($i=0;$campo=$result->fetch_array();$i++)
{
	 
    $database->consulta(PRODUCTOS::TOTAL_PAG_C,"nume_orde='$campo[nume_orde]'",NULL,'GROUP BY nume_orde');
    $pago=$database->result();
	if(empty($pago['total_pag']))
	$pago['total_pag']=0;
	
   if(fmt_num($campo['total_bs']-$pago['total_pag'])!=0)
   {
	    if($i%2==0) 
	 $row_act='';
	 else
	  $row_act='row_act';
	  
		echo "<tr class='col_hov  $row_act' >
		<td>
		<a href='../orden_compra/info_orde.php?nume_orde=$campo[nume_orde]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='orde_comp_prod[iframe]'>$campo[nume_orde]</a>
		</td>
		<td>".$campo['nume_fact']."</td>
		<td>".fmt_num($campo['total_bs'])."</td>
		<td>".fmt_num($pago['total_pag'])."</td>
		<td>".fmt_num($campo['total_bs']-$pago['total_pag'])."</td>
		<td>".$campo['fech_fact']."</td>
		</tr>";
		$tota_bs+=$campo['total_bs'];
		$total_pag+=$pago['total_pag'];
   }
}
  ?></tbody>
  <tfoot>
  <tr>
    <td  colspan="5">&nbsp;</td>
  </tr>
  <TR>
 
  <td  colspan="4"> </td>
   <td  width="97"  class="row_act" >TOTAL</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($tota_bs);
			    ?>
           </td>
  </TR>
    <TR>
  <td  colspan="4"></td>
   <td  width="97"  class="row_act" >ADEUDA</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($tota_bs-$total_pag );
			    ?>
           </td>
  </TR>
  </tfoot>
</table>
      </div></div>
      <div id="tablepanel-4"> <div class="produc">
        <h1>PRODUCTOS QUE DISTRIBULLE </h1>
        <table width="560" border="0" cellspacing="1" cellpadding="0" >
  <tr class="col_title">
    <td>CODIGO</td>
    <td>DESCRIPCION</td>
    <td>ULTIMO PRECIO</td>
    <td></td>
  </tr>
  <?php
  
  $database->consulta($database->AddCollConsulta(PRODUCTOS::FACT_PROD,['MAX(faco_prod.cost_comp) as precio'])," idet_prov='$_GET[idet_prov]' GROUP BY faco_prod.id_prod");
  echo $database->error;
for($i=0;$campo=$database->result();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  
	echo "<tr class='col_hov  $row_act' >
	<td>".$campo['codi_clpr'].$campo['id_prod']."</td>
	<td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."</td>
	<td>".$campo['precio']."</td>
	<td><a href='../lista de precios/info.php?id_prod=".$campo['id_prod']."'><div class='buscar1 mas_info actions' ></div></a>
	</tr>";
}
  ?>
</table>
      </div></div>
</div>
<?php

?>
