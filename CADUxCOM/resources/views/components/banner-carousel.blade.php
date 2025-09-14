<div id="bannerCarousel" class="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/Banner1.png') }}" alt="Banner 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/banner2.jpeg') }}" alt="Banner 2"
                onerror="this.style.border='3px solid red'; console.error('No se pudo cargar banner2.jpg');">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/Banner3.jpg') }}" alt="Banner 3">
        </div>
    </div>

    <button class="carousel-control prev" onclick="prevSlide()">&#10094;</button>
    <button class="carousel-control next" onclick="nextSlide()">&#10095;</button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentIndex = 0;
        const carouselInner = document.querySelector('#bannerCarousel .carousel-inner');
        const slides = document.querySelectorAll('#bannerCarousel .carousel-item');
        let autoPlayInterval;
        let startX = 0;

        function showSlide(index) {
            if (index < 0 || index >= slides.length) return;
            carouselInner.style.transform = `translateX(-${index * 100}%)`;
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }

        window.prevSlide = function () {
            currentIndex = (currentIndex === 0) ? slides.length - 1 : currentIndex - 1;
            showSlide(currentIndex);
        }

        window.nextSlide = function () {
            currentIndex = (currentIndex === slides.length - 1) ? 0 : currentIndex + 1;
            showSlide(currentIndex);
        }

        function startAutoPlay() {
            autoPlayInterval = setInterval(window.nextSlide, 5000);
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        document.getElementById('bannerCarousel').addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
            stopAutoPlay();
        });

        document.getElementById('bannerCarousel').addEventListener('touchend', function (e) {
            let endX = e.changedTouches[0].clientX;
            if (startX - endX > 50) {
                window.nextSlide();
            } else if (endX - startX > 50) {
                window.prevSlide();
            }
            startAutoPlay();
        });

        showSlide(currentIndex);
        startAutoPlay();
    });
</script>
