<?php

//var $this de la clase HTML
$this->addlink_css('{src}orveinca.min.css','U');
$this->addlink_js('{src}orveinca.min.js','U');
$this->addlink_css('{src}jquery-ui.min.css','U');
?><head>
<?php echo $this->link_cssjs()?>
 <script type="text/javascript"><?php echo $this->GetJsScript()?></script>
  <style type="text/css">
<?php echo $this->GetCssScript()?>
</style>
</head>
<div class="container"> 
 <?php echo $content ?>
</div>
<div class='cargando'></div>

<div id='errores' class='errores'   title='<?php echo htmlentities("<H2>!! ERROR ¡¡</H2>")?>' style='display:none;'>
  <div id='error_div'><?php echo $this->errores?></div>
</div>
