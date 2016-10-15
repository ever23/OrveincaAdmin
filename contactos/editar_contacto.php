<?php
require_once("../clases/config_orveinca.php");

$html= new HTML();
$database= new CONTACTOS();
if($_POST)
{
	if($database->editar_contacto($_POST))
	{
		redirec($_POST['redir']."&".$_POST['tident']."=".$_POST['idet']);
	}
}

$html->set_title("EDITAR");


if(empty($_GET['ci_cont']))
$html->__destruct();
?>

<style type="text/css">
.telef {
	width: 260px;
	height: 23px;
	font-size: 14px;
	padding-top: 2px;
	padding-right: 2px;
	padding-bottom: 2px;
	padding-left: 2px;
}

.banc {
	width: 261px;
	height: 37px;
	font-size: 14px;
	padding-top: 2px;
	padding-right: 2px;
	padding-bottom: 2px;
	padding-left: 2px;
}

.div_bancos ,.div_telf{
	width: 226px;
	min-height: 30px;
}
</style>
<div align="center" class="conten_ico" ><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">
  <div class="atras" id="atras"></div></a>
 
</div>
<div class="form1" align="center">
  <div align='center'>
 <h1 align="center">EDITAR REGISTRO DE CONTACTO</h1>
    <form class="contact_form form" action="" METHOD="post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
      <?php
	  $ci_cont=$_GET['ci_cont'];
		
		$database->consulta(CONTACTOS::CONTAC,"ci_cont='$ci_cont'");
		$contacto=$database->result();
		?>
  	 
	 <input type="hidden" name="ci_cont"value="<?php echo (int)$ci_cont ?>" class="main_input"/>
	  
      <ul>
        <li class="form_row">
        <label for="nombre">NOMBRE</label>
        <input type="text" name="nom1_cont" placeholder="nombre" value="<?php echo $contacto['nom1_cont']?>" class="main_input"/>
           </li>
        <li class="form_row">
        <label for="nombre">SEGUNDO NOMBRE</label>
        <input type="text" name="nom2_cont" value="<?php echo $contacto['nom2_cont']?>" placeholder="segundo nombre"  class="main_input"/>
         </li>
        <li class="form_row">
        <label for="nombre">APELLIDO</label>
        <input type="text" name="ape1_cont" placeholder="apellido"  value="<?php echo $contacto['ape1_cont'] ?>" class="main_input"/>
         </li>
        <li class="form_row">
        <label for="nombre">SEGUNDO APELLIDO</label>
        <input type="text" name="ape2_cont" placeholder="segundo apellido" value="<?php echo  $contacto['ape2_cont'] ?>"  class="main_input"/><br>
          </li>
        <li class="form_row">
        <label for="nombre">EMAIL</label>
        <input type="email" name="emai_cont" placeholder="@email" value="<?php echo  $contacto['emai_cont']?>"  class="main_input"  />
         </li>
          
		 <li class="form_row">
         <?php
		$database->consulta("SELECT * FROM telefonos WHERE id_tper='cont' and  idet_pers='$ci_cont'");
		$tel_bd='';
		 for($i=0;$campo=$database->result();$i++)
		{
			if($i%2==0)
		$row_act=' row_act';
		else
		$row_act='';
		
          $tel_bd.="  <div id='telefonos$i' class=' div_telf'>
             
              <div class='telef $row_act'> TEL: ".$campo['#telf']."  <div class='elimina del_tel_bd' 
			   onClick='del_tel_ajax(\"$i\",\"\",\"".$campo['#telf']." \")' ></div></div>
            </div>
            <input name='contacto_telefono[$i]' value='' type='hidden'>
			
            ";
	
        }
		$auxi=$i;
		echo $html->form_telef('contacto_',$tel_bd,$auxi);
		?>
  
       
     </li>
       
      <li class="form_row">
       <input type="hidden" name="redir" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
         <input type="hidden" name="tab" value="<?php echo $_GET['tab'] ?>">
  <input type="hidden" name="idet" value="<?php echo $_GET['idet'] ?>">
   <input type="hidden" name="tident" value="<?php echo $_GET['tident'] ?>">
        <button class="submit" form="frmDatos" type="submit" name="boton" value="encargado">ENVIAR</button>
      </li>
      </ul>
    </form>
    </div>
    </div>

