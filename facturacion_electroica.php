<?php
ini_set("max_execution_time", "0")
?>
<?php
// Proceso nocturno para la generacion de las facturas electronicas
// Este proceso correra el  ultimo dia de cada mes en la noche para generar las facturas correspondientes al mes terminado.
// se genera el CFD y su respectiva presentacion impresa.
// todos los pagos estan agrupados por cliente.
// la facturacion correra los dias 5 de cada mes, para evitar  dejar fuera pagos que se realizaron los ultimos dias y que no se registraron hasta dias despues, por ejemplo si se pagaron el viernes y se registraron hasta el lunes.. 


include ("db.php");
include("phpmkrfn.php");
include("fcaturacion_electronica_xml.php");
include("facturacion_electronica_cadena_original.php");
include("fcaturacion_electronica_pdf.php");
include("amount2txt.php");
include("factura_xml.php");


include_once('fpdf.php'); //para crear el pdf
include_once('numero_a_letra.php'); // funcion para convertir el total a letra
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_fecha  = date("Y-m-d");

$x_ultimo_dia_mes = 0;
$x_fecha_inicio = "";
$x_fecha_fin = "";



class PDF extends FPDF
{
    //Encabezado de página
    function Header()
    {   
		$this->SetFillColor(140,240,90);
        $this->Image('imgs/logo.jpg',6,8,56);
		$this->Image('imgs/cedula.jpg',10,192,39);
		$this->SetFont('Arial','B',12);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("Microfinanciera Crece, S.A. de C.V., SOFOM, E.N.R."),0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->Cell(50,4,"FACTURA",1,1,'C',true);
		$this->SetFont('Arial','B',9);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("R.F.C. ISP900909Q88"),0,0,'C');
		$this->SetFont('Arial','',8);
		$this->Cell(25,4,"Serie: ".$x_serie,1,0,'C');
		$this->Cell(25,4,"Folio: ".$x_folio,1,1,'C');
		$this->SetFont('Arial','B',8);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("Alvaro Obregón Num. 1909, Col. San Angel"),0,1,'C');
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode('Del. Albaro obregon, México Distrito Federal, CP. 01000'),0,1,'C');
		$this->Ln(4);
    } 
}

$sqld = "SELECT DATE_SUB('$x_fecha', INTERVAL 1 MONTH )AS mes_pasado";
$rsd = phpmkr_query($sqld, $conn) or die ("Error al seleccionar el ultimo dia del mes :".phpmkr_error($sqld). "Query :".$sqld);
$rowd =phpmkr_fetch_array($rsd);
$x_fecha_mes_pasado = $rowd["mes_pasado"];
#echo "mes pasdo".$x_ultimo_dia_mes."<br>";
$sqld = "SELECT LAST_DAY('$x_fecha_mes_pasado') AS ultimo_dia";
$rsd = phpmkr_query($sqld, $conn) or die ("Error al seleccionar el ultimo dia del mes :".phpmkr_error($sqld). "Query :".$sqld);
$rowd =phpmkr_fetch_array($rsd);
$x_ultimo_dia_mes = $rowd["ultimo_dia"];
$x_p = explode("-",$x_ultimo_dia_mes);
$x_dia = $x_p["2"];
$x_mes = $x_p["1"];
$x_anio = $x_p["0"];
#echo "ultimo dia".$x_dia."<br>";
$x_primer_dia_mes = "01";
$x_fecha_inicio = $x_anio."-".$x_mes."-".$x_primer_dia_mes;
$x_fecha_fin = $x_anio."-".$x_mes."-".$x_dia;

#echo "incio".$x_fecha_inicio."<br>";

#echo "fin".$x_fecha_fin."<br>";

#####################################################################################################################
#												SELLO Y CERTIFICADO DIGITAL											#	
#####################################################################################################################

$key = "mcr070419kn2_1012292351s";
#echo "archivo".$key."<br>";

$ruta = ($maquina == "www.financieracrea.com.mx") ? "/public_html/esf/" : "./certificados/";
$file=$ruta.$key.".key.pem"; // Ruta al archivo LLAVE PRIVADA
$file_ww=$ruta.$key.".key"; // 
$fp = fopen($file, "r");
$priv_key = fread($fp,8192);
fclose($fp);

