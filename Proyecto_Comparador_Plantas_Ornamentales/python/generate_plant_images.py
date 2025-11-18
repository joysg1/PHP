import matplotlib.pyplot as plt
import matplotlib.patches as patches
from matplotlib.patches import FancyBboxPatch, Circle, Wedge
import numpy as np
import json
import os
import sys
from PIL import Image, ImageDraw, ImageFont
import random

def generate_plant_image(plant_data, output_dir):
    """Genera una imagen representativa para una planta basada en sus características"""
    
    # Crear figura con fondo blanco
    fig, ax = plt.subplots(figsize=(6, 8), dpi=100)
    fig.patch.set_facecolor('white')
    ax.set_facecolor('#f8fafc')
    
    # Obtener características de la planta
    name = plant_data['name']
    characteristics = plant_data['characteristics']
    
    # Colores basados en el tipo de planta
    plant_colors = {
        'Rosa': ['#ff6b6b', '#ff8e8e', '#ff5252'],
        'Lavanda': ['#9c27b0', '#ba68c8', '#7b1fa2'],
        'Orquídea': ['#e91e63', '#f06292', '#ad1457'],
        'Cactus': ['#4caf50', '#66bb6a', '#388e3c'],
        'Hiedra': ['#2e7d32', '#43a047', '#1b5e20'],
        'Bambú': ['#558b2f', '#7cb342', '#33691e']
    }
    
    colors = plant_colors.get(name, ['#4caf50', '#66bb6a', '#388e3c'])
    
    # Dibujar maceta
    pot_color = '#8d6e63'
    pot = FancyBboxPatch((2.5, 0.5), 1, 0.8, 
                        boxstyle="round,pad=0.1", 
                        facecolor=pot_color, 
                        edgecolor='#5d4037', 
                        linewidth=2)
    ax.add_patch(pot)
    
    # Dibujar tallo principal
    stem_height = 3 + (characteristics['resistencia'] / 10) * 2
    stem = patches.Rectangle((3, 1.3), 0.2, stem_height, 
                           facecolor='#795548', 
                           edgecolor='#5d4037',
                           linewidth=1)
    ax.add_patch(stem)
    
    # Dibujar hojas basadas en resistencia y duración
    num_leaves = int(characteristics['resistencia'] / 2) + 2
    for i in range(num_leaves):
        leaf_y = 1.5 + (i * stem_height / num_leaves)
        leaf_size = 0.3 + (characteristics['duracion'] / 20)
        
        # Alternar lados del tallo
        side = 1 if i % 2 == 0 else -1
        leaf_x = 3.1 + (side * 0.3)
        
        leaf = patches.Ellipse((leaf_x, leaf_y), 
                             leaf_size, leaf_size * 0.6,
                             angle=45 * side,
                             facecolor=colors[1],
                             edgecolor=colors[2],
                             linewidth=1,
                             alpha=0.8)
        ax.add_patch(leaf)
    
    # Dibujar flores basadas en floración
    if characteristics['floracion'] > 5:
        num_flowers = int(characteristics['floracion'] / 3)
        for i in range(num_flowers):
            flower_y = 3 + (i * 0.8)
            flower_x = 3 + random.uniform(-0.5, 0.5)
            
            # Pétalos
            for angle in range(0, 360, 60):
                rad = np.radians(angle)
                petal_x = flower_x + 0.4 * np.cos(rad)
                petal_y = flower_y + 0.4 * np.sin(rad)
                
                petal = Circle((petal_x, petal_y), 0.3,
                             facecolor=colors[0],
                             edgecolor=colors[2],
                             linewidth=1,
                             alpha=0.7)
                ax.add_patch(petal)
            
            # Centro de la flor
            center = Circle((flower_x, flower_y), 0.15,
                          facecolor='#ffeb3b',
                          edgecolor='#fbc02d',
                          linewidth=1)
            ax.add_patch(center)
    
    # Configurar los límites y aspecto del gráfico
    ax.set_xlim(0, 6)
    ax.set_ylim(0, 8)
    ax.set_aspect('equal')
    ax.axis('off')
    
    # Añadir título con el nombre de la planta
    plt.text(3, 7.5, name, ha='center', va='center', 
             fontsize=16, fontweight='bold', color=colors[2])
    
    # Añadir características como texto
    char_text = f"Resistencia: {characteristics['resistencia']}/10\n"
    char_text += f"Floración: {characteristics['floracion']}/10\n"
    char_text += f"Duración: {characteristics['duracion']}/10"
    
    plt.text(3, 0.2, char_text, ha='center', va='center',
             fontsize=8, color='#666666',
             bbox=dict(boxstyle="round,pad=0.3", facecolor='white', alpha=0.8))
    
    # Ajustar layout y guardar
    plt.tight_layout()
    
    # Crear directorio si no existe
    os.makedirs(output_dir, exist_ok=True)
    
    # Guardar imagen
    output_path = os.path.join(output_dir, f"plant_{plant_data['id']}.png")
    plt.savefig(output_path, dpi=100, bbox_inches='tight', 
                facecolor='white', edgecolor='none')
    plt.close()
    
    print(f"Imagen generada: {output_path}")
    return output_path

