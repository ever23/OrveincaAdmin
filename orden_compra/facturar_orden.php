<?PHP 


//funciones mysql
require_once("../clases/config_orveinca.php");
//numero de lineas para el salto 
$html= new HTML();
$database= new PRODUCTOS();
if($_POST)
{
    if($database->facturar_compra($_POST))
    {
        redirec("compras.php?nume_orde=".$_POST['nume_orde']);
    }
}
//FUNCIONES DE USUSARIO

//FIN DE LA FUNCIONES
$conf=$database->config();
$html->set_title("ENTREGAR PEDIDO");

$html->prettyPhoto();


if(empty($_GET['nume_orde']))
{
    $html->__destruct();	
}
?>
<script type='text/javascript'>
    var iva=<?php echo  $conf['iva'];?>;
    $(document).ready(function() 
                      {
        $('.cancelar_orde').click(function(e) {
            e.preventDefault();
            var prod=$(this).attr('href');
            $( '#dialog:ui-dialog' ).dialog('destroy' );
            $( '#errores' ).attr('title','ELIMINAR PRODUCTO');
            $( '#error_div' ).html('<h3>ESTA SEGUR@ DE QUE DESEA NO DESEA INCLUIR EL PRODUCTO EN LA FACTURACION </H3>');

            $( '#errores' ).dialog({

                height:300,
                modal: true,
                buttons: {
                    'SI': function() {
                        $( '#errores' ).dialog( 'close' );
                        $('#'+prod).fadeOut(function(){ $('#'+prod).html('');  total_bsf(); });

                    },
                    'NO': function() {
                        $( '#errores' ).dialog( 'close' );
                    }
                }
            });

        });
        $('.cant_reci').keyup(function(e) {
            var cant_reci=$(this).attr('value');
            var row=$(this).attr('title');
            var cant_orde=$('.cant_orde[title='+row+']').attr('value');
            if(Number(cant_reci)>Number(cant_orde))
            {
                $(this).attr('value',cant_orde);
            }

        });
        $('.cost_orde').keyup(function(e) {

            var cost_orde=$(this).attr('value');
            var row=$(this).attr('title');
            var cant_orde=$('.cant_orde[title='+row+']').attr('value');
            var tbs=cost_orde*cant_orde;
            $('#tbs'+row).html(tbs);
            total_bsf();

        });
        $('.cant_orde').keyup(function(e) {
            var cant_orde=$(this).attr('value');
            var row=$(this).attr('title');
            var cost_orde=$('.cost_orde[title='+row+']').attr('value');
            var tbs=cost_orde*cant_orde;
            $('#tbs'+row).html(tbs);
            total_bsf();

        });

    });
    function total_bsf()
    {
        var sub_total=0;
        var total=0;
        var total_p=0;
        for(var i=0;$('#'+i).html()!=undefined;i++)
        {
            if($('#tbs'+i).html()!=undefined)
            {
                sub_total+=Number($('#tbs'+i).html());
                total_p+=Number($('.cant_orde[title='+i+']').attr('value'));
            }


        }
        $('#sub_total').html(Number(sub_total));
        var ivs=sub_total*iva;
        $('#iva').html(Number(ivs));
        $('#total').html(Number(sub_total)+Number(ivs));
        return total_p;
    }
</script>
<style type="text/css">
    a { color: rgba(0,0,0,1.00); }
</style>
<div align="center" class="conten_ico" > <a href="buscar_ordecomp.php">
    <div class="atras" id="atras"></div>
    </a> <a href="buscar_ordecomp.php">
    <div class='buscar buscar_pedi' id="busqueuda"  ></div>
    </a> </div>
<div class="form1" align="center">
    <div align="center"></div>
    <h2></h2>
    <h1>FACURAR ORDEN DE COMPRA </h1>
    <form action="" method="post" >
        <div class="info" align="center">
            <?PHP
