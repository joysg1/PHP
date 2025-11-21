import json
import pandas as pd
import numpy as np
from datetime import datetime
import os

class DataProcessor:
    def __init__(self, data_file):
        self.data_file = data_file
        self.data = None
        self.df = None
        
    def load_data(self):
        """Cargar datos desde el archivo JSON"""
        try:
            with open(self.data_file, 'r', encoding='utf-8') as f:
                self.data = json.load(f)
            self.df = self._create_dataframe()
            print(f"✓ Datos cargados: {len(self.df)} registros, {self.df['conflict_id'].nunique()} conflictos")
            return True
        except Exception as e:
            print(f"✗ Error cargando datos: {e}")
            return False
    
    def _create_dataframe(self):
        """Crear DataFrame de pandas desde los datos JSON"""
        records = []
        for conflict in self.data.get('conflicts', []):
            for year_data in conflict.get('yearly_data', []):
                record = {
                    'conflict_id': conflict['id'],
                    'conflict_name': conflict['name'],
                    'year': year_data['year'],
                    'duration': conflict['duration'],
                    'countries_involved': len(conflict['countries_involved']),
                    'gdp_change': year_data.get('gdp_change', 0),
                    'inflation': year_data.get('inflation', 0),
                    'unemployment': year_data.get('unemployment', 0),
                    'military_spending': year_data.get('military_spending', 0),
                    'civilian_casualties': year_data.get('civilian_casualties', 0),
                    'economic_sector': conflict.get('economic_sector_impact', 'Mixed'),
                    'region': conflict.get('region', 'Global')
                }
                records.append(record)
        
        return pd.DataFrame(records)
    
    def get_conflict_stats(self):
        """Obtener estadísticas generales de los conflictos"""
        if self.df is None:
            return {}
        
        # Convertir numpy types a Python nativos para JSON
        stats = {
            'total_conflicts': int(self.df['conflict_id'].nunique()),
            'years_covered': f"{int(self.df['year'].min())}-{int(self.df['year'].max())}",
            'avg_gdp_change': float(self.df['gdp_change'].mean()),
            'avg_inflation': float(self.df['inflation'].mean()),
            'max_gdp_drop': float(self.df['gdp_change'].min()),
            'max_inflation': float(self.df['inflation'].max()),
            'total_civilian_casualties': int(self.df['civilian_casualties'].sum())
        }
        
        return stats
    
    def get_conflict_list(self):
        """Obtener lista de conflictos"""
        if self.data is None:
            return []
        
        conflicts = []
        for conflict in self.data.get('conflicts', []):
            conflicts.append({
                'id': conflict['id'],
                'name': conflict['name'],
                'years': conflict['years'],
                'region': conflict.get('region', 'Global'),
                'description': conflict.get('description', '')
            })
        
        return conflicts
    
    def get_yearly_data(self, conflict_id=None):
        """Obtener datos anuales, filtrados por conflicto si se especifica"""
        if self.df is None:
            return []
        
        if conflict_id:
            filtered_df = self.df[self.df['conflict_id'] == conflict_id]
        else:
            filtered_df = self.df
        
        # Convertir a tipos nativos de Python
        return filtered_df.astype({
            'year': int,
            'gdp_change': float,
            'inflation': float,
            'unemployment': float,
            'military_spending': float,
            'civilian_casualties': int
        }).to_dict('records')
    
    def get_region_data(self):
        """Obtener datos agregados por región"""
        if self.df is None:
            return []
        
        region_stats = self.df.groupby('region').agg({
            'gdp_change': 'mean',
            'inflation': 'mean',
            'unemployment': 'mean',
            'military_spending': 'mean',
            'civilian_casualties': 'sum',
            'conflict_id': 'nunique'
        }).reset_index()
        
        # Convertir a tipos nativos de Python
        return region_stats.astype({
            'gdp_change': float,
            'inflation': float,
            'unemployment': float,
            'military_spending': float,
            'civilian_casualties': int,
            'conflict_id': int
        }).to_dict('records')