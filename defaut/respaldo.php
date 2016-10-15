<?php
require("../clases/config_orveinca.php");
if(!empty($_GET['dowload']))
{
	$db= new BD_ORVEINCA();
	$exp= $db->ExportDBGzip();;
	if(!is_null($exp))
	{
		$time= new TIME();
		$name="orveinca_F_".$time->fecha().'_H_'.$time->hora();
		  header('Content-type: application/x-orv');
   		 header('Content-Disposition: attachment; filename="'.$name.'.orv"');
		 echo $exp;
		 exit;
	}
}
$Html = new HTML();

?>
<div align="center" >
<h1>RESPALDO DE LA BASE DE DATOS </h1>
<a href="respaldo.php?dowload=true">
<button class="submit" type="submit" name="respaldo" >RESPALDAR</button>
</a>
</div>
<div align="center">

</div>