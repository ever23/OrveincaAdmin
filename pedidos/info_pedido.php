<?php
require_once("../clases/config_orveinca.php");
$time= new TIME();
$html= new INFO_HTML();
$html->uipanel('#panel',2);
if(empty($_GET['nume_pedi']))
$html->__destruct();
$database= new PRODUCTOS();
?>
<style>

.atras
{
	position: absolute;
	left: 545px;
	top: -10px;
}

#iframe { display:none; }
.load_catalogo,#barra_load_pdf{ left:140px;}
#pag_pdf{
	 display:none; 
}
</style>
<script>
$(document).ready(function(e) {
    $('.iguala_pedi_pro').click(function(e) {
		e.preventDefault();
	 var selec=$(this);
     var id_pepr=selec.attr('href');
     var valor=confirm('ESTA SEGURO IGUALAR LO ENTREGADO CON LO QUE ESTA POR ENTREGAR?');
	if(valor)
	{
			$().load_json("ajax_pedidos.php",{"iguala_pedi_prod":true,'id_pepr':id_pepr }, 
			function(json){
				if(!json.error)
				{
					
                    $("#cant_pentr"+id_pepr).html($("#cant_entr"+id_pepr).html());
                    $("#cant_entr"+id_pepr).removeClass('P').addClass('E');
					selec.fadeOut();
				
					
				}else
				{
					error('ERROR',json.error);
				}
			});	
	}	
    });
    
	$('.cancelar_pedi_pro').click(function(e) {
		e.preventDefault();
	 var selec=$(this);
     var id_pepr=selec.attr('href');
     var valor=confirm('ESTA SEGURO CANCELAR EL PRODUCTO DEL PEDIDO');
	if(valor)
	{
			$().load_json("ajax_json.php",{"cancela_pedi_prod":true,'id_pepr':id_pepr }, 
			function(json){
				if(!json.error)
				{
					$("#cant_entr"+id_pepr).html('').removeClass('P').addClass('C');
					selec.fadeOut();
					$("#"+id_pepr).addClass('cancel');
					
				}else
				{
					error('ERROR',json.error);
				}
			});	
	}	
    });
	$('#pdf_doc').click(function(e) {
        e.preventDefault();
		if($('#iframe').css('display')=='none')
		{
			var htm=$(this).attr('href');
			var h=390;
			var w=590;
			star_load_pdf();
			$('#conten_html').fadeOut('slow','',function(){ 
			$('#iframe').fadeIn();
			$('#pag_pdf').fadeIn();
			   });
			
			$('#iframe').attr('src',htm);
			$('#iframe').attr('width',w);
			$('#iframe').attr('height',h);
		}else
		{
			$('#iframe').fadeOut('slow','',function(){
			$('#conten_html').fadeIn();
			$('#pag_pdf').fadeOut(); 
			});
			$('#iframe').attr('src','');
		}
		
    });
	 $('#iframe').load(function(e) {
    $('#panel-1').css('height',$('#panel-1> div').height()+5);
});
	$('#carta').click(function(e) {
		  e.preventDefault();
		
    $('#iframe').attr('src', $('#pdf_doc').attr('href')+'&style=Letter');
	star_load_pdf();
});
$('#oficio').click(function(e) {
	  e.preventDefault();
    $('#iframe').attr('src', $('#pdf_doc').attr('href')+'&style=Legal');
	star_load_pdf();
});


//editar color
$('.edit_color').dblclick(function(e) {
	
	//console.log($('td[id=cant_entr'+$(this).closest('tr').attr('id')+']').html());
	var cant=$('td[id=cant_entr'+$(this).closest('tr').attr('id')+']').html();
	if(cant=='' || Number(cant)!=0)
	return ;
	var exad=$(this).attr('Ccolor');
    var edit_color=$('<select name="exad_colo"></select>');
	
	edit_color.load_json('../ajax/ajax.php',{'Ccolor':true},function(json){
		var html='',sel=false;
			if(json.error)
			{
				error('ERROR',json.error);
				return '';
			}
			for(var i=0;campo=json.result[i];i++)
            {
				if(exad==campo['exad'])
				{
					html+="<option selected value='"+campo['exad']+"'style='color:"+campo['exad']+";'>"+campo['desc_colo']+"</option>";
					sel=true;
				}else
				{
					html+="<option value='"+campo['exad']+"'style='color:"+campo['exad']+";'>"+campo['desc_colo']+"</option>";
				}
			}
			if(sel==false)
			{
				html="<option selected  value='#'>NUNGUNO</option>"+html;
			}else
			{
				html="<option  value='#'>NUNGUNO</option>"+html;
			}
			html+="<option  value='otro'>otro</option>";
			return html;
		});
		edit_color.change(EditColorChang);
		$(this).html(edit_color);
		
		
});


});

