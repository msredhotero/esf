function ini(){
	alert("ini:");
	}


function eventosFormatoIndividual(){
	alert("Credito Individual");
	
	
	//document.getElementById("f").onchange = viviendatipo; 
	
	function buscadelegacion(){
	EW_this = document.frmAddSolicitud;
	EW_this.a_add.value = "D";	
	EW_this.submit();	
}

}//creditoINdividual

function buscacliente(){
	EW_this = document.frmAddSolicitud;
	EW_this.a_add.value = "X";
	EW_this.submit();

}
function validaimporte(){
	EW_this = document.frmAddSolicitud;
	if(EW_this.x_importe_solicitado.value < 1000){
		alert("El importe es incorrecto valor minimo 1000");
		EW_this.x_importe_solicitado.focus();
	}
}


function mismosdom(){
	EW_this = document.frmAddSolicitud;
	validada = true;
	
	if(EW_this.x_mismos.checked == true){
		if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio particular."))
				validada = false;
		}
		if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio particular."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id && !EW_hasValue(EW_this.x_entidad_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id, "SELECT", "Indique la entidad del domicilio particular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegación del domicilio particular."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismos.checked = false;
		}else{
			EW_this.x_calle2.value = EW_this.x_calle.value;
			EW_this.x_colonia2.value = EW_this.x_colonia.value;
			EW_this.x_codigo_postal2.value = EW_this.x_codigo_postal.value;
			EW_this.x_ubicacion2.value = EW_this.x_ubicacion.value;
			EW_this.x_antiguedad2.value = EW_this.x_antiguedad.value;
			EW_this.x_telefono2.value = EW_this.x_telefono.value;		
	
			var indice = EW_this.x_entidad_id.selectedIndex;
			EW_this.x_entidad_id2.options[indice].selected = true;
	
			EW_this.x_delegacion_id_temp.value = EW_this.x_delegacion_id.value;
			EW_this.a_add.value = "D";	
			showHint(EW_this.x_entidad_id2,'txtHint2', 'x_delegacion_id2', EW_this.x_delegacion_id.value);


//			EW_this.submit();	
	
		}
	}else{
		EW_this.x_calle2.value = "";
		EW_this.x_colonia2.value = "";
		EW_this.x_entidad_id2.options[0].selected = true;	
	//	EW_this.x_delegacion_id2.options[0].selected = true;
		EW_this.x_entidad2.value = "";
		EW_this.x_codigo_postal2.value = "";
		EW_this.x_ubicacion2.value = "";
		EW_this.x_antiguedad2.value = "";
		EW_this.x_telefono2.value = "";			
	}
	
}


function mismosdomtit(){
	EW_this = document.frmAddSolicitud;
	validada = true;
	
	if(EW_this.x_mismos_titluar.checked == true){
		if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio particular del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio particular del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id && !EW_hasValue(EW_this.x_entidad_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id, "SELECT", "Indique la entidad del domicilio particular del titular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegación del domicilio particular del titular."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismos_titluar.checked = false;
		}else{
			EW_this.x_calle3.value = EW_this.x_calle.value;
			EW_this.x_colonia3.value = EW_this.x_colonia.value;
			EW_this.x_codigo_postal3.value = EW_this.x_codigo_postal.value;
			EW_this.x_ubicacion3.value = EW_this.x_ubicacion.value;
			EW_this.x_antiguedad3.value = EW_this.x_antiguedad.value;
			EW_this.x_telefono3.value = EW_this.x_telefono.value;		

			var indice_vt = EW_this.x_vivienda_tipo_id.selectedIndex;
			EW_this.x_vivienda_tipo_id2.options[indice_vt].selected = true;
	

			var indice = EW_this.x_entidad_id.selectedIndex;
			EW_this.x_entidad_id3.options[indice].selected = true;

			showHint(EW_this.x_entidad_id3,'txtHint3', 'x_delegacion_id3', EW_this.x_delegacion_id.value);
	
		}
	}else{
		EW_this.x_calle3.value = "";
		EW_this.x_colonia3.value = "";
		EW_this.x_vivienda_tipo_id2.options[0].selected = true;			
		EW_this.x_entidad_id3.options[0].selected = true;	
		EW_this.x_codigo_postal3.value = "";
		EW_this.x_ubicacion3.value = "";
		EW_this.x_antiguedad3.value = "";
		EW_this.x_telefono3.value = "";			

	}
}


