#!/usr/bin/env python3
import json
import sys
import os
from datetime import datetime

class ContinentesDatabase:
    def __init__(self):
        self.continentes_file = 'continentes_data.json'
        self.init_database()
    
    def init_database(self):
        """Inicializa la base de datos con datos precisos de continentes"""
        if not os.path.exists(self.continentes_file) or os.path.getsize(self.continentes_file) == 0:
            print("DEBUG: Creando base de datos de continentes...", file=sys.stderr)
            
            continentes_data = {
                'Africa': {
                    'nombre_completo': 'África',
                    'poblacion': 1340598147,
                    'area_km2': 30370000,
                    'paises_count': 54,
                    'paises': [
                        {'nombre': 'Nigeria', 'poblacion': 206139587, 'capital': 'Abuya'},
                        {'nombre': 'Etiopía', 'poblacion': 114963588, 'capital': 'Adís Abeba'},
                        {'nombre': 'Egipto', 'poblacion': 102334404, 'capital': 'El Cairo'},
                        {'nombre': 'República Democrática del Congo', 'poblacion': 89561403, 'capital': 'Kinsasa'},
                        {'nombre': 'Sudáfrica', 'poblacion': 59308690, 'capital': 'Pretoria'}
                    ],
                    'idiomas_principales': ['Árabe', 'Swahili', 'Inglés', 'Francés', 'Hausa'],
                    'punto_mas_alto': {'nombre': 'Monte Kilimanjaro', 'altura_m': 5895, 'pais': 'Tanzania'},
                    'moneda_comun': False,
                    'curiosidades': [
                        'África es el continente con más países del mundo',
                        'Alberga el río más largo del mundo: el Nilo (6,650 km)',
                        'Tiene las poblaciones de animales salvajes más grandes del mundo'
                    ],
                    'clima': 'Ecuatorial, tropical y desértico',
                    'densidad_poblacion': 45.0
                },
                'America': {
                    'nombre_completo': 'América',
                    'poblacion': 1029525000,
                    'area_km2': 42330000,
                    'paises_count': 35,
                    'paises': [
                        {'nombre': 'Estados Unidos', 'poblacion': 331002651, 'capital': 'Washington D.C.'},
                        {'nombre': 'Brasil', 'poblacion': 212559417, 'capital': 'Brasilia'},
                        {'nombre': 'México', 'poblacion': 128932753, 'capital': 'Ciudad de México'},
                        {'nombre': 'Colombia', 'poblacion': 50882891, 'capital': 'Bogotá'},
                        {'nombre': 'Argentina', 'poblacion': 45195774, 'capital': 'Buenos Aires'}
                    ],
                    'idiomas_principales': ['Español', 'Inglés', 'Portugués', 'Francés'],
                    'punto_mas_alto': {'nombre': 'Monte Aconcagua', 'altura_m': 6961, 'pais': 'Argentina'},
                    'moneda_comun': False,
                    'curiosidades': [
                        'América tiene el río Amazonas, el más caudaloso del mundo',
                        'Contiene el desierto de Atacama, el más seco del mundo',
                        'Tiene la cordillera más larga: los Andes (7,000 km)'
                    ],
                    'clima': 'Diverso: polar, templado, tropical',
                    'densidad_poblacion': 24.5
                },
                'Asia': {
                    'nombre_completo': 'Asia',
                    'poblacion': 4641054775,
                    'area_km2': 44579000,
                    'paises_count': 48,
                    'paises': [
                        {'nombre': 'China', 'poblacion': 1439323776, 'capital': 'Pekín'},
                        {'nombre': 'India', 'poblacion': 1380004385, 'capital': 'Nueva Delhi'},
                        {'nombre': 'Indonesia', 'poblacion': 273523615, 'capital': 'Yakarta'},
                        {'nombre': 'Pakistán', 'poblacion': 220892340, 'capital': 'Islamabad'},
                        {'nombre': 'Bangladés', 'poblacion': 164689383, 'capital': 'Daca'}
                    ],
                    'idiomas_principales': ['Chino mandarín', 'Hindi', 'Árabe', 'Bengalí', 'Ruso'],
                    'punto_mas_alto': {'nombre': 'Monte Everest', 'altura_m': 8849, 'pais': 'Nepal/China'},
                    'moneda_comun': False,
                    'curiosidades': [
                        'Asia es el continente más grande y poblado del mundo',
                        'Alberga el punto más alto de la Tierra: el Monte Everest',
                        'Tiene la economía de más rápido crecimiento del mundo'
                    ],
                    'clima': 'Diverso: desde ártico hasta tropical',
                    'densidad_poblacion': 150.0
                },
                'Europa': {
                    'nombre_completo': 'Europa',
                    'poblacion': 747636026,
                    'area_km2': 10180000,
                    'paises_count': 44,
                    'paises': [
                        {'nombre': 'Rusia', 'poblacion': 145934462, 'capital': 'Moscú'},
                        {'nombre': 'Alemania', 'poblacion': 83783942, 'capital': 'Berlín'},
                        {'nombre': 'Reino Unido', 'poblacion': 67886011, 'capital': 'Londres'},
                        {'nombre': 'Francia', 'poblacion': 65273511, 'capital': 'París'},
                        {'nombre': 'Italia', 'poblacion': 60461826, 'capital': 'Roma'}
                    ],
                    'idiomas_principales': ['Ruso', 'Alemán', 'Inglés', 'Francés', 'Italiano'],
                    'punto_mas_alto': {'nombre': 'Monte Elbrús', 'altura_m': 5642, 'pais': 'Rusia'},
                    'moneda_comun': True,
                    'curiosidades': [
                        'Europa tiene la moneda común más exitosa: el Euro',
                        'Es el continente con más países por área',
                        'Tiene la población más envejecida del mundo'
                    ],
                    'clima': 'Templado en su mayoría',
                    'densidad_poblacion': 34.0
                },
                'Oceania': {
                    'nombre_completo': 'Oceanía',
                    'poblacion': 42677813,
                    'area_km2': 8526000,
                    'paises_count': 14,
                    'paises': [
                        {'nombre': 'Australia', 'poblacion': 25499884, 'capital': 'Canberra'},
                        {'nombre': 'Papúa Nueva Guinea', 'poblacion': 8947024, 'capital': 'Puerto Moresby'},
                        {'nombre': 'Nueva Zelanda', 'poblacion': 4822233, 'capital': 'Wellington'},
                        {'nombre': 'Fiyi', 'poblacion': 896445, 'capital': 'Suva'},
                        {'nombre': 'Islas Salomón', 'poblacion': 686884, 'capital': 'Honiara'}
                    ],
                    'idiomas_principales': ['Inglés', 'Francés', 'Tok Pisin', 'Fiyiano', 'Maorí'],
                    'punto_mas_alto': {'nombre': 'Monte Wilhelm', 'altura_m': 4509, 'pais': 'Papúa Nueva Guinea'},
                    'moneda_comun': False,
                    'curiosidades': [
                        'Oceanía es el continente más pequeño en superficie terrestre',
                        'Australia es la isla más grande del mundo',
                        'Tiene la Gran Barrera de Coral, el sistema de arrecifes más grande'
                    ],
                    'clima': 'Oceánico y tropical',
                    'densidad_poblacion': 5.0
                },
                'Antartida': {
                    'nombre_completo': 'Antártida',
                    'poblacion': 1000,
                    'area_km2': 14000000,
                    'paises_count': 0,
                    'paises': [
                        {'nombre': 'Base McMurdo (EEUU)', 'poblacion': 250, 'capital': 'N/A'},
                        {'nombre': 'Base Esperanza (Argentina)', 'poblacion': 55, 'capital': 'N/A'},
                        {'nombre': 'Base Vostok (Rusia)', 'poblacion': 25, 'capital': 'N/A'}
                    ],
                    'idiomas_principales': ['Inglés', 'Ruso', 'Español', 'Francés'],
                    'punto_mas_alto': {'nombre': 'Macizo Vinson', 'altura_m': 4892, 'pais': 'N/A'},
                    'moneda_comun': False,
                    'curiosidades': [
                        'La Antártida es el continente más frío, seco y ventoso',
                        'Contiene el 90% del hielo mundial y el 70% del agua dulce',
                        'No tiene población nativa, solo investigadores temporales'
                    ],
                    'clima': 'Polar extremo',
                    'densidad_poblacion': 0.00008
                }
            }
            
            self._save_json(self.continentes_file, continentes_data)
            print("DEBUG: Base de datos de continentes creada exitosamente", file=sys.stderr)
        else:
            print("DEBUG: Base de datos de continentes ya existe", file=sys.stderr)
    
    def _save_json(self, filename, data):
        """Guarda datos en archivo JSON"""
        try:
            with open(filename, 'w', encoding='utf-8') as f:
                json.dump(data, f, ensure_ascii=False, indent=2)
            return True
        except Exception as e:
            print(f"ERROR: No se pudo guardar {filename}: {e}", file=sys.stderr)
            return False
    
    def _load_json(self, filename):
        """Carga datos desde archivo JSON"""
        try:
            with open(filename, 'r', encoding='utf-8') as f:
                return json.load(f)
        except Exception as e:
            print(f"ERROR: No se pudo cargar {filename}: {e}", file=sys.stderr)
            return {}
    
    # ==================== OPERACIONES DE DATOS ====================
    
    def get_continentes(self):
        """Obtiene todos los continentes"""
        return self._load_json(self.continentes_file)
    
    def get_continente(self, nombre_continente):
        """Obtiene un continente específico"""
        continentes = self.get_continentes()
        return continentes.get(nombre_continente, {})
    
    def get_poblacion_total(self):
        """Calcula la población mundial total"""
        continentes = self.get_continentes()
        return sum(continente['poblacion'] for continente in continentes.values())
    
    def get_area_total(self):
        """Calcula el área terrestre total"""
        continentes = self.get_continentes()
        return sum(continente['area_km2'] for continente in continentes.values())
    
    def get_continente_mas_poblado(self):
        """Encuentra el continente más poblado"""
        continentes = self.get_continentes()
        if not continentes:
            return {}
        
        mas_poblado = max(continentes.items(), key=lambda x: x[1]['poblacion'])
        return {
            'nombre': mas_poblado[0],
            'datos': mas_poblado[1]
        }
    
    def get_continente_mas_grande(self):
        """Encuentra el continente más grande por área"""
        continentes = self.get_continentes()
        if not continentes:
            return {}
        
        mas_grande = max(continentes.items(), key=lambda x: x[1]['area_km2'])
        return {
            'nombre': mas_grande[0],
            'datos': mas_grande[1]
        }
    
    def get_paises_por_continente(self, nombre_continente):
        """Obtiene los países de un continente"""
        continente = self.get_continente(nombre_continente)
        return continente.get('paises', [])
    
    def buscar_pais(self, nombre_pais):
        """Busca un país en todos los continentes"""
        continentes = self.get_continentes()
        for nombre_continente, datos in continentes.items():
            for pais in datos.get('paises', []):
                if pais['nombre'].lower() == nombre_pais.lower():
                    return {
                        'pais': pais,
                        'continente': nombre_continente,
                        'datos_continente': datos
                    }
        return {}
    
    def get_estadisticas_globales(self):
        """Calcula estadísticas globales"""
        continentes = self.get_continentes()
        if not continentes:
            return {}
        
        poblaciones = [c['poblacion'] for c in continentes.values()]
        areas = [c['area_km2'] for c in continentes.values()]
        densidades = [c['poblacion'] / c['area_km2'] for c in continentes.values() if c['area_km2'] > 0]
        
        return {
            'total_continentes': len(continentes),
            'total_paises': sum(c['paises_count'] for c in continentes.values()),
            'poblacion_total': sum(poblaciones),
            'area_total': sum(areas),
            'densidad_promedio': sum(densidades) / len(densidades) if densidades else 0,
            'continente_mas_poblado': self.get_continente_mas_poblado(),
            'continente_mas_grande': self.get_continente_mas_grande(),
            'continente_menos_poblado': min(continentes.items(), key=lambda x: x[1]['poblacion'])[0],
            'continente_mas_denso': max(continentes.items(), key=lambda x: x[1]['poblacion'] / x[1]['area_km2'] if x[1]['area_km2'] > 0 else 0)[0]
        }
    
    def filtrar_continentes_por_poblacion(self, poblacion_minima):
        """Filtra continentes por población mínima"""
        continentes = self.get_continentes()
        return {nombre: datos for nombre, datos in continentes.items() if datos['poblacion'] >= poblacion_minima}
    
    def ordenar_continentes_por(self, criterio, descendente=True):
        """Ordena continentes por criterio específico"""
        continentes = self.get_continentes()
        
        if criterio == 'poblacion':
            clave = 'poblacion'
        elif criterio == 'area':
            clave = 'area_km2'
        elif criterio == 'nombre':
            return dict(sorted(continentes.items(), key=lambda x: x[0], reverse=descendente))
        elif criterio == 'paises_count':
            clave = 'paises_count'
        elif criterio == 'densidad':
            # Calcular densidad sobre la marcha
            continentes_con_densidad = {}
            for nombre, datos in continentes.items():
                densidad = datos['poblacion'] / datos['area_km2'] if datos['area_km2'] > 0 else 0
                continentes_con_densidad[nombre] = {**datos, 'densidad_calculada': densidad}
            return dict(sorted(continentes_con_densidad.items(), key=lambda x: x[1]['densidad_calculada'], reverse=descendente))
        else:
            return continentes
        
        return dict(sorted(continentes.items(), key=lambda x: x[1][clave], reverse=descendente))

