<?php
require_once("../clases/config_orveinca.php");

$html= new HTML();
$database=new EMPLEADOS();
if($_POST)
{
	if($database->insertar_empleado($_POST))
	{
		redirec("buscar_empleados.php");
	}
}

$html->set_title("INGRESAR NUEVO EMPLEADO EN NOMINA");



?>
<style>
#comic{
	display:none;	
}
</style>
<script>
$(document).ready(function(e) {
    $('select[name=codi_carg]').change(function(e) {
        var carg=$(this).attr('value');
		if(carg=='vend')
		{
			$('#comic').fadeIn();
			$('input[name=porc_comi]').attr('value','');
		}else
		{
			$('input[name=porc_comi]').attr('value','NULL');
			$('#comic').fadeOut();	
		}
    });
	$('input[name=ci_empl]').keyup(function(e) {
        var value=e.target.value;
		$('input[name=rif_empl]').attr('value',str_replace('.','',value));
    }).focusout(function(e) {
         var value=e.target.value;
		$('input[name=rif_empl]').attr('value',$.FmtIdentificacion.Fmt(value));
    });
	$('input[name=rif_empl]').FmtIdentificacion('Default');
});


</script>
<div align="center" class="conten_ico"> <a href="buscar_empleados.php">
  <div class='atras' id="atras"  ></div>
  </a> </div>
<div class="form1" align="center">
  <div align="center"></div>
  <h1>INGRESAR NUEVO EMPLEADO EN NOMINA </h1>
  <div align="center">
  <form class="contact_form form" action="" METHOD="post" enctype="multipart/form-data" name="frmDatos" id="frmDatos">
    <ul>
      <li class="form_row">
        <label for="nombre">NOMBRE</label>
        <input type="text" name="nom1_empl" placeholder="nombre" required  class="main_input"/>
      </li>
      <li class="form_row">
        <label for="nombre">SEGUNDO NOMBRE</label>
        <input type="text" name="nom2_empl"  placeholder="segundo nombre" required  class="main_input"/>
      </li>
      <li class="form_row">
        <label for="nombre">APELLIDO</label>
        <input type="text" name="ape1_empl" placeholder="apellido" required   class="main_input"/>
      </li>
      <li class="form_row">
        <label for="nombre">SEGUNDO APELLIDO</label>
        <input type="text" name="ape2_empl" placeholder="segundo apellido" required   class="main_input"/>
        <br>
      </li>
      <li class="form_row">
        <label for="nombre">ENAIL</label>
        <input type="email" name="emai_empl" placeholder="@email"   class="main_input"  />
      </li>
      <li class="form_row">
        <label for="t_ident" >CI</label>
        <input form="frmDatos" type="TEXT" name="ci_empl" id="ci" placeholder="CI" required  class="main_input" />
      </li>
       <li class="form_row">
        <label for="t_ident" >RIF</label>
        <input form="frmDatos" type="TEXT" name="rif_empl"   placeholder="rid" required  class="main_input" />
      </li>
      <li class="form_row">
   
      <?PHP
	  
	  echo $html->form_telef();
	  ?>
      </li>
      <li class="form_row">
        <?php
		echo $html->form_cuet_banc();
		
		?>
      </li>
      <li class="form_row">
      <?php
	  echo $html->form_direccion("id_parr","dire_empl");
	  ?>
        <BR>
      </li>
       <li class="form_row">
        <label>CARGO</label>
        <select name="codi_carg" required>
        <option value=" ">SELECCIONE CARGO</option>
        <?php
		$database->consulta("SELECT * FROM cargos");
		while($campo=$database->result())
		{
			echo " <option value='".$campo['codi_carg']."'>".$campo['desc_carg']."</option>";
		}
		?>
       
        </select>
      </li>
       <li class="form_row">
        <label>DEPARTAMENTO</label>
      <select name="codi_dept" required>
       <option value=" ">SELECCIONE DEPARTAMENTO</option>
        <?php
		$database->consulta("SELECT * FROM departamen");
		while($campo=$database->result())
		{
			echo " <option value='".$campo['codi_dept']."'>".$campo['desc_dept']."</option>";
		}
		?>
      </select>
      </li>
      <li class="form_row" id="comic">
        <label>PORCENTAJE DE COMICION</label>
        <input type="text" name="porc_comi"  placeholder="COMICION"  class="main_input" value="NULL">
      </li>
       <li class="form_row">
        <label>SALARIO MENSUAL</label>
        <input type="text" name="sueldo"  placeholder="SALARIO"  class="main_input" >
      </li>
      <li class="form_row">
        <button class="submit" form="frmDatos" type="reset" value="EMPLEADO" id="b_reset">RESET</button>
        <button class="submit" type="submit" value="entrar"> ENVIAR </button>
      </li>
    </ul>
  </form>
  </div>
</div>

