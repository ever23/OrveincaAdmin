<?PHP
include("../clases/config_orveinca.php");
$html= new HTML();
$html->prettyPhoto();

?>
<style type="text/css"></style>
<script type="text/javascript">
$(document).ready(function(e) {
   star_load_pdf();
   
   $('select[name=opcion1]').change(function(e) {
    
	if($(this).val()=='mes')
	{
		$('input[name=text_date]').fadeIn();
	}else{ if($(this).val()=='ano')
	{
		
	}else
	{
		$('input[name=text_date]').fadeOut();
	}}
	});
	
	$('input[name=text_date]').change(function(e) {
		var opcion=$('select[name=opcion1]').val();
		var value=$(this).val();
		 star_load_pdf();
		$('iframe').attr('src','reportes_pdf.php?reporte=mes&value='+value);
		   	
	});
	$('input[name=text_date]').tics("SELECCION EL MES DEL CUAL SE GENERARA EL REPORTE");
});


</script>
<div   class="conten_ico" >

  <div id="pdf" class="pdf" title="inventario_pdf.php"></div>

</div>

<div align="center" >


  <h1>REPORTES DE INVENTARIO</h1>
   <div align="center" class="form_search">
     
   <input type="month" name="text_date" >
    
  </div>
  
  <div class="center_content" id="muestra">
    <div align="center" class="stabla">
      <h2></h2>
    </div>
    <div id="pdf_blok">
    
      <iframe id="iframe" src="inventario_pdf2.php" width="960" height="600" ></iframe>
    </div>
    
  </div>
</div>
