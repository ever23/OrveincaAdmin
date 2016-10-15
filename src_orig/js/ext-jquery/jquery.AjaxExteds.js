/**
jquery.ajaxexteds jquery 1.8.2, jquery-ui
Autor Enyerber Franco (enyerverfranco@gmail.com ,enyerverfranco@outlook.com)
*/
(function($){
	$.fn.AjaxExteds=function(option)
	{
		
		var opciones=$.extend({},$.fn.AjaxExteds.OptionDefault, option);
			if(this==undefined)
			{
				opciones.context=$.extend({a:''},{OpcAjaxExteds:opciones});
			}else
			{
				opciones.context=$.extend({},this,{OpcAjaxExteds:opciones});	
			}
			$.ajax(opciones);
		return this;
	};
	$.fn.AjaxExteds.OptionDefault=
		{
		// la URL para la petición
		url : '../ajax/ajax.php',
		// la información a enviar
		// (también es posible utilizar una cadena de datos)
		data : { },
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta
		dataType : 'json',
		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success:function(json){},
		calback:function(ent){return ent;},
		msj_erro: 'ERROR EN CONECCION ',
		title_error:'ERROR ajax',

		// código a ejecutar si la petición falla;
		// son pasados como argumentos a la función
		// el objeto jqXHR (extensión de XMLHttpRequest), un texto con el estatus
		// de la petición y un texto con la descripción del error que haya dado el servidor
		error : function(jqXHR, status, error) {
		var title=this.OpcAjaxExteds==undefined?'ERROR EN CONECCION':this.OpcAjaxExteds.title_error;
		var msj=this.OpcAjaxExteds==undefined?'ERROR ':this.OpcAjaxExteds.title_error;
			$( '#errores:ui-dialog' ).dialog('destroy' );
			$( '#errores' ).attr('title','<H2>'+title+'</H2>');
			$( '#error_div' ).html(msj+error);
			$( '#errores' ).dialog({height: 300,width:300,modal: true});
		},
		// código a ejecutar sin importar si la petición falló o no
		complete : function(jqXHR, status) { }
	};
	$.fn.load_json=function(dir,data,fn,opc_plus)
	{
		var opciones={
			url : dir,
			data : data,
			calback:fn,
			success : function(json)
			{
				var conten;
				try
				{
					conten=this.OpcAjaxExteds.calback(json);
					if(conten!=undefined)
					{
						$(this).html(conten);
					}
					
				}
				catch(Exeption)
				{
					 window.console.log(Exeption);
					this.OpcAjaxExteds.error('','','LARESPUESTA DEL SERVIDOR NO FUE LA ESPERADA');
				}

			}
		}
		
		opciones=$.extend(opciones, opc_plus);
		if(this.length==0)
		{
			return $().AjaxExteds(opciones);
			return this;
		}
		return this.each(function()
		{
			$(this).AjaxExteds(opciones);
		});
	};
	$.fn.load_html=function(dir,data,fn,opc_plus)
	{
		var opciones={
			dataType : 'html',
			url : dir,
			data : data,
			calback:fn,
			success : function(html)
			{
				var conten=this.OpcAjaxExteds.calback(html);
				
				$(this).html(conten);
				if(conten!=undefined)
				{
					
				}	
			}
		}
		opciones=$.extend(opciones, opc_plus);
		if(this.length==0)
		{
			 $().AjaxExteds(opciones);
			return this;
		}
		return this.each(function()
		{
			$(this).AjaxExteds(opciones);
		});
		
		
	}
})(jQuery);
