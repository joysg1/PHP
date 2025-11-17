#!/usr/bin/env python3
import json
import sys
import os
import matplotlib
matplotlib.use('Agg')  # ¡IMPORTANTE para servidores web!
import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd
import numpy as np
import base64
from io import BytesIO

class GeneradorGraficos:
    def __init__(self):
        self.continentes_file = 'continentes_data.json'
        self.setup_estilo()
    
    def setup_estilo(self):
        """Configura el estilo de los gráficos"""
        plt.style.use('default')
        sns.set_palette("husl")
    
    def _load_continentes(self):
        """Carga datos de continentes"""
        try:
            with open(self.continentes_file, 'r', encoding='utf-8') as f:
                return json.load(f)
        except Exception as e:
            print(f"ERROR cargando continentes: {e}", file=sys.stderr)
            return {}
    
    def _figura_a_base64(self, fig):
        """Convierte figura matplotlib a base64"""
        buffer = BytesIO()
        fig.savefig(buffer, format='png', dpi=100, bbox_inches='tight', 
                   facecolor='white', edgecolor='none')
        buffer.seek(0)
        image_base64 = base64.b64encode(buffer.getvalue()).decode('utf-8')
        plt.close(fig)  # Liberar memoria
        return image_base64
    
    def grafico_poblacion(self):
        """Genera gráfico de población por continente"""
        continentes = self._load_continentes()
        if not continentes:
            return None
        
        nombres = []
        poblaciones = []
        colores = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD']
        
        for nombre, datos in continentes.items():
            nombres.append(datos['nombre_completo'])
            poblaciones.append(datos['poblacion'] / 1000000)  # Convertir a millones
        
        fig, ax = plt.subplots(figsize=(10, 6))
        bars = ax.bar(nombres, poblaciones, color=colores[:len(nombres)], alpha=0.8)
        
        # Añadir valores en las barras
        for bar, poblacion in zip(bars, poblaciones):
            height = bar.get_height()
            ax.text(bar.get_x() + bar.get_width()/2., height + 0.1,
                   f'{poblacion:.1f}M', ha='center', va='bottom', fontweight='bold')
        
        ax.set_title('🌍 Población por Continente (en millones)', fontsize=14, fontweight='bold', pad=20)
        ax.set_ylabel('Población (Millones)')
        ax.tick_params(axis='x', rotation=45)
        ax.grid(axis='y', alpha=0.3)
        
        plt.tight_layout()
        return self._figura_a_base64(fig)
    
    def grafico_area(self):
        """Genera gráfico de área por continente"""
        continentes = self._load_continentes()
        if not continentes:
            return None
        
        nombres = []
        areas = []
        
        for nombre, datos in continentes.items():
            nombres.append(datos['nombre_completo'])
            areas.append(datos['area_km2'] / 1000000)  # Convertir a millones de km²
        
        fig, ax = plt.subplots(figsize=(10, 6))
        bars = ax.bar(nombres, areas, color='lightblue', alpha=0.7)
        
        # Añadir valores en las barras
        for bar, area in zip(bars, areas):
            height = bar.get_height()
            ax.text(bar.get_x() + bar.get_width()/2., height + 0.1,
                   f'{area:.1f}M km²', ha='center', va='bottom', fontweight='bold')
        
        ax.set_title('🗺️ Área por Continente (millones de km²)', fontsize=14, fontweight='bold', pad=20)
        ax.set_ylabel('Área (Millones de km²)')
        ax.tick_params(axis='x', rotation=45)
        ax.grid(axis='y', alpha=0.3)
        
        plt.tight_layout()
        return self._figura_a_base64(fig)
    
    def grafico_densidad(self):
        """Genera gráfico de densidad poblacional"""
        continentes = self._load_continentes()
        if not continentes:
            return None
        
        nombres = []
        densidades = []
        
        for nombre, datos in continentes.items():
            if datos['area_km2'] > 0:
                nombres.append(datos['nombre_completo'])
                densidad = datos['poblacion'] / datos['area_km2']
                densidades.append(densidad)
        
        fig, ax = plt.subplots(figsize=(10, 6))
        bars = ax.bar(nombres, densidades, color='lightgreen', alpha=0.7)
        
        # Añadir valores en las barras
        for bar, densidad in zip(bars, densidades):
            height = bar.get_height()
            ax.text(bar.get_x() + bar.get_width()/2., height + 0.1,
                   f'{densidad:.1f} hab/km²', ha='center', va='bottom', fontsize=9)
        
        ax.set_title('📊 Densidad Poblacional por Continente', fontsize=14, fontweight='bold', pad=20)
        ax.set_ylabel('Habitantes por km²')
        ax.tick_params(axis='x', rotation=45)
        ax.grid(axis='y', alpha=0.3)
        
        plt.tight_layout()
        return self._figura_a_base64(fig)
    
    def grafico_comparativo(self):
        """Genera gráfico comparativo múltiple"""
        continentes = self._load_continentes()
        if not continentes:
            return None
        
        # Preparar datos
        datos_grafico = []
        for nombre, datos in continentes.items():
            datos_grafico.append({
                'Continente': datos['nombre_completo'],
                'Población (M)': datos['poblacion'] / 1000000,
                'Área (M km²)': datos['area_km2'] / 1000000,
                'Países': datos['paises_count']
            })
        
        df = pd.DataFrame(datos_grafico)
        
        fig, axes = plt.subplots(2, 2, figsize=(12, 10))
        fig.suptitle('📈 Comparativa Completa de Continentes', fontsize=16, fontweight='bold')
        
        # Gráfico 1: Población
        axes[0,0].bar(df['Continente'], df['Población (M)'], color='skyblue', alpha=0.8)
        axes[0,0].set_title('Población (Millones)')
        axes[0,0].tick_params(axis='x', rotation=45)
        axes[0,0].grid(axis='y', alpha=0.3)
        
        # Gráfico 2: Área
        axes[0,1].bar(df['Continente'], df['Área (M km²)'], color='lightcoral', alpha=0.8)
        axes[0,1].set_title('Área (Millones km²)')
        axes[0,1].tick_params(axis='x', rotation=45)
        axes[0,1].grid(axis='y', alpha=0.3)
        
        # Gráfico 3: Países
        axes[1,0].bar(df['Continente'], df['Países'], color='lightgreen', alpha=0.8)
        axes[1,0].set_title('Número de Países')
        axes[1,0].tick_params(axis='x', rotation=45)
        axes[1,0].grid(axis='y', alpha=0.3)
        
        # Gráfico 4: Pie chart de población
        axes[1,1].pie(df['Población (M)'], labels=df['Continente'], autopct='%1.1f%%', 
                     startangle=90, colors=sns.color_palette("Set3"))
        axes[1,1].set_title('Distribución Poblacional')
        
        plt.tight_layout()
        return self._figura_a_base64(fig)
    
    def grafico_paises_top(self):
        """Genera gráfico de los 10 países más poblados"""
        continentes = self._load_continentes()
        if not continentes:
            return None
        
        # Recolectar todos los países
        todos_paises = []
        for datos in continentes.values():
            for pais in datos.get('paises', []):
                todos_paises.append({
                    'nombre': pais['nombre'],
                    'poblacion': pais['poblacion'],
                    'continente': datos['nombre_completo']
                })
        
        # Ordenar y tomar top 10
        top_paises = sorted(todos_paises, key=lambda x: x['poblacion'], reverse=True)[:10]
        
        nombres = [p['nombre'] for p in top_paises]
        poblaciones = [p['poblacion'] / 1000000 for p in top_paises]  # A millones
        continentes_pais = [p['continente'] for p in top_paises]
        
        fig, ax = plt.subplots(figsize=(12, 8))
        
        # Crear mapeo de colores por continente
        continentes_unicos = list(set(continentes_pais))
        palette = sns.color_palette("husl", len(continentes_unicos))
        color_map = dict(zip(continentes_unicos, palette))
        colores = [color_map[cont] for cont in continentes_pais]
        
        bars = ax.barh(nombres, poblaciones, color=colores, alpha=0.8)
        
        # Añadir valores
        for bar, poblacion in zip(bars, poblaciones):
            width = bar.get_width()
            ax.text(width + 0.1, bar.get_y() + bar.get_height()/2.,
                   f'{poblacion:.1f}M', ha='left', va='center', fontweight='bold')
        
        ax.set_title('🏆 Top 10 Países Más Poblados del Mundo', fontsize=14, fontweight='bold', pad=20)
        ax.set_xlabel('Población (Millones)')
        ax.grid(axis='x', alpha=0.3)
        
        # Añadir leyenda
        from matplotlib.patches import Patch
        legend_elements = [Patch(facecolor=color_map[cont], label=cont) 
                          for cont in continentes_unicos]
        ax.legend(handles=legend_elements, loc='lower right')
        
        plt.tight_layout()
        return self._figura_a_base64(fig)
    
    def generar_todos_los_graficos(self):
        """Genera todos los gráficos y retorna en base64"""
        try:
            return {
                'poblacion': self.grafico_poblacion(),
                'area': self.grafico_area(),
                'densidad': self.grafico_densidad(),
                'comparativo': self.grafico_comparativo(),
                'paises_top': self.grafico_paises_top()
            }
        except Exception as e:
            print(f"ERROR generando gráficos: {e}", file=sys.stderr)
            return {"error": str(e)}

def ejecutar_comando_graficos(comando, datos=None):
    """Ejecuta comandos de gráficos desde PHP"""
    generador = GeneradorGraficos()
    
    try:
        if comando == "generar_todos":
            return generador.generar_todos_los_graficos()
        
        elif comando == "grafico_poblacion":
            return {"poblacion": generador.grafico_poblacion()}
        
        elif comando == "grafico_area":
            return {"area": generador.grafico_area()}
        
        elif comando == "grafico_densidad":
            return {"densidad": generador.grafico_densidad()}
        
        elif comando == "grafico_comparativo":
            return {"comparativo": generador.grafico_comparativo()}
        
        elif comando == "grafico_paises_top":
            return {"paises_top": generador.grafico_paises_top()}
        
        else:
            return {"error": f"Comando de gráficos no reconocido: {comando}"}
            
    except Exception as e:
        return {"error": f"Error en gráficos: {str(e)}"}

if __name__ == "__main__":
    # Comunicación via STDIN/STDOUT
    try:
        input_data = json.loads(sys.stdin.read())
        comando = input_data.get('comando')
        datos = input_data.get('datos', {})
        
        resultado = ejecutar_comando_graficos(comando, datos)
        print(json.dumps(resultado, ensure_ascii=False))
        
    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))