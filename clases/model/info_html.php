<?php

class INFO_HTML extends HTML
{
	
	public function __construct($compress=true)
	{
		parent:: __construct(true,$compress);
		parent::SetLayout('info');
		$this->AddCssScript(".container{
	 width:580px; padding:0px; margin:0px;
	 background:rgb(255,255,255);
}
body{ background:rgb(255,255,255);}
a{text-decoration:none; color:rgba(0,0,0,1);}
a{text-decoration:underline ; color:rgba(0,0,0,1);}
.produc{  margin:3px;  height:auto;  min-height:360px; width:100%;}
.img{ float:left;}
.selec{ width:100%; float:center;}
.info
{
	 float:left;
	 max-width:250;

}
.cargando{
	left: 292px;
	position: absolute;

}");


	}
	public function __destruct()
	{
		parent::__destruct();

	}
	public function uipanel($select,$num)
	{
		$script=$style='';

		for($i=1;$i<=$num;$i++)
		{
			$style.=$select.'-'.$i.",";
			$script.="

	$('a[href=".$select.'-'.$i."]').click(function(e) {
     $('".$select.'-'.$i."').css('height',$('".$select.'-'.$i." > div').height()+5);
    });";
		}
		$style= substr($style,0,strlen($style)-1);
		$this->AddCssScript("".$style." {
			height:auto;
			min-height: 360px;
			margin-top: 0px;
			padding-top: 0px;
			padding-right: 0px;
			padding-bottom: 0px;
			padding-left: 0px;
			width: 614px;
		}
		".$select." { width: 625px;   }")
			->AddJsScript(" $(document).ready(function(e) {   $( '$select' ).tabs(); 
		$('".$select."-1').css('height',$('".$select."-1 > div').height()+5);
		$script
		}); ");


	}



}