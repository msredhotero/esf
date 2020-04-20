<?php
include_once('Forma.class.php');
/**
 * 
 */
class FormularioSolicitud extends Forma
{
	private $fecha ='';

	private function getFecha()
	{		
		return $this->fecha;
	}


	private function getFechaLetras()
	{	$fechaLetras ='';	
		$servicios = new Servicios();
		$fechaContrato = (!empty($this->get_value('fecha_registro')))?$this->get_value('fecha_registro'):$this->getFecha();
		$fechaLetras = $servicios->obtenerFechaEnLetra($fechaContrato);
		return $fechaLetras;
	}
	
	private function getNombreusuario()
	{
		$usuario = new Usuario();
		$nombre = $usuario->getNombre();
		$nombreContratoGlobal = $this->get_value('nombre')." ". $this->get_value('paterno')." ".$this->get_value('materno');

		$nombre = ($nombreContratoGlobal !="  ") ?$nombreContratoGlobal :$usuario->getNombre();

		return strtoupper($nombre);
	}
	public function __construct()
	{

		#echo "nombre ".$this->get_value('nombre');
		$this->fecha = (!empty($this->get_value('fecha_registro')))?$this->get_value('fecha_registro'): date("Y-m-d");
		$content = array(
			$this->section(true, array(
				$this->input_hidden('idprueba2'),
		 		$this->input_hidden('idprueba1'),	
			)),
			$this->section(true, array(



			)),

		);// content
	}


	public function  formaDocumentos(){
		
		$serviciosSol = new ServiciosSolicitudes();
		$documetosSolicitados =  $serviciosSol->buscarTipoDoctos();
		$arrayFiles = array();
		$titulo = 'Atención!' ;
		$mensaje = 'Antes de adjuntar los documentos por favor verifique que cumplen los requisitos establecidos para cada tipo de documento, como la vigencia o datos que debe contener el documento';
		foreach ($documetosSolicitados as $idDoc => $detalle) {			
			$requerido =$detalle['requerio'];
			
			$arrayFiles[] = $this->input_file($idDoc,  array('req'=>$detalle['requerio'], 'det'=>$detalle['especificaciones'], 'desc'=>$detalle['documento']));

			# code...
		}
			$arrayFiles[] = array('tag'=>'br');
			$arrayFiles[] = array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
					array('tag'=>'button', 'id'=>'btn_guardar', 'value'=>'Guardar', 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right', 'inside'=>array('Guardar')),
				));

		
		
		$content = array();
		$content[] = array('tag'=>'div', 'inside'=>array(
					array('tag'=>'hidden', 'name'=>'refcontratoglobal', 'id'=>'refcontratoglobal'),
				));
		$content[] = array(
					'tag' => 'div',
					'id' => 'doctos',
					'inside' => array(					
						$this->section(true, $arrayFiles)
					)
				);


