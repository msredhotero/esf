
//validacion para el caso credito individual
function validaFrmCreditoInd(){
		
	EW_this = document.frmAddSolicitud;
	validada = true;

if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}

if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el numero de pagos."))
		validada = false;
}
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}




if (validada == true && EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "Indique su Nombre."))
		validada = false;
}
if (validada == true && EW_this.x_apellido_paterno && !EW_hasValue(EW_this.x_apellido_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno, "TEXT", "Indique su Apellido Paterno."))
		validada = false;
}

if (validada == true && EW_this.x_tipo_negocio && !EW_hasValue(EW_this.x_tipo_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_negocio, "TEXT", "Indique el tipo de su negocio."))
		validada = false;
}
if (validada == true && EW_this.x_edad && !EW_hasValue(EW_this.x_edad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "Indique su Edad."))
		validada = false;
}
if (validada == true && EW_this.x_edad && !EW_checkinteger(EW_this.x_edad.value)) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "Su Edad es incorrecta, por favor verifiquela."))
		validada = false;
}
if (validada == true && EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "RADIO" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "RADIO", "Indique su genero."))
		validada = false;
}
if (validada == true && EW_this.x_estado_civil_id && !EW_hasValue(EW_this.x_estado_civil_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_estado_civil_id, "SELECT", "Indique su Estado Civil."))
		validada = false;
}
/*
if (validada == true && EW_this.x_email && !EW_hasValue(EW_this.x_email, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "Indique su Email."))
		validada = false;
}
if (validada == true && EW_this.x_email && !EW_checkemail(EW_this.x_email.value)) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "Su Email es incorrecto, por favor verifiquelo."))
		validada = false;
}
*/



if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio particular."))
		validada = false;
}

if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegación del domicilio particular."))
		validada = false;
}




if (validada == true && EW_this.x_entidad && !EW_hasValue(EW_this.x_entidad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "Indique la entidad del domicilio particular."))

		validada = false;
}
if (validada == true && EW_this.x_codigo_postal && !EW_hasValue(EW_this.x_codigo_postal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "Indique el Código Postal del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion && !EW_hasValue(EW_this.x_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion, "TEXT", "Indique la Ubicación del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad && !EW_hasValue(EW_this.x_antiguedad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "Indique la Antiguedad en el domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad && !EW_checkinteger(EW_this.x_antiguedad.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "La Antiguedad del domicilio particular es incorrecta, por favor veriqfiquela."))
		validada = false;
}
if (validada == true && EW_this.x_vivienda_tipo_id && !EW_hasValue(EW_this.x_vivienda_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_vivienda_tipo_id, "SELECT", "Indique el tipo de vivienda del domicilio particular."))
		validada = false;
}

// validacion para el telefono
if(EW_this.x_telefono.value.length != 0 || EW_this.x_telefono_secundario.value.length != 0 ||
   EW_this.x_telefono_sec.value.length != 0){
	//alguno de los telefono esta llenos
	}else{
		// no se ha llenado ningun telefono...es necesario llenar almenos uno de los 4		
		alert("Por favor introduzca almenos 1 de los telefono listados en la seccion domicilio");
		validada = false;
		
		}



/*

if (validada == true && EW_this.x_calle2 && !EW_hasValue(EW_this.x_calle2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle2, "TEXT", "Indique la calle del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_colonia2 && !EW_hasValue(EW_this.x_colonia2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia2, "TEXT", "Indique la colonia del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_delegacion_id2 && !EW_hasValue(EW_this.x_delegacion_id2, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "SELECT", "Indique la delegación del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_delegacion_id2.value == 17) {
	if (validada == true && EW_this.x_otra_delegacion2 && !EW_hasValue(EW_this.x_otra_delegacion2, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_otra_delegacion2, "TEXT", "Indique la delegación del domicilio de negocio."))
			validada = false;
	}
}

if (validada == true && EW_this.x_entidad2 && !EW_hasValue(EW_this.x_entidad2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad2, "TEXT", "Indique la entidad del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_codigo_postal2 && !EW_hasValue(EW_this.x_codigo_postal2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal2, "TEXT", "Indique el Código Postal del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion2 && !EW_hasValue(EW_this.x_ubicacion2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion2, "TEXT", "Indique la Ubicación del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad2 && !EW_hasValue(EW_this.x_antiguedad2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad2, "TEXT", "Indique la Antiguedad en el domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad2 && !EW_checkinteger(EW_this.x_antiguedad2.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad2, "TEXT", "La Antiguedad del domicilio de negocio es incorrecta, por favor veriqfiquela."))
		validada = false;
}

*/

//if(document.getElementById('aval').className == "TG_visible"){
//validacion datos aval
if(1==2){}



// if(document.getElementById('garantias').className == "TG_visible"){
//validacion datos de la garantia
if(1==2){
	
}



if (validada == true && EW_this.x_ingresos_negocio && !EW_checknumber(EW_this.x_ingresos_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_negocio, "TEXT", "Los ingresos del negocio son incorrectos, por favor verifiquelos."))
		validada = false;
}
if (validada == true && EW_this.x_ingresos_familiar_1 && !EW_checknumber(EW_this.x_ingresos_familiar_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_familiar_1, "TEXT", "Los ingresos familiares 1 son incorrectos, por favor verifiquelos."))
		validada = false;
}

if(EW_this.x_ingresos_familiar_1.value > 0){
	if (validada == true && EW_this.x_parentesco_tipo_id_ing_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ing_1, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ing_1, "SELECT", "Indique el parentesco 1 en ingresos familiares."))
			validada = false;
	}
}

if (validada == true && EW_this.x_ingresos_familiar_2 && !EW_checknumber(EW_this.x_ingresos_familiar_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_familiar_2, "TEXT", "Los ingresos familiares 2 son incorrectos, por favor verifiquelos."))
		validada = false;
}
if(EW_this.x_ingresos_familiar_2.value > 0){
	if (validada == true && EW_this.x_parentesco_tipo_id_ing_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ing_2, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ing_2, "SELECT", "indique el parentesco 2, en ingresos familiares."))
			validada = false;
	}
}
if (validada == true && EW_this.x_otros_ingresos && !EW_checknumber(EW_this.x_otros_ingresos.value)) {
	if (!EW_onError(EW_this, EW_this.x_otros_ingresos, "TEXT", "Los Otros ingresos son incorrectos, por favor verifiquelos."))
		validada = false;
}


