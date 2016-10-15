<?php
require("../clases/config_orveinca.php");
$html= new HTML();
$database= new BD_ORVEINCA();
if(!empty($_POST['imagen_env']))
{
	$database->editar_img_clpr($_POST['codi_clpr'],$_FILES);
}


$campo=$database->config();
$html->AddCssScript('.producto
{
	float:left;
	width:300px;
	height:90;
}');


?>
<script>
$(document).ready(function(e) {
    $('.edit').tics("CAMBIAR  IMAGEN");
});
</script>

<div align="center">
  <h1> CLASIFICACION DE LOS PRODUCTOS </h1>
  <?php 
if(empty($_GET['codi_clpr']))
{
$database->consulta("SELECT * FROM clas_prod");
while($campo=$database->result())
{
	echo "
	<div class='producto'>
	<h3><a href='$_SERVER[PHP_SELF]?codi_clpr=$campo[codi_clpr]'><div class='edit'></div></a>$campo[desc_clpr]</h3>
	<img src='../mysql/redimen.php?id=$campo[id_imag]&w=90&h=120' width='90'  height='120'>
	</div>";
}
}
if(!empty($_GET['codi_clpr']))
{
	$database->consulta("SELECT * FROM clas_prod where codi_clpr='".$_GET['codi_clpr']."'");
	$campo=$database->result();
	echo "<h2>$campo[desc_clpr]</h2>";
	?>
  <form action="clasificacion.php" name="frmDatos" method="post"  ENCTYPE="multipart/form-data">
    <input name="foto" type="file" >
    <input type="hidden" name="codi_clpr" value="<?php  echo $_GET['codi_clpr'] ?>">
   
    <button class="submit" value="g" name="imagen_env">CARGAR IMAGEN</button>
  </form>
  <?php 
	echo "<div align='center'>
	<img src='../mysql/redimen.php?id=$campo[id_imag]&w=250&h=400' id='foto' width='250' height='400' >
	</div>";
	
	
}


?>
</div>
