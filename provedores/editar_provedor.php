<?php
require("../clases/config_orveinca.php");
$database= new PROVEDORES();
if($_POST)
{	

	if($database->editar_provedor($_POST))
	redirec("buscar_provedores.php?opcion=idet_prov&text=$_POST[idet_prov]");
	
}

$edit='';
if(!empty($_GET['edit']))
$edit=$_GET['edit'];
$error='';
if(empty($_GET['idet_prov']))
redirec("buscar_provedores.php");

$database->autocommit(TRUE);

$html= new HTML();
$html->set_title("EDITAR");




	
if($edit=='basico')
{
	$dir="buscar_provedores.php";
}else
{
	$dir=$_SERVER['HTTP_REFERER'];
}
//if($_COOKIE['srgdrgb']!=''){
	 ?>

<div align="center" class="conten_ico" ><a href="<?php echo $dir;?>">
  <div class="atras" id="atras"></div>
  </a> </div>
<div  align="center">
  <div class="form1 form" align='center'>
    <?php
 if($edit=='basico')
{
			
			$database->consulta(PROVEDORES::PROV,"idet_prov='$_GET[idet_prov]'");
			$provedor =$database->result()

			?>
    <h1 align="center">EDITAR REGISTRO DE PROVEDOR</h1>
    <form class="contact_form" action="" METHOD="post" enctype="multipart/form-data" name="frmDatos" id="frmDatos">
      <ul>
        <li class="form_row">
          <label for="name">RASON SOCIAL</label>
          <input name="nomb_prov" form="frmDatos" type="text" value="<?PHP echo $provedor['nomb_prov'] ?>" id="nombre"  placeholder="mailto"  class="main_input" />
        </li>
        <li class="form_row">
          <label for="email">EMAIL</label>
          <input type="email" form="frmDatos" value="<?PHP echo $provedor['emai_prov'] ?>" name="emai_prov" placeholder="@email "   class="main_input"  />
        </li>
        <li class="form_row" >
          <?php
		$database->consulta("SELECT * FROM telefonos WHERE id_tper='prov' and idet_pers='$provedor[idet_prov]'");
		$tel_bd='';
		 for($i=0;$campo=$database->result();$i++)
		{
			if($i%2==0)
		$row_act=' row_act';
		else
		$row_act='';
		
          $tel_bd.="  <div id='telefonos$i ' class=' div_telf'>
             
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
          <?php 
		 
		  $database->cuent_banc("idet_pers='".$provedor['idet_prov']."' and id_tper='prov'");
		$is_row_act=true;
		$bancbd='';
		$i=0;
		  for($i=0;$campo=$database->result();$i++)
		  {
			  
			if($i%2==0)
				$row_act=' row_act';
			else
			$row_act='';
             $bancbd.=' <div class="div_bancos" id="banco'.$i.'">
              <div class="banc '.$row_act.'">'.$campo['nomb_banc'].' '.$campo['tipo_cuet'].'<BR> NRO: '.$campo['#cuenta'].'
             <div class="elimina del_banc_bd"  onClick="del_banc_ajax(\''.$i.'\',\'\',\''.$campo['#cuenta'].'\')" ></div>
              </div>
              <input name="nro_cuenta['.$i.']" value="" type="hidden">
              </div> ';
              
		  }
		echo   $html->form_cuet_banc('',$bancbd,$i);
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
			if($campo_['id_esta']==$provedor['id_esta'])
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
		$database->consulta("SELECT * FROM municipios WHERE id_esta='$provedor[id_esta]'");
		while($campo_=$database->result())
		{
			$select='';
			if($campo_['id_muni']==$provedor['id_muni'])
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
		$database->consulta("SELECT * FROM parroquias WHERE id_muni='$provedor[id_muni]'");
		while($campo_=$database->result())
		{
			$select='';
			if($campo_['id_parr']==$provedor['id_parr_prov'])
			{
				$select='selected';
			}
			echo "<option value='".$campo_['id_parr']."' $select >$campo_[desc_parr]</option>";
		}
		
		?>
          </select>
          <BR>
          <input type="text" name="dire_prov" value="<?PHP echo $provedor['dire_prov'] ?>"  placeholder="CALLE NRO DE LOCAL O CASA"  class="main_input"/>
          <BR>
        </li>
        <li class="form_row">
          <h2>EDITAR CONTACTO</h2>
          <h3>
            <?php
			$database->consulta("SELECT * FROM contactos");
		 echo "<select name='ci_cont' id='search_cont' >
		 <option value=''>CONTACTO</option>";
		while($campo=$database->result())
		{
			$select='';
			if($campo['ci_cont']==$provedor['ci_cont'])
			{
				$select='selected';
			}
			echo "<option value='$campo[ci_cont]' $select $campo[ci_cont] $campo[nom1_cont] $campo[ape2_cont]</option>";
		}
		echo "</select>
	
		";
		echo "<a href='../contactos/new_contacto.php?tab=provedores&idet=".$provedor['idet_prov']."&tident=idet_prov'><div class='new' id='new_cont'></div></a>";
		if(!empty($provedor['ci_cont']) )
		 echo "<a href='../contactos/editar_contacto.php?tab=provedores&idet=".$provedor['idet_prov']."&tident=idet_prov&ci_cont=".$provedor['ci_cont']."' >
		 <div class='edit' id='edit_cont'></div></a><br>";
		
		 ?>
          </h3>
        <li  class="form_row" >
          <input name="idet_prov" value="<?php echo $provedor['idet_prov'] ?>" type="hidden" >
          <button class="submit" form="frmDatos" type="submit"  >ENVIAR</button>
        </li>
      </ul>
    </form>
    <?php }
	

		  ?>
  </div>
</div>
