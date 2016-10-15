<?php
require_once("../clases/config_orveinca.php");
$html= new HTML();
$database= new PRODUCTOS();
$TIME= new TIME();
$html->prettyPhoto('info_pedido')
    ->prettyPhoto('info_cliente')
    ->prettyPhoto('info_vendedor')
    ->prettyPhoto('mas_info_prov')
    ->prettyPhoto('orde_comp_prod'); 
?>

<style type="text/css">
.google #frmbuscar .boton {
}
.info_ventas, .info_compras {
	width: 404px;
	height: 288px;
	border: 1px solid rgba(6,6,6,1.00);
	float: left;
	border-radius: 6px;
	margin-top: 16px;
	margin-right: 16px;
	margin-bottom: 16px;
	margin-left: 16px;
}
.frame_tab {
	overflow: scroll;
	overflow-x: hidden;
	overflow-y: auto;
	position: absolute;
}
a {
	color: rgba(0,0,0,1.00);
}
</style>
<script>

    //EVER FRANCO
	
    $(document).ready(function(e) {
        $("a[data-gal^=\'info_cliente\']").tics("INFORMACION DEL CLIENTE ");
        $("a[data-gal^=\'info_vendedor\']").tics("INFORMACION DEL VENDEDOR ");
        $("a[data-gal^=\'mas_info_prov\']").tics("INFORMACION DEL PROVEEDOR ");
        $('#edit_conf').tics("EDITAR CONFIGURACION");

    });
</script>

<div align="center" style="display:block; width:inherit; float:left;">
  <h1 aling="center">ORVEINCA_ADMIN <?php 
  echo __VERSION_ORVEINCA_ADMIN__;?></h1>
  <div class="catalogo" align="center">
    
    <div class="info_ventas">
      <h3>PEDIDOS POR ENTREGAR</h3>
      <table width="380"  >
        <thead>
          <tr>
            <td width="65">N° PEDIDO</td>
            <td width="133">CLIENTE</td>
            <td width="148">VENDEDOR</td>
            <td width="25">&nbsp;</td>
            <td width="10">&nbsp;</td>
          </tr>
        </thead>
        <?php

                if($database->consulta(PRODUCTOS::PEDIDOS,"`pedidos`.`esta_pedi`='P'"," `pedidos`.`esta_pedi`  DESC"))
                ?>
        <tbody   width="300" height="170"  style="width:378px;" class="frame_tab ">
          <?php 
             for($i=0;$campo =$database->result();$i++)
            {
                if($i%2==0)
                    $row_act=' ';
                else
                    $row_act='row_act';
                echo "
			<tr class='col_hov $row_act'>
            <td width='72'>$campo[nume_pedi]</td>
            <td width='137'><a href='../clientes/cliente_allinfo.php?idet_clie=$campo[idet_clie]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='info_cliente[iframe]'>
	$campo[nomb_clie] </a></td>
            <td width='155'><a href='../empleados/empleados_allinfo.php?ci_empl=$campo[ci_empl]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='info_vendedor[iframe]'>$campo[nom1_empl] $campo[ape1_empl]</a></td>
            <td width='20'> <a href= '../pedidos/info_pedido.php?nume_pedi=".$campo['nume_pedi']."&amp;iframe=true&amp;width=640&amp;height=430&amp;' data-gal='info_pedido[iframe]' >
	 <div class='buscar1 mas_info_pedi actions' ></div></a></td>

          </tr>
			";
            }
                    ?>
        </tbody>
      </table>
        <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR><br>

      <BR>
      <BR>
      <BR>
      <BR>
       <table width="380">
        <thead>
          <tr>
            <td width="181">PEDIDOS TOTALES</td>
            <td width="187"><?php echo $i ?></td>
          </tr>
        </thead>
      </table>
    </div>
    <div class="info_ventas">
      <h3>CUENTAS POR COBRAR</h3>
      <table width="380">
        <thead>
          <tr>
            <td width="186">CLIENTE</td>
            <td width="182">DEUDA</td>
          </tr>
        </thead>
        <?php

                ?>
        <tbody width="300" height="170"  style="width:378px;" class="frame_tab ">
          <?php
$total_por_cobrar=0;
$i=0;
if($resul=$database->consulta('select info_nota_entrega_plus_pago.*,sum(total_bs-if(isnull(bsf_pago),0,bsf_pago)) as deuda from info_nota_entrega_plus_pago GROUP BY idet_clie  having deuda>0 order by deuda DESC'))

