<?php
require_once "lib_fun.php";
require_once "orveinca.php";
define('__VERSION_ORVEINCA_ADMIN__','2.3.2.1');
define('__DIRECCION_FISCAL__','DIRECCION: CALLE CRISTOBAL MENDOZA C.C MILY LOCAL N-1 SECTOR LOS ANGELES A POCOS METROS DEL COMPLEJO FERIAL, SABANA DE MENDOZA MUNICIPIO SUCRE ESTADO TRUJILLO.');
define('__CONTACTO__',"\nTELEFONOS: 0271/4156898 0414/7538554 0414/7329577 \nCORREOS: ORVEINCA@GMAIL.COM");
define('__PDF_MSJ_FOOTHER__',"TIEMPO DE ENTREGA EN EQUIPO:  EN CASO DE NO TENER LA EXISTENCIA, 10 DIAS HABILES, FABRICACION Y ENTREGA DE UNIFORMES 22 DIAS HABILES. AL CONCRETARSE LA COMPRA SE SOLICITA EL 50% ADELANTADO DEL MISMO LOS PRECIOS ESTARAN SUJETOS A MODIFICACIONES SIN PREVIO AVISO DEVIDO A AUMENTOS EN LOS COSTOS POR CIRCUNSTANCIAS FUERA DE NUESTRO CONTROL EN LA LEGISLACION VIGENTE. NUESTRA EMPRESA OFRECE ENTREGA DE LA MERCANCIA A NIVEL ESTADAL, SIN NINGUN COSTO ADICIONAL, LA PRESENTE COTIZACION TENDRA UNA VALIDEZ DE 3 DIAS HABILES DESDE LA FECHA DE SU EMISION......");
define('__SLOGAN__',"TE RECORDAMOS LA PREVENCION NO ES UN GASTO ES UNA \"INVERSION\"");
define('__NILL__','411388-1');
define('__LOGO_REDONDO_ORVEINCA__',__AUTORIZATE_DIRNAME__.'images/logo.gif');
define('__LOGO_LARGO_ORVEINCA__',__AUTORIZATE_DIRNAME__.'images/logo_largo.png');
/** TIPOS DE MESAJES DE ERROR EN EXEPCIONES OrveincaExeption */
define('__DOCUMENT_ROOT__','/orveinca');
require("autoload.php");
Server::FilterXss(Server::FilterXssAll);//filtra y previene  ataques XSS
OrveincaExeption::SetMode(2);
SESSION::SeCookie('ORVEINCA_ADMIN','nocache,limiter',NULL,__DOCUMENT_ROOT__);
SESSION::Start();
?>