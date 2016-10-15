<?php
require_once("../clases/config_orveinca.php");
$database= new EMPLEADOS();
if($_POST)
{
	if($database->editar_empleado($_POST))
	{
		redirec("buscar_empleados.php?opcion=ci_empl&text=$_POST[ci_empl]");
	}
}
if(empty($_GET['ci_empl']))
{
	redirec("buscar_empleados.php");
}


$html= new HTML();
$html->set_title("EDITAR");
	
?>
  <div align="center" class="conten_ico" ><a href="buscar_empleados.php">
  <div class="atras" id="atras"></div></a>
 
</div>
<div class="form1" align="center">
  <div align='center'>
  
    <?php
		$database->consulta(EMPLEADOS::EMPL," ci_empl='$_GET[ci_empl]'");
		$empleado=$database->result();
			?>
    <h1 align="center">EDITAR EMPLEADO</h1>
    <form class="contact_form form" action="" METHOD="Post" enctype="multipart/form-data" name="frmDatos" target="_self" id="frmDatos">
      <input type="hidden" name="ci_empl" value="<?php echo $empleado['ci_empl'] ?>">
      <ul>
        <li class="form_row">
          <label>NOMBRE</label>
          <input type="text" name="nom1_empl" class="main_input"  placeholder="nombre " value="<?php echo $empleado['nom1_empl'] ?>">
        </li>
        <li  class="form_row">
          <label>SEGUNDO NOMBRE</label>
          <input type="text" name="nom2_empl"  class="main_input" placeholder="segundo nombre" value="<?php echo $empleado['nom2_empl'] ?>">
        </li>
        <li class="form_row">
          <label>APELLIDO</label>
          <input type="text" name="ape1_empl" class="main_input" placeholder="apellido" value="<?php echo $empleado['ape1_empl'] ?>">
        </li>
        <li class="form_row">
          <label>SEGUNDO APELLIDO</label>
          <input type="text" name="ape2_empl" class="main_input" placeholder="segundo apellido" value="<?php echo $empleado['ape2_empl'] ?>">
        </li>
        <li class="form_row">
          <label>EMAIL</label>
          <input type="email" name="emai_empl"  class="main_input" placeholder="correo electronico" value="<?php echo $empleado['emai_empl'] ?>">
        </li>
        
        <li class="form_row" >
         <?php
		$database->consulta("SELECT * FROM telefonos WHERE id_tper='empl' and idet_pers='$empleado[ci_empl]'");
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
		 
		  $database->cuent_banc("idet_pers='".$empleado['ci_empl']."' and id_tper='empl'");
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
			if($campo_['id_esta']==$empleado['id_esta'])
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
		$database->consulta("SELECT * FROM municipios WHERE id_esta='$empleado[id_esta]'");
		while($campo_=$database->result())
		{
			$select='';
			if($campo_['id_muni']==$empleado['id_muni'])
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
		$database->consulta("SELECT * FROM parroquias WHERE id_muni='$empleado[id_muni]'");
		while($campo_=$database->result())
		{
			$select='';
			if($campo_['id_parr']==$empleado['id_parr'])
			{
				$select='selected';
			}
			echo "<option value='".$campo_['id_parr']."' $select >$campo_[desc_parr]</option>";
		}
		
		?>
          </select>
          <BR>
          <input type="text" name="dire_empl" value="<?PHP echo $empleado['dire_empl'] ?>"  placeholder="CALLE NRO DE LOCAL O CASA"  class="main_input"/>
          <BR>
        </li>
         <li class="form_row">
        <label>CARGO</label>
        <select name="codi_carg">
        <option value=" ">SELECCIONE CARGO</option>
        <?php
		$database->consulta("SELECT * FROM cargos");
		while($campo=$database->result())
		{
			$select='';
			if($campo['codi_carg']==$empleado['codi_carg'])
			{
				$select='selected';
			}
			echo " <option value='".$campo['codi_carg']."' $select>".$campo['desc_carg']."</option>";
		}
		?>
       
        </select>
      </li>
       <li class="form_row">
        <label>DEPARTAMENTO</label>
      <select name="codi_dept">
       <option value=" ">SELECCIONE DEPARTAMENTO</option>
        <?php
		$database->consulta("SELECT * FROM departamen");
		while($campo=$database->result())
		{
			
			$select='';
			if($campo['codi_dept']==$empleado['codi_dept'])
			{
				$select='selected';
			}
			echo " <option value='".$campo['codi_dept']."' $select>".$campo['desc_dept']."</option>";
		}
		?>
      </select>
      </li>
        <li class="form_row">
             <button class="submit" form="frmDatos" type="reset" name="reset" value="VENDEDOR" id="b_reset">RESET</button>
          <button class="submit" form="frmDatos" type="submit" name="enviar" value="VENDEDOR">ENVIAR</button>
        </li>
      </ul>
    </form>
  </div>
</div>


