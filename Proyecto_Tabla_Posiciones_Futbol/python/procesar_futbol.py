import sys
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import os

def generar_tabla_grafico(tipo_visualizacion, archivo_datos, archivo_salida):
    """
    Genera visualizaciones de la tabla de posiciones
    """
    try:
        # Cargar datos
        datos = pd.read_csv(archivo_datos)
        
        # Calcular diferencia de goles
        datos['diferencia_goles'] = datos['goles_favor'] - datos['goles_contra']
        
        # Ordenar por puntos (tabla de posiciones)
        datos = datos.sort_values('puntos', ascending=False).reset_index(drop=True)
        datos['posicion'] = range(1, len(datos) + 1)
        
        # Configurar estilo
        sns.set_style("whitegrid")
        plt.figure(figsize=(12, 8))
        
        if tipo_visualizacion == "tabla_barras":
            # Gráfico de barras - Puntos por equipo
            plt.figure(figsize=(12, 8))
            
            # ✅ CORREGIDO: Lógica de colores por posición
            def obtener_color(posicion):
                if posicion <= 4:    # Champions League
                    return '#2ecc71'  # Verde
                elif posicion <= 6:  # Europa League  
                    return '#f39c12'  # Naranja
                elif posicion <= 7:  # Conference League
                    return '#3498db'  # Azul
                elif posicion >= 18: # Descenso
                    return '#e74c3c'  # Rojo
                else:                # Medio de tabla
                    return '#95a5a6'  # Gris
            
            # Crear lista de colores según posición real
            colores = [obtener_color(i+1) for i in range(len(datos))]
            
            grafico = sns.barplot(
                data=datos,
                x='puntos',
                y='equipo',
                palette=colores,
                orient='h'
            )
            
            plt.title('Premier League - Puntos por Equipo', fontsize=16, fontweight='bold')
            plt.xlabel('Puntos', fontsize=12)
            plt.ylabel('Equipo', fontsize=12)
            
            # Agregar valores en las barras
            for i, v in enumerate(datos['puntos']):
                plt.text(v + 0.5, i, str(v), va='center', fontweight='bold')
            
            # ✅ Agregar leyenda de colores
            from matplotlib.patches import Patch
            legend_elements = [
                Patch(facecolor='#2ecc71', label='Champions League (Top 4)'),
                Patch(facecolor='#f39c12', label='Europa League (5-6)'),
                Patch(facecolor='#3498db', label='Conference League (7)'),
                Patch(facecolor='#e74c3c', label='Descenso (18-20)'),
                Patch(facecolor='#95a5a6', label='Medio de tabla')
            ]
            plt.legend(handles=legend_elements, loc='lower right')
            
        elif tipo_visualizacion == "goles":
            # Gráfico de goles a favor y en contra
            plt.figure(figsize=(12, 8))
            
            x = range(len(datos))
            width = 0.35
            
            plt.bar(x, datos['goles_favor'], width, label='Goles a Favor', color='green', alpha=0.7)
            plt.bar([i + width for i in x], datos['goles_contra'], width, label='Goles en Contra', color='red', alpha=0.7)
            
            plt.xlabel('Equipos')
            plt.ylabel('Goles')
            plt.title('Goles a Favor vs Goles en Contra - Premier League')
            plt.xticks([i + width/2 for i in x], datos['equipo'], rotation=45, ha='right')
            plt.legend()
            plt.tight_layout()
            
        elif tipo_visualizacion == "efectividad":
            # Gráfico de efectividad (partidos ganados vs jugados)
            plt.figure(figsize=(12, 8))
            
            datos['efectividad'] = (datos['partidos_ganados'] / datos['partidos_jugados']) * 100
            
            # ✅ Aplicar mismos colores por posición
            colores = [obtener_color(i+1) for i in range(len(datos))]
            
            grafico = sns.barplot(
                data=datos,
                x='efectividad',
                y='equipo',
                palette=colores,
                orient='h'
            )
            
            plt.title('Efectividad - Porcentaje de Partidos Ganados', fontsize=16, fontweight='bold')
            plt.xlabel('Efectividad (%)', fontsize=12)
            plt.ylabel('Equipo', fontsize=12)
            
            # Agregar valores
            for i, v in enumerate(datos['efectividad']):
                plt.text(v + 0.5, i, f'{v:.1f}%', va='center')
                
            # ✅ Leyenda
            plt.legend(handles=legend_elements, loc='lower right')
        
        # Guardar gráfico
        plt.tight_layout()
        plt.savefig(archivo_salida, dpi=300, bbox_inches='tight')
        plt.close()
        
        return datos.to_dict('records')
        
    except Exception as e:
        print(f"Error al generar visualización: {str(e)}")
        return None

if __name__ == "__main__":
    if len(sys.argv) == 4:
        tipo = sys.argv[1]
        datos = sys.argv[2]
        salida = sys.argv[3]
        
        resultado = generar_tabla_grafico(tipo, datos, salida)
        if resultado:
            print("Visualización generada exitosamente")