<div id="bannerCarousel" class="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/Banner1.png') }}" alt="Banner 1" loading="lazy">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/banner2.jpeg') }}" alt="Banner 2" loading="lazy">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/Banner3.jpg') }}" alt="Banner 3" loading="lazy">
        </div>
    </div>

    <button class="carousel-control prev" onclick="prevSlide()" aria-label="Imagen anterior">&#10094;</button>
    <button class="carousel-control next" onclick="nextSlide()" aria-label="Siguiente imagen">&#10095;</button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentIndex = 0;
        const carouselInner = document.querySelector('#bannerCarousel .carousel-inner');
        const slides = document.querySelectorAll('#bannerCarousel .carousel-item');
        let autoPlayInterval;
        let startX = 0;
        let isAutoPlayActive = true;

        function showSlide(index) {
            if (index < 0 || index >= slides.length) return;
            console.log('Mostrando slide:', index, 'de', slides.length);
            carouselInner.style.transform = `translateX(-${index * 100}%)`;
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
            currentIndex = index;
        }

        window.prevSlide = function () {
            currentIndex = (currentIndex === 0) ? slides.length - 1 : currentIndex - 1;
            showSlide(currentIndex);
            // Reiniciar autoplay después de navegación manual
            if (isAutoPlayActive) {
                stopAutoPlay();
                startAutoPlay();
            }
        }

        window.nextSlide = function () {
            currentIndex = (currentIndex === slides.length - 1) ? 0 : currentIndex + 1;
            showSlide(currentIndex);
            // Reiniciar autoplay después de navegación manual
            if (isAutoPlayActive) {
                stopAutoPlay();
                startAutoPlay();
            }
        }

        function startAutoPlay() {
            if (isAutoPlayActive) {
                console.log('Iniciando autoplay del carrusel');
                autoPlayInterval = setInterval(function() {
                    currentIndex = (currentIndex === slides.length - 1) ? 0 : currentIndex + 1;
                    console.log('Cambiando a slide:', currentIndex);
                    showSlide(currentIndex);
                }, 5000);
            }
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // Eventos de mouse para pausar/reanudar autoplay
        const carousel = document.getElementById('bannerCarousel');
        
        carousel.addEventListener('mouseenter', function() {
            stopAutoPlay();
        });

        carousel.addEventListener('mouseleave', function() {
            if (isAutoPlayActive) {
                startAutoPlay();
            }
        });

        // Eventos táctiles corregidos
        carousel.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
            stopAutoPlay();
        });

        carousel.addEventListener('touchend', function (e) {
            let endX = e.changedTouches[0].clientX;
            if (startX - endX > 50) {
                window.nextSlide();
            } else if (endX - startX > 50) {
                window.prevSlide();
            }
            // Reiniciar autoplay después del toque
            if (isAutoPlayActive) {
                startAutoPlay();
            }
        });

        // Prevenir scroll vertical pero permitir horizontal
        carousel.addEventListener('touchmove', function(e) {
            // Solo prevenir si es un gesto vertical
            const touch = e.touches[0];
            const deltaY = Math.abs(touch.clientY - startX);
            const deltaX = Math.abs(touch.clientX - startX);
            
            if (deltaY > deltaX) {
                e.preventDefault(); // Prevenir scroll vertical
            }
        }, { passive: false });

        // Prevenir scroll vertical con rueda del mouse
        carousel.addEventListener('wheel', function(e) {
            if (e.deltaY !== 0) {
                e.preventDefault(); // Solo prevenir scroll vertical
            }
        }, { passive: false });

        // Prevenir scroll con teclado (flechas, espacio, etc.)
        carousel.addEventListener('keydown', function(e) {
            if ([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
                e.preventDefault();
            }
        });

        // Hacer el carrusel enfocable para eventos de teclado
        carousel.setAttribute('tabindex', '0');

        // Inicializar el carrusel
        showSlide(currentIndex);
        startAutoPlay();

        // Limpiar interval cuando la página se oculta
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoPlay();
            } else if (isAutoPlayActive) {
                startAutoPlay();
            }
        });
    });
</script>
