<?php
include ("clases/config_orveinca.php");
$min= new MinScript();
echo '<pre>';

$CSS2=[
	"src_orig/css/style.css",
    "src_orig/css/catalogo.css",
	"src_orig/css/menu_simple.css",
];
$js=[
    "src_orig/js/jquery-1.8.0.min.js",
    "src_orig/js/info.js",
    "src_orig/js/mis_funciones.js",
    "src_orig/js/menu_simple.js",
    "src_orig/js/object_ajax.js"
];
if(!empty($_GET['min']))
{
	
	print_r($min->FileMin($CSS2,'min',"src/css/orveinca.min.css",'css'));

print_r($min->FileMin("src_orig/css/preview.css",'',"src/css/preview.min.css",'css'));
print_r($min->FileMin("src_orig/css/font-awesome.css",'',"src/css/font-awesome.css",'css'));
print_r($min->FileMin("src_orig/css/prettyPhoto.css",'',"src/css/prettyPhoto.min.css",'css'));
print_r($min->FileMin("src_orig/css/login_form.css",'',"src/css/login_form.min.css",'css'));

}


//print_r($min->FileMin($js,'min',"src/js/orveinca.min.js",'js'))
/*  */


?>