function EditColorChang(e)
{
	var exad=e.target.value;
	var obj=$(this);
	var id=obj.closest('tr');
	var otro_exa=$('<input type="color" name="exad"  >');
	var otro_des=$('<input type="text" name="desc_colo"placeholder="descripcion del color">');
	
	if(exad=='otro')
	{
		otro_des.keyup(OtroColorKey);
		obj.closest('td').html(otro_exa).append(otro_des);
		
		
		
		return 0;
	}
	$().load_json('../ajax/ajax.php',{'edit_color_pedi_p':true,'id_pepr':id.attr('id'),'exad':exad},function(json)
	{
		
		if(json.error)
		{
			error('ERROR',json.error);
			return '';
		}
		var campo=json.result[0];
		obj.closest('td').html(campo['desc_colo']).attr('Ccolor',campo['exad']);
		obj.remove();
		
	});
	
}
function OtroColorKey(e)
{
	var desc=e.target.value;
	var obj=$(this);
	var id=obj.closest('tr');
	var otro_exa=obj.siblings();
	
	//var otro_exa.
	if(e.which==13)
	{
		$().load_json('../ajax/ajax.php',{'insert_color_pedi_p':true,'id_pepr':id.attr('id'),'exad':otro_exa.val(),'desc_colo':desc},function(json)
	{
		
		if(json.error)
		{
			error('ERROR',json.error);
			return '';
		}
		var campo=json.result[0];
		obj.closest('td').html(campo['desc_colo']).attr('Ccolor',campo['exad']);
		obj.remove();
		
	});
	}
	
	
}
function ajax_edit_color(json)
	{
		
		if(json.error)
		{
			error('ERROR',json.error);
			return '';
		}
		var campo=json.result[0];
		obj.closest('td').html(campo['desc_colo']).attr('Ccolor',campo['exad']);
		obj.remove();
		
	}
</script>

<div id="panel">
<ul>
  <li><a href="#panel-1">DATOS </a></li>
    <li><a href="#panel-2">PEDIDO</a></li>
  
</ul>
<div id="panel-1">
  <div class="produc" >
  <?php
if(!empty($_GET['extern']))
{
	echo "<a href='$_SERVER[HTTP_REFERER]'><div class='atras' id='atras'></div></a>";
}
?><a href="pedido_pdf.php?nume_pedi=<?php echo $_GET['nume_pedi']?>" id="pdf_doc">
<div class="pdf"></div></a>
<div align='center' id="pag_pdf"><a href='' id='carta'><div>CARTA</div></a>  <a href='' id='oficio'><div>OFICIO</div></a></div>
  <iframe id="iframe"></iframe>

  <div id="conten_html">
<h1>PEDIDO N-<?PHP echo $_GET['nume_pedi']?></h1>
<?PHP
$database->consulta(PRODUCTOS::PEDIDOS,"nume_pedi='". $_GET['nume_pedi']."' ".'GROUP BY pedidos.nume_pedi');
$pedido=$database->result();
if($database->consulta(CLIENTES::CLIE,"idet_clie='".$pedido['idet_clie']."'"))
	$cliente=$database->result();