//referencias requeridos 2
if((EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_2.value.length != 0)||
	(EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_3.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_4.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_2.value.length != 0 && EW_this.x_nombre_completo_ref_3.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_2.value.length != 0 && EW_this.x_nombre_completo_ref_4.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_2.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_3.value.length != 0 && EW_this.x_nombre_completo_ref_4.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_3.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_4.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0)){
	// almenos dos referencias requeridas
	if (validada == true && EW_this.x_nombre_completo_ref_1 && EW_hasValue(EW_this.x_nombre_completo_ref_1, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_1 && !EW_hasValue(EW_this.x_telefono_ref_1, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_1, "TEXT", "Indique el teléfono de la referencia 1."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_1, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_1, "SELECT", "Indique el parentesco en la referencia 1."))
					validada = false;
			}
				}
				
		if (validada == true && EW_this.x_nombre_completo_ref_2 && EW_hasValue(EW_this.x_nombre_completo_ref_2, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_2 && !EW_hasValue(EW_this.x_telefono_ref_2, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_2, "TEXT", "Indique el teléfono de la referencia 2."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_2, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_2, "SELECT", "Indique el parentesco en la referencia 2."))
					validada = false;
			}
				}
				
				
				if (validada == true && EW_this.x_nombre_completo_ref_3 && EW_hasValue(EW_this.x_nombre_completo_ref_3, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_3 && !EW_hasValue(EW_this.x_telefono_ref_3, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_3, "TEXT", "Indique el teléfono de la referencia 3."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_3 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_3, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_3, "SELECT", "Indique el parentesco en la referencia 3."))
					validada = false;
			}
				}
				
				
		if (validada == true && EW_this.x_nombre_completo_ref_4 && EW_hasValue(EW_this.x_nombre_completo_ref_4, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_4 && !EW_hasValue(EW_this.x_telefono_ref_4, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_4, "TEXT", "Indique el teléfono de la referencia 4."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_4 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_4, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_4, "SELECT", "Indique el parentesco en la referencia 4."))
					validada = false;
			}
				}		
				
			
			if (validada == true && EW_this.x_nombre_completo_ref_5 && EW_hasValue(EW_this.x_nombre_completo_ref_5, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_5 && !EW_hasValue(EW_this.x_telefono_ref_5, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_5, "TEXT", "Indique el teléfono de la referencia 5."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_5 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_5, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_5, "SELECT", "Indique el parentesco en la referencia 5."))
					validada = false;
			}
				}
			
			
				
	}else{
		alert("Debe introducir almenos dos referencias");
		validada = false;
		
		}

/*
if (validada == true && EW_this.x_nombre_completo_ref_1 && !EW_hasValue(EW_this.x_nombre_completo_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo_ref_1, "TEXT", "Indique el Nombre completo de la Referencia 1."))
		validada = false;
}
if (validada == true && EW_this.x_telefono_ref_1 && !EW_hasValue(EW_this.x_telefono_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_telefono_ref_1, "TEXT", "Indique el teléfono de la referencia 1."))
		validada = false;
}
if (validada == true && EW_this.x_parentesco_tipo_id_ref_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_1, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_1, "SELECT", "Indique el parentesco en la referencia 1."))
		validada = false;
}

if (validada == true && EW_this.x_nombre_completo_ref_2 && EW_hasValue(EW_this.x_nombre_completo_ref_2, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_2 && !EW_hasValue(EW_this.x_telefono_ref_2, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_2, "TEXT", "Indique el teléfono de la referencia 2."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_2, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_2, "SELECT", "Indique el parentesco en la referencia 2."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_3 && EW_hasValue(EW_this.x_nombre_completo_ref_3, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_3 && !EW_hasValue(EW_this.x_telefono_ref_3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_3, "TEXT", "Indique el teléfono de la referencia 3."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_3 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_3, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_3, "SELECT", "Indique el parentesco en la referencia 3."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_4 && EW_hasValue(EW_this.x_nombre_completo_ref_4, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_4 && !EW_hasValue(EW_this.x_telefono_ref_4, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_4, "TEXT", "Indique el teléfono de la referencia 4."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_4 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_4, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_4, "SELECT", "Indique el parentesco en la referencia 4."))
			validada = false;
	}
}
if (validada == true && EW_this.x_nombre_completo_ref_5 && EW_hasValue(EW_this.x_nombre_completo_ref_5, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_5 && !EW_hasValue(EW_this.x_telefono_ref_5, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_5, "TEXT", "Indique el teléfono de la referencia 5."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_5 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_5, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_5, "SELECT", "Indique el parentesco en la referencia 5."))
			validada = false;
	}
}*/



/*
if(validada == true && EW_this.x_acepto.checked == false){
	alert("Debe de marcar la casilla: Aceptao los términos y condiciones.");
	validada = false;
}
*/
if(validada == true){
	EW_this.submit();	
}
		
}//fin validacion para el caso credito individual
	
	
	
	// validadcion para el caso credito solidario
 function validaFrmCreditoSol(){
	 
	 
	
	 
	 EW_this = document.frmAddSolicitud;
	  validada = true;
	  
	  
	  //agregar las validacione para el tipo de rol del integrante de grupo
	  
if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}