# ==================== INTERFAZ CON PHP ====================
def ejecutar_comando(comando, datos=None):
    """Ejecuta comandos desde PHP"""
    db = ContinentesDatabase()
    
    try:
        if comando == "get_continentes":
            return db.get_continentes()
        
        elif comando == "get_continente":
            nombre = datos.get('nombre', '')
            return db.get_continente(nombre)
        
        elif comando == "get_poblacion_total":
            return db.get_poblacion_total()
        
        elif comando == "get_area_total":
            return db.get_area_total()
        
        elif comando == "get_continente_mas_poblado":
            return db.get_continente_mas_poblado()
        
        elif comando == "get_continente_mas_grande":
            return db.get_continente_mas_grande()
        
        elif comando == "get_paises_por_continente":
            nombre = datos.get('nombre', '')
            return db.get_paises_por_continente(nombre)
        
        elif comando == "buscar_pais":
            nombre = datos.get('nombre', '')
            return db.buscar_pais(nombre)
        
        elif comando == "get_estadisticas_globales":
            return db.get_estadisticas_globales()
        
        elif comando == "filtrar_continentes_por_poblacion":
            poblacion_min = datos.get('poblacion_minima', 1000000000)
            return db.filtrar_continentes_por_poblacion(poblacion_min)
        
        elif comando == "ordenar_continentes_por":
            criterio = datos.get('criterio', 'poblacion')
            descendente = datos.get('descendente', True)
            return db.ordenar_continentes_por(criterio, descendente)
        
        else:
            return {"error": f"Comando no reconocido: {comando}"}
            
    except Exception as e:
        return {"error": f"Error ejecutando {comando}: {str(e)}"}

if __name__ == "__main__":
    # Comunicación via STDIN/STDOUT
    try:
        input_data = json.loads(sys.stdin.read())
        comando = input_data.get('comando')
        datos = input_data.get('datos', {})
        
        resultado = ejecutar_comando(comando, datos)
        print(json.dumps(resultado, ensure_ascii=False))
        
    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))
