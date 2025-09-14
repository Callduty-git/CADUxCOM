<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>
<body>
<x-header-pages />

    

    <!-- HERO -->
    <section class="about-hero">
        <div class="hero-overlay fade-in">
            <!-- Logo -->
            <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="CADUxCOM Logo" class="about-logo fade-in">

            <!-- 👇 título con estilos mejorados -->
            <h1 class="about-title">
                <span class="sobre">Sobre</span> 
                <span class="cadu">CADU</span><span class="xcom">xCOM</span>
            </h1>  

            <p>Conectamos empresas y consumidores para reducir el desperdicio de alimentos, ofreciendo productos próximos a caducar a precios justos.</p>
            
        </div>
        
    </section>

    <div class="about-container">
        <section class="highlights fade-in">
            <div class="highlight-item">✅ Ahorra dinero</div>
            <div class="highlight-item">🌱 Reduce desperdicio</div>
            <div class="highlight-item">🚀 Accede a productos cercanos</div>
        </section>

        <!-- Misión -->
        <section class="about-mission diagonal fade-in">
            <h2>Nuestra Misión</h2>
            <p>En CADUxCOM tenemos la firme misión de transformar la manera en que Colombia consume y gestiona los alimentos, enfrentando uno de los retos más grandes de nuestro tiempo: el desperdicio alimentario.

            Buscamos ser un puente entre empresas y consumidores, a través de una plataforma segura, accesible y sostenible, que permita dar un nuevo propósito a los productos próximos a caducar, evitando que se pierdan y ofreciendo la oportunidad de adquirirlos a precios justos.

            Promovemos una cultura de consumo consciente, basada en la eficiencia, la solidaridad y el compromiso con el medio ambiente. Creemos que cada acción cuenta, y que al reducir el desperdicio generamos valor no solo para las empresas y los hogares, sino también para toda la sociedad.

            Nuestra misión es avanzar hacia un futuro donde la tecnología, la responsabilidad social y la sostenibilidad se unan para construir un modelo de consumo más humano, inclusivo y respetuoso con el planeta.</p>
        </section>

        <!-- Valores -->
        <section class="about-values fade-in">
            <h2>Valores</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🌱</div>
                    <h3>Sostenibilidad</h3>
                    <p>Promovemos un consumo consciente y responsable, protegiendo el medio ambiente.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🤝</div>
                    <h3>Confianza</h3>
                    <p>Brindamos seguridad en las transacciones y calidad en los productos.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">💡</div>
                    <h3>Innovación</h3>
                    <p>Ofrecemos soluciones digitales modernas para facilitar el acceso a alimentos.</p>
                </div>
            </div>
        </section>

        <!-- Equipo -->
        <section class="about-team diagonal fade-in">
            <h2>Nuestro Equipo</h2>
            <p>En CADUxCOM creemos que cada acción cuenta para construir un futuro más sostenible. Nuestro equipo está conformado por personas apasionadas, creativas y comprometidas con generar un cambio positivo en la forma en que consumimos alimentos.

            Unimos la innovación tecnológica con la responsabilidad social para crear un puente entre empresas y consumidores, facilitando el acceso a productos próximos a caducar a precios justos y evitando que toneladas de alimentos terminen desperdiciados.

            Trabajamos cada día con la convicción de que la tecnología no solo transforma mercados, sino también conciencias, promoviendo hábitos de consumo responsables que benefician tanto a la economía de las familias como al cuidado del planeta.</p>
        </section>
    </div>

    <!-- Script para animación fade-in -->
    <script>
        const faders = document.querySelectorAll('.fade-in');
        const appearOptions = {
            threshold: 0.2,
            rootMargin: "0px 0px -50px 0px"
        };

        const appearOnScroll = new IntersectionObserver(function(entries, observer){
            entries.forEach(entry => {
                if(!entry.isIntersecting) return;
                entry.target.classList.add('appear');
                observer.unobserve(entry.target);
            });
        }, appearOptions);

        faders.forEach(fader => {
            appearOnScroll.observe(fader);
        });
    </script>

<x-footer />
</body>
</html>
