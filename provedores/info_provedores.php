<?php
require_once("../clases/config_orveinca.php");
$html=new HTML();
//conectar al servidor 
$database= new PROVEDORES();
if(empty($_POST['redirec']))
{
	$redirec='buscar_provedores.php';	
}else
{
	$redirec=$_POST['redirec'];
}
$error='';
if(!$database->error())
if($_POST)
{ 
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
			$pos=array_merge(['ci_cont'=>$contacto],$_POST);
		}
	
	}
	$database= new PROVEDORES;
	if($cont)
	if($database->isertar_provedor($pos))
	Server::Redirec($redirec,['codi_tide'=>$_POST['codi_tide'],'idet_prov'=>$_POST['idet_prove'],'text'=>$_POST['codi_tide'].$_POST['idet_prove']]);
}
$database->autocommit(TRUE);


$html->set_title("NUEVO PROVEDOR");


?>

<div align="center" class="conten_ico" >
  <?php
	if(!empty($_GET['redirec'])  && !empty($_SERVER['HTTP_REFERER']))
	{
		echo  "<a href='buscar_provedores.php?redirec=$_GET[redirec]'><div class='buscar'></div></a>";
		echo "<a href='$_SERVER[HTTP_REFERER]'>";		
	}else
	{
		echo '<a href="buscar_provedores.php">';
	}
	?>
  <div class="atras" id="atras"></div>
  </a> </div>
<div  align="center">
  <div  class="form1  form" >
  <div align="center"></div>
    <h1>INGRESAR NUEVO PROVEDOR</h1>
    <form class="contact_form" action="" METHOD="post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
      <ul>
        <li class="form_row">
          <?php
	if(!empty($_GET['redirec']))
	{
		echo "<input type='hidden' name='redirec' value='$_GET[redirec]'>";
	}
	?>
          <label for="pass">PROVEDOR</label>
          <input name="nomb_prov" type="text" required id="nombre"  placeholder="requerido"  class="main_input" />
        </li>
        <li class="form_row">
          <label for="rif">IDENTIFICACION</label>
          <select name="codi_tide">
            <?php
		$database->consulta("SELECT * FROM t_ident");
		while($campo=$database->result())
		{
			echo " <option value='$campo[codi_tide]' > $campo[codi_tide] </option>";
		}
		?>
          </select><br>
          <input type="TEXT" name="idet_prov" placeholder="IDENTIFICACION" required  class="main_input" id="rif"/>
        </li>
        <li class="form_row">
          <label for="pass">EMAIL</label>
          <input type="email" name="emai_prov" placeholder="@EMAIL"  class="main_input"  />
        </li>
        <li class="form_row">
          <?php 
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
	 echo  $html->form_direccion('id_parr','dire_prov');
	  ?>
        </li>
        <li class="form_row">
          <div class="buscar" id="buscar_enc"></div>
          <div class="new2" id="new_enc"></div>
          <label ></label>
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
          <input type="text" name="ape2_cont"  placeholder="segundo apellido"  class="main_input"/>
        </li>
        <li class="form_row contacto_new">
          <label >EMAIL</label>
          <input type="email" name="emai_cont"  placeholder="@email"  class="main_input"  />
        </li>
        
        <li class="form_row contacto_new">
          <?php
	  echo $html->form_telef('contacto_');
	  ?>
        </li>
        
        <li class="form_row" id="contacto_bus"> </li>
        <li class="form_row">
          <button class="submit" form="frmDatos" type="reset" name="reset" value="VENDEDOR" id="b_reset">RESET</button>
          <button class="submit" type="submit" name="boton">ENVIAR</button>
        </li>
      </ul>
    </form>
  </div>
</div>
<script>
$(document).ready(function(e) {
    
	$('button[name=boton]').click(function(e) {
     
			if(($('input[name=nom1_cont]').val()!='' && $('input[name=ape1_cont]').val()=='') || ($('input[name=nom1_cont]').val()=='' && $('input[name=ape1_cont]').val()!=''))
			{
				e.preventDefault();
				error('ERROR FORMULARIO DE CONTACTO INCOMPLETO','LOS DATOS MINIMOS DE UN CONTACTO SON EN PRIMER NOMBRE Y EL PRIMER APELLIDO');
				return 1;
			}
    });
	<?php
	if(!empty($_GET['redirec']) && !empty($_SERVER['HTTP_REFERER']))
	{
		$HREF=  "$_GET[redirec]";
		
	}else
	{
		$HREF= 'buscar_provedores.php';
	}
	?>
	$('input[name=idet_prov]').focusout(function(e) {
		var codi_tide=$('select[name=codi_tide]').val();
		var idet_prov=$(this).val();
		if(idet_prov.length>0)
      $().load_json('provedor_ajax.php',{VERIFICA_PROV:true,'idet_prov':''+idet_prov,'codi_tide':codi_tide},
	  function(json){
		  if(json.idet_exist)
		  {
			  error(' ',' YA EXISTE UN PROVEEDOR REGISTRADO CON ESTA IDENTIFICACION <a href="<?php echo $HREF ?>?codi_tide='+json.codi_tide+'&idet_prov='+json.idet_prov+'&text='+json.codi_tide+json.idet_prov+'"><h2>'+json.codi_tide+json.idet_prov+'<br>'+json.nomb_prov+'</h2></a>');
			
		  }
		  });
    });
});
</script> 