if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el numero de pagos solicitado."))
		validada = false;
}
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}	  
	  
	  
	 
	  
//validadcion para los roles de los integrantes

	
	/*if (EW_this.x_integrante_1 && EW_hasValue(EW_this.x_integrante_1, "TEXT" )) {		
	if (EW_this.x_rol_integrante_1 && !EW_hasValue(EW_this.x_rol_integrante_1, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_1, "SELECT", "El rol de integrante 1 es requerido"))
		validada = false;
	}}
	
	if (EW_this.x_integrante_2 && EW_hasValue(EW_this.x_integrante_2, "TEXT" )) {		
	if (EW_this.x_rol_integrante_2 && !EW_hasValue(EW_this.x_rol_integrante_2, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_2, "SELECT", "El rol de integrante 2 es requerido"))
		validada = false;
	}}
		
		if (EW_this.x_integrante_3 && EW_hasValue(EW_this.x_integrante_3, "TEXT" )) {		
	if (EW_this.x_rol_integrante_3 && !EW_hasValue(EW_this.x_rol_integrante_3, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_3, "SELECT", "El rol de integrante 3 es requerido"))
		validada = false;
	}}
	
	if (EW_this.x_integrante_4 && EW_hasValue(EW_this.x_integrante_4, "TEXT" )) {		
	if (EW_this.x_rol_integrante_4 && !EW_hasValue(EW_this.x_rol_integrante_4, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_4, "SELECT", "El rol de integrante 4 es requerido"))
		validada = false;
		
	}}
		
		
	if (EW_this.x_integrante_5 && EW_hasValue(EW_this.x_integrante_5, "TEXT" )) {		
	if (EW_this.x_rol_integrante_5 && !EW_hasValue(EW_this.x_rol_integrante_5, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_5, "SELECT", "El rol de integrante 5 es requerido"))
		validada = false;
	}}
	 
	 
	if (EW_this.x_integrante_6 && EW_hasValue(EW_this.x_integrante_6, "TEXT" )) {		
	if (EW_this.x_rol_integrante_6 && !EW_hasValue(EW_this.x_rol_integrante_6, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_6, "SELECT", "El rol de integrante 6 es requerido"))
		validada = false;
	 
	}}
	 
	 
	if (EW_this.x_integrante_7 && EW_hasValue(EW_this.x_integrante_7, "TEXT" )) {		
	if (EW_this.x_rol_integrante_7 && !EW_hasValue(EW_this.x_rol_integrante_7, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_7, "SELECT", "El rol de integrante 7 es requerido"))
		validada = false;
	 
	}}
	 
	if (EW_this.x_integrante_8 && EW_hasValue(EW_this.x_integrante_8, "TEXT" )) {		
	if (EW_this.x_rol_integrante_8 && !EW_hasValue(EW_this.x_rol_integrante_8, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_8, "SELECT", "El rol de integrante 8 es requerido"))
		validada = false;
		
	}}
		 
	if (EW_this.x_integrante_9 && EW_hasValue(EW_this.x_integrante_9, "TEXT" )) {		
	if (EW_this.x_rol_integrante_9 && !EW_hasValue(EW_this.x_rol_integrante_9, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_9, "SELECT", "El rol de integrante 9 es requerido"))
		validada = false;
	}}
	 
	 
	if (EW_this.x_integrante_10 && EW_hasValue(EW_this.x_integrante_10, "TEXT" )) {		
	if (EW_this.x_rol_integrante_10 && !EW_hasValue(EW_this.x_rol_integrante_10, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_10, "SELECT", "El rol de integrante 10 es requerido"))
		validada = false;
		
	}}*/
		
	//roles y montos de los integranes de los integrantes
