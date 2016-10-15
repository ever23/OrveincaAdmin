<?php
require_once("../clases/config_orveinca.php");
$json= new JsonBuffer(true);
$json->Set('error',FALSE);
$database= new PRODUCTOS();
if(!empty($_POST['cancela_pedido']) && !empty($_POST['nume_pedi']) )
{
	$database->autocommit(false);
	$database->consulta("SELECT * FROM pedi_prod WHERE (cant_entr>0 and cant_entr is NOT NULL ) and nume_pedi='".$_POST['nume_pedi']."'");
	if($database->result->num_rows==0)
	{
		if($database->consulta("UPDATE pedi_prod SET cant_entr=NULL WHERE nume_pedi='".$_POST['nume_pedi']."'"))
		{
			if(!$database->consulta("UPDATE pedidos SET esta_pedi='C' WHERE nume_pedi='".$_POST['nume_pedi']."'"))
			{
				$json->Set('error',"ERROR INESPERADO AL CANCELAR  PEDIDO ");
			
			}
		}else
		{
			$json->Set('error',"ERROR INESPERADO AL CANCELAR EL PRODUCTO DEL PEDIDO ");
			
		}
	}else
	{
		$database->rollback();
		$json->Set('error',"<H3>ERROR NO PUEDE CANCELAR UN PEDIDO COMPLETO SI SE A ENTREGADO UNO O MAS  PRODUCTOS DEL MISMO ANTES<H3>");
		
	}
	if(!$database->error())
	{
		$database->commit();	
	}else
	{
		$database->rollback();
	}
}

if(!empty($_POST['cancela_pedi_prod']) && !empty($_POST['id_pepr']) )
{
	$database->autocommit(false);
	if($database->consulta("UPDATE pedi_prod SET cant_entr=NULL WHERE id_pepr='".$_POST['id_pepr']."'"))
	{
			$database->consulta("SELECT * FROM pedi_prod WHERE  id_pepr='".$_POST['id_pepr']."'");
		$pedi=$database->result();
		$database->consulta("SELECT * FROM pedi_prod WHERE (cant_entr is NOT NULL ) and nume_pedi='".$pedi['nume_pedi']."'");
		if($database->result->num_rows==0)
		{
			if(!$database->consulta("UPDATE pedidos SET esta_pedi='C' WHERE nume_pedi='".$pedi['nume_pedi']."'"))
			{
				$json->Set('error',"ERROR INESPERADO AL CANCELAR  PEDIDO");
				
			}
		}else
		{
			$database->consulta("SELECT * FROM pedi_prod where cant_pedi!=cant_entr  and nume_pedi='".$pedi['nume_pedi']."'");
			if($database->result->num_rows==0)
			{
				if(!$database->consulta("UPDATE pedidos SET esta_pedi='E' WHERE nume_pedi='".$pedi['nume_pedi']."'"))
				{
					$json->Set('error',"ERROR INESPERADO AL entregar  PEDIDO");
					
				}
			}
		}
	}else
	{
		new OrveincaExeption("ERROR INESPERADO AL CANCELAR EL PRODUCTO DEL PEDIDO");
		$json->Set('error',OrveincaExeption::GetExeptionS(TRUE));
		
	}
	if(!$database->error())
	{
		$database->commit();	
	}else
	{
		$database->rollback();
	}

}
if(!empty($_POST['iguala_pedi_prod']) && !empty($_POST['id_pepr']) )
{
    $buff->SetTypeMin('json');
    	$database->autocommit(false);
	 if($database->consulta("UPDATE pedi_prod SET cant_pedi=cant_entr WHERE id_pepr='".$_POST['id_pepr']."'"))
	{
         $database->consulta("SELECT * FROM pedi_prod WHERE  id_pepr='".$_POST['id_pepr']."'");
		$pedi=$database->result();
		
           $database->consulta("SELECT * FROM pedi_prod where (cant_pedi!=cant_entr AND (cant_entr is NOT NULL ))  and nume_pedi='".$pedi['nume_pedi']."'");
			if($database->result->num_rows==0)
			{
				if(!$database->consulta("UPDATE pedidos SET esta_pedi='E' WHERE nume_pedi='".$pedi['nume_pedi']."'"))
				{
					$json->Set('error',"ERROR INESPERADO AL entregar  PEDIDO");
                   
					
				}
			}
		
	}else
	{
		$json->Set('error',"ERROR INESPERADO AL CANCELAR EL PRODUCTO DEL PEDIDO");
         
	}
	if(!$database->error())
	{
		$database->commit();
       
	}else
	{
		$database->rollback();
		$json->Set('error',"ERROR AL IGUALAR LAS CANTIDADES");
	}
}