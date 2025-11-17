// assets/js/app.js - Funcionalidades JavaScript adicionales

class ContinentesApp {
    constructor() {
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.initCharts();
        this.addAccessibilityFeatures();
    }
    
    bindEvents() {
        // Mejorar la experiencia de los botones de operación
        document.querySelectorAll('.operation-card').forEach(card => {
            card.addEventListener('click', (e) => {
                this.selectOperation(card);
            });
            
            card.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    this.selectOperation(card);
                }
            });
        });
        
        // Efectos de hover para tarjetas de continentes
        document.addEventListener('mouseover', (e) => {
            if (e.target.closest('.continente-card')) {
                this.animateCard(e.target.closest('.continente-card'));
            }
        });
        
        // Cargar datos al iniciar si es necesario
        this.loadInitialData();
    }
    
    selectOperation(card) {
        const operation = card.querySelector('h4').textContent.toLowerCase();
        const operationMap = {
            'vista general': 'mostrar_todos',
            'detalles': 'info_detallada',
            'estadísticas': 'estadisticas_globales',
            'buscar país': 'buscar_pais',
            'comparar': 'comparar_continentes',
            'gráficos': 'generar_graficos'
        };
        
        const operationValue = operationMap[operation];
        if (operationValue) {
            document.getElementById('operacion').value = operationValue;
            this.toggleContinenteSelect();
            
            // Efecto visual de selección
            document.querySelectorAll('.operation-card').forEach(c => {
                c.style.transform = 'scale(1)';
                c.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
            });
            
            card.style.transform = 'scale(1.05)';
            card.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.3)';
        }
    }
    
    toggleContinenteSelect() {
        const operacion = document.getElementById('operacion').value;
        const continenteGroup = document.getElementById('continente-group');
        const paisGroup = document.getElementById('pais-group');
        
        if (operacion === 'info_detallada') {
            continenteGroup.style.display = 'block';
            paisGroup.style.display = 'none';
            this.animateElement(continenteGroup);
        } else if (operacion === 'buscar_pais') {
            continenteGroup.style.display = 'none';
            paisGroup.style.display = 'block';
            this.animateElement(paisGroup);
        } else {
            continenteGroup.style.display = 'none';
            paisGroup.style.display = 'none';
        }
    }
    
    animateElement(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.3s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 50);
    }
    
    animateCard(card) {
        card.style.transition = 'all 0.3s ease';
    }
    
    initCharts() {
        // Placeholder para futuras integraciones de gráficos en tiempo real
        console.log('Sistema de gráficos listo para integración');
    }
    
    addAccessibilityFeatures() {
        // Mejorar accesibilidad del formulario
        const form = document.querySelector('form');
        if (form) {
            form.setAttribute('aria-label', 'Formulario de operaciones geográficas');
        }
        
        // Añadir labels ARIA a los elementos interactivos
        document.querySelectorAll('.operation-card').forEach((card, index) => {
            card.setAttribute('role', 'button');
            card.setAttribute('tabindex', '0');
            card.setAttribute('aria-label', `Seleccionar operación: ${card.querySelector('h4').textContent}`);
        });
        
        // Mejorar navegación por teclado
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.resetSelections();
            }
        });
    }
    
    resetSelections() {
        document.querySelectorAll('.operation-card').forEach(card => {
            card.style.transform = 'scale(1)';
            card.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        });
    }
    
    loadInitialData() {
        // Podría cargar datos iniciales vía AJAX en el futuro
        console.log('Sistema de continentes cargado correctamente');
    }
    
    // Método para mostrar notificaciones
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()">×</button>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'error' ? '#e74c3c' : type === 'success' ? '#27ae60' : '#3498db'};
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
}

// Estilos para notificaciones
const notificationStyles = `
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.notification {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-width: 300px;
}

.notification button {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    margin-left: 10px;
}
`;

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Añadir estilos de notificaciones
    const styleSheet = document.createElement('style');
    styleSheet.textContent = notificationStyles;
    document.head.appendChild(styleSheet);
    
    // Inicializar app
    window.continentesApp = new ContinentesApp();
    
    // Mejorar el formulario existente
    enhanceExistingForm();
});

function enhanceExistingForm() {
    // Añadir funcionalidades adicionales al formulario existente
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', (e) => {
            // Validación adicional antes del envío
            const operacion = document.getElementById('operacion').value;
            if (!operacion) {
                e.preventDefault();
                if (window.continentesApp) {
                    window.continentesApp.showNotification('Por favor, selecciona una operación', 'error');
                }
                return;
            }
            
            // Mostrar estado de carga
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            button.textContent = 'Procesando...';
            button.classList.add('loading');
            
            // Restaurar después de un tiempo (en caso de que falle el envío)
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('loading');
            }, 5000);
        });
    }
}

// Utilidades adicionales
const ContinentesUtils = {
    formatNumber: (num) => {
        return new Intl.NumberFormat('es-ES').format(num);
    },
    
    formatArea: (area) => {
        return `${ContinentesUtils.formatNumber(area)} km²`;
    },
    
    formatPopulation: (population) => {
        if (population >= 1000000000) {
            return `${(population / 1000000000).toFixed(2)}B`;
        } else if (population >= 1000000) {
            return `${(population / 1000000).toFixed(2)}M`;
        }
        return ContinentesUtils.formatNumber(population);
    },
    
    calculateDensity: (population, area) => {
        return area > 0 ? (population / area).toFixed(2) : '0';
    }
};

// Hacer las utilidades disponibles globalmente
window.ContinentesUtils = ContinentesUtils;