//INTEGRANTE UNO
if (validada == true && EW_this.x_integrante_1 && EW_hasValue(EW_this.x_integrante_1, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_1 && !EW_hasValue(EW_this.x_monto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_1, "TEXT", "Indique el monto del integrante 1."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_1 && !EW_hasValue(EW_this.x_rol_integrante_1, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_1, "SELECT", "Indique el rol del integrante 1."))
		validada = false;
}
}//fin integrante 1
//INTEGRANTE2DOS
if (validada == true && EW_this.x_integrante_2 && EW_hasValue(EW_this.x_integrante_2, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_2 && !EW_hasValue(EW_this.x_monto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_2, "TEXT", "Indique el monto del integrante 2."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_2 && !EW_hasValue(EW_this.x_rol_integrante_2, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_2, "SELECT", "Indique el rol del integrante 2."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE TRES
if (validada == true && EW_this.x_integrante_3 && EW_hasValue(EW_this.x_integrante_3, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_3 && !EW_hasValue(EW_this.x_monto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_3, "TEXT", "Indique el monto del integrante 3."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_3 && !EW_hasValue(EW_this.x_rol_integrante_3, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_3, "SELECT", "Indique el rol del integrante 3."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE CUATRO
if (validada == true && EW_this.x_integrante_4 && EW_hasValue(EW_this.x_integrante_4, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_4 && !EW_hasValue(EW_this.x_monto_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_4, "TEXT", "Indique el monto del integrante 4."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_4 && !EW_hasValue(EW_this.x_rol_integrante_4, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_4, "SELECT", "Indique el rol del integrante 4."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE CINCO
if (validada == true && EW_this.x_integrante_5 && EW_hasValue(EW_this.x_integrante_5, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_5 && !EW_hasValue(EW_this.x_monto_5, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_5, "TEXT", "Indique el monto del integrante 5."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_5 && !EW_hasValue(EW_this.x_rol_integrante_5, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_5, "SELECT", "Indique el rol del integrante 5."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE SEIS
if (validada == true && EW_this.x_integrante_6 && EW_hasValue(EW_this.x_integrante_6, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_6 && !EW_hasValue(EW_this.x_monto_6, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_6, "TEXT", "Indique el monto del integrante 6."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_6 && !EW_hasValue(EW_this.x_rol_integrante_6, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_6, "SELECT", "Indique el rol del integrante 6."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE SIETE
if (validada == true && EW_this.x_integrante_7 && EW_hasValue(EW_this.x_integrante_7, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_7 && !EW_hasValue(EW_this.x_monto_7, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_7, "TEXT", "Indique el monto del integrante 7."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_7 && !EW_hasValue(EW_this.x_rol_integrante_7, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_7, "SELECT", "Indique el rol del integrante 7."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE OCHO
if (validada == true && EW_this.x_integrante_8 && EW_hasValue(EW_this.x_integrante_8, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_8 && !EW_hasValue(EW_this.x_monto_8, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_8, "TEXT", "Indique el monto del integrante 8."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_8 && !EW_hasValue(EW_this.x_rol_integrante_8, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_8, "SELECT", "Indique el rol del integrante 8."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE NUEVE
if (validada == true && EW_this.x_integrante_9 && EW_hasValue(EW_this.x_integrante_9, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_9 && !EW_hasValue(EW_this.x_monto_9, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_9, "TEXT", "Indique el monto del integrante 9."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_9 && !EW_hasValue(EW_this.x_rol_integrante_9, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_9, "SELECT", "Indique el rol del integrante 9."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE DIEZ
if (validada == true && EW_this.x_integrante_10 && EW_hasValue(EW_this.x_integrante_10, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_10 && !EW_hasValue(EW_this.x_monto_10, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_10, "TEXT", "Indique el monto del integrante 10."))
		validada = false;
}
 //rol
 if (validada == true && EW_this.x_rol_integrante_10 && !EW_hasValue(EW_this.x_rol_integrante_10, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_10, "SELECT", "Indique el rol del integrante 10."))
		validada = false;
}
}//fin integrante 10

	
	
	
if (EW_this.x_nombre_grupo && !EW_hasValue(EW_this.x_nombre_grupo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_grupo, "TEXT", "El sigueinete campo es requerido - nombre grupo"))
		validada = false;
}
if (EW_this.x_promotor && !EW_hasValue(EW_this.x_promotor, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor, "TEXT", "El siguiente campo es requerido - promotor"))
		validada = false;
}
if (EW_this.x_representante_sugerido && !EW_hasValue(EW_this.x_representante_sugerido, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_representante_sugerido, "TEXT", "El siguiente campo es requerido - representante sugerido"))
		validada = false;
}
if (EW_this.x_tesorero && !EW_hasValue(EW_this.x_tesorero, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tesorero, "TEXT", "El siguiente campo es requerido - tesorero"))
		validada = false;
}
if (EW_this.x_numero_integrantes && !EW_hasValue(EW_this.x_numero_integrantes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_integrantes, "TEXT", "El siguiente campo es requerido - numero integrantes"))
		validada = false;
}
if (EW_this.x_numero_integrantes && !EW_checkinteger(EW_this.x_numero_integrantes.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_integrantes, "TEXT", "Este campo acepta valor numerico - numero integrantes"))
		validada = false; 
}
if (EW_this.x_monto_1 && !EW_checknumber(EW_this.x_monto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_1, "TEXT", "Incorrect floating point number - monto 1"))
		validada = false; 
}
if (EW_this.x_monto_2 && !EW_checknumber(EW_this.x_monto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_2, "TEXT", "Incorrect floating point number - monto 2"))
		validada = false; 
}
if (EW_this.x_monto_3 && !EW_checknumber(EW_this.x_monto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_3, "TEXT", "Incorrect floating point number - monto 3"))
		validada = false; 
}
if (EW_this.x_monto_4 && !EW_checknumber(EW_this.x_monto_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_4, "TEXT", "Incorrect floating point number - monto 4"))
		validada = false; 
}
if (EW_this.x_monto_5 && !EW_checknumber(EW_this.x_monto_5.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_5, "TEXT", "Incorrect floating point number - monto 5"))
		validada = false; 
}
if (EW_this.x_monto_6 && !EW_checknumber(EW_this.x_monto_6.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_6, "TEXT", "Incorrect floating point number - monto 6"))
		validada = false; 
}
if (EW_this.x_monto_7 && !EW_checknumber(EW_this.x_monto_7.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_7, "TEXT", "Incorrect floating point number - monto 7"))
		validada = false; 
}
if (EW_this.x_monto_8 && !EW_checknumber(EW_this.x_monto_8.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_8, "TEXT", "Incorrect floating point number - monto 8"))
		validada = false; 
}
if (EW_this.x_monto_9 && !EW_checknumber(EW_this.x_monto_9.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_9, "TEXT", "Incorrect floating point number - monto 9"))
		validada = false; 
}
if (EW_this.x_monto_10 && !EW_checknumber(EW_this.x_monto_10.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_10, "TEXT", "Incorrect floating point number - monto 10"))
		validada = false; 
}
if (EW_this.x_monto_total && !EW_checknumber(EW_this.x_monto_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_total, "TEXT", "Incorrect floating point number - monto total"))
		validada = false; 
}
if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "El siguiente campo es requerido - fecha registro"))
		validada = false;
}
x_cont = 1;

while(x_cont  <= 10){
	
	 if(EW_this.x_integrante_+x_cont.length > 0){
	 if (EW_this.x_rol_integrante_+x_cont && !EW_hasValue(EW_this.x_fecha_registro, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_+x_cont, "SELECT", "El siguiente campo es requerido - ROL INTEGRANTE"))
		validada = false;
}
	 
	 
	 }
	 x_cont++;
}
if(validada == true){
	EW_this.submit();	
	
	}
	 
	 


}// fin validadcion para el caso credito solidario
	
//validacion para el caso credito adquision maquinaria	
function validadFrmCreditoAdqMaq(){
	
	EW_this = document.frmAddSolicitud;
	validada = true;

///////////////////////////////////////////////////

if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}

if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique numero de pagos."))
		validada = false;
}
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}



if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Por favor introduzca el campo requerido - nombre"))
		validada = false;
}
if (EW_this.x_rfc && !EW_hasValue(EW_this.x_rfc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_rfc, "TEXT", "Por favor introduzca el campo requerido - RFC"))
		validada = false;
}
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Por favor introduzca el campo requerido - CURP"))
		validada = false;
}
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Por favor introduzca el campo requerido - fecha nacimiento"))
		validada = false;
}/*
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha nacimiento"))
		validada = false; 
}*/
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Por favor introduzca el campo requerido - sexo"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_hasValue(EW_this.x_integrantes_familia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Por favor introduzca el campo requerido - integrantes familia"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Valor incorrecto, se espera un entero. - integrantes familia"))
		validada = false; 
}
if (EW_this.x_dependientes && !EW_hasValue(EW_this.x_dependientes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Por favor introduzca el campo requerido - dependientes"))
		validada = false;
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Valor incorrecto, se espera un entero. - dependientes"))
		validada = false; 
}

/*
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Por favor introduzca el campo requerido - correo electronico"))
		validada = false;
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Email incorrecto, verifque por favor - correo electronico"))
		validada = false; 
}*/
if (EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Por favor introduzca el campo requerido - calle domicilio"))
		validada = false;
}
if (EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Por favor introduzca el campo requerido - colonia domicilio"))
		validada = false;
}
if (EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "TEXT", "Por favor introduzca el campo requerido - entidad domicilio"))
		validada = false;
}
/*
if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Por favor introduzca el campo requerido - codigo postal domicilio"))
		validada = false;
}
if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal domicilio"))
		validada = false; 
}*/
if (EW_this.x_ubicacion_domicilio && !EW_hasValue(EW_this.x_ubicacion_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_domicilio, "TEXT", "Por favor introduzca el campo requerido - ubicacion domicilio"))
		validada = false;
}
if (EW_this.x_tipo_vivienda && !EW_hasValue(EW_this.x_tipo_vivienda, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_vivienda, "TEXT", "Por favor introduzca el campo requerido - tipo vivienda"))
		validada = false;
}
if (EW_this.x_renta_mensula_domicilio && !EW_checknumber(EW_this.x_renta_mensula_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensula_domicilio, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensula domicilio"))
		validada = false; 
}

if (EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "TEXT", "Por favor introduzca el campo requerido - delegacion, de la seccion domicilio"))
		validada = false;
}



