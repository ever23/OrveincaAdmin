<?php
require_once("../clases/config_orveinca.php");
$html= new INFO_HTML();
$time= new TIME();


$html->uipanel('#tablepanel',4);

if(empty($_GET['ci_empl']))
$html->__destruct();


$database= new EMPLEADOS();

if(!empty($_POST['comicion']))
{
	if(!$database->consulta("UPDATE empleados SET porc_comi='".($_POST['comicion']*0.01)."' WHERE ci_empl='$_GET[ci_empl]' ;"))
	{
			$e= new OrveincaExeption("ERROR INESPERADO AL EDITAR LA COMICION ");
		
	}
}
if(!empty($_POST['sueldo']))
{
	if(!$database->consulta("UPDATE empleados SET sueldo='".$_POST['sueldo']."' WHERE ci_empl='$_GET[ci_empl]' ;"))
	{
			$e= new OrveincaExeption("ERROR INESPERADO AL EDITAR EL SUELDO ",$database);
	}
}
$database->consulta(EMPLEADOS::EMPL," ci_empl='$_GET[ci_empl]'");
$empleado=$database->result();
?>
<style>
.atras
{
	position: absolute;
	left: 545px;
	top: -10px;
}

</style>
<script>
var salario=0;
$(document).ready(function(e){
$('#editar_comi').click(function(e) {
        $(this).css('display','none');
		var comi=$('#comicion').html();
		$('#comicion').html('<form action="" method="post"><input type="text" name="comicion"  width="5" size="1" value="'+comi+'"><button>enviar</button></form>');
    });
	$('#editar_suel').click(function(e) {
        $(this).css('display','none');
		$('#sueldo').html('<form action="" method="post"><input type="text" name="sueldo"  width="22" size="10" value="'+salario+'"><button>enviar</button></form>');
    });

});

</script>

   <?php
if(!empty($_GET['extern']))
{
	echo "<a href='$_SERVER[HTTP_REFERER]'><div class='atras' id='atras'></div></a>";
}
?>

<div id="tablepanel">
  <ul>
    <li><a href="#tablepanel-1"><?php echo strtoupper($empleado['desc_carg'])?></a></li>
    <?PHP
	if($empleado['codi_carg']=='vend')
	{
		echo '<li><a href="#tablepanel-2">CLIENTES</a></li>
		<li><a href="#tablepanel-3">CUENTAS POR COBRAR</a></li>
       <li><a href="#tablepanel-4">VENTAS</a></li>';
	}
	   ?>
  </ul>
  <div id="tablepanel-1"><div class="produc">
          <h1><?php echo "$empleado[nom1_empl] $empleado[nom2_empl] $empleado[ape1_empl] $empleado[ape2_empl]"; ?> </h1>
          <table width="560" border="0" cellspacing="1" cellpadding="0" >
            <tr class="col_title">
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr class="col_hov  ">
              <td>CI : </td>
              <td><?php echo $empleado['ci_empl']?></td>
            </tr >
              <tr class="col_hov row_act ">
              <td>RIF : </td>
              <td><?php echo $empleado['rif_empl']?></td>
            </tr >
            <tr class="col_hov ">
              <td>EMAIL:</td>
              <td><?php echo $empleado['emai_empl'] ?></td>
            </tr >
            <tr class="col_hov  row_act">
              <td>TELEFONOS:</td>
              <td><?php
			  if($database->consulta("SELECT * FROM telefonos  WHERE id_tper='empl' and idet_pers='$empleado[ci_empl]'"))
	 while($telefono=$database->result())
	{
		echo $telefono['#telf']."  , ";
	} ?></td>
            </tr>
            <tr class="col_hov ">
              <td>DIRECCION:</td>
              <td><?php echo "$empleado[dire_empl] $empleado[desc_parr] $empleado[desc_muni] $empleado[desc_esta]"; ?></td>
            </tr>
              <tr class="col_hov row_act">
              <td>CARGO: </td>
              <td ><?php echo strtoupper( $empleado['desc_carg'])?> </td>
            </tr >
            
              <tr class="col_hov ">
              <td>DEPARTAMENTO: </td>
              <td ><?php echo strtoupper($empleado['desc_dept'])?> </td>
            </tr >
            <tr class="col_hov row_act">
              <td>SALARIO MENSUAL: <div class="edit" id='editar_suel'></div></td><script> salario= '<?php echo $empleado['sueldo']?>';</script>
              <td ><div id="sueldo" style="width:20px; float:left;"><?php echo fmt_num($empleado['sueldo'])?></div> </td>
            </tr >
            <?php
            if($empleado['codi_carg']=='vend')
			{ ?>
            <tr class="col_hov ">
              <td>PROCENTAJE DE COMICION : <div class="edit" id='editar_comi'></div></td>
              <td ><div id="comicion" style="width:20px; float:left;"><?php echo $empleado['porc_comi']/0.01?></div>%</td>
            </tr >
           <?php }?>
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
			  if($database->cuent_banc("idet_pers='".$empleado['ci_empl']."' and id_tper='empl'"))
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
          </table></div>
        </div>
  
    </div>
    <?php
	if($empleado['codi_carg']=='vend')
	{
		?>
  <div id="tablepanel-2"><div class="produc">
          <h1><?php 
		  $database->consulta(CLIENTES::CLIE,"ci_empl='$empleado[ci_empl]'");
		 
		  echo "$empleado[nom1_empl] $empleado[nom2_empl] $empleado[ape1_empl] $empleado[ape2_empl]"; ?> </h1>
          <table width="560" border="0" cellspacing="1" cellpadding="0" >
            <tr class="col_title">
              <td>IDENTIFICACION</td>
              <td>RASON SOCIAL</td>
              <td>DIRECCION</td>
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
              <td>$campo[codi_tide]$campo[idet_clie]</td>
              <td>$campo[nomb_clie]</td>
              <td>$campo[dire_clie] $campo[desc_parr] $campo[desc_muni] $campo[desc_esta]</td>
               <td><A href='../clientes/cliente_allinfo.php?idet_clie=$campo[idet_clie]&extern=true'><div class='buscar'></div></a></td>
            </tr>";
			}
			?>
           
          </table>
        </div>
  </div>
  
  <div id="tablepanel-3"> <div class="produc">
        <h1>NOTAS DE ENTREGA POR COBRAR </h1>
        <table width="600" border="0" cellspacing="3" cellpadding="0" >
 <thead><tr>
    <td>N° NOTA</td>
    <td>N° FACT</td>
    <td>CLIENTE</td>
    <td>MONTO</td>
    <td>PAGADO</td>
    <td>ADEUDA</td>
    <td>FECHA</td>
  </tr></thead> 
  <tbody>
  <?php
  $resul=$database->consulta(PRODUCTOS::VENTAS,"nota_entrg.ci_empl='".$_GET['ci_empl']."'".' GROUP BY nota_entrg.nume_nent','nume_nent DESC','LIMIT 30  ');
  $tota_bs=$total_pag=0;
