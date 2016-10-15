
var popUpWin=0;
function popUpWindow(URLStr,w,h)
{
	if(popUpWin)
 	{
		if(!popUpWin.closed) popUpWin.close();
	}
    popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+w+',height='+h+'');
}
function stop_load()
{
	$('.cargando').show().stop(true, true).animate({ 'top' : -50 });
}

function star_load()
{
	$('.cargando').animate({ 'top' : 20 });
}
function star_load_pdf()
{
	$('.load_catalogo').animate({ 'top' : 114 });
	$('#barra_load_pdf').animate({ 'top' : 116 });
}

function stop_load_pdf()
{
	$('.load_catalogo').animate({ 'top' : -300 });
	$('#barra_load_pdf').animate({ 'top' : -333 });
}
function error(header,msj,funct)
{
	if(typeof funct!='function')
	{
		funct=function(){};
	}
	$( '#errores:ui-dialog' ).dialog('destroy' );
	$( '#errores' ).dialog( 'close' );
	$( '#errores' ).attr('title','<H2>'+header+'</H2>');
	$( '#error_div' ).html(msj);
	$( '#errores' ).dialog({
		height: 300,
		width:300,
		modal: true,
		buttons: {
			'Cerrar': function() 
			{
				$( '#errores' ).dialog( 'close' );
				funct();
			}
		}
	});
}
/*
*	FUNCIONA IGUAL QUE LA VERSION DE PHP
*
*/
function str_replace(char,rem,str)
{
	if(str.indexOf(char)!=-1)
	{
		return str_replace(char,rem,str.replace(char,rem))
	}else
	{
		return str;
	}
}


/***********************************************************/
/**  FUNCIONES PARA AGREGAS Y ELIMINAR CAMPOS DE TELEFONOS */
/***********************************************************/
function add_tel(indice,varjs)
{
	var aux=indice+1;
	var htm="<div id='"+varjs+"telefonos"+indice+"'>";
	htm+="<input name='"+varjs+"id_tel["+indice+"]' value='no_value' type='hidden'>";
	htm+='  <div class="elimina del_tel"  onClick="del_tel(\''+indice+'\',\''+varjs+'\')" ></div>';
	htm+="<input  type='tel' name='"+varjs+"telefono["+indice+"]' placeholder='####### ' class='main_input telef'  />";
	htm+="</div>";
	$('#'+varjs+'telefonos'+indice).html(htm);

	$('#'+varjs+'telefonos').append("<div id='"+varjs+"telefonos"+aux+"'> </div>");
	$('input[type=tel]').FmtTelefono();
	/*	var tel=$('#'+varjs+'telefonos').html();
		$('#'+varjs+'telefonos').html('');
		$('#'+varjs+'telefonos').html(tel+"<div id='"+varjs+"telefonos"+aux+"'> </div>");*/
	return aux;
}

function del_tel(indice,varjs)
{
	$('#'+varjs+'telefonos'+indice).html('');
	$('#'+varjs+'telefonos'+indice).fadeOut();

}

function del_tel_ajax(index,vajs,tel)
{
	$( '#errores:ui-dialog' ).dialog('destroy' );
	$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
	$( '#error_div' ).html('ESTA SEGURO DE ELIMINAR EL NUMERO DE TELEFONO');
	$( '#errores' ).dialog({	
		height:300,
		modal: true,
		buttons: 
		{
			'ELIMINAR': function()
			{
				$( '#errores' ).dialog( 'close' );
				$().load_html('../ajax/ajax.php',{'del_tel':true,'telf':tel},
							  function(html)
							  {
					if(html=='')
					{
						$('#'+vajs+'telefonos'+index).fadeOut(); 
					}else
					{
						error('ERROR',html)	;
					}


				});
			},
			Cancel: function() 
			{
				$( '#errores' ).dialog( 'close' );
			}
		}
	});
}


/***********************************************************/
/**  FUNCIONES PARA AGREGAS Y ELIMINAR CAMPOS DE BANCOS    */
/***********************************************************/
var ajax_BANC=
    {
        data : { 'bancos_json' : 123 },
        success : function(json) {
            var html='';
			try
			{
            	for(var i=0;i<json.id_banc.length;i++)
           		 {
                	html+="<option value='"+json.id_banc[i]+"'>"+json.nomb_banc[i]+"</option>";
            	}
				$(this).html(html);
			}
			catch(Exeption)
			{
				this.OpcAjaxExteds.error('','','LARESPUESTA DEL SERVIDOR NO FUE LA ESPERADA <BR>'+Exeption)
			}
            
        },
    };
function add_banco(indice,varjs)
{

	var aux=indice+1;
	var htm;
	var htm2=$('#bancos_').html();


	htm=' <select name="'+varjs+'banco['+indice+']" id="'+varjs+'banco_select'+indice+'"> </select>';
	htm+='</select>';
	htm+='        <select name="t_cuenta['+indice+']" >';
	htm+='       <option value="corriente">corriente</option>';
	htm+='        <option value="nomina">nomina</option>';
	htm+='        <option value="ahorro">ahorro</option>';
	htm+='</select><BR />';
	htm+= '<input type="text" name="'+varjs+'nro_cuenta['+indice+']" onKeyUp="fmt_banco(event)"  placeholder="NRO CUENTA'+indice+'"  class="main_input"/> ';
	htm+='  <div class="elimina del_banc" onClick="del_banco('+indice+',\''+varjs+'\')"id="delbanco"></div>';

	$('#'+varjs+'banco'+indice).html(htm);
	$('#'+varjs+'bancos').append('<div id="'+varjs+'banco'+aux+'"></div>');
	star_load();
	$('#'+varjs+'banco_select'+indice).AjaxExteds(ajax_BANC);
	/*var banc=$('#'+varjs+'bancos').html();
	htm= '<div id="'+varjs+'banco'+aux+'"></div>';
	$('#'+varjs+'bancos').html(banc+htm);*/
	return aux;
}

function del_banc_ajax(index,vajs,num)
{
	$( '#errores:ui-dialog' ).dialog('destroy' );
	$( '#errores' ).attr('title','CONFIRMA PARA ELIMINAR');
	$( '#error_div' ).html('ESTA SEGURO DE ELIMINAR EL NUMERO DE CUENTA');
	$( '#errores' ).dialog({	
		height:300,
		modal: true,
		buttons: 
		{
			'ELIMINAR': function()
			{
				$( '#errores' ).dialog( 'close' );
				$().load_html('../ajax/ajax.php',{'del_banc':true,'numero':num},
							  function(html)
							  {
					if(html=='')
					{
						$('#'+vajs+'banco'+index).fadeOut(); 
					}else
					{
						error('ERROR',html)	;
					}
				});
			},
			Cancel: function() 
			{
				$( '#errores' ).dialog( 'close' );
			}
		}
	});
}


function del_banco(indice,varjs)
{
	$('#'+varjs+'banco'+indice).html('');
	$('#'+varjs+'banco'+indice).fadeOut();
}

function fmt_banco(e)
{

}
/*** END BANCOS */

function del_tp(id)
{
	$("#tamano_costo"+id+"").fadeOut();
	$("#tamano_costo"+id+"").html('');
}

var ind_tc=0;