from flask import Flask, request, jsonify
import json
import os
from datetime import datetime
import uuid

app = Flask(__name__)

# Configuración
DATA_FILE = 'data/estudiantes.json'

# Headers CORS manuales
@app.after_request
def after_request(response):
    response.headers.add('Access-Control-Allow-Origin', '*')
    response.headers.add('Access-Control-Allow-Headers', 'Content-Type,Authorization')
    response.headers.add('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
    return response

@app.route('/', methods=['OPTIONS'])
@app.route('/api/<path:path>', methods=['OPTIONS'])
def options_response(path=None):
    return '', 200

def cargar_estudiantes():
    """Cargar estudiantes desde el archivo JSON"""
    try:
        if not os.path.exists(DATA_FILE):
            # Crear archivo con array vacío si no existe
            os.makedirs(os.path.dirname(DATA_FILE), exist_ok=True)
            with open(DATA_FILE, 'w', encoding='utf-8') as f:
                json.dump([], f, ensure_ascii=False, indent=2)
            return []
        
        with open(DATA_FILE, 'r', encoding='utf-8') as f:
            data = f.read().strip()
            if not data:
                return []
            return json.loads(data)
    except Exception as e:
        print(f"Error cargando estudiantes: {e}")
        return []

def guardar_estudiantes(estudiantes):
    """Guardar estudiantes en el archivo JSON"""
    try:
        with open(DATA_FILE, 'w', encoding='utf-8') as f:
            json.dump(estudiantes, f, ensure_ascii=False, indent=2)
        return True
    except Exception as e:
        print(f"Error guardando estudiantes: {e}")
        return False

def generar_id():
    """Generar ID único para estudiante"""
    return str(uuid.uuid4())

def validar_calificacion(calificacion):
    """Validar que la calificación sea válida"""
    calificaciones_validas = ['A', 'B', 'C', 'D', 'F', 'I', 'N']
    return calificacion in calificaciones_validas

# ==================== RUTAS DE LA API ====================

@app.route('/api/estudiantes', methods=['GET'])
def obtener_estudiantes():
    """Obtener todos los estudiantes"""
    try:
        estudiantes = cargar_estudiantes()
        return jsonify({
            'success': True,
            'data': estudiantes,
            'total': len(estudiantes)
        })
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/estudiantes/<string:estudiante_id>', methods=['GET'])
def obtener_estudiante(estudiante_id):
    """Obtener un estudiante por ID"""
    try:
        estudiantes = cargar_estudiantes()
        estudiante = next((e for e in estudiantes if e['id'] == estudiante_id), None)
        
        if estudiante:
            return jsonify({
                'success': True,
                'data': estudiante
            })
        else:
            return jsonify({
                'success': False,
                'error': 'Estudiante no encontrado'
            }), 404
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/estudiantes/cedula/<string:cedula>', methods=['GET'])
def obtener_estudiante_por_cedula(cedula):
    """Obtener estudiante por cédula"""
    try:
        estudiantes = cargar_estudiantes()
        estudiante = next((e for e in estudiantes if e['cedula'] == cedula), None)
        
        if estudiante:
            return jsonify({
                'success': True,
                'data': estudiante
            })
        else:
            return jsonify({
                'success': False,
                'data': None
            })
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/estudiantes', methods=['POST'])
def crear_estudiante():
    """Crear nuevo estudiante o agregar materia"""
    try:
        data = request.get_json()
        print(f"Datos recibidos: {data}")
        
        # Validaciones básicas
        if not data or 'cedula' not in data:
            return jsonify({
                'success': False,
                'error': 'La cédula es obligatoria'
            }), 400
        
        cedula = data['cedula'].strip()
        carrera = data.get('carrera', '').strip()
        materia = data.get('materia', '').strip()
        calificacion = data.get('calificacion', '')
        
        if not carrera:
            return jsonify({
                'success': False,
                'error': 'La carrera es obligatoria'
            }), 400
        
        if not materia:
            return jsonify({
                'success': False,
                'error': 'La materia es obligatoria'
            }), 400
        
        if not calificacion or not validar_calificacion(calificacion):
            return jsonify({
                'success': False,
                'error': 'Calificación inválida'
            }), 400
        
        estudiantes = cargar_estudiantes()
        
        # Buscar si el estudiante ya existe
        estudiante_existente = next((e for e in estudiantes if e['cedula'] == cedula), None)
        
        if estudiante_existente:
            # Verificar si la materia ya existe en la misma carrera
            materias_existentes = estudiante_existente.get('materias', [])
            materia_duplicada = any(
                m for m in materias_existentes 
                if m['materia'] == materia and m['carrera'] == carrera
            )
            
            if materia_duplicada:
                return jsonify({
                    'success': False,
                    'error': f'El estudiante ya tiene la materia "{materia}" en la carrera "{carrera}"'
                }), 400
            
            # Agregar nueva materia al estudiante existente
            nueva_materia = {
                'carrera': carrera,
                'materia': materia,
                'calificacion': calificacion,
                'fecha_registro': datetime.now().isoformat()
            }
            
            if 'materias' not in estudiante_existente:
                estudiante_existente['materias'] = []
            
            estudiante_existente['materias'].append(nueva_materia)
            
            # Actualizar el estudiante en la lista
            for i, est in enumerate(estudiantes):
                if est['cedula'] == cedula:
                    estudiantes[i] = estudiante_existente
                    break
            
            if guardar_estudiantes(estudiantes):
                return jsonify({
                    'success': True,
                    'message': 'Materia agregada exitosamente',
                    'data': estudiante_existente
                })
            else:
                return jsonify({
                    'success': False,
                    'error': 'Error al guardar los datos'
                }), 500
        
        else:
            # Crear nuevo estudiante
            if not data.get('nombre') or not data.get('apellido'):
                return jsonify({
                    'success': False,
                    'error': 'Nombre y apellido son obligatorios para nuevo estudiante'
                }), 400
            
            nuevo_estudiante = {
                'id': generar_id(),
                'cedula': cedula,
                'nombre': data['nombre'].strip(),
                'apellido': data['apellido'].strip(),
                'fecha_registro': datetime.now().isoformat(),
                'materias': [{
                    'carrera': carrera,
                    'materia': materia,
                    'calificacion': calificacion,
                    'fecha_registro': datetime.now().isoformat()
                }]
            }
            
            estudiantes.append(nuevo_estudiante)
            
            if guardar_estudiantes(estudiantes):
                return jsonify({
                    'success': True,
                    'message': 'Estudiante creado exitosamente',
                    'data': nuevo_estudiante
                })
            else:
                return jsonify({
                    'success': False,
                    'error': 'Error al guardar los datos'
                }), 500
    
    except Exception as e:
        print(f"Error en crear_estudiante: {e}")
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/estudiantes/<string:estudiante_id>', methods=['PUT'])
def actualizar_estudiante(estudiante_id):
    """Actualizar información básica del estudiante"""
    try:
        data = request.get_json()
        
        if not data:
            return jsonify({
                'success': False,
                'error': 'Datos no proporcionados'
            }), 400
        
        estudiantes = cargar_estudiantes()
        estudiante_index = next((i for i, e in enumerate(estudiantes) if e['id'] == estudiante_id), None)
        
        if estudiante_index is None:
            return jsonify({
                'success': False,
                'error': 'Estudiante no encontrado'
            }), 404
        
        # Actualizar campos permitidos
        campos_permitidos = ['nombre', 'apellido', 'cedula']
        for campo in campos_permitidos:
            if campo in data:
                estudiantes[estudiante_index][campo] = data[campo].strip()
        
        if guardar_estudiantes(estudiantes):
            return jsonify({
                'success': True,
                'message': 'Estudiante actualizado exitosamente',
                'data': estudiantes[estudiante_index]
            })
        else:
            return jsonify({
                'success': False,
                'error': 'Error al guardar los datos'
            }), 500
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/estudiantes/<string:estudiante_id>', methods=['DELETE'])
def eliminar_estudiante(estudiante_id):
    """Eliminar estudiante"""
    try:
        estudiantes = cargar_estudiantes()
        estudiantes_filtrados = [e for e in estudiantes if e['id'] != estudiante_id]
        
        if len(estudiantes_filtrados) == len(estudiantes):
            return jsonify({
                'success': False,
                'error': 'Estudiante no encontrado'
            }), 404
        
        if guardar_estudiantes(estudiantes_filtrados):
            return jsonify({
                'success': True,
                'message': 'Estudiante eliminado exitosamente'
            })
        else:
            return jsonify({
                'success': False,
                'error': 'Error al guardar los datos'
            }), 500
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/estudiantes/<string:cedula>/materias/<int:materia_index>', methods=['DELETE'])
def eliminar_materia(cedula, materia_index):
    """Eliminar materia específica de un estudiante"""
    try:
        estudiantes = cargar_estudiantes()
        estudiante_index = next((i for i, e in enumerate(estudiantes) if e['cedula'] == cedula), None)
        
        if estudiante_index is None:
            return jsonify({
                'success': False,
                'error': 'Estudiante no encontrado'
            }), 404
        
        estudiante = estudiantes[estudiante_index]
        materias = estudiante.get('materias', [])
        
        if materia_index < 0 or materia_index >= len(materias):
            return jsonify({
                'success': False,
                'error': 'Índice de materia inválido'
            }), 400
        
        # Eliminar la materia
        materia_eliminada = materias.pop(materia_index)
        estudiante['materias'] = materias
        estudiantes[estudiante_index] = estudiante
        
        if guardar_estudiantes(estudiantes):
            return jsonify({
                'success': True,
                'message': 'Materia eliminada exitosamente',
                'data': materia_eliminada
            })
        else:
            return jsonify({
                'success': False,
                'error': 'Error al guardar los datos'
            }), 500
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/estadisticas', methods=['GET'])
def obtener_estadisticas():
    """Obtener estadísticas del sistema"""
    try:
        estudiantes = cargar_estudiantes()
        
        total_estudiantes = len(estudiantes)
        total_materias = 0
        calificaciones_count = {'A': 0, 'B': 0, 'C': 0, 'D': 0, 'F': 0, 'I': 0, 'N': 0}
        carreras_count = {}
        
        for estudiante in estudiantes:
            materias = estudiante.get('materias', [])
            total_materias += len(materias)
            
            for materia in materias:
                calificacion = materia['calificacion']
                if calificacion in calificaciones_count:
                    calificaciones_count[calificacion] += 1
                
                carrera = materia['carrera']
                carreras_count[carrera] = carreras_count.get(carrera, 0) + 1
        
        return jsonify({
            'success': True,
            'data': {
                'total_estudiantes': total_estudiantes,
                'total_materias': total_materias,
                'total_carreras': len(carreras_count),
                'calificaciones': calificaciones_count,
                'carreras': carreras_count
            }
        })
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/health', methods=['GET'])
def health_check():
    """Endpoint para verificar que el API está funcionando"""
    return jsonify({
        'success': True,
        'message': 'API funcionando correctamente',
        'timestamp': datetime.now().isoformat()
    })

if __name__ == '__main__':
    print("🚀 Iniciando servidor API de Gestión Estudiantil...")
    print("📊 Endpoints disponibles:")
    print("   GET  /api/estudiantes - Obtener todos los estudiantes")
    print("   POST /api/estudiantes - Crear estudiante o agregar materia")
    print("   GET  /api/estadisticas - Obtener estadísticas")
    print("   GET  /api/health - Verificar estado del API")
    print("\n🌐 Servidor corriendo en: http://localhost:5000")
    
    # Crear directorio data si no existe
    os.makedirs('data', exist_ok=True)
    
    app.run(debug=True, host='0.0.0.0', port=5000)