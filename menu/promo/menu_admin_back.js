stBM(1,"tree6575",[0,"","","menu/blank.gif",0,"left","default","hand",1,0,28,201,-1,"none",0,"#666666","#eaedef","","repeat",1,"menu/0609_f.gif","menu/0609_uf.gif",9,9,0,"","","","",0,0,0,3,"right",0]);
stBS("p0",[0,0]);
stIT("p0i0",["","#","_self","","","","",20,5,"8pt 'Arial'","#000000","none","transparent","","repeat","8pt 'Arial'","#0099FF","none","transparent","","repeat","8pt 'Arial'","#0099FF","none","transparent","","repeat","8pt 'Arial'","#0099FF","none","transparent","","repeat",1]);
stIT("p0i1",["       Menu Prinicpal",,,,,,,0,33,"bold 8pt 'Arial'",,,"#0099CC","menu/titlebutton.gif",,"bold 8pt 'Arial'","#000000",,,"menu/titlebutton.gif",,"bold 8pt 'Arial'","#000000",,,"menu/titlebutton.gif",,"bold 8pt 'Arial'","#000000",,,"menu/titlebutton.gif"],"p0i0");
stBS("p1",[,1],"p0");
stIT("p1i0",["Inicio","php_inicio.php",,,,,,0,0,,,,,"menu/back.gif",,,"#0000FF",,,,,,"#0000FF"],"p0i0");
stIT("p1i1",["Mensajer�a","php_mensajeria.php"],"p1i0");
stIT("p1i2",["Contactos","php_contactolist.php?cmd=resetall"],"p1i0");
stIT("p1i3",["Clientes","#"],"p1i0");
stBS("p2",[],"p0");
stIT("p2i0",["Listado","php_clientelist.php?cmd=resetall"],"p1i0");
stIT("p2i1",["Avales","php_avallist.php?cmd=resetall"],"p1i0");
stIT("p2i2",["Referencias","php_referencialist.php?cmd=resetall"],"p1i0");
stIT("p2i3",["Garantias","php_garantialist.php?cmd=resetall"],"p1i0");
stES();
stIT("p1i4",["Cerrar Sesion","logout.php"],"p1i0");
stES();
stIT("p0i2",["       Solicitudes"],"p0i1");
stBS("p3",[],"p1");
stIT("p3i0",["Elaborar nueva solicitud","php_solicitudadd.php"],"p1i0");
stIT("p3i1",["Ver listado","php_solicitudlist.php?cmd=resetall"],"p1i0");
stIT("p3i2",["Contratos","php_contratos.php"],"p1i0");
stIT("p3i3",["Pagares","php_pagares.php"],"p1i0");
stES();
stIT("p0i3",["       Cr�ditos"],"p0i1");
stBS("p4",[],"p1");
stIT("p4i0",["Otorgar cr�dito","php_creditoadd.php"],"p1i0");
stIT("p4i1",[,"php_creditolist.php?cmd=resetall"],"p2i0");
stIT("p4i2",["Tipos de Credito","php_credito_tipolist.php?cmd=resetall"],"p1i0");
stES();
stIT("p0i4",["       Vencimientos"],"p0i1");
stBS("p5",[],"p1");
stIT("p5i0",["Generar Cobranza","php_cobranza_gen.php"],"p1i0");
stIT("p5i1",["Cartera Vencida","php_cartera_vencida.php"],"p1i0");
stIT("p5i2",["Recibos","php_recibolist.php?cmd=resetall"],"p1i0");
stES();
stIT("p0i5",["       Promotores"],"p0i1");
stBS("p6",[],"p1");
stIT("p6i0",["Nuevo","php_promotoradd.php"],"p1i0");
stIT("p6i1",[,"php_promotorlist.php?cmd=resetall"],"p2i0");
stIT("p6i2",["Comisiones","php_promotor_comisiones.php"],"p1i0");
stES();
stIT("p0i6",["       Reportes"],"p0i1");
stBS("p7",[],"p1");
stIT("p7i0",[,"php_rpt_clientes.php"],"p1i3");
stIT("p7i1",["Solicitudes","php_rpt_solicitudes.php"],"p1i0");
stIT("p7i2",["Creditos","php_rpt_creditos.php"],"p1i0");
stIT("p7i3",["Cobranza","php_rpt_cobranza.php"],"p1i0");
stIT("p7i4",[,"php_rpt_cartera_venc.php"],"p5i1");
stIT("p7i5",["Contabilidad","php_rpt_contabilidad.php"],"p1i0");
stES();
stIT("p0i7",["       Cat�logos"],"p0i1");
stBS("p8",[],"p1");
stIT("p8i0",["Usuarios","php_usuariolist.php?cmd=resetall"],"p1i0");
stIT("p8i1",[,"php_mensajeria_admin.php"],"p1i1");
stIT("p8i2",["Medios de Pago","php_medio_pagolist.php?cmd=resetall"],"p1i0");
stIT("p8i3",["Estados Civiles","php_estado_civillist.php?cmd=resetall"],"p1i0");
stIT("p8i4",["Parentescos","php_parentesco_tipolist.php?cmd=resetall"],"p1i0");
stIT("p8i5",["Tipos de Vivienda"],"p8i4");
stIT("p8i6",["Formatos","php_formato_doctolist.php?cmd=resetall"],"p1i0");
stIT("p8i7",["Delegaciones","php_delegacionlist.php?cmd=resetall"],"p1i0");
stES();
stIT("p0i8",[],"p0i0");
stES();
stEM();
