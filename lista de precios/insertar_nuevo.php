<?php
require_once("../clases/config_orveinca.php");
$database= new PRODUCTOS();
if(!empty($_POST))
{
	if($id_pord=$database->insertar_prod($_POST,$_FILES))
	Server::Redirec('l_precios.php',['codi_clpr'=>$_POST['codi_clpr'],'opcion'=>'id_prod','text'=>$id_pord,'editado'=>1]);
		
}
$database->autocommit(TRUE);
$database->consulta("SELECT * FROM marcas");
$MARK=$database->result_array();
$clasificacion=$database->clasificacion();

$html= new HTML();

$html->set_title("INSERTAR NUEVO");


//if($_COOKIE['srgdrgb']!=''){ 
?>
<script type='text/javascript'>

$(document).ready(function() 
{
	//ever
	$("#marca_m").change(function(e)
	{
		var opcion=$("#marca_m").attr('value');
		switch(opcion)
		{
			case 'otra_mark':
			{
				$('#otro_mark').html('<input id="otro_mark" name="new_marca"  placeholder="otro"  type="text" size="10">');
				$('#modelo').html('<input name="new_modelo" placeholder="otro" type="text">'); 
				
			}break;
			
			case 'NULL' :
			$('#modelo').html('<input  name="modelo"  placeholder="otro"  type="hidden" value="NULL">');
			$('#otro_mark').html('');
			break;
			
			default:
			{
				$('#modelo').load_html('ajax_precios.php',{'marca':opcion});
				$('#otro_mark').html('');
			}
			
		}
	});
	$('#modelo').change(function(e)
	{
		
		var opcion=$('#modelo_selet').attr('value');
		if(opcion=='otro_modelo')
		{
			$('#modelo').html('<input name="new_modelo" placeholder="otro" type="text">');
			
		}
	});
ind_tc=1;
	
	$('button[name=boton]').click(function(e) {
        
		var clas_prod=$('select[name=clas_prod]').attr('value');
		if(clas_prod=='NULL' )
		{
			e.preventDefault();
			error('!ATENCION!','SELECCIONE LA CLASIFICACION A LA QUE PERTENECE EL PRODUCTO');
		}
		
    });
	
	

});

</script>
<style type="text/css">
.middle_banner {
	height: 10px;
}

.form, .contact_form {
	width: 600px;
	float: none;
	height: auto;
	display: block;
	margin-left: 60px;
}

.formulario_new {
	margin: 3px;
	display: compact;
	padding: 5px;
}


ul li { list-style: circle; }

table tr th { text-align: center; }

.div_costo
{
	width: 270px;
	float: right;
	display: block;
}

.div_tamano
{
	width: 352px;
	display: block;
	float: left;
}

.tamano_costo
{
	-webkit-transition: all;
	-o-transition: all;
	transition: all;
	min-height: 59px;
	margin-top: -4px;
}

.medidas
{
	width: 789px;
	padding-top: 12px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
}

#tamano_costo:hover
{
	background: #C5EEFE;
	border-color: #FF0000;
}
</style>

<div align="center" class="conten_ico" > <a href="l_precios.php">
  <div class="atras"></div>
  </a> </div>
<div align="center" >
  <h1>INSERTA NUEVO PRODUCTO</h1>
  <div class="catalogo" >
    <form  action="" METHOD="post"  enctype="multipart/form-data"  name="frmDatos" target="_self" >
      <div class="formulario_new">
        <select name="clas_prod" required id="clpr">
          <option value="NULL">SELECCIONE CLASIFICACION</option>
          <?php
	foreach($clasificacion as $ind=>$campo)
	{
		echo"<option  value='".$campo['codi_clpr']."' >".$campo['codi_clpr']." ".$campo['desc_clpr']."</option>";
	}
				  ?>
        </select>
        <table width="900" border="0" cellspacing="0" cellpadding="1">
          <tr>
            <th scope="col"  colspan="2">DESCRIPCION</th>
          </tr>
          <tr>
            <th scope="col" colspan="2"><input class="input_text " id="desc"   name="desc_prod" placeholder="REQUERIDO" required type="text" ></th>
          </tr>
          <tr>
            <th scope="col">MARCA</th>
            <th scope="col">MODELO</th>
          <tr>
            <th scope="col"> <select name="marca" id="marca_m">
                <option value="NULL">SELECCIONES LA MARCA</option>
                <?php
				foreach($MARK as $idema=>$campo)
				{
     				echo"<option value=\"$campo[id_marc]\">$campo[desc_marc]</option>";
				}
				?>
                <option value="NULL" >NINGUNA</option>
                <option value="otra_mark">OTRA</option>
              </select>
              <div  id="otro_mark"></div>
            </th>
            <th scope="col" id="modelo"></th>
          </tr>
          <tr>
            <th scope="col">TAMANO</th>
            <th scope="col">COSTO
              <div class="new" id="new_t_c"></div>
            </th>
          </tr>
          <tr>
            <th scope="col"  colspan="2">
             <select name="id_u_medida" id="u_medida">
                <option   >MEDIDA</option>
                <?php
			$database->consulta("SELECT * FROM u_medida");
			while($campo=$database->result())
			{
				echo "<option value='$campo[codi_umed]'  >$campo[desc_umed]</option>";
			}
			
			?>
              </select>
              <div align="center" >
                <div class="medidas" id="meiddas">
                  <div  id="tamano_costo0" class="tamano_costo row_act">
                    <div class="elimina del_tp" id="del_tp0" onClick="del_tp(0)"></DIV>
                    <div  align="center"  class="div_costo" id="div_costo0"> <bR>
                      <input name="costo[0]"  class="input_text" placeholder="costo" required type="text">
                    </div>
                    <div align="center" class="div_tamano"  id="div_tamano">
                      <div id="tamano0" > </div>
                    </div>
                  </div>
                  <div class="tamano_costo" id="tamano_costo1"></div>
                </div>
              </div>
            </th>
          </tr>
          <tr>
            <th scope="col" colspan="3">GARGAR IMAGEN</th>
          </tr>
          <tr>
            <th scope="col" colspan="3"><img id="foto" src="../mysql/img.php" width="100" height="100"></th>
          </tr>
          <tr>
            <th scope="col" colspan="3" ><input type="checkbox"  name="is_imagen" value="true" id="select_img">
              <input name="foto" placeholder="imagen" class="input_text" value=""  type="file"></th>
          </tr>
        </table>
      </div>
      <button class="submit" type="submit" name="boton">Enviar</button>
    </form>
  </div>
</div>

