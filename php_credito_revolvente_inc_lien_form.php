<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
if(isset($_GET["x_credito_id"])){
	//mandamos a Load data
	$x_credito_id = $_GET["x_credito_id"];
	if(!Loaddata($conn, $x_credito_id)){
		// no se cargaron los datos
		echo "Error!!";
		}
	
	}else if(isset($_POST['addData'])){
//	echo "esta entrando con el post para guardar....";
		// insertamos los registros
		
		$x_credito_id = $_POST["x_credito_id"];
		$x_monto_para_genererar_nuevo_vencimeinto = $_POST["x_monto_otorgado"];
		
		
		$sqlLineaCredito = "select * from linea_credito WHERE credito_id =  $x_credito_id ";
		$rsLineaCredito = phpmkr_query($sqlLineaCredito,$conn)or die ("Error al seleccionar los datosde l al linea de credito".phpmkr_error().$sqlLineaCredito);
		$rowLineaCredit = phpmkr_fetch_array($rsLineaCredito);
		$x_monto_linea_credito =  $rowLineaCredit["monto_maximo_aprobado"];

		//sleccionamos los dtaos del credito que corresponde
		
		$sqlCredito = "SELECT * FROM credito WHERE credito_id =  $x_credito_id ";
		$rsCredito = phpmkr_query($sqlCredito,$conn) or die("Error la seleccionar los credito".phpmkr_error()."sql : ".$sqlCredito);
		$rowCredito = phpmkr_fetch_array($rsCredito);
		$x_fecha_otorgamiento = $rowCredito["fecha_otrogamiento"];
		$x_tasa = $rowCredito["tasa"];
		$x_iva = $rowCredito["iva"]; // si tiene iva vale 1 el campo




		// seleccionamos el monto del ultimo incredmento
		
		$sqlUltimoIncremento =  "SELECT * FROM  credito_incremento WHERE credito_id = $x_credito_id order by credito_incremento_id DESC limit 0,1 ";
		$rsUltimoIncremento = phpmkr_query($sqlUltimoIncremento,$conn) or die("Error al seleccionar el ultimo incremento".phpmkr_error()."sql: ".$sqlUltimoIncremento);
		$rowUltimoIncremento =  phpmkr_fetch_array($rsUltimoIncremento);
		$x_ultimo_incremento =  $rowUltimoIncremento["monto_incrementado"]+0;
		$GLOBALS["monto_actual_autorizado"] = $x_ultimo_incremento;
		
				// seleccionamos la fecha del ultimo vencimiento
		$sqlFV =  "SELECT * FROM vencimiento WHERE credito_id =  $x_credito_id and vencimiento_num < 1000 ORDER BY vencimiento_id DESC LIMIT 0,1 ";
		$rsFV  = phpmkr_query($sqlFV,$conn)or die ("Error al seleccionar el ultimo vencimiento".phpmkr_error()."sql:".$sqlFV);
		$rowFV = phpmkr_fetch_array($rsFV);
		$x_fecha_ultimo_vencimiento = $rowFV["fecha_vencimiento"]; 
		$x_venc_num =  $rowFV["vencimiento_num"];
		$x_venc_num_act = $x_venc_num + 1;
		//icrementamos la fecha en 1 mes
		$sqlFceha =  " SELECT DATE_ADD(\"$x_fecha_ultimo_vencimiento\", INTERVAL 1 MONTH) AS proximo_mes";
		$rsFecha = phpmkr_query($sqlFceha,$conn)or die ("error al seleccionar la fecha de mes ".phpmkr_error()."sql :".$sqlFceha);
		$rowFceha = phpmkr_fetch_array($rsFecha);
		$x_fecha_mes_proximo = $rowFceha["proximo_mes"];

		$x_calculo_incremento =  2;
		$x_incremento = 0;
		$x_nuevo_monto_a_otorgar = 0;
		$x_fecha_nuevo_vencimento= "0000-00-00";
		###############################################################################
		// dos manera de calcuña el siguiente incremento
		#1,- el incremento se calcula sobre con el 10% sobre le onto base otorgado
		#2.- el incremento se calcula con el 10% sobre el monto del ultimo incremento
		###############################################################################
		$x_today = date("Y-m-d");
		if($x_calculo_incremento ==  1){
			$x_incremento =  ($x_monto_base * 1.10)-$x_ultimo_incremento;
			$x_nuevo_monto_a_otorgar =  $x_ultimo_incremento + $x_incremento;	
			$x_fecha_nuevo_vencimento = $x_fecha_mes_proximo ; // la fecha del ultimo vencimeinto mas 1 periodo
			}else{
				$x_incremento =  ($x_ultimo_incremento * 1.10)-$x_ultimo_incremento;
				$x_nuevo_monto_a_otorgar =  $x_ultimo_incremento + $x_incremento;	
				$x_fecha_nuevo_vencimeinto = $x_fecha_mes_proximo  ;// se conserva la mis fecha de vecimiento
				}
				
	$GLOBALS["x_monto_maximo_a_incrementar"] =  ($x_ultimo_incremento * 1.10);	
// insertamos en la tabla credito_incremento
		$sqlInsertaIncremento =  "INSERT INTO credito_incremento (`credito_incremento_id`, `credito_id`, `monto_inicio`, `monto_incrementado`, `fecha_incremento`)";
		$sqlInsertaIncremento .= " VALUES (NULL, $x_credito_id, $x_ultimo_incremento, $x_nuevo_monto_a_otorgar, \"$x_today\")";
		$rsInsertaIncremento =  phpmkr_query($sqlInsertaIncremento,$conn) or die("Error inserta incremento".phpmkr_error()."sql: ".$sqlInsertaIncremento);
		#echo  $sqlInsertaIncremento."<br>";
		
		// el nuevo vencimiento se calcula
		# el valor del nuevo vemiciento sera por el nuevo monto a otorgar menos el monto que este pendiente por liquidar
		$sqlVecimeintosPendiente = "SELECT SUM(total_venc) AS total_pendiente from vencimiento where credito_id = $x_credito_id and vencimiento.vencimiento_status_id in (1,3,6,7) ";
		$rsVencPend = phpmkr_query($sqlVecimeintosPendiente,$conn)or die("Error la sumar los pendientes ".phpmkr_error()."sql:".$sqlVecimeintosPendiente);
		$rowVencPend = phpmkr_fetch_array($rsVencPend);
		$x_monto_pendiente = $rowVencPend["total_pendiente"];
		$GLOBALS["x_saldo_pendiente_actual"] = $x_monto_pendiente;
		// se calcula el monto para generar el nuevo vencimiento 
		//$x_monto_para_genererar_nuevo_vencimeinto =   $x_nuevo_monto_a_otorgar - $x_monto_pendiente;



// se calculan los interes del nuevo vencimento y se inserta en la tabla vencimiento
// los intereses se calculan desde hoy.. hasta el proximon mes
$x_hoy = date("Y-m-d");
$sqlDiasPeriodo =  " SELECT DATEDIFF(\"$x_fecha_nuevo_vencimeinto\",\"$x_hoy\") AS dias_periodo";
$rsDiasPOeriodo = phpmkr_query($sqlDiasPeriodo,$conn)or die("Error al selecionar los dias del period".phpmkr_error()."sql:".$sqlDiasPeriodo);
$rowDiasP  = phpmkr_fetch_array($rsDiasPOeriodo);
$x_dias_periodo = $rowDiasP["dias_periodo"];

// se calcula el siguiente vencimiento
$interes_vencimiento = round($x_monto_para_genererar_nuevo_vencimeinto * ((($x_tasa/100)/360) * $x_dias_periodo));
 
// si tiene iva 
if($x_iva == 1){
	$x_iva_vencimiento = round($interes_vencimiento * .16 );
	$x_total_vencimiento = $interes_vencimiento + $x_iva_vencimiento+ $x_monto_para_genererar_nuevo_vencimeinto;
	}else{
		$x_iva_vencimiento = 0;
		$x_total_vencimiento = $interes_vencimiento + $x_monto_para_genererar_nuevo_vencimeinto;
		} 
		
		
#echo "<BR> MONTO NUEVO A OTORGAR ".$x_nuevo_monto_a_otorgar."<BR> ".$x_monto_linea_credito ;		
if($x_monto_para_genererar_nuevo_vencimeinto < $x_monto_linea_credito ){
	#echo "nuevo monto a otorgar es menor al monto maximo de la linea de credito ";
$sSql = "insert into vencimiento values(0,$x_credito_id, $x_venc_num_act,1, '".$x_fecha_nuevo_vencimeinto."', $x_monto_para_genererar_nuevo_vencimeinto, $interes_vencimiento, 0, $x_iva_vencimiento, 0, $x_total_vencimiento,NULL)";
$rsSql =  phpmkr_query($sSql,$conn) or die ("Error al insertar en vencimiento".phpmkr_error()."sql:".$sSql);
//Se actualiz el credito
$sSql = "UPDATE credito set importe = (importe + $x_monto_para_genererar_nuevo_vencimeinto) where credito_id = $x_credito_id ";
$rs = phpmkr_query($sSql,$conn) or die("error".phpmkr_error().$sSql);

//se actulizan las fechas de los vencimientos.. a la fecha nueva calculada; si y solo si el vencimiento esta pendiente o vencido
$sSql = "UPDATE vencimiento SET fecha_vencimiento =  \"$x_fecha_nuevo_vencimeinto\" WHERE credito_id = $x_credito_id and vencimiento_status_id in (1,3) ";
$sSql .= " and fecha_vencimiento <  \"$x_fecha_nuevo_vencimeinto\" ";
$rs = phpmkr_query($sSql,$conn)or die("Error al actualiza los vecimineotos".phpmkr_error()."SQL:".$sSql);


}else{
	$_SESSION["ewmsg"]= "El monto que se prente otorgar es mayor al monto aprobado en la linea de credito, intentelo nuevamente con un monto menor";
	
	}
		
				phpmkr_db_close($conn);
			//	ob_end_clean();
			//	header("Location: php_credito_revolventeedit.php?credito_id=".$x_credito_id."");
			//	exit();
				//http://financieracrea.com/prueba.esf/php_credito_revolventeedit.php?credito_id=$x_credito_id
		
		
		
		}


