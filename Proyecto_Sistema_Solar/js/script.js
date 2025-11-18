// Estado de la aplicación
const AppState = {
    currentTab: 'bodies',
    currentChartIndex: 0,
    charts: [],
    modalOpen: false,
    carouselPosition: 0,
    interactiveCharts: {
        massChart: null,
        temperatureChart: null
    }
};

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando aplicación...');
    initializeTabs();
    initializeCarousel();
    initializeModal();
    createStarfield();
    loadPreloadedCharts();
    
    // Remover el botón de generar gráficos si existe
    removeGenerateButton();
    
    // Inicializar gráficos interactivos si es necesario
    if (document.getElementById('interactive-tab')?.classList.contains('active')) {
        setTimeout(() => {
            initializeInteractiveCharts();
        }, 1000);
    }
});

// Remover el botón de generar gráficos
function removeGenerateButton() {
    const chartControls = document.querySelector('.chart-controls');
    if (chartControls && chartControls.querySelector('#generateChartsBtn')) {
        chartControls.remove();
    }
}

// Sistema de pestañas - VERSIÓN MEJORADA
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.dataset.tab;
            
            // Actualizar botones activos
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Actualizar contenidos activos
            tabContents.forEach(content => content.classList.remove('active'));
            document.getElementById(`${tabId}-tab`).classList.add('active');
            
            AppState.currentTab = tabId;
            
            // Si es la pestaña de gráficos, inicializar carrusel
            if (tabId === 'charts') {
                setTimeout(initializeCarousel, 100);
            }
            
            // Si es la pestaña interactiva, inicializar gráficos
            if (tabId === 'interactive') {
                setTimeout(() => {
                    console.log('Cambiando a pestaña interactiva - inicializando gráficos...');
                    initializeInteractiveCharts();
                }, 500);
            }
        });
    });
    
    console.log('Sistema de pestañas inicializado');
}

// Carrusel de gráficos
function initializeCarousel() {
    const carousel = document.querySelector('.carousel');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    
    if (!carousel) return;
    
    // Event listeners para navegación
    prevBtn?.addEventListener('click', () => navigateCarousel(-1));
    nextBtn?.addEventListener('click', () => navigateCarousel(1));
    
    // Event listeners para items del carrusel
    const carouselItems = document.querySelectorAll('.carousel-item');
    carouselItems.forEach((item, index) => {
        item.addEventListener('click', () => openChartModal(index));
    });
    
    updateCarouselNavigation();
}

function navigateCarousel(direction) {
    const carousel = document.querySelector('.carousel');
    const itemWidth = 320 + 25; // ancho del item + gap
    const visibleItems = Math.floor(carousel.offsetWidth / itemWidth);
    const maxPosition = Math.max(0, AppState.charts.length - visibleItems);
    
    AppState.carouselPosition += direction * visibleItems;
    AppState.carouselPosition = Math.max(0, Math.min(AppState.carouselPosition, maxPosition));
    
    carousel.scrollTo({
        left: AppState.carouselPosition * itemWidth,
        behavior: 'smooth'
    });
    
    updateCarouselNavigation();
}

function updateCarouselNavigation() {
    const carousel = document.querySelector('.carousel');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    
    if (!carousel) return;
    
    const itemWidth = 320 + 25;
    const visibleItems = Math.floor(carousel.offsetWidth / itemWidth);
    const maxPosition = Math.max(0, AppState.charts.length - visibleItems);
    
    if (prevBtn) prevBtn.disabled = AppState.carouselPosition === 0;
    if (nextBtn) nextBtn.disabled = AppState.carouselPosition >= maxPosition;
}

// Modal de gráficos
function initializeModal() {
    const modal = document.getElementById('chartModal');
    const closeBtn = document.querySelector('.modal-close');
    const prevBtn = document.querySelector('.prev-chart');
    const nextBtn = document.querySelector('.next-chart');
    
    if (!modal || !closeBtn) return;
    
    closeBtn.addEventListener('click', closeChartModal);
    
    if (prevBtn) prevBtn.addEventListener('click', () => navigateCharts(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => navigateCharts(1));
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeChartModal();
        }
    });
    
    // Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && AppState.modalOpen) {
            closeChartModal();
        }
        if (e.key === 'ArrowLeft' && AppState.modalOpen) {
            navigateCharts(-1);
        }
        if (e.key === 'ArrowRight' && AppState.modalOpen) {
            navigateCharts(1);
        }
    });
}

