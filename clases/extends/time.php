<?php
/** time **/ 
class TIME extends DateTimeEx
{
    public $seg;
    public $min;
    public $horas;
    public $dia;
    public $mes;
    public $ano;
	public function __construct($time=NULL,$datetimezone=NULL)
	{
		parent::__construct($time,$datetimezone);
		$this->actual_time();
	}
    public function actual_time()
    {
		parent::actual_time();
        $this->seg= $this->format('s');;
        $this->min=$this->format('i');
        $this->horas=$this->format('H');
        $this->dia=$this->format('d');
        $this->mes=$this->format('m');
        $this->ano=$this->format('Y');	
    }
    function fecha($string="")
    {
        if($string=="actual" || $string=="ACTUAL")
            $this->actual_time();
			
		
        return sprintf("%04d",$this->ano)."-".sprintf("%02d",$this->mes)."-".sprintf("%02d",$this->dia);
        //$fecha=$this->ano."-".$this->mes."-".$this->dia;
      
    }
    function hora($string="")
    {
        if($string=="actual" || $string=="ACTUAL")
        {
            $this->actual_time();
        }
        $hora=sprintf("%02d",$this->horas).":".sprintf("%02d",$this->min).":".sprintf("%02d",$this->seg);
        //$hora=$this->horas.":".$this->min.":".$this->seg;
        return $hora;
    }
    function Set_datetime($datetime_string)
    {
        $this->Set_date(substr($datetime_string,0,10));
        $this->Set_time(substr($datetime_string,11,20));	
    }
    function Set_date($date_string)
    {
        $this->ano=substr($date_string,0,4);
        $this->mes=substr($date_string,5,2);
        $this->dia=substr($date_string,8,2);
		$this->setDate($this->ano,$this->mes,$this->dia);
    }
    function Set_time($time_string)
    {
        $this->hora=substr($time_string,0,2);
        $this->min=substr($time_string,3,2);
        $this->seg=substr($time_string,7,2);
		$this->setTime($this->horas,$this->min,$this->seg);
    }
    function fecha_hora($string="")
    {
        return $this->fecha($string)." ".$this->hora($string);
    }
}
//FIN DE LA CLASE TIME 
?>