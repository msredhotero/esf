<?php
//header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');



class ServiciosSolicitudes {
	private $idSolicitud; 
	private $idCliente;
	private $idUsuario;
	private $idDocumentos;
	private $idReferencias;
	private $datos;
	private $idContratoGlobal;
	private $refcontratoglobal;
	private $lectura ;

	public function setSolcitudId($idSolicitud)
	{
		$this->idSolicitud = $IdSolicitud;
	}

	public function setContratoGlobalId($idContratoGlobal)
	{
		$this->idContratoGlobal = $idContratoGlobal;
		$this->refcontratoglobal = $idContratoGlobal;
	}


	public function setDatos($datos){
		$this->datos = $datos;		
	}

	public function getDatos(){
		return $this->datos;
	}

	public function getLectura(){
		return $this->lectura;
	}

	public function setLectura($lectura){
		 $this->lectura = $lectura;

	}


	/**
	 * @return mixed
	 */
	public function getDato($campo)
	{
		return $this->datos->$campo;
	}
	
	/**
	 * @param mixed $datos
	 */
	public function setDato($campo, $dato)
	{
		$this->datos->$campo = $dato;
	}

	function __construct($idContratoGlobal = NULL){
		 $query = new Query();
		 if(!empty($idContratoGlobal)){		 	
		 	$this->setContratoGlobalId($idContratoGlobal);	 	
		 	$this->refcontratoglobal = $idContratoGlobal;
		 }else{
		 	$usuario = new Usuario();
			$usuario_id = $usuario->getUsuarioId();	
			$whereT = "	usuario_id = ".$usuario_id;
			if(!empty($usuario_id)){
				$idContratoGlobal = $query->selectCampo('idcontratoglobal','dbcontratosglobales',$whereT );
				$this->setContratoGlobalId($idContratoGlobal);
				$this->refcontratoglobal = $idContratoGlobal;
			}
		 }

		 if(!empty($this->idContratoGlobal)){		 
		 echo "carga permisos =>".$this->cargarPermisos($this->idContratoGlobal);	
		 	$this->setLectura($this->cargarPermisos($this->idContratoGlobal));		 	
		 }else{		 	
		 	$this->setLectura(false);		 	
		 }	
		  

		 // cagar campos que no sean de solicitudes
	}

	public function cargarPermisos($idContratoGlobal){
		$lectura = true;
		$query  = new Query();
		$usuario = new Usuario();

		$sqlPermisos = "SELECT max(dbcgp.`refproceso`) AS ultimo_proceso, dbcgp.refusuario, p.descripcion, p.roles AS usuarios_edicion FROM `dbcontratosglobalesprocesos` AS dbcgp JOIN tbproceso AS p ON p.idproceso = dbcgp.refproceso WHERE `refcontratoglobal` = ".$idContratoGlobal;
		$query->setQuery($sqlPermisos);
		$resPermisos = $query->eject();
		$permisos = $resPermisos->fetch_object();
		$usuariosEdicion = $permisos->usuarios_edicion;		
		$arrayUsers = explode(',', $usuariosEdicion);		
		$lectura = !in_array($usuario->getRolId(), $arrayUsers);

		echo "Lectrua en funcion=>".$lectura;
		return $lectura;		
	}

	public function cargarDoctosContratoGlobal($idContratoGlogal){
		// aqui buscamos los documentos del contrato global
		$query = new Query();
		if(!empty($idContratoGlobal)){
			// se se llecionan los archivos del contrato
			$wh = " refcontratoglobal  = ".$idContratoGlogal;
			$sqlDoctos = " SELECT * FROM dbcontratosglobalesdocumentos".$wh;
			$query->setQuery($sqlDoctos);
			$rsDoctos = $query->eject();


		}

	}

