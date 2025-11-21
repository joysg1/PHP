from flask import Flask, jsonify, request
from flask_cors import CORS
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import numpy as np
import json
import base64
import io
import os
from data_processor import DataProcessor
from ml_models import MLModels

app = Flask(__name__)
CORS(app)

# Configuración
DATA_FILE = os.path.join(os.path.dirname(__file__), '../data/war_economy_data.json')
OUTPUT_DIR = os.path.join(os.path.dirname(__file__), '../frontend/assets/charts')

# Inicializar procesador de datos
data_processor = DataProcessor(DATA_FILE)
data_loaded = data_processor.load_data()

def create_output_dir():
    """Crear directorio para gráficos si no existe"""
    if not os.path.exists(OUTPUT_DIR):
        os.makedirs(OUTPUT_DIR)
        print(f"Directorio creado: {OUTPUT_DIR}")
    
    # Crear archivo .htaccess para permitir acceso a gráficos
    htaccess_path = os.path.join(OUTPUT_DIR, '.htaccess')
    if not os.path.exists(htaccess_path):
        with open(htaccess_path, 'w') as f:
            f.write("Require all granted\n")

def fig_to_base64(fig):
    """Convertir figura matplotlib a base64"""
    try:
        buf = io.BytesIO()
        fig.savefig(buf, format='png', dpi=300, bbox_inches='tight', 
                    facecolor='#1e1e1e', edgecolor='none')
        buf.seek(0)
        img_base64 = base64.b64encode(buf.getvalue()).decode('utf-8')
        plt.close(fig)
        return img_base64
    except Exception as e:
        print(f"Error convirtiendo figura a base64: {e}")
        return None

