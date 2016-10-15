<?php
if(!empty($_GET['id']))
{
	include("../clases/config_orveinca.php");
	$database= new  MySQLi(HOST,USER,PASS,DB);
	if($res=$database->query("SELECT * FROM imagenes where id_imag = '$_GET[id]';"))
	{
		if($res->num_rows>0)
		{
			$img=$res->fetch_array();
			header("Content-type: image/png");
			echo $img['img'];
			exit;
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