	public function subirDocumentosSolicitudGlobal(){
		$query = new Query();
		$serviciosUsuarios = new ServiciosUsuarios();
		$usuario = new Usuario();
		$emailUsuario = $usuario->getUsuario();
		$idContratoGlobal = (!empty($idContratoG))?$idContratoG:$this->traercontratoGlobalId();
		$tipoPermitido = array("jpg" => "image/jpg","JPG" => "image/jpg", "JPEG" => "image/jpeg","jpeg" => "image/jpeg", "pdf" => "application/pdf");
		$error = '';
		$_POST['refcontratoglobal'] = $this->traercontratoGlobalId();
		$directorioCarga = "../upload/".$idContratoGlobal."/";

		$cuerpoMail .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
    	$cuerpoMail .= '<h2 class=\"p3\"> DOCUMENTOS RECIBIDOS</h2>';
    	$servidor = $_SERVER['SERVER_NAME'];
    	$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
   		$cuerpoMail .= '<h3><small><p>Hemos recibido sus documentos, en breve nos comunicaremos con usted, por favor espere nuestra llamada. </p></small></h3>';
   		$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';


		if (!file_exists($directorioCarga)) {
		   if(!mkdir($directorioCarga, 0777, true)){
		   	$error .= "Error al crear la carpeta destino";
		   }
		}

		
		foreach ($_FILES as $file => $filevalues) {
			// para cada archivo insertamos en base de datos y movemos el temporal a la nueva ruta
			# code...
			$nuevoNombreFile =  $idContratoGlobal."_".$this->nombreArchivo($file);	
			if($filevalues["error"] != 4){		
				if( $filevalues["error"] == 0  ){
					$filename = $filevalues["name"];
	        		$filetype = $filevalues["type"];
	        		$filesize = $filevalues["size"];      		

					 $ext = pathinfo($filename, PATHINFO_EXTENSION);
					 $nuevoNombreFile = $nuevoNombreFile.".".$ext;
					 if(!array_key_exists($ext, $tipoPermitido)) 
					 	$error .= "Error: tipo de archivo incorrecto " .$this->nombreArchivo($file);
						// Verificar MYME tipo de archivo
				        if(in_array($filetype, $tipoPermitido)){
				            // verificamos is ya existe el archivo
				            if(file_exists($directorioCarga.$filename)){
				                $error .= " ". $filename . " el archivo ya existe. \n <br>";
				                // aqui opcion para sobre escribir, si fuera el caso
				            } else{
				                if(!move_uploaded_file($filevalues["tmp_name"], $directorioCarga. $nuevoNombreFile)){
				                	$error .= " ". "Error al subir el archivo= \n <br>";
				                }else{
				                	// el archivo se cargo correctamente alservidor se inserta el registro en la base de datos
				                	$sqlIsertFile = "INSERT INTO `dbcontratosglobalesdocumentos` (`idcontratoglobaldocumento`, `refcontratoglobal`, `refdocumento`, `nombre`, `ruta`) ";
				                	$sqlIsertFile .= " VALUES (NULL, $idContratoGlobal , $file, '".$nuevoNombreFile."', '".$directorioCarga."'); ";
				                	$query->setQuery($sqlIsertFile);
				                	$query->eject(1);
				                		                	
				                }			               
				            } 
				        } else{
				            $error .= "Error: error al cargar tu archivo por favor intenta nuevamente.".$this->nombreArchivo($file); 
				        }
			    } else{
			        
			        if($filevalues["error"] == 1) 
		        		$error .= "Error: " . $filevalues["error"]." El fichero seleccionado excede el tamaño máximo permitido";
			        if($filevalues["error"] == 2) 
		        		$error .= "Error: " . $filevalues["error"]." El archivo subido excede la directiva MAX_FILE_SIZE,";
			        if($filevalues["error"] == 3) 
		        		$error .= "Error: " . $filevalues["error"]." El archivo subido fue sólo parcialmente cargado.";
			         if($filevalues["error"] == 6) 
		        		$error .= "Error: " . $filevalues["error"]." Falta el directorio de almacenamiento temporal.";
			         if($filevalues["error"] == 7) 
		         		$error .= "Error: " . $filevalues["error"]." No se puede escribir el archivo (posible problema relacionado con los permisos de escritura)";
			      	 if($filevalues["error"] == 8) 
		    	 		$error .= "Error: " . $filevalues["error"]." Una extensión PHP detuvo la subida del archivo";

			    }
			}

		} // foreach
		if($error==''){
			// enviamos correo al usauurio
			$serviciosUsuarios->enviarEmail($emailUsuario,'Recepción de documentos',utf8_decode($cuerpoMail));	
		}

		return $error;

	}

	public function traercontratoGlobalId(){
		$usuario = new Usuario();
		$query = new Query();
		$usuario_id = $usuario->getUsuarioId();
		$condicion =  " usuario_id =".$usuario_id;
		$contratoGlobalId = $query->selectCampo('idcontratoglobal', 'dbcontratosglobales', $condicion );
		return $contratoGlobalId ;
	}

	public function nombreArchivo($idArchivo){
		$query = new Query();
		$condicion = "	iddocumento = ".$idArchivo."";
		$nombreArchivo = $query->selectCampo('nombre_archivo', 'tbdocumento', $condicion );
		return $nombreArchivo;
	}

