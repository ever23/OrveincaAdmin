<?php
$error='';
require_once("../clases/config_orveinca.php");
$database= new PRODUCTOS();
if(!empty($_POST['boton']))
{
	if($id_pord=$database->editar_prod($_POST,$_FILES))
		redirec("l_precios.php?codi_clpr=$_POST[codi_clpr]&opcion=id_prod&text=$id_pord&editado=1"); 
}

if(empty($_GET['id_prod']))
{
	redirec("l_precios.php"); 
}
$database->autocommit(TRUE);

$html= new HTML();
$html->prettyPhoto();

?>
<script type='text/javascript'>
	$(document).ready(function() 
					  {
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
		$('#elim_img').click(function(e) {
			if(!confirm('ESTA SEGURO QUE DESA ELIMINAR LA IMAGEN DE ESTE PRODUCTO?'))
			{
				return 0;
			}
			$().load_json('ajax_precios.php',{elimina_img:true,id_prod:$(this).attr('title')},function(js)
						  {
				if(!js.error)
				{
					$('#foto').attr('src',js.src);

				}else
				{
					error("ERROR ",js.error);
					$('#foto').attr('src',js.src);

				}



			});
		});
		$('#modelo').change(function(e)
							{
			var ind=e.target.selectedIndex;
			var opcion=e.target.options[ind].value;
			if(opcion=='otro_modelo')
			{
				$('#modelo').html('<input name="new_modelo" placeholder="otro" type="text">');
			}

		});
		ind_tc=1;
	});
</script>
<style type="text/css">
	ul li { list-style: circle; }

	table tr th { text-align: center; }
	#l_precios tr th
	{
		text-align:justify;
	}
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
	.edit
	{
		display:none;	
	}
</style>
<div align="center" class="conten_ico" ><a href="l_precios.php">
	<div class="atras" id="atras"></div></a>
</div>
<div align="center">
	<h1> EDITAR REGISTRO</h1>

	<?php
echo $error.$database->error();

$database->consulta(PRODUCTOS::PROD,"id_prod='$_GET[id_prod]'");
$productos=$database->result();
echo "<script>
  $(document).ready(function(e) {

	$('#l_precios').load_html('ajax_precios.php',{'opcion':'id_prod','texto' :'$_GET[id_prod]','like'  :'=','l_precios':true});
});
  </script>";
	?>

	<div class="catalogo" >
		<table width="800" border="0" cellspacing="1" cellpadding="1"  id="l_precios" style="text-align:justify;">
		</table><br><br>
		<form  action="" METHOD="post"  enctype="multipart/form-data" name="frmDatos" target="_self" >
			<input type="hidden" name="id_prod" value="<?PHP  echo $productos['id_prod'] ?>">
			<input type="hidden" name="tabla" value="<?PHP  echo $productos['codi_clpr'] ?>">
			<div class="formulario_new">
				<table width="900" border="0" cellspacing="0" cellpadding="1">
					<tr>
						<th scope="col" colspan="2">DESCRIPCION</th>

					</tr>
					<tr>
						<th scope="col"  colspan="2"><input class="input_text "   name="desc_prod" value="<?PHP echo $productos['desc_prod'] ?>" required type="text" ></th>

					</tr>
					<tr>
						<th scope="col">MARCA</th>
						<th scope="col">MODELO</th>
					<tr>
						<th scope="col"> <select name="marca" id="marca_m">
							<option value="NULL" >NINGUNA</option>
							<?PHP
		$database->consulta("SELECT * FROM marcas");
$MARK=$database->result_array();
foreach($MARK as $idema=>$campo)
{
	if($productos['id_marc']==$campo['id_marc'])
		$select="selected";
	else
		$select="";

	echo"<option value=\"$campo[id_marc]\" $select >$campo[desc_marc]</option>";
}
							?>
							<option value="otra_mark" >OTRA</option>
							</select>
							<div  id="otro_mark"></div>
						</th>
						<th scope="col" id="modelo"> 
							<?PHP
$database->consulta("select * from modelos where id_marc='$productos[id_marc]';");

echo '
	<select name="modelo" id="modelo_selet" >';
while($campo=$database->result())
{
	if($productos['id_mode']==$campo['id_mode'])
		$select="selected";
	else
		$select="";
	echo "<option value='$campo[id_mode]' $select>$campo[desc_mode]</option>";
}
echo "
  <option value='otro_modelo' >OTRA</option>
	</select>";

							?>

						</th>
					</tr>
					<tr>
						<th scope="col">TAMANO</th>
						<th scope="col">COSTO
							<div class="new" id="new_t_c"></div>
						</th>
					</tr>
					<tr>
						<th scope="col"  colspan="2" >
							<?php
$result_med=$database->consulta(PRODUCTOS::PROD_TC_MIN," id_prod='$_GET[id_prod]'");
$medidas=$result_med->fetch_array();
//echo $database->sql;
							?>

							<select name="id_u_medida" id="u_medida">
								<option   >MEDIDA</option>
								<?PHP




$database->consulta("SELECT * FROM u_medida ");
while($campo=$database->result())
{
	if($medidas['codi_umed']==$campo['codi_umed'])
		$select="selected";
	else
		$select="";
	echo "<option value='$campo[codi_umed]'   $select>$campo[desc_umed] </option>";
}
								?>
							</select>

							<div align="center" >
								<div class="medidas" id="meiddas">
									<?PHP
$result_med->data_seek(0);

