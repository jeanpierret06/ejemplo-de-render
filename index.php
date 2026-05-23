<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Moto | Ultra Premium</title>

    <!-- Importación de Bootstrap para componentes e interactividad -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* CONFIGURACIÓN GENERAL Y TEXTURAS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Inter, sans-serif;
        }

        body {
            background-color: #08080c;
            color: #ffffff;
            scroll-behavior: smooth;
            overflow-x: hidden;
            background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* ANIMACIONES CORE */
        @keyframes JoltIn {
            0% {
                opacity: 0;
                transform: translateY(30px) skewX(-5deg);
            }
            100% {
                opacity: 1;
                transform: translateY(0) skewX(0deg);
            }
        }

        @keyframes BlurReveal {
            0% {
                filter: blur(10px);
                opacity: 0;
            }
            100% {
                filter: blur(0);
                opacity: 1;
            }
        }

        /* NAVBAR CON EFECTO BLUR (FROSTED GLASS) */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 8%;
            background-color: rgba(8, 8, 12, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(230, 0, 0, 0.2);
        }

        .logo {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 3px;
            font-style: italic;
            text-transform: uppercase;
        }

        .logo span {
            color: #e60000;
            text-shadow: 0 0 10px rgba(230, 0, 0, 0.6);
        }

        .nav-links {
            display: flex;
            list-style: none;
            margin-bottom: 0; /* Corrección de margen Bootstrap */
            align-items: center;
        }

        .nav-links li {
            margin: 0 20px;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: color 0.3s, text-shadow 0.3s;
        }

        .nav-links a:hover {
            color: #e60000;
            text-shadow: 0 0 8px rgba(230, 0, 0, 0.5);
        }

        .btn-nav {
            border: 2px solid #e60000;
            padding: 10px 24px;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%);
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-nav:hover {
            background-color: #e60000;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(230, 0, 0, 0.6);
            transform: scale(1.05);
        }

        /* HERO SECTION CONFIGURADA */
        .hero {
            min-height: 100vh;
            background-image: url('https://wallpapers.com/images/hd/1920x1080-hd-bikes-kawasaki-z1000-ovtfljc00eeve3hw.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 140px 20px 80px 20px;
            clip-path: polygon(0 0, 100% 0, 100% 92vh, 0 100%);
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(8, 8, 12, 0.9), rgba(8, 8, 12, 0.4)),
                linear-gradient(to bottom, transparent 50%, #08080c 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            width: 100%;
        }

        .hero-content h1 {
            font-size: 4.5rem;
            font-weight: 900;
            letter-spacing: 4px;
            margin-bottom: 20px;
            line-height: 1.1;
            font-style: italic;
            text-transform: uppercase;
            animation: JoltIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero-content h1 span {
            color: #e60000;
            text-shadow: 0 0 20px rgba(230, 0, 0, 0.4);
            display: inline-block;
        }

        .hero-content p {
            font-size: 1.2rem;
            color: #cccccc;
            margin-bottom: 35px;
            line-height: 1.6;
            animation: BlurReveal 1.2s ease forwards;
        }

        /* FORMULARIO UNIFICADO PREMIUM */
        .php-form-box {
            animation: BlurReveal 1.5s ease forwards;
            background: rgba(18, 18, 24, 0.75);
            border: 1px solid rgba(230, 0, 0, 0.25);
            backdrop-filter: blur(12px);
            padding: 35px;
            border-radius: 16px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6);
        }

        .input-pilot {
            padding: 14px;
            width: 100%;
            background: #08080c;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: white;
            text-transform: uppercase;
            text-align: center;
            font-weight: 700;
            letter-spacing: 1.5px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .input-pilot:focus {
            outline: none;
            border-color: #e60000;
            box-shadow: 0 0 12px rgba(230, 0, 0, 0.4);
        }

        .btn-submit-php {
            width: 100%;
            background-color: #e60000;
            color: #ffffff;
            border: none;
            padding: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            clip-path: polygon(6% 0, 100% 0%, 94% 100%, 0% 100%);
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(230, 0, 0, 0.3);
        }

        .btn-submit-php:hover {
            background-color: #ff1a1a;
            box-shadow: 0 0 25px rgba(230, 0, 0, 0.6);
            transform: scale(1.02);
        }

        /* SECCIÓN TARJETAS */
        .features {
            padding: 60px 8% 120px 8%;
            position: relative;
        }

        .section-title {
            font-size: 2.8rem;
            text-align: center;
            margin-bottom: 60px;
            letter-spacing: 3px;
            font-style: italic;
            font-weight: 900;
            text-transform: uppercase;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: #e60000;
            margin: 15px auto 0 auto;
            box-shadow: 0 0 10px rgba(230, 0, 0, 0.6);
            transform: skewX(-20deg);
        }

        .grid-motos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
        }

        .moto-card {
            background: linear-gradient(145deg, #121218, #0a0a0f);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .moto-card:hover {
            transform: translateY(-10px);
            border-color: rgba(230, 0, 0, 0.5);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 15px rgba(230, 0, 0, 0.15);
        }

        .moto-img-container {
            overflow: hidden;
            height: 240px;
            position: relative;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
        }

        .moto-img {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .moto-card:hover .moto-img {
            transform: scale(1.1) rotate(-1deg);
        }

        .moto-info {
            padding: 30px;
            position: relative;
        }

        .moto-info h3 {
            font-size: 1.6rem;
            margin-bottom: 12px;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .moto-info p {
            color: #a0a0aa;
            font-size: 1rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .btn-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            display: inline-block;
            transition: color 0.3s, transform 0.3s;
        }

        .btn-link span {
            color: #e60000;
            display: inline-block;
            transition: transform 0.3s;
        }

        .moto-card:hover .btn-link {
            color: #e60000;
        }

        .moto-card:hover .btn-link span {
            transform: translateX(8px);
        }

        footer {
            text-align: center;
            padding: 50px;
            background-color: #040406;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            color: #52525b;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 20px 5%;
            }
            .nav-links {
                display: none;
            }
            .hero-content h1 {
                font-size: 3rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAV BAR LIMPIA Y PREMIUM -->
    <header class="navbar">
        <div class="logo">APEX<span>MOTO</span></div>
        <nav>
            <ul class="nav-links">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="views/modelos.php">Modelos</a></li>
                <li><a href="#modelos">Fichas Técnicas</a></li>
            </ul>
        </nav>
        <!-- Botón de navegación directo a la sección de juego/ingreso -->
        <a href="#inicio" class="btn-nav">Misión Piloto</a>
    </header>

    <!-- HERO SECTION -->
    <section id="inicio" class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>SIENTE LA <span>ADRENALINA</span></h1>
            <p>Registra tu nombre de piloto y selecciona tu destino: explora nuestro catálogo exclusivo o pon a prueba tu memoria en el circuito Apex Match.</p>

            <!-- FORMULARIO UNIFICADO MULTIDESTINO -->
            <div class="php-form-box">
                <form id="pilotForm" method="POST" action="views/modelos.php">
                    <!-- Input de Nombre Estilizado -->
                    <div class="mb-3">
                        <input type="text" name="nombre" class="input-pilot" placeholder="TU NOMBRE DE PILOTO" required autocomplete="off">
                    </div>
                    
                    <!-- Selector de Destino Premium integrado con Bootstrap -->
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold tracking-wide d-block mb-2">SELECCIONA TU OBJETIVO</label>
                        <select id="destinationSelect" class="form-select bg-dark text-white border-secondary text-center fw-bold" style="letter-spacing: 1px;">
                            <option value="views/modelos.php">VER CATÁLOGO DE MODELOS</option>
                            <option value="views/juegos.php">INICIAR DESAFÍO DE MEMORIA</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit-php">
                        INICIAR SECUENCIA →
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- DESTACADOS -->
    <section id="modelos" class="features">
        <h2 class="section-title">LO ÚLTIMO EN LA PISTA</h2>
        <div class="grid-motos">
            <div class="moto-card">
                <div class="moto-img-container">
                    <div class="moto-img" style="background-image: url('https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?q=80&w=600&auto=format&fit=crop');"></div>
                </div>
                <div class="moto-info">
                    <h3>Superbike 1000cc</h3>
                    <p>Potencia pura y aerodinámica radical heredada directamente de los circuitos de MotoGP.</p>
                    <a href="views/modelos.php" class="btn-link">Ficha Técnica <span>→</span></a>
                </div>
            </div>
            <div class="moto-card">
                <div class="moto-img-container">
                    <div class="moto-img" style="background-image: url('https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?q=80&w=600&auto=format&fit=crop');"></div>
                </div>
                <div class="moto-info">
                    <h3>Naked R 600cc</h3>
                    <p>Agilidad urbana agresiva con la aceleración furiosa de un motor de carreras de altas RPM.</p>
                    <a href="views/modelos.php" class="btn-link">Ficha Técnica <span>→</span></a>
                </div>
            </div>
            <div class="moto-card">
                <div class="moto-img-container">
                    <div class="moto-img" style="background-image: url('https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?q=80&w=600&auto=format&fit=crop');"></div>
                </div>
                <div class="moto-info">
                    <h3>Hyperbike Turbo</h3>
                    <p>Rompiendo las leyes de la física y los límites de la velocidad máxima en línea recta.</p>
                    <a href="views/modelos.php" class="btn-link">Ficha Técnica <span>→</span></a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 APEXMOTO. Diseñado para los apasionados de la velocidad extrema.</p>
    </footer>

    <!-- INTERACCIÓN DINÁMICA DE ENRUTAMIENTO -->
    <script>
        const form = document.getElementById('pilotForm');
        const select = document.getElementById('destinationSelect');

        // Escucha el cambio del selector y altera dinámicamente el action del formulario
        select.addEventListener('change', function() {
            form.action = this.value;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>