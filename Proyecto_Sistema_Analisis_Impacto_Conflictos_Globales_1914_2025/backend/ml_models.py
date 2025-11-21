import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestRegressor
from sklearn.linear_model import LinearRegression
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import mean_squared_error, r2_score
import warnings
warnings.filterwarnings('ignore')

class MLModels:
    def __init__(self, dataframe):
        self.df = dataframe
        self.scaler = StandardScaler()
    
    def predict_economic_impact(self, features, target):
        """Predecir impacto económico usando Random Forest"""
        try:
            # Preparar datos
            X = self.df[features]
            y = self.df[target]
            
            # Eliminar filas con valores NaN
            mask = ~(X.isna().any(axis=1) | y.isna())
            X_clean = X[mask]
            y_clean = y[mask]
            
            if len(X_clean) < 10:
                return {'error': 'Datos insuficientes para el modelo'}
            
            # Entrenar modelo
            model = RandomForestRegressor(n_estimators=100, random_state=42)
            model.fit(X_clean, y_clean)
            
            # Métricas
            predictions = model.predict(X_clean)
            mse = float(mean_squared_error(y_clean, predictions))
            r2 = float(r2_score(y_clean, predictions))
            
            # Importancia de características (convertir a tipos nativos)
            feature_importance = {feature: float(importance) for feature, importance in zip(features, model.feature_importances_)}
            
            return {
                'mse': mse,
                'r2_score': r2,
                'feature_importance': feature_importance,
                'predictions': [float(p) for p in predictions],
                'actual_values': [float(v) for v in y_clean]
            }
            
        except Exception as e:
            return {'error': str(e)}
    
    def cluster_conflicts(self, n_clusters=4):
        """Agrupar conflictos usando K-means clustering"""
        try:
            # Seleccionar características para clustering
            cluster_features = ['gdp_change', 'inflation', 'unemployment', 
                              'military_spending', 'civilian_casualties', 'duration']
            
            # Preparar datos
            conflict_stats = self.df.groupby('conflict_id').agg({
                'gdp_change': 'mean',
                'inflation': 'mean',
                'unemployment': 'mean',
                'military_spending': 'mean',
                'civilian_casualties': 'sum',
                'duration': 'first',
                'conflict_name': 'first'
            }).reset_index()
            
            X = conflict_stats[cluster_features]
            X_scaled = self.scaler.fit_transform(X)
            
            # Aplicar K-means
            kmeans = KMeans(n_clusters=n_clusters, random_state=42)
            clusters = kmeans.fit_predict(X_scaled)
            
            # Resultados (convertir a tipos nativos)
            conflict_stats['cluster'] = clusters
            cluster_centers = [list(map(float, center)) for center in kmeans.cluster_centers_]
            
            return {
                'clusters': conflict_stats.astype({
                    'gdp_change': float,
                    'inflation': float,
                    'unemployment': float,
                    'military_spending': float,
                    'civilian_casualties': int,
                    'duration': int,
                    'cluster': int
                }).to_dict('records'),
                'cluster_centers': cluster_centers,
                'cluster_features': cluster_features
            }
            
        except Exception as e:
            return {'error': str(e)}
    
    def trend_analysis(self):
        """Análisis de tendencias temporales"""
        try:
            # Agregar datos por año
            yearly_agg = self.df.groupby('year').agg({
                'gdp_change': 'mean',
                'inflation': 'mean',
                'unemployment': 'mean',
                'military_spending': 'mean',
                'civilian_casualties': 'sum',
                'conflict_id': 'nunique'
            }).reset_index()
            
            # Modelo de tendencia para GDP
            X = yearly_agg[['year']]
            y_gdp = yearly_agg['gdp_change']
            
            model_gdp = LinearRegression()
            model_gdp.fit(X, y_gdp)
            gdp_trend = model_gdp.predict(X)
            
            # Modelo de tendencia para inflación
            y_inflation = yearly_agg['inflation']
            model_inflation = LinearRegression()
            model_inflation.fit(X, y_inflation)
            inflation_trend = model_inflation.predict(X)
            
            return {
                'yearly_data': yearly_agg.astype({
                    'year': int,
                    'gdp_change': float,
                    'inflation': float,
                    'unemployment': float,
                    'military_spending': float,
                    'civilian_casualties': int,
                    'conflict_id': int
                }).to_dict('records'),
                'gdp_trend': [float(t) for t in gdp_trend],
                'inflation_trend': [float(t) for t in inflation_trend],
                'gdp_slope': float(model_gdp.coef_[0]),
                'inflation_slope': float(model_inflation.coef_[0])
            }
            
        except Exception as e:
            return {'error': str(e)}