	public function buscarTipoDoctos(){
		$arrDoctos = array();
		$query = new Query();
		$usuario = new Usuario();
		$rol = $usuario->getRolId();
		
		$responsable = ($rol == 8 || $rol == 1)?1:2; // 1=cliente, 2= financiera, 3 = empresa_afiliada 
		$this->cargarDatosContratoGlobal();
		$empresaAfiliada = $this->getDato('refempresaafiliada');
		
		if(!empty($empresaAfiliada)){
			$wr .= ' WHERE `idempresaafiliada` = '.$empresaAfiliada;
			if(!empty($responsable))
			$wr .= ' AND `responsable` = '.$responsable; 
			$sqlDoctos = "SELECT * FROM  vista_empresa_afialida_documentos ".$wr;			
			$query->setQuery($sqlDoctos);
			$rs = $query->eject();
			while($rowDoctos = $rs->fetch_array(MYSQLI_ASSOC)){	
					$arrDoctos[$rowDoctos['iddocumento']] = array('documento'=> $rowDoctos['documento'],
																  'especificaciones'=> $rowDoctos['especificaciones'],
																  'requerio'=> $rowDoctos['requerio'],
																  'responsable'=> $rowDoctos['responsable']
																 );
			}	
		}
		#documetosSolicitados
		return $arrDoctos;

	}
	

	public function cargarDatosContratoGlobal($idContratoG = NULL){
		// cargamos todos los datos de la solicictud de credito
		$query = new Query();
		$datosSolicitud = array();
		$datosConsulta = array(); // aqui guardaremos los datos de las tabla
	

		//$this->idSolicitud =6;
		if(!empty($this->idContratoGlobal) ){
			// si la solicitud ya existe buscamos los datos
			$wr .= ' WHERE `idcontratoglobal` = \''.$this->idContratoGlobal.'\'';
			$qSol = "SELECT * FROM dbcontratosglobales ".$wr;
		
			$query->setQuery($qSol);
			$rs = $query->eject();
			$rw = $rs->fetch_object();
			foreach ($rw as $campo => $valor){
				if(is_null($valor)){
					$valor = '';
				}
				$datosConsulta[$campo] = $valor;	
			}
		


			$arrayDoctos = array();
			// veificamos si ya existen los documentos
			$wr = ' WHERE `refcontratoglobal` = \''.$this->idContratoGlobal.'\'';
			$sqlDoctos = "SELECT * FROM dbcontratosglobalesdocumentos ".$wr;
			$query->setQuery($sqlDoctos);
			$rs2 = $query->eject();
			$rw2= $rs2->fetch_all(MYSQLI_ASSOC);
			$archivo = 0;
			foreach ($rw2 as $renglon => $campos){
				foreach ($campos as $nombre => $valor) {				
					if($nombre =='refdocumento'){
						$archivo = $valor;
					}
					if($nombre =='nombre'){
						if(is_null($valor)){
							$valor = '';
						}
						$arrayDoctos[$archivo] = $valor;
					}	
				}				
			}

			foreach ($arrayDoctos as $campo => $valor) {
				$datosConsulta["documento_".$campo] = $valor;				
			}

		}// contratoGlobal			
			
		// asignamos los valores de la consulta a las propiedades de la clase		
		$this->datos = (object)$datosConsulta;
		
	}


	

	


	
	
	

	function traerCamposSolicitudCliente(){
		$tabla = 'dbSolicitudes';		
		$renglones = '';
		$forma =  new ServiciosForma();
		
		#$r1c1 =  $forma->columnaTabla($tabla,$refdescripcion,$refCampo,'reftipocredito' , 6);
		$r1c1 =  $forma->columnaTabla($tabla,'reftipocredito' , 6, 'tbtiposcredito');
		$r1c2 =  $forma->columnaTabla($tabla,'reftipocreditoer' , 6, 'tbtiposcredito' );		
		$camposR1 = array($r1c1,$r1c2);
		$renglones .= $forma->generaFormGroup($r1c1);
		$renglones .= $forma->generaFormGroup($r1c1);
		$renglones .= $forma->generaFormGroup($camposR1);
		$renglones .= $forma->generaFormGroupEmcabezado('Datos personales');
		return $renglones;

	}

	function traerCamposSolicitudClientePersonales(){
		$tabla = 'dbclientes';		
		$renglones = '';
		$forma =  new ServiciosForma();
		
		#$r1c1 =  $forma->columnaTabla($tabla,$refdescripcion,$refCampo,'reftipocredito' , 6);
		$formulario .= $forma->generaFormGroupEmcabezado('Datos personales');
		$r1c1 =  $forma->columnaTabla($tabla,'nombre' , 4,'');
		$r1c2 =  $forma->columnaTabla($tabla,'apellidopaterno',4,'');	
		$r1c3 =  $forma->columnaTabla($tabla,'apellidomaterno',4,'');	
		$camposR1 = array($r1c1,$r1c2,$r1c3);
		
		$renglones .= $forma->generaFormGroup($camposR1);
		
		return $renglones;

	}