for($ind=0;$campo = $result_med->fetch_array();$ind++)
{
	if($ind%2==0) 
		$row_act='row_act';
	else
		$row_act='';

	echo '<script>
			  $(document).ready(function()
			  {
				 $("#del_tp'.$ind.'").click(function(e) 
				 {
					 var valor=confirm("ESTA SEGURO DE ELIMINAR RANGO DE TALLA O MEDIDA");
					if(valor)
					{
						$("#tamano_costo'.$ind.'").load_html("ajax_precios.php",{"elimina_cp":'.$campo['id_tmpd'].'},
						function(html){
						if(html=="")
						{
							$("#tamano_costo'.$ind.'").fadeOut();
						}else
						{
							error("ERROR",html);
						}
						});
					}
				 });
   			 }); 
			  </script>
 			 <input type="hidden" name="id_tam_pro['.$ind.']" value="'.$campo['id_tmpd'].'">';
	echo '
              <div  id="tamano_costo'.$ind.'" class="tamano_costo '.$row_act.'">
			  <div class="elimina" id="del_tp'.$ind.'" " ></div> 
                <div  align="center"  class="div_costo" id="div_costo'.$ind.'"> <bR>
                  <input name="costo['.$ind.']"  class="input_text" value="'.$campo['cost_tama'].'" required type="text">
                </div>
                <div align="center" class="div_tamano"  id="div_tamano">
                  <div id="tamano'.$ind.'" > ';

	if($medidas['codi_umed']=='t')
	{
		$database->consulta("SELECT * FROM tamanos where codi_umed='t' ORDER BY id_tama");
		echo 	'<div id="div_tamano_ini'.$ind.'">desde<select name="tamano_ini['.$ind.']" id="tamano_ini'.$ind.'" >';
		while($cmedida=$database->result())
		{
			if($campo['id_tama1']==$cmedida['id_tama'])
				$select="selected";
			else
				$select="";
			echo  "<option value='$cmedida[id_tama]' $select>$cmedida[medi_tama]</option>";
		}
		echo "</select></div>";
		echo '<div id="div_tamano_end'.$ind.'">hasta
						<select name="tamano_end['.$ind.']" id="tamano_end'.$ind.'" >';
		$database->result->data_seek(0);
		while($cmedida=$database->result())
		{
			if($campo['id_tama2']==$cmedida['id_tama'])
				$select="selected";
			else
				$select="";
			echo  "<option value='$cmedida[id_tama]' $select>$cmedida[medi_tama]</option>";
		}	
		echo "</select></div>";


	}elseif($medidas['codi_umed']=='-')
	{

		echo '<div id="div_tamano_ini'.$ind.'"><input name="tamano_ini['.$ind.']" type="hidden" value="'.$campo['id_tama1'].'"></div><div id="div_tamano_end'.$ind.'"> <H4>NO POSEE</H4><input name="tamano_end['.$ind.']" type="hidden" value="'.$campo['id_tama2'].'"></div>';
		echo '
				  </div>
                </div>
              </div>
			  '; 
		break;

	}elseif($medidas['codi_umed']=='cm2')
	{
		echo '<div id="div_tamano_ini'.$ind.'">

				<input type="text" name="otro_tam_ini['.$ind.']" id="numero" value="'.$campo['medi_tama1'].'" placeholder="medida1" >
				</div>
				<div id="div_tamano_end'.$ind.'"> 
				<input type="text" name="otro_tam_end['.$ind.']" id="numero" value="'.$campo['medi_tama2'].'" placeholder="medida2" >

				</div>';

	}else
	{
		echo '<div id="div_tamano_ini'.$ind.'">

				<input type="number" name="otro_tam_ini['.$ind.']" id="numero" value="'.$campo['medi_tama1'].'" placeholder="medida1" >
				</div>
				<div id="div_tamano_end'.$ind.'"> 
				<input type="number" name="otro_tam_end['.$ind.']" id="numero" value="'.$campo['medi_tama2'].'" placeholder="medida2" >

				</div>';
	}
	echo '
				  </div>
                </div>
              </div>
			  '; 
}
$u_medida='';
if(!empty($medidas['codi_umed']))
	$u_medida=$medidas['codi_umed'];
echo  '<div class="tamano_costo" id="tamano_costo'.$result_med->num_rows.'"></div>
			 ';
									?>
								</div>
							</div>
							<?php echo '<script>
			  unidad_medida="'.$u_medida.'";
	$(document).ready(function()
	{



	ind_tc='.$result_med->num_rows.';
});
</script>  ';?>
						</th>
					</tr>
					<tr>
						<th scope="col" colspan="3" align="center">GARGAR IMAGEN</th>
					<tr>
					<tr>
						<th scope="col" colspan="2" align="center"><img src="<?php echo "../mysql/img.php?id=$productos[id_imag_p]";?>" id="foto" width="100" height="100">
						</th>
					<tr>
					<tr>
						<th scope="col" colspan="3"><input type="checkbox" name="is_imagen" value="true">
							<input name="foto" placeholder="imagen"    class="input_text" id="load_img"  type="file"><br><div style="float:left; height:20px; width:300px;"></div><div class="elimina" id="elim_img"  align="center" style="float:left;" title="<?PHP echo $_GET['id_prod'] ?>"></div><br></th>
					</tr>
				</table>
			</div>
			<button class="submit" type="submit" name="boton" value="mod">Enviar</button>
		</form>
	</div>
</div>