def generate_simple_plant_image(plant_data, output_dir):
    """Versión más simple usando PIL para mayor compatibilidad"""
    
    # Crear imagen
    img = Image.new('RGB', (400, 500), color='#f8fafc')
    draw = ImageDraw.Draw(img)
    
    # Colores
    name = plant_data['name']
    colors = {
        'Rosa': {'primary': '#ff6b6b', 'secondary': '#ff8e8e'},
        'Lavanda': {'primary': '#9c27b0', 'secondary': '#ba68c8'},
        'Orquídea': {'primary': '#e91e63', 'secondary': '#f06292'},
        'Cactus': {'primary': '#4caf50', 'secondary': '#66bb6a'},
        'Hiedra': {'primary': '#2e7d32', 'secondary': '#43a047'},
        'Bambú': {'primary': '#558b2f', 'secondary': '#7cb342'}
    }
    
    color_set = colors.get(name, {'primary': '#4caf50', 'secondary': '#66bb6a'})
    
    # Dibujar maceta
    draw.rectangle([150, 400, 250, 450], fill='#8d6e63', outline='#5d4037', width=2)
    
    # Dibujar tallo
    stem_height = 200 + (plant_data['characteristics']['resistencia'] * 10)
    draw.rectangle([195, 250, 205, 400], fill='#795548', outline='#5d4037', width=1)
    
    # Dibujar hojas
    num_leaves = max(2, plant_data['characteristics']['resistencia'] // 2)
    for i in range(num_leaves):
        y_pos = 300 + (i * 30)
        size = 20 + (plant_data['characteristics']['duracion'] * 2)
        
        # Alternar lados
        if i % 2 == 0:
            draw.ellipse([205, y_pos, 205 + size, y_pos + size//2], 
                        fill=color_set['secondary'], outline=color_set['primary'], width=1)
        else:
            draw.ellipse([195 - size, y_pos, 195, y_pos + size//2], 
                        fill=color_set['secondary'], outline=color_set['primary'], width=1)
    
    # Dibujar flores si tiene buena floración
    if plant_data['characteristics']['floracion'] > 5:
        num_flowers = min(3, plant_data['characteristics']['floracion'] // 3)
        for i in range(num_flowers):
            y_pos = 350 - (i * 40)
            x_pos = 200 + (i * 20 - 20)
            
            # Pétalos
            for angle in [0, 90, 180, 270]:
                if angle == 0:
                    draw.ellipse([x_pos + 15, y_pos, x_pos + 35, y_pos + 20], 
                                fill=color_set['primary'])
                elif angle == 90:
                    draw.ellipse([x_pos, y_pos - 15, x_pos + 20, y_pos + 5], 
                                fill=color_set['primary'])
                elif angle == 180:
                    draw.ellipse([x_pos - 15, y_pos, x_pos + 5, y_pos + 20], 
                                fill=color_set['primary'])
                elif angle == 270:
                    draw.ellipse([x_pos, y_pos + 15, x_pos + 20, y_pos + 35], 
                                fill=color_set['primary'])
            
            # Centro
            draw.ellipse([x_pos + 5, y_pos + 5, x_pos + 15, y_pos + 15], 
                        fill='#ffeb3b')
    
    # Añadir texto del nombre
    try:
        font_large = ImageFont.truetype("arial.ttf", 24)
        font_small = ImageFont.truetype("arial.ttf", 12)
    except:
        font_large = ImageFont.load_default()
        font_small = ImageFont.load_default()
    
    # Nombre de la planta
    bbox = draw.textbbox((0, 0), name, font=font_large)
    text_width = bbox[2] - bbox[0]
    draw.text((200 - text_width//2, 50), name, fill=color_set['primary'], font=font_large)
    
    # Nombre científico
    sci_name = plant_data['scientific']
    bbox = draw.textbbox((0, 0), sci_name, font=font_small)
    text_width = bbox[2] - bbox[0]
    draw.text((200 - text_width//2, 80), sci_name, fill='#666666', font=font_small)
    
    # Características
    chars = plant_data['characteristics']
    char_text = f"R: {chars['resistencia']}/10  F: {chars['floracion']}/10  D: {chars['duracion']}/10"
    bbox = draw.textbbox((0, 0), char_text, font=font_small)
    text_width = bbox[2] - bbox[0]
    draw.text((200 - text_width//2, 450), char_text, fill='#666666', font=font_small)
    
    # Crear directorio si no existe
    os.makedirs(output_dir, exist_ok=True)
    
    # Guardar imagen
    output_path = os.path.join(output_dir, f"plant_{plant_data['id']}.png")
    img.save(output_path, 'PNG')
    
    print(f"Imagen simple generada: {output_path}")
    return output_path

def generate_all_plants_images(plants_file, output_dir):
    """Genera imágenes para todas las plantas en el JSON"""
    
    # Cargar datos de plantas
    with open(plants_file, 'r', encoding='utf-8') as f:
        plants_data = json.load(f)
    
    generated_images = {}
    
    for plant in plants_data:
        try:
            # Intentar con matplotlib primero
            image_path = generate_plant_image(plant, output_dir)
        except Exception as e:
            print(f"Error con matplotlib para {plant['name']}: {e}")
            print("Usando método simple...")
            # Fallback a método simple
            image_path = generate_simple_plant_image(plant, output_dir)
        
        generated_images[plant['id']] = image_path
    
    return generated_images

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Uso: python generate_plant_images.py <plants_json> <output_dir>")
        sys.exit(1)
    
    plants_file = sys.argv[1]
    output_dir = sys.argv[2]
    
    if not os.path.exists(plants_file):
        print(f"Error: Archivo {plants_file} no encontrado")
        sys.exit(1)
    
    print("Generando imágenes de plantas...")
    images = generate_all_plants_images(plants_file, output_dir)
    print(f"Imágenes generadas: {len(images)}")