	function traerDatosCatalogo($tabla){
		$sql = "SELECT * FROM ".$tabla." WHERE 1";		
		$res = $this->query($sql,0);

		if ($res == false) {
			return 'Error al traer datos';
		} else {
			return $res;
		}
	}

	function insertarSolicitudGlobal(){
		
		$msg = array();
		$msg['error'] = '';		
		$query = new Query();
		$usuario = new Usuario();
		$errorEnTrasaccion = '';
		$tabla = 'dbcontratosglobales';
		$_POST['usuario_id'] = $usuario->getUsuarioId();
		$_POST['fecha_registro'] =  date('Y-m.d');		
		$valuesSolicitud = $this->traercamposValoresPost($tabla);
		
		// iniciamos la transaccion
		// si hay errror en algun query ejecutamos el rollback
		// si todas estan bien ejecuatamos el commit
		$query->beginTrans();			
		$query->insert($tabla,$valuesSolicitud);
		$rs = $query->ejectTrans(1);		
		if(!$rs){
			$errorEnTrasaccion = 1; 
			$query->rollbackTrans();			
			echo "<br>ERROR EN SOLICITUD:</br> ";
			$msg['error'] = 1;	
			$msg2  .='"error":1';		
		}else{
			$idSolicitudNueva = $rs;
			$msg['IdSolicitud'] = $idSolicitudNueva;
			$msg2  .='"IdSolicitud":'.$idSolicitudNueva;				
		}

		$sqlUpdateHora = " UPDATE ".$tabla." SET hora_registro = now() WHERE idcontratoglobal =". $idSolicitudNueva;
		$query->setQuery($sqlUpdateHora);
		$rs = $query->ejectTrans(0);		
		if(!$rs){			
			$errorEnTrasaccion = 1;
			$rb = $query->rollbackTrans();
			echo "<br>ERROR EN UPDATE:</br> ";	
			$msg['error'] = 1;
						
		}else{
			$idClienteNuevo = $rs;							
		}
			
		if(!$errorEnTrasaccion){
			// ningun error en los queries
			$query->commitTrans();
		}	
		
		return 	json_encode($msg) ;
		//return $errorEnTrasaccion;
	}


	function editarSolicitudGlobal(){
		
		$msg = array();
		$msg['error'] = '';		
		$query = new Query();
		$errorEnTrasaccion = '';
		$tablaSol = 'dbcontratosglobales';
		$valuesSolGlobal = $this->traercamposValoresPost($tablaSol);
		
		// iniciamos la transaccion		
		$query->beginTrans();
		$whUpdate = " idcontratoglobal = ".$this->idContratoGlobal;			
		$query->update($tablaSol,$valuesSolGlobal, $whUpdate);
		$rs = $query->ejectTrans();		
		if(!$rs){
			$errorEnTrasaccion = 1; 
			$query->rollbackTrans();			
			echo "<br>ERROR EN SOLICITUD:</br> ".$rs;
			$msg['error'] = 1;				
		}			
		if(!$errorEnTrasaccion){
			// ningun error en los queries
			$query->commitTrans();
		}	
		
		return 	json_encode($msg);
		//return $errorEnTrasaccion;
	}

	public function insert($table, $values){
		$values = $this->clear($values);
		
		$query = '';
		$query .= 'INSERT INTO `'.$table.'` ';
		$query .= ' (`'.implode('`, `', array_keys($values)).'`) VALUE';
		$query .= ' ('.implode(', ', $values).');';
		
		$this->setQuery($query);
	}
	

	function traercamposValoresPost($tabla){
		$values = array();
		$query = new Query();			
		$q_campos = 'SHOW COLUMNS FROM `'.$tabla.'`';
		
		$query->setQuery($q_campos);
		$rs_campos = $query->eject();		
		while($rw_campos = $rs_campos->fetch_array(MYSQLI_ASSOC)){			
			$value = '';			
			if(isset($_POST[$rw_campos['Field']])){
				$value = $_POST[$rw_campos['Field']];
				$values[$rw_campos['Field']] = $value;
			}			
			
		}		
		return $values;
	}


	function query($sql,$accion) {

		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];


		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());

		mysql_select_db($database);
		mysql_query("SET NAMES 'utf8'");
		        $error = 0;
		mysql_query("BEGIN");
		$result=mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysql_query("ROLLBACK");
			return false;
		}
		 else{
			mysql_query("COMMIT");
			return $result;
		}

	}
	
	
}
?>