iconoEHT = 'http://'+window.location.host+'/mapa/img/EHT25.png';// Estación Hidroclimatológica Telemétrica
iconoEC  = 'http://'+window.location.host+'/mapa/img/ECnew2.png'; // Estación Climatológica
iconoECT = 'http://'+window.location.host+'/mapa/img/ECTnewblued.png';//Estación Climatológica Telemétrica
iconoSN  = 'http://'+window.location.host+'/mapa/img/nivelb23.png'; // Sensores de Nivel
iconoPD  = 'http://'+window.location.host+'/mapa/img/PD30.png'; // Pluviómetros con Datalogger, No Telemétricos
iconoPDT  = 'http://'+window.location.host+'/mapa/img/PDT.png'; // Pluviómetros con Datalogger, Telemétricos
iconoPos  = 'http://'+window.location.host+'/mapa/img/punto.png';
// New added 01-02-2015
iconoENT  = 'http://'+window.location.host+'/mapa/img/ENT.png'; // Estaciones de Nivel Telemétrica
iconoEQT  = 'http://'+window.location.host+'/mapa/img/caudal.png'; // Estaciones de Caudal Telemétricas
// Pozos added 27-07-2017
iconoPozo  = 'http://'+window.location.host+'/mapa/img/pozo.png'; // Estaciones de Pozo similar a las de Nivel

