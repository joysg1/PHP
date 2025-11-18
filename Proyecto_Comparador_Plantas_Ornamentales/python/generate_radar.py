import matplotlib.pyplot as plt
import numpy as np
import sys
import json
import os

def generate_radar_chart(plant1_data, plant2_data, output_path):
    try:
        # Configurar estilo profesional
        plt.style.use('default')
        plt.rcParams['font.family'] = 'sans-serif'
        plt.rcParams['font.sans-serif'] = ['DejaVu Sans', 'Arial', 'Helvetica']
        
        # Características a comparar
        categories = ['Resistencia', 'Mantenimiento', 'Floración', 'Adaptabilidad', 'Duración']
        
        # Valores de las plantas
        values1 = [
            plant1_data['characteristics']['resistencia'],
            plant1_data['characteristics']['mantenimiento'],
            plant1_data['characteristics']['floracion'],
            plant1_data['characteristics']['adaptabilidad'],
            plant1_data['characteristics']['duracion']
        ]
        
        values2 = [
            plant2_data['characteristics']['resistencia'],
            plant2_data['characteristics']['mantenimiento'],
            plant2_data['characteristics']['floracion'],
            plant2_data['characteristics']['adaptabilidad'],
            plant2_data['characteristics']['duracion']
        ]
        
        # Ángulos para el gráfico de radar
        angles = np.linspace(0, 2*np.pi, len(categories), endpoint=False).tolist()
        angles += angles[:1]  # Cerrar el círculo
        
        values1 += values1[:1]
        values2 += values2[:1]
        
        # Crear figura con tamaño más pequeño y adecuado
        fig, ax = plt.subplots(figsize=(8, 6), subplot_kw=dict(projection='polar'))
        fig.patch.set_facecolor('white')
        
        # Colores profesionales
        color1 = '#10b981'  # Verde esmeralda
        color2 = '#6366f1'  # Índigo
        
        # Dibujar las áreas con transparencia
        ax.fill(angles, values1, color=color1, alpha=0.25, label=plant1_data['name'], linewidth=0)
        ax.fill(angles, values2, color=color2, alpha=0.25, label=plant2_data['name'], linewidth=0)
        
        # Dibujar las líneas principales
        ax.plot(angles, values1, 'o-', linewidth=2, color=color1, markersize=6, label='_nolegend_')
        ax.plot(angles, values2, 'o-', linewidth=2, color=color2, markersize=6, label='_nolegend_')
        
        # Configurar el área del gráfico
        ax.set_ylim(0, 10)
        ax.set_yticks([2, 4, 6, 8, 10])
        ax.set_yticklabels(['2', '4', '6', '8', '10'], fontsize=9, color='#6b7280')
        
        # Configurar grid
        ax.grid(True, alpha=0.3, linewidth=0.5)
        ax.set_facecolor('#f8fafc')
        
        # Configurar etiquetas de categorías
        ax.set_xticks(angles[:-1])
        ax.set_xticklabels(categories, fontsize=10, fontweight=500)
        
        # Mejorar la legibilidad de las etiquetas
        for label, angle in zip(ax.get_xticklabels(), angles[:-1]):
            if angle == 0:
                label.set_horizontalalignment('center')
            elif 0 < angle < np.pi:
                label.set_horizontalalignment('left')
            elif angle == np.pi:
                label.set_horizontalalignment('center')
            else:
                label.set_horizontalalignment('right')
        
        # Añadir leyenda compacta
        legend = ax.legend(loc='upper right', bbox_to_anchor=(1.2, 1.0), 
                          frameon=True, fancybox=True, shadow=True,
                          fontsize=10, framealpha=0.95)
        legend.get_frame().set_facecolor('white')
        legend.get_frame().set_edgecolor('#e5e7eb')
        
        # Añadir título más pequeño
        plt.title(f'Comparación: {plant1_data["name"]} vs {plant2_data["name"]}', 
                 size=12, pad=20, fontweight=600, color='#1f2937')
        
        # Ajustar los márgenes
        plt.tight_layout()
        
        # Guardar con tamaño optimizado
        plt.savefig(output_path, dpi=100, bbox_inches='tight', 
                   facecolor='white', edgecolor='none',
                   transparent=False, pad_inches=0.1)
        plt.close()
        
        return True
        
    except Exception as e:
        print(f"Error al generar el gráfico: {str(e)}")
        import traceback
        print(traceback.format_exc())
        return False

