// JavaScript Document
$(document).ready(function(e)
{
    var html_comp= {
		fact_comp:'<td width="80" scope="col">N°_ORDEN</td>\
	<td width="98" scope="col">N°_FACTURA</td>\<td width="360" scope="col">PROVEEDOR</td>\
	<td width="108" scope="col">ESTADO</td>\<td width="75">MONTO</td>\
	<td width="75">PAGADO</td>\<td width="116" scope="col">FECHA</td>\
	<td width="80" scope="col"></td>',
	orde_comp:'<td width="78" scope="col">N°_ORDEN</td>\
	<td width="364" scope="col">PROVEEDOR</td>\<td width="100" scope="col">ESTADO</td>\
	<td width="89">MONTO</td>\<td width="119" scope="col">FECHA</td>\
	<td width="70" scope="col"></td>',
	selct_fact:'<option value="all" selected></option>\
	<option value="nume_orde">NUMERO DE ORDEN DE COMPRA</option>\<option value="nume_fact">NUMERO DE FACTURA DE COMPRA</option>\
	<option value="prov">PROVEEDOR</option>\<option value="fech">FECHA</option>\
	<option value="estado">ESTADO DE LOS PRODUCTOS</option>',
	selct_orde:'<option value="all" selected></option>\
	<option value="nume_orde">NUMERO DE ORDEN DE COMPRA</option>\<option value="estado">ESTADO</option>\
	<option value="prov">PROVEEDOR</option>\<option value="fech">FECHA</option>',
	estado_fact:'<option value=""></option>\
	<option value="=0">RECIBIDO</option>\<option value="!=0">PENDIENTE</option>',
	estado_orde:'<option value=""></option>\
	<option value="P">PENDIENTE</option>\<option value="F">FACTURADO</option>\
	<option value="C">CANCELADO</option>'
	};
    var selected='FACTURAS_COMPRAS';
    $("#thead-fact>tr").html(html_comp.fact_comp);
    $('#factura_compras').load_html('ajax_compras.php', {FACTURAS_COMPRAS:true});
    $('select[name=selct]').change(function(e)
    {
        var value=$(this).attr('value');
        switch(value)
        {
        case 'fech':
            $('input[name=text]').fadeOut(function()
            {
                $('input[name=text_date]').fadeIn();
            });
            $('select[name=estado]').fadeOut(function()
            {
                $('input[name=text_date]').fadeIn();
            });
            break;
        case 'estado':
            $('input[name=text]').fadeOut(function()
            {
                $('select[name=estado]').fadeIn();
            });
            $('input[name=text_date]').fadeOut(function()
            {
                $('select[name=estado]').fadeIn();
            });
            break;
        default:
            $('input[name=text_date]').fadeOut(function()
            {
                $('input[name=text]').fadeIn();
                $('select[name=estado]').fadeOut();
            });
            break;
        }
    });
    $('input[name=text]').keyup(function(e)
    {
        var sele=$('select[name=selct]').attr('value');
        var text=$(this).attr('value');
        if(selected=='FACTURAS_COMPRAS')
        {
            $('#factura_compras').load_html('ajax_compras.php', {FACTURAS_COMPRAS:true,'opcion':sele,'texto':text});
        }
        else if(selected=='ORDE_COMP')
        {
            $('#factura_compras').load_html('ajax_compras.php', {ORDE_COMP:true,'opcion':sele,'texto':text});
        }
    });
    $('input[name=text_date]').change(function(e)
    {
        var sele;
        var text=$(this).attr('value');
        if(selected=='FACTURAS_COMPRAS')
        {
            sele='fech_fact';
            $('#factura_compras').load_html('ajax_compras.php', {FACTURAS_COMPRAS:true,'opcion':sele,'texto':text});
        }
        else
        {
            sele='fech_orde';
            $('#factura_compras').load_html('ajax_compras.php', {ORDE_COMP:true,opcion:sele,texto:text});
        }
    });
    $('select[name=estado]').change(function(e)
    {
        var sele='estado';
        var text=$(this).attr('value');
        if(selected=='FACTURAS_COMPRAS')
        {
            $('#factura_compras').load_html('ajax_compras.php', {FACTURAS_COMPRAS:true,'opcion':sele,'texto':text});
        }
        else
        {
            $('#factura_compras').load_html('ajax_compras.php', {ORDE_COMP:true,opcion:sele,texto:text});
        }
    });
    $('#buscar').click(function(e)
    {
        if($('#form_searh').css('display')!='block')
        {
            $('#form_searh').fadeIn();
        }
        else
        {
            $('#form_searh').fadeOut();
        }
    });
    $("#naveg>ul>li>a").click(function(e)
    {
        e.preventDefault();
        var value=$(this).attr('href');
        if(value=='fact_comp')
        {
            $('select[name=estado]').html(html_comp.estado_fact);
            $('select[name=selct]').html(html_comp.selct_fact);
            $("#naveg>ul>li>a[href=orde_comp]").removeClass('current');
            $("#naveg>ul>li>a[href=fact_comp]").addClass('current');
            $('#factura_compras').load_html('ajax_compras.php', {FACTURAS_COMPRAS:true},function(html)
            {
                $("#thead-fact>tr").html(html_comp.fact_comp);
                return html;
            });
            selected='FACTURAS_COMPRAS';
        }
        else
        {
            $('select[name=estado]').html(html_comp.estado_orde);
            $('select[name=selct]').html(html_comp.selct_orde);
            $("#naveg>ul>li>a[href=fact_comp]").removeClass('current');
            $("#naveg>ul>li>a[href=orde_comp]").addClass('current');
            $('#factura_compras').load_html('ajax_compras.php', {'ORDE_COMP':true,s_fact:true},function(html)
            {
                $("#thead-fact>tr").html(html_comp.orde_comp);
                return html;
            });
            selected='ORDE_COMP';
        }
    });
});