for($i=0;$campo=$resul->fetch_array();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
  $database->consulta(PRODUCTOS::TOTAL_PAG_V,"nume_nent='$campo[nume_nent]'",NULL,'GROUP BY nume_nent');
   $pago=$database->result();
   if(number_format(($campo['total_bs']-$pago['total_pag']),2 , "." ,"")!=0)
   {
		echo "<tr class='col_hov  $row_act' >
		<td><a href='../nota_entrega/info_venta.php?nume_nent=".$campo['nume_nent']."'>".$campo['nume_nent']."</a></td>
		<td>".$campo['nume_fact']."</td>
		<td><a href='../clientes/cliente_allinfo.php?idet_clie=".$campo['idet_clie']."'>".$campo['nomb_clie']."</a> </td>
		<td>".fmt_num($campo['total_bs'])."</td>
		<td>".fmt_num($pago['total_pag'])."</td>
		<td>".fmt_num($campo['total_bs']-$pago['total_pag'])."</td>
		<td>".$campo['fech_nent']."</td>
		</tr>";
		$tota_bs+=$campo['total_bs'];
		$total_pag+=$pago['total_pag'];
   }
}
  ?></tbody>
  <tfoot>
  <tr>
    <td  colspan="6">&nbsp;</td>
  </tr>
  <TR>
 
  <td  colspan="5"> </td>
   <td  width="97"  class="row_act" >TOTAL</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($tota_bs);
			    ?>
           </td>
  </TR>
    <TR>
  <td  colspan="5"></td>
   <td  width="97"  class="row_act" >POR COBRAR</td>
          <td  width="71"  class="row_act" id="iva">
              <?PHP  
			echo fmt_num($tota_bs-$total_pag);
			    ?>
           </td>
  </TR>
  </tfoot>
</table>
      </div></div>
  
   <div id="tablepanel-4"><div class="produc">
          <h1><?php echo "$empleado[nom1_empl] $empleado[nom2_empl] $empleado[ape1_empl] $empleado[ape2_empl]"; ?> </h1>
          <table width="560" border="0" cellspacing="1" cellpadding="0" >
            <tr class="col_title">
              <td>MES</td>
              <td>VENTAS</td>
              <td>COMISION</td>
            </tr>
            <?php
			$time->actual_time();
			
			for($i=1;$i<=12;$i++)
			{
				if($i%2==0)
				$row_act='row_act';
				else
				$row_act='';
				$database->consulta(EMPLEADOS::VENT_VEND,"ci_empl='".$empleado['ci_empl']."'
				 and (fech_nent>='".$time->ano."-".$i."-01' and  fech_nent<'".$time->ano."-".($i+1)."-01')");
				
				$ventas=$database->result();
				$comision=$ventas['total_bs']*$empleado['porc_comi'];
				echo " <tr class='col_hov $row_act'>
              <td>".strtoupper($time->mes_cadena($i))."</td>
              <td>".fmt_num($ventas['total_bs'])."</td>
              <td>".fmt_num($comision)."</td>
            </tr >";
			}
			?>
          </table>
        </div>
  </div>
  <?php } ?>
  </div>
  