if($database->consulta(PRODUCTOS::ORDE_COMP,"nume_orde='".$_GET['nume_orde']."'"))
{
    $orden_comp=$database->result();
    if($orden_comp['esta_orde']!='P')
    {
        echo "<h2>LA ORDEN DE COMPRA  N° ".$_GET['nume_orde']." NO SE PUEDE FACTURAR</h2></div></div>";
        $html->__destruct();


    }
    $database->consulta(PROVEDORES::PROV,"idet_prov='".$orden_comp['idet_prov']."'");
    $provedor=$database->result();
}


            ?>
            <table width="560" border="0" cellspacing="1" cellpadding="0">
                <tr class="col_title">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="col_hov row_act ">
                    <td>PROVEEDOR</td>
                    <td><?php echo $provedor['nomb_prov']?></td>
                </tr >
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
                if($database->consulta("SELECT * FROM telefonos  WHERE id_tper='prov' and idet_pers='$provedor[idet_prov]'"))		  
                while($telefono=$database->result())
            {
                echo $telefono['#telf'].", ";
            } ?></td>
                </tr>
                <tr class="col_hov row_act">
                    <td>DIRECCION:</td>
                    <td><?php echo "$provedor[dire_prov] , PARROQUIA: ".$provedor['desc_parr'].", MUNICIPIO: ".$provedor['desc_muni'].", ESTADO: ".$provedor['desc_esta']; ?></td>
                </tr>
                <tr class="col_hov ">
                    <td>CONTACTO:</td>
                    <td><?php echo $provedor['nom1_cont']." ".$provedor['nom2_cont'] ; ?></td>
                </tr>
                <tr class="col_hov ">
                    <td>NUMERO DE FACTURA :</td>
                    <td><input type="text" required  name="nume_fac"></td>
                </tr>
            </table>
        </div>
        <div id="conten_html" style="display:block;">
            <div>
                <h3>PRODUCTOS </h3>
                <?php
echo " <input type='hidden' name='idet_prov' value='".$orden_comp['idet_prov']."'>";
echo " <input type='hidden' name='nume_orde' value='".$orden_comp['nume_orde']."'>";
                ?>
                <table width="901" border="0" cellspacing="1" cellpadding="0">
                    <tr class="col_title">
                        <td width="68" scope="col"  >CODIGO
                    </th>
                    <td  width="480"  scope="col"  >DESCRIPCION </td>
                    <td  width="100"  scope="col"  >COLOR </td>
                    <td  width="100"  scope="col"  >MEDIDA </td>
                    <td  width="100"  scope="col"  >PRECIO U </td>
                    <td  width="100"  scope="col"  >CANTIDAD </td>
                    <td width="50" scope="col" >TOTAL </td>
                    <td  width="100"  scope="col"  >RECIBIDO </td>
                    <td width="30" scope="col" ></td>
                    </tr>
                <?PHP
$buff="";
$subtotal=0;
if($database->consulta(PRODUCTOS::ORDE_COMP_PROD,"nume_orde='".$_GET['nume_orde']."'"))
    for($i=0;$campo=$database->result();$i++)
{
    if($i%2==0) 
        $row_act='';
    else
        $row_act='row_act';
    echo  "
		<input type='hidden' name='id_orpr[".$i."]' value='".$campo['id_orpr']."'>
		<tr class='col_hov  $row_act' id='".$i."'>
		<td>".$campo['codi_clpr'].$campo['id_prod']."</td>
		<td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."</td>
		<td>".$campo['desc_colo']."</td>
		<td>".$campo['codi_umed']." ".$campo['medi_tama']."</td>
		<td><input type='text' name='cost_orde[".$i."]' value='".$campo['cost_orde']."' required size='7' class='cost_orde' title='".$i."'></td>
		<td><input type='text' name='cant_orde[".$i."]' value='".$campo['cant_orde']."' required size='7'  class='cant_orde' title='".$i."'></td>
		<td align='center' id='tbs".$i."'> ".$campo['totalbs']."</td>
		<td><input type='text' name='cant_reci[".$i."]' value='0'  size='7' class='cant_reci' title='".$i."'  ></td>
		<td><a href='".$i."' class='cancelar_orde'><div class='elimina'></div></a></td>
		</tr>";   
    $subtotal+=$campo['totalbs'];
}


$iva=$conf['iva']*$subtotal;
$total=$subtotal+$iva;;
                ?>
                </table>
            <br>
            <br>
            <table width="901" border="0" cellspacing="2" cellpadding="0">
                <tr >
                    <th scope="col" colspan="2">&nbsp;&nbsp;&nbsp; </th>
                    <th scope="col" width="97" class="row_act" >SUB-TOTAL</th>
                    <th scope="col" width="71" class="row_act" id="sub_total">
                        <?PHP  echo  fmt_num($subtotal);?>
                    </th>
                </tr>
                <tr>
                    <th scope="col" colspan="2">&nbsp;</th>

                    <th scope="col" width="97"  class="row_act" >+I.V.A <?php echo $conf['iva']/0.01  ?>%</th>
                    <th scope="col" width="71"  class="row_act" id="iva">
                        <?PHP  
                    echo fmt_num($iva );
                        ?>
                    </th>
                </tr>
                <tr>
                    <th scope="col" colspan="2">&nbsp;</th>
                    <th scope="col" width="97"  class="row_act" >TOTAL</th>
                    <th scope="col" width="71"  class="row_act" id="total">
                        <?php echo  fmt_num($total); ?>
                    </th>
                </tr>
            </table>
        </div>
        </div>
    <button class="submit" type="submit" name="boton" value="">Enviar</button>
    </form>
</div>