function mismosdomtitneg(){
	EW_this = document.frmAddSolicitud;
	validada = true;
	
	if(EW_this.x_mismosava2.checked == true){
		EW_this.x_mismosava.checked = false;
		if (validada == true && EW_this.x_calle2 && !EW_hasValue(EW_this.x_calle2, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle2, "TEXT", "Indique la calle del domicilio del negocio del titular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_colonia2 && !EW_hasValue(EW_this.x_colonia2, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia2, "TEXT", "Indique la colonia del domicilio del negocio del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id2 && !EW_hasValue(EW_this.x_entidad_id2, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id2, "SELECT", "Indique la entidad del domicilio del negocio del titular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id2 && !EW_hasValue(EW_this.x_delegacion_id2, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "SELECT", "Indique la delegación del domicilio del negocio del titular."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismosava2.checked = false;
			EW_this.x_mismosava.checked = false;
		}else{

			EW_this.x_calle3_neg.value = EW_this.x_calle2.value;
			EW_this.x_colonia3_neg.value = EW_this.x_colonia2.value;
			EW_this.x_codigo_postal3_neg.value = EW_this.x_codigo_postal2.value;
			EW_this.x_ubicacion3_neg.value = EW_this.x_ubicacion2.value;
			EW_this.x_antiguedad3_neg.value = EW_this.x_antiguedad2.value;
			EW_this.x_telefono3_neg.value = EW_this.x_telefono2.value;		


			var indice2 = EW_this.x_entidad_id2.selectedIndex;
			EW_this.x_entidad_id3_neg.options[indice2].selected = true;

			showHint(EW_this.x_entidad_id3_neg,'txtHint3_neg', 'x_delegacion_id3_neg', EW_this.x_delegacion_id2.value);

	
		}
	}else{
		EW_this.x_calle3_neg.value = "";
		EW_this.x_colonia3_neg.value = "";
		EW_this.x_entidad_id3_neg.options[0].selected = true;	
		EW_this.x_codigo_postal3_neg.value = "";
		EW_this.x_ubicacion3_neg.value = "";
		EW_this.x_antiguedad3_neg.value = "";
		EW_this.x_telefono3_neg.value = "";			
	}
}

function mismosdomava(){
	EW_this = document.frmAddSolicitud;
	validada = true;
	
	if(EW_this.x_mismosava.checked == true){
		EW_this.x_mismosava2.checked = false;		
		if (validada == true && EW_this.x_calle3 && !EW_hasValue(EW_this.x_calle3, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle3, "TEXT", "Indique la calle del domicilio particular del aval."))
				validada = false;
		}
		if (validada == true && EW_this.x_colonia3 && !EW_hasValue(EW_this.x_colonia3, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia3, "TEXT", "Indique la colonia del domicilio particular del aval."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id3 && !EW_hasValue(EW_this.x_entidad_id3, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id3, "SELECT", "Indique la entidad del domicilio particular del aval."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id3 && !EW_hasValue(EW_this.x_delegacion_id3, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id3, "SELECT", "Indique la delegación del domicilio particular del aval."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismosava.checked = false;
			EW_this.x_mismosava2.checked = false;			
		}else{
			EW_this.x_calle3_neg.value = EW_this.x_calle3.value;
			EW_this.x_colonia3_neg.value = EW_this.x_colonia3.value;
			EW_this.x_codigo_postal3_neg.value = EW_this.x_codigo_postal3.value;
			EW_this.x_ubicacion3_neg.value = EW_this.x_ubicacion3.value;
			EW_this.x_antiguedad3_neg.value = EW_this.x_antiguedad3.value;
			EW_this.x_telefono3_neg.value = EW_this.x_telefono3.value;		
	
			var indice = EW_this.x_entidad_id3.selectedIndex;
			EW_this.x_entidad_id3_neg.options[indice].selected = true;
	
			showHint(EW_this.x_entidad_id3_neg,'txtHint3_neg', 'x_delegacion_id3_neg', EW_this.x_delegacion_id3.value);

	
		}
	}else{
		EW_this.x_calle3_neg.value = "";
		EW_this.x_colonia3_neg.value = "";
		EW_this.x_entidad_id3_neg.options[0].selected = true;
		EW_this.x_entidad3_neg.value = "";
		EW_this.x_codigo_postal3_neg.value = "";
		EW_this.x_ubicacion3_neg.value = "";
		EW_this.x_antiguedad3_neg.value = "";
		EW_this.x_telefono3_neg.value = "";
	}

}



function viviendatipo(viv){
	EW_this = document.frmAddSolicitud;
	if(viv == 1){
		if(EW_this.x_vivienda_tipo_id.value == 2){
			document.getElementById('prop1rentada').className = "TG_visible";
		}else{
			document.getElementById('prop1rentada').className = "TG_hidden";
			EW_this.x_propietario_renta.value = "";
			EW_this.x_gastos_renta_casa.value = "";
		}

		if(EW_this.x_vivienda_tipo_id.value == 3){
			document.getElementById('prop1').className = "TG_visible";
		}else{
			document.getElementById('prop1').className = "TG_hidden";
			EW_this.x_propietario_familiar.value = "";			
		}

		if(EW_this.x_vivienda_tipo_id.value == 4){
			document.getElementById('prop1credito').className = "TG_visible";
		}else{
			document.getElementById('prop1credito').className = "TG_hidden";
			EW_this.x_propietario_ch.value = "";			
			EW_this.x_gastos_credito_hipotecario.value = "";
		}

	}
	if(viv == 2){
		if(EW_this.x_vivienda_tipo_id2.value == 2){
			document.getElementById('prop1rentada2').className = "TG_visible";
		}else{
			document.getElementById('prop1rentada2').className = "TG_hidden";
			EW_this.x_propietario_renta2.value = "";
			EW_this.x_gastos_renta_casa2.value = "";
		}

		if(EW_this.x_vivienda_tipo_id2.value == 3){
			document.getElementById('prop2').className = "TG_visible";
		}else{
			document.getElementById('prop2').className = "TG_hidden";
			EW_this.x_propietario_familiar2.value = "";			
		}

		if(EW_this.x_vivienda_tipo_id2.value == 4){
			document.getElementById('prop1credito2').className = "TG_visible";
		}else{
			document.getElementById('prop1credito2').className = "TG_hidden";
			EW_this.x_propietario_ch2.value = "";			
			EW_this.x_gastos_credito_hipotecario2.value = "";
		}
/*
		if(EW_this.x_vivienda_tipo_id2.value == 3){
			document.getElementById('prop2').className = "TG_visible";
		}else{
			document.getElementById('prop2').className = "TG_hidden";
		}
*/		
	}
}
	
	//fin function credito individual




 function eventosFormatoSolidario(){
	alert("Credito Solidario");
	 	
	//EVENTOS PARA ACTUALIZAR EL FORMULARIO
	//document.getElementById("enviarFormulario").onclick = EW_checkMyForm;
	/*document.getElementById("x_nombre_grupo").onchange = aMayus;
	document.getElementById("x_promotor").onchange = aMayus;
	document.getElementById("x_representante_sugerido").onchange = aMayus;*/
	//document.getElementById("seEnvioFormulario").onclick = ocultaMens;
	
	document.getElementById("x_monto_1").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_2").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_3").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_4").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_5").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_6").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_7").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_8").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_9").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_10").onchange = actualizaImporteTotal;
	
	document.getElementById("x_integrante_1").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_2").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_3").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_4").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_5").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_6").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_7").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_8").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_9").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_10").onchange = actualizaNumeroIntegrantes;
	
	
	/*function ocultaMens (){
	document.getElementById("seEnvioFormulario").style.display = "none";	
	}*/
	
	function actualizaNumeroIntegrantes (){
			
		numero = 0;
		 t1 = document.getElementById("x_integrante_1").value;
		 t2 = document.getElementById("x_integrante_2").value;
		 t3 = document.getElementById("x_integrante_3").value;
		 t4 = document.getElementById("x_integrante_4").value;
		 t5 = document.getElementById("x_integrante_5").value;
		 t6 = document.getElementById("x_integrante_6").value;
		 t7 = document.getElementById("x_integrante_7").value;
		 t8 = document.getElementById("x_integrante_8").value;
		 t9 = document.getElementById("x_integrante_9").value;
		 t10 = document.getElementById("x_integrante_10").value;
		
		if(t1 != '')
			numero ++;
		if(t2 != '')
			numero ++;
		if(t3 != '')
			numero ++;
		if(t4 != '')
			numero ++;
		if(t5 != '')
			numero ++;
		if(t6 != '')
			numero ++;
		if(t7 != '')
			numero ++;
		if(t8 != '')
			numero ++;
		if(t9 != '')
			numero ++;
		if(t10 != '')
			numero ++;
			
		 document.getElementById("x_numero_integrantes").value = numero;	
		}
	
	
	function actualizaImporteTotal(){
		 impoT = parseFloat(document.getElementById("x_monto_total").value);
		 suma = 0;
		 t1 = document.getElementById("x_monto_1").value;
		 t2 = document.getElementById("x_monto_2").value;
		 t3 = document.getElementById("x_monto_3").value;
		 t4 = document.getElementById("x_monto_4").value;
		 t5 = document.getElementById("x_monto_5").value;
		 t6 = document.getElementById("x_monto_6").value;
		 t7 = document.getElementById("x_monto_7").value;
		 t8 = document.getElementById("x_monto_8").value;
		 t9 = document.getElementById("x_monto_9").value;
		 t10 = document.getElementById("x_monto_10").value;
		 if(t1 == ''){			
			m1 = 0; }else{
			m1 = parseFloat(document.getElementById("x_monto_1").value);
			}
		if(t2 == ''){			
			m2 = 0; }else{
			m2 = parseFloat(document.getElementById("x_monto_2").value);
			}
		if(t3 == ''){			
			m3 = 0; }else{
			m3 = parseFloat(document.getElementById("x_monto_3").value);
			}
		if(t4 == ''){			
			m4 = 0; }else{
			m4 = parseFloat(document.getElementById("x_monto_4").value);
			}
		if(t5 == ''){			
			m5 = 0; }else{
			m5 = parseFloat(document.getElementById("x_monto_5").value);
			}
		if(t6 == ''){			
			m6 = 0; }else{
			m6 = parseFloat(document.getElementById("x_monto_6").value);
			}
		if(t7 == ''){			
			m7 = 0; }else{
			m7 = parseFloat(document.getElementById("x_monto_7").value);
			}
		if(t8 == ''){			
			m8 = 0; }else{
			m8 = parseFloat(document.getElementById("x_monto_8").value);
			}
		 
		
		if(t9 == ''){			
			m9 = 0; }else{
			m9 = parseFloat(document.getElementById("x_monto_9").value);
			}
		if(t10 == ''){			
			m10 = 0; }else{
			m10 = parseFloat(document.getElementById("x_monto_10").value);
			}
		
		suma = m1 + m2 + m3 +m4 +m5 +m6 +m7 +m8 +m9 +m10;	
		document.getElementById("x_monto_total").value = suma;
		document.getElementById("x_importe_solicitado").value= suma;
		}
	
 }//function eventosFormatoSolidario
 
 function eventosFormatoMaquinaria(){
	 alert("Credito para Adquisicion de Maquinaria")
	 
	 
	 //ACTUALIZA EL CONTENIDO DEL FORMATO ADQUISICION DE MAQUINARIA
	// document.getElementById("siguiente").onclick = muestraOculta;
	//document.getElementById("anterior").onclick = muestraOculta;
	//document.getElementById("enviar").onclick = EW_checkMyForm;
	//document.getElementById("seEnvioFormulario").onclick = ocultaMens;
	
	document.getElementById("x_inv_neg_var_valor_1").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_2").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_3").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_4").onchange = totalVarible;
	document.getElementById("x_inv_neg_fija_valor_1").onchange= totalFija;
	document.getElementById("x_inv_neg_fija_valor_2").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_3").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_4").onchange = totalFija;
	document.getElementById("x_ing_fam_negocio").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_otro_th").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_2").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_2").onchange = ingresoFamiliar;
	document.getElementById("x_flujos_neg_ventas").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_3").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_4").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_3").onchange = flujosDeNegocio;
	
	 
	
	