//telefonos del domicilio

if(EW_this.x_telefono_domicilio.value.length != 0 || EW_this.x_celular.value.length != 0 ||
   EW_this.x_otro_telefono_domicilio_2.value.length != 0 || EW_this.x_otro_tel_domicilio_1.value.length != 0 ){
	//alguno de los telefono esta llenos
	}else{
		// no se ha llenado ningun telefono...es necesario llenar almenos uno de los 4		
		alert("Por favor introduzca almenos 1 de los telefono listados en la seccion domicilio")
		validada = false;
		
		}

//referencias
if((EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_2.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_3.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0 )){
	
	if (EW_this.x_referencia_com_1 && EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 1"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 1"))
		validada = false;
		}
				
	}//valida referncia uno
	
	
	
	if (EW_this.x_referencia_com_2 && EW_hasValue(EW_this.x_referencia_com_2, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_2 && !EW_hasValue(EW_this.x_parentesco_ref_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_2, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 2"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_2 && !EW_hasValue(EW_this.x_tel_referencia_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_2, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 2"))
		validada = false;
		}
				
	}//valida referncia 2
	
	
	
	if (EW_this.x_referencia_com_3 && EW_hasValue(EW_this.x_referencia_com_3, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_3 && !EW_hasValue(EW_this.x_parentesco_ref_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_3, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 3"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_3 && !EW_hasValue(EW_this.x_tel_referencia_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_3, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 3"))
		validada = false;
		}
				
	}//valida referncia 3
	
	
	if (EW_this.x_referencia_com_4 && EW_hasValue(EW_this.x_referencia_com_4, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_4 && !EW_hasValue(EW_this.x_parentesco_ref_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_4, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 4"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_4 && !EW_hasValue(EW_this.x_tel_referencia_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_4, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 4"))
		validada = false;
		}
				
	}//valida referncia 4
	
	
	
	//se llenaron almenos dos campos
		
	}else{
		alert("Debe introducir al menos dos referencias");			
		validada = false;
	
	}







/*
if (EW_this.x_calle_negocio && !EW_hasValue(EW_this.x_calle_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_negocio, "TEXT", "Por favor introduzca el campo requerido - calle negocio"))
		validada = false;
}
if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Por favor introduzca el campo requerido - colonia negocio"))
		validada = false;
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Por favor introduzca el campo requerido - entidad negocio"))
		validada = false;
}
if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Por favor introduzca el campo requerido - ubicacion negocio"))
		validada = false;
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal negocio"))
		validada = false; 
}
if (EW_this.x_renta_mensual && !EW_checknumber(EW_this.x_renta_mensual.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensual"))
		validada = false; 
}
if (EW_this.x_solicitud_compra && !EW_hasValue(EW_this.x_solicitud_compra, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_solicitud_compra, "TEXTAREA", "Por favor introduzca el campo requerido - solicitud compra"))
		validada = false;
}
if (EW_this.x_referencia_com_1 && !EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_com_1, "TEXT", "Por favor introduzca el campo requerido - referencia com 1"))
		validada = false;
}
if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - tel referencia 1"))
		validada = false;
}
if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco ref 1"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_hasValue(EW_this.x_ing_fam_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Por favor introduzca el campo requerido - ing fam negocio"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam negocio"))
		validada = false; 
}
if (EW_this.x_ing_fam_otro_th && !EW_hasValue(EW_this.x_ing_fam_otro_th, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Por favor introduzca el campo requerido - ing fam otro th"))
		validada = false;
}
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam otro th"))
		validada = false; 
}
if (EW_this.x_ing_fam_1 && !EW_hasValue(EW_this.x_ing_fam_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Por favor introduzca el campo requerido - ing fam 1"))
		validada = false;
}
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_2 && !EW_hasValue(EW_this.x_ing_fam_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Por favor introduzca el campo requerido - ing fam 2"))
		validada = false;
}
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_1 && !EW_hasValue(EW_this.x_ing_fam_deuda_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 1"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_2 && !EW_hasValue(EW_this.x_ing_fam_deuda_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 2"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_total && !EW_hasValue(EW_this.x_ing_fam_total, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Por favor introduzca el campo requerido - ing fam total"))
		validada = false;
}
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam total"))
		validada = false; 
}
if (EW_this.x_flujos_neg_ventas && !EW_hasValue(EW_this.x_flujos_neg_ventas, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Por favor introduzca el campo requerido - flujos neg ventas"))
		validada = false;
}
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg ventas"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 3"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 4"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 4"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_hasValue(EW_this.x_flujos_neg_gasto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_hasValue(EW_this.x_flujos_neg_gasto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_hasValue(EW_this.x_flujos_neg_gasto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 3"))
		validada = false; 
}
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ingreso negocio"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_fija && !EW_hasValue(EW_this.x_inv_neg_total_fija, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Por favor introduzca el campo requerido - inv neg total fija"))
		validada = false;
}
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total fija"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_hasValue(EW_this.x_inv_neg_var_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_hasValue(EW_this.x_inv_neg_var_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_hasValue(EW_this.x_inv_neg_var_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_hasValue(EW_this.x_inv_neg_var_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_var && !EW_hasValue(EW_this.x_inv_neg_total_var, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Por favor introduzca el campo requerido - inv neg total var"))
		validada = false;
}
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total var"))
		validada = false; 
}
if (EW_this.x_inv_neg_activos_totales && !EW_hasValue(EW_this.x_inv_neg_activos_totales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Por favor introduzca el campo requerido - inv neg activos totales"))
		validada = false;
}
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg activos totales"))
		validada = false; 
}
if (EW_this.x_fecha && !EW_hasValue(EW_this.x_fecha, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Por favor introduzca el campo requerido - fecha"))
		validada = false;
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha"))
		validada = false; 
}
*/
///////////////////////////////////////


