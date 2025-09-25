/**
 * Sidebar JavaScript - CADUxCOM
 * Maneja la funcionalidad del sidebar incluyendo responsive design
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const sidebarContainer = document.querySelector('.sidebar-container');
    const mainContent = document.querySelector('.main-content');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const navButtons = document.querySelectorAll('.nav-buttons .btn');
    
    // Variables de estado
    let isMobile = window.innerWidth <= 768;
    let sidebarOpen = false;
    
    // Inicializar sidebar
    initSidebar();
    
    /**
     * Inicializar funcionalidad del sidebar
     */
    function initSidebar() {
        // Crear botón móvil si no existe
        createMobileButton();
        
        // Crear overlay si no existe
        createOverlay();
        
        // Agregar event listeners
        addEventListeners();
        
        // Marcar botón activo basado en la URL actual
        setActiveButton();
        
        // Configurar responsive
        handleResize();
    }
    
    /**
     * Crear botón hamburguesa para móvil
     */
    function createMobileButton() {
        if (!mobileMenuBtn && isMobile) {
            const button = document.createElement('button');
            button.className = 'mobile-menu-btn';
            button.innerHTML = '<i class="fas fa-bars"></i>';
            button.setAttribute('aria-label', 'Abrir menú');
            document.body.appendChild(button);
            
            // Actualizar referencia
            window.mobileMenuBtn = button;
        }
    }
    
    /**
     * Crear overlay para móvil
     */
    function createOverlay() {
        if (!sidebarOverlay && isMobile) {
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
            
            // Actualizar referencia
            window.sidebarOverlay = overlay;
        }
    }
    
    /**
     * Agregar event listeners
     */
    function addEventListeners() {
        // Botón móvil
        const mobileBtn = document.querySelector('.mobile-menu-btn');
        if (mobileBtn) {
            mobileBtn.addEventListener('click', toggleMobileSidebar);
        }
        
        // Overlay
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeMobileSidebar);
        }
        
        // Resize window
        window.addEventListener('resize', debounce(handleResize, 250));
        
        // Escape key para cerrar sidebar en móvil
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMobile && sidebarOpen) {
                closeMobileSidebar();
            }
        });
        
        // Hover effects para desktop
        if (sidebarContainer) {
            sidebarContainer.addEventListener('mouseenter', handleSidebarHover);
            sidebarContainer.addEventListener('mouseleave', handleSidebarLeave);
        }
        
        // Click en botones de navegación
        navButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Marcar como activo
                setActiveButton(this);
                
                // Cerrar sidebar en móvil después de click
                if (isMobile) {
                    setTimeout(closeMobileSidebar, 150);
                }
            });
        });
    }
    
    /**
     * Toggle sidebar en móvil
     */
    function toggleMobileSidebar() {
        if (sidebarOpen) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    }
    
    /**
     * Abrir sidebar en móvil
     */
    function openMobileSidebar() {
        const sidebar = document.querySelector('.sidebar-container');
        const overlay = document.querySelector('.sidebar-overlay');
        const mobileBtn = document.querySelector('.mobile-menu-btn');
        
        if (sidebar) {
            sidebar.classList.add('mobile-open');
        }
        
        if (overlay) {
            overlay.classList.add('active');
        }
        
        if (mobileBtn) {
            mobileBtn.classList.add('active');
            mobileBtn.innerHTML = '<i class="fas fa-times"></i>';
            mobileBtn.setAttribute('aria-label', 'Cerrar menú');
        }
        
        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';
        
        sidebarOpen = true;
    }
    
    /**
     * Cerrar sidebar en móvil
     */
    function closeMobileSidebar() {
        const sidebar = document.querySelector('.sidebar-container');
        const overlay = document.querySelector('.sidebar-overlay');
        const mobileBtn = document.querySelector('.mobile-menu-btn');
        
        if (sidebar) {
            sidebar.classList.remove('mobile-open');
        }
        
        if (overlay) {
            overlay.classList.remove('active');
        }
        
        if (mobileBtn) {
            mobileBtn.classList.remove('active');
            mobileBtn.innerHTML = '<i class="fas fa-bars"></i>';
            mobileBtn.setAttribute('aria-label', 'Abrir menú');
        }
        
        // Restaurar scroll del body
        document.body.style.overflow = '';
        
        sidebarOpen = false;
    }
    
    /**
     * Manejar hover del sidebar en desktop
     */
    function handleSidebarHover() {
        if (!isMobile && mainContent) {
            mainContent.style.transition = 'margin-left 0.3s ease, width 0.3s ease';
        }
    }
    
    /**
     * Manejar cuando se quita el hover del sidebar
     */
    function handleSidebarLeave() {
        if (!isMobile && mainContent) {
            // Mantener la transición suave
        }
    }
    
    /**
     * Manejar cambios de tamaño de ventana
     */
    function handleResize() {
        const wasMobile = isMobile;
        isMobile = window.innerWidth <= 768;
        
        // Si cambió de móvil a desktop o viceversa
        if (wasMobile !== isMobile) {
            if (isMobile) {
                // Cambió a móvil
                closeMobileSidebar();
                createMobileButton();
                createOverlay();
            } else {
                // Cambió a desktop
                closeMobileSidebar();
                
                // Remover elementos móviles si existen
                const mobileBtn = document.querySelector('.mobile-menu-btn');
                const overlay = document.querySelector('.sidebar-overlay');
                
                if (mobileBtn) mobileBtn.remove();
                if (overlay) overlay.remove();
                
                // Restaurar estilos de desktop
                if (mainContent) {
                    mainContent.style.marginLeft = '';
                    mainContent.style.width = '';
                }
                
                document.body.style.overflow = '';
            }
        }
        
        // Actualizar tooltips en móvil
        updateTooltips();
    }
    
    /**
     * Marcar botón activo basado en la URL o elemento específico
     */
    function setActiveButton(activeButton = null) {
        // Remover clase active de todos los botones
        navButtons.forEach(btn => btn.classList.remove('active'));
        
        if (activeButton) {
            // Marcar el botón clickeado como activo
            activeButton.classList.add('active');
        } else {
            // Determinar botón activo basado en la URL
            const currentPath = window.location.pathname;
            
            navButtons.forEach(btn => {
                const href = btn.getAttribute('href');
                if (href && currentPath.includes(href.replace(/^\//, ''))) {
                    btn.classList.add('active');
                }
            });
            
            // Si no hay coincidencia exacta, marcar el dashboard como activo por defecto
            if (!document.querySelector('.nav-buttons .btn.active')) {
                const dashboardBtn = document.querySelector('.nav-buttons .btn[href*="dashboard"]');
                if (dashboardBtn) {
                    dashboardBtn.classList.add('active');
                }
            }
        }
    }
    
    /**
     * Actualizar tooltips para móvil
     */
    function updateTooltips() {
        if (isMobile) {
            navButtons.forEach(btn => {
                const text = btn.textContent.trim();
                if (text) {
                    btn.setAttribute('data-tooltip', text);
                }
            });
        } else {
            navButtons.forEach(btn => {
                btn.removeAttribute('data-tooltip');
            });
        }
    }
    
    /**
     * Función debounce para optimizar eventos de resize
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    /**
     * Función para agregar animaciones suaves
     */
    function addSmoothAnimations() {
        // Agregar clases CSS para animaciones si no existen
        const style = document.createElement('style');
        style.textContent = `
            .sidebar-container {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .nav-buttons .btn {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .main-content {
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                           width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
        `;
        
        if (!document.querySelector('#sidebar-animations')) {
            style.id = 'sidebar-animations';
            document.head.appendChild(style);
        }
    }
    
    /**
     * Función para manejar accesibilidad
     */
    function setupAccessibility() {
        // Agregar roles ARIA
        if (sidebarContainer) {
            sidebarContainer.setAttribute('role', 'navigation');
            sidebarContainer.setAttribute('aria-label', 'Menú principal');
        }
        
        // Agregar navegación por teclado
        navButtons.forEach((btn, index) => {
            btn.setAttribute('tabindex', '0');
            
            btn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    btn.click();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextBtn = navButtons[index + 1] || navButtons[0];
                    nextBtn.focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevBtn = navButtons[index - 1] || navButtons[navButtons.length - 1];
                    prevBtn.focus();
                }
            });
        });
    }
    
    // Inicializar funcionalidades adicionales
    addSmoothAnimations();
    setupAccessibility();
    
    // Exponer funciones globalmente para uso externo si es necesario
    window.sidebarUtils = {
        toggleMobile: toggleMobileSidebar,
        closeMobile: closeMobileSidebar,
        openMobile: openMobileSidebar,
        setActive: setActiveButton,
        isMobile: () => isMobile,
        isOpen: () => sidebarOpen
    };
});

/**
 * Función para inicializar el sidebar desde HTML
 * Útil si se necesita reinicializar después de cambios dinámicos
 */
function initializeSidebar() {
    // Disparar evento DOMContentLoaded manualmente
    const event = new Event('DOMContentLoaded');
    document.dispatchEvent(event);
}

// Exportar para uso en módulos si es necesario
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { initializeSidebar };
}