function muestraOculta(){
 	var id = this.id; 
 	if(id =="siguiente"){
		document.getElementById("paginaUno").style.display ="none";
		document.getElementById("paginaDos").style.display ="block";
		document.getElementById("siguiente").style.display="none";
		document.getElementById("anterior").style.display="block";	
		} else{
			document.getElementById("paginaUno").style.display ="block";
			document.getElementById("paginaDos").style.display ="none";
			document.getElementById("anterior").style.display="none";
			document.getElementById("siguiente").style.display="block";
			}
 }//function muestraOculta 
	 
		function totalVarible (){
			t1 = document.getElementById("x_inv_neg_var_valor_1").value;
			t2 = document.getElementById("x_inv_neg_var_valor_2").value;
			t3 = document.getElementById("x_inv_neg_var_valor_3").value;
			t4 = document.getElementById("x_inv_neg_var_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_var_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_var_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_var_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_var_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_fija").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_fija").value);
			
			
			document.getElementById("x_inv_neg_total_var").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}
	 
	     
	 
	function totalFija (){
			t1 = document.getElementById("x_inv_neg_fija_valor_1").value;
			t2 = document.getElementById("x_inv_neg_fija_valor_2").value;
			t3 = document.getElementById("x_inv_neg_fija_valor_3").value;
			t4 = document.getElementById("x_inv_neg_fija_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_fija_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_fija_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_fija_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_fija_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_var").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_var").value);
			
			
			document.getElementById("x_inv_neg_total_fija").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}	
			
			function ingresoFamiliar(){
				t1 = document.getElementById("x_ing_fam_negocio").value;
				t2 = document.getElementById("x_ing_fam_otro_th").value;
				t3 = document.getElementById("x_ing_fam_1").value;
				t4 = document.getElementById("x_ing_fam_2").value;
				t5 = document.getElementById("x_ing_fam_deuda_1").value;
				t6 = document.getElementById("x_ing_fam_deuda_2").value;
				
				if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_ing_fam_negocio").value);
				
				if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_ing_fam_otro_th").value);
				
				
				if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_ing_fam_1").value);
				
				if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_ing_fam_2").value);
				
				if(t5 == '')
				n5 = 0;
				else
				n5 = parseFloat(document.getElementById("x_ing_fam_deuda_1").value);
				
				
				if(t6 == '')
				n6 = 0;
				else
				n6 = parseFloat(document.getElementById("x_ing_fam_deuda_2").value);
				
				total =(n1 + n2 +n3 +n4)-(n5 + n6);	
				
				
				document.getElementById("x_ing_fam_total").value = total;			
				
				}
				
				function flujosDeNegocio (){
					
					t1 = document.getElementById("x_flujos_neg_ventas").value;
					t2 = document.getElementById("x_flujos_neg_proveedor_1").value;
					t3 = document.getElementById("x_flujos_neg_proveedor_2").value;
					t4 = document.getElementById("x_flujos_neg_proveedor_3").value;
					t5 = document.getElementById("x_flujos_neg_proveedor_4").value;
					t6 = document.getElementById("x_flujos_neg_gasto_1").value;
					t7 = document.getElementById("x_flujos_neg_gasto_2").value;
					t8 = document.getElementById("x_flujos_neg_gasto_3").value;
					total = 0;
					
					if(t1 == '')
					n1 = 0;
					else
					n1 = parseFloat(document.getElementById("x_flujos_neg_ventas").value);
					
					if(t2 == '')
					n2 = 0;
					else
					n2 = parseFloat(document.getElementById("x_flujos_neg_proveedor_1").value);
					
					if(t3 == '')
					n3 = 0;
					else
					n3 = parseFloat(document.getElementById("x_flujos_neg_proveedor_2").value);
					
					if(t4 == '')
					n4 = 0;
					else
					n4 = parseFloat(document.getElementById("x_flujos_neg_proveedor_3").value);
					
					if(t5 == '')
					n5 = 0;
					else
					n5 = parseFloat(document.getElementById("x_flujos_neg_proveedor_4").value);
					
					if(t6 == '')
					n6 = 0;
					else
					n6 = parseFloat(document.getElementById("x_flujos_neg_gasto_1").value);
					
					if(t7 == '')
					n7 = 0;
					else
					n7 = parseFloat(document.getElementById("x_flujos_neg_gasto_2").value);
					
					if(t8 == '')
					n8 = 0;
					else
					n8 = parseFloat(document.getElementById("x_flujos_neg_gasto_3").value);
					
					
					total = ((n1)-(n2 + n3 + n4 + n5 +n6 +n7 +n8));					
					
					 document.getElementById("x_ingreso_negocio").value = total;
					 }
	 
	 
 
	 }//fin function eventos Maquinaria
	 
