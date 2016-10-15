<?php
//var $this de la clase HTML
//var $conten contenido html del documento
/*$this->addlink_css(
[
"{src_orig}style.css",
"{src_orig}catalogo.css",
//"{src_orig}menu.css"
]);
$this->addlink_js(
[
	"{src_orig}jquery-1.8.0.min.js",
	"{src_orig}jquery-ui.min.js",
	"{src_orig}object_ajax.js",
  	"{src_orig}mis_funciones.js",
    "{src_orig}info.js"
]);		*/
	$this->addlink_css('{src}orveinca.min.css','U');
		$this->addlink_js('{src}orveinca.min.js','U');
		$this->addlink_css('{src}jquery-ui.min.css','U');
$this->addlink_css("{src}login_form.min.css",'U');
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<link rel="shortcut icon"  href="<?php echo $this->ico ?>"  media="monochrome"/>
<title><?php echo $this->titulo?></title>
<?php echo $this->link_cssjs()?>
<!--[if lt IE 9]><script src='<?php echo $this->ROOT_HTML?>src/js/html5shiv.min.js'></script><![endif]-->
<!--[if lt IE 8]><script src='<?php echo $this->ROOT_HTML?>src/js/html5shiv.min.js'></script><![endif]-->
<!--[if lt IE 7]><script src='<?php echo $this->ROOT_HTML?>src/js/html5shiv.min.js'></script><![endif]-->
<script type='text/javascript'>
<?php echo $this->GetJsScript()?>
</script>
<style type='text/css'>
<?php echo $this->GetCssScript()?>
</style>
</head>
<body>
<div id="wrap">
  <div id="main_container">
  
    <div class="center_content">
    	<div id="header">
      <div id="logo" ><img  src="<?php echo __LOGO_LARGO_ORVEINCA__?>" width="397" height="78" border="0" alt="ORGANIZACION VENEZOLANA EN SEGURIDAD INDUSTRIAL.CA"
	   title="ORGANIZACION VENEZOLANA EN SEGURIDAD INDUSTRIAL.CA" /></div>
    </div>
    <div class="middle_banner"> </div>
      <div class="cargando"></div>
      <div class="content"> <?php echo $content ?> </div>
      <div class="load_catalogo"></div>
      <div id="barra_load_pdf">
        <div id="barra_load_"></div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="footer">
      <div class="copyright"> </div>
      <div class="footer_links"></div>
    </div>
  </div>
</div>
</body>
</html>
<div class="help" id="help"></div>
<div id='errores' class='errores'  title='<?php echo htmlentities("<H2>!! ERROR ¡¡</H2>")?>' style='display:none;'>
  <div id='error_div'><?php echo $this->errores?></div>
</div>
