<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php include ("phpmkrfn.php") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3" align="left" valign="top"><table width="674" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="texto_normal">Promotor:</td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td width="159" class="texto_normal">Tipo de Cr&eacute;dito: </td>
        <td width="155" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td width="109"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="251" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Importe solicitado: </span></td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">Plazo:</span></div></td>
        <td style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><span class="texto_normal">Forma de pago :</span></div></td>
        <td style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="141">&nbsp;</td>
    <td width="433">&nbsp;</td>
    <td width="126">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000">Datos Personales</td>
  </tr>
  
  <tr>
    <td colspan="3"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Nombre(s): </span></td>
        <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
	  <tr>
	    <td class="texto_normal">Apellido Paterno:</td>
	    <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
	    </tr>
	  <tr>
	    <td class="texto_normal">Apellido Materno:</td>
	    <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
	    </tr>
	  <tr>
	  <td class="texto_normal">RFC:</td>
	  <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
	  </tr>
	<tr>
	  <td class="texto_normal">CURP:</td>
	  <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
	  </tr>      
      <tr>
        <td><span class="texto_normal">Tipo de Negocio: </span></td>
        <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha de Nacimiento:</span></td>
        <td colspan="4"><table width="535" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="156" style=" border-bottom: solid 1px #000000">&nbsp;</td>
            <td width="116"><div align="left"><span class="texto_normal">Genero:
              
			</span>__________</div></td>
            <td width="263"><div align="left"><span class="texto_normal">Edo. Civil:
              
            </span>________________________________</div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><span class="texto_normal">No. de hijos
          : </span></td>
        <td colspan="3"><span class="texto_normal">___________________
          Hijos dependientes:
			
        _______</span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nombre del Conyuge:</span></td>
        <td width="535" colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Email</span>:</td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nacionalidad:</span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000">Domicilio Particular </td>
  </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="171"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="166" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td width="66"><div align="right"><span class="texto_normal">Del/Mun: </span></div></td>
        <td width="297" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>

      <tr>
        <td><span class="texto_normal">C.P.
          : </span></td>
        <td colspan="4" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4">____________<span class="texto_normal">
          (a&ntilde;os)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tipo de Vivienda: </td>
        <td colspan="4" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td ><span class="texto_normal">Propietario de la Vivienda:&nbsp;</span></td>
        <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
    <tr>
	  <td class="texto_normal">Tels. Particular: </td>
	  <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
	  </tr>
	<tr>
	  <td class="texto_normal">Tel. Celular: </td>
	  <td colspan="4" style=" border-bottom: solid 1px #000000">&nbsp;</td>
	  </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000">Domicilio del negocio </td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top">


	<table width="700" border="0" cellspacing="0" cellpadding="0">
      
      <tr>
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Puesto: </span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha Contratacion:</span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Salario Mensual: </span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td width="71"><div align="right"><span class="texto_normal">
          Del/Mun: </span></div></td>
        <td width="292" style=" border-bottom: solid 1px #000000"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          :</span></td>
        <td colspan="4" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          __________ (a&ntilde;os)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.: </td>
        <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000"><span class="texto_normal">&nbsp; </span></td>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000 ">Datos Aval </td>
  </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top"><!--	<div id="aval" class="TG_hidden"> -->
    <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="texto_normal">Nombre Completo: </td>
            <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td class="texto_normal">Parentesco:</td>
            <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
          </tr>