function eventosFormatoPYME(){
	alert("Credito para PYME")
	//EVE2NTOS PARA ACTUALIZAR FORMATO PYME
	//document.getElementById("siguiente").onclick = muestraOculta;
	//document.getElementById("anterior").onclick = muestraOculta;
	//document.getElementById("enviar").onclick = EW_checkMyForm;
	//document.getElementById("seEnvioFormulario").onclick = ocultaMens;	

    document.getElementById("x_inv_neg_var_valor_1").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_2").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_3").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_4").onchange = totalVarible;
	document.getElementById("x_inv_neg_fija_valor_1").onchange= totalFija;
	document.getElementById("x_inv_neg_fija_valor_2").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_3").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_4").onchange = totalFija;
	
/*	document.getElementById("x_ing_fam_negocio").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_otro_th").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_2").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_2").onchange = ingresoFamiliar;*/
	
	document.getElementById("x_flujos_neg_ventas").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_3").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_4").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_3").onchange = flujosDeNegocio;
	
	
	
function muestraOculta(){
 	var id = this.id; 
 	if(id =="siguiente"){
		document.getElementById("paginaUno").style.display ="none";
		document.getElementById("paginaDos").style.display ="block";
		document.getElementById("siguiente").style.display="none";
		document.getElementById("anterior").style.display="block";	
		} else{
			document.getElementById("paginaUno").style.display ="block";
			document.getElementById("paginaDos").style.display ="none";
			document.getElementById("anterior").style.display="none";
			document.getElementById("siguiente").style.display="block";
			}
 }//function muestraOculta 
	 
		function totalVarible (){
			t1 = document.getElementById("x_inv_neg_var_valor_1").value;
			t2 = document.getElementById("x_inv_neg_var_valor_2").value;
			t3 = document.getElementById("x_inv_neg_var_valor_3").value;
			t4 = document.getElementById("x_inv_neg_var_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_var_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_var_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_var_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_var_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_fija").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_fija").value);
			
			
			document.getElementById("x_inv_neg_total_var").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}
	 
	     
	 
	function totalFija (){
			t1 = document.getElementById("x_inv_neg_fija_valor_1").value;
			t2 = document.getElementById("x_inv_neg_fija_valor_2").value;
			t3 = document.getElementById("x_inv_neg_fija_valor_3").value;
			t4 = document.getElementById("x_inv_neg_fija_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_fija_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_fija_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_fija_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_fija_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_var").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_var").value);
			
			
			document.getElementById("x_inv_neg_total_fija").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}	
			
			function ingresoFamiliar(){
				t1 = document.getElementById("x_ing_fam_negocio").value;
				t2 = document.getElementById("x_ing_fam_otro_th").value;
				t3 = document.getElementById("x_ing_fam_1").value;
				t4 = document.getElementById("x_ing_fam_2").value;
				t5 = document.getElementById("x_ing_fam_deuda_1").value;
				t6 = document.getElementById("x_ing_fam_deuda_2").value;
				
				if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_ing_fam_negocio").value);
				
				if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_ing_fam_otro_th").value);
				
				
				if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_ing_fam_1").value);
				
				if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_ing_fam_2").value);
				
				if(t5 == '')
				n5 = 0;
				else
				n5 = parseFloat(document.getElementById("x_ing_fam_deuda_1").value);
				
				
				if(t6 == '')
				n6 = 0;
				else
				n6 = parseFloat(document.getElementById("x_ing_fam_deuda_2").value);
				
				total =(n1 + n2 +n3 +n4)-(n5 + n6);	
				
				
				document.getElementById("x_ing_fam_total").value = total;			
				
				}
				
				function flujosDeNegocio (){
					
					t1 = document.getElementById("x_flujos_neg_ventas").value;
					t2 = document.getElementById("x_flujos_neg_proveedor_1").value;
					t3 = document.getElementById("x_flujos_neg_proveedor_2").value;
					t4 = document.getElementById("x_flujos_neg_proveedor_3").value;
					t5 = document.getElementById("x_flujos_neg_proveedor_4").value;
					t6 = document.getElementById("x_flujos_neg_gasto_1").value;
					t7 = document.getElementById("x_flujos_neg_gasto_2").value;
					t8 = document.getElementById("x_flujos_neg_gasto_3").value;
					total = 0;
					
					if(t1 == '')
					n1 = 0;
					else
					n1 = parseFloat(document.getElementById("x_flujos_neg_ventas").value);
					
					if(t2 == '')
					n2 = 0;
					else
					n2 = parseFloat(document.getElementById("x_flujos_neg_proveedor_1").value);
					
					if(t3 == '')
					n3 = 0;
					else
					n3 = parseFloat(document.getElementById("x_flujos_neg_proveedor_2").value);
					
					if(t4 == '')
					n4 = 0;
					else
					n4 = parseFloat(document.getElementById("x_flujos_neg_proveedor_3").value);
					
					if(t5 == '')
					n5 = 0;
					else
					n5 = parseFloat(document.getElementById("x_flujos_neg_proveedor_4").value);
					
					if(t6 == '')
					n6 = 0;
					else
					n6 = parseFloat(document.getElementById("x_flujos_neg_gasto_1").value);
					
					if(t7 == '')
					n7 = 0;
					else
					n7 = parseFloat(document.getElementById("x_flujos_neg_gasto_2").value);
					
					if(t8 == '')
					n8 = 0;
					else
					n8 = parseFloat(document.getElementById("x_flujos_neg_gasto_3").value);
					
					
					total = ((n1)-(n2 + n3 + n4 + n5 +n6 +n7 +n8));					
					
					 document.getElementById("x_ingreso_negocio").value = total;
					 }
	
	
	}	 
	 
	 
 function eventosFormatoIndividualPersonal(){
	alert("Credito Personal");
	//EVE2NTOS PARA ACTUALIZAR FORMATO PYME
	//document.getElementById("siguiente").onclick = muestraOculta;
	//document.getElementById("anterior").onclick = muestraOculta;
	//document.getElementById("enviar").onclick = EW_checkMyForm;
	//document.getElementById("seEnvioFormulario").onclick = ocultaMens;	

    document.getElementById("x_inv_neg_var_valor_1").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_2").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_3").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_4").onchange = totalVarible;
	document.getElementById("x_inv_neg_fija_valor_1").onchange= totalFija;
	document.getElementById("x_inv_neg_fija_valor_2").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_3").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_4").onchange = totalFija;
	document.getElementById("x_ing_fam_negocio").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_otro_th").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_2").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_2").onchange = ingresoFamiliar;
	document.getElementById("x_flujos_neg_ventas").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_3").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_4").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_3").onchange = flujosDeNegocio;
	
	
	