#$pkeyid = openssl_get_privatekey(file_get_contents($file));
$pkeyid = openssl_get_privatekey($priv_key);

$certificado = "00001000000102549242";

$file=$ruta.$certificado.".cer.pem";      // Ruta al archivo de Llave publica
$datos = file($file);
$certificado_ww = $ruta.$certificado."cer";

$x_cer ="00001000000102549242.cer.pem";
$x_llave = "mcr070419kn2_1012292351s.key.pem";

#echo "llave  ".$x_llave."<br>";
#echo  "llave  ".$x_cer."<br>";
$certificado_texto = "";
$ar=fopen("./certificados/certificado.txt","r") or die("No se pudo abrir el archivo");
while (!feof($ar))
	  {
		$certificado_texto.= fgets($ar);
	  }
fclose($ar);


 $x_certificados = openssl_x509_check_private_key ($x_cer,$x_llave);
 if($x_certificados){
	 echo "el certificado y la llave SI corresponden ".$x_certificados;
	 }else{
		 echo "el certificado y la llave NO corresponden ".$x_certificados; 
		 }
#echo "datos".$datos."<br>";
$certificado = ""; $carga=false;

for ($i=0; $i<sizeof($datos); $i++) {
    if (strstr($datos[$i],"END CERTIFICATE")) $carga=false;
    if ($carga) $certificado .= trim($datos[$i]);
    if (strstr($datos[$i],"BEGIN CERTIFICATE")) $carga=true;
}
#echo "certificado".$certificado."<br>";


######################################################################################################################
######################################################################################################################
$sqlpago = "SELECT * FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id JOIN credito ON credito.credito_id = vencimiento.credito_id WHERE fecha_pago <= '2012-01-01' AND fecha_pago >= '2012-01-01' ";
$rsp = phpmkr_query($sqlpago,$conn) or die("Error al seleccionar los pagos de inicio y fin de mes".phpmkr_error(). "Query :".$sqlpago);
while ($rowp = phpmkr_fetch_array($rsp)){
$x_recibo_id = $rowp["recibo_id"];	

#echo "pago_id______________ ".$x_recibo_id."....";

#echo "vencimeintos :".$rowp["referencia_pago_2"]."<br>";
}
//$sqlpago = "SELECT * FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id JOIN  credito ON credito.credito_id = vencimiento.credito_id  WHERE fecha_pago  <= '$x_fecha_fin'   AND fecha_pago >= '$x_fecha_inicio' ";
$sqlpago = "SELECT  recibo.referencia_pago_2 as r_referencia_pago_2, recibo.importe as r_monto_pago, recibo.fecha_pago as r_fecha_pago, recibo.recibo_id as r_recibo_id, credito.credito_id as c_credito_id, credito.iva AS c_iva FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id JOIN credito ON credito.credito_id = vencimiento.credito_id WHERE fecha_pago <= '2012-01-31' AND fecha_pago >= '2012-01-01' GROUP BY recibo.recibo_id";


