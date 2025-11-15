import sys
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import os

def generar_grafico_poblacion(tipo_grafico, archivo_datos, archivo_salida):
    """
    Genera gráficos de población mundial usando Seaborn
    """
    try:
        # Leer datos
        datos = pd.read_csv("/home/userlm/Documentos/Proyecto_Poblacion/datos/poblacion_mundial.csv")
        
        # Configurar estilo
        sns.set_style("whitegrid")
        plt.figure(figsize=(12, 8))
        
        if tipo_grafico == "barras":
            # Gráfico de barras
            grafico = sns.barplot(
                data=datos, 
                x='pais', 
                y='poblacion_2023',
                hue='continente',
                palette='viridis'
            )
            plt.title('Población Mundial 2023 - Top 10 Países', fontsize=16, fontweight='bold')
            plt.xlabel('País', fontsize=12)
            plt.ylabel('Población', fontsize=12)
            plt.xticks(rotation=45, ha='right')
            
        elif tipo_grafico == "torta":
            # Gráfico de torta
            plt.pie(
                datos['poblacion_2023'], 
                labels=datos['pais'],
                autopct='%1.1f%%',
                startangle=90
            )
            plt.title('Distribución de Población Mundial 2023', fontsize=16, fontweight='bold')
            
        elif tipo_grafico == "continentes":
            # Agrupar por continente
            datos_continente = datos.groupby('continente')['poblacion_2023'].sum().reset_index()
            grafico = sns.barplot(
                data=datos_continente,
                x='continente',
                y='poblacion_2023',
                palette='Set2'
            )
            plt.title('Población por Continente - Top 10 Países', fontsize=16, fontweight='bold')
            plt.xlabel('Continente', fontsize=12)
            plt.ylabel('Población Total', fontsize=12)
            
        # Guardar gráfico
        plt.tight_layout()
        plt.savefig(archivo_salida, dpi=300, bbox_inches='tight')
        plt.close()
        
        return True
        
    except Exception as e:
        print(f"Error al generar gráfico: {str(e)}")
        return False

if __name__ == "__main__":
    if len(sys.argv) == 4:
        tipo = sys.argv[1]
        datos = sys.argv[2]
        salida = sys.argv[3]
        generar_grafico_poblacion(tipo, datos, salida)
