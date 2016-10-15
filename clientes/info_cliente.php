<?php
require_once("../clases/config_orveinca.php");
$database= new CLIENTES();
$error='';

if(!$database->error())
if($_POST)
{
	if(empty($_POST['redirec']))
	{
		$redirec='buscar_info_cliente.php';	
	}else
	{
		$redirec=$_POST['redirec'];
	}
	$cont=true;
	$database->autocommit(FALSE);
	$pos=$_POST;
	if($_POST['nom1_cont']!='' && $_POST['nom1_cont']!='')
	{
		$cont=false;
		 
		$database= new CONTACTOS;
		if($contacto=$database->insertar_contacto($_POST))
		{
			$cont=true;
		}
		$pos=array_merge(['ci_cont'=>$contacto],$_POST);
		
	}
	$database= new CLIENTES;
	if($cont)
	if($idet_clie=$database->insertar_cliente($pos))
	Server::Redirec($redirec,['opcion'=>'NULL','text'=>$_POST['codi_tide'].$idet_clie,'codi_tide'=>$_POST['cod_tide'],'idet_clie'=>$idet_clie]);
}

$html= new HTML();
$html->set_title("CLIENTES");
?>

<div align="center" class="conten_ico">
  <?php
	if(!empty($_GET['redirec']) && !empty($_SERVER['HTTP_REFERER']))
	{
		echo  "<a href='buscar_info_cliente.php?redirec=$_GET[redirec]'><div class='buscar'></div></a>";
		echo "<a href='$_SERVER[HTTP_REFERER]'>";		
	}else
	{
		echo '<a href="buscar_info_cliente.php">';
	}
	
	?>
  <div class="atras" id="atras"></div>
  </a> </div>
<div  align="center">
  <div class="form1  form">
    <div align="center"></div>
    <h1>INSERTAR NUEVO CLIENTE</h1>
    <form class="contact_form" action="info_cliente.php" METHOD="Post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
      <ul>
        <li class="form_row">
          <label for="vend">VENDEDOR</label>
          <?php
	if(!empty($_GET['redirec']))
	{
		echo "<input type='hidden' name='redirec' value='$_GET[redirec]'>";
	}
	?>
          <select name="ci_empl" form="frmDatos">
            <option value='null'>selecciona vendedor</option>
            <?php 
		$database->consulta("SELECT * FROM empleados where codi_carg='vend';");
		while($campo =$database->result())
		{
			echo " <option value='$campo[ci_empl]'>$campo[nom1_empl] $campo[ape1_empl] </option>";
		} 
		?>
          </select>
        </li>
        <li class="form_row">
          <label for="name">RASON SOCIAL</label>
          <input name="nomb_clie" form="frmDatos" type="text" required id="nombre"  placeholder="mailto"  class="main_input" />
        </li>
        <li class="form_row">
          <label for="t_ident" >IDENTIFICACION</label>
          <select name="codi_tide" form="frmDatos">
            <?php
		$database->consulta("SELECT * FROM  t_ident");
		while($campo=$database->result())
		{
			echo "<option value='$campo[codi_tide]' > $campo[codi_tide]</option>";
		}
		?>
          </select>
          <br>
          <input form="frmDatos" type="TEXT" name="idet_clie" placeholder="identificacion" required id="rif"  class="main_input" />
          <div id="msj_idet"></div>
        </li>
        <li class="form_row">
          <label for="email">EMAIL</label>
          <input type="email" form="frmDatos" name="emai_clie" placeholder="@email "   class="main_input"  />
        </li>
        <li class="form_row">
          <?php 
	  echo $html->form_telef();
	   ?>
        </li>
        <li class="form_row">
          <?php
	 echo  $html->form_direccion('id_parr','dire_clie');
	  ?>
        </li>
        <li class="form_row">
          <div class="buscar" id="buscar_enc"></div>
          <div class="new2" id="new_enc"></div>
          <h2>CONTACTO</h2>
        </li>
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
          <button class="submit" form="frmDatos" type="reset" name="reset" value="VENDEDOR" id="b_reset">RESET</button>
          <button class="submit" form="frmDatos" type="submit" name="boton">ENVIAR</button>
        </li>
      </ul>
    </form>
  </div>
</div>
<script>
$(document).ready(function(e) {
	<?php
	if(!empty($_GET['redirec']) && !empty($_SERVER['HTTP_REFERER']))
	{
		$HREF=  "$_GET[redirec]";
		
	}else
	{
		$HREF= 'buscar_info_cliente.php';
	}
		?>
	$('button[name=boton]').click(function(e) {
     
			if(($('input[name=nom1_cont]').val()!='' && $('input[name=ape1_cont]').val()=='') || ($('input[name=nom1_cont]').val()=='' && $('input[name=ape1_cont]').val()!=''))
			{
				e.preventDefault();
				error('ERROR FORMULARIO DE CONTACTO INCOMPLETO','LOS DATOS MINIMOS DE UN CONTACTO SON EN PRIMER NOMBRE Y EL PRIMER APELLIDO');
				return 1;
			}
    });
	
	$('input[name=idet_clie]').focusout(function(e) {
		var codi_tide=$('select[name=codi_tide]').val();
		var idet_clie=$(this).val();
		if(idet_clie.length>0)
		{
     		 $().load_json('cliente_ajax.php',{VERIFICA_CLIE:true,'idet_clie':idet_clie,'codi_tide':codi_tide},
	  		function(json){
			if(json.idet_exist)
			  {
			  
			  error(' ',' YA EXISTE UN CLIENTE REGISTRADO CON ESTA IDENTIFICACION <a href="<?php echo $HREF ?>?opcion=NULL&text='+json.codi_tide+json.idet_clie+'&codi_tide='+json.codi_tide+'&idet_clie='+json.idet_clie+'"><h2>'+json.codi_tide+json.idet_clie+'<br>'+json.nomb_clie+'</h2></a>');
			
		 	 }
		  });
		}
    });
	
});
</script>