function openChartModal(chartIndex) {
    AppState.currentChartIndex = chartIndex;
    AppState.modalOpen = true;
    
    const modal = document.getElementById('chartModal');
    const modalImg = document.getElementById('modalChartImg');
    const chartTitle = document.getElementById('modalChartTitle');
    const chartInfo = document.getElementById('modalChartInfo');
    
    if (AppState.charts[chartIndex]) {
        const chart = AppState.charts[chartIndex];
        
        // Mostrar loading
        modalImg.style.opacity = '0';
        modalImg.src = '';
        chartTitle.textContent = chart.title;
        chartInfo.textContent = `${chartIndex + 1} de ${AppState.charts.length}`;
        
        updateModalNavigation();
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Preload image
        const img = new Image();
        img.onload = function() {
            modalImg.src = chart.url;
            setTimeout(() => {
                modalImg.style.opacity = '1';
            }, 50);
        };
        img.onerror = function() {
            // Si falla la carga, mostrar placeholder codificado correctamente
            const modalFallbackSvg = `data:image/svg+xml,${encodeURIComponent(
                '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">' +
                '<rect width="800" height="600" fill="#1e293b"/>' +
                '<text x="400" y="300" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="18">Gráfico no disponible</text>' +
                '</svg>'
            )}`;
            modalImg.src = modalFallbackSvg;
            modalImg.style.opacity = '1';
        };
        img.src = chart.url + '?t=' + new Date().getTime();
    }
}

function closeChartModal() {
    const modal = document.getElementById('chartModal');
    if (!modal) return;
    
    modal.classList.remove('active');
    AppState.modalOpen = false;
    document.body.style.overflow = 'auto';
}

function navigateCharts(direction) {
    const newIndex = AppState.currentChartIndex + direction;
    
    if (newIndex >= 0 && newIndex < AppState.charts.length) {
        openChartModal(newIndex);
    }
}

function updateModalNavigation() {
    const prevBtn = document.querySelector('.prev-chart');
    const nextBtn = document.querySelector('.next-chart');
    const chartInfo = document.getElementById('modalChartInfo');
    
    if (prevBtn) prevBtn.disabled = AppState.currentChartIndex === 0;
    if (nextBtn) nextBtn.disabled = AppState.currentChartIndex === AppState.charts.length - 1;
    if (chartInfo) chartInfo.textContent = `${AppState.currentChartIndex + 1} de ${AppState.charts.length}`;
}

// Cargar gráficos predefinidos
function loadPreloadedCharts() {
    // Mostrar estado de carga
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        carousel.innerHTML = `
            <div class="carousel-item" style="width: 100%; text-align: center; padding: 40px;">
                <div class="loading" style="font-size: 48px; margin-bottom: 20px;">🌌</div>
                <h3 style="color: #e2e8f0; margin-bottom: 10px;">Cargando gráficos...</h3>
                <p style="color: #94a3b8;">Preparando visualizaciones del sistema solar</p>
            </div>
        `;
    }
    
    // Cargar gráficos desde el servidor
    fetch('generate_charts.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.charts && data.charts.length > 0) {
                AppState.charts = data.charts;
                showNotification('✅ ' + data.message);
            } else {
                AppState.charts = getDefaultCharts();
                showNotification('⚠️ Usando gráficos por defecto');
            }
            renderCarousel();
        })
        .catch(error => {
            console.error('Error cargando gráficos:', error);
            AppState.charts = getDefaultCharts();
            showNotification('❌ Error cargando gráficos. Usando versión local.');
            renderCarousel();
        });
}

