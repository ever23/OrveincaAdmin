/**
jquery.info jquery 1.8.2
Autor Enyerber Franco (enyerverfranco@gmail.com ,enyerverfranco@outlook.com)
*/
(function($){
$.fn.help=function(e,msj,option)
{
	var config=
	{
		width:'149px',
		background_color: 'rgba(231,228,228,0.70)',
		border_radius: '5px',
		min_width: '36px',
		left:e.pageX+5,
		top: e.pageY +5//helo
	};
	//hola mundo//jola
	$.extend(config, option);/*jsafdkhasf*/
	return this.each(function()
	{
		$(this).css({
			left:config.left,
			top:config.top ,
			display: 'block',
			width:config.width,
			'border-radius':config.border_radius,
			'background-color':config.background_color,
			'min-width':config.min_width,
			position: 'absolute',
			'padding-top': '11px',
			'padding-right': '11px',
			'padding-bottom':' 11px',
			'padding-left':' 11px'
			});
		$(this).html(msj);
	});
};
$.fn.tics=function(msj,option)
{
	var html='<div class="help" id="help"></div>';
	var OBJ=$(html);
	$(this).mouseleave(function(e)
	{
		OBJ.remove();
	});
	$(this).mousemove(function(e){
		OBJ.appendTo('body');
       OBJ.help(e,msj,option);
    });
}

})(jQuery);

