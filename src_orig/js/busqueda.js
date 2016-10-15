// JavaScript Document
var costo=0;
var precio=0;
var cantidad=0;
function costo_medida(medida,tam1 ,tam2,cost)
{
	if(tam1[0].indexOf("*")!=-1)
	{
		for(var i=0;i<tam1.length;i++)
		{
			if(medida==tam1[i])
			{
				return cost[i];
			}
		}

	}else
		if(tam1[0]=='-' && tam2[0]=='-')
		{
			return cost[0];
		}else
			if(tam1[0]!='-' && tam2[0]=='-')
			{
				for(var i=0;i<tam1.length;i++)
				{
					if(Number(medida)==Number(tam1[i]))
					{
						return cost[i];
					}
				}

			}else
			{
				for(var i=0;i<tam1.length;i++)
				{
					if(Number(medida)>=Number(tam1[i]) && Number(medida)<=Number(tam2[i]))
					{
						return cost[i];
					}
				}	

				return "indefinido";
			}
}
function prec_vent(porc,cost,cant)
{
	var msj=(Number(porc)*Number(cost))+Number(cost);
	$('#precio').html(msj+'bs');
	tprecio(msj,cant);

	return msj;
}
function tprecio(prec,cant)
{
	$('#tprecio').html((Number(prec)*Number(cant))+' bs');
}
function cantidad_p()
	{
		
		if($('input[name=cant_coti]').val()!=undefined)
		{
			return $('input[name=cant_coti]').val();
		}else
		if($('input[name=cant_orde]').val()!=undefined)
		{
			return $('input[name=cant_orde]').val();
		}
	}
$(document).ready(function(e) {
    
	 $('select[name=id_tama]').change(function(e) {
		var ind=e.target.selectedIndex;
		var value=e.target.options[ind].value;
		costo=e.target.options[ind].title;
		prec_vent(precio,costo,cantidad);
    });
	$('select[name=precio]').change(function(e) {
        var ind=e.target.selectedIndex;
		var value=e.target.options[ind].value;
		
		precio=Number(value);
		prec_vent(precio,costo,cantidad);
    });
	$('input[name=cant_coti]').keyup(function(e) {
        
		var value=$(this).attr('value');
		cantidad=Number(value);
		prec_vent(precio,costo,cantidad);
		
    });
	$('select[name=exad_colo]').change(function(e) {
		
        var valor=$(this).attr('value');
		if(valor=='otro')
		{
			$('#otro_color').css('display','block');	
		}
		else
		{
			$('#otro_color').css('display','none');
		}
    });
	$('button[name=boton]').click(function(e) {
		var medida=$('#medida').attr('value');
		var medida1=$('input[name=otro_tamano]').attr('value');
	    cantidad=cantidad_p();
		if(medida=='NULL' && medida1==0)
		{
			e.preventDefault();
			error('ALERTA ','PORFAVOR SELECCIONA O INGRESA UN MEDIDA O TALLA');
		}
		if(cantidad<=0  && cantidad=='')
		{
			 e.preventDefault();
			error('ALERTA,','PORFAVOR INGRESE UNA CANTIDAD MAYOR A CERO');
		}
		
    });
	
	$('#otro_pre').click(function(e) {
			$('#precio').html('<input type="text" size="7"  name="prec_venta" value="'+prec_vent(precio,costo,cantidad)+'">');
		$('input[name=prec_venta]').keyup(function(e){
			
			 tprecio(event.target.value,cantidad());
			});
		});
		
	$('#otro_tamano').keyup(function(e) {
        var value=$(this).attr('value');
		costo=costo_medida(value,tam1,tam2,cost);
		prec_vent(precio,costo,cantidad);
    }).focusout(function(e) {
		  var value=$(this).attr('value');
		var cost=costo_medida(value,tam1,tam2,cost);
		//alert(cost);
		
        if(cost=='indefinido' || cost==undefined)
		{
			error('ALERTA ','NO EXISTE UN PRECIO PARA ESTA MEDIDA '+value);
		}
    });
	$('input[name=cant_orde]').keyup(function(e) {
        
		var value=$(this).attr('value');
		cantidad=Number(value);
		prec_vent(precio,costo,cantidad);
		
    });
	
});