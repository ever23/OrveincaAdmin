<?php

require ("../clases/config_orveinca.php");
$database= new BD_ORVEINCA();
$Session= new SESSION(false);
if($_POST && !empty($Session->GetVar('permisos')) && $Session->GetVar('permisos')==__AUTORIZATE_ROOT__ )
{
	if($database->consulta("INSERT INTO users VALUES('".$_POST['nombre']."','".md5($_POST['pass'],__LLAVEMD5__)."','".$_POST['permisos']."')"))
	{
		redirec("../defaut/defaut.php");
	}
}


$html=new HTML();

	
if($Session->GetVar('permisos')!=__AUTORIZATE_ROOT__)
{
	$html->__destruct();
}
 ?>
<script>
$(document).ready(function(e) {
    
	$('button').click(function(e) {
      if(  $('input[name=pass]').val()!=$('input[name=pass1]').val())
	  {
		  error('ERROR','LAS CONTRASEÑAS NO COINCIDEN');
		  e.preventDefault();
	  }
    });
});
</script>
<div class="form1" align="center">
  <div align="center"></div>
  
  <h1>REGISTRAR NUEVO USUARIO</h1>
  <form class="contact_form" action="" METHOD="Post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
    <ul>
      <li class="form_row">
        <label for="name">Nombre:</label>
        <input name="nombre" type="text" required id="nombre"  placeholder="mailto"  class="main_input" />
      </li>
      <li class="form_row">
        <label for="pass">Contraseña:</label>
        <input type="password" name="pass1" placeholder="*************" required  class="main_input" maxlength="16" />
      </li>
      <li class="form_row">
        <label for="pass">Repita Contraseña:</label>
        <input type="password" name="pass" placeholder="*************" required  class="main_input" maxlength="16" />
      </li>
      <li class="form_row">
        <label for="pass">Permisos:</label>
        <select name="permisos">
          <!--<option value="5246">VENDEDOR </option>-->
          <option value="6454">ADMINISTRADOR </option>
          <option value="8247">FULL ACCESO</option>
        </select>
      </li>
      <li class="form_row">
        <button class="submit" type="submit" name="entrar" value="entrar">Entrar</button>
      </li>
    </ul>
  </form>
</div>
