<?php
include("../clases/config_orveinca.php");

if(!empty($_GET['dir']))
{	
	$tipo;
	for($i=strlen($img)-1;$i>0;$i--)
	{
		if (substr($img,$i,1)==".")
		{
			$tipo=substr($img,$i+1);
			break;
		}
	}
	$fmt= "image/$tipo";
	$IMG=new IMG($_GET['w'],$_GET['h'],$fmt);
	$IMG->importar_img('imagen',$_GET['dir']);
	$IMG->print_img_import('imagen',0,0,0,0,$IMG->w,$IMG->h);
	$IMG->Output();
}else
{
	include("img_error.php");
}
?>