<?PHP 
//funciones mysql
require_once("../clases/config_orveinca.php");

//numero de lineas para el salto 
$database= new PRODUCTOS();
if($_POST)
{
	
	$database->autocommit(FALSE);
	$database->consulta("TRUNCATE TABLE `tem_nent_prod`");
	foreach($_POST['id_prod'] as $i=>$id_prod)
	{
		if($_POST['cant_orde'][$i]>0)
		{
			if(!$database->temp_entrega(
			array(
			'id_prod'=>$id_prod,
			'id_tama'=>$_POST['id_tama'][$i],
			'exad_colo'=>$_POST['exad_colo'][$i],
			'cant_orde'=>$_POST['cant_orde'][$i],
			'prec_venta'=>$_POST['prec_vent'][$i],
			'nume_pedi'=>$_POST['nume_pedi']
			)
			,NULL))
			break;
			
		}
		
		
	}
	if(!$database->error())
	{
		$database->commit();
		redirec("../nota_entrega/nota_entrega.php?idet_clie=".$_POST['idet_clie']);
		
	}else
	{
		$database->rollback();
		$database->autocommit(true);
	}
	
	
}
$database->consulta("TRUNCATE TABLE `tem_nent_prod`");
//FUNCIONES DE USUSARIO

	//FIN DE LA FUNCIONES

$html= new HTML();
$html->set_title("ENTREGAR PEDIDO");

$html->prettyPhoto();


if(empty($_GET['nume_pedi']))
{
	$html->__destruct();	
}
?>
<script type='text/javascript'>

$(document).ready(function() 
{
	$('.elimina_entr').click(function(e) {
        e.preventDefault();
		var value=$(this).attr('href');
		$('#'+value).fadeOut(400,null,function(){ $('#'+value).html(''); });
    });
     $('button[name=boton]').click(function(e)
    {
        for(var i=0;$('.cant_orde[lang='+i+']').html()!=undefined;i++)
	   {
		
              if(Number($('.cant_orde[lang='+i+']').attr('value'))>Number($('.cant_orde[lang='+i+']').attr('title')))
              {
                 
               
               error('ERROR',"LA CANTIDAD DR UNO O MAS PRODUCTOS  SUPERA LA DEL PRODUCTO POR ENTREGAR");
                 // alert("LA CANTIDAD DR UNO O MAS PRODUCTOS  SUPERA LA DEL PRODUCTO POR ENTREGAR");
                   e.preventDefault();
              } 
       }
     
     });
	
});
</script>
<style type="text/css">

 
a{ color:rgba(0,0,0,1.00);
}
</style>
<div align="center" class="conten_ico" > <a href="buscar_pedido.php">
  <div class="atras" id="atras"></div>
  </a>
 
  <a href="buscar_pedido.php">
  <div class='buscar buscar_pedi' id="busqueuda"  ></div>
  </a> </div>