function getDefaultCharts() {
    // Gráficos por defecto que deberían generarse
    return [
        {
            url: 'generated_charts/mass_composition.png',
            title: 'Composición de Masas',
            description: 'Distribución de masa en el sistema solar'
        },
        {
            url: 'generated_charts/temperature_area.png',
            title: 'Temperaturas por Tipo',
            description: 'Área bajo la curva de temperaturas por categoría'
        },
        {
            url: 'generated_charts/size_comparison.png',
            title: 'Comparación de Tamaños',
            description: 'Diámetros relativos de cuerpos celestes'
        },
        {
            url: 'generated_charts/orbital_periods.png',
            title: 'Períodos Orbitales',
            description: 'Duración de órbitas alrededor del Sol'
        },
        {
            url: 'generated_charts/planet_types.png',
            title: 'Tipos de Planetas',
            description: 'Distribución por tipo de cuerpo celeste'
        },
        {
            url: 'generated_charts/moon_distribution.png',
            title: 'Distribución de Lunas',
            description: 'Número de satélites por planeta'
        },
        {
            url: 'generated_charts/density_comparison.png',
            title: 'Comparación de Densidades',
            description: 'Densidad promedio de los cuerpos celestes'
        }
    ];
}

function renderCarousel() {
    const carousel = document.querySelector('.carousel');
    if (!carousel) return;
    
    if (AppState.charts.length === 0) {
        carousel.innerHTML = `
            <div class="carousel-item" style="width: 100%; text-align: center; padding: 40px;">
                <div style="font-size: 48px; margin-bottom: 20px;">📊</div>
                <h3 style="color: #e2e8f0; margin-bottom: 10px;">No hay gráficos disponibles</h3>
                <p style="color: #94a3b8;">Los gráficos se generan automáticamente al iniciar la aplicación</p>
            </div>
        `;
        return;
    }
    
    // SVG de fallback codificado correctamente
    const fallbackSvg = `data:image/svg+xml,${encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="320" height="200" viewBox="0 0 320 200">' +
        '<rect width="320" height="200" fill="#1e293b"/>' +
        '<text x="160" y="100" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="14">Gráfico no disponible</text>' +
        '</svg>'
    )}`;
    
    carousel.innerHTML = AppState.charts.map((chart, index) => `
        <div class="carousel-item" data-index="${index}">
            <img src="${chart.url}" alt="${chart.title}" 
                 onerror="this.onerror=null; this.src='${fallbackSvg}'">
            <div class="carousel-content">
                <h3>${chart.title}</h3>
                <p>${chart.description}</p>
            </div>
        </div>
    `).join('');
    
    // Re-inicializar event listeners
    const carouselItems = document.querySelectorAll('.carousel-item');
    carouselItems.forEach((item, index) => {
        item.addEventListener('click', () => openChartModal(index));
    });
    
    updateCarouselNavigation();
}

// Efecto de campo de estrellas
function createStarfield() {
    const container = document.querySelector('.space-bg');
    if (!container) return;
    
    const starCount = 200;
    
    for (let i = 0; i < starCount; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        
        // Tamaño aleatorio
        const size = Math.random() * 3;
        star.style.width = `${size}px`;
        star.style.height = `${size}px`;
        
        // Posición aleatoria
        star.style.left = `${Math.random() * 100}%`;
        star.style.top = `${Math.random() * 100}%`;
        
        // Brillo aleatorio
        const brightness = 0.5 + Math.random() * 0.5;
        star.style.opacity = brightness;
        
        // Animación aleatoria
        const duration = 2 + Math.random() * 4;
        const delay = Math.random() * 5;
        star.style.animation = `twinkle ${duration}s infinite ${delay}s`;
        
        container.appendChild(star);
    }
}

// Gráficos interactivos con Chart.js - VERSIÓN COMPLETAMENTE CORREGIDA
function initializeInteractiveCharts() {
    console.log('🚀 Inicializando gráficos interactivos...');
    console.log('Datos disponibles:', typeof bodiesData !== 'undefined' ? bodiesData.length : 'No hay datos');
    
    // Verificar que Chart.js esté cargado
    if (typeof Chart === 'undefined') {
        console.error('❌ Chart.js no está cargado');
        showNotification('❌ Error: Chart.js no está disponible');
        return;
    }
    
    // Mostrar estados de carga
    showChartLoading('massChart', true);
    showChartLoading('temperatureChart', true);
    
    // Pequeño delay para asegurar que el DOM esté listo
    setTimeout(() => {
        createMassChart();
        createTemperatureChart();
    }, 100);
}

