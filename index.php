<?php
require("clases/config_orveinca.php");
if(!$_POST)
{	
	$html=new HTML(false); 
	$html->SetLayout('login');
	$ERROR='';
	setcookie('REINICIAR',true,NULL,__DOCUMENT_ROOT__);
	if(!empty($_COOKIE['ERROR']))
	{
		$ERROR=$_COOKIE['ERROR'];
		setcookie('ERROR','',time());
	}
	$ERROR.=SESSION::GetVar('ERROR');
	$html->add_error($ERROR);
	$html->addlink_css("src/css/login_form.min.css");
	$html->set_ico("images/favicon.ico");
	$html->set_title("ORVEINCA_ADMIN ".__VERSION_ORVEINCA_ADMIN__);
	?>
<div class="form1" align="center">
<div align="center"><img src="img/lock.png"></div>
<h1>INGRESAR</h1>
<?php
	if(!SESSION::_empty('false') && SESSION::GetVar('false')>5)
	{
		echo "<h2>PORFAVOR VUELVE A  INTENTAR EN 1 MINUTO </h2>";
	}else{
		?>
<form class="form-1"  action="index.php" METHOD="post" enctype="application/x-www-form-urlencoded" name="frmentrada" target="_self" id="frmDatos">
  <p class="field">
    <input autocomplete="off" required type="text" name="usuario" placeholder="Nombre de usuario" >
    <i class="icon-user icon-large"></i></p>
  <p class="field">
    <input 	autocomplete="off" required	type="password" name="password" placeholder="contraseña">
    <i class="icon-lock icon-large"></i></p>
  <p class="submit">
    <button ><i 	class="icon-arrow-right icon-large"></i></button>
  </p>
</form>
<?php
	}
	echo "</div>";
	SESSION::Del();
	SESSION::Destroy();
    
}else
{
	$buffer= new DocumentBuffer(true,true,true);
	setcookie('REINICIAR',true,time(),__DOCUMENT_ROOT__);
	if($_SERVER['HTTP_REFERER']!=__AUTORIZATE_DIRNAME__."index.php"&&0)
	{	
		setcookie("ERROR","URL NO AUTORIZADA PORFAVOR INGRESE DESDE ESTA PAGINA",time()+200);
		redirec(__AUTORIZATE_DIRNAME__."index.php");  
	}else
	{
		$database= new BD_ORVEINCA(false);
		if($database->connect_error)
		{
			SESSION::SetVar('ERROR',OrveincaExeption::GetExeptionS());
			redirec(__AUTORIZATE_DIRNAME__."index.php");  
		}
		//VERIFICAR EL USUARIO Y LA CLAVE
		//if(!($res=$database->select('users','*',"where nombre='".$_POST['usuario']."' AND clave='".md5($_POST['password'],__LLAVEMD5__)."' limit 1;")))
		if(!($res=$database->consulta("select * from users where nombre='".$_POST['usuario']."' and clave='".md5($_POST['password'],true)."' limit 1;")))
		{
			$e= new OrveincaExeption("ERROR INESPERADO ",$database);
			SESSION::SetVar('ERROR',OrveincaExeption::GetExeptionS());
			
			redirec(__AUTORIZATE_DIRNAME__."index.php");  
		}
		if($res->num_rows!=1)
		{
			if(!SESSION::_empty('false'))
			{
				SESSION::SetVar('false',1);
			}else
			{
				SESSION::SetVar('false',SESSION::GetVar('false')+1);
			}
			SESSION::SetVar("ERROR","ADCESO DENEGADO PORFAVOR VERIFICA EL USUARIO O CONTRASEÑA ");
			redirec(__AUTORIZATE_DIRNAME__."index.php");  	
		}else
		{
			$campo = $res->fetch_array();
			SESSION::SetVar('id_user',$campo['nombre']);
			SESSION::SetVar('permisos',$campo['permisos']);
			SESSION::DelVar('false');
			SESSION::DelVar('ERROR');

			//LIMPIAR REGISTROS Y ARCHIVOS TEMPORALES
			$free_temp=  dir("temp");
			while($fichero=$free_temp->read())
			{
				if($fichero!='.' && $fichero!='..')
					unlink("temp/".$fichero);
			}
			$free_temp->close();
			$database->obtimizar();
?>
<!doctype html>
<html lang="es" >
<head>
<meta charset="utf-8">
<title>ORVEINCA_ADMIN<?php echo __VERSION_ORVEINCA_ADMIN__?></title>
<link rel="shortcut icon"  href="images/favicon.ico"  media="all" />
<link rel="stylesheet" href="src/css/preview.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="src/css/jquery-ui.min.css"  type="text/css" media="all" />
<script src="src/js/orveinca.min.js" ></script>
<script src="src_orig/js/preview.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src='src/js/html5shiv.min.js'></script>
<![endif]-->
<!--[if lt IE 8]>
<script src='src/js/html5shiv.min.js'></script>
<![endif]-->
<!--[if lt IE 7]>
<script src='src/js/html5shiv.min.js'></script>
<![endif]-->
<script type="text/javascript" >

    
			var time=0;
			var frecuencia=1000;
			var cookie='';
			
			function time_inline()
			{
				time++;
				if(document.cookie.indexOf("REINICIAR")!=-1)
				{
					document.cookie='ERROR="Session cerrada"';
					location.href='index.php';
				}
				//$('#time').html(time);
				if(time>1800)
				{
					document.cookie='ERROR="No ha habido actividad desde hace 30 minutos o más  inicie sesión nuevamente"';
					location.href='index.php';
				}
				setTimeout("time_inline()",frecuencia);
			};
			$(document).ready(function(e) 
							  {
				time_inline();
				cookie=document.cookie;
				$('#frame').load(function(e)
								 {
									
					$('#hide-button').css('height',$('#customize').css('height'));
					if(document.cookie=='')
					{
						document.cookie=cookie;
					}
					time=0;
				});
                var h=((window.innerHeight*80)/100);
        $('.le').css('height',h);
				$('#hide-button').css('height',$('#customize').css('height'));

				$('.frame_tab >li > ol >li >a').menu_orve();
				/*$('#user').menu_orve();
				$('#l_precios').menu_orve();
				$('#notas').menu_orve();
				$('#inve').menu_orve();
				$('#clie').menu_orve();
				$('#prov').menu_orve();
				$('#comp').menu_orve();
				$('#vent').menu_orve();
				$('#pedi').menu_orve();
				$('#empl').menu_orve();
				$('#plus').menu_orve();
				$('#gastos').menu_orve();*/
			});
		</script>
<style type="text/css">
    .le{
	overflow-y:auto;
	
	}
.salir {
	position: absolute;
	top: 5px;
	left: 200px;
	z-index: 10;
	background: #FFF;
	list-style: none;
	width: 20px;
	height: 30px;
	list-style: none;
	padding: 5px 5px 5px 9px;
	display: block;
	border-bottom: solid 1px #e9d7be;
 color:;
	float: left;
	width: 42px;
	border-radius: 5px 5px 5px 5px;
	margin: 0px;
	box-shadow: 8px 1px 5px #999;
	border: 0px;
}
a .salir {
	text-decoration: none;
	color: #000000;
}
#hide-button {
	width: 25px;
	height: 674px;
	position: absolute;
	top: 0%;
	right: -14px;
	cursor: pointer;
	z-index: 20;
	background: #fef9ec url(img/hide.png) no-repeat 3px center;
	border: solid 1px #d7bb9d;
	border-left: 0;
}
#sub_menu {
	width: 140px;
	height: 15px;
}
ul >li {
	width: 150PX;
	height: 15px;
}
</style>
</head>
<body class="html" marginheight="0">
<div id="customize"align="center">
  <div id="customize-holder"  >
    <h2 align="center"> <img src="images/favicon.png" width="60">
      <p>ORVEINCA_ADMIN<br><?php $t= new TIME(); echo $t->fecha();?>
      
      <div id="time"></div>
      <p></p>
    </h2>
    <div class="colors" >
      <div class="cl">&nbsp;</div>
      <ul class="frame_tab le"  style="max-height:600px;" >
        <li>
          <div align="center"><a href="defaut/defaut.php" class="color" style="color:#">
            <h2>Inicio</h2>
            </a> <a href="index.php">
            <div class="salir"> Cerrar sesion </div>
            </a> </div>
          <ol >
            <li   > <a   style=" width:150PX; height:15px;  "  id="precios" name="frame_precios">
              <h2 align="center">PRODUCTOS </h2>
              </a>
              <ol id="frame_precios" >
                <li><a href="lista de precios/l_precios.php"   id="sub_menu">CONSULTAR </a></li>
                <li><a href="cotizacion/busqueda.php" id="sub_menu"  >COTIZACION</a></li>
                <li><a href="lista de precios/clasificacion.php"   id="sub_menu">CLASIFICACION</a></li>
                <li><a href="lista de precios/l_precios.php?action=lpre_pdf" id="sub_menu"  >LISTA DE PRECIOS PDF</a></li>
                <li><a href="lista de precios/l_precios.php?action=cat_pdf"   id="sub_menu" >CATALOGO PDF</a></li>
                <li><a href="lista de precios/insertar_nuevo.php"   id="sub_menu">INSERTAR PRODUCTO</a></li>
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; " id="inve" name="frame_inve">
              <h2 align="center">INVENTARIO</h2>
              </a>
              <ol id="frame_inve" >
                <li><a href='inventario/inventario.php'  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="inventario/inventario_reportes.php" id="sub_menu" >REPORTES</a></li>
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; "  id="pedi" name="frame_pedi" >
              <h2 align="center">PEDIDOS</h2>
              </a>
              <ol id="frame_pedi">
                <li><a  href="pedidos/buscar_pedido.php"  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="pedidos/busqueda.php" id="sub_menu" >NOTA DE PEDIDO</a></li>
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; " id="vent" name="frame_vent">
              <h2 align="center">VENTAS</h2>
              </a>
              <ol id="frame_vent">
                <li><a href="nota_entrega/ventas.php"  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="nota_entrega/busqueda.php" id="sub_menu"  >NOTA DE ENTREGA</a></li>
                <li><a href="nota_entrega/ventas_reportes.php" id="sub_menu" >REPORTES DE VENTAS</a></li>
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; " id="comp" name="frame_comp">
              <h2 align="center">COMPRAS</h2>
              </a>
              <ol id="frame_comp">
                <li><a href="orden_compra/compras.php"  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="orden_compra/busqueda.php"   id="sub_menu" >ORDEN DE COMPRA</a></li>
                <li><a href="orden_compra/compras_reportes.php" id="sub_menu" >REPORTES</a></li>
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; " id="clie" name="frame_clie">
              <h2 align="center">CLIENTES</h2>
              </a>
              <ol id="frame_clie">
                <li><a href="clientes/buscar_info_cliente.php"  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="clientes/info_cliente.php" id="sub_menu" >REGISTRAR NUEVO</a></li>
                <li><a href="clientes/clientes_reportes.php" id="sub_menu" >REPORTES</a></li>
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; " id="prov" name="frame_prov">
              <h2 align="center">PROVEEDORES</h2>
              </a>
              <ol id="frame_prov">
                <li><a href="provedores/buscar_provedores.php"  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="provedores/info_provedores.php" id="sub_menu" >REGISTRAR NUEVO</a></li>
                <!--   <li><a href="provedores/" id="sub_menu" >REPORTES</a></li>-->
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; " id="empl" name="frame_empl">
              <h2 align="center">EMPLEADOS</h2>
              </a>
              <ol id="frame_empl">
                <li><a href="empleados/buscar_empleados.php"  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="empleados/info_empleados.php" id="sub_menu" >REGISTRAR NUEVO</a></li>
                <li><a href="empleados/nomina.php" id="sub_menu" >NOMINA</a></li>
              </ol>
            </li>
          </ol>
          <ol >
            <li> <a  style=" width:150PX; height:15px; " id="gastos" name="frame_gastos">
              <h2 align="center">GASTOS</h2>
              </a>
              <ol id="frame_gastos">
                <li><a href="gastos/"  id="sub_menu" >CONSULTAR</a></li>
                <li><a href="gastos/ingresar_gasto.php" id="sub_menu" >REGISTRAR NUEVO</a></li>
                <li><a href="gastos/gastos_reportes.php" id="sub_menu" >REPORTES</a></li>
              </ol>
            </li>
          </ol>
            <ol>
            <li> <a href="defaut/respaldo.php"style=" width:150PX; height:15px; " >
              <h2 align="center">RESPALDO</h2>
              </a> </li>
          </ol>
          <?php if($campo['permisos']==__AUTORIZATE_ROOT__){
							?>
          <ol>
            <li> <a class="h" id="user" style=" width:150PX; height:15px; " name="frame_user">
              <h2 align="center">SUPERUSUARIO</h2>
              </a>
               <ol id="frame_user" style="display:none;"  >
            <li><a id="sub_menu" href="users/index.php" >NEW USERS</a></li>
         
          </ol>
               </li>
          </ol>
         
          <!--
<ol>
<li><a  style="width:50px;" href="users/mod_user.php" >REGRESAR ADCESO</a></li>
</ol>
-->
          <?php }?>
          <ol>
            <li> <a id="plus" style=" width:150PX; height:15px; " > </a> </li>
          </ol>
        </li>
      </ul>
      <div class="cl">&nbsp;</div>
    </div>
  </div>
</div>
<div class="iframe-holder" >
  <iframe  id="frame" src="" width="100%" scrolling="auto"  frameborder="0" ></iframe>
</div>
</body>
</html>
<?php	
			echo  "<div id='errores' class='errores'  title='".htmlentities("<H2>!! ERROR ¡¡</H2>")."' style='display:none;'><div id='error_div'>".OrveincaExeption::GetExeptionS()."</div></div>";
		}
	}
}
?>
