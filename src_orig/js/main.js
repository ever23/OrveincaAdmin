// JavaScript Document
var unidad_medida=null;
var root_html='../';
function main(MainEvent)
{
	$(window).load(stop_load);
	$('.cargando').ajaxStart(star_load).ajaxStop(stop_load);
	$('input[type=tel]').FmtTelefono();

	$('#rif').FmtIdentificacion('select[name=codi_tide]',{
		tipos:{
			'J-':
			{
					fmt:$.FmtIdentificacion.Fmt,
					valid:$.FmtIdentificacion.ValidDefault,
					msj:'EL RIF ES INVALIDO',
					maxlength:10	
			}
		}
		});
	$('#ci').FmtIdentificacion('C.I-');

	$('input[name=foto]').change(function(e) {
		var formData = new FormData($('form[name=frmDatos]')[0]);
		$('#foto').load_json(root_html+'ajax/ajax.php',formData,function(json)
							 {
			$('#foto').attr('src',json.src);
		},{  contentType: false, processData: false,});
	});
	$('#generate_nota').click(function(e) {
		e.preventDefault();
		var href=  $(this).attr('href');
		location.href=href+'&ci_empl='+$('select[name=ci_empl]').attr('value');
	});

	$('#criterio_search').click(function(e) {

		if(e.target.checked)
		{
			$('#opcion').fadeIn(300);	

			$('.form_search').animate({'width':'274'},500)
		}else
		{
			$('#opcion').fadeOut(300);	
			$('#opcion').attr('value','all');
			$('.form_search').animate({'width':'156'},500)
		}
	});
	$('#busqueuda').click(function()
						  {

		if($('.form_search').css('display')=='none')
		{


			$('.form_search').fadeIn(200);	
		}
		else
		{

			$('.form_search').fadeOut(300);
		}

	});





	$("#new_enc").click(function()
						{
		$('.contacto_new').css('display','block');
		$('#contacto_bus').html('');
		$("#new_enc").css('display','none');
		$("#buscar_enc").css('display','block');
	});

	$("#buscar_enc").click(function()
						   {
		$('.contacto_new').css('display','none');
		$('#contacto_bus').load_html('../ajax/ajax.php',{'contactos' : 1});
		$("#buscar_enc").css('display','none');
		$("#new_enc").css('display','block');
	});

	$('#new_t_c').click(function(e) {

		var aux_tc=ind_tc+1;
		var html_tc='';
		html_tc+=' <div class="tamano_costo" id="tamano_costo'+ind_tc+'">  <div class="elimina del_tp" id="del_tp'+ind_tc+'" onClick="del_tp(\''+ind_tc+'\')"></div> ';
		html_tc+='   <div  align="center" class="div_costo" id="div_costo'+ind_tc+'"> <bR>';
		html_tc+='     <input name="costo['+ind_tc+']"  class="input_text" placeholder="costo" required type="text">';
		html_tc+='    </div>';
		html_tc+='    <div align="center" class="div_tamano"  id="div_tamano">';
		html_tc+='      <div id="tamano'+ind_tc+'" > ';
		html_tc+='  </div>';
		html_tc+='    </div>';

		html_tc+=' </div>';

		$('#tamano_costo'+ind_tc).fadeIn(300);
		$('#tamano_costo'+ind_tc).html(html_tc);
		if((ind_tc%2)==0)
		{
			$('#tamano_costo'+ind_tc).addClass('row_act');
		}

		$('#meiddas').append( '<div class="tamano_costo" id="tamano_costo'+aux_tc+'"></div>');
		/*var medidas= $('#meiddas').html();
	   $('#meiddas').html(medidas+ '<div class="tamano_costo" id="tamano_costo'+aux_tc+'"></div>');*/

		if(unidad_medida!=null)
		{
			$('#tamano'+ind_tc).load_html('ajax_precios.php',{'u_medida':unidad_medida,'ind':ind_tc}); 
		}

		ind_tc++;	
	});


	$('#u_medida').change(function(e) 
						  {
		var ind=e.target.selectedIndex;
		var opcion=e.target.options[ind].value;


		for(var i=0;i<ind_tc;i++)
		{
			star_load();
			$('#tamano'+i).load_html('ajax_precios.php',{'u_medida':opcion,'ind':i}); 
		}

		//alert(i);

		unidad_medida=opcion;
	});
	$('#b_reset').click(function()
						{
		location.href=location;	
	});


	$('#pdf').click(function(evento)
					{ 
		var sql_where='';
		if($('#sql_where').empty())
		{
			sql_where='?where='+$('#sql_where').html();
		}
		var htm=  $('#pdf').attr('title')+sql_where;
		var w=960;
		var h=600;
		if($('#conten_html').css('display')!='none')
		{	
			star_load_pdf();
			$('#conten_html').fadeOut(function(){$('#iframe').fadeIn(); });

			$('#iframe').attr('src',htm);
			$('#iframe').attr('width',w);
			$('#iframe').attr('height',h);

		}else
		{

			$('#iframe').fadeOut(function(){$('#conten_html').fadeIn();});
			//$('#iframe').attr('src','');
			$('#iframe').attr('width',0);
			$('#iframe').attr('height',0);
		}
	});
	$('#iframe').load(function(e) {
		stop_load_pdf();
	});

	$('#pdf_frame_info').click(function(e) {
		e.preventDefault();
		if($('#iframe').css('display')=='none')
		{
			var htm=$(this).attr('href');
			var h=390;
			var w=590;
			star_load_pdf();
			$('#conten_html').fadeOut('slow','',function(){
				$('#pag_pdf').fadeIn(); 
				$('#iframe').fadeIn();


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
			//$('#iframe').attr('src','');
		}

	});

	$('select[name=cont_id_muni]').change(function(e)
										  {
		var value=$(this).attr('value');
		var afect=$(this).attr('title');
		$('select[name='+afect+']').load_json('../ajax/ajax.php',{ 'parroquias_json' : true,'id_muni':value  },
											  function(json) 
											  {
			var html='<option value="NULL">PARROQUIAS</option>';
			for(var i=0;i<json.id_parr.length;i++)
			{
				html+="<option value='"+json.id_parr[i]+"'>"+json.desc_parr[i]+"</option>";
			}
			return html;
		});

	});
	$('select[name=id_muni]').change(function(e)
									 {
		var value=$(this).attr('value');
		var afect=$(this).attr('title');
		$('select[name='+afect+']').load_json('../ajax/ajax.php',{ 'parroquias_json' : true,'id_muni':value  },
											  function(json) 
											  {
			var html='<option value="NULL">PARROQUIAS</option>';
			for(var i=0;i<json.id_parr.length;i++)
			{
				html+="<option value='"+json.id_parr[i]+"'>"+json.desc_parr[i]+"</option>";
			}
			return html;
		});

	});
	$('select[name=id_estado]').change(function(e)
									   {
		var value=$(this).attr('value');
		var afect=$(this).attr('title');
		$('select[name='+afect+']').load_json('../ajax/ajax.php',{ 'municipios_json' : true,'id_esta':value  },
											  function(json) 
											  {
			var html='<option value="NULL">MUNICIPIOS</option>';
			for(var i=0;i<json.id_muni.length;i++)
			{
				html+="<option value='"+json.id_muni[i]+"'>"+json.desc_muni[i]+"</option>";
			}
			return html;
		});

	});
	$('#row_inve').tics('AGREGA UN REGISTRO AL INVENTARIO');
	$('#atras').tics('REGRESAR');
	$('.newtelf').tics('NUEVO CAMPO PARA TELEFONO');
	$('#buscar_enc').tics('SELECCIONA EL CONTACTO  EXISTENTE');
	$('#new_enc').tics('INGRESA UN NUEVO CONTACTO');
	$('#newbanco').tics('NUEVO CAMPO PARA CUENTA BANCARIA');	
	$('#pdf').tics('GENERA UN DOCUMENTO PDF');
	$('#new_vendedor').tics('INGRESA UN NUEVO PROVEDOR');	
	$('#busqueuda').tics('BUSCAR');		

	$('.editar_tc').tics('EDITA EL COSTO ');		
	$('.elimina_tc').tics('ELIMINA EL PRECIO Y TALLAS');	

	$('#marca_m').tics('SELECCIONA LA MARCA DEL PRODUCTO');
	$('#modelo').tics('SELECCIONA EL MODELO DEL PRODUCTO');
	$('#u_medida').tics('SELECCIONA LA UNIDAD DE MEDIDA QUE UTILIZARA EL PRODUCTO');
	$('#new_t_c').tics('AGREGA UN CAMPO PARA EL TAMANOS Y COSTO ');
	$('.del_tp').tics('ELIMINA EL CAMPO PARA  TAMANOS Y COSTO ');

	$('#select_img').tics('SELECCIONA PARA ENVIAR LA IMAGEN');	
	$('#load_img').tics('SELECCIONA PARA ENVIAR LA IMAGEN');	
	$('#desc').tics('INGRESA LA DESCRIPCION DEL PRODUCTO');	
	$('#clpr').tics('SELECCIONA LA CLASIFICACION DEL PRODUCTO');	
	$('#criterio_search').tics('SELECCIONAR CRITERIO DE BUSQUEDA');
	$('#new_cliente').tics('INSERTAR NUEVO CLIENTE');
	$('#desechar_coti').tics('DESECHAR COTIZACION');
	$('#desechar_orde').tics('DESECHAR ORDEN DE COMPRA');
	$('#desechar_entr').tics('DESECHAR NOTA DE ENTREGA');
	$('#new_prod').tics("INSERTAR NUEVO PRODUCTO");
	$('#catalogo_pdf').tics('GENERA EL CATALOGO DE LA LISTA DE PRECIOS');
	$('#editar_comi').tics("EDITAR LA COMICION");
	$('#new_pedi').tics("INSERTAR NUEVO PEDIDO");
	$('.cancelar_pedi_pro').tics('CANCELAR EL PRODUCTO EN EL PEDIDO');
	$('.buscar_clie').tics('BUSCAR UN CLIENTE');
	$('.dolar').tics("REGISTRAR PAGO");
	$('input[name=exad]').tics("SELECCIONA EL COLOR");
	$('input[name=desc_colo]').tics("INGRESA LA DESCRIPCION DEL COLOR");
	$('#otro_pre').tics("EDITAR EL PRECIO");


}
$(document).ready(main);