<?php
require_once("../clases/config_orveinca.php");

$html= new HTML();
$database= new CONTACTOS();

if(!empty($_POST['nom1_cont']))
{
	if($ci_cont=$database->insertar_contacto($_POST))
	if(!empty($_POST['tab']))
	{
		$database->consulta("UPDATE ".$_POST['tab']." SET ci_cont='".$ci_cont."' where ".$_POST['tident']."='".$_POST['idet']."'");
		if(!$database->error())
		redirec($_POST['redir']."&".$_POST['tident']."=".$_POST['idet']);
		
	}else
	{
		redirec($_POST['redir']."&".$_POST['tident']."=".$_POST['idet']);
	}		
}


$html->set_title("INSERTAR");


?>
<div align="center" class="conten_ico" ><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">
  <div class="atras" id="atras"></div></a>
 
</div>

<div class="form1 "   align="center">
  
  <div align="center"></div>
  <h1>INSERTAR NUEVO CONTACTO</h1>
  <form class="contact_form form" action="new_contacto.php" METHOD="Post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
  <input type="hidden" name="tab" value="<?php echo $_GET['tab'] ?>">
  <input type="hidden" name="idet" value="<?php echo $_GET['idet'] ?>">
   <input type="hidden" name="tident" value="<?php echo $_GET['tident'] ?>">
   <input type="hidden" name="redir" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
    <ul>
      <li class="form_row contacto_new">
        <label >NOMBRE</label>
        <input type="text" name="nom1_cont" placeholder="nombre"  class="main_input"/>
      </li>
      <li class="form_row contacto_new">
        <label >SEGUNDO NOMBRE</label>
        <input type="text" name="nom2_cont" placeholder="segundo nombre"  class="main_input"/>
      </li>
      <li class="form_row contacto_new">
        <label >APELLIDO</label>
        <input type="text" name="ape1_cont" placeholder="apellido"  class="main_input"/>
      </li>
      <li class="form_row contacto_new">
        <label >SEGUNDO APELLIDO</label>
        <input type="text" name="ape2_cont" placeholder="segundo apellido"  class="main_input"/>
      </li>
      <li class="form_row contacto_new">
        <label >EMAIL</label>
        <input type="email" name="emai_cont" placeholder="@email"  class="main_input"  />
      </li>
    
      <li class="form_row contacto_new">
      <?php
        echo $html->form_telef('contacto_');
		?>
      </li>
     
      <li class="form_row" id="contacto_bus" > </li>
      <li class="form_row">
        <button class="submit" form="frmDatos" type="submit" name="boton">ENVIAR</button>
      </li>
    </ul>
  </form>
</div>

<script type="text/javascript">


$(document).ready(function() {
    
	$('button[name=boton]').click(function(e) {
     
			if(($('input[name=nom1_cont]').val()!='' && $('input[name=ape1_cont]').val()=='') || ($('input[name=nom1_cont]').val()=='' && $('input[name=ape1_cont]').val()!=''))
			{
				e.preventDefault();
				error('ERROR FORMULARIO DE CONTACTO INCOMPLETO','LOS DATOS MINIMOS DE UN CONTACTO SON EN PRIMER NOMBRE Y EL PRIMER APELLIDO');
				return 1;
			}
    });
});

</script>

