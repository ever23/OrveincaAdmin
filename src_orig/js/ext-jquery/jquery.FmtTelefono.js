/**
jquery.FmtTelefono jquery 1.8.2
Autor Enyerber Franco (enyerverfranco@gmail.com ,enyerverfranco@outlook.com)
*/
(function($){
$.fn.FmtTelefono = function(opc)
{
	var opciones=$.fn.FmtTelefono.OptionDefault;
	$.extend(opciones,opc);
	var funt=function(e)
	{
		e.target.value=$.fn.FmtTelefono.Fmt(e.target.value) 
	};
	return this.each(function()
	{
		var OBJ= $(this);
		OBJ.attr('maxlength',13);
		OBJ.keyup(funt).focusout(funt);
		var div=$('<div></div>');
		OBJ.closest('form').bind('submit',function(e){
			if(OBJ.val().length>0 && !OBJ.ValidTelefono())
			{
				e.preventDefault();
				var remove=function(e) {div.remove();};
				var p=OBJ.position();
				div.css(opciones.cssmsj);
				p.top+=OBJ.innerHeight()+4;
				p.left+=OBJ.innerWidth()/2;
				div.css(p);
				div.html(opciones.msj);
				OBJ.after(div).focus().focusout(remove).keyup(remove);
				
			}
			
			
			});
		
		
	});
}
$.fn.FmtTelefono.OptionDefault=
{
	msj:"EL NUMERO DE TELEFONO ES INVALIDO",
	cssmsj:
		{
			width:'149px',
			display: 'block',
			'border-radius':'5px',
			'background-color':'rgba(255,255,255,1.00)',
			'min-width':'36px',
			position: 'absolute',
			'font-size':'10px',
			'border':'1px ',
			'border-color':'rgba(255,200,96,1.00)',
			'border-style':'ridge',
			'z-index':200
		}
	
}
$.fn.ValidTelefono=function()
{
	var OBJ= $(this);
	var value=$.fn.FmtTelefono.Fmt(OBJ.val());
	var num=str_replace("-","",value);
	if(value.length==13 && $.isNumeric(num))
	{
		return true;
	}
	return false;
}
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
$.fn.FmtTelefono.Fmt=function(num) 
{
	var idet=num;
	if(idet.indexOf('-',0)!=4 && idet.indexOf('.',0)!=9 && idet.indexOf('.',0)!=-1)
	{
		idet=str_replace('-','',idet);
	}
	if(idet.length>4 && idet.indexOf('-')!=4)
	{
		var idet1=idet.substr(0,4)+'-';
		var idet2=idet.substr(4,idet.length);
	}else
	{
		var idet1=idet.substr(0,5);
		var idet2=idet.substr(5,idet.length);
	}
	if(idet.length>9&& idet2.indexOf('-')!=4)
	{
		var idet2=idet2.substr(0,4)+'-'+idet2.substr(4,idet2.length);
	}
	idet=idet1+idet2;
	if( idet.length>13)
	{
		idet=idet.slice(0,13);
	}
	return idet;
}

})(jQuery);
