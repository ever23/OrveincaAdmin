<?php
// REDIRECCIONAMIENTO WEB
function redirec($a)
{
    header("Location: $a ");	
    exit;
}
function jredirec($a)
{
    echo" <script language='javascript' >window.open('$a','_self','');</script>";		
}
function header_json()
{
    header("Content-type:  application/json");
    header('Content-Disposition: inline; filename=""');
    header('Pragma: public');
}
function lnstring($titulo,$nchar,$char)
{
    $ret=$tename=trim($titulo);
    $n=$nchar;
    if(strlen($tename)<$nchar)
    {
        return $titulo;
    }
    for($i=0;$i<strlen($tename);$i++)
    {
        $aux_ca=substr($tename,0,$i);
        $aux_ca2=substr($tename,$i,strlen($tename));
        if(strlen($aux_ca)>$n )
        {
            if(substr($tename,$i,1)!=" ")
            {
                $a=explode(" ",$aux_ca);
                $ult=$a[count($a)-1];
                $a[count($a)-1]='';
                $aux_ca2=" ".$ult.$aux_ca2;
                $aux_ca=trim(implode(' ',$a));
            }
            if(strlen($aux_ca2)>$n)
                $aux_ca2=lnstring(substr($aux_ca2,1,strlen($aux_ca2)),$n,$char);	
            $ret=$aux_ca.$char.trim($aux_ca2);
            break;
        }
    }	
    $titulo=$ret;
    return $titulo;
}
function fmt_string($string)
{
    $cadena1=strtoupper($string);
    $cadena2=trim($cadena1);
    return $cadena2;
}

function fmt_num($number)
{
	$number=number_format($number ,2 , "." ,"");
    return number_format($number ,2 , "." ,",");
}

function autenticate()
{
	if(SESSION::_empty('user')==false && SESSION::_empty('permisos')==false)
	{
		return false;
	}else
{
	return true;;
}
	
}

?>