estacionesJSON = [
  		{
  			"id" : "46",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT001 (El Lago)",
		    "carpeta": "el lago",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "radiacion",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.814751659,
		      "longitud": -75.69949032
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "1450 m.s.n.m",
		    "ubicacion": "Centro Administrativo el Lago, Centro del municipio de Pereira",
		    "fecha": "Septiembre de 2006",
		    "estado": "Activa"	  
		},
		{
			"id" : "45",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT002 (Cortaderal)",
		    "carpeta": "cortaderal",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "radiacion",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.750273723,
		      "longitud": -75.490426
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "3700 m.s.n.m",
		    "ubicacion": "Cuenca alta del Río Otún, Parque NN Los Nevados",
		    "fecha": "23 de Diciembre de 2009",
		    "estado": "Inactiva"	  		  
		},		
		{
			"id" : "47",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT004 (Alto del Nudo)",
		    "carpeta": "el nudo",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "radiacion",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.872101755,
		      "longitud": -75.70935144
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "2002 m.s.n.m",
		    "ubicacion": "Vereda Las Hortensias, PRN Alto del Nudo",
		    "fecha": "1 de Abril de 2011",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "48",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT005 (Quinchía - Seafield)",
		    "carpeta": "quinchia seafield",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "radiacion",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 5.295278392,
		      "longitud": -75.69944588
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "1673 m.s.n.m",
		    "ubicacion": "Vereda Miraflores, Municipo de Quinchia, Risaralda",
		    "fecha": "15 de Marzo de 2012",
		    "estado": "Activa"	  		  
		},
		// Added March 2nd 2016
		{
			"id" : "51",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT006 (CAM)",
		    "carpeta": "cam",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "radiacion",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.833718335,
		      "longitud": -75.67106802
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "2 de Marzo de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "50",
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT007 (Mairabajo - Belén de Umbría)",
		    "carpeta": "mairabajo",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "nivel",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 5.257036639,
		      "longitud": -75.83335167
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "18 de Julio de 2016",
		    "estado": "Activa"	  		  
		},
		// 4 Next stations added Feb 4th 2018
		{
			"id" : "56",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT009 (El Bosque)",
		    "carpeta": "el bosque",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "nivel",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.740882052,
		      "longitud": -75.44244535
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "4 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "57",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT010 (La Argentina)",
		    "carpeta": "la argentina",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "nivel",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.731862605,
		      "longitud": -75.44713981
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "4 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "61",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT011 (El Cedral)",
		    "carpeta": "el cedral ect",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "nivel",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.702895856,
		      "longitud": -75.53377329
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "4 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},/*  This one already exist I just need to change the position*/
		{
			"id" : "58",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT003 (San José)",
		    "carpeta": "san jose ect",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "humedad",
		      "3": "nivel",
		      "4": "presion",
		      "5": "velocidad",
		      "6": "direccion",
		      "7": "evapotranspiracion"
		    },
		    "coordenadas": {
		      "latitud": 4.823268326,
		      "longitud": -75.60587622
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "4 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		// End of 4 stations added Feb 4th 2018
		{ // Station added Nov 26 2018, it was just an EC and it was converted to ECT and fixed the coordinates
			"id" : "57",// New table id Nov 26 2018 its at UTP
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT012 (Mundo Nuevo)",
		    "carpeta":"mundo nuevo",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.773618228, 
		      "longitud": -75.66105968  
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "1550 m.s.n.m",
		    "ubicacion": "Planta de Tratamiento de Aguas, Vereda Mundo Nuevo",
		    "fecha": "Diciembre de 2007",
		    "estado": "Activa"	  		  
		},
		{ // Station added Nov 26 2018
			"id" : "59",// New table id Nov 26 2018 its at UTP
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT013 (La Colonia)",
		    "carpeta":"la colonia",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.751043171, 
		      "longitud": -75.62707072  
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "Noviembre 26 de 2018",
		    "estado": "Activa"	  		  
		},
		{ // Station added Nov 26 2018
			"id" : "60",// New table id Nov 26 2018 its at UTP
  			"nombrelargo": "Estaciones Climatológicas Telemétricas (ECT)",
		    "nombre": "ECT014 (Ukumarí)",
		    "carpeta":"ukumari",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.803568281, 
		      "longitud": -75.8103572  
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "Noviembre 26 de 2018",
		    "estado": "Activa"	  		  
		},
		{ // Station added Nov 26 2018
			"id" : "61",// New table id Nov 26 2018 its at UTP
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT015 (La Curva)",
		    "carpeta":"la curva",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.783115488, 
		      "longitud": -75.68810416
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "Noviembre 26 de 2018",
		    "estado": "Activa"	  		  
		},
		{ // Station added Nov 26 2018
			"id" : "62",// New table id Nov 26 2018 its at UTP
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT016 (La Católica)",
		    "carpeta":"la catolica",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.806909961, 
		      "longitud": -75.72643758
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "Noviembre 26 de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "34",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT001 (El Cedral)",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.70317919,
		      "longitud": -75.53658441
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "2080 m.s.n.m",
		    "ubicacion": "Cuenca Media-Alta Río Otún Estación Hidrobiológica Aguas y Aguas",
		    "fecha": "27 de Marzo de 2010",
		    "estado": "Activa"	  		  
		},/*
		{
			"id" : "0",// Pending for ID on new db
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT002 (San Juan)",
		    "carpeta": "san juan",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.756979306,
		      "longitud": -75.59752067
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "2080 m.s.n.m",
		    "ubicacion": "Cuenca Media-Alta Río Otún Estación Hidrobiológica Aguas y Aguas",
		    "fecha": "27 de Marzo de 2010",
		    "estado": "Activa"	  		  
		},*/
		// Added March 2nd 2016
		{
			"id" : "39", 
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT003 (Totui)",
		     "carpeta": "totui",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.970768616,
		      "longitud": -75.93162134
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "2 de Marzo de 2016",
		    "estado": "Activa"	  		  
		},
		// Last 4 stations created July 18th 2016
		{
			"id" : "40",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT004 (Río Guatica)",
		    "carpeta": "rio guatica",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 5.278800582,
		      "longitud": -75.82782112
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "18 de Julio de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "41",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT005 (Rio Mistrató)",
		     "carpeta": "rio mistrato",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 5.248022748,
		      "longitud": -75.84114614
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "18 de Julio de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "42",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT006 (El Diamante - Río Risaralda)",
		     "carpeta": "el diamante- rio risaralda",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 5.23686437,
		      "longitud": -75.81181276
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "18 de Julio de 2016",
		    "estado": "Activa"	  		  
		},
		// ESTACIONES SERVER 2		
		{
			"id" : "52",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT007 (Antes de Bocatoma Nuevo Libaré)",
		    "carpeta": "antes de bocatoma nuevo libare",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.78061548,
		      "longitud": -75.64479298
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "9 de Agosto de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "51",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT008 (Quebrada Volcanes - Las Mangas)",
		    "carpeta": "quebrada volcanes - las mangas",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.78345435,
		      "longitud": -75.63460127
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "9 de Agosto de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "54",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT009 (Río Azul - El Jordan)",
		     "carpeta": "rio azul - el jordan",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.73277094,
		      "longitud": -75.45494814
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "9 de Agosto de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "55",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT010 (Río Otún - El Jordan)",
		    "carpeta": "rio otun - el jordan",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.73420425,
		      "longitud": -75.45445092
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "9 de Agosto de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "53",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT011 (Río Barbo - Boc. Pezfresco)",
		    "carpeta": "rio barbo - boc pezfresco",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.73219872,
		      "longitud": -75.56929003
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "9 de Agosto de 2016",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "60",
			"bd" : "bd_aguas_new",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT012 (Río San Juan - Desembocadura)",
		    "carpeta": "rio san juan - desembocadura",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.75697931,
		      "longitud": -75.59752067
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "9 de Agosto de 2016",
		    "estado": "Activa"	  		  
		},
		// FIN ESTACIONES SERVER 2
		// 2 Estaciónes agregadas el 26 de Diciembre de 2017
		{
			"id" : "43",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT013 (Casa de maquinas - Río Risaralda)",
		    "carpeta": "casa de maquinas - rio risaralda",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 5.18854483,
		      "longitud": -75.81336554
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "26 de Diciembre de 2017",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "44",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT 014 (La Nona)",
		    "carpeta": "la nona",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.88294624,
		      "longitud": -75.7169181
		    },
		    "isPublic": true,
		    "tipo":"EHT",
		    "icono": iconoEHT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "26 de Diciembre de 2017",
		    "estado": "Activa"	  		  
		},
		//
		{
			"id":"35",
  			"nombrelargo": "Estaciones de Nivel Telemétricas (ENT)",
		    "nombre": "ENT001 (Bocatoma nuevo Libaré)",
            "carpeta": "bocatoma nuevo libare",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.781601593,
		      "longitud": -75.64497076
		    },
		    "isPublic": true,
		    "tipo":"ENT",
		    "icono": iconoENT, 
		    "altitud": "1535 m.s.n.m",
		    "ubicacion": "Bocatoma nuevo Libaré ",
		    "fecha": "14 de Junio de 2013",
		    "estado": "Activa"	  		  
		},
		{
			"id":"37",
  			"nombrelargo": "Estaciones de Nivel Telemétricas (ENT)",
		    "nombre": "ENT002 (Bocatoma Belmonte) ",
            "carpeta": "bocatoma belmonte",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.818462752,
		      "longitud": -75.72730703
		    },
		    "isPublic": true,
		    "tipo":"ENT",
		    "icono": iconoENT, 
		    "altitud": "1278 m.s.n.m",
		    "ubicacion": "Bocatoma Belmonte ",
		    "fecha": "14 de Junio de 2013",
		    "estado": "Activa"	  		  
		},
		{
			"id":"36",
  			"nombrelargo": "Estaciones de Nivel Telemétricas (ENT)",
		    "nombre": "ENT003 (Canal entrada Belmonte) ",
            "carpeta": "canal entrada belmonte",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.818390529,
		      "longitud": -75.7277487
		    },
		    "isPublic": false,
		    "tipo":"ENT",
		    "icono": iconoENT,  
		    "altitud": "1277 m.s.n.m",
		    "ubicacion": "Canal entrada Belmonte ",
		    "fecha": "29 de julio de 2013",
		    "estado": "Activa"	  		  
		},{
			"id":"0",// Pending for ID 
  			"nombrelargo": "Estaciones de Nivel Telemétricas (ENT)",
		    "nombre": "ENT004 (Canal Salida Belmonte) ",
            "carpeta": "canal salida belmonte",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.817601637,
		      "longitud": -75.772546
		    },
		    "isPublic": false,
		    "tipo":"ENT",
		    "icono": iconoENT, 
		    "altitud": "1240 m.s.n.m",
		    "ubicacion": "Canal Salida Belmonte ",
		    "fecha": "29 de julio de 2013",
		    "estado": "Activa"	  		  
		},
		
		
		{
			"id":"52",
  			"nombrelargo": "Estaciones de Caudal Telemétricas (EQT)",
		    "nombre": "Planta de Generación electrica  Empresa de Energía de Pereira barrio Nuevo Libaré ",
            "carpeta": "planta nuevo libare",
		    "variables": {
		    	"0": "caudal"
		    },
		    "coordenadas": {
		      "latitud": 4.804343284,
		      "longitud": -75.66661526
		    },
		    "isPublic": false,
		    "tipo":"EQT",
		    "icono": iconoEQT, 
		    "altitud": "1240 m.s.n.m",
		    "ubicacion": "Nuevo Libaré ",
		    "fecha": "29 de julio de 2013",
		    "estado": "Activa"	  		  
		},
		{
			"id":"53",
  			"nombrelargo": "Estaciones de Caudal Telemétricas (EQT)",
		    "nombre": "Planta de Generación electrica belmonte Empresa de Energía de Pereira ",
            "carpeta": "planta belmonte",
		    "variables": {
		    	"0": "caudal"
		    },
		    "coordenadas": {
		      "latitud": 4.815651663,
		      "longitud": -75.77360156
		    },
		    "isPublic": false,
		    "tipo":"EQT",
		    "icono": iconoEQT, 
		    "altitud": "1240 m.s.n.m",
		    "ubicacion": "Belmonte ",
		    "fecha": "29 de julio de 2013",
		    "estado": "Activa"	  		  
		},				
		{
			"id" : "49",
  			"nombrelargo": "Estaciones Climatológicas no Telemétricas (EC)",
		    "nombre": "EC001 (Acuaseo)",
		    "carpeta": "acuaseo",
		    "variables": {
		      "0": "Temperatura",
		      "1": "Precipitacion",
		      "2": "Humedad Relativa",
		      "3": "Radiación Solar",
		      "4": "Presión Barométrica",
		      "5": "Velocidad del Viento",
		      "6": "Dirección del Viento"
		    },
		    "coordenadas": {
		      "latitud": 4.861723969,
		      "longitud": -75.65464021
		    },
		    "isPublic": true,
		    "tipo":"EC",
		    "icono": iconoECT,
		    "altitud": "1550 m.s.n.m",
		    "ubicacion": "Planta de Tratamiento de Agua, compañía de servicios públicos Acuaseo, Dosquebradas",
		    "fecha": "Diciembre de 2007",
		    "estado": "Activa"	  		  
		},		
		{
			"id" : "0",
  			"nombrelargo": "Estaciones Climatológicas no Telemétricas (EC)",
		    "nombre": "EC005 (Universidad Católica)",
		    "carpeta": "universidad catolica",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.80414884,
		      "longitud": -75.72504036
		    },
		    "isPublic": true,
		    "tipo":"EC",
		    "icono": iconoEC,
		    "altitud": "1550 m.s.n.m",
		    "ubicacion": "",
		    "fecha": "Diciembre de 2007",
		    "estado": "Activa"	  		  
		},
		
		{
			"id" : "0",//"id" : "32",// Its not anymore on the station`s table
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN001 (Quebrada Dalí)",
		    "carpeta": "quebrada dali",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.741718167,
		      "longitud": -75.59009842
		    },
		    "isPublic": true,
		    "tipo":"SN",
		    "icono": iconoSN,
		    "altitud": "1800 m.s.n.m",
		    "ubicacion": "Finca Lisbrán, Cuenca Media Río Otún ",
		    "fecha": "12 de Febrero de 2010 (cambio de ubicación Octubre 2012)",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "0",//"id" : "34",// Its not anymore on the station`s table
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN002 (Quebrada Negra)",
		    "carpeta": "quebrada la negra",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.744059843,
		      "longitud": -75.60326232
		    },
		    "isPublic": true,
		    "tipo":"SN",
		    "icono": iconoSN,
		    "altitud": "1750 m.s.n.m",
		    "ubicacion": "Cerca al Centro Aguas y Aguas Vía al Cedral.",
		    "fecha": "12 de Febrero de 2010",
		    "estado": "Inactiva"	  		  
		},
		{
			"id" : "0",//"id" : "31",// Its not anymore on the station`s table
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN003 (Quebrada El Manzano)",
		    "carpeta": "quebrada el manzano",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.759279315,
		      "longitud": -75.61186236
		    },
		    "isPublic": true,
		    "tipo":"SN",
		    "icono": iconoSN,
		    "altitud": "1700 m.s.n.m",
		    "ubicacion": "Corregimiento de La Florida, Cuenca Media Río Otún ",
		    "fecha": "12 de Febrero de 2010",
		    "estado": "Inactiva"	  		  
		},
		{
			"id" : "0",//"id" : "35",// Its not anymore on the station`s table
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN004 (Quebrada Volcanes) ",
		    "carpeta": "quebrada volcanes",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.783696013,
		      "longitud": -75.63461238
		    },
		    "isPublic": true,
		    "tipo":"SN",
		    "icono": iconoSN,
		    "altitud": "1600 m.s.n.m",
		    "ubicacion": "Cuenca media Río Otún ",
		    "fecha": "3 de Marzo de 2010",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "0",//"id" : "30",// Its not anymore on the station`s table
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN005  (Quebrada San Eustaquio)",
            "carpeta": "quebrada san eustaquio",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.769723768,
		      "longitud": -75.60855679
		    },
		    "isPublic": true,
		    "tipo":"SN",
		    "icono": iconoSN,
		    "altitud": "1700 m.s.n.m",
		    "ubicacion": "Cuenca media Río Otún ",
		    "fecha": "12 de Febrero de 2010",
		    "estado": "Inactiva"	  		  
		},
		{
			"id" : "0",//"id" : "33",// Its not anymore on the station`s table
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN006  (Quebrada El Oso)",
		    "carpeta": "quebrada el oso",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.800371046,
		      "longitud": -75.73376536
		    },
		    "isPublic": true,
		    "tipo":"SN",
		    "icono": iconoSN,
		    "altitud": "1290 m.s.n.m",
		    "ubicacion": "Barrio La Habana Cuba.",
		    "fecha": "23 de Febrero de 2010",
		    "estado": "Activa"	  		  
		},
		
		{
			"id" : "0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN011 (Quebrada Callejones) ",
		    "carpeta": "quebrada callejones",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.736584813,
		      "longitud": -75.56916781
		    },
		    "isPublic": true,
		    "tipo":"SN",
		    "icono": iconoSN,
		    "altitud": "1600 m.s.n.m",
		    "ubicacion": "Cuenca media Río Otún ",
		    "fecha": "3 de Marzo de 2010",
		    "estado": "Activa"	  		  
		},
		/*
		{
			"id":"0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN007  (Humedal Lisbran 1)",
            "carpeta": "01_finca_lisbran",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.738834822,
		      "longitud": -75.58364006
		    },
		    "isPublic": true,
		    "tipo":"SNNT",
		    "icono": iconoSN,
		    "altitud": "1847 m.s.n.m",
		    "ubicacion": "Finca Lisbran",
		    "fecha": "17 de febrero de 2012",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN008 (Humedal Lisbran 2)",
		    "carpeta": "02_finca_lisbran",
		    "variables": {
		    	"0": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.738368153,
		      "longitud": -75.58307064
		    },
		    "isPublic": true,
		    "tipo":"SNNT",
		    "icono": iconoSN,
		    "altitud": "",
		    "ubicacion": "Finca Lisbran",
		    "fecha": "30 de Enero de 2014",
		    "estado": "Activa"	  		  
		},*/
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD001  (Bocatoma Acueducto La Florida) ",
            "carpeta": "boc acueducto la florida",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.744045954,
		      "longitud": -75.60941513
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD, 
		    "altitud": "1750 m.s.n.m",
		    "ubicacion": "Corregimiento de la Florida ",
		    "fecha": "12 de Noviembre de 2009",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD002 (Bocatoma Acueducto La Bella) ",
            "carpeta": "boc acueducto la bella",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.738934822,
		      "longitud": -75.6177679
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1950 m.s.n.m",
		    "ubicacion": "Vereda La Bella",
		    "fecha": "12 de Noviembre de 2009",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD003 (Bocatoma Acueducto Pérez Alto) ",
                    "carpeta": "boc acueducto perez alto",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.711854225,
		      "longitud": -75.66235135
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1700 m.s.n.m",
		    "ubicacion": "Vereda Yarumal ",
		    "fecha": "12 de Noviembre de 2009",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD004 (Bocatoma Acueducto Acuasat Tinajas) ",
            "carpeta": "boc acueducto acuasat tinajas",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.744709846,
		      "longitud": -75.70915421
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1500 m.s.n.m",
		    "ubicacion": "Corregimiento de Altagracia",
		    "fecha": "12 de Noviembre de 2009",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD005 (Bocatoma Acueducto La Honda) ",
            "carpeta": "boc acueducto la honda",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.874440653,
		      "longitud": -75.78304604
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1250 m.s.n.m",
		    "ubicacion": "Vereda La Honda",
		    "fecha": "13 de Noviembre de 2009",
		    "estado": "Activa"	  		  
		},
		/*
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD006 (Finca Lisbran 1)",
            "carpeta": "01_finca_lisbran",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.740557051,
		      "longitud": -75.58052897
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1890 m.s.n.m.",
		    "ubicacion": "Finca Lisbran",
		    "fecha": "28 deMayo 2012",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD007 (Finca Lisbran 2)",
            "carpeta": "02_finca_lisbran",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.742445948,
		      "longitud": -75.58047341
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1861 m.s.n.m.",
		    "ubicacion": "Finca Lisbran",
		    "fecha": "28 deMayo 2012",
		    "estado": "Activa"	  		  
		},*/
		{
			"id" : "0",//"id" : "39",// Its not anymore on the station`s table
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD008 (UTP)",
            "carpeta": "utp",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.793668275,
		      "longitud": -75.69079306
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,//iconoEC,
		    "altitud": "1861 m.s.n.m.",
		    "ubicacion": "UTP",
		    "fecha": "28 deMayo 2012",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD009 (Ormaza)",
            "carpeta": "bomberos ormaza",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.813973878,
		      "longitud": -75.68077917
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1389 m.s.n.m.",
		    "ubicacion": "Cuerpo de bomberos barrio Ormaza ",
		    "fecha": "14 de Marzo de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD010 (CAI Galan)",
            "carpeta": "cai galan",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.821334985,
		      "longitud": -75.70369586
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1330 m.s.n.m.",
		    "ubicacion": "CAI Barrio Galan, Calle 31 con AV del río",
		    "fecha": "14 de Marzo de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD011 (Parque Industrial)",
            "carpeta": "parque industrial",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.821909988,
		      "longitud": -75.7247848
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1348 m.s.n.m.",
		    "ubicacion": "Parque Industrial, Manzana 13 sector B",
		    "fecha": "20 de Marzo de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD012 (CAI Consota)",
            "carpeta": "cai consota",
		    "variables": {
		      "0": "Precipitación"
		    },
		    "coordenadas": {
		      "latitud": 4.801954386,
		      "longitud": -75.7249348
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1333 m.s.n.m.",
		    "ubicacion": "CAI, Barrio Padre Valencia",
		    "fecha": "14 de Marzo de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD013 (CAI Poblado)",
            "carpeta": "cai poblado",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.802251609,
		      "longitud": -75.70397364
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1314 m.s.n.m.",
		    "ubicacion": "CAI Barrio Poblado I",
		    "fecha": "14 de Marzo de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD014 (CAI Villa Verde)",
            "carpeta": "cai villaverde",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.792168269,
		      "longitud": -75.71141256
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1402 m.s.n.m.",
		    "ubicacion": "CAI Barrio Villa Verde",
		    "fecha": "10 de Junio de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD015 (Bosque de Cuba)",
            "carpeta": "bosques de cuba",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.792140491,
		      "longitud": -75.7269987
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1321 m.s.n.m.",
		    "ubicacion": "Conjunto cerrado Bosque de Cuba",
		    "fecha": "10 de Junio de 2014",
		    "estado": "Activa"	  		  
		},		
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD016 (Tanque Villa Santana)",
            "carpeta": "tanque villasantana",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.796982177,
		      "longitud": -75.66864301
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1561 m.s.n.m.",
		    "ubicacion": "Taque de reserva de Agua Barrio Villa Santana",
		    "fecha": "14 de Abril de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD017 (Cruz Roja)",
            "carpeta": "cruz roja",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.807421074,
		      "longitud": -75.69224029
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1400 m.s.n.m.",
		    "ubicacion": "Carrera 15 con Calle 16",
		    "fecha": "21 de Abril de 2014",
		    "estado": "Activa"	  		  
		},
		{
			"id":"0",
  			"nombrelargo": "Pluviómetros con Datalogger, no telemétricos (PD)",
		    "nombre": "PD018 (Saint George School)",
            "carpeta": "colegio saint george",
		    "variables": {
		    	"0": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 4.781084924,
		      "longitud": -75.69473475
		    },
		    "isPublic": true,
		    "tipo":"PD",
		    "icono": iconoPD,
		    "altitud": "1480 m.s.n.m.",
		    "ubicacion": "Saint George School, vía hacia municipio de Armenia",
		    "fecha": "",
		    "estado": "Activa"	  		  
		},
		{
			"id":"38",
  			"nombrelargo": "Pluviómetros con Datalogger, telemétricos (PDT)",
		    "nombre": "PD019 (San Rafael)",
            //"carpeta": "planes de san rafael",
		    "variables": {
		    	"0": "temperatura",
		    	"1": "precipitacion"
		    },
		    "coordenadas": {
		      "latitud": 5.125555824,
		      "longitud": -76.0019139
		    },
		    "isPublic": true,
		    //"tipo":"PDT", Changed in order to allow reports to autoselect
		    "tipo":"PDT",
		    "icono": iconoPDT,
		    "altitud": "1480 m.s.n.m.",
		    "ubicacion": "",
		    "fecha": "",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "0",
  			"nombrelargo": "Estaciones Climatológicas no Telemétricas (EC)",
		    "nombre": "EC004 (Lisbrán)",
		    "carpeta": "lisbran",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.73788204,
		      "longitud": -75.58422895
		    },
		    "isPublic": true,
		    "tipo":"EC",
		    "icono": iconoEC,
		    "altitud": "1550 m.s.n.m",
		    "ubicacion": "",
		    "fecha": "Diciembre 4 de 2015",
		    "estado": "Activa"	  		  
		},
		
		
		
		
		
		
		
		
		
		
		
		{
			"id" : "0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua Subterráneos, no telemétricos (SNS)",
		    "nombre": "SNS001 (Pozo Jamaica)",
		    "carpeta": "jamaica",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.77801546,
		      "longitud": -75.6959792
		    },
		    "isPublic": true,
		    "tipo":"SNS",
		    "icono": iconoPozo,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua Subterráneos, no telemétricos (SNS)",
		    "nombre": "SNS002 (Pozo Cerritos)",
		    "carpeta": "cerritos",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.80373773,
		      "longitud": -75.84493227
		    },
		    "isPublic": true,
		    "tipo":"SNS",
		    "icono": iconoPozo,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua Subterráneos, no telemétricos (SNS)",
		    "nombre": "SNS003 (Pozo Alen+ pro)",
		    "carpeta": "alenpro",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.76209322,
		      "longitud": -75.9066546
		    },
		    "isPublic": true,
		    "tipo":"SNS",
		    "icono": iconoPozo,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua Subterráneos, no telemétricos (SNS)",
		    "nombre": "SNS005 (Pozo Linares)",
		    "carpeta": "linares",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.815996108,
		      "longitud": -75.80399328
		    },
		    "isPublic": true,
		    "tipo":"SNS",
		    "icono": iconoPozo,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "0",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua Subterráneos, no telemétricos (SNS)",
		    "nombre": "SNS004 (Pozo Coca Cola)",
		    "carpeta": "cocacola",
		    "variables": {
		      "0": "temperatura",
		      "1": "precipitacion",
		      "2": "nivel"
		    },
		    "coordenadas": {
		      "latitud": 4.80924886,
		      "longitud": -75.79177382
		    },
		    "isPublic": true,
		    "tipo":"SNS",
		    "icono": iconoPozo,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "",
		    "estado": "Activa"	  		  
		},
		// Wunderground Stations		
		{
			"id" : "ILACELIA2",
			"bd" : "wunderground",
  			"nombrelargo": "La Celia Patio Bonito",
		    "nombre": "La Celia",
		    "carpeta": "la celia patio bonito",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 4.993,
			    "longitud": -75.982
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IBELNDEU2",
			"bd" : "wunderground",
  			"nombrelargo": "Belén de Umbría",
		    "nombre": "Belén de Umbría",
		    "carpeta": "belen de umbria",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 5.203,
			    "longitud": -75.867
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IDOSQUEB2 ",
			"bd" : "wunderground",
  			"nombrelargo": "Dosqeubradas - Japon",
		    "nombre": "Dosqeubradas - Japon",
		    "carpeta": "dosqeubradas - japon",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 4.818,
			    "longitud": -75.679
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IMARSELL3",
			"bd" : "wunderground",
  			"nombrelargo": "Marsella",
		    "nombre": "Marsella",
		    "carpeta": "marsella",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 4.936,
			    "longitud": -75.740
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IMISTRAT2",
			"bd" : "wunderground",
  			"nombrelargo": "Mistrató",
		    "nombre": "Mistrató",
		    "carpeta": "mistrato",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 5.297,
			    "longitud": -75.883
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IPEREIRA2",
			"bd" : "wunderground",
  			"nombrelargo": "El Jardin Pereira",
		    "nombre": "El Jardin Pereira",
		    "carpeta": "el jardin pereira",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 4.807,
			    "longitud": -75.719
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IPEREIRA3",
			"bd" : "wunderground",
  			"nombrelargo": "Vereda La Convención",
		    "nombre": "Vereda La Convención",
		    "carpeta": "vereda la convencion",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 4.882,
			    "longitud": -75.725
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IPEREIRA4",
			"bd" : "wunderground",
  			"nombrelargo": "Vereda Altagracia",
		    "nombre": "Vereda Altagracia",
		    "carpeta": "Vereda Altagracia",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 4.745,
			    "longitud": -75.713
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "IQUINCHA2",
			"bd" : "wunderground",
  			"nombrelargo": "Quinchía Naranjal",
		    "nombre": "Quinchía Naranjal",
		    "carpeta": "quinchia naranjal",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 5.324,
			    "longitud": -75.712
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		}/*,
		{
			"id" : "IQUINCHA3",
			"bd" : "wunderground",
  			"nombrelargo": "Cartagueño, Quinchía",
		    "nombre": "Cartagueño, Quinchía",
		    "carpeta": "cartagueno quinchia",
		    "variables": {
			      "0": "temperatura",
			      "1": "precipitacion",
			      "2": "humedad",
			      "3": "radiacion",
			      "4": "presion",
			      "5": "velocidad",
			      "6": "direccion",
			      "7": "evapotranspiracion"
			},
		    "coordenadas": {
		    
		    		"latitud": 5.333,
			    "longitud": -75.688
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "",
		    "ubicacion": "",
		    "fecha": "25 de Febrero de 2018",
		    "estado": "Activa"	  		  
		}*/
	];
