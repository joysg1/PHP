import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
import json
import sys
import os
from math import pi

# Configurar estilo con colores claros para texto
plt.rcParams['text.color'] = '#e2e8f0'
plt.rcParams['axes.labelcolor'] = '#e2e8f0'
plt.rcParams['xtick.color'] = '#e2e8f0'
plt.rcParams['ytick.color'] = '#e2e8f0'
plt.rcParams['axes.facecolor'] = '#1e293b'
plt.rcParams['figure.facecolor'] = '#0f172a'

def create_mass_composition_chart(data, output_path):
    """Gráfico de torta para composición de masas"""
    try:
        names = [body['name'] for body in data['celestial_bodies']]
        masses = [body['mass_kg'] for body in data['celestial_bodies']]
        colors = [body['color'] for body in data['celestial_bodies']]
        
        # Filtrar masas muy pequeñas para mejor visualización
        total_mass = sum(masses)
        threshold = total_mass * 0.01  # 1% del total
        
        filtered_data = []
        for name, mass, color in zip(names, masses, colors):
            if mass >= threshold:
                filtered_data.append((name, mass, color))
        
        if not filtered_data:
            filtered_data = list(zip(names, masses, colors))
        
        filtered_names, filtered_masses, filtered_colors = zip(*filtered_data)
        
        fig, ax = plt.subplots(figsize=(10, 8))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        wedges, texts, autotexts = ax.pie(filtered_masses, labels=filtered_names, colors=filtered_colors,
                                         autopct='%1.1f%%', startangle=90, shadow=False)
        
        # Mejorar estilo del texto
        for text in texts:
            text.set_color('#e2e8f0')
            text.set_fontsize(9)
        for autotext in autotexts:
            autotext.set_color('#0f172a')
            autotext.set_fontweight('bold')
            autotext.set_fontsize(8)
        
        ax.set_title('Composición de Masas del Sistema Solar', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico de composición de masas: {str(e)}")
        return False

def create_temperature_area_chart(data, output_path):
    """Gráfico de área para temperaturas"""
    try:
        planets = [body for body in data['celestial_bodies'] if body['type'] == 'planet']
        
        if not planets:
            return False
            
        names = [planet['name'] for planet in planets]
        temperatures = [planet['temperature_k'] for planet in planets]
        colors = [planet['color'] for planet in planets]
        
        fig, ax = plt.subplots(figsize=(12, 6))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        # Crear gráfico de área
        x = range(len(names))
        ax.fill_between(x, temperatures, alpha=0.3, color='#6366f1')
        line = ax.plot(x, temperatures, color='#6366f1', linewidth=2, marker='o', markersize=6)[0]
        
        # Colorear los puntos
        for i, (temp, color) in enumerate(zip(temperatures, colors)):
            ax.plot(i, temp, 'o', color=color, markersize=8, markeredgecolor='white', markeredgewidth=1)
        
        ax.set_title('Distribución de Temperaturas Planetarias', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        ax.set_xlabel('Planetas', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_ylabel('Temperatura (K)', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_xticks(x)
        ax.set_xticklabels(names, rotation=45, ha='right', color='#94a3b8')
        ax.tick_params(axis='y', colors='#94a3b8')
        
        # Añadir valores
        for i, (name, temp) in enumerate(zip(names, temperatures)):
            ax.annotate(f'{temp}K', (i, temp), xytext=(0, 10), textcoords='offset points',
                       ha='center', va='bottom', fontsize=8, color='#e2e8f0',
                       bbox=dict(boxstyle='round,pad=0.2', facecolor='#1e293b', alpha=0.8))
        
        ax.grid(True, alpha=0.2, color='#475569')
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico de área de temperaturas: {str(e)}")
        return False

def create_size_comparison_chart(data, output_path):
    """Gráfico de barras para comparación de tamaños"""
    try:
        names = [body['name'] for body in data['celestial_bodies']]
        diameters = [body['diameter_km'] for body in data['celestial_bodies']]
        colors = [body['color'] for body in data['celestial_bodies']]
        
        fig, ax = plt.subplots(figsize=(12, 6))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        bars = ax.bar(names, diameters, color=colors, alpha=0.8, edgecolor='white', linewidth=1)
        
        ax.set_title('Comparación de Diámetros Planetarios', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        ax.set_xlabel('Cuerpos Celestes', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_ylabel('Diámetro (km)', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.tick_params(axis='x', rotation=45, colors='#94a3b8')
        ax.tick_params(axis='y', colors='#94a3b8')
        
        # Añadir valores en las barras
        for bar, diameter in zip(bars, diameters):
            height = bar.get_height()
            ax.text(bar.get_x() + bar.get_width()/2., height + 1000,
                   f'{diameter:,} km', ha='center', va='bottom', 
                   fontsize=8, color='#e2e8f0', fontweight='bold')
        
        ax.grid(True, alpha=0.2, color='#475569', axis='y')
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico de comparación de tamaños: {str(e)}")
        return False

def create_orbital_periods_chart(data, output_path):
    """Gráfico de barras para períodos orbitales"""
    try:
        planets = [body for body in data['celestial_bodies'] if body['type'] == 'planet']
        
        if not planets:
            return False
        
        names = [planet['name'] for planet in planets]
        periods = [planet['orbital_period_days'] for planet in planets]
        colors = [planet['color'] for planet in planets]
        
        fig, ax = plt.subplots(figsize=(12, 6))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        bars = ax.bar(names, periods, color=colors, alpha=0.8, edgecolor='white', linewidth=1)
        
        ax.set_title('Períodos Orbitales de los Planetas', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        ax.set_xlabel('Planetas', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_ylabel('Días Orbitales', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.tick_params(axis='x', rotation=45, colors='#94a3b8')
        ax.tick_params(axis='y', colors='#94a3b8')
        
        # Añadir valores en las barras
        for bar, period in zip(bars, periods):
            height = bar.get_height()
            ax.text(bar.get_x() + bar.get_width()/2., height + 100,
                   f'{period:,} días', ha='center', va='bottom', 
                   fontsize=8, color='#e2e8f0', fontweight='bold')
        
        ax.grid(True, alpha=0.2, color='#475569', axis='y')
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico de períodos orbitales: {str(e)}")
        return False

def create_planet_types_chart(data, output_path):
    """Gráfico de dona para tipos de cuerpos celestes"""
    try:
        type_count = {}
        type_colors = {}
        
        for body in data['celestial_bodies']:
            body_type = body['type']
            if body_type not in type_count:
                type_count[body_type] = 0
                type_colors[body_type] = body['color']
            type_count[body_type] += 1
        
        types = list(type_count.keys())
        counts = list(type_count.values())
        colors = [type_colors[t] for t in types]
        
        fig, ax = plt.subplots(figsize=(8, 8))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        wedges, texts, autotexts = ax.pie(counts, labels=types, colors=colors, autopct='%1.0f%%',
                                         startangle=90, wedgeprops=dict(width=0.3))
        
        # Mejorar estilo del texto
        for text in texts:
            text.set_color('#e2e8f0')
            text.set_fontsize(10)
        for autotext in autotexts:
            autotext.set_color('#0f172a')
            autotext.set_fontweight('bold')
        
        ax.set_title('Distribución por Tipo de Cuerpo Celeste', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico de tipos de planetas: {str(e)}")
        return False

def create_moon_distribution_chart(data, output_path):
    """Gráfico de barras horizontales para distribución de lunas"""
    try:
        planets_with_moons = [body for body in data['celestial_bodies'] if body['type'] == 'planet' and body.get('moons')]
        
        if not planets_with_moons:
            # Crear gráfico alternativo si no hay lunas
            return create_alternative_chart(data, output_path)
        
        names = [planet['name'] for planet in planets_with_moons]
        moon_counts = [len(planet['moons']) for planet in planets_with_moons]
        colors = [planet['color'] for planet in planets_with_moons]
        
        fig, ax = plt.subplots(figsize=(10, 6))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        bars = ax.barh(names, moon_counts, color=colors, alpha=0.8, edgecolor='white', linewidth=1)
        
        ax.set_title('Distribución de Satélites Naturales', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        ax.set_xlabel('Número de Lunas', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_ylabel('Planetas', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.tick_params(axis='x', colors='#94a3b8')
        ax.tick_params(axis='y', colors='#94a3b8')
        
        # Añadir valores en las barras
        for bar, count in zip(bars, moon_counts):
            width = bar.get_width()
            ax.text(width + 0.1, bar.get_y() + bar.get_height()/2.,
                   f'{count}', ha='left', va='center', 
                   fontsize=9, color='#e2e8f0', fontweight='bold')
        
        ax.grid(True, alpha=0.2, color='#475569', axis='x')
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico de distribución de lunas: {str(e)}")
        return False

def create_density_comparison_chart(data, output_path):
    """Gráfico de dispersión para comparación de densidades"""
    try:
        # Calcular densidad aproximada (masa/volumen)
        densities = []
        names = []
        colors = []
        
        for body in data['celestial_bodies']:
            if body['type'] != 'star':  # Excluir el sol para mejor escala
                volume = (4/3) * pi * (body['diameter_km']/2 * 1000)**3  # en m³
                if volume > 0:
                    density = body['mass_kg'] / volume
                    densities.append(density)
                    names.append(body['name'])
                    colors.append(body['color'])
        
        if not densities:
            return False
            
        fig, ax = plt.subplots(figsize=(12, 6))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        scatter = ax.scatter(range(len(densities)), densities, c=colors, s=150, alpha=0.8, edgecolors='white', linewidth=1)
        
        ax.set_title('Comparación de Densidades', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        ax.set_xlabel('Cuerpos Celestes', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_ylabel('Densidad (kg/m³)', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_xticks(range(len(names)))
        ax.set_xticklabels(names, rotation=45, ha='right', color='#94a3b8')
        ax.tick_params(axis='y', colors='#94a3b8')
        
        # Añadir etiquetas de valores
        for i, (name, density) in enumerate(zip(names, densities)):
            ax.annotate(f'{density:.1f}', (i, density), textcoords="offset points", 
                       xytext=(0,10), ha='center', fontsize=8, color='#e2e8f0',
                       bbox=dict(boxstyle="round,pad=0.2", facecolor='#1e293b', alpha=0.8))
        
        ax.grid(True, alpha=0.2, color='#475569')
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico de comparación de densidades: {str(e)}")
        return False

def create_alternative_chart(data, output_path):
    """Gráfico alternativo si falla alguno"""
    try:
        names = [body['name'] for body in data['celestial_bodies']]
        masses = [body['mass_kg'] for body in data['celestial_bodies']]
        colors = [body['color'] for body in data['celestial_bodies']]
        
        fig, ax = plt.subplots(figsize=(12, 6))
        fig.patch.set_facecolor('#0f172a')
        ax.set_facecolor('#1e293b')
        
        bars = ax.bar(names, masses, color=colors, alpha=0.8)
        ax.set_yscale('log')
        
        ax.set_title('Masa de Cuerpos Celestes (Escala Logarítmica)', fontsize=14, fontweight='bold', color='#f8fafc', pad=20)
        ax.set_xlabel('Cuerpos Celestes', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.set_ylabel('Masa (kg)', fontsize=11, fontweight='bold', color='#e2e8f0')
        ax.tick_params(axis='x', rotation=45, colors='#94a3b8')
        ax.tick_params(axis='y', colors='#94a3b8')
        ax.grid(True, alpha=0.2, color='#475569', axis='y')
        
        plt.tight_layout()
        plt.savefig(output_path, dpi=150, facecolor='#0f172a', edgecolor='none', bbox_inches='tight')
        plt.close()
        return True
        
    except Exception as e:
        print(f"Error en gráfico alternativo: {str(e)}")
        return False

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Uso: python charts.py <tipo_grafico> [archivo_json]")
        print("Tipos: all, mass, temperature, size, orbital, types, moons, density")
        sys.exit(1)
    
    chart_type = sys.argv[1]
    json_file = sys.argv[2] if len(sys.argv) > 2 else "../data/celestial_bodies.json"
    
    try:
        with open(json_file, 'r', encoding='utf-8') as f:
            data = json.load(f)
    except Exception as e:
        print(f"Error cargando JSON: {str(e)}")
        sys.exit(1)
    
    output_dir = "../generated_charts"
    os.makedirs(output_dir, exist_ok=True)
    
    generated_files = []
    success_count = 0
    
    try:
        if chart_type in ["all", "mass"]:
            output_path = os.path.join(output_dir, "mass_composition.png")
            if create_mass_composition_chart(data, output_path):
                generated_files.append(output_path)
                success_count += 1
                print("✓ Gráfico de composición de masas generado")
        
        if chart_type in ["all", "temperature"]:
            output_path = os.path.join(output_dir, "temperature_area.png")
            if create_temperature_area_chart(data, output_path):
                generated_files.append(output_path)
                success_count += 1
                print("✓ Gráfico de área de temperaturas generado")
        
        if chart_type in ["all", "size"]:
            output_path = os.path.join(output_dir, "size_comparison.png")
            if create_size_comparison_chart(data, output_path):
                generated_files.append(output_path)
                success_count += 1
                print("✓ Gráfico de comparación de tamaños generado")
        
        if chart_type in ["all", "orbital"]:
            output_path = os.path.join(output_dir, "orbital_periods.png")
            if create_orbital_periods_chart(data, output_path):
                generated_files.append(output_path)
                success_count += 1
                print("✓ Gráfico de períodos orbitales generado")
        
        if chart_type in ["all", "types"]:
            output_path = os.path.join(output_dir, "planet_types.png")
            if create_planet_types_chart(data, output_path):
                generated_files.append(output_path)
                success_count += 1
                print("✓ Gráfico de tipos de planetas generado")
        
        if chart_type in ["all", "moons"]:
            output_path = os.path.join(output_dir, "moon_distribution.png")
            if create_moon_distribution_chart(data, output_path):
                generated_files.append(output_path)
                success_count += 1
                print("✓ Gráfico de distribución de lunas generado")
        
        if chart_type in ["all", "density"]:
            output_path = os.path.join(output_dir, "density_comparison.png")
            if create_density_comparison_chart(data, output_path):
                generated_files.append(output_path)
                success_count += 1
                print("✓ Gráfico de comparación de densidades generado")
        
        print(f"🎉 Gráficos generados exitosamente: {success_count}/7")
        
    except Exception as e:
        print(f"❌ Error durante la generación: {str(e)}")
        sys.exit(1)