		return $content;
	}


	public function formaContratoGlobal(){

		$content = array(					
				
				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'En dondé trabajas?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refempresaafiliada', array('cat_nombre'=>'tbempresaafiliada','id_cat'=>'idempresaafiliada')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),
		 		
		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),

		 		$this->title_seccion('Datos personales'),

		 		

			 	$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Nombre completo'),
		 			$this->form_column(4,'', array(
			 		
			 			$this->input_text('nombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('paterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('materno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	)),


			 	

			 	$this->form_group(array(	
		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_date('fechanacimiento'),
			 			$this->input_help('Fecha de nacimiento',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_select('refnacionalidad', array('cat_nombre'=>'nacionalidad','id_cat'=>'nacionalidad_id', 'descripcion'=>'pais_nombre')), 
			 			$this->input_help('País de nacimiento',true),
			 		)),

			 		$this->form_column(3,1, array(
			 			$this->input_select('refentidadnacimiento', array('cat_nombre'=>'entidad_nacimiento','id_cat'=>'entidad_nacimiento_id')), 
			 			$this->input_help('Estado',true),
			 		)),
			 		

			 		
			 	
			 	)),

			 	$this->form_group(array(	
		 			
		 			$this->form_column(2,'', array(
			 			$this->input_select('refgenero', array('cat_nombre'=>'tbgenero','id_cat'=>'idgenero')), 
			 			
			 			$this->input_help('Genero',true),
			 		)),

			 		$this->form_column(3,2, array(
			 			$this->input_text('rfc'),
			 			$this->input_help('RFC',true),
			 		)),

			 		$this->form_column(4,1, array(
			 			$this->input_text('curp'),
			 			$this->input_help('CURP',true),
			 		)),
			 	
			 	)),


			 	$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Datos del conyugue'),
		 			$this->form_column(4,'', array(
			 		
			 			$this->input_text('cnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cpaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	)),
			 	$this->title_seccion('Domicilio'),
		 							
				$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Dirección'),
		 			$this->form_column(8,'', array(
			 		
			 			$this->input_text('calle'),
			 			$this->input_help('Calle',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numeroexterior'),
			 			$this->input_help('Número exterior',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numerointerior'),
			 			$this->input_help('Número interior',true),
			 		)),

			 		
			 	
			 	)),


			 	$this->form_group(array(	
		 			
		 			$this->form_column(6,'', array(
			 		
			 			$this->input_text('colonia'),
			 			$this->input_help('Colonia',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('codigopostal'),
			 			$this->input_help('C.P.',true),
			 		)),
			 	
			 	)),

				$this->form_group(array(	
		 			$this->form_column(3, '', array(
			 			$this->input_select('refentidad', array('cat_nombre'=>'inegi2020_estado','id_cat'=>'estado_id')), 
			 			$this->input_help('Estado',true),
			 		)),

			 		$this->form_column(3, 1, array(			 			 
			 			$this->input_select('refmunicipio', array('cat_nombre'=>'inegi2020_municipio','id_cat'=>'municipio_id', 'filtros'=>array(
			 					array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
			 			))), 
			 			$this->input_help('Municipio',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('reflocalidad', array('cat_nombre'=>'inegi2020_localidad','id_cat'=>'localidad_id', 'filtros'=>array(
                            array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
                            array('field'=>'refmunicipio', 'value'=>$this->get_value('refmunicipio')),
                        ))), 
			 			$this->input_help('Localidad',true),
			 		)),
			 	
			 	)),

$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Telefonos'),
		 			$this->form_column(3,'', array(			 		
			 			$this->input_text('telefono1'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('reftipotelefono1', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_text('celular1'),
			 			$this->input_help('Celular',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('refcompania1', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id','descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),





			 	
			 	)),

				$this->form_group(array(	
		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_text('telefono2'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('reftipotelefono2', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_text('celular2'),
			 			$this->input_help('Celular',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('refcompania2', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id', 'descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),			 	
			 	)),
				$this->title_seccion('Persona Polícamente Expuesta (PPE)'),

				$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, '   A) ¿Usted desempeña o ha desempeñado funciones públicas destacadas en un país extranjero o en territorio nacional, como son, entre otros, jefes de estado o de gobierno, líderes políticos, funcionarios gubernamentales, judiciales o militares de alta jerarquía, altos ejecutivos de empresas estatales o funcionarios o miembros importantes de partidos políticos?'),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublico', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Cargo público',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, 'B) ¿Usted es cónyuge o tiene parentesco por consanguinidad o afinidad hasta el segundo grado con personas que caen en el supuesto de la pregunta anterior? '),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublicofamiliar', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Familiar con cargo público',true),
			 		)),
		 		)),

			 	$this->title_seccion('Historial créditicio'),

			 	$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con algun crédito hipotecario?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditohipotecario', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),

				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Ha ejercido en los últimos 2 años algún crédito automotriz?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditoautomotriz', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con alguna tarjeta de crédito?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('tarjetacredito', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),

			 		$this->form_column(2, '', array(
			 			$this->input_number('digitostarjeta'),			 			
			 			$this->input_help('Últimos 4 digitos',true),
			 		)),
		 		)),


		 		array('tag'=>'br', 'class'=>'','inside'=>array()),

		 		$this->form_group(array(
		 			
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('burocredito', '<small class="text-muted text-justify">Hoy siendo '.$this->getFechaLetras().', <span class="nombreClienteAutoriza">'.$this->getNombreusuario().'</span> autoriza a  <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b>  a consultar sus antecedentes crediticios por única ocasión ante las Sociedades de Información Crediticia que estime conveniente, declarando que conoce la naturaleza, alcance y uso que <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b> hará de tal información.</small> ', '', '')
			 		)),

			 		
		 		)),
				
				array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
					array('tag'=>'button', 'id'=>'btn_guardar', 'value'=>'Guardar', 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right', 'inside'=>array('Guardar')),
				))
				

					
				);

		return $content;
	}


	public function wizardSolicicitud(){
		$title_seccion = array('zulma');
		$content =  array(

			array('tag'=>'div', 'class'=>'f1-steps', 'inside'=>array(
				array('tag'=>'div', 'class'=>'f1-progress', 'inside'=>array(
					array('tag'=>'div', 'class'=>'f1-progress-line', 'data-now-value'=>'16.66','data-number-of-steps'=>'3', 'style'=>'width: 16.66%;', 'inside'=>array())
				)),
				$this->f1_step(true,'fa-user','Datos personales'),
				$this->f1_step('','fa-key','Cuenta'),
				$this->f1_step('','fa-twitter','Redes sociales'),	
			)),


			// $this->f1_steps('', '', '', array(
			// 	$this->f1_step(true,'fa-user','Datos personales'),
			// 	$this->f1_step('','fa-key','Cuenta'),
			// 	$this->f1_step('','fa-twitter','Redes sociales'),				
			// )),

			
			$this->f1_fieldset('Datos personales', array(
				$this->form_group(array(
		 			$this->form_label(3,'', true, 'Catalogo'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('credito_tipo_id', array('cat_nombre'=>'credito_tipo','id_cat'=>'credito_tipo_id')), 
			 			$this->input_help('Mes',true),
			 		)),
		 		)),
		 		$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),
		 		$this->f1_buttons(true, false),
			)),

			$this->f1_fieldset('Datos personales 2', array(
				$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),

			 	$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),
			 	$this->f1_buttons(true, true,false, 'nuevaSolicitud'),
			)),		


			$this->f1_fieldset('Datos personales 2', array(
				$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),

			 	$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),
			 	$this->f1_buttons(false, true,true, 'nuevaSolicitud'),
			)),		
		);

		

		return $content;


	}

	public function altaSolicitud(){
		$content =  array(		
		 $this->section(true, array(
		 	$this->input_hidden('idprueba2'),
		 	$this->input_hidden('idprueba1'),
		 )),
		 $this->section(true, array(
		 	
		 	$this->form_group(array(
		 		$this->form_label(3,'', true, 'Catalogo'),
		 		$this->form_column(3, '', array(
			 		$this->input_select('credito_tipo_id', array('cat_nombre'=>'credito_tipo','id_cat'=>'credito_tipo_id')), 
			 			$this->input_help('Mes',true),
			 		)),
		 	)),

		 	$this->title_seccion('Datos personales del cliente'),
		 	$this->form_group(array(		 	
		 		$this->row_col_label(4,'Nombre','idprueba1',array(
		 			$this->input_text('nombre')
		 		)),
		 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
		 			$this->input_text('apellidopaterno')
		 		)),
		 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
		 			$this->input_text('apellidomaterno')
		 		)),
		 		
		 	)),


		 	$this->form_group(array(		 	
		 		
		 		$this->row_col_label(3,'Tipo Cliente','reftipocredito',array(
		 			$this->input_select('credito_tipo_id', array('cat_nombre'=>'credito_tipo','id_cat'=>'credito_tipo_id')), 	
		 		)),
		 	
		 	$this->footer_form( array(
		 		array('tag'=>'button','class'=>'btn btn-primary waves-effect nuevaSolicitud', 'type'=>'submit' ,'inside'=>array('Guardar') ),
		 		array('tag'=>'button','class'=>'btn btn-primary waves-effect editarSolicitud', 'type'=>'submit' ,'inside'=>array('Editar')),

		 	
		 	)),	
		 	)),
		 )),// seccion
		);

		return $content;
	}

	public function cargaDoctos(){

	}

	public function aceptapaquete(){

	}

	public function generaNip(){

	}

	public function buroCredito(){

	}
}


?>