if(validada == true){
	EW_this.submit();	
	 
	//EW_this.getElementById("seEnvioFormulario").style.display="block"; 
	
}
	
	
	
	
	
	
	
	
	
	} //validacion para el caso credito adquision maquinaria	
	
//validadcion para el cason credito PYME		
function validadFrmCreditoPyme(){
	
	EW_this = document.frmAddSolicitud;
	validada = true;

///////////////////////////////////////////////////
if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}

if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique elnumero de pagos"))
		validada = false;
}
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}



if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Por favor introduzca el campo requerido - nombre"))
		validada = false;
}
/*
if (EW_this.x_rfc && !EW_hasValue(EW_this.x_rfc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_rfc, "TEXT", "Por favor introduzca el campo requerido - RFC"))
		validada = false;
}
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Por favor introduzca el campo requerido - CURP"))
		validada = false;
}*/
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Por favor introduzca el campo requerido - fecha nacimiento"))
		validada = false;
}/*
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha nacimiento"))
		validada = false; 
}*/
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Por favor introduzca el campo requerido - sexo"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_hasValue(EW_this.x_integrantes_familia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Por favor introduzca el campo requerido - integrantes familia"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Valor incorrecto, se espera un entero. - integrantes familia"))
		validada = false; 
}
if (EW_this.x_dependientes && !EW_hasValue(EW_this.x_dependientes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Por favor introduzca el campo requerido - dependientes"))
		validada = false;
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Valor incorrecto, se espera un entero. - dependientes"))
		validada = false; 
}
/*
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Por favor introduzca el campo requerido - correo electronico"))
		validada = false;
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Email incorrecto, verifque por favor - correo electronico"))
		validada = false; 
}*/
if (EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Por favor introduzca el campo requerido - calle domicilio"))
		validada = false;
}
if (EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Por favor introduzca el campo requerido - colonia domicilio"))
		validada = false;
}
if (EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "TEXT", "Por favor introduzca el campo requerido - entidad domicilio"))
		validada = false;
}

if (EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "TEXT", "Por favor introduzca el campo requerido - delegacion, de la seccion domicilio"))
		validada = false;
}

if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Por favor introduzca el campo requerido - codigo postal domicilio"))
		validada = false;
}

if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal domicilio"))
		validada = false; 
}
if (EW_this.x_ubicacion_domicilio && !EW_hasValue(EW_this.x_ubicacion_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_domicilio, "TEXT", "Por favor introduzca el campo requerido - ubicacion domicilio"))
		validada = false;
}
if (EW_this.x_tipo_vivienda && !EW_hasValue(EW_this.x_tipo_vivienda, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_vivienda, "TEXT", "Por favor introduzca el campo requerido - tipo vivienda"))
		validada = false;
}
if (EW_this.x_renta_mensula_domicilio && !EW_checknumber(EW_this.x_renta_mensula_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensula_domicilio, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensula domicilio"))
		validada = false; 
}

if (EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "TEXT", "Por favor introduzca el campo requerido - delegacion, de la seccion domicilio"))
		validada = false;
}


// validaciones para telefonop de direccion particular


if(EW_this.x_telefono_domicilio.value.length != 0 || EW_this.x_celular.value.length != 0 ||
   EW_this.x_otro_telefono_domicilio_2.value.length != 0 || EW_this.x_otro_tel_domicilio_1.value.length != 0 ){
	//alguno de los telefono esta llenos
	}else{
		// no se ha llenado ningun telefono...es necesario llenar almenos uno de los 4		
		alert("Por favor introduzca almenos 1 de los telefono listados en la seccion domicilio")
		validada = false;
		
		}


		






// en el formto pyme si son requeridos lo campos de negocio

if (EW_this.x_giro_negocio && !EW_hasValue(EW_this.x_giro_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio, "TEXT", "Por favor introduzca el campo requerido - giro negocio"))
		validada = false;
}
if (EW_this.x_calle_negocio && !EW_hasValue(EW_this.x_calle_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_negocio, "TEXT", "Por favor introduzca el campo requerido - calle negocio"))
		validada = false;
}
if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Por favor introduzca el campo requerido - colonia negocio"))
		validada = false;
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Por favor introduzca el campo requerido - entidad negocio"))
		validada = false;
}

if (EW_this.x_delegacion_id2 && !EW_hasValue(EW_this.x_delegacion_id2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "TEXT", "Por favor introduzca el campo requerido - delegacion, de la seccion negocio"))
		validada = false;
}





if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Por favor introduzca el campo requerido - ubicacion negocio"))
		validada = false;
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal negocio"))
		validada = false; 
}
if (EW_this.x_renta_mensual && !EW_checknumber(EW_this.x_renta_mensual.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensual"))
		validada = false; 
}

if (EW_this.x_antiguedad_negocio && !EW_hasValue(EW_this.x_antiguedad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad_negocio, "TEXT", "Por favor introduzca el campo requerido - antiguedad del negocio"))
		validada = false;
}




if (EW_this.x_tel_arrendatario_negocio && !EW_hasValue(EW_this.x_tel_arrendatario_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_arrendatario_negocio, "TEXT", "Por favor introduzca el campo requerido - contacto negocio"))
		validada = false;
}

if (EW_this.x_antiguedad_ubicacion && !EW_hasValue(EW_this.x_antiguedad_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad_ubicacion, "TEXT", "Por favor introduzca el campo requerido - antiguedad del negocio en la ubicacion actual"))
		validada = false;
}





