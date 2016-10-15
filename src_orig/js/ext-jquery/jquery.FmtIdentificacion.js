/**
jquery.FmtIdentificacion jquery 1.8.2
Autor Enyerber Franco (enyerverfranco@gmail.com ,enyerverfranco@outlook.com)
*/
(function($){
	$.fn.FmtIdentificacion = function(CodiTide,opt)
	{
		var option=$.FmtIdentificacion.OptionDefault;
		if(opt!=undefined)
		{
			if(opt.tipos!=undefined)
			option.tipos=$.extend(option.tipos,opt.tipos);
			if(opt.cssmsj!=undefined)
			option.cssmsj=$.extend(option.cssmsj,opt.cssmsj);
		}
		
		var inarr=existobj(option.tipos,CodiTide);
		return this.each(function()
						 {
			var OBJ= $(this);
			
			OBJ.data('FmtIdentificacionOption',option);
			//console.log(inarr);
			if(!inarr)
			{
				var value;
				OBJ.data('CodiTide',$(CodiTide).val());
				
				OBJ.attr('maxlength',OBJ.data('FmtIdentificacionOption').tipos[OBJ.data('CodiTide')].maxlength);
				$(CodiTide).change(function() {
					var value=OBJ.val();
					var codi='Default';
					var opciones=OBJ.data('FmtIdentificacionOption');
					if(existobj(opciones.tipos,$(this).val()))
					{
						codi=$(this).val();

					}
					OBJ.data('CodiTide',codi);
					if(opciones.tipos[codi].fmt==undefined)
					{
						codi='Default';
					}
					try
					{
						OBJ.attr('maxlength',opciones.tipos[codi].maxlength);
						value=opciones.tipos[codi].fmt(OBJ.val());

					}catch(ex)
					{
						long(ex);
					}
					OBJ.val(value);

				});
			}else
			{
				//long(option.tipos[OBJ.data('CodiTide')]);
				OBJ.attr('maxlength',option.tipos[CodiTide].maxlength);
				OBJ.data('CodiTide',CodiTide);
			}

			var fmt=function (e)
			{
				var opciones=OBJ.data('FmtIdentificacionOption');
				var codi;
				if(OBJ.data('CodiTide')!=undefined)
				{
					codi=OBJ.data('CodiTide');
				}else
				{
					codi='Default';
				}
				var value=OBJ.val();
				if(opciones.tipos[codi].fmt==undefined)
				{
					codi='Default';
				}
				try
				{
					value=opciones.tipos[codi].fmt(OBJ.val());

				}catch(ex)
				{
					long(ex);
				}

				OBJ.val(value);
			}	


			OBJ.keyup(fmt).focusout(fmt);
			OBJ.closest('form').bind('submit',function(e){

				if(OBJ.val().length>0 && !OBJ.ValidIdentificacion())
				{
					e.preventDefault();
					OBJ.IdentificacionInvalida();
				}
			}); 

		});

	}
	$.fn.IdentificacionInvalida=function(msj,opc)
	{
		var div=$('<div></div>');
		var OBJ=$(this);
		var opciones=OBJ.data('FmtIdentificacionOption');
		$.extend(opciones.cssmsj,opc);
		var t=OBJ.data('CodiTide');
		var remove=function(e) {div.remove();};
		var p=OBJ.position();
		div.css(opciones.cssmsj);
		if(msj==undefined)
		{
			if(opciones.tipos[t].msj==undefined)
			{
				t='Default';
			}
			try
			{
				div.html(opciones.tipos[t].msj);

			}catch(ex)
			{
				long(ex);
			}
		}else
		{
			div.html(msj);
		}
		p.top+=OBJ.innerHeight()+4;
		p.left+=OBJ.innerWidth()/2;
		div.css(p);
		OBJ.after(div).focus().focusout(remove).keyup(remove);		
	}

	$.fn.ValidIdentificacion = function(coditide)
	{
		var OBJ=$(this);
		var value
		var codi;
		var resul=false;
		if(coditide==undefined)
		{
			if(OBJ.data('CodiTide')!=undefined)
			{
				codi=OBJ.data('CodiTide');
			}else
			{
				codi='Default';
			}
		}else
		{
			codi=coditide;
		}
		var opt=OBJ.data('FmtIdentificacionOption');
		if(opt==undefined)
		{
			opt=$.FmtIdentificacion.OptionDefault;
		}
		try
		{
			if(opt.tipos[codi].fmt==undefined)
			{
				OBJ.val(opt.tipos['Default'].fmt(OBJ.val()));
			}else
			{
				OBJ.val(opt.tipos[codi].fmt(OBJ.val()));
			}

			value=OBJ.val();
			if(opt.tipos[codi].valid==undefined)
			{
				resul=opt.tipos['Default'].valid(value);
			}else
			{
				resul=opt.tipos[codi].valid(value);
			}

		}catch(ex)
		{
			long(ex);	
		}
		return resul;

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
	};
	$.FmtIdentificacion=
		{
		FmtCi:function(ident)
		{
			var idet=ident;
			idet=str_replace('-','',idet);
			if(idet.length<=9 && idet.indexOf('.')!=-1)
			{
				if(idet.indexOf('.')!=-1 && (idet.indexOf('.')!=1 && idet.indexOf('.')!=7))
				{
					idet=str_replace('.','',idet);
					//$.fn.FmtIdentificacion.fmt(idet,Tide);
				}
				if(idet.length>0 && idet.indexOf('.')!=1)
				{
					var idet1=idet.substr(0,1)+'.';
					var idet2=idet.substr(1,idet.length);
				}else
				{
					var idet1=idet.substr(0,2);
					var idet2=idet.substr(2,idet.length);
				}
				if(idet.length>5 && idet2.indexOf('.')!=3)
				{
					var i1=idet2.substr(0,3)+'.';
					var i2=idet2.substr(3,idet2.length);;
					idet2=i1+i2;
				}
				idet=idet1+idet2;  
			}else
			{   
				if(idet.indexOf('.')!=2 && idet.indexOf('.')!=8 && idet.indexOf('.')!=-1)
				{
					idet=str_replace('.','',idet);
				}
				if(idet.length>2 && idet.indexOf('.')!=2)
				{
					var idet1=idet.substr(0,2)+'.';
					var idet2=idet.substr(2,idet.length);
				}else
				{
					var idet1=idet.substr(0,3);
					var idet2=idet.substr(3,idet.length);
				}
				if(idet.length>6 && idet2.indexOf('.')!=3)
				{
					var idet2=idet2.substr(0,3)+'.'+idet2.substr(3,idet2.length);
				}
				idet=idet1+idet2;  
			}
			if( idet.length>10)
			{
				idet=idet.slice(0,10);
			}
			return idet;
		},
		Fmt:function(ident)
		{
			var idet=ident;
			idet=str_replace('.','',idet);
			if(idet.length<=9)
			{
				if( idet.indexOf('-')!=8 && idet.indexOf('-')!=-1)
				{

					idet=str_replace('-','',idet);
				}

				if(idet.length>6 && idet.indexOf('-')!=7)
				{
					var i1=idet.substr(0,7);
					var i2=idet.substr(7,idet.length);;
					idet=i1+'-'+i2;
				}
			}else
			{

				if(idet.indexOf('-')!=8 && idet.indexOf('-')!=-1)
				{
					idet=str_replace('-','',idet);

				}
				if(idet.length>7 && idet.indexOf('-')!=8 )
				{
					idet=idet.substr(0,8)+'-'+idet.substr(8,idet.length);
				}			
			}
			if( idet.length>10)
			{
				idet=idet.slice(0,10);
			}
			return idet;
		},
		ValidDefault:function (value)
		{
			var num=str_replace('-','',value);
			var num=str_replace('.','',num);
			if((value.length==9 || value.length==10) && $.isNumeric(num))
			{
				return true;
			}
			return false;
		}
	};
	$.FmtIdentificacion.OptionDefault={
		tipos:
		{
			'C.I-':
			{
				fmt:$.FmtIdentificacion.FmtCi,
				valid:$.FmtIdentificacion.ValidDefault,
				msj:'LA CEDULA ES INVALIDA',
				maxlength:10
			},
			'C.I':{
				fmt:$.FmtIdentificacion.FmtCi,
				valid:$.FmtIdentificacion.ValidDefault,
				msj:'LA CEDULA ES INVALIDA',
				maxlength:10
			},
			'Default':
			{
				fmt:$.FmtIdentificacion.Fmt,
				valid:$.FmtIdentificacion.ValidDefault,
				msj:'LA IDENTIFICACION ES INVALIDA',
				maxlength:10
			},

		},
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
	}	;

	function long(e)
	{
		window.console.log(e);
	}

	function existobj(obj,name)
	{
		var inarr=0;
		$.each(obj,function(i,v)
			   {
			inarr+=(i==name);
		});
		if(inarr==0)
		{
			return false;
		}
		return true;
	}


})(jQuery);

