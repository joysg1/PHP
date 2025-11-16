#!/usr/bin/env python3
import json
import sys
import os
from datetime import datetime

class MedicalDatabase:
    def __init__(self):
        self.diagnosticos_file = 'diagnosticos.json'
        self.profesionales_file = 'profesionales.json'
        self.init_database()
    
    def init_database(self):
        """Inicializa los archivos JSON si no existen"""
        print(f"DEBUG: Inicializando base de datos...", file=sys.stderr)
        
        # Crear archivo de profesionales si no existe o está vacío
        if not os.path.exists(self.profesionales_file) or os.path.getsize(self.profesionales_file) == 0:
            print(f"DEBUG: Creando archivo de profesionales...", file=sys.stderr)
            profesionales = [
                {"id": 1, "nombre": "Dr. Carlos Pérez", "especialidad": "Médico General", "telefono": "555-1001", "activo": True},
                {"id": 2, "nombre": "Dra. Ana García", "especialidad": "Cardióloga", "telefono": "555-1002", "activo": True},
                {"id": 3, "nombre": "Dr. Luis Rodríguez", "especialidad": "Traumatólogo", "telefono": "555-1003", "activo": True},
                {"id": 4, "nombre": "Lic. María Martínez", "especialidad": "Psicóloga", "telefono": "555-1004", "activo": True},
                {"id": 5, "nombre": "Dra. Laura López", "especialidad": "Dermatóloga", "telefono": "555-1005", "activo": True},
                {"id": 6, "nombre": "Lic. Pedro Torres", "especialidad": "Nutriólogo", "telefono": "555-1006", "activo": True},
                {"id": 7, "nombre": "Lic. Carmen Sánchez", "especialidad": "Fisioterapeuta", "telefono": "555-1007", "activo": True}
            ]
            self._save_json(self.profesionales_file, profesionales)
            print(f"DEBUG: Archivo de profesionales creado con {len(profesionales)} registros", file=sys.stderr)
        else:
            profesionales = self._load_json(self.profesionales_file)
            print(f"DEBUG: Archivo de profesionales ya existe con {len(profesionales) if profesionales else 0} registros", file=sys.stderr)
        
        # Crear archivo de diagnósticos si no existe
        if not os.path.exists(self.diagnosticos_file):
            print(f"DEBUG: Creando archivo de diagnósticos...", file=sys.stderr)
            self._save_json(self.diagnosticos_file, [])
        
        print(f"DEBUG: Base de datos inicializada correctamente", file=sys.stderr)
    
    def _save_json(self, filename, data):
        """Guarda datos en archivo JSON"""
        try:
            with open(filename, 'w', encoding='utf-8') as f:
                json.dump(data, f, ensure_ascii=False, indent=2)
            print(f"DEBUG: Datos guardados en {filename}", file=sys.stderr)
            return True
        except Exception as e:
            print(f"ERROR: No se pudo guardar en {filename}: {e}", file=sys.stderr)
            return False
    
    def _load_json(self, filename):
        """Carga datos desde archivo JSON"""
        try:
            if not os.path.exists(filename):
                print(f"DEBUG: Archivo {filename} no existe", file=sys.stderr)
                return []
            
            with open(filename, 'r', encoding='utf-8') as f:
                data = json.load(f)
                print(f"DEBUG: Datos cargados desde {filename}: {len(data) if data else 0} registros", file=sys.stderr)
                return data
        except json.JSONDecodeError as e:
            print(f"ERROR: Archivo {filename} corrupto: {e}", file=sys.stderr)
            return []
        except Exception as e:
            print(f"ERROR: No se pudo cargar {filename}: {e}", file=sys.stderr)
            return []
    
    def get_profesionales(self):
        """Obtiene todos los profesionales"""
        profesionales = self._load_json(self.profesionales_file)
        print(f"DEBUG: get_profesionales retornando {len(profesionales)} profesionales", file=sys.stderr)
        return profesionales
    
    def get_profesional_por_especialidad(self, especialidad):
        """Busca profesional por especialidad (case-insensitive)"""
        print(f"DEBUG: Buscando profesional con especialidad: '{especialidad}'", file=sys.stderr)
        profesionales = self.get_profesionales()
        
        if not profesionales:
            print(f"ERROR: No hay profesionales cargados", file=sys.stderr)
            return None
        
        # Mostrar especialidades disponibles para debug
        especialidades_disponibles = [p['especialidad'] for p in profesionales]
        print(f"DEBUG: Especialidades disponibles: {especialidades_disponibles}", file=sys.stderr)
        
        for prof in profesionales:
            # Comparar sin importar mayúsculas/minúsculas ni acentos
            especialidad_prof = prof['especialidad'].lower()
            especialidad_buscada = especialidad.lower()
            
            # Manejo simple de acentos
            especialidad_prof = (especialidad_prof
                               .replace('ó', 'o')
                               .replace('á', 'a')
                               .replace('é', 'e')
                               .replace('í', 'i')
                               .replace('ú', 'u'))
            
            especialidad_buscada = (especialidad_buscada
                                  .replace('ó', 'o')
                                  .replace('á', 'a')
                                  .replace('é', 'e')
                                  .replace('í', 'i')
                                  .replace('ú', 'u'))
            
            if especialidad_prof == especialidad_buscada:
                print(f"DEBUG: Profesional encontrado: {prof['nombre']}", file=sys.stderr)
                return prof
        
        print(f"DEBUG: No se encontró profesional para especialidad: '{especialidad}'", file=sys.stderr)
        return None
    
    def save_diagnostico(self, nombre_paciente, afeccion, sintomas, profesionales):
        """Guarda un nuevo diagnóstico"""
        print(f"DEBUG: Guardando diagnóstico para {nombre_paciente}", file=sys.stderr)
        diagnosticos = self._load_json(self.diagnosticos_file)
        
        nuevo_diagnostico = {
            "id": len(diagnosticos) + 1,
            "nombre_paciente": nombre_paciente,
            "afeccion": afeccion,
            "sintomas": sintomas,
            "profesionales": profesionales,
            "fecha_creacion": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }
        
        diagnosticos.append(nuevo_diagnostico)
        # Mantener solo últimos 10 registros
        diagnosticos = diagnosticos[-10:]
        
        success = self._save_json(self.diagnosticos_file, diagnosticos)
        if success:
            print(f"DEBUG: Diagnóstico guardado exitosamente", file=sys.stderr)
            return nuevo_diagnostico
        else:
            print(f"ERROR: No se pudo guardar el diagnóstico", file=sys.stderr)
            return {"error": "No se pudo guardar el diagnóstico"}
    
    def get_diagnosticos(self):
        """Obtiene todos los diagnósticos"""
        diagnosticos = self._load_json(self.diagnosticos_file)
        print(f"DEBUG: get_diagnosticos retornando {len(diagnosticos)} diagnósticos", file=sys.stderr)
        return diagnosticos