// validaciones para referencias
if((EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_2.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_3.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0 )){
	
	if (EW_this.x_referencia_com_1 && EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 1"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 1"))
		validada = false;
		}
				
	}//valida referncia uno
	
	
	
	if (EW_this.x_referencia_com_2 && EW_hasValue(EW_this.x_referencia_com_2, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_2 && !EW_hasValue(EW_this.x_parentesco_ref_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_2, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 2"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_2 && !EW_hasValue(EW_this.x_tel_referencia_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_2, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 2"))
		validada = false;
		}
				
	}//valida referncia 2
	
	
	
	if (EW_this.x_referencia_com_3 && EW_hasValue(EW_this.x_referencia_com_3, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_3 && !EW_hasValue(EW_this.x_parentesco_ref_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_3, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 3"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_3 && !EW_hasValue(EW_this.x_tel_referencia_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_3, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 3"))
		validada = false;
		}
				
	}//valida referncia 3
	
	
	if (EW_this.x_referencia_com_4 && EW_hasValue(EW_this.x_referencia_com_4, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_4 && !EW_hasValue(EW_this.x_parentesco_ref_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_4, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 4"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_4 && !EW_hasValue(EW_this.x_tel_referencia_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_4, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 4"))
		validada = false;
		}
				
	}//valida referncia 4
	
	
	
	//se llenaron almenos dos campos
		
	}else{
		alert("Debe introducir al menos dos referencias");		
		
		validada = false;
	
	}







/*if (EW_this.x_referencia_com_1 && !EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_com_1, "TEXT", "Por favor introduzca el campo requerido - referencia com 1"))
		validada = false;
}
if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - tel referencia 1"))
		validada = false;
}
if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco ref 1"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_hasValue(EW_this.x_ing_fam_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Por favor introduzca el campo requerido - ing fam negocio"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam negocio"))
		validada = false; 
}
if (EW_this.x_ing_fam_otro_th && !EW_hasValue(EW_this.x_ing_fam_otro_th, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Por favor introduzca el campo requerido - ing fam otro th"))
		validada = false;
}
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam otro th"))
		validada = false; 
}
if (EW_this.x_ing_fam_1 && !EW_hasValue(EW_this.x_ing_fam_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Por favor introduzca el campo requerido - ing fam 1"))
		validada = false;
}
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_2 && !EW_hasValue(EW_this.x_ing_fam_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Por favor introduzca el campo requerido - ing fam 2"))
		validada = false;
}
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_1 && !EW_hasValue(EW_this.x_ing_fam_deuda_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 1"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_2 && !EW_hasValue(EW_this.x_ing_fam_deuda_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 2"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_total && !EW_hasValue(EW_this.x_ing_fam_total, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Por favor introduzca el campo requerido - ing fam total"))
		validada = false;
}
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam total"))
		validada = false; 
}
if (EW_this.x_flujos_neg_ventas && !EW_hasValue(EW_this.x_flujos_neg_ventas, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Por favor introduzca el campo requerido - flujos neg ventas"))
		validada = false;
}
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg ventas"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 3"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 4"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 4"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_hasValue(EW_this.x_flujos_neg_gasto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_hasValue(EW_this.x_flujos_neg_gasto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_hasValue(EW_this.x_flujos_neg_gasto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 3"))
		validada = false; 
}
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ingreso negocio"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_fija && !EW_hasValue(EW_this.x_inv_neg_total_fija, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Por favor introduzca el campo requerido - inv neg total fija"))
		validada = false;
}
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total fija"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_hasValue(EW_this.x_inv_neg_var_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_hasValue(EW_this.x_inv_neg_var_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_hasValue(EW_this.x_inv_neg_var_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_hasValue(EW_this.x_inv_neg_var_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_var && !EW_hasValue(EW_this.x_inv_neg_total_var, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Por favor introduzca el campo requerido - inv neg total var"))
		validada = false;
}
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total var"))
		validada = false; 
}
if (EW_this.x_inv_neg_activos_totales && !EW_hasValue(EW_this.x_inv_neg_activos_totales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Por favor introduzca el campo requerido - inv neg activos totales"))
		validada = false;
}
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg activos totales"))
		validada = false; 
}
if (EW_this.x_fecha && !EW_hasValue(EW_this.x_fecha, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Por favor introduzca el campo requerido - fecha"))
		validada = false;
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha"))
		validada = false; 
}*/

///////////////////////////////////////


if(validada == true){
	EW_this.submit();	
	 
	//EW_this.getElementById("seEnvioFormulario").style.display="block"; 
	
}
}//validadcion para el cason credito PYME



function validaFrmCreditoIndPersonal(){
	
	EW_this = document.frmAddSolicitud;
	validada = true;

///////////////////////////////////////////////////
if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}

if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el numero de pagos."))
		validada = false;
}
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}



if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Por favor introduzca el campo requerido - nombre"))
		validada = false;
}

/*
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Por favor introduzca el campo requerido - CURP"))
		validada = false;
}*/
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Por favor introduzca el campo requerido - fecha nacimiento"))
		validada = false;
}
/*
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha nacimiento"))
		validada = false; 
}*/


if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Por favor introduzca el campo requerido - sexo"))
		validada = false;
}

if (EW_this.x_integrantes_familia && !EW_hasValue(EW_this.x_integrantes_familia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Por favor introduzca el campo requerido - integrantes familia"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Valor incorrecto, se espera un entero. - integrantes familia"))
		validada = false; 
}
if (EW_this.x_dependientes && !EW_hasValue(EW_this.x_dependientes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Por favor introduzca el campo requerido - dependientes"))
		validada = false;
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Valor incorrecto, se espera un entero. - dependientes"))
		validada = false; 
}
/*
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Por favor introduzca el campo requerido - correo electronico"))
		validada = false;
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Email incorrecto, verifque por favor - correo electronico"))
		validada = false; 
}*/
if (EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Por favor introduzca el campo requerido - calle domicilio"))
		validada = false;
}
if (EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Por favor introduzca el campo requerido - colonia domicilio"))
		validada = false;
}
if (EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "TEXT", "Por favor introduzca el campo requerido - entidad domicilio"))
		validada = false;
}


if (EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "TEXT", "Por favor introduzca el campo requerido - delegacion, de la seccion domicilio"))
		validada = false;
}