while($campo=$resul->fetch_array())
{
        $total_por_cobrar+=$campo['deuda'];
        if($i%2==0) 
            $row_act='';
        else
            $row_act='row_act';
        echo "
		<tr class='col_hov  $row_act' >
		  <td width='186'><a href='../clientes/cliente_allinfo.php?idet_clie=$campo[idet_clie]&amp;iframe=true&amp;width=640&amp;height=430&amp;' class='lightbox-image'  data-gal='info_cliente[iframe]'>
	$campo[nomb_clie] </a></td>
		<td  width='182'>".fmt_num($campo['deuda'])."</td>

		</tr>";
        $i++; 
}

                    ?>
        </tbody>
      </table>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <table width="380">
        <thead >
          <tr>
            <td width="181">TOTAL</td>
            <td width="187"><?php echo fmt_num($total_por_cobrar) ?></td>
          </tr>
        </thead>
      </table>
    </div>
    <div class="info_compras">
      <h3>ORDENES DE COMPRAS POR FACTURAR</h3>
      <table width="380">
        <thead >
          <tr>
            <td width="45">N-</td>
            <td width="119">PROVEEDOR</td>
             <td width="64">MONTO</td>
              <td width="97">FECHA</td>
               <td width="31"></td>
          </tr>
        </thead>
        <tbody class="frame_tab "  width="380" height="170">
          <?php 
                        $total_por_pagar=0;
$database->consulta("select * from info_orden_compra where esta_orde='P' GROUP BY nume_orde  HAVING total_bs is NOT NULL");
$i=0;
$campo='';

for($i=0;$campo=$database->result();$i++)
{
	if($i%2==0)
			$row_act=' row_act';
		else
			$row_act='';
			echo "<tr class='col_hov $row_act ' id='$campo[nume_orde]'>
		<th width='45'>$campo[nume_orde]</th>
		<th width='119'>
		<a href='../provedores/provedor_allinfo.php?idet_prov=$campo[idet_prov]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='mas_info_prov[iframe]'>$campo[nomb_prov]</a>
		</th>
		
		<th  width='64'>".fmt_num($campo['total_bs'])."</th>
		<th  width='97'>$campo[fech_orde]</th>
		<th width='31'><a href='../orden_compra/info_orde.php?nume_orde=$campo[nume_orde]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='orde_comp_prod[iframe]'><div class='buscar1 info_orde'></div></a>";
			echo "<a href='../orden_compra/facturar_orden.php?nume_orde=".$campo['nume_orde']."'><div class='entr'></div></a>";
		echo "</th>
		</tr>";
		$total_por_pagar+=$campo['total_bs'];
}


                                                                    ?>
        </tbody>
      </table>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <table width="380">
        <thead>
          <tr>
            <td width="181">TOTAL</td>
            <td width="187"><?php echo fmt_num($total_por_pagar) ?></td>
          </tr>
        </thead>
      </table>
    </div>
    <div class="info_compras">
      <h3>CUENTAS POR PAGAR</h3>
      <table width="380">
        <thead >
          <tr>
            <td width="181">PROVEEDOR</td>
            <td width="187">DEUDA</td>
          </tr>
        </thead>
        <tbody class="frame_tab "  width="380" height="170">
          <?php 
                        $total_por_pagar=0;
$result=$database->consulta("SELECT info_facturas_c_plus_pagado.*,sum(total_bs-if(isnull(bsf_pago),0,bsf_pago)) as deuda FROM
 info_facturas_c_plus_pagado  GROUP BY idet_prov having deuda>0  ORDER BY esta_reci DESC,deuda DESC ");

$i=0;
while($campo=$result->fetch_array())
{
	if(fmt_num($campo['deuda'])==0)
	continue;
     $total_por_cobrar+=$campo['deuda'];
        if($i%2==0) 
            $row_act='';
        else
            $row_act='row_act';
        $total_por_pagar+=$campo['deuda'];
        echo "<tr class='col_hov  $row_act'>
			 <td  width='182'><a href='../provedores/provedor_allinfo.php?idet_prov=$campo[idet_prov]&amp;iframe=true&amp;width=650&amp;height=450&amp;' class='lightbox-image'  data-gal='mas_info_prov[iframe]'>$campo[nomb_prov]</a> </td>
		 	<td  width='182'>".fmt_num($campo['deuda'])."</td>
			</tr>";
        $i++;
}


                                                                    ?>
        </tbody>
      </table>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <BR>
      <table width="380">
        <thead>
          <tr>
            <td width="181">TOTAL</td>
            <td width="187"><?php echo fmt_num($total_por_pagar) ?></td>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>