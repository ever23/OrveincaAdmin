<?php
include("../clases/config_orveinca.php");


if(!empty($_GET['msj']))
{
	$titulo=lnstring($_GET['msj'],7,"\n");
}else
{
	$titulo="";
}
$IMG=new IMG(100,200,"image/png");
$IMG->load_ttf('font1',"../src/ttf/airstrike.ttf");
$IMG->create_color('negro',1,1,1);
$IMG->create_color('rojo',255,1,5);
$IMG->rectangulo(1,1,99,199,'rojo');
$IMG->text_print_ttf(10,0,20,60,'negro','font1',$titulo);
$IMG->Output('msj.PNG','I');

?>