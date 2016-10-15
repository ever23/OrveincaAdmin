<?php
class HTML extends SysHTml
{
	protected $session_var=array('user','permisos');
	protected $autenticate=true;
	public function __construct($autenticar=true,$compress=true)
	{
		parent::__construct(false,FALSE);
		$this->SetSrc("{src_orig}",'{root}/src_orig/');
	    $this->autenticate=$autenticar;
		$this->script_error="$(document).ready(function(){ $( '#errores:ui-dialog' ).dialog('destroy' );
	$( '#errores' ).dialog( 'close' );
	$( '#errores' ).dialog({
		height: 350,
		width:300,
		modal: true,
		buttons: {
			'Cerrar': function() 
			{
				$( '#errores' ).dialog( 'close' );
			}
		}
	});});";
		$this->Autenticate();
		
		/*$this->addlink_js([
				"{src_orig}jquery-1.8.0.min.js",
				"{src_orig}jquery-ui.min.js",
				"{src_orig}object_ajax.js",
			    "{src_orig}mis_funciones.js",
				"{src_orig}menu_simple.js",
                "{src_orig}info.js"
				
			]);*/
	}
	public function __destruct()
	{
		parent::__destruct();	
	}
    public function head(){}
    public function foother(){}
	protected function Autenticate()
	{
		
		if(!SESSION::_empty('ERROR'))
		{
			$this->add_error(SESSION::GetVar('ERROR'));
		}
		if($this->autenticate)
		if(!autenticate())
		{
			setcookie('REINICIAR',true,NULL,__DOCUMENT_ROOT__);
			SESSION::Destroy();
			//self::Clear();
			echo "<div align='center'><h2>SESSION EXPIRADA PORFAVOR <br><a href='".__AUTORIZATE_DIRNAME__."index.php'>REINICIE SESSION</a></h2></div>";
			$this->add_error("<h2>SESSION EXPIRADA PORFAVOR <br><a href='".__AUTORIZATE_DIRNAME__."index.php'> REINICIE SESSION</a></h2>");
			$this->__destruct();
			
		}	
	}

	
	public function &prettyPhoto($selector='prettyPhoto')
	{
		$this->addlink_css("{src}prettyPhoto.min.css");
		$this->addlink_js("{src}jquery.prettyPhoto.min.js");
		$this->AddJsScript('$(document).ready(function() { 
			$(".lightbox").append("<span></span>");
			$("a[data-gal^=\''.$selector.'\']").prettyPhoto({animation_speed:\'normal\',theme:\'facebook\',slideshow:false, autoplay_slideshow: false});
		});');
		return $this;
	}
	public function menu($content,$selet)
	{
		$this->AddCssScript('
		#naveg{
	border: 1px solid rgba(181,32,37,1.00);
	height: 31px;
	border-radius: 8px;
}
#menu{
	float:none;
}
		')->AddJsScript('
		$(document).ready(function(){
			$("#naveg").css("width",($("#naveg > ul >li").width()*'.count($content).')+10);

			});
		');
		$buffer="  <div id='menu' >
            <div id='naveg'>
                <ul>";         
		foreach($content as $ind=>$cont)
		{
			$current="";
			if($selet==$ind)
				$current="current";
			$buffer.="<li ><a href='".$cont."' class='$current'>".$ind."</a></li>";	
		}

		$buffer.=" </ul></div>
            </div>";
		return $buffer;	

	}
	public function form_direccion($var_parr,$var_dire,$plus_id='')
	{
		$this->AddJsScript('$(document).ready(function()
		{
			  $("select[name=id_estado]").load_json("../ajax/ajax.php",{"estados_json" : true},
		    function(json) 
			{
				var html="<option value=\'\'>ESTADO</option>";
				for(var i=0;i<json.id_esta.length;i++)
				{
					html+="<option value=\'"+json.id_esta[i]+"\'>"+json.desc_esta[i]+"</option>";
				}
				return html;
		  	});	
		});');
		$buffer= '
        <label for="direccion">DIRECCION</label>
        <select name="id_estado" title="'.$plus_id.'id_muni">
        </select>
        <select name="'.$plus_id.'id_muni" title="'.$var_parr.'" >
          <option value="">--------</option>
        </select>
        <select name="'.$var_parr.'" id="id_parr">
          <option value="">--------</option>
        </select>
		<BR>
        <input type="text" name="'.$var_dire.'"  placeholder="CALLE NRO DE LOCAL O CASA"  class="main_input"/>';
		return $buffer;	
	}
	public function form_telef($plus_id='',$tel_bd='',$ind=0)
	{
		$varjs=$ind+1;
		$this->AddJsScript(" $(document).ready(function(e) {
			 $('#".$plus_id."newtelf').click(function()
			{
				".$plus_id."n_tel=add_tel(".$plus_id."n_tel,'".$plus_id."');	
			});
	$('.del_tel').tics('ELIMINA CAMPO PARA EL TELEFONO');
	$('.del_tel_bd').tics('ELIMINA EL TELEFONO DEL SISTEMA');
        });");

		$buffer="";
		$buffer.='
        <div class="new newtelf" id="'.$plus_id.'newtelf"></div>
        <label for="telefono" form="frmDatos">TELEFONO</label>
          <div id="'.$plus_id.'telefonos">'.$tel_bd;

		$buffer.="
        <div id='".$plus_id."telefonos".$ind."'>
          <input form='frmDatos' type='tel' name='".$plus_id."telefono[".$ind."]'  placeholder='####### '  class='main_input'  />
        </div>

        <div id='".$plus_id."telefonos".$varjs."'> </div>
       </div>";
		$this->AddJsScript(" var ".$plus_id."n_tel=".$varjs.";");
		return $buffer;	
	}
	public function form_cuet_banc($plus_id='',$bac_bd='',$ind=0)
	{

		$buffer='';
		$buffer.='
        <label for="direccion">CUENTA BANCARIA</label>
        <div class="new" id="'.$plus_id.'newbanco"></div>
        <div id="bancos">
          '.$bac_bd;
		$buffer.='<div id="'.$plus_id.'banco'.$ind.'">
          <select name="'.$plus_id.'banco['.$ind.']" id="'.$plus_id.'banco_select'.$ind.'">

          </select>
           <select name="'.$plus_id.'t_cuenta['.$ind.']" >
             <option value="corriente">tipo de cuenta</option>
           <option value="corriente">corriente</option>
           <option value="nomina">nomina</option>
           <option value="ahorro">ahorro</option>
           </select>
          <BR />

          <input type="text" name="'.$plus_id.'nro_cuenta['.$ind.']" onKeyUp="fmt_banco(event)"  placeholder="NRO CUENTA"  class="main_input"/>
          <div class="elimina del_banc" onClick="javascript:del_banco('.$ind.',\''.$plus_id.'\')" id="delbanco"></div>

        </div>
        <div id="'.$plus_id.'banco'.($ind+1).'"> </div>
        </div>
       ';
		$this->AddJsScript(' var '.$plus_id.'n_banco='.($ind+1).';
	   $(document).ready(function(e) {
		    $("#'.$plus_id.'banco_select'.$ind.'").AjaxExteds(ajax_BANC);
           $(".del_banc").tics("ELIMINA EL CAMPO PARA LA CUENTA BANCARIA");	
		    $("#'.$plus_id.'newbanco").click(function()
		{
			'.$plus_id.'n_banco=add_banco('.$plus_id.'n_banco,"");	

		});

        });');

		return $buffer;	
	}

}
?>