function createMassChart() {
    const canvas = document.getElementById('massChart');
    const loadingElement = document.getElementById('massChartLoading');
    
    if (!canvas) {
        console.error('❌ No se encontró el canvas para el gráfico de masas');
        showChartLoading('massChart', false);
        return;
    }
    
    if (!bodiesData || bodiesData.length === 0) {
        console.error('❌ No hay datos para el gráfico de masas');
        showChartLoading('massChart', false);
        return;
    }
    
    try {
        // Destruir gráfico existente
        if (AppState.interactiveCharts.massChart) {
            AppState.interactiveCharts.massChart.destroy();
        }
        
        const ctx = canvas.getContext('2d');
        
        // Configuración global de Chart.js para tema oscuro
        Chart.defaults.color = '#e2e8f0';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';
        
        const massChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: bodiesData.map(body => body.name),
                datasets: [{
                    label: 'Masa (kg)',
                    data: bodiesData.map(body => body.mass_kg),
                    backgroundColor: bodiesData.map(body => body.color),
                    borderColor: bodiesData.map(body => body.color),
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#e2e8f0',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: "'Inter', 'Segoe UI', sans-serif"
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(99, 102, 241, 0.5)',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                if (value >= 1e24) {
                                    return `Masa: ${(value / 1e24).toFixed(2)} × 10²⁴ kg`;
                                } else if (value >= 1e21) {
                                    return `Masa: ${(value / 1e21).toFixed(2)} × 10²¹ kg`;
                                }
                                return `Masa: ${value.toExponential(2)} kg`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'logarithmic',
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Masa (kg) - Escala Logarítmica',
                            color: '#f8fafc',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: "'Inter', 'Segoe UI', sans-serif"
                            },
                            padding: {top: 10, bottom: 10}
                        },
                        ticks: {
                            color: '#94a3b8',
                            callback: function(value) {
                                if (value >= 1e24) {
                                    return (value / 1e24).toFixed(1) + 'e24';
                                } else if (value >= 1e21) {
                                    return (value / 1e21).toFixed(1) + 'e21';
                                } else if (value >= 1e18) {
                                    return (value / 1e18).toFixed(1) + 'e18';
                                }
                                return value.toExponential(1);
                            },
                            padding: 8
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            drawBorder: true,
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Cuerpos Celestes',
                            color: '#f8fafc',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: "'Inter', 'Segoe UI', sans-serif"
                            },
                            padding: {top: 10, bottom: 10}
                        },
                        ticks: {
                            color: '#94a3b8',
                            maxRotation: 45,
                            padding: 8
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            drawBorder: true,
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // Guardar referencia
        AppState.interactiveCharts.massChart = massChart;
        showChartLoading('massChart', false);
        console.log('✅ Gráfico de masas creado exitosamente');
        
    } catch (error) {
        console.error('❌ Error creando gráfico de masas:', error);
        showChartLoading('massChart', false);
        showNotification('❌ Error al crear gráfico de masas');
    }
}

