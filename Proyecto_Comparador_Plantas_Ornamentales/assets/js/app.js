class PlantComparisonApp {
    constructor() {
        this.selectedPlants = [];
        this.maxSelection = 2;
        this.plants = [];
        
        this.init();
    }

    async init() {
        await this.loadPlants();
        this.setupEventListeners();
        this.renderPlants();
    }

    async loadPlants() {
        try {
            const response = await fetch('compare.php');
            if (response.ok) {
                this.plants = await response.json();
                this.preloadImages();
            } else {
                console.error('Error al cargar las plantas');
                this.showError('No se pudieron cargar las plantas');
            }
        } catch (error) {
            console.error('Error de conexión:', error);
            this.showError('Error de conexión al cargar las plantas');
        }
    }

    preloadImages() {
        this.plants.forEach(plant => {
            const img = new Image();
            img.src = plant.image;
            img.onerror = () => {
                console.warn(`Error cargando imagen para ${plant.name}`);
            };
        });
    }

    setupEventListeners() {
        // Búsqueda de plantas
        document.getElementById('plantSearch').addEventListener('input', (e) => {
            this.filterPlants(e.target.value);
        });

        // ✅ SISTEMA DE SELECCIÓN ORIGINAL - CLIC EN CUALQUIER PARTE DE LA TARJETA
        document.getElementById('plantsGrid').addEventListener('click', (e) => {
            const plantCard = e.target.closest('.plant-card');
            if (plantCard) {
                const plantId = parseInt(plantCard.dataset.id);
                this.togglePlantSelection(plantId);
            }
        });

        // Botones de remover en la vista previa
        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-remove-plant')) {
                const plantId = parseInt(e.target.closest('.btn-remove-plant').dataset.id);
                this.removePlant(plantId);
                e.stopPropagation(); // Prevenir que el clic se propague
            }

            if (e.target.closest('.btn-view-details')) {
                const plantId = parseInt(e.target.closest('.btn-view-details').dataset.id);
                this.showPlantDetails(plantId);
                e.stopPropagation(); // Prevenir que el clic se propague
            }
        });

        // Botones de acción principales
        document.getElementById('compareBtn').addEventListener('click', () => {
            this.comparePlants();
        });

        document.getElementById('clearSelection').addEventListener('click', () => {
            this.clearSelection();
        });

        document.getElementById('closeResults').addEventListener('click', () => {
            this.hideResults();
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.querySelector('.plant-modal');
                if (modal) modal.remove();
            }
        });
    }

    filterPlants(searchTerm) {
        const term = searchTerm.toLowerCase();
        const plantCards = document.querySelectorAll('.plant-card');
        
        plantCards.forEach(card => {
            const plantName = card.dataset.name;
            const scientificName = card.querySelector('.plant-scientific').textContent.toLowerCase();
            const description = card.querySelector('.plant-description').textContent.toLowerCase();
            
            if (plantName.includes(term) || scientificName.includes(term) || description.includes(term)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // ✅ FUNCIÓN ORIGINAL DE SELECCIÓN - SIMPLE Y EFECTIVA
    togglePlantSelection(plantId) {
        const plant = this.plants.find(p => p.id === plantId);
        if (!plant) return;

        const isSelected = this.selectedPlants.some(p => p.id === plantId);

        if (isSelected) {
            // Deseleccionar
            this.selectedPlants = this.selectedPlants.filter(p => p.id !== plantId);
            this.showNotification(`🗑️ ${plant.name} removida`, 'info');
        } else {
            // Verificar límite
            if (this.selectedPlants.length >= this.maxSelection) {
                this.showNotification(`Solo puedes seleccionar máximo ${this.maxSelection} plantas`, 'warning');
                return;
            }
            // Seleccionar
            this.selectedPlants.push(plant);
            this.showNotification(`🌿 ${plant.name} seleccionada`, 'success');
        }
        
        this.updateUI();
    }

    // Función auxiliar para remover plantas específicas
    removePlant(plantId) {
        const plant = this.plants.find(p => p.id === plantId);
        this.selectedPlants = this.selectedPlants.filter(p => p.id !== plantId);
        this.updateUI();
        if (plant) {
            this.showNotification(`🗑️ ${plant.name} removida`, 'info');
        }
    }

    clearSelection() {
        this.selectedPlants = [];
        this.updateUI();
        this.showNotification('🧹 Selección limpiada', 'info');
    }

    updateUI() {
        this.updateSelectionCounter();
        this.updatePlantCards();
        this.updateSelectedPlantsPreview();
        this.updateCompareButton();
    }

    updateSelectionCounter() {
        const counter = document.getElementById('selectedCount');
        if (counter) {
            counter.textContent = this.selectedPlants.length;
        }
    }

    updatePlantCards() {
        document.querySelectorAll('.plant-card').forEach(card => {
            const plantId = parseInt(card.dataset.id);
            const isSelected = this.selectedPlants.some(p => p.id === plantId);
            
            if (isSelected) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }

    updateSelectedPlantsPreview() {
        const container = document.getElementById('selectedPlantsPreview');
        if (!container) return;
        
        if (this.selectedPlants.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-seedling"></i>
                    <p>Selecciona hasta 2 plantas para comparar</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="selected-plants-container">
                ${this.selectedPlants.map(plant => `
                    <div class="selected-plant-preview">
                        <div class="plant-image-container">
                            <img src="${plant.image}" alt="${plant.name}" 
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDEyMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiBmaWxsPSIjMTEyNzI3Ii8+CjxwYXRoIGQ9Ik00NSA0NUg3NVY3NUg0NVY0NVoiIGZpbGw9IiMxZjI5MzciLz4KPHBhdGggZD0iTTQ1IDQ1Vjc1SDc1VjQ1SDQ1Wk00NSA0NUg3NU03NSA0NVY3NU03NSA3NUg0NU00NSA3NVY0NSIgc3Ryb2tlPSIzNzQxNTEiIHN0cm9rZS13aWR0aD0iMiIvPgo8L3N2Zz4='">
                            <div class="plant-badge">${plant.name.charAt(0)}</div>
                        </div>
                        <h4>${plant.name}</h4>
                        <p class="scientific">${plant.scientific}</p>
                        <div class="plant-stats">
                            <div class="stat">
                                <span class="stat-label">Resistencia</span>
                                <div class="stat-value">${plant.characteristics.resistencia}/10</div>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Floración</span>
                                <div class="stat-value">${plant.characteristics.floracion}/10</div>
                            </div>
                        </div>
                        <button class="btn-remove-plant" data-id="${plant.id}">
                            <i class="fas fa-times"></i>
                            Remover
                        </button>
                    </div>
                `).join('')}
            </div>
        `;
    }

    updateCompareButton() {
        const compareBtn = document.getElementById('compareBtn');
        if (compareBtn) {
            compareBtn.disabled = this.selectedPlants.length < 2;
            
            if (this.selectedPlants.length === 2) {
                compareBtn.innerHTML = '<i class="fas fa-chart-radar"></i> Comparar 2 Plantas';
            } else {
                compareBtn.innerHTML = '<i class="fas fa-chart-radar"></i> Generar Comparación';
            }
        }
    }

    async comparePlants() {
        if (this.selectedPlants.length !== 2) {
            this.showNotification('Selecciona exactamente 2 plantas para comparar', 'warning');
            return;
        }

        this.showLoading(true);

        try {
            const response = await fetch('compare.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    plantIds: this.selectedPlants.map(p => p.id)
                })
            });

            const result = await response.json();
            
            if (result.success) {
                this.displayComparisonResult(result);
                this.showNotification('📊 Comparación generada exitosamente', 'success');
            } else {
                throw new Error(result.error || 'Error en la comparación');
            }
        } catch (error) {
            console.error('Error al comparar plantas:', error);
            this.showError('Error al generar la comparación: ' + error.message);
            this.displayFallbackComparison();
        } finally {
            this.showLoading(false);
        }
    }

    displayComparisonResult(result) {
        const resultsContent = document.getElementById('resultsContent');
        const comparisonSection = document.getElementById('comparisonResults');
        
        if (!resultsContent || !comparisonSection) return;

        let content = '';
        
        if (result.chartPath) {
            content = `
                <div class="comparison-chart">
                    <div class="radar-chart-container">
                        <img src="${result.chartPath}?t=${Date.now()}" 
                             alt="Gráfico de comparación" 
                             class="radar-image fade-in"
                             style="max-height: 400px; width: auto;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="chart-placeholder" style="display: none;">
                            <i class="fas fa-chart-pie"></i>
                            <h3>Gráfico no disponible</h3>
                            <p>Mostrando tabla comparativa en su lugar.</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            content = `
                <div class="chart-placeholder">
                    <i class="fas fa-chart-pie"></i>
                    <h3>Gráfico no disponible</h3>
                    <p>Mostrando tabla comparativa en su lugar.</p>
                </div>
            `;
        }

        content += this.createComparisonTable(result);
        content += this.createCharacteristicsSummary(result);

        resultsContent.innerHTML = content;
        comparisonSection.style.display = 'block';
        
        // Scroll suave a los resultados
        comparisonSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    displayFallbackComparison() {
        const resultsContent = document.getElementById('resultsContent');
        const comparisonSection = document.getElementById('comparisonResults');
        
        if (!resultsContent || !comparisonSection) return;

        const fallbackContent = `
            <div class="fallback-comparison">
                <div class="message warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gráfico no disponible</h3>
                    <p>Mostrando tabla comparativa en su lugar.</p>
                </div>
                ${this.createFallbackTable()}
                ${this.createCharacteristicsSummary({
                    plants: this.selectedPlants,
                    comparison: {
                        categories: ['Resistencia', 'Mantenimiento', 'Floración', 'Adaptabilidad', 'Duración'],
                        values: {
                            [this.selectedPlants[0].name]: [
                                this.selectedPlants[0].characteristics.resistencia,
                                this.selectedPlants[0].characteristics.mantenimiento,
                                this.selectedPlants[0].characteristics.floracion,
                                this.selectedPlants[0].characteristics.adaptabilidad,
                                this.selectedPlants[0].characteristics.duracion
                            ],
                            [this.selectedPlants[1].name]: [
                                this.selectedPlants[1].characteristics.resistencia,
                                this.selectedPlants[1].characteristics.mantenimiento,
                                this.selectedPlants[1].characteristics.floracion,
                                this.selectedPlants[1].characteristics.adaptabilidad,
                                this.selectedPlants[1].characteristics.duracion
                            ]
                        }
                    }
                })}
            </div>
        `;
        
        resultsContent.innerHTML = fallbackContent;
        comparisonSection.style.display = 'block';
        comparisonSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    createFallbackTable() {
        if (this.selectedPlants.length < 2) return '';
        
        return `
            <div class="comparison-table-container fade-in">
                <h3>Comparación Detallada</h3>
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Característica</th>
                            <th>${this.selectedPlants[0].name}</th>
                            <th>${this.selectedPlants[1].name}</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${['Resistencia', 'Mantenimiento', 'Floración', 'Adaptabilidad', 'Duración'].map((category, index) => {
                            const key = category.toLowerCase();
                            const value1 = this.selectedPlants[0].characteristics[key] || 0;
                            const value2 = this.selectedPlants[1].characteristics[key] || 0;
                            
                            return `
                                <tr>
                                    <td>${category}</td>
                                    <td>
                                        <div class="value-bar">
                                            <div class="bar-fill" style="width: ${value1 * 10}%">
                                                ${value1}/10
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="value-bar">
                                            <div class="bar-fill" style="width: ${value2 * 10}%">
                                                ${value2}/10
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    createComparisonTable(result) {
        const { plants, comparison } = result;
        
        return `
            <div class="comparison-table-container fade-in">
                <h3>Comparación Detallada</h3>
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Característica</th>
                            <th>${plants[0].name}</th>
                            <th>${plants[1].name}</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${comparison.categories.map((category, index) => `
                            <tr>
                                <td>${category}</td>
                                <td>
                                    <div class="value-bar">
                                        <div class="bar-fill" style="width: ${comparison.values[plants[0].name][index] * 10}%">
                                            ${comparison.values[plants[0].name][index]}/10
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="value-bar">
                                        <div class="bar-fill" style="width: ${comparison.values[plants[1].name][index] * 10}%">
                                            ${comparison.values[plants[1].name][index]}/10
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    createCharacteristicsSummary(result) {
        const { plants } = result;
        
        return `
            <div class="characteristics-summary fade-in">
                <h3>Resumen de Características</h3>
                <div class="summary-grid">
                    ${plants.map(plant => `
                        <div class="plant-summary">
                            <h4>${plant.name}</h4>
                            <p><strong>Nombre científico:</strong> ${plant.scientific}</p>
                            <p><strong>Descripción:</strong> ${plant.description}</p>
                            <div class="key-characteristics">
                                <h5>Características clave:</h5>
                                <ul>
                                    <li>🌱 Resistencia: ${plant.characteristics.resistencia}/10</li>
                                    <li>🔧 Mantenimiento: ${plant.characteristics.mantenimiento}/10</li>
                                    <li>🌸 Floración: ${plant.characteristics.floracion}/10</li>
                                    <li>🔄 Adaptabilidad: ${plant.characteristics.adaptabilidad}/10</li>
                                    <li>⏱️ Duración: ${plant.characteristics.duracion}/10</li>
                                </ul>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    showPlantDetails(plantId) {
        const plant = this.plants.find(p => p.id === plantId);
        if (!plant) return;

        // Cerrar modal existente si hay uno
        const existingModal = document.querySelector('.plant-modal');
        if (existingModal) existingModal.remove();

        const modal = document.createElement('div');
        modal.className = 'plant-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>${plant.name}</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <img src="${plant.image}" alt="${plant.name}" 
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDQwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjMTEyNzI3Ii8+CjxwYXRoIGQ9Ik0xMDAgMTAwSDMwMFYyMDBIMTAwVjEwMFpNMTAwIDEwMEgzMDBNMzAwIDEwMFYyMDBNMzAwIDIwMEgxMDBNMTAwIDIwMFYxMDAiIHN0cm9rZT0iIzM3NDE1MSIgc3Ryb2tlLXdpZHRoPSIyIi8+Cjwvc3ZnPg=='>
                    <div class="plant-info">
                        <p class="scientific">${plant.scientific}</p>
                        <p class="description">${plant.description}</p>
                        <div class="characteristics-grid">
                            ${Object.entries(plant.characteristics).map(([key, value]) => `
                                <div class="characteristic-item">
                                    <span class="char-name">${this.formatCharacteristicName(key)}</span>
                                    <div class="char-bar">
                                        <div class="char-fill" style="width: ${value * 10}%"></div>
                                    </div>
                                    <span class="char-value">${value}/10</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;

        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(10px);
        `;

        modal.querySelector('.modal-close').onclick = () => modal.remove();
        modal.onclick = (e) => {
            if (e.target === modal) modal.remove();
        };

        document.body.appendChild(modal);
    }

    formatCharacteristicName(key) {
        const names = {
            resistencia: '🌱 Resistencia',
            mantenimiento: '🔧 Mantenimiento',
            floracion: '🌸 Floración',
            adaptabilidad: '🔄 Adaptabilidad',
            duracion: '⏱️ Duración'
        };
        return names[key] || key;
    }

    hideResults() {
        const comparisonSection = document.getElementById('comparisonResults');
        if (comparisonSection) {
            comparisonSection.style.display = 'none';
        }
    }

    showLoading(show) {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = show ? 'flex' : 'none';
        }
    }

    showNotification(message, type = 'info') {
        // Cerrar notificaciones existentes
        document.querySelectorAll('.notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;

        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${this.getNotificationColor(type)};
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 1001;
            animation: slideInRight 0.3s ease;
            max-width: 350px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        `;

        document.body.appendChild(toast);

        // Remover después de 4 segundos
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        }, 4000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            warning: 'exclamation-triangle',
            error: 'times-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    getNotificationColor(type) {
        const colors = {
            success: '#10b981',
            warning: '#f59e0b',
            error: '#ef4444',
            info: '#3b82f6'
        };
        return colors[type] || '#3b82f6';
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    renderPlants() {
        const grid = document.getElementById('plantsGrid');
        if (!grid) return;

        grid.innerHTML = this.plants.map(plant => this.createPlantCard(plant)).join('');
    }

    createPlantCard(plant) {
        const isSelected = this.selectedPlants.some(p => p.id === plant.id);
        
        return `
            <div class="plant-card ${isSelected ? 'selected' : ''}" 
                 data-id="${plant.id}" 
                 data-name="${plant.name.toLowerCase()}">
                <div class="card-header">
                    <div class="selection-indicator">
                        <i class="fas fa-check"></i>
                    </div>
                    <img src="${plant.image}" alt="${plant.name}" class="plant-image"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDQwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjMTEyNzI3Ii8+CjxwYXRoIGQ9Ik0xMDAgMTAwSDMwMFYyMDBIMTAwVjEwMFpNMTAwIDEwMEgzMDBNMzAwIDEwMFYyMDBNMzAwIDIwMEgxMDBNMTAwIDIwMFYxMDAiIHN0cm9rZT0iIzM3NDE1MSIgc3Ryb2tlLXdpZHRoPSIyIi8+Cjwvc3ZnPg=='>
                    <div class="plant-overlay">
                        <button class="btn-select-plant" data-id="${plant.id}">
                            <i class="fas fa-${isSelected ? 'minus' : 'plus'}"></i>
                            ${isSelected ? 'Remover' : 'Seleccionar'}
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <h3 class="plant-name">${plant.name}</h3>
                    <p class="plant-scientific">${plant.scientific}</p>
                    <p class="plant-description">${plant.description}</p>
                    
                    <div class="plant-characteristics">
                        <div class="characteristic">
                            <span class="char-label">Resistencia</span>
                            <div class="char-bar">
                                <div class="char-fill" style="width: ${plant.characteristics.resistencia * 10}%"></div>
                            </div>
                            <span class="char-value">${plant.characteristics.resistencia}/10</span>
                        </div>
                        <div class="characteristic">
                            <span class="char-label">Floración</span>
                            <div class="char-bar">
                                <div class="char-fill" style="width: ${plant.characteristics.floracion * 10}%"></div>
                            </div>
                            <span class="char-value">${plant.characteristics.floracion}/10</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button class="btn-view-details" data-id="${plant.id}">
                        <i class="fas fa-info-circle"></i>
                        Detalles
                    </button>
                </div>
            </div>
        `;
    }
}

// Sistema de partículas para el fondo
class ParticleSystem {
    constructor() {
        this.particles = [];
        this.container = null;
        this.init();
    }

    init() {
        this.container = document.createElement('div');
        this.container.className = 'particles';
        document.body.appendChild(this.container);
        this.createParticles();
    }

    createParticles() {
        const particleCount = 12;
        
        for (let i = 0; i < particleCount; i++) {
            setTimeout(() => {
                this.createParticle();
            }, i * 300);
        }
    }

    createParticle() {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 80 + 40;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.top = `${Math.random() * 100}vh`;
        
        particle.style.opacity = Math.random() * 0.04 + 0.01;
        
        const colors = [
            'rgba(16, 185, 129, 0.08)',
            'rgba(139, 92, 246, 0.08)',
            'rgba(245, 158, 11, 0.08)'
        ];
        const color = colors[Math.floor(Math.random() * colors.length)];
        particle.style.background = `radial-gradient(circle, ${color} 0%, transparent 70%)`;
        
        const duration = Math.random() * 40 + 25;
        const delay = Math.random() * 15;
        particle.style.animation = `float ${duration}s infinite linear`;
        particle.style.animationDelay = `${delay}s`;
        
        this.container.appendChild(particle);
        this.particles.push(particle);
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new PlantComparisonApp();
    new ParticleSystem();
});

// Añadir estilos CSS adicionales para el tema oscuro
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
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes float {
        0% { transform: translateY(100vh) rotate(0deg); }
        100% { transform: translateY(-100vh) rotate(360deg); }
    }
    
    .plant-image-container {
        position: relative;
        display: inline-block;
    }
    
    .plant-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    
    .plant-stats {
        display: flex;
        gap: 1rem;
        margin: 1rem 0;
        justify-content: center;
    }
    
    .stat {
        text-align: center;
    }
    
    .stat-label {
        display: block;
        font-size: 0.8rem;
        color: #9ca3af;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-weight: 600;
        color: #f9fafb;
    }
    
    .plant-modal .modal-content {
        background: linear-gradient(135deg, #1f2937, #111827);
        border-radius: 16px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        border: 1px solid #374151;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.4);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #374151;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #9ca3af;
        transition: color 0.3s ease;
    }
    
    .modal-close:hover {
        color: #f9fafb;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-body img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #374151;
    }
    
    .characteristics-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .characteristic-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .char-name {
        min-width: 120px;
        font-weight: 500;
        color: #d1d5db;
    }
    
    .message.warning {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #fbbf24;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        backdrop-filter: blur(5px);
    }
    
    .particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
    }
    
    .particle {
        position: absolute;
        border-radius: 50%;
    }
`;
document.head.appendChild(style);