@app.route('/api/stats', methods=['GET'])
def get_stats():
    """Obtener estadísticas generales"""
    print("Solicitando estadísticas...")
    try:
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        stats = data_processor.get_conflict_stats()
        conflicts = data_processor.get_conflict_list()
        print(f"Estadísticas generadas: {len(conflicts)} conflictos")
        return jsonify({
            'success': True,
            'stats': stats,
            'conflicts': conflicts
        })
    except Exception as e:
        print(f"Error en /api/stats: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/conflicts', methods=['GET'])
def get_conflicts():
    """Obtener lista de conflictos"""
    print("Solicitando lista de conflictos...")
    try:
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        conflicts = data_processor.get_conflict_list()
        print(f"Devolviendo {len(conflicts)} conflictos")
        return jsonify({'success': True, 'conflicts': conflicts})
    except Exception as e:
        print(f"Error en /api/conflicts: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/chart/area', methods=['GET'])
def generate_area_chart():
    """Generar gráfico de área sobre la curva"""
    print("Generando gráfico de área...")
    try:
        create_output_dir()
        
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        # Configurar estilo
        plt.style.use('dark_background')
        sns.set_palette("husl")
        
        # Preparar datos
        yearly_data = data_processor.get_yearly_data()
        df = pd.DataFrame(yearly_data)
        
        if df.empty:
            return jsonify({'success': False, 'error': 'No hay datos disponibles'})
            
        yearly_agg = df.groupby('year').agg({
            'gdp_change': 'mean',
            'inflation': 'mean',
            'conflict_id': 'nunique'
        }).reset_index()
        
        fig, ax = plt.subplots(figsize=(14, 8))
        
        # Gráfico de área para GDP
        ax.fill_between(yearly_agg['year'], yearly_agg['gdp_change'], 
                       alpha=0.6, label='Cambio GDP (%)', color='#ff6b6b')
        
        # Gráfico de área para inflación
        ax.fill_between(yearly_agg['year'], yearly_agg['inflation'], 
                       alpha=0.6, label='Inflación (%)', color='#4ecdc4')
        
        ax.set_xlabel('Año', fontsize=12, color='white')
        ax.set_ylabel('Porcentaje (%)', fontsize=12, color='white')
        ax.set_title('Evolución del GDP e Inflación durante Conflictos Bélicos (1914-2025)', 
                    fontsize=14, color='white', pad=20)
        ax.legend(fontsize=10)
        ax.grid(True, alpha=0.3)
        
        img_base64 = fig_to_base64(fig)
        if img_base64:
            print("Gráfico de área generado exitosamente")
            return jsonify({'success': True, 'image': img_base64})
        else:
            return jsonify({'success': False, 'error': 'Error generando imagen'})
        
    except Exception as e:
        print(f"Error en generate_area_chart: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/chart/radar', methods=['GET'])
def generate_radar_chart():
    """Generar gráfico radar"""
    print("Generando gráfico radar...")
    try:
        create_output_dir()
        
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        # Obtener datos de regiones
        region_data = data_processor.get_region_data()
        df_regions = pd.DataFrame(region_data)
        
        if df_regions.empty:
            return jsonify({'success': False, 'error': 'No hay datos regionales disponibles'})
        
        # Seleccionar métricas para radar
        metrics = ['gdp_change', 'inflation', 'unemployment', 'military_spending']
        
        # Verificar que tenemos datos para las métricas
        available_metrics = [m for m in metrics if m in df_regions.columns]
        if not available_metrics:
            return jsonify({'success': False, 'error': 'No hay métricas disponibles para el gráfico radar'})
        
        fig = plt.figure(figsize=(12, 10))
        
        # Normalizar datos para radar chart
        normalized_data = []
        for metric in available_metrics:
            min_val = df_regions[metric].min()
            max_val = df_regions[metric].max()
            if max_val - min_val > 0:
                normalized = (df_regions[metric] - min_val) / (max_val - min_val)
            else:
                normalized = df_regions[metric] * 0
            normalized_data.append(normalized)
        
        # Crear ángulos para el radar chart
        angles = np.linspace(0, 2*np.pi, len(available_metrics), endpoint=False).tolist()
        angles += angles[:1]  # Cerrar el círculo
        
        # Crear subplot polar
        ax = fig.add_subplot(111, polar=True)
        
        # Paleta de colores más variada y contrastante
        colors = [
            '#FF6B6B',  # Rojo coral
            '#4ECDC4',  # Turquesa
            '#45B7D1',  # Azul claro
            '#96CEB4',  # Verde menta
            '#FFEAA7',  # Amarillo pastel
            '#DDA0DD',  # Ciruela
            '#98D8C8',  # Verde agua
            '#F7DC6F',  # Amarillo mostaza
            '#BB8FCE',  # Lavanda
            '#85C1E9'   # Azul cielo
        ]
        
        # Plot cada región
        for idx, region in enumerate(df_regions['region']):
            values = [normalized_data[i].iloc[idx] for i in range(len(available_metrics))]
            values += values[:1]  # Cerrar el círculo
            color = colors[idx % len(colors)]
            ax.plot(angles, values, 'o-', linewidth=2.5, label=region, color=color, markersize=6)
            ax.fill(angles, values, alpha=0.25, color=color)
        
        # Configurar ejes
        ax.set_xticks(angles[:-1])
        metric_labels = {
            'gdp_change': 'GDP',
            'inflation': 'Inflación',
            'unemployment': 'Desempleo',
            'military_spending': 'Gasto Militar'
        }
        labels = [metric_labels.get(m, m) for m in available_metrics]
        ax.set_xticklabels(labels, fontsize=12, color='white', fontweight='bold')
        
        # Configurar grid
        ax.set_ylim(0, 1)
        ax.set_yticks([0.2, 0.4, 0.6, 0.8, 1.0])
        ax.set_yticklabels(['0.2', '0.4', '0.6', '0.8', '1.0'], fontsize=10, color='white', alpha=0.7)
        ax.grid(True, alpha=0.3)
        
        # Título y leyenda
        ax.set_title('Comparación Regional del Impacto Económico de Conflictos', 
                    size=16, color='white', pad=30, fontweight='bold')
        ax.legend(loc='upper right', bbox_to_anchor=(1.4, 1.1), fontsize=11, 
                 frameon=True, fancybox=True, shadow=True, facecolor='#2c2c2c')
        
        img_base64 = fig_to_base64(fig)
        if img_base64:
            print("Gráfico radar generado exitosamente")
            return jsonify({'success': True, 'image': img_base64})
        else:
            return jsonify({'success': False, 'error': 'Error generando imagen'})
        
    except Exception as e:
        print(f"Error en generate_radar_chart: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/chart/stacked', methods=['GET'])
def generate_stacked_bar():
    """Generar gráfico de barras apiladas - CORREGIDO: problema de comillas en bbox"""
    print("Generando gráfico de barras apiladas...")
    try:
        create_output_dir()
        
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        plt.style.use('dark_background')
        fig, ax = plt.subplots(figsize=(16, 10))
        
        # Preparar datos por década
        yearly_data = data_processor.get_yearly_data()
        df = pd.DataFrame(yearly_data)
        
        if df.empty:
            return jsonify({'success': False, 'error': 'No hay datos disponibles'})
            
        # Crear décadas
        df['decade'] = (df['year'] // 10) * 10
        
        # Agrupar por década
        decade_stats = df.groupby('decade').agg({
            'gdp_change': 'mean',
            'inflation': 'mean',
            'unemployment': 'mean',
            'military_spending': 'mean',
            'conflict_id': 'nunique'
        }).reset_index()
        
        # Filtrar solo las métricas que existen
        metrics = ['gdp_change', 'inflation', 'unemployment', 'military_spending']
        available_metrics = [m for m in metrics if m in decade_stats.columns]
        
        if not available_metrics:
            return jsonify({'success': False, 'error': 'No hay métricas disponibles para el gráfico de barras'})
        
        # Crear barras apiladas
        bar_width = 8
        decades = decade_stats['decade']
        
        metric_labels = {
            'gdp_change': 'GDP',
            'inflation': 'Inflación', 
            'unemployment': 'Desempleo',
            'military_spending': 'Gasto Militar'
        }
        
        # Paleta de colores más variada y contrastante
        colors = [
            '#FF6B6B',  # Rojo coral
            '#4ECDC4',  # Turquesa
            '#45B7D1',  # Azul claro
            '#F7DC6F',  # Amarillo mostaza
            '#DDA0DD',  # Ciruela
            '#98D8C8',  # Verde agua
            '#FFEAA7',  # Amarillo pastel
            '#BB8FCE'   # Lavanda
        ]
        
        bottom = np.zeros(len(decades))
        bars = []
        
        for i, metric in enumerate(available_metrics):
            if metric in decade_stats.columns:
                values = decade_stats[metric].fillna(0)
                bar = ax.bar(decades, values, bar_width, 
                           label=metric_labels.get(metric, metric),
                           bottom=bottom, 
                           color=colors[i % len(colors)], 
                           alpha=0.85,
                           edgecolor='white',
                           linewidth=0.5)
                bars.append(bar)
                
                # Agregar etiquetas de valores en cada segmento de barra - CORREGIDO
                for j, (decade, value) in enumerate(zip(decades, values)):
                    if abs(value) > 1:  # Solo mostrar etiquetas para valores significativos
                        y_pos = bottom[j] + value / 2
                        # Usar diccionario correctamente para bbox
                        bbox_props = dict(boxstyle="round,pad=0.3", facecolor='black', alpha=0.7, edgecolor='none')
                        ax.text(decade, y_pos, f'{value:.1f}%', 
                               ha='center', va='center', 
                               fontsize=9, fontweight='bold', 
                               color='white',
                               bbox=bbox_props)
                
                bottom += values
        
        # Configurar ejes y estilo
        ax.set_xlabel('Década', fontsize=13, color='white', fontweight='bold')
        ax.set_ylabel('Valores Acumulados (%)', fontsize=13, color='white', fontweight='bold')
        ax.set_title('Indicadores Económicos por Década durante Conflictos Bélicos', 
                    fontsize=16, color='white', pad=25, fontweight='bold')
        
        # Mejorar la leyenda
        ax.legend(fontsize=11, loc='upper left', frameon=True, 
                 fancybox=True, shadow=True, facecolor='#2c2c2c')
        
        # Configurar grid
        ax.grid(True, alpha=0.3, linestyle='--')
        ax.set_axisbelow(True)
        
        # Rotar etiquetas del eje X para mejor legibilidad
        plt.xticks(decades, rotation=45, color='white', fontsize=11)
        plt.yticks(color='white', fontsize=11)
        
        # Ajustar layout
        plt.tight_layout()
        
        img_base64 = fig_to_base64(fig)
        if img_base64:
            print("Gráfico de barras apiladas generado exitosamente")
            return jsonify({'success': True, 'image': img_base64})
        else:
            return jsonify({'success': False, 'error': 'Error generando imagen'})
        
    except Exception as e:
        print(f"Error en generate_stacked_bar: {str(e)}")
        import traceback
        print(f"Traceback: {traceback.format_exc()}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/chart/pie', methods=['GET'])
def generate_pie_chart():
    """Generar gráfico de pastel"""
    print("Generando gráfico de pastel...")
    try:
        create_output_dir()
        
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        plt.style.use('dark_background')
        fig, ax = plt.subplots(figsize=(12, 10))
        
        # Preparar datos por región
        region_data = data_processor.get_region_data()
        df_regions = pd.DataFrame(region_data)
        
        if df_regions.empty or 'conflict_id' not in df_regions.columns:
            return jsonify({'success': False, 'error': 'No hay datos regionales disponibles'})
        
        # Gráfico de pastel para distribución de conflictos
        sizes = df_regions['conflict_id']
        labels = df_regions['region']
        colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#F7DC6F', '#DDA0DD', '#98D8C8', '#BB8FCE']
        
        # Filtrar regiones sin conflictos
        valid_data = [(size, label) for size, label in zip(sizes, labels) if size > 0]
        if not valid_data:
            return jsonify({'success': False, 'error': 'No hay conflictos para mostrar'})
            
        sizes_valid, labels_valid = zip(*valid_data)
        
        wedges, texts, autotexts = ax.pie(sizes_valid, 
                                         labels=labels_valid, 
                                         colors=colors[:len(sizes_valid)], 
                                         autopct='%1.1f%%', 
                                         startangle=90,
                                         textprops={'color': 'white', 'fontsize': 11, 'fontweight': 'bold'},
                                         wedgeprops={'edgecolor': 'white', 'linewidth': 1.5})
        
        # Mejorar estética
        for autotext in autotexts:
            autotext.set_color('black')
            autotext.set_fontweight('bold')
            autotext.set_fontsize(10)
        
        # Hacer el título más prominente
        ax.set_title('Distribución de Conflictos Bélicos por Región (1914-2025)', 
                    fontsize=16, color='white', pad=25, fontweight='bold')
        
        img_base64 = fig_to_base64(fig)
        if img_base64:
            print("Gráfico de pastel generado exitosamente")
            return jsonify({'success': True, 'image': img_base64})
        else:
            return jsonify({'success': False, 'error': 'Error generando imagen'})
        
    except Exception as e:
        print(f"Error en generate_pie_chart: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/ml/clusters', methods=['GET'])
def get_clusters():
    """Obtener clusters de conflictos usando ML"""
    print("Solicitando clusters ML...")
    try:
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        ml_processor = MLModels(data_processor.df)
        clusters = ml_processor.cluster_conflicts(n_clusters=4)
        print("Clusters ML generados exitosamente")
        return jsonify({'success': True, 'clusters': clusters})
    except Exception as e:
        print(f"Error en /api/ml/clusters: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/ml/predict', methods=['POST'])
def predict_impact():
    """Predecir impacto económico"""
    print("Solicitando predicción ML...")
    try:
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        data = request.get_json()
        features = data.get('features', ['duration', 'countries_involved', 'military_spending'])
        target = data.get('target', 'gdp_change')
        
        print(f"Predicción con features: {features}, target: {target}")
        
        ml_processor = MLModels(data_processor.df)
        prediction = ml_processor.predict_economic_impact(features, target)
        
        print("Predicción ML generada exitosamente")
        return jsonify({'success': True, 'prediction': prediction})
    except Exception as e:
        print(f"Error en /api/ml/predict: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/ml/trends', methods=['GET'])
def get_trends():
    """Obtener análisis de tendencias"""
    print("Solicitando tendencias ML...")
    try:
        if not data_loaded:
            return jsonify({'success': False, 'error': 'Datos no cargados correctamente'})
        
        ml_processor = MLModels(data_processor.df)
        trends = ml_processor.trend_analysis()
        print("Tendencias ML generadas exitosamente")
        return jsonify({'success': True, 'trends': trends})
    except Exception as e:
        print(f"Error en /api/ml/trends: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

@app.route('/api/health', methods=['GET'])
def health_check():
    """Endpoint de salud para verificar que el servidor está funcionando"""
    return jsonify({
        'success': True,
        'status': 'running',
        'data_loaded': data_loaded,
        'data_file': DATA_FILE
    })

@app.route('/api/debug/chart/<chart_type>', methods=['GET'])
def debug_chart(chart_type):
    """Endpoint de debug para gráficos"""
    print(f"Debug solicitado para gráfico: {chart_type}")
    
    chart_functions = {
        'area': generate_area_chart,
        'radar': generate_radar_chart,
        'stacked': generate_stacked_bar,
        'pie': generate_pie_chart
    }
    
    if chart_type in chart_functions:
        return chart_functions[chart_type]()
    else:
        return jsonify({'success': False, 'error': f'Tipo de gráfico no válido: {chart_type}'})

if __name__ == '__main__':
    create_output_dir()
    
    if data_loaded:
        print("✓ Datos cargados correctamente")
        stats = data_processor.get_conflict_stats()
        print(f"✓ {stats.get('total_conflicts', 0)} conflictos cargados")
        print(f"✓ Período: {stats.get('years_covered', 'N/A')}")
    else:
        print("✗ Error cargando datos")
    
    print("Servidor Flask iniciado en http://localhost:5000")
    print("Endpoints disponibles:")
    print("  GET  /api/stats")
    print("  GET  /api/conflicts")
    print("  GET  /api/chart/area")
    print("  GET  /api/chart/radar")
    print("  GET  /api/chart/stacked")
    print("  GET  /api/chart/pie")
    print("  GET  /api/ml/clusters")
    print("  POST /api/ml/predict")
    print("  GET  /api/ml/trends")
    print("  GET  /api/health")
    print("  GET  /api/debug/chart/<tipo>")
    
    app.run(debug=True, port=5000)