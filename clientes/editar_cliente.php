<?php
require_once("../clases/config_orveinca.php");
$database= new CLIENTES();
$edit='';
if(!empty($_GET['edit']))
$edit=$_GET['edit'];
$error='';
$MY_ERROR=NULL;
if(!$database->error())
{
if($_POST)
if(!empty($_POST['boton']) && $_POST['boton']=='cliente')
{	
	if($database->editar_cliente($_POST))
	{
		redirec("buscar_info_cliente.php?opcion=idet_clie&text=$_POST[idet_clie]");
	}
}
}
$database->autocommit(TRUE);

$html= new HTML();
$html->set_title("EDITAR");

	
if($edit=='basico')
{
	$dir="buscar_info_cliente.php";
}else
{
	$dir=$_SERVER['HTTP_REFERER'];
}

	 ?>
<style type="text/css">
.telef
{
	width: 271px;
	height: 23px;
	font-size: 14px;
	padding-top: 2px;
	padding-right: 2px;
	padding-bottom: 2px;
	padding-left: 2px;
}
</style>
<div align="center" class="conten_ico" ><a href="<?php echo $dir;?>">
  <div class="atras" id="atras"></div>
  </a> </div>
<div  align="center">
  <div class="form1 form" align='center'>
    <?php
 if($edit=='basico')
{
			$database->consulta(CLIENTES::CLIE,"idet_clie='$_GET[idet_clie]'");
			$cliente =$database->result()

			?>
    <h1 align="center">EDITAR REGISTRO DE CLIENTES</h1>
    <form class="contact_form" action="" METHOD="post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
      <ul>
        <li class="form_row">
          <label for="vendedor">VENDEDOR</label>
          <select name="ci_empl" form="frmDatos">
            <option value='NULL'>VENDEDOR</option>
            <?php 
		$database->consulta("SELECT * FROM empleados where codi_carg='vend';");
		while($campo =$database->result())
		{
			$select='';
			if($cliente['ci_empl']==$campo['ci_empl'])
			$select="selected";
			echo " <option value='$campo[ci_empl]' $select>$campo[nom1_empl] $campo[ape1_empl] </option>";
		} 
		?>
          </select>
        </li>
        <li class="form_row">
          <label for="name">RASON SOCIAL</label>
          <input name="nomb_clie" form="frmDatos" type="text" value="<?PHP echo $cliente['nomb_clie'] ?>" id="nombre"  placeholder="mailto"  class="main_input" />
        </li>
        <li class="form_row">
          <label for="email">EMAIL</label>
          <input type="email" form="frmDatos" value="<?PHP echo $cliente['emai_clie'] ?>" name="emai_clie" placeholder="@email "   class="main_input"  />
        </li>
        <li class="form_row" >
          <?php
		$database->consulta("SELECT * FROM telefonos WHERE id_tper='clie' and  idet_pers='$cliente[idet_clie]'");
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
            <input name='telefono[$i]' value='' type='hidden'>
			
            ";
	
        }
		$auxi=$i;
		echo $html->form_telef('',$tel_bd,$auxi);
		?>
        </li>
        <li class="form_row">
          <label for="direccion">DIRECCION</label>
          <select name="id_estado" title="id_muni">
            <option value="">ESTADO</option>
            <?php
		$database->consulta("SELECT * FROM estados");
		while($campo_=$database->result())
		{
			$select='';
			if($campo_['id_esta']==$cliente['id_esta'])
			{
				$select='selected';
			}
			echo "<option value='".$campo_['id_esta']."' $select >$campo_[desc_esta]</option>";
		}
		
		?>
          </select>
          <select name="id_muni" title="id_parr" >
            <option value="">--------</option>
            <?php
		$database->consulta("SELECT * FROM municipios WHERE id_esta='$cliente[id_esta]'");
		while($campo_=$database->result())
		{
			$select='';
			if($campo_['id_muni']==$cliente['id_muni'])
			{
				$select='selected';
			}
			echo "<option value='".$campo_['id_muni']."' $select >$campo_[desc_muni]</option>";
		}
		
		?>
          </select>
          <select name="id_parr" id="parroquia">
            <option value="">--------</option>
            <?php
		$database->consulta("SELECT * FROM parroquias WHERE id_muni='$cliente[id_muni]'");
		while($campo_=$database->result())
		{
			$select='';
			if($campo_['id_parr']==$cliente['id_parroquia'])
			{
				$select='selected';
			}
			echo "<option value='".$campo_['id_parr']."' $select >$campo_[desc_parr]</option>";
		}
		
		?>
          </select>
          <BR>
          <input type="text" name="dire_clie" value="<?PHP echo $cliente['dire_clie'] ?>"  placeholder="CALLE NRO DE LOCAL O CASA"  class="main_input"/>
          <BR>
        </li>
        <li class="form_row">
          <h2>EDITAR CONTACTO</h2>
          <h3>
            <?php
			$database->consulta("SELECT * FROM contactos");
		 echo "<select name='ci_cont' id='search_cont' >
		 <option value='null'>ENCARGADO</option>";
		while($campo=$database->result())
		{
			$select='';
			if($campo['ci_cont']==$cliente['ci_cont'])
			{
				$select='selected';
			}
			echo "<option value='$campo[ci_cont]' $select>$campo[nom1_cont] $campo[ape2_cont]</option>";
		}
		echo "</select>
	
		";
		echo "<a href='../contactos/new_contacto.php?tab=clientes&idet=".$cliente['idet_clie']."&tident=idet_clie'><div class='new' id='new_cont'></div></a>";
		if(!empty($cliente['ci_cont']))
		echo "<a href='../contactos/editar_contacto.php?idet=".$cliente['idet_clie']."&tident=idet_clie&ci_cont=".$cliente['ci_cont']."' >
		 <div class='edit' id='edit_cont'></div></a><br>";
	
		
		 ?>
          </h3>
        <li  class="form_row" >
          <button class="submit" form="frmDatos" type="submit" name="boton" value="cliente">ENVIAR</button>
        </li>
      </ul>
      <input name="idet_clie" value="<?php echo $cliente['idet_clie'] ?>" type="hidden" >
    </form>
    <?php }?>
  </div>
</div>
<script type="text/javascript">


$(document).ready(function() {
   $(".new2").css('display','none');
	
});

</script>
<?php

//}else echo"<h1>SESSION EXPIRADA PORFAVOR REINICIE SECCION </h1>"; 

?>
