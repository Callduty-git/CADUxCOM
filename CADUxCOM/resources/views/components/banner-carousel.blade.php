<div id="bannerCarousel" class="carousel" role="region" aria-label="Carrusel de banners">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/Banner1.png') }}" 
                 alt="Banner promocional CADUxCOM" 
                 loading="eager"
                 width="1200" 
                 height="400"
                 class="carousel-image">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/banner2.jpeg') }}" 
                 alt="Ofertas especiales CADUxCOM" 
                 loading="lazy"
                 width="1200" 
                 height="400"
                 class="carousel-image">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/Banner3.jpg') }}" 
                 alt="Productos frescos CADUxCOM" 
                 loading="lazy"
                 width="1200" 
                 height="400"
                 class="carousel-image">
        </div>
    </div>

    <!-- Indicadores de posición -->
    <div class="carousel-indicators">
        <button class="indicator active" data-slide="0" aria-label="Ir a slide 1"></button>
        <button class="indicator" data-slide="1" aria-label="Ir a slide 2"></button>
        <button class="indicator" data-slide="2" aria-label="Ir a slide 3"></button>
    </div>

    <!-- Controles de navegación -->
    <button class="carousel-control prev" onclick="prevSlide()" aria-label="Imagen anterior">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button class="carousel-control next" onclick="nextSlide()" aria-label="Siguiente imagen">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    <!-- Loading spinner -->
    <div class="carousel-loading" id="carouselLoading">
        <div class="loading-spinner"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Variables del carrusel
        let currentIndex = 0;
        const carouselInner = document.querySelector('#bannerCarousel .carousel-inner');
        const slides = document.querySelectorAll('#bannerCarousel .carousel-item');
        const indicators = document.querySelectorAll('#bannerCarousel .indicator');
        const loadingElement = document.getElementById('carouselLoading');
        let autoPlayInterval;
        let startX = 0;
        let isAutoPlayActive = true;
        let isTransitioning = false;

        // Preload de imágenes para mejor rendimiento
        function preloadImages() {
            const images = document.querySelectorAll('.carousel-image');
            let loadedCount = 0;
            
            images.forEach((img, index) => {
                if (img.complete) {
                    loadedCount++;
                } else {
                    img.addEventListener('load', () => {
                        loadedCount++;
                        if (loadedCount === images.length) {
                            hideLoading();
                        }
                    });
                }
            });
            
            // Si todas las imágenes ya están cargadas
            if (loadedCount === images.length) {
                hideLoading();
            }
        }

        function hideLoading() {
            if (loadingElement) {
                loadingElement.style.opacity = '0';
                setTimeout(() => {
                    loadingElement.style.display = 'none';
                }, 300);
            }
        }

        function showSlide(index) {
            if (index < 0 || index >= slides.length || isTransitioning) return;
            
            isTransitioning = true;
            
            // Actualizar indicadores
            indicators.forEach(indicator => indicator.classList.remove('active'));
            if (indicators[index]) {
                indicators[index].classList.add('active');
            }
            
            // Transición suave
            carouselInner.style.transform = `translateX(-${index * 100}%)`;
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
            currentIndex = index;
            
            // Resetear flag de transición
            setTimeout(() => {
                isTransitioning = false;
            }, 500);
        }

        // Funciones globales optimizadas
        window.prevSlide = function () {
            if (isTransitioning) return;
            currentIndex = (currentIndex === 0) ? slides.length - 1 : currentIndex - 1;
            showSlide(currentIndex);
            resetAutoPlay();
        }

        window.nextSlide = function () {
            if (isTransitioning) return;
            currentIndex = (currentIndex === slides.length - 1) ? 0 : currentIndex + 1;
            showSlide(currentIndex);
            resetAutoPlay();
        }

        function resetAutoPlay() {
            if (isAutoPlayActive) {
                stopAutoPlay();
                startAutoPlay();
            }
        }

        function startAutoPlay() {
            if (isAutoPlayActive && !isTransitioning) {
                autoPlayInterval = setInterval(function() {
                    if (!isTransitioning) {
                        currentIndex = (currentIndex === slides.length - 1) ? 0 : currentIndex + 1;
                        showSlide(currentIndex);
                    }
                }, 6000); // Aumentado a 6 segundos para mejor UX
            }
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // Event listeners optimizados
        const carousel = document.getElementById('bannerCarousel');
        
        // Hover para pausar/reanudar
        carousel.addEventListener('mouseenter', stopAutoPlay);
        carousel.addEventListener('mouseleave', () => {
            if (isAutoPlayActive) startAutoPlay();
        });

        // Indicadores clickeables
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                if (!isTransitioning) {
                    currentIndex = index;
                    showSlide(currentIndex);
                    resetAutoPlay();
                }
            });
        });

        // Touch events optimizados
        carousel.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
            stopAutoPlay();
        }, { passive: true });

        carousel.addEventListener('touchend', function (e) {
            const endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            
            if (Math.abs(diff) > 50) { // Mínimo swipe de 50px
                if (diff > 0) {
                    window.nextSlide();
                } else {
                    window.prevSlide();
                }
            }
            
            if (isAutoPlayActive) {
                setTimeout(startAutoPlay, 1000); // Delay antes de reanudar
            }
        }, { passive: true });

        // Prevenir scroll vertical en el carrusel
        carousel.addEventListener('touchmove', function(e) {
            const touch = e.touches[0];
            const deltaY = Math.abs(touch.clientY - startX);
            const deltaX = Math.abs(touch.clientX - startX);
            
            if (deltaY > deltaX) {
                e.preventDefault();
            }
        }, { passive: false });

        // Keyboard navigation
        carousel.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    window.prevSlide();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    window.nextSlide();
                    break;
                case ' ':
                    e.preventDefault();
                    isAutoPlayActive = !isAutoPlayActive;
                    if (isAutoPlayActive) {
                        startAutoPlay();
                    } else {
                        stopAutoPlay();
                    }
                    break;
            }
        });

        // Hacer el carrusel enfocable
        carousel.setAttribute('tabindex', '0');

        // Visibility API para optimizar cuando la página no está visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoPlay();
            } else if (isAutoPlayActive) {
                startAutoPlay();
            }
        });

        // Intersection Observer para lazy loading optimizado
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // El carrusel es visible, asegurar que esté funcionando
                    if (isAutoPlayActive) {
                        startAutoPlay();
                    }
                } else {
                    // El carrusel no es visible, pausar autoplay
                    stopAutoPlay();
                }
            });
        }, { threshold: 0.1 });

        observer.observe(carousel);

        // Inicialización optimizada
        function initCarousel() {
            preloadImages();
            showSlide(currentIndex);
            
            // Delay inicial para mejor UX
            setTimeout(() => {
                if (isAutoPlayActive) {
                    startAutoPlay();
                }
            }, 1000);
        }

        // Inicializar el carrusel
        initCarousel();

        // Cleanup al salir de la página
        window.addEventListener('beforeunload', () => {
            stopAutoPlay();
            observer.disconnect();
        });
    });
</script>
