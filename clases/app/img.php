<?php

class IMG
{
    var $img;
    var $w;
    var $h;
    var $tipo;
    var $fondo;
    var $fuente;
    var $colores;
    var $img_import;
    var $mask_color;
    var $hader;
    function __construct($ancho,$alto,$tipo)
    {
        $this->w=$ancho;
        $this->h=$alto;
        $this->img= imagecreatetruecolor($ancho,$alto);
        $this->fuente=NULL;
        $this->colores=array();
        $this->img_import=array();
        $this->fuente=array();

        $this->hader=$tipo;
        if ($tipo=="image/x-png" || $tipo=="image/png")
        {

            $this->tipo='png';
        }
        if ($tipo=="image/pjpeg" || $tipo=="image/jpeg")
        {

            $this->tipo='jpeg';
        }
        if ($tipo=="image/gif" || $tipo=="image/gif")
        {
            $this->tipo='gif';
        }



        $this->fondo=imagecolorallocate ($this->img, 255, 255, 255);
        $this->mask_color=imagecolorallocate ($this->img, 255, 0, 255);
        //$this->rectangulo_ex(0,0,$this->w,$this->h,imagecolorallocate ($this->img, 255, 0, 255));
        imagefill($this->img, 0, 0, $this->fondo);
        //imagecolortransparent($this->img ,$this->fondo);
    }
    function Output($name="",$ouput="I")
    {

        $image="image";
        $image.=$this->tipo;
        switch($ouput)
        {
            case "I":
            {
                header("Content-type: ".$this->hader);
                header('Content-Disposition: inline; filename="'.$name.'"');
                header('Pragma: public');
                $image($this->img);

            } break;
            case "F":
            {
                $image($this->img,$name.".".$this->tipo);

            }break;	
        }

    }
    function __destruct()
    {

        imagedestroy($this->img);
    }

    function color_mask($color)
    {
        $this->mask_color=$this->colores[$color];
        imagecolortransparent($this->img ,$this->mask_color);
    }
    function load_ttf($name,$file_ttf)
    {
        if($file_ttf!='')
        {
            $this->fuente+=array( $name=>$file_ttf );
            return 0;
        }
        return 1;

    }

    function create_color($name,$R,$G,$B,$A=0)
    {
        $this->colores+=array( $name=>imagecolorallocatealpha($this->img,$R,$G,$B,(int)($A)));
        return $this->colores[$name];
    }
    function rgba($R,$G,$B,$A=0)
    {
        return imagecolorallocatealpha($this->img,$R,$G,$B,(int)($A));
    }
    function fill($x, $y, $rgb_color)
    {
        imagefill($this->img, $x, $y, $this->colores[$rgb_color]);
    }
    function linea($x,$y,$w,$h,$rgb_color)
    {
        imageline($this->img,$x,$y,$w,$h,$this->colores[$rgb_color]);
    }
    function rectangulo($x,$y,$w,$h,$rgb_color)
    {
        imagerectangle ($this->img, $x, $y, $w, $h, $this->colores[$rgb_color]);
    }
    function rectangulo_ex($x,$y,$w,$h,$rgb_color)
    {
        imagefilledrectangle($this->img,  $x, $y, $w, $h, $this->colores[$rgb_color]);
    }

    function text_print($tam,$x,$y,$cadena,$rgb_color)
    {
        imagestring ($this->img, $tam, $x, $y,$cadena,$this->colores[$rgb_color]);
    }
    function text_print_ttf( $tam, $angulo, $x, $y, $rgb_color,$fuente, $cadena)
    {
        imagettftext($this->img, $tam, $angulo, $x, $y, $this->colores[$rgb_color],$this->fuente[$fuente], $cadena);
    }
    function print_img($img_class,$x,$y,$x_img,$y_img,$ancho_img,$alto_img)
    {
        imagecopyresampled($this->img,$img_class->img,$x,$y,$x_img,$y_img,$ancho_img, $alto_img,$img_class->w,$img_class->h);
    }

    function importar_img($name,$filename,$tip='')
    {
        for($i=strlen($filename)-1;$i>0;$i--)
        {
            if (substr($filename,$i,1)==".")
            {
                $tipo=substr($filename,$i+1);
                if($tipo!='png' && $tipo!='gif' && $tipo!='jpeg')
                    $tipo=$tip; 
                break;
            }
        }

        $this->img_import+=array( $name=>array( "img"=>$filename,"tipo"=>$tipo) );
    }
    function print_img_import($name_img,$x,$y,$x_img,$y_img,$ancho_img,$alto_img,$color_trasparen=NULL)
    {
        $tipo=$this->img_import[$name_img]['tipo'];

        $original=$this->img_import[$name_img]['img'];;

        $imagecreatefrom="imagecreatefrom";

        $imagecreatefrom.=$tipo;

        $importada=$imagecreatefrom($original);

        if($tipo=="png" OR $tipo=="gif")
        {
            imagefill($this->img,0,0,$this->fondo);
        }

        $tamano=getimagesize($original);

        $orig_Ancho = $tamano[0];

        $orig_Alto =$tamano[1];
        if($color_trasparen!=NULL)
        {
            imagecolortransparent($importada,$this->colores[$color_trasparen]);
        }

        imagecopyresampled($this->img,$importada,$x,$y,$x_img,$y_img,$ancho_img, $alto_img,$orig_Ancho,$orig_Alto);
        imagedestroy($importada);

    }
    function print_img_import_alpha($name_img,$x,$y,$ancho_img,$alto_img,$alpha)
    {
        $tipo=$this->img_import[$name_img]['tipo'];

        $original=$this->img_import[$name_img]['img'];;

        $imagecreatefrom="imagecreatefrom";
        if($tipo=='php')
            $tipo='png';

        $imagecreatefrom.=$tipo;

        $importada=$imagecreatefrom($original);

        if($tipo=="png" OR $tipo=="gif")
        {
            imagefill($this->img,0,0,$this->fondo);
        }

        $tamano=getimagesize($original);

        $orig_Ancho = $tamano[0];

        $orig_Alto =$tamano[1];
        $im_truco=imagecreatetruecolor($orig_Ancho, $orig_Alto);
        $fondo1=imagecolorallocate($im_truco,255,255,255);
        imagefill($im_truco,0,0,$fondo1);
        imagecolortransparent ($im_truco,$fondo1);
        imagecopy($im_truco, $importada, 0, 0, 0, 0, $orig_Ancho, $orig_Alto);
        imagecopymerge( $this->img, $im_truco, $x , $y ,$ancho_img,$alto_img ,$orig_Ancho, $orig_Alto ,$alpha );
        imagedestroy($importada);
    }

}

/*
$imagen=new IMG(900,1140,"image/png");
$imagen->create_color('negro',255, 255, 255);
$imagen->create_color('amarillo',255, 255,0);
$imagen->linea(0,0,200,200,"negro");

$imagen->create_color('alpha',255, 255,0,100);//crear solo antes de utilizar
$imagen->rectangulo_ex(150, 150, 300, 300, 'alpha');
$imagen->load_ttf('font','airstrike.ttf');
$imagen->text_print_ttf(40,20,300,300,'negro','font'," esta es la cadena");
$imagen->importar_img('2013','../img/2013.png');
$imagen->print_img_import('2013',300,300,0,0,100,100);
$imagen->print_img_import_alpha('2013',25,25,0,0,80);
*/
?>