function muestraOculta(){
 	var id = this.id; 
 	if(id =="siguiente"){
		document.getElementById("paginaUno").style.display ="none";
		document.getElementById("paginaDos").style.display ="block";
		document.getElementById("siguiente").style.display="none";
		document.getElementById("anterior").style.display="block";	
		} else{
			document.getElementById("paginaUno").style.display ="block";
			document.getElementById("paginaDos").style.display ="none";
			document.getElementById("anterior").style.display="none";
			document.getElementById("siguiente").style.display="block";
			}
 }//function muestraOculta 
	 
		function totalVarible (){
			t1 = document.getElementById("x_inv_neg_var_valor_1").value;
			t2 = document.getElementById("x_inv_neg_var_valor_2").value;
			t3 = document.getElementById("x_inv_neg_var_valor_3").value;
			t4 = document.getElementById("x_inv_neg_var_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_var_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_var_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_var_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_var_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_fija").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_fija").value);
			
			
			document.getElementById("x_inv_neg_total_var").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}
	 
	     
	 
	function totalFija (){
			t1 = document.getElementById("x_inv_neg_fija_valor_1").value;
			t2 = document.getElementById("x_inv_neg_fija_valor_2").value;
			t3 = document.getElementById("x_inv_neg_fija_valor_3").value;
			t4 = document.getElementById("x_inv_neg_fija_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_fija_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_fija_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_fija_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_fija_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_var").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_var").value);
			
			
			document.getElementById("x_inv_neg_total_fija").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}	
			
			function ingresoFamiliar(){
				t1 = document.getElementById("x_ing_fam_negocio").value;
				t2 = document.getElementById("x_ing_fam_otro_th").value;
				t3 = document.getElementById("x_ing_fam_1").value;
				t4 = document.getElementById("x_ing_fam_2").value;
				t5 = document.getElementById("x_ing_fam_deuda_1").value;
				t6 = document.getElementById("x_ing_fam_deuda_2").value;
				
				if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_ing_fam_negocio").value);
				
				if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_ing_fam_otro_th").value);
				
				
				if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_ing_fam_1").value);
				
				if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_ing_fam_2").value);
				
				if(t5 == '')
				n5 = 0;
				else
				n5 = parseFloat(document.getElementById("x_ing_fam_deuda_1").value);
				
				
				if(t6 == '')
				n6 = 0;
				else
				n6 = parseFloat(document.getElementById("x_ing_fam_deuda_2").value);
				
				total =(n1 + n2 +n3 +n4)-(n5 + n6);	
				
				
				document.getElementById("x_ing_fam_total").value = total;			
				
				}
				
				function flujosDeNegocio (){
					
					t1 = document.getElementById("x_flujos_neg_ventas").value;
					t2 = document.getElementById("x_flujos_neg_proveedor_1").value;
					t3 = document.getElementById("x_flujos_neg_proveedor_2").value;
					t4 = document.getElementById("x_flujos_neg_proveedor_3").value;
					t5 = document.getElementById("x_flujos_neg_proveedor_4").value;
					t6 = document.getElementById("x_flujos_neg_gasto_1").value;
					t7 = document.getElementById("x_flujos_neg_gasto_2").value;
					t8 = document.getElementById("x_flujos_neg_gasto_3").value;
					total = 0;
					
					if(t1 == '')
					n1 = 0;
					else
					n1 = parseFloat(document.getElementById("x_flujos_neg_ventas").value);
					
					if(t2 == '')
					n2 = 0;
					else
					n2 = parseFloat(document.getElementById("x_flujos_neg_proveedor_1").value);
					
					if(t3 == '')
					n3 = 0;
					else
					n3 = parseFloat(document.getElementById("x_flujos_neg_proveedor_2").value);
					
					if(t4 == '')
					n4 = 0;
					else
					n4 = parseFloat(document.getElementById("x_flujos_neg_proveedor_3").value);
					
					if(t5 == '')
					n5 = 0;
					else
					n5 = parseFloat(document.getElementById("x_flujos_neg_proveedor_4").value);
					
					if(t6 == '')
					n6 = 0;
					else
					n6 = parseFloat(document.getElementById("x_flujos_neg_gasto_1").value);
					
					if(t7 == '')
					n7 = 0;
					else
					n7 = parseFloat(document.getElementById("x_flujos_neg_gasto_2").value);
					
					if(t8 == '')
					n8 = 0;
					else
					n8 = parseFloat(document.getElementById("x_flujos_neg_gasto_3").value);
					
					
					total = ((n1)-(n2 + n3 + n4 + n5 +n6 +n7 +n8));					
					
					 document.getElementById("x_ingreso_negocio").value = total;
					 }
	
	
	}	 
 

	
	
	