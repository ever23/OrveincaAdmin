<?PHP

require_once("../clases/config_orveinca.php");
$html= new HTML();
$database= new PRODUCTOS();
$html->prettyPhoto();

?>
<script type="text/javascript">
$(document).ready(function(e) {
   star_load_pdf();
   $('select[name=opcion1]').change(function(e) {
    
	if($(this).val()=='mes')
	{
		$('input[name=text_date]').fadeIn();
	}else
	{ if($(this).val()=='ano')
	{
		
	}else
	{
		$('input[name=text_date]').fadeOut();
	}}
	});
	
	$('input[name=text_date]').change(function(e) {
		var value=$(this).val();
		var codi_tpga=$('select[name=codi_tpga]').val();
		 star_load_pdf();
		$('iframe').attr('src','reportes_pdf.php?opcion=mes&value='+value+(codi_tpga!=''?'&codi_tpga='+codi_tpga:''));
		   	
	});
	$('input[name=text_date]').tics("SELECCION EL MES DEL CUAL SE GENERARA EL REPORTE");
});
</script>

<div   class="conten_ico" >
  <div id="pdf" class="pdf" title="reportes_pdf.php"></div>
</div>
<div align="center" >
  <h1>REPORTES DE GASTO</h1>
  <div align="center" class="form_search">
    <select name="codi_tpga">
      <option value=""></option>
      <?php
	$database->consulta("select * from tipogasto");
	while($campo=$database->result())
	{
		echo "<option value='".$campo['codi_tpga']."'>".$campo['desc_tpga']."</option>";
	}
	?>
    </select>
    <br>
    <input type="month" name="text_date" >
  </div>
  <div class="center_content" id="muestra">
    <div align="center" class="stabla">
      <h2></h2>
    </div>
    <div id="pdf_blok">
      <iframe id="iframe" src="reportes_pdf.php" width="960" height="600" ></iframe>
    </div>
  </div>
</div>
