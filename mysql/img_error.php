<?php
include("../clases/config_orveinca.php");


if(!empty($_GET['error']))
{
	$titulo=lnstring($_GET['error'],14,"\n");
}else
{
	$titulo=lnstring("IMAGEN NO EXISTE EN EL SISTEMA ",14,"\n");
}

	
$IMG=new IMG(200,200,"image/png");
$IMG->load_ttf('font1',"../src/ttf/airstrike.ttf");
$IMG->create_color('negro',1,1,1);
$IMG->create_color('rojo',255,1,5);
$IMG->importar_img('logo','../img/error_img1.png');
$IMG->print_img_import('logo',50,100,0,0,110,100);

$IMG->rectangulo(1,1,199,199,'rojo');
$IMG->text_print_ttf(10,0,10,30,'negro','font1',$titulo);
$IMG->Output('ERROR.PNG','I');

?>