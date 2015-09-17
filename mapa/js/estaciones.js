iconoEHT = 'http://'+window.location.host+'/mapa/img/EHT25.png';////'img/hidroelectrica.png';
iconoEC  = 'http://'+window.location.host+'/mapa/img/ECnew2.png';
iconoECT = 'http://'+window.location.host+'/mapa/img/ECTnewblued.png';//ECT30.png
iconoSN  = 'http://'+window.location.host+'/mapa/img/nivelb23.png';
iconoPD  = 'http://'+window.location.host+'/mapa/img/PD30.png';
iconoPos  = 'http://'+window.location.host+'/mapa/img/punto.png';
// New added 01-02-2015
iconoENT  = 'http://'+window.location.host+'/mapa/img/ENT.png';
iconoEQT  = 'http://'+window.location.host+'/mapa/img/caudal.png';

estacionesJSON = [
  		{
  			"id" : "11",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT001 (El Lago)",
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
			"id" : "6",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT002 (Cortaderal)",
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
			"id" : "16",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT003 (San José)",
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
		      "latitud": 4.815287772,
		      "longitud": -75.59604566
		    },
		    "isPublic": true,
		    "tipo":"ECT",
		    "icono": iconoECT,
		    "altitud": "1900 m.s.n.m",
		    "ubicacion": "Finca San José, municipio de Santa Rosa de Cabal",
		    "fecha": "26 de Enero de 2010",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "26",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT004 (Alto del Nudo)",
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
			"id" : "30",
  			"nombrelargo": "Estaciones Climatológica Telemétrica (ECT)",
		    "nombre": "ECT005 (Quinchía - Seafield)",
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
		{
			"id" : "14",
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
		},
		{
			"id" : "17",
  			"nombrelargo": "Estaciones Hidroclimatológicas telemétricas (EHT)",
		    "nombre": "EHT002 (San Juan)",
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
		},
		
		
		{
			"id":"60",
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
		    "isPublic": false,
		    "tipo":"ENT",
		    "icono": iconoENT, 
		    "altitud": "1535 m.s.n.m",
		    "ubicacion": "Bocatoma nuevo Libaré ",
		    "fecha": "14 de Junio de 2013",
		    "estado": "Activa"	  		  
		},
		{
			"id":"62",
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
		    "isPublic": false,
		    "tipo":"ENT",
		    "icono": iconoENT, 
		    "altitud": "1278 m.s.n.m",
		    "ubicacion": "Bocatoma Belmonte ",
		    "fecha": "14 de Junio de 2013",
		    "estado": "Activa"	  		  
		},
		{
			"id":"61",
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
			"id":"59",
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
			"id":"63",
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
			"id":"69",
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
			"id" : "37",
  			"nombrelargo": "Estaciones Climatológicas no Telemétricas (EC)",
		    "nombre": "EC001 (Acuaseo)",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.861723969,
		      "longitud": -75.65464021
		    },
		    "isPublic": true,
		    "tipo":"EC",
		    "icono": iconoEC,
		    "altitud": "1550 m.s.n.m",
		    "ubicacion": "Planta de Tratamiento de Agua, compañía de servicios públicos Acuaseo, Dosquebradas",
		    "fecha": "Diciembre de 2007",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "38",
  			"nombrelargo": "Estaciones Climatológicas no Telemétricas (EC)",
		    "nombre": "EC002 (Mundo Nuevo)",
		    "variables": {},
		    "coordenadas": {
		      "latitud": 4.756890416,
		      "longitud": -75.66111246
		    },
		    "isPublic": true,
		    "tipo":"EC",
		    "icono": iconoEC,
		    "altitud": "1550 m.s.n.m",
		    "ubicacion": "Planta de Tratamiento de Aguas, Vereda Mundo Nuevo",
		    "fecha": "Diciembre de 2007",
		    "estado": "Activa"	  		  
		},
		{
			"id" : "32",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN001 (Quebrada Dalí)",
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
			"id" : "34",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN002 (Quebrada Negra)",
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
			"id" : "31",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN003 (Quebrada El Manzano)",
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
			"id" : "35",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN004 (Quebrada Volcanes) ",
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
			"id": "30",
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
			"id":"33",
  			"nombrelargo": "Sensores de Nivel por Presión de Lamina de Agua, no telemétricos (SN)",
		    "nombre": "SN006  (Quebrada El Oso)",
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
		},
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
		},
		{
			"id":"39",
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
		    "icono": iconoPD,
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
            "carpeta": "calicanto",
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
		}
	];