?>
<table width="560" border="0" cellpadding="0">
            <tr class="col_title">
              <td>&nbsp;</td>
                         <td>&nbsp;</td>
            </tr>
            <tr class="col_hov row_act ">
              <td>RASON SOCIAL </td>
              <td><?php echo $cliente['nomb_clie']?></td>
            </tr >
            <tr class="col_hov  ">
              <td>RIF : </td>
              <td><?php echo "$cliente[codi_tide]$cliente[idet_clie]"?></td>
            </tr >
            <tr class="col_hov row_act">
              <td>EMAIL:</td>
              <td><?php echo "$cliente[emai_clie] "?></td>
            </tr >
            <tr class="col_hov  ">
              <td>TELEFONOS:</td>
              <td>   <?php
			  $database->consulta("SELECT * FROM telefonos  WHERE id_tper='clie' and idet_pers='$cliente[idet_clie]'");
	 while($telefono=$database->result())
	{
		echo $telefono['#telf'].", ";
	} ?></td>
            </tr>
            <tr class="col_hov row_act">
              <td>DIRECCION:</td>
              <td><?php echo "$cliente[dire_clie], PARROQUIA: ".$cliente['desc_parr'].", MUNICIPIO: ".$cliente['desc_muni'].", ESTADO: ".$cliente['desc_esta']; ?></td>
            </tr>
            <tr class="col_hov ">
              <td>CONTACTO:</td>
              <td><?php echo $cliente['nom1_cont']." ".$cliente['nom2_cont'] ; ?></td>
            </tr>
             <tr class="col_hov row_act">
              <td>VENDEDOR:</td>
              <td><?php $pedido['nom1_empl']." ".$pedido['ape1_empl'] ?></td>
            </tr>
              <tr class="col_hov ">
         <td>NUMERO DE PEDIDO MANUAL:</td>
          <td> <?PHP  echo  $pedido['nped_manu'];?></td>
        </tr>
          </table>
    
        
    
</div>
  </div>
</div>
<div id="panel-2">
<div class="produc">
<h1>PEDIDO N-<?PHP echo $_GET['nume_pedi']?></h1>
<table width="600" border="0"  cellpadding="0" >
  <tr class="col_title">
    <td>CODI</td>
    <td>DESCRIPCION</td>
    <td>COLOR</td>
    <td>MEDIDA</td>
    <td>PREC U</td>
    <td>CANTIDAD</td>
    <td>ENTREGADO</td>
     <td></td>
  </tr>
  <?php
  $database->consulta(PRODUCTOS::PEDI," nume_pedi='$_GET[nume_pedi]'",'cant_entr ASC');
for($i=0;$campo=$database->result();$i++)
{
	  if($i%2==0) 
	 $row_act='';
	 else
   $row_act='row_act';
   $estado='';
   $cancel='';
   if($campo['cant_entr']==NULL)
   {
	    $estado='C';
		 $cancel='cancel';
   }else
   if($campo['cant_entr']<$campo['cant_pedi'])
   {
	    $estado='P';
   }
   else
   {
	    $estado='E';
   }
	echo "<tr class='col_hov  $row_act $cancel' id='".$campo['id_pepr']."'>
	<td>".$campo['codi_clpr'].$campo['id_prod']."</td>
	<td>".stripslashes("$campo[desc_prod] $campo[desc_mode] $campo[desc_marc] ")."</td>
	<td class='edit_color' Ccolor='".$campo['exad']."'>".$campo['desc_colo']."</td>
	<td >".$campo['codi_umed']." ".$campo['medi_tama']."</td>
	<td align='center'>".fmt_num($campo['prec_vent'])."</td>
	<td align='center' id='cant_pentr".$campo['id_pepr']."'>".$campo['cant_pedi']."</td>
	<td align='center' class='$estado' id='cant_entr".$campo['id_pepr']."'>".$campo['cant_entr']."</td>";
    if($campo['cant_entr']!=NULL)
	 if($campo['cant_entr']==0)
     {
         echo "<td> <a href='".$campo['id_pepr']."' class='cancelar_pedi_pro'><div class='elimina'></div></a></td>";
     }else
     {
         echo "<td> <a href='".$campo['id_pepr']."' class='iguala_pedi_pro'><div class='elimina'></div></a></td>";
     }
	
	
	echo "
	</tr>";
}
  ?>
</table>
</div>
 
</div>

</div>


<div class="load_catalogo"></div>
<div id="barra_load_pdf">
  <div id="barra_load_"></div></div>