function createTemperatureChart() {
    const canvas = document.getElementById('temperatureChart');
    const loadingElement = document.getElementById('temperatureChartLoading');
    
    if (!canvas) {
        console.error('❌ No se encontró el canvas para el gráfico de temperaturas');
        showChartLoading('temperatureChart', false);
        return;
    }
    
    if (!bodiesData || bodiesData.length === 0) {
        console.error('❌ No hay datos para el gráfico de temperaturas');
        showChartLoading('temperatureChart', false);
        return;
    }
    
    try {
        // Destruir gráfico existente
        if (AppState.interactiveCharts.temperatureChart) {
            AppState.interactiveCharts.temperatureChart.destroy();
        }
        
        const ctx = canvas.getContext('2d');
        
        const temperatureChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: bodiesData.map(body => body.name),
                datasets: [{
                    label: 'Temperatura (K)',
                    data: bodiesData.map(body => body.temperature_k),
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: bodiesData.map(body => body.color),
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#e2e8f0',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: "'Inter', 'Segoe UI', sans-serif"
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(245, 158, 11, 0.5)',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return `Temperatura: ${context.raw} K`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Temperatura (K)',
                            color: '#f8fafc',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: "'Inter', 'Segoe UI', sans-serif"
                            },
                            padding: {top: 10, bottom: 10}
                        },
                        ticks: {
                            color: '#94a3b8',
                            padding: 8
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            drawBorder: true,
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Cuerpos Celestes',
                            color: '#f8fafc',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: "'Inter', 'Segoe UI', sans-serif"
                            },
                            padding: {top: 10, bottom: 10}
                        },
                        ticks: {
                            color: '#94a3b8',
                            maxRotation: 45,
                            padding: 8
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            drawBorder: true,
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // Guardar referencia
        AppState.interactiveCharts.temperatureChart = temperatureChart;
        showChartLoading('temperatureChart', false);
        console.log('✅ Gráfico de temperaturas creado exitosamente');
        
    } catch (error) {
        console.error('❌ Error creando gráfico de temperaturas:', error);
        showChartLoading('temperatureChart', false);
        showNotification('❌ Error al crear gráfico de temperaturas');
    }
}

// Función para mostrar/ocultar loading de gráficos
function showChartLoading(chartId, show) {
    const loadingElement = document.getElementById(chartId + 'Loading');
    const canvas = document.getElementById(chartId);
    
    if (loadingElement) {
        loadingElement.style.display = show ? 'flex' : 'none';
    }
    if (canvas) {
        canvas.style.display = show ? 'none' : 'block';
    }
}

// Función para actualizar gráficos interactivos
function refreshInteractiveCharts() {
    console.log('🔄 Actualizando gráficos interactivos...');
    showNotification('🔄 Actualizando gráficos...');
    
    // Destruir gráficos existentes
    if (AppState.interactiveCharts.massChart) {
        AppState.interactiveCharts.massChart.destroy();
        AppState.interactiveCharts.massChart = null;
    }
    if (AppState.interactiveCharts.temperatureChart) {
        AppState.interactiveCharts.temperatureChart.destroy();
        AppState.interactiveCharts.temperatureChart = null;
    }
    
    // Mostrar loading
    showChartLoading('massChart', true);
    showChartLoading('temperatureChart', true);
    
    // Recrear gráficos después de un pequeño delay
    setTimeout(() => {
        createMassChart();
        createTemperatureChart();
        showNotification('✅ Gráficos interactivos actualizados');
    }, 500);
}

// Funciones de utilidad
function exportData() {
    if (typeof bodiesData === 'undefined' || !bodiesData.length) {
        showNotification('❌ No hay datos disponibles para exportar');
        return;
    }
    
    try {
        const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(bodiesData, null, 2));
        const downloadAnchorNode = document.createElement('a');
        downloadAnchorNode.setAttribute("href", dataStr);
        downloadAnchorNode.setAttribute("download", "solar_system_data.json");
        document.body.appendChild(downloadAnchorNode);
        downloadAnchorNode.click();
        downloadAnchorNode.remove();
        
        showNotification('✅ Datos exportados exitosamente');
    } catch (error) {
        console.error('Error exportando datos:', error);
        showNotification('❌ Error al exportar datos');
    }
}

function printCharts() {
    window.print();
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--accent);
        color: white;
        padding: 15px 25px;
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow);
        z-index: 10000;
        font-weight: 600;
        animation: slideInRight 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Agregar estilos de animación para notificaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Redimensionar carrusel en resize
window.addEventListener('resize', () => {
    updateCarouselNavigation();
});

// Utilidades de formato
function formatMass(mass) {
    if (mass >= 1e24) {
        return (mass / 1e24).toFixed(2) + ' × 10²⁴ kg';
    } else if (mass >= 1e21) {
        return (mass / 1e21).toFixed(2) + ' × 10²¹ kg';
    } else {
        return mass.toFixed(2) + ' kg';
    }
}

// Inicializar gráficos cuando la página esté completamente cargada
window.addEventListener('load', function() {
    console.log('Página completamente cargada');
    // Los gráficos interactivos se inicializan automáticamente al cambiar de pestaña
});