#echo "sql :".$sqlpago."<br>";
$rsp = phpmkr_query($sqlpago,$conn) or die("Error al seleccionar los pagos de inicio y fin de mes".phpmkr_error(). "Query :".$sqlpago);
while ($rowp = phpmkr_fetch_array($rsp)){
	
	// seleccionamos todo los pagos que se hicieron en el mes
	$x_recibo_id = $rowp["r_recibo_id"];	#echo "pago_id______________ ".$x_recibo_id."<br>";
	$x_fecha_pago = $rowp["r_fecha_pago"];
	$x_importe = $rowp["r_monto_pago"];
	$x_referencia_pago = $rowp["r_referencia_pago_2"];
	$x_credito_id  = $rowp["c_credito_id"];
	$x_iva = $rowp["c_iva"];
	$x_total_venc = $rowp["total_venc"];
	
#	echo "credito_id__________".$x_credito_id ."<br>";
	
	//seleccionamos los datos del cliente
	$sqlcliente = "SELECT cliente.* FROM cliente JOIN solicitud_cliente ON	solicitud_cliente.cliente_id = cliente.cliente_id  JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito.credito_id = $x_credito_id";
	$rscliente = phpmkr_query($sqlcliente,$conn) or die("Erroe en datos del cliente". phpmkr_error()."Query: ".$sqlcliente);
	$rowcliente = phpmkr_fetch_array($rscliente);
	
	
	$x_cliente_id = $rowcliente["cliente_id"];
	$x_nombre_completo  = $rowcliente["nombre_completo"];
	$x_rfc  = $rowcliente["rfc"];
	$x_longitud_rfc = strlen($x_rfc);
	if(($x_longitud_rfc < 13) || ($x_longitud_rfc > 13) ){
		$x_rfc = "XAXX010101000";
		}
	$x_apellido_paterno = $rowcliente["apellido_paterno"];
	$x_apellido_materno  = $rowcliente["apellido_materno"];
	
	#echo "sql<br>". $sqlcliente."<br>";
#	echo "cliente_id".$x_cliente_id."<br>";
	// verificamos si ya existe una factura del mes actual para el cliente en turno
	// si ya existe solo se agrega la informacion del pago.
	// si no existe se agrega la informacion del pago. de la factura y del cliente
	
	$sqlFactura = "SELECT * FROM factura WHERE fecha_generacion <= '$x_fecha_fin' AND fecha_generacion >= '$x_fecha_inicio' and cliente_id = $x_cliente_id";
	$rsFactura = phpmkr_query($sqlFactura, $conn) or die("Error al seleccionar en factura :".phpmkr_error(). "Query :".$sqlFactura);
	$rowFactura = phpmkr_fetch_array($rsFactura);
	$x_facturas = phpmkr_num_rows($rsFactura);
#	echo "facturas registradas de este cliente".$x_facturas."<br>";
			if($x_facturas < 1){				//				
				#echo "NO HAY FACTURA REGISTRADA DE ESTE CLIENTE<BR>";
				//seleccionamos los datos de la direccion del cliente.
				
				$sqlDireccion = "SELECT * FROM direccion WHERE cliente_id = $x_cliente_id AND direccion_tipo_id  = 1 order by  direccion_id DESC";
				#echo "sql direccion<br>".$sqlDireccion."<br>";
				$rsDireccion = phpmkr_query($sqlDireccion, $conn) or die("Error al selaccionar los datos de la direccion ". phpmkr_error()."Sql :".$sqlDireccion);
				$rowDireccion  = phpmkr_fetch_array($rsDireccion);
				$x_calle = $rowDireccion["calle"];
				$x_colonia  = $rowDireccion["colonia"];
				$x_numero_exterior = $rowDireccion["numero_exterior"];
				$x_delegacion_id = $rowDireccion["delegacion_id"];
				$x_entidad_id = $rowDireccion["entidad"];
				
			#	echo "entidad_id =".$x_entidad_id."<br>";
			#	echo "delegacion id".$x_delegacion_id."<br>";
				$x_codigo_postal = $rowDireccion["codigo_postal"];
			#	echo "codigo.postal".$x_codigo_postal."<br>";
				$x_delegacion = "";
				$x_entidad = "";
				 if($x_delegacion_id == 0){
					 echo "del id 0<br>";
					echo  $sqlDireccion."<br>";
					 
					 }
				if(!empty($x_delegacion_id) && ($x_delegacion_id >0)){
				#	echo "delagacio no vacia";
				$sqlDel  = "SELECT delegacion.descripcion, delegacion.entidad_id, entidad.nombre FROM delegacion JOIN entidad ON entidad.entidad_id = delegacion.entidad_id WHERE delegacion_id = $x_delegacion_id" ;
				$rsDel = phpmkr_query($sqlDel, $conn) or die("Error al seleccionar la delagacion de la direccion del cliente". phpmkr_error()."sql :".$sqlDel);
				$rowDel = phpmkr_fetch_array($rsDel);
				$x_delegacion = $rowDel["descripcion"];
				$x_entidad = $rowDel["nombre"];
				}else if(!empty($x_entidad_id)){
				#	echo "estado no vacio";
					$sqlDel  = "SELECT entidad.nombre, delegacion.descripcion FROM entidad JOIN delegacion ON delegacion.entidad_id = entidad.entidad_id WHERE entidad.entidad_id = $x_entidad_id" ;
				$rsDel = phpmkr_query($sqlDel, $conn) or die("Error al seleccionar la delagacion de la direccion del cliente". phpmkr_error()."sql :".$sqlDel);
				$rowDel = phpmkr_fetch_array($rsDel);
				$x_delegacion = $rowDel["descripcion"];
				$x_entidad = $rowDel["nombre"];	
				#echo "sql edo".$sqlDel."<br>";
				}
				echo "entidad_id =".$x_entidad_id."<br>";
				echo "delegacion id".$x_delegacion_id."<br>";
				echo "codigo.postal".$x_codigo_postal."<br>";
				
				
				 empty($x_delegacion)? "ALVARO OBREGON":$x_delegacion;
				 empty($x_entidad)? "DISTRITO FEDERAL":$x_entidad;
				 empty($x_codigo_postal)? "01001":$x_codigo_postal;
				 empty($x_numero_exterior)? "0":$x_numero_exterior;
				 empty($x_calle)? "0":$x_calle;
				 empty($x_colonia)? "0":$x_colonia;
				 
				 
				# echo $x_delegacion."<br>";
				#  echo $x_entidad."<br>";
				 
				 
				 //empty($x_delegacion)? "ALVARO OBREGON":$x_delegacion;
				
				
				// la factura no existe y se debe guardar los datos de la factura, cliente y pago. 	
				//insert en factura.
				//insert en datoscliente.				
				//insert en pagos.
				
				//FOLIO SIGUIENTE
				$sqlFolio = "SELECT MAX(folio) AS mayor FROM factura";
				$rsFolio = phpmkr_query($sqlFolio,$conn) or die("Error al seleccionar el folio".phpmkr_error()."Sql :".$sqlFolio);
				$rowFolio = phpmkr_fetch_array($rsFolio);
				$x_folio_siguiente = $rowFolio["mayor"]+1;
				$x_sub_total = 0;
				$x_total = 0;
				$x_forma_pago = "PAGO EN UNA SOLA EXHIBICION";
				$x_metodo_pago = "NO IDENTIFICADO";
				$x_tipo_comprobante = "ingreso";
				
				$sqlInsertFac = "INSERT INTO `factura` (`factura_id` ,`cliente_id` ,`fecha_generacion` ,`folio` ,`sub_total` ,`total` ,`forma_pago` ,`metodo_pago` ,`tipo_comprobante`)";
				$sqlInsertFac .= "VALUES (NULL , $x_cliente_id, '$x_fecha_fin', $x_folio_siguiente, $x_sub_total, $x_total, '$x_forma_pago', '$x_metodo_pago', '$x_tipo_comprobante');";
				$rsInsertFact = phpmkr_query($sqlInsertFac,$conn) or die("Error al insertar en factura". phpmkr_error()."SQL :".$sqlInsertFac);
				$x_factura_id =  mysql_insert_id(); 
				
				
				$x_valor_unitario = $x_importe; // corresponde al monto del pago menos el iva que se pago en ese recibo
				$x_importe_p = $x_importe;// es igaul al valor unitario ya que solo es un pago
				
				##SOLO APLICA SI EL CREDITO ES CON IVA
				$x_tasa = 0;
				$x_importe_iva = 0;
				$impuesto = "";
				if($x_iva == 1){
				$x_tasa = 16; // el valor de la tasa de 	IVA
			#	echo "importe =".$x_importe."<br>";
				$x_importe_iva = (($x_tasa /100) * $x_importe); // la cantidad que se pago de iva en el recibo debe corresponder al 16% si es que el credito cobra IVA si no es asi debe ser de 0;
			#	echo "importe iva =".$x_importe_iva."<br>";
				$x_impuesto = "I.V.A";
				$x_valor_unitario = ($x_importe -$x_importe_iva);
			#	echo "valor unitario".$x_valor_unitario."<br>";
				$x_importe_p = $x_valor_unitario;
			#	echo "importe final".$x_importe_p."<br>";
				}
				$sqlInsertRecibo = "INSERT INTO `factura_pago`";
				$sqlInsertRecibo .= "(`factura_pago_id`, `factura_id`, `recibo_id`, `cantidad`, `unidad`, `descripcion`, `valor_unitario`, `importe`, `impuesto`, `importe_impuesto`, `tasa`)";
				$sqlInsertRecibo .= "VALUES (NULL, $x_factura_id, $x_recibo_id, 1, 'Pago', 'Pagos correspondientes al mes actual',";
				$sqlInsertRecibo .= "$x_valor_unitario, $x_importe_p, '$x_impuesto',$x_importe_iva, $x_tasa);";
				$rsInsertRecibo = phpmkr_query($sqlInsertRecibo, $conn) or die ("Error al insertar en factura_pago". phpmkr_error()."SQL :".$sqlInsertRecibo);
				
				
				###### validar rfc y validar codigo postal......
				
				$sqlInsertCliente = "INSERT INTO `financ13_esf`.`factura_datos`";
				$sqlInsertCliente .= " (`factura_datos_id`, `factura_id`, `cliente_id`, `nombre`, `paterno`, `materno`, `rfc`, `calle`, `numero`,`colonia`,`delegacion`,`estado`,`codigo_postal`)";
				$sqlInsertCliente .= " VALUES(NULL,$x_factura_id,$x_cliente_id,'$x_nombre_completo','$x_apellido_paterno','$x_apellido_materno','$x_rfc','$x_calle',$x_numero_exterior,";
				$sqlInsertCliente .= "'$x_colonia', '$x_delegacion','$x_entidad',$x_codigo_postal);";
				$rsInsertCliente = phpmkr_query($sqlInsertCliente, $conn) or die("Error al insertar los datos del cleinete en la factura".phpmkr_error()."sql:".$sqlInsertCliente);
			
				}else if($x_facturas > 0){
				// la factura ya existe, solo se guardan los datos del pago.
				
				#echo "YA TENEMOS UNA FACTURA REGISTRADA DE ESTE CLIENTE<BR>";
				$sqlFactura_ID = "SELECT factura_id as fac_id FROM factura WHERE fecha_generacion <= '$x_fecha_fin' AND fecha_generacion >= '$x_fecha_inicio' and cliente_id = $x_cliente_id";
				$rsFactura_ID = phpmkr_query($sqlFactura_ID, $conn) or die("Error al seleccionar en factura :".phpmkr_error(). "Query :".$sqlFactura_ID);
				$rowFactura_ID = phpmkr_fetch_array($rsFactura_ID);
				
				$x_factura_id = $rowFactura_ID["fac_id"];
				
				$x_valor_unitario = $x_importe; // corresponde al monto del pago menos el iva que se pago en ese recibo
				$x_importe_p = $x_importe;// es igaul al valor unitario ya que solo es un pago
				
				##SOLO APLICA SI EL CREDITO ES CON IVA
				$x_tasa = 0;
				$x_importe_iva = 0;
				$impuesto = "";
				if($x_iva == 1){
				$x_tasa = 16; // el valor de la tasa de 	IVA
				$x_importe_iva = (($x_tasa /100) * $x_importe); // la cantidad que se pago de iva en el recibo debe corresponder al 16% si es que el credito cobra IVA si no es asi debe ser de 0;
				$x_impuesto = "I.V.A";
				$x_valor_unitario = ($x_importe -$x_importe_iva);
				$x_importe_p = $x_valor_unitario;
				}
				$sqlInsertRecibo = "INSERT INTO `factura_pago`";
				$sqlInsertRecibo .= "(`factura_pago_id`, `factura_id`, `recibo_id`, `cantidad`, `unidad`, `descripcion`, `valor_unitario`, `importe`, `impuesto`, `importe_impuesto`, `tasa`)";
				$sqlInsertRecibo .= "VALUES (NULL, $x_factura_id, $x_recibo_id, 1, 'Pago', 'Pagos correspondientes al mes actual',";
				$sqlInsertRecibo .= "$x_valor_unitario, $x_importe_p, '$x_impuesto',$x_importe_iva, $x_tasa);";
				$rsInsertRecibo = phpmkr_query($sqlInsertRecibo, $conn) or die ("Error al insertar en factura_pago". phpmkr_error()."SQL :".$sqlInsertRecibo);
				
				
				
				}
				
				
				
				
				//$x_cadena_original = generaCadenaOriginal();
				}
				
				
				##ACTUALIZAMOS  LAS FACTURAS EN LOS CAMPOS DE SUBTOTAL Y DE TOTAL
				$sqlCFD = "SELECT * FROM factura WHERE  fecha_generacion <= '$x_fecha_fin' AND fecha_generacion >= '$x_fecha_inicio'";
				$rsCFD = phpmkr_query($sqlCFD, $conn) or die ("Error al seleccionar las facturas". phpmkr_error()."sql :".$sqlCFD);
				while($rowCFD = phpmkr_fetch_array($rsCFD)){
					$x_factura_id = $rowCFD["factura_id"];
					$x_sub_t = 0;
					$x_t = 0;
					$x_t_iva = 0;
					$x_r = 0;
					$x_r_i = 0;
					$sqlPd = "SELECT * FROM factura_pago WHERE factura_id = $x_factura_id";
					$rsPd = phpmkr_query($sqlPd, $conn) or die ("ERROR AL SELECCIONAR PAGOS DE LA FACTURA".php_error()."sql :".$sqlPd);
					while($rowPd = phpmkr_fetch_array($rsPd)){
						
						$x_subtotal_venci = 0;
						$x_subtotal_venci = $rowPd["importe"];						
						$x_sub_iva = 0;
						$x_sub_iva = $rowPd["importe_impuesto"];						
						$x_t_iva =$x_t_iva + $x_sub_iva;
						$x_sub_t = $x_sub_t +$x_subtotal_venci;
						$x_r = 0;
						$x_r = $x_sub_iva + $x_subtotal_venci;						
						$x_t = $x_t +$x_r ;				
						
						}
					$sqlUF = "UPDATE factura set sub_total = $x_sub_t, total = $x_t WHERE factura_id = $x_factura_id";
					$rsUF = phpmkr_query($sqlUF, $conn) or die ("Error al actulizar sub_total y total en factura $x_factura_id ".phpmkr_error()."query:".$sqlUF);					
				
				}//fin while total subtotal
				
				## HASTA AQUI YA SE GAURDARON LOS DATOS DE LA FACTURA EN LAS TABLAS AHORA TOCA GENERAR EL XML Y EL PDF Y GUARDAR LOS ARCHIVOS EN LAS TABLAS CORRESPONDIENTES
				
				$sqlCFD2 = "SELECT * FROM factura WHERE  fecha_generacion <= '$x_fecha_fin' AND fecha_generacion >= '$x_fecha_inicio'";
				$rsCFD2 = phpmkr_query($sqlCFD2, $conn) or die ("Error al seleccionar las facturas". phpmkr_error()."sql :".$sqlCFD2);
				while($rowCFD2 = phpmkr_fetch_array($rsCFD2)){
					$x_factura_id = $rowCFD2["factura_id"];
					echo "factura_id......".$x_factura_id;
					$x_mes = "06";
					$x_anio = "2012";
					$x_cadena_original = generaCadenaOriginal($x_factura_id, $conn, $x_mes, $x_anio);
					#echo "++++++++++++++++++++++++++++CADENA ORIGINAL++++++++++++++++++++++++++++++<BR>".$x_cadena_original."<BR>";
					#$cadena_firmada = "";
					
					#$pkeyid = openssl_get_privatekey($priv_key);	
					#echo "llave privada --------->".$pkeyid."<br>";
  					#openssl_sign($cadena_original, $cadena_firmada, $pkeyid, OPENSSL_ALGO_SHA1); la version del servidor no soporta los 4 parametros
					#openssl_sign($x_cadena_original, $x_cadena_firmada, $pkeyid);					
					#openssl_free_key($pkeyid);
					#$x_sello = base64_encode($x_cadena_firmada);
					#echo "+++++++++++++++++++++++++++++++++++SELLO++++++++++++++++++++++++++++++++++<BR>".$x_sello."<BR>";
					// YA TENEMOS LA CADENA ORIGINAL FIRMADA, AHORA GENERAMOS EL XML
					$x_fact = generaXmlNew($x_factura_id, $conn);
					//$x_xml = generaXml($x_factura_id, $x_sello, $certificado_texto ,$conn);
					#$x_pdf = generaPDF($x_factura_id, $conn,$x_cadena_original, $certificado_texto);
					if($x_xml){
						#echo "______________SE GENERO EL XML_______________".$x_xml."<BR>";
						}
				
					}// while facturas	 cfd


	 
	 
	 
 function generaImpresion($x_factura_id){
	 
	 
	 
	 
	 
	 return $x_impresion;
	 }	 
?>