<?php
//var $this de la clase HTML
//var $conten contenido html del documento
$this->addlink_css('{src}orveinca.min.css','U');
//$this->addlink_js('{src}orveinca.min.js','U');
$this->addlink_js([
				"{src_orig}jquery-1.8.2.min.js",
				"{src_orig}jquery-ui.min.js",
				"{src_orig}ext-jquery/jquery.AjaxExteds.js",
				"{src_orig}ext-jquery/jquery.info.js",
				"{src_orig}ext-jquery/jquery.FmtIdentificacion.js",
				"{src_orig}ext-jquery/jquery.FmtTelefono.js",
			    "{src_orig}functions.js",
				"{src_orig}menu_simple.js",
				"{src_orig}main.js"
			],'U');;
$this->addlink_css('{src}jquery-ui.min.css','U');
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
<div class="help" id="help"></div>
<div id='errores' class='errores'  title='<?php echo htmlentities("<H2>!! ERROR ¡¡</H2>")?>' style='display:none;'>
  <div id='error_div'><?php echo $this->errores?></div>
</div>
</body>
</html>

