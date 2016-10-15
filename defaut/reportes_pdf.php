<?php
require_once("../clases/config_orveinca.php");

$header = array(
    'desc'=>array("DESCRIPCION",100),
    'bs'=>array("BSF",30),
);

$mysql= new PRODUCTOS();
OrveincaExeption::DieExeptionS();
$titulo;
$time= new TIME();
if(empty($_GET['reporte']))
{
    $reporte='mes';
}else
{
    $reporte=$_GET['reporte'];
}
if(empty($_GET['value']))
{

    $value=$time->fecha();
    $time->actual_time();
}else
{
    $value=$_GET['value'];
}

$time->Set_date($value.'-0');
$MES= $time->mes;
switch($reporte)
{
    case 'mes':
    $titulo="DE ".$time->mes_cadena()." ".$time->ano;
    $where_entr=" fech_fact>='".$time->ano."-".$time->mes."-1' and fech_fact<'".$time->ano."-".($time->mes+1)."-1'";
    $where_salida=" fech_nent>='".$time->ano."-".$time->mes."-1' and fech_nent<'".$time->ano."-".($time->mes+1)."-1'";
    $inve1=" fech_fact<'".$time->ano."-".($time->mes)."-1'";
    $inve2=" fech_nent<'".$time->ano."-".($time->mes)."-1'";
    $inve3="fech_fact<'".$time->ano."-".($time->mes+1)."-1'";
    $gasto="fech_pago>='".$time->ano."-".$time->mes."-1' and fech_pago<'".$time->ano."-".($time->mes+1)."-1'";
    $nomina="fech_nomi>='".$time->ano."-".($MES)."-1' and fech_nomi<'".$time->ano."-".($MES+1)."-1'";
    break;	
    case 'ano':
    $titulo="DEL ANO ".$time->ano;
    $where_entr=" fech_fact>='".$time->ano."-1-1' and fech_fact<'".($time->ano+1)."-1-1'";
    $where_salida=" fech_nent>='".$time->ano."-1-1' and fech_nent<'".($time->ano+1)."-1-1'";
    $inve1=" fech_fact<'".$time->ano."-1-1'";
    $inve2=" fech_nent<'".$time->ano."-1-1'";
    $inve3="fech_fact<'".($time->ano+1)."-1-1'";
    $gasto=" fech_pago>='".$value."-01-01' and fech_pago<'".($time->ano+1)."-01-01'";
    break;	


}
/******** COMPRAS *******/
if(!$mysql->consulta(PRODUCTOS::REPORTE_ENTRADA,$where_entr." GROUP by faco_prod.id_prod,faco_prod.exad_colo,faco_prod.id_tama "))
{
    echo "ERRO AL CONSULTAR LA ENTRADA ".$mysql->error;;
    exit();
}
$reporte= new TablePdfIterator(7,array(201,199,190),'arial',12);
$reporte->SetRowHeah($header);
$i=0;
$total_prod_entr=0;
$total_bs_entr=0;
foreach($mysql->result_array() as $i=>$campo)
{
    $total_bs_entr+=$campo['total_bs'];
    $total_prod_entr+=$campo['cantidad'];


}
$reporte->AddRow(5,'arial',10,false,array(255,255,255))
    ->AddCell('desc',"compras")
    ->AddCell('bs',fmt_num($total_bs_entr));
/******** VENTAS *****/
if(!$mysql->consulta(PRODUCTOS::REPORTE_SALIDA,$where_salida." GROUP by `nent_prod`.id_prod,`nent_prod`.exad_colo,`nent_prod`.id_tama "))
{
    echo "ERRO AL CONSULTAR LA SALIDA ".$mysql->error;
    exit();
}
$i=0;
$total_prod_sali=0;
$total_bs_sali=0;
foreach($mysql->result_array() as $i=>$campo)
{
    $costo='';



    $total_bs_sali+=$campo['total_bs'];
    $total_prod_sali+=$campo['cantidad'];


}
$reporte->AddRow()
    ->AddCell('desc',"ventas")
    ->AddCell('bs',fmt_num($total_bs_sali));


