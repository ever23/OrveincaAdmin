<?php
require_once("../clases/config_orveinca.php");
$time= new TIME();
$html= new INFO_HTML();


$database= new  BD_ORVEINCA();
if($_POST)
{
	$database->edit_config($_POST);
	$database->consulta("UPDATE empleados SET sueldo='".$_POST['conf-sueldo_min']."' where sueldo<".$_POST['conf-sueldo_min']."");
	
}
?>
<div id="conten_html" class="produc" align="center">
 <div class="head-border-baj ct_title"><b>CONFIGURACIONES DE NOMINA </b></div> 
  <?PHP
if($_POST && !$database->error())
{
	echo "<H2>SE A MODIFICADO LA CONFIGURACION CON EXITO</H2>";
	//print_r($_POST);
}
$conf=$database->config();

?>
  <form action="" name="conf" method="post">
    <table>
     
      <tr>
       <td> SUELDO MINIMO </td>
        <td><input name="conf-sueldo_min" value="<?php echo $conf['sueldo_min']?>"></td>
      </tr>
      <tr>
        <td> CESTA TIKE </td>
      <td><input name="conf-cest_tike" value="<?php  echo $conf['cest_tike']?>"></td>
       
      </tr>
      <tr>
        <td> s.o.s </td>
       <td><input name="conf-s_o_s" value="<?php  echo $conf['s_o_s']?>"></td>
       
      </tr>
      <tr>
      <td> l.p.h </td>
       <td><input name="conf-l_p_h" value="<?php  echo $conf['l_p_h']?>"></td>
      </tr>
      <tr>
      <td> s.p.f </td>
       <td><input name="conf-s_p_f" value="<?php  echo $conf['s_p_f']?>"></td>
      </tr>
    </table>
    <div align="center">
      <button class="submit" value="env" name="echo">GUARDAR</button>
    </div>
  </form><br>
<br>
<br>
<br>
<A href="cargo.php"><h2>INSERTAR CARGO </h2></A><br>
<br>
<A href="dpt.php"><h2>INSERTAR DEPARTAMENTO </h2></A>
</div>

</div>

<div class="load_catalogo"></div>
<div id="barra_load_pdf">
  <div id="barra_load_"></div>
</div>