# Funciones para comunicación con PHP
def ejecutar_comando(comando, datos=None):
    """Ejecuta comandos desde PHP"""
    print(f"DEBUG: Ejecutando comando: {comando}", file=sys.stderr)
    db = MedicalDatabase()
    
    try:
        if comando == "get_profesionales":
            resultado = db.get_profesionales()
            
        elif comando == "get_profesional":
            especialidad = datos.get('especialidad', '')
            profesional = db.get_profesional_por_especialidad(especialidad)
            resultado = profesional if profesional else {}
            
        elif comando == "save_diagnostico":
            resultado = db.save_diagnostico(
                datos.get('nombre_paciente', ''),
                datos.get('afeccion', ''),
                datos.get('sintomas', ''),
                datos.get('profesionales', '')
            )
            
        elif comando == "get_diagnosticos":
            resultado = db.get_diagnosticos()
            
        else:
            resultado = {"error": "Comando no reconocido"}
            
        print(f"DEBUG: Comando {comando} ejecutado exitosamente", file=sys.stderr)
        return resultado
        
    except Exception as e:
        error_msg = f"Error ejecutando {comando}: {str(e)}"
        print(f"ERROR: {error_msg}", file=sys.stderr)
        return {"error": error_msg}

if __name__ == "__main__":
    # Comunicación via STDIN/STDOUT
    try:
        input_str = sys.stdin.read()
        print(f"DEBUG: Input recibido: {input_str}", file=sys.stderr)
        
        if not input_str.strip():
            print(f"ERROR: No se recibió input", file=sys.stderr)
            print(json.dumps({"error": "No input received"}, ensure_ascii=False))
            sys.exit(1)
            
        input_data = json.loads(input_str)
        comando = input_data.get('comando')
        datos = input_data.get('datos', {})
        
        if not comando:
            print(f"ERROR: No se especificó comando", file=sys.stderr)
            print(json.dumps({"error": "No command specified"}, ensure_ascii=False))
            sys.exit(1)
        
        resultado = ejecutar_comando(comando, datos)
        output = json.dumps(resultado, ensure_ascii=False)
        print(f"DEBUG: Enviando output: {output}", file=sys.stderr)
        print(output)
        
    except json.JSONDecodeError as e:
        error_msg = f"Error decodificando JSON: {str(e)}"
        print(f"ERROR: {error_msg}", file=sys.stderr)
        print(json.dumps({"error": error_msg}, ensure_ascii=False))
        sys.exit(1)
    except Exception as e:
        error_msg = f"Error general: {str(e)}"
        print(f"ERROR: {error_msg}", file=sys.stderr)
        print(json.dumps({"error": error_msg}, ensure_ascii=False))
        sys.exit(1)