if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Por favor introduzca el campo requerido - codigo postal domicilio"))
		validada = false;
}
/*
if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal domicilio"))
		validada = false; 
}*/
if (EW_this.x_ubicacion_domicilio && !EW_hasValue(EW_this.x_ubicacion_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_domicilio, "TEXT", "Por favor introduzca el campo requerido - ubicacion domicilio"))
		validada = false;
}
if (EW_this.x_tipo_vivienda && !EW_hasValue(EW_this.x_tipo_vivienda, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_vivienda, "TEXT", "Por favor introduzca el campo requerido - tipo vivienda"))
		validada = false;
}
if (EW_this.x_renta_mensula_domicilio && !EW_checknumber(EW_this.x_renta_mensula_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensula_domicilio, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensula domicilio"))
		validada = false; 
}
/*
if (EW_this.x_giro_negocio && !EW_hasValue(EW_this.x_giro_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio, "TEXT", "Por favor introduzca el campo requerido - giro negocio"))
		validada = false;
}
if (EW_this.x_calle_negocio && !EW_hasValue(EW_this.x_calle_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_negocio, "TEXT", "Por favor introduzca el campo requerido - calle negocio"))
		validada = false;
}
if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Por favor introduzca el campo requerido - colonia negocio"))
		validada = false;
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Por favor introduzca el campo requerido - entidad negocio"))
		validada = false;
}
if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Por favor introduzca el campo requerido - ubicacion negocio"))
		validada = false;
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal negocio"))
		validada = false; 
}
if (EW_this.x_renta_mensual && !EW_checknumber(EW_this.x_renta_mensual.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensual"))
		validada = false; 
}
if (EW_this.x_garantias && !EW_hasValue(EW_this.x_garantias, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_garantias, "TEXTAREA", "Por favor introduzca el campo requerido - solicitud compra"))
		validada = false;
}*/






if(EW_this.x_telefono_domicilio.value.length != 0 || EW_this.x_celular.value.length != 0 ||
   EW_this.x_otro_telefono_domicilio_2.value.length != 0 || EW_this.x_otro_tel_domicilio_1.value.length != 0 ){
	//alguno de los telefono esta llenos
	}else{
		// no se ha llenado ningun telefono...es necesario llenar almenos uno de los 4		
		alert("Por favor introduzca almenos 1 de los telefono listados en la seccion domicilio")
		validada = false;
		
		}

if((EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_2.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_3.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0 )){
	
	if (EW_this.x_referencia_com_1 && EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 1"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 1"))
		validada = false;
		}
				
	}//valida referncia uno
	
	
	
	if (EW_this.x_referencia_com_2 && EW_hasValue(EW_this.x_referencia_com_2, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_2 && !EW_hasValue(EW_this.x_parentesco_ref_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_2, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 2"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_2 && !EW_hasValue(EW_this.x_tel_referencia_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_2, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 2"))
		validada = false;
		}
				
	}//valida referncia 2
	
	
	
	if (EW_this.x_referencia_com_3 && EW_hasValue(EW_this.x_referencia_com_3, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_3 && !EW_hasValue(EW_this.x_parentesco_ref_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_3, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 3"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_3 && !EW_hasValue(EW_this.x_tel_referencia_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_3, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 3"))
		validada = false;
		}
				
	}//valida referncia 3
	
	
	if (EW_this.x_referencia_com_4 && EW_hasValue(EW_this.x_referencia_com_4, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_4 && !EW_hasValue(EW_this.x_parentesco_ref_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_4, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 4"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_4 && !EW_hasValue(EW_this.x_tel_referencia_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_4, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 4"))
		validada = false;
		}
				
	}//valida referncia 4
	
	
	
	//se llenaron almenos dos campos
		
	}else{
		alert("Debe introducir al menos dos referencias");		
		
		validada = false;
	
	}
		

	
	/*

if (EW_this.x_ing_fam_negocio && !EW_hasValue(EW_this.x_ing_fam_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Por favor introduzca el campo requerido - ing fam negocio"))
		validada = false;	
}
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam negocio"))
		validada = false; 
}
if (EW_this.x_ing_fam_otro_th && !EW_hasValue(EW_this.x_ing_fam_otro_th, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Por favor introduzca el campo requerido - ing fam otro th"))
		validada = false;
}
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam otro th"))
		validada = false; 
}
if (EW_this.x_ing_fam_1 && !EW_hasValue(EW_this.x_ing_fam_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Por favor introduzca el campo requerido - ing fam 1"))
		validada = false;
}
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_2 && !EW_hasValue(EW_this.x_ing_fam_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Por favor introduzca el campo requerido - ing fam 2"))
		validada = false;
}
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_1 && !EW_hasValue(EW_this.x_ing_fam_deuda_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 1"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_2 && !EW_hasValue(EW_this.x_ing_fam_deuda_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 2"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_total && !EW_hasValue(EW_this.x_ing_fam_total, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Por favor introduzca el campo requerido - ing fam total"))
		validada = false;
}
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam total"))
		validada = false; 
}
if (EW_this.x_flujos_neg_ventas && !EW_hasValue(EW_this.x_flujos_neg_ventas, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Por favor introduzca el campo requerido - flujos neg ventas"))
		validada = false;
}
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg ventas"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 3"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 4"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 4"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_hasValue(EW_this.x_flujos_neg_gasto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_hasValue(EW_this.x_flujos_neg_gasto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_hasValue(EW_this.x_flujos_neg_gasto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 3"))
		validada = false; 
}
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ingreso negocio"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_fija && !EW_hasValue(EW_this.x_inv_neg_total_fija, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Por favor introduzca el campo requerido - inv neg total fija"))
		validada = false;
}
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total fija"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_hasValue(EW_this.x_inv_neg_var_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_hasValue(EW_this.x_inv_neg_var_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_hasValue(EW_this.x_inv_neg_var_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_hasValue(EW_this.x_inv_neg_var_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_var && !EW_hasValue(EW_this.x_inv_neg_total_var, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Por favor introduzca el campo requerido - inv neg total var"))
		validada = false;
}
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total var"))
		validada = false; 
}
if (EW_this.x_inv_neg_activos_totales && !EW_hasValue(EW_this.x_inv_neg_activos_totales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Por favor introduzca el campo requerido - inv neg activos totales"))
		validada = false;
}
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg activos totales"))
		validada = false; 
}
if (EW_this.x_fecha && !EW_hasValue(EW_this.x_fecha, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Por favor introduzca el campo requerido - fecha"))
		validada = false;
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha"))
		validada = false; 
}*/

///////////////////////////////////////


if(validada == true){
	EW_this.submit();	
	 
	//EW_this.getElementById("seEnvioFormulario").style.display="block"; 
	
}
}//validadcion para el cason credito PYME




