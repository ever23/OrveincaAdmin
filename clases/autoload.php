<?php
require("app/SearchClass.php");
//EJECUCION GLOBAL
SearchClass::StartAutoloadClass(
array(
	array('dir'=>dirname(__FILE__).'/extends/','avance'=>false),
	array('dir'=>dirname(__FILE__).'/app/','avance'=>false),
	array('dir'=>dirname(__FILE__).'/extern/','avance'=>false)
)
);