<tr>
        <td class="texto_normal">RFC:</td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
      <tr>
        <td class="texto_normal">CURP:</td>
        <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>          
        <tr>
        <td class="texto_normal">Tels.:</td>
        <td colspan="3" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
      <tr>
        <td class="texto_normal">Tel&eacute;fono celular:</td>
        <td colspan="3" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td height="22" class="texto_normal_bold">Domicilio Particular </td>
            <td colspan="3" >&nbsp;</td>
          </tr>
          <tr>
            <td width="173"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
            <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td><span class="texto_normal">Colonia: </span></td>
            <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td><span class="texto_normal">Entidad:</span></td>
            <td width="162" style=" border-bottom: solid 1px #000000">&nbsp;</td>
          <td width="70"><div align="right"><span class="texto_normal">Del/Mun: </span></div></td>
            <td width="291" style=" border-bottom: solid 1px #000000">&nbsp;</td>
          </tr>
          
          <tr>
            <td><span class="texto_normal">C.P.
              : </span></td>
            <td colspan="4" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
            <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td class="texto_normal">Antiguedad en Domicilio: </td>
            <td colspan="4"><span class="texto_normal">
              __________ (a&ntilde;os) </span></td>
        </tr>
          <tr>
            <td class="texto_normal"> Tipo de Vivienda:</td>
            <td colspan="4" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td ><span class="texto_normal">Propietario de la Vivienda:&nbsp;</span></td>
            <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
          </tr>
          <tr>
            <td height="21" class="texto_normal_bold">Domicilio del Negocio </td>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td width="173"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
            <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td><span class="texto_normal">Colonia: </span></td>
            <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td><span class="texto_normal">Entidad:</span></td>
            <td width="162" style=" border-bottom: solid 1px #000000">&nbsp;</td>
          <td width="70"><div align="right"><span class="texto_normal">Del/Mun: </span></div></td>
            <td width="291" style=" border-bottom: solid 1px #000000">&nbsp;</td>
          </tr>
          
          <tr>
            <td><span class="texto_normal">C.P.
              : </span></td>
            <td colspan="4" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
            <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td class="texto_normal">Antiguedad en Domicilio: </td>
            <td colspan="4"><span class="texto_normal">
              __________ (a&ntilde;os) </span></td>
        </tr>
          <tr>
            <td class="texto_normal">Tel.</td>
            <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td height="23" class="texto_normal_bold">Ingresos</td>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td class="texto_normal">Ingresos Mensuales : </td>
            <td colspan="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td class="texto_normal">Otros Ingresos: </td>
            <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
            <td class="texto_normal" ><div align="right"><span class="texto_normal">Origen: </span></div></td>
            <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
            <td width="4" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td class="texto_normal">Ingresos Familiares: </td>
            <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
            <td class="texto_normal"><div align="right">Parentesco: </div></td>
            <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
            <td class="texto_normal">&nbsp;</td>
        </tr>
		<tr>
        <td class="texto_normal">Origen: </td>
        <td colspan="4">&nbsp;</td>
      </tr>          
          <tr>
            <td class="texto_normal">Ocupaci&oacute;n:</td>
            <td colspan="4" class="texto_normal">&nbsp;</td>
        </tr>
        </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000" ><div align="center" class="texto_normal_bold">Garant&iacute;as</div></td>
  </tr>
  
  <tr>
    <td colspan="3"><!-- 	<div id="garantias" class="TG_hidden" > -->
    <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="91"><span class="texto_normal">Descripci&oacute;n</span></td>
            <td width="331" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
            <td width="278" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
          <tr>
            <td><span class="texto_normal">Valor</span></td>
            <td style=" border-bottom: solid 1px #000000">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="texto_normal">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000">Ingresos Mensuales </td>
  </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="150"><span class="texto_normal">Ingresos del Negocio:</span></td>
        <td width="207" class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td width="85" class="texto_normal">&nbsp;</td>
        <td width="258" class="texto_normal">&nbsp;</td>
      </tr>
      
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
		<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000"><div align="left"></div></td>
        <td class="texto_normal">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
		</tr>      
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        </tr>
		<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000"><div align="left"></div></td>
        <td class="texto_normal">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
		</tr>
		<tr>
		  <td><span class="texto_normal">Otros Ingresos: </span></td>
		  <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
		  <td class="texto_normal">&nbsp;</td>
		  <td class="texto_normal">&nbsp;</td>
	    </tr>
		<tr>
		  <td><span class="texto_normal">Origen:</span></td>
		  <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
		  <td class="texto_normal">&nbsp;</td>
		  <td class="texto_normal">&nbsp;</td>
	    </tr>        

    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000">Referencias</td>
  </tr>
  <tr>
    <td colspan="3" class="texto_normal"></td>
  </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Nombre</span></td>
        <td width="84" class="texto_normal">Tel&eacute;fono</td>
        <td width="163" class="texto_normal">Parentesco</td>
      </tr>
      <tr>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
        <td class="texto_normal" style=" border-bottom: solid 1px #000000">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 
  <tr>
    <td colspan="3" style=" border-bottom: solid 1px #000000; border-top: solid 1px #000000" ><div align="center" class="texto_normal_bold">T&eacute;rminos y condiciones </div></td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="34">&nbsp;</td>
        <td width="600" class="texto_small">
        Fecha: M&eacute;xico, D.F. a __________________________________________________________________		</td>
        <td width="66">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left" class="texto_small">Por este conducto autorizo expresamente a Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R., para que por conducto de sus funcionarios facultados lleve a cabo Investigaciones, sobre mi comportamiento e historia&iacute; crediticio, asi como de cualquier otra informaci&oacute;n de naturaleza an&aacute;loga, en las Sociedades de Informaci&oacute;n Crediticia que estime conveniente. As&iacute; mismo, declaro que conozco la naturaleza y alcance de la informaci&oacute;n que se solicitar&aacute;, del uso que Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R. har&aacute; de ta&iacute; informaci&oacute;n y de que &eacute;sta podr&aacute; realzar consultas peri&oacute;dicas de mi historial crediticio, consintiendo que esta autorizaci&oacute;n se encuentre vigente por un periodo de 3 a&ntilde;os contados a partir de la fecha de su expedici&oacute;n y
            en todo caso durante el tiempo que mantengamos una relaci&oacute;n jur&iacute;dica. Estoy conciente y acepto que este documento quede bajo propiedad de Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R. para efectos de control y cumplimiento del art. 28 de la Ley para regular a las Sociedades e informaci&oacute;n Cr&eacute;diticia. <br />
            <br />
            De acuerdo al Capítulo II, Sección Primera, Artículo 3, cláusula cuatro de la Ley para la Transparencia y Ordenamiento de los Servicios Financieros Aplicables a los Contratos de Adhesión, Publicidad, Estados de Cuenta y Comprobantes de Operación de las Sociedades Financieras de Objeto Múltiple No Reguladas; por éste medio expreso mi consentimiento que a través del personal facultado de &quot;Microfinanciera Crece SOFOM ENR&quot;, he sido enterado del Costo Anual Total del crédito que estoy interesado en celebrar. También he sido enterado de la tasa de interés moratoria que se cobrará en caso de presentar atraso(s) en alguno(s) de los vencimientos del préstamo. También de acuerdo al Capítulo IV, Sección Primera, Artículo 23 de la misma; estoy de acuerdo en consultar mi estado de cuenta a través de internet mediante la página www.financieracrece.com con el usuario y contraseña que &quot;Microfinanciera
          Crece SOFOM ENR&quot; a través de su personal facultado me hagan saber toda vez que se firme el pagaré correspondiente al crédito que estoy interesado en pactar.</div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55"><div align="center"></div></td>
            <td width="245" class="texto_normal">Acepto estos T&eacute;rminos y condiciones.</td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table width="639" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td class="texto_normal"><div align="center">CLIENTE</div></td>
        <td class="texto_normal">&nbsp;</td>
        <td class="texto_normal"><div align="center">AVAL</div></td>
      </tr>
      <tr>
        <td width="114">&nbsp;</td>
        <td width="218">&nbsp;</td>
        <td width="53" class="texto_normal">&nbsp;</td>
        <td width="254">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Firma:</td>
        <td>____________________________________</td>
        <td>&nbsp;</td>
        <td>_______________________________________</td>
      </tr>
      <tr>
        <td class="texto_normal">Nombre:</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>