?>

<html> 
<head>  
  <link href="php_project_esf.css" rel="stylesheet" type="text/css" />
  <style type="text/css" media="screen">
    html { background: #ddd; }
    body { font: normal 11px/1.6 'Open Sans', "Helvetica Neue", Arial, sans-serif; font-weight: 200; color: #777; padding: 2em 5%; width: 80%; max-width: 900px; margin: 0 auto; background: #fff; }
    small { color: #aaa; }
    h1,h2,h3,h4 { color: #444; font-weight: 300; font-size: 1.6em; letter-spacing: -1px; }
    a { color: #0086B3; font-weight: 700; }
    a:hover { color: #000; }
    p code, li code {background:#ffffcc; color: #444; }
    pre { font-size: 12px; line-height: 18px; }
    pre code { overflow: scroll; padding: 1em; border-radius: 10px; }
    hr { height: 10px; background: #eee; border: none; }
    table {width:100%;border-collapse:collapse;}
    td { border: 1px solid #eee; padding: 5px; }
    td pre { margin: 0; }
    
    /* Example 2 (login form) */
    .login_form.modal {
      border-radius: 0;
      line-height: 18px;
      padding: 0;
      font-family: "Lucida Grande", Verdana, sans-serif;
    }
    
    .login_form h3 {
      margin: 0;
      padding: 10px;
      color: #fff;
      font-size: 14px;
      background: -moz-linear-gradient(top, #2e5764, #1e3d47);
      background: -webkit-gradient(linear,left bottom,left top,color-stop(0, #1e3d47),color-stop(1, #2e5764));
    }
    
    .login_form.modal p { padding: 20px 30px; border-bottom: 1px solid #ddd; margin: 0;
      background: -webkit-gradient(linear,left bottom,left top,color-stop(0, #eee),color-stop(1, #fff));  
      overflow: hidden;
    }
    .login_form.modal p:last-child { border: none; }
    .login_form.modal p label { float: left; font-weight: bold; color: #333; font-size: 13px; width: 110px; line-height: 22px; }
    .login_form.modal p input[type="text"],
    .login_form.modal p input[type="password"] {
      font: normal 12px/18px "Lucida Grande", Verdana;
      padding: 3px;
      border: 1px solid #ddd;
      width: 200px;
    }
    
    
    .part {
      display: none;
    }
    
  </style>
  <script language="javascript">
	$(document).ready(function(){ 
		
	 $('form').keypress(function(e){   
		if(e == 13){
		  return false;
		}
	  });

  $('input').keypress(function(e){
	  if(e.which == 13){
		  
      return false;
    }
  });
		
	$('#x_monto_otorgado').change(function (evento){
		var valor_a_otorgar = $(this).val();
		var valor_maximo = $('#x_monto_maximo_a_otorgar').val();
		//alert ("entra a monto otorgado");
		//alert("ortgado"+valor_a_otorgar);
		//alert("maximo"+valor_maximo);
		
		if(valor_a_otorgar > valor_maximo){
			$('#x_compara_valores').html("EL MONTO QUE INTENTA OTORGAR ES MAYOR AL PERMITIDO");
			$('#x_envia').attr('disabled','disabled');

			}else{				
				if(valor_a_otorgar > 0){
				$('#x_envia').removeAttr("disabled");
				$('#x_compara_valores').html('');
				}else{
					$('#x_compara_valores').html("EL MONTO QUE INTENTA OTORGAR ES NEGATIVO");
					}
				}
				
		});
	
	$('x_envia').click(function (evento){
		$('#x_form_inc_linea').submit();
		
		});	
	});
	</script>
  
  <title>FINANCIERA CREA == INCREMENTO EN LA LINE DE CREDITO ==</title>
</head> 

<body>
<CENTER><form action="php_credito_revolvente_inc_lien_form.php" id="x_form_inc_linea" method="post" >
<input type="hidden" name="addData" value="1">
<input type="hidden" name="x_credito_id" id="x_credito_id" value="<?php echo $x_credito_id;?>" >
<table width="70%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"  ><CENTER><h3>AUMENTO EN L&Iacute;NEA DE CR&Eacute;DITO</h3></CENTER></td>
    </tr>
  <tr>
    <td width="576"><label>N&uacute;mero de cr&eacute;dito </label></td>
    <td width="272"><strong><?php echo $x_credito_num?></strong></td>
    </tr>
  <tr>
    <td><label>Monto apertura</label></td>
    <td><?php echo $x_monto_linea_credito?></td>
    </tr>
  <tr>
    <td><label>Porcentaje a incrementar</label></td>
    <td>10%</td>
    </tr>
  <tr>
    <td><label>Monto autorizado actual</label></td>
    <td><?php echo $x_monto_actual_autorizado?></td>
    </tr>
   <tr>
    <td><label>Monto m&aacute;ximo a incrementar</label></td>
    <td><?php echo $x_monto_maximo_a_incrementar?></td>
    </tr>
   <tr>
    <td><label>Saldo pendiente actual</label></td>
    <td><?php echo $x_saldo_pendiente_actual?></td>
    </tr>    
   <tr>
    <td><label>Monto m&aacute;ximo a otorgar</label></td>
    <td style="color:#3399FF"><strong><?php echo $x_sugerido_a_otorgar?></strong><input type="hidden" name="x_monto_maximo_a_otorgar" id="x_monto_maximo_a_otorgar" value="<?php echo $x_sugerido_a_otorgar?>"></td>
    </tr>
  
    <tr>
    <td colspan="2" style="color:#FF0000"><center><span id="x_compara_valores">&nbsp;</span></center></td>
    </tr>
    <tr>
    <td><label>Monto que se otorgar&aacute;</label></td>
    <td><input type="text" name="x_monto_otorgado" id="x_monto_otorgado" value="<?php echo $x_monto_otorgado?>"></td>
    </tr>
   <tr>
    <td colspan="2" align="right"><input  type="button" value="Aprobar" id="x_envia" disabled ></td>
    </tr>
   
</table>
</form></CENTER></body>
</html>
<?php 
function LoadData($conn, $x_credito_id){
	// aqui se aumenta la linea de credito
$x_credito_id =  $_GET["x_credito_id"];
$x_monto_nuevo = $_GET["x_monto_nuevo"];
$x_today =  date("Y-m-d");


// seleccionamos los datos de la linea de credito
$sqlLineaCredito = "SELECT * FROM linea_credito WHERE credito_id = $x_credito_id ";
$rsLineaCredito = phpmkr_query($sqlLineaCredito,$conn)or die("erro al selccionar la lienea de credito".phpmkr_error()."sql:".$sqlLineaCredito);
$rowLineaCredit = phpmkr_fetch_array($rsLineaCredito);
$x_monto_linea_credito = $rowLineaCredit["monto_apertura"];
$GLOBALS["x_monto_maximo"] = $rowLineaCredit["monto_maximo_aprobado"]; 
$GLOBALS["x_monto_linea_credito"] = $x_monto_linea_credito;
#echo "SQL = ".$sqlLineaCredito."<BR> monto max aunt:". $rowLineaCredit["monto_maximo_aprobado"]."<br>";
//sleccionamos los dtaos del credito que corresponde
#echo "<br>monto apertura ".$x_monto_linea_credito."";
$sqlCredito = "SELECT * FROM credito WHERE credito_id =  $x_credito_id ";
$rsCredito = phpmkr_query($sqlCredito,$conn) or die("Error la seleccionar los credito".phpmkr_error()."sql : ".$sqlCredito);
$rowCredito = phpmkr_fetch_array($rsCredito);
$x_fecha_otorgamiento = $rowCredito["fecha_otrogamiento"];
$x_tasa = $rowCredito["tasa"];
$x_iva = $rowCredito["iva"]; // si tiene iva vale 1 el campo
$GLOBALS["x_credito_num"] = $rowCredito["credito_num"];


// seleccionamos los datos del vemiento 
#$sqlvencimiento = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id  and vencimiento_num = 1";

// seleccionamos el montos con el que inicio el credito
/*$sqlMontoBase = "SELECT * FROM credito_monto_apertura  WHERE credito_id = $x_credito_id ";
$rsMontoBase = phpmkr_query($sqlMontoBase,$conn) or die("Error la seleccionar el monto base ".phpmkr_error()."sql:".$sqlMontoBase);
$rowMontoBase = phpmkr_fetch_array($rsMontoBase);
$x_monto_base = $rowMontoBase["monto_apertura"];
$x_monto_maximo = $rowMontoBase["monto_maximo_autorizado"]; */

// seleccionamos el monto del ultimo incredmento

$sqlUltimoIncremento =  "SELECT * FROM  credito_incremento WHERE credito_id = $x_credito_id order by credito_incremento_id DESC limit 0,1 ";
$rsUltimoIncremento = phpmkr_query($sqlUltimoIncremento,$conn) or die("Error al seleccionar el ultimo incremento".phpmkr_error()."sql: ".$sqlUltimoIncremento);
$rowUltimoIncremento =  phpmkr_fetch_array($rsUltimoIncremento);
$x_ultimo_incremento =  $rowUltimoIncremento["monto_incrementado"];
$GLOBALS["x_monto_actual_autorizado"] = $x_ultimo_incremento;
// seleccionamos la fecha del ultimo vencimiento
$sqlFV =  "SELECT * FROM vencimiento WHERE credito_id =  $x_credito_id ORDER BY vencimiento_id DESC LIMIT 0,1 ";
$rsFV  = phpmkr_query($sqlFV,$conn)or die ("Error al seleccionar el ultimo vencimiento".phpmkr_error()."sql:".$sqlFV);
$rowFV = phpmkr_fetch_array($rsFV);
$x_fecha_ultimo_vencimiento = $rowFV["fecha_vencimiento"]; 
$x_venc_num =  $rowFV["vencimiento_num"];
$x_venc_num_act = $x_venc_num + 1;
//icrementamos la fecha en 1 mes
$sqlFceha =  " SELECT DATE_ADD(\"$x_fecha_ultimo_vencimiento\", INTERVAL 1 MONTH) AS proximo_mes";
$rsFecha = phpmkr_query($sqlFceha,$conn)or die ("error al seleccionar la fecha de mes ".phpmkr_error()."sql :".$sqlFceha);
$rowFceha = phpmkr_fetch_array($rsFecha);
$x_fecha_mes_proximo = $rowFceha["proximo_mes"];

$x_calculo_incremento =  2;
$x_incremento = 0;
$x_nuevo_monto_a_otorgar = 0;
$x_fecha_nuevo_vencimento= "0000-00-00";
###############################################################################
// dos manera de calcuña el siguiente incremento
#1,- el incremento se calcula sobre con el 10% sobre le onto base otorgado
#2.- el incremento se calcula con el 10% sobre el monto del ultimo incremento
###############################################################################
$x_today = date("Y-m-d");
if($x_calculo_incremento ==  1){
	$x_incremento =  ($x_monto_base * 1.10)-$x_ultimo_incremento;
	$x_nuevo_monto_a_otorgar =  $x_ultimo_incremento + $x_incremento;	
	$x_fecha_nuevo_vencimento = $x_fecha_mes_proximo ; // la fecha del ultimo vencimeinto mas 1 periodo
	}else{
		$x_incremento =  ($x_ultimo_incremento * 1.10)-$x_ultimo_incremento;
		$x_nuevo_monto_a_otorgar =  $x_ultimo_incremento + $x_incremento;	
		$x_fecha_nuevo_vencimeinto = $x_fecha_mes_proximo  ;// se conserva la mis fecha de vecimiento
		}
	$GLOBALS["x_monto_maximo_a_incrementar"] =  ($x_ultimo_incremento * 1.10);	
// insertamos en la tabla credito_incremento
		#$sqlInsertaIncremento =  "INSERT INTO credito_incremento (`credito_incremento_id`, `credito_id`, `monto_inicio`, `monto_incrementado`, `fecha_incremento`)";
		#$sqlInsertaIncremento = " VALUES (NULL, $x_credito_id, $x_ultimo_incremento, $x_nuevo_monto_a_otorgar, \"$x_today\")";
		#$rsInsertaIncremento =  phpmkr_query($sqlInsertaIncremento,$conn) or die("Error inserta incremento".phpmkr_error()."sql: ".$sqlInsertaIncremento);
		
		
// el nuevo vencimiento se calcula
# el valor del nuevo vemiciento sera por el nuevo monto a otorgar menos el monto que este pendiente por liquidar
$sqlVecimeintosPendiente = "SELECT SUM(total_venc) AS total_pendiente from vencimiento where credito_id = $x_credito_id and vencimiento.vencimiento_status_id in (1,3,6,7) ";
$rsVencPend = phpmkr_query($sqlVecimeintosPendiente,$conn)or die("Error la sumar los pendientes ".phpmkr_error()."sql:".$sqlVecimeintosPendiente);
$rowVencPend = phpmkr_fetch_array($rsVencPend);
$x_monto_pendiente = $rowVencPend["total_pendiente"];
$GLOBALS["x_saldo_pendiente_actual"] = $x_monto_pendiente;
// se calcula el monto para generar el nuevo vencimiento 
$x_monto_para_genererar_nuevo_vencimeinto =   $x_nuevo_monto_a_otorgar - $x_monto_pendiente;

$GLOBALS["x_sugerido_a_otorgar"]= $x_nuevo_monto_a_otorgar;
$GLOBALS["x_sugerido_a_otorgar"]= $x_monto_para_genererar_nuevo_vencimeinto;

// se calculan los interes del nuevo vencimento y se inserta en la tabla vencimiento
$sqlDiasPeriodo =  " SELECT DATEDIFF(\"$x_fecha_nuevo_vencimeinto\",\"$x_fecha_ultimo_vencimiento\") AS dias_periodo";
$rsDiasPOeriodo = phpmkr_query($sqlDiasPeriodo,$conn)or die("Error al selecionar los dias del period".phpmkr_error()."sql:".$sqlDiasPeriodo);
$rowDiasP  = phpmkr_fetch_array($rsDiasPOeriodo);
$x_dias_periodo = $rowDiasP["dias_periodo"];

// se calcula el siguiente vencimiento
$interes_vencimiento = $x_monto_para_genererar_nuevo_vencimeinto * ((($x_tasa/100)/360) * $x_dias_periodo);
 
// si tiene iva 
if($x_iva == 1){
	$x_iva_vencimiento = round($interes_vencimiento * .16 );
	$x_total_vencimiento = $interes_vencimiento + $x_iva_vencimiento+ $x_monto_para_genererar_nuevo_vencimeinto;
	}else{
		$x_iva_vencimiento = 0;
		$x_total_vencimiento = $interes_vencimiento + $x_monto_para_genererar_nuevo_vencimeinto;
		} 
if($x_nuevo_monto_a_otorgar < $x_monto_linea_credito ){
#$sSql = "insert into vencimiento values(0,$x_credito_id, $x_venc_num_act,1, '".$x_fecha_nuevo_vencimeinto."', 0, $interes_vencimiento, 0, $x_iva_vencimiento, 0, $x_total_vencimiento,NULL)";
#$rsSql =  phpmkr_query($sSql,$conn) or die ("Error al insertar en vencimiento".phpmkr_error()."sql:".$sSql);
}else{
	$_SESSION["ewmsg"]= "El monto que se prente otorgar es mayor al monto aprobado en la linea de credito, intentelo nuevamente con un monto menor";
	
	}

	return true;
	
	
	}


?>