<div class="form1" align="center">
  <div align="center"></div>
  <h2></h2>
  <h1>INSERTAR EN NOTA DE ENTREGA</h1>
  
  
  <div id="conten_html" style="display:block;">
  <div>
  <h3>DISPONIBLE PARA ENTREGAR</h3>
  <form action="" method="post" >
  <?php
  $database->consulta(PRODUCTOS::PEDIDOS,"nume_pedi='".$_GET['nume_pedi']."'");
  $pedi_clie=$database->result();
  echo " <input type='hidden' name='idet_clie' value='".$pedi_clie['idet_clie']."'>
  <input type='hidden' name='nume_pedi' value='".$_GET['nume_pedi']."'>";
  ?>
  <table width="900" border="0" cellspacing="1" cellpadding="1" >
  <tr class="col_title">
    <td>CODI</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
    <td>CANT</td>
    <td>EXIST</td>
    <td></td>

  </tr>
  
  <?PHP
  $buff="";
  $result=$database->consulta(PRODUCTOS::PEDI,"nume_pedi='".$_GET['nume_pedi']."'",'cant_entr ASC');
  $row=TRUE;
  $i=0;
  while($pedido=$result->fetch_array())
  {
	  if($pedido['cant_entr']==$pedido['cant_pedi'] || $pedido['cant_entr']==NULL)
	  continue;
	   if($database->consulta(PRODUCTOS::INVENTARIO1,"id_prod=$pedido[id_prod] and id_tama=$pedido[id_tama] and exad_colo ".BD_ORVEINCA::sql_null($pedido['exad_colo'],'S')))
	   {
		   if($database->result->num_rows>0)
		   {
			 $inventario=$database->result();
			 $database->consulta("
		SELECT SUM(nent_prod.cant_nent) as cant_vend
FROM nent_prod
WHERE id_prod='$inventario[id_prod]' and  id_tama= '$inventario[id_tama]' and exad_colo ".($inventario['exad']!=''?"='$inventario[exad]'":" IS NULL ")."
GROUP by id_prod,exad_colo,id_tama 
		");
		
		$vendio=$database->result();
		if(($existencia=$inventario['cant_reci']-$vendio['cant_vend'])>0)
		{
	 		 if($row) 
			 $row_act='';
			 else
  			 $row_act='row_act';
			 $row=!$row;
			if($existencia>$pedido['cant_pedi']-$pedido['cant_entr'])
		  	{
			   $cantidad=$pedido['cant_pedi']-$pedido['cant_entr'];
		   	}else
		  	{
			    $cantidad=$existencia;
		 	}
			 echo  "
			 <tr class='col_hov  $row_act' id='".$i."'>
			  <input type='hidden' name='id_prod[".$i."]' value='".$pedido['id_prod']."'>
			    <input type='hidden' name='id_tama[".$i."]' value='".$pedido['id_tama']."'>
				<input type='hidden' name='exad_colo[".$i."]' value='".$pedido['exad_colo']."'>
				
			   
			<td>".$pedido['codi_clpr'].$pedido['id_prod']."</td>
			<td>".stripslashes("$pedido[desc_prod] $pedido[desc_mode] $pedido[desc_marc] ")."</td>
			<td>".$pedido['desc_colo']."</td>
			<td>".$pedido['codi_umed']." ".$pedido['medi_tama']."</td>
			<td><input type='text' name='prec_vent[".$i."]' value='".$pedido['prec_vent']."' size='7'></td>
			<td><input type='text' name='cant_orde[".$i."]' value='".$cantidad."' class='cant_orde' lang='".$i."' title='".$cantidad."' size='7'></td>
			<td>".$existencia."</td>
			<td><a href='".$i."' class='elimina_entr'><div class='elimina'></div></a></td>
			</tr>";
			$i++;
		}
		   }
	   }
  }
 
	if($i==0)
	{
		
		 echo "<tr>
	 <td colspan='9'><H2>NO SE ENCONTRO NINGUN  PRODUCTO DE ESTE PEDIDO EN EL INVENTARIO</H2></td>
	 </tr>"; 
	}
  ?></table><br>
<br>

    <button  class="submit" type="submit" name="boton" value="">Enviar</button>
  </form>
  </div>
  <br>
<br>
<br>

    <H3>PEDIDO COMPLETO</H3>
   <table width="900" border="0" cellspacing="1" cellpadding="0" >
  <tr class="col_title">
    <td>CODI</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
    <td>CANT</td>
    <td>ENTREG</td>
  </tr>
    <?PHP
	$result->data_seek(0);
	$row=true;
	 while($pedido=$result->fetch_array())
	 {
		  if($row) 
			 $row_act='';
			 else
  			 $row_act='row_act';
			 $row=!$row;
		 $cancel='';
  		 if($pedido['cant_entr']==NULL)
  		 {
	    		$estado='C';
		 		$cancel='cancel';
   			}else
   			if($pedido['cant_entr']<$pedido['cant_pedi'])
   			{
	  			  $estado='P';
 			  }
  			 else
  			 {
	   			 $estado='E';
  			 }
		 echo "<tr class='col_hov  $row_act $cancel'>
			<td>".$pedido['codi_clpr'].$pedido['id_prod']."</td>
			<td>".stripslashes("$pedido[desc_prod] $pedido[desc_mode] $pedido[desc_marc] ")."</td>
			<td>".$pedido['desc_colo']."</td>
			<td>".$pedido['codi_umed']." ".$pedido['medi_tama']."</td>
			<td>".fmt_num($pedido['prec_vent'])."</td>
			<td>".$pedido['cant_pedi']."</td>
			<td class='$estado'>".$pedido['cant_entr']."</td>
			</tr>";
	 }

	?>
    </table>
  </div>
</div>