/********** inventario *****/
if(!$inventario=$mysql->consulta(PRODUCTOS::INVENTARIO1,'1 GROUP by id_prod,exad_colo,id_tama ',' id_prod'))
{
    echo "ERRO AL CONSULTAR LA TABLA CLIENTES ";
    exit();
}

$i=0;
$total_prod=0;
$total_bs=0;
for($i=0;$campo=$inventario->fetch_array();$i++)
{
    $mysql->consulta(PRODUCTOS::INVENTARIO1,"id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")." and ".$inve1.' GROUP by id_prod,exad_colo,id_tama ',' id_prod');

    $resul_inve1=$mysql->result();
    $mysql->consulta("
		SELECT SUM(nent_prod.cant_nent) as cant_vend
FROM nent_prod
LEFT JOIN nota_entrg using(nume_nent)
WHERE id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]' ":" IS NULL ")." and ".$inve2." 
GROUP by id_prod,exad_colo,id_tama 
		");

    $resul_inve2=$mysql->result();

    $mysql->consulta(PRODUCTOS::INVENTARIO1,
                     "id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]'
					  and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")." 
					 and ".$where_entr.'  GROUP by id_prod,exad_colo,id_tama ',' id_prod');

    $entr=$mysql->result();

    $mysql->consulta("
		SELECT SUM(nent_prod.cant_nent) as cant_vend
FROM nent_prod
LEFT JOIN nota_entrg using(nume_nent)
WHERE id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")." and ".$where_salida."
GROUP by id_prod,exad_colo,id_tama 
		");

    $sali=$mysql->result();
    $exist_ant=$resul_inve1['cant_reci']-$resul_inve2['cant_vend'];
    $exist_act=$entr['cant_reci']-($sali['cant_vend']-$exist_ant);


    $existencia=$exist_act;
    if($existencia!=0)
    {
        $costo='';
        $const_comp=0;
        $aux_exist=$existencia;
        $mysql->sql='';
        $cost_result=$mysql->consulta("SELECT * FROM faco_prod
		left join fact_comp using(nume_orde)
		 where id_prod='$campo[id_prod]' and  id_tama= '$campo[id_tama]' and exad_colo ".($campo['exad']!=''?"='$campo[exad]'":" IS NULL ")."  and ".$inve3."  order by  faco_prod.nume_orde DESC ");
        //echo $mysql->sql;
        while($cost=$mysql->result())
        {
            if($aux_exist>$cost['cant_reci'])
            {
                $aux_exist-=$cost['cant_reci'];
                $const_comp+=$cost['cant_reci']*$cost['cost_comp'];
                // echo $cost['cant_reci'].'*'.$cost['cost_comp'].'='.$cost['cant_reci']*$cost['cost_comp'].'<br>';
            }else
            {
                $const_comp+=$aux_exist*$cost['cost_comp'];
                //  echo $aux_exist.'*'.$cost['cost_comp'].'='.$aux_exist*$cost['cost_comp'].'<br>';
                break;
            }
        }
        $mysql->result->data_seek(0);
        $cost=$mysql->result();
        $mysql->result->free();
        $tbs=$const_comp;

        $total_bs+=$tbs;
        $total_prod+=$existencia;


    }
}
$reporte->AddRow()
    ->AddCell('desc',"inventario")
    ->AddCell('bs',fmt_num($total_bs));


/***** GATOS ******/
$gastos=array();
$TOTAL=0;
$result=$mysql->consulta("SELECT * FROM tipogasto");
$gastos= new TablePdfIterator(7,array(201,199,190),'arial',12);
$gastos->SetRowHeah($header);

while($G=$result->fetch_array())
{
    $consulta=$mysql->AddCollConsulta(PRODUCTOS::GATOS,["sum(bsf_pago) as gasto"]);
    $mysql->consulta($consulta,$gasto." and codi_tpga='".$G['codi_tpga']."'");
    $campo=$mysql->result();
    $TOTAL+=$campo['gasto'];
    $gastos->AddRow(5,'arial',10,false,array(255,255,255))
        ->AddCell('desc',$G['desc_tpga'])
        ->AddCell('bs',fmt_num($campo['gasto']));	
}
$gastos->AddRow()
    ->AddCell('desc',"TOTAL")
    ->AddCell('bs',fmt_num($TOTAL));
/******** EMPLEADOS ********/

$Gnomina=array();
!$mysql->consulta("SELECT nomina.*,empleados.* FROM `nomina` 
LEFT JOIN empleados USING(ci_empl) where ".$nomina);
$total_cestatike=0;
$total_salario=0;
$total_l_p_h=0;
$total_s_o_s=0;
$total_s_p_f=0;
while($campo=$mysql->result())
{
    $sueldo_m=(((float)$campo['suel_diar'])*((float)30.0));
    $tcestatiek=((float)$campo['cest_tike'])*((float)$campo['dias_labo']);
    $tasig=$tcestatiek+$sueldo_m;
    $total_dedu=$campo['l_p_h']+$campo['s_o_s']+$campo['s_p_f'];
    $total=$tasig-$total_dedu;
    $total_cestatike+=$tcestatiek;
    $total_salario+=$sueldo_m;
    $total_l_p_h+=$campo['l_p_h'];
    $total_s_o_s+=$campo['s_o_s'];
    $total_s_p_f+=$campo['s_p_f'];


}
$GNOMINA= new TablePdfIterator(7,array(201,199,190),'arial',12);
$GNOMINA->SetRowHeah($header);
$GNOMINA->AddRow(5,'arial',10,false,array(255,255,255))
    ->AddCell('desc',"TOTAL EN SUELDOS")
    ->AddCell('bs',fmt_num($total_salario));
$GNOMINA->AddRow()
    ->AddCell('desc',"TOTAL EN CESTATIKE")
    ->AddCell('bs',fmt_num($total_cestatike));
$GNOMINA->AddRow()
    ->AddCell('desc',"TOTAL L.P.H")
    ->AddCell('bs',fmt_num($total_l_p_h));
$GNOMINA->AddRow()
    ->AddCell('desc',"TOTAL S.O.S")
    ->AddCell('bs',fmt_num($total_s_o_s));
$GNOMINA->AddRow()
    ->AddCell('desc',"TOTAL S.O.S")
    ->AddCell('bs',fmt_num($total_s_o_s));
$GNOMINA->AddRow()
    ->AddCell('desc',"TOTAL  S.P.F")
    ->AddCell('bs',fmt_num($total_s_p_f));


$pdf = new ORVEINCA_PDF('P','mm','letter');
$pdf->AliasNbPages();

$pdf->AddFont('airstrike','','airstrike.php');
$pdf->titulo(" REPORTE ".$titulo,100,'airstrike','',20);

$pdf->AddPage();
$marg=$pdf->lMargin;
$pdf->SetLeftMargin(40);
$pdf->Table($reporte);
$pdf->SetLeftMargin($marg);
$pdf->Ln();
$pdf->SetFont('airstrike','',20);
$pdf->MultiCell($pdf->w,6,"GASTOS ".$titulo,0,'C');
$pdf->Ln();
$pdf->SetLeftMargin(40);
$pdf->Table($gastos);
$pdf->SetLeftMargin($marg);
$pdf->Ln();
$pdf->SetFont('airstrike','',20);
$pdf->MultiCell($pdf->w,6,"NOMINA ".$titulo,0,'C');
$pdf->Ln();
$pdf->SetLeftMargin(40);
$pdf->Table($GNOMINA);
$pdf->Output('REPORTE '.$titulo.'.pdf','I');


?>