def generate_small_radar_chart(plant1_data, plant2_data, output_path):
    """Versión más compacta del gráfico de radar"""
    try:
        # Configuración mínima
        plt.style.use('default')
        
        categories = ['Resistencia', 'Mantenimiento', 'Floración', 'Adaptabilidad', 'Duración']
        
        values1 = [
            plant1_data['characteristics']['resistencia'],
            plant1_data['characteristics']['mantenimiento'],
            plant1_data['characteristics']['floracion'],
            plant1_data['characteristics']['adaptabilidad'],
            plant1_data['characteristics']['duracion']
        ]
        
        values2 = [
            plant2_data['characteristics']['resistencia'],
            plant2_data['characteristics']['mantenimiento'],
            plant2_data['characteristics']['floracion'],
            plant2_data['characteristics']['adaptabilidad'],
            plant2_data['characteristics']['duracion']
        ]
        
        angles = np.linspace(0, 2*np.pi, len(categories), endpoint=False).tolist()
        angles += angles[:1]
        values1 += values1[:1]
        values2 += values2[:1]
        
        # Figura más pequeña
        fig, ax = plt.subplots(figsize=(6, 4), subplot_kw=dict(projection='polar'))
        
        # Colores
        color1 = '#10b981'
        color2 = '#6366f1'
        
        # Gráfico simple
        ax.plot(angles, values1, 'o-', linewidth=1.5, color=color1, markersize=4, label=plant1_data['name'])
        ax.plot(angles, values2, 'o-', linewidth=1.5, color=color2, markersize=4, label=plant2_data['name'])
        ax.fill(angles, values1, color=color1, alpha=0.2)
        ax.fill(angles, values2, color=color2, alpha=0.2)
        
        # Configuración básica
        ax.set_ylim(0, 10)
        ax.set_yticks([5, 10])
        ax.set_yticklabels(['5', '10'], fontsize=8)
        ax.grid(True, alpha=0.3)
        ax.set_xticks(angles[:-1])
        ax.set_xticklabels(categories, fontsize=8)
        
        # Leyenda compacta
        ax.legend(loc='upper right', bbox_to_anchor=(1.3, 1.0), fontsize=8)
        
        plt.tight_layout()
        plt.savefig(output_path, dpi=100, bbox_inches='tight', facecolor='white')
        plt.close()
        
        return True
        
    except Exception as e:
        print(f"Error en gráfico pequeño: {str(e)}")
        return False

if __name__ == "__main__":
    if len(sys.argv) != 4:
        print("Uso: python generate_radar.py <plant1_json> <plant2_json> <output_path>")
        sys.exit(1)
    
    try:
        plant1_data = json.loads(sys.argv[1])
        plant2_data = json.loads(sys.argv[2])
        output_path = sys.argv[3]
        
        # Crear directorio si no existe
        os.makedirs(os.path.dirname(output_path), exist_ok=True)
        
        # Intentar con el gráfico principal primero
        success = generate_radar_chart(plant1_data, plant2_data, output_path)
        
        if not success:
            print("Intentando con gráfico pequeño...")
            success = generate_small_radar_chart(plant1_data, plant2_data, output_path)
        
        if success:
            print(f"Gráfico guardado en: {output_path}")
            
            # Verificar el tamaño del archivo
            if os.path.exists(output_path):
                file_size = os.path.getsize(output_path) / 1024  # KB
                print(f"Tamaño del archivo: {file_size:.1f} KB")
        else:
            print("Error: No se pudo generar el gráfico")
            sys.exit(1)
            
    except json.JSONDecodeError as e:
        print(f"Error decodificando JSON: {str(e)}")
        sys.exit(1)
    except Exception as e:
        print(f"Error inesperado: {str(e)}")
        import traceback
        print(traceback.format_exc())
        sys.exit(1)