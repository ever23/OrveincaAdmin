<?php
require_once("../clases/config_orveinca.php");

$time= new TIME();
$html= new INFO_HTML();


$database= new PRODUCTOS();
if($_POST)
{
	$http=['conf-iva'=>$_POST['conf-iva']*0.01,'conf-precio1'=>$_POST['conf-precio1']*0.01,'conf-precio2'=>$_POST['conf-precio2']*0.01,'conf-precio3'=>$_POST['conf-precio3']*0.01];
	$database->edit_config($http);
	    
}
?>



<div id="conten_html" class="produc" align="center">
 <div class="head-border-baj ct_title"><b>CONFIGURACIONES DE PRECIOS </b></div> 

  <?PHP
if($_POST && !$database->error())
{
	echo "<H2>SE A MODIFICADO LA CONFIGURACION CON EXITO</H2>";
}
$conf=$database->config();

?>
  <form action="" name="conf" method="post">
    <table>
     
      <tr>
       <td> IVA </td>
        <td><input name="conf-iva" value="<?php echo $conf['iva']/0.01?>"></td>
      </tr>
      <tr>
        <td> % PRECIO 1 </td>
      <td><input name="conf-precio1" value="<?php  echo $conf['precio1']/0.01?>"></td>
       
      </tr>
      <tr>
        <td> % PRECIO 2 </td>
       <td><input name="conf-precio2" value="<?php  echo $conf['precio2']/0.01?>"></td>
       
      </tr>
      <tr>
      <td> % PRECIO 3 </td>
       <td><input name="conf-precio3" value="<?php  echo $conf['precio3']/0.01?>"></td>
      </tr>
    </table>
    <div align="center">
      <button class="submit" value="env" name="echo">GUARDAR</button>
    </div>
  </form>
</div>
</div>

<div class="load_catalogo"></div>
<div id="barra_load_pdf">
  <div id="barra_load_"></div>
</div>
