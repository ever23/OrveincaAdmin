<?PHP

class ORVEINCA_PDF extends ExtendsFpdf 
{
    public function __construct($orientation='P', $unit='mm', $size='A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->SetCreator("ORVEINCA_ADMIN ".__VERSION_ORVEINCA_ADMIN__,true);
    }
    public function Header()
    {
       parent::Header();
        $this->Image(__LOGO_REDONDO_ORVEINCA__,2,2,30);
     
    }
	public function fn_footer($fn_text)
    {
       parent::FnFooter($fn_text);
    }
	public function fn_head($fn_text)
    {
	   parent::FnHeader($fn_text);
    }

}?>