// JavaScript Document
var timeoutId;
function menu(modo){
    if(modo==1){
        clearTimeout(timeoutId);	
        $('#Div_menu').fadeIn(100);
        $('#boton_aparece_menu').css("background-color","#fef9ec");
        $('#boton_aparece_menu').css("color","#000");
        $('#boton_aparece_menu').css("border","1px solid #333");
    }else if(modo==2){
        timeoutId = setTimeout(function(){
            $('#Div_menu').fadeOut(100);
            $("#boton_aparece_menu").css("background-color","");
            $("#boton_aparece_menu").css("color","");
            $("#boton_aparece_menu").css("border","");		 
        }, 300);
    }else if(modo==3){
        clearTimeout(timeoutId);	
    }else if(modo==4)
    {
        clearTimeout(0);

    }

} 

function redirec(link)
{
    location.href=link;	
}
