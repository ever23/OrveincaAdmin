<?php
/** time **/ 
class DateTimeEx extends DateTime
{
    public $meses=array(1=>'enero','febrero','marzo','abril','mayo','junio','julio','agosto','sectiembre','octubre','nobiembre','diciembre');
   
	public function DateEdad($ano=NULL,$mes=0,$dia=0)
	{
		if(is_null($ano))
		{
			$ano=$this->format('Y');
			$mes=$this->format('m');
			$dia=$this->format('d');
		}
		
		return (date('m')-$mes<0)?((date('Y')-$ano)-1):((date('d')-$dia<0)?(date('Y')-$ano)-1:date('Y')-$ano);//$ano $mes $dia 
	}
	
    public function actual_time()
    {
		
		 $localtime = localtime();
        $time = localtime(time(), true);
		$this->setDate(date('Y'),date('m'),$time['tm_mday']);
		$this->setTime($time['tm_hour'],$time['tm_min'],$time['tm_sec']);
      
    }
    function mes_cadena($mes=NULL)
    {
        return $this->meses[((int)(!is_null($mes)?$mes:$this->format('m')))];
    }
}