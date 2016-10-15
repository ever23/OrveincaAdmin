<?php
include("../clases/config_orveinca.php");

if(!empty($_GET['id']))
{
	
	$database= new BD_ORVEINCA();
	if($database->consulta("SELECT * FROM imagenes where id_imag = '$_GET[id]';",NULL))
	{
		if($database->result->num_rows>0)
		{
			
			$img=$database->result();
			$IMG=new IMG($_GET['w'],$_GET['h'],'image/png');
			$IMG->importar_img('imagen',__AUTORIZATE_DIRNAME__."mysql/img.php?id=".$_GET['id'], substr($img['ext'],1,strlen($img['ext'])));
			$IMG->print_img_import('imagen',0,0,0,0,$IMG->w,$IMG->h);
			$IMG->Output();
		}else
		{
			include("img_error.php");
		}
	}else
	{
		$error=$database->error();
		include("img_error.php");
	}
	
}else
{
	include("img_error.php");
}

?>