<?php
// Capturar el nombre del piloto enviado desde el formulario de index.php o asignar por defecto
$nombrePiloto = isset($_POST['piloto']) && !empty(trim($_POST['piloto'])) ? htmlspecialchars($_POST['piloto']) : 'Piloto Apex';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Moto | Performance Configurator</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: #060609;
            color: #ffffff;
            overflow-x: hidden;
            padding: 40px 20px;
            background-image: linear-gradient(rgba(230, 0, 0, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(230, 0, 0, 0.015) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        .config-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 50px auto;
        }

        .config-header h1 {
            font-size: 3rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .config-header h1 span {
            color: #e60000;
            text-shadow: 0 0 20px rgba(230, 0, 0, 0.6);
        }

        .config-header p {
            color: #71717a;
            margin-top: 10px;
            font-size: 1.1rem;
        }

        .mode-selector-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 50px;
            background: rgba(18, 18, 24, 0.6);
            padding: 15px 30px;
            border-radius: 50px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .mode-label {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.9rem;
        }

        .mode-label.street {
            color: #00ccff;
            text-shadow: 0 0 10px rgba(0, 204, 255, 0.2);
        }

        .mode-label.track {
            color: #555;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 70px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #00ccff;
            transition: .4s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 34px;
            box-shadow: 0 0 15px rgba(0, 204, 255, 0.4);
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #e60000;
            box-shadow: 0 0 25px rgba(230, 0, 0, 0.7);
        }

        input:checked+.slider:before {
            transform: translateX(36px);
        }

        body:has(input:checked) .mode-label.street {
            color: #555;
            text-shadow: none;
        }

        body:has(input:checked) .mode-label.track {
            color: #e60000;
            text-shadow: 0 0 15px rgba(230, 0, 0, 0.5);
        }

        .configurador-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .visual-panel {
            position: relative;
            background: linear-gradient(145deg, #0f0f15, #08080c);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }

        .bike-display {
            width: 100%;
            height: 380px;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            transition: transform 0.5s ease, filter 0.3s;
            clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
        }

        body:has(input:checked) .bike-display {
            filter: saturate(1.2) contrast(1.1);
        }

        .hud-overlay {
            position: absolute;
            top: 50px;
            left: 50px;
            font-family: monospace;
            color: #00ccff;
            font-size: 0.9rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        body:has(input:checked) .hud-overlay {
            color: #e60000;
        }

        .telemetry-panel {
            background: rgba(14, 14, 20, 0.8);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
        }

        .telemetry-title {
            font-size: 1.5rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            border-left: 4px solid #00ccff;
            padding-left: 15px;
        }

        .telemetry-subtitle {
            color: #e60000;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            padding-left: 19px;
        }

        body:has(input:checked) .telemetry-title {
            border-color: #e60000;
        }

        .stat-group {
            margin-bottom: 25px;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            margin-bottom: 8px;
            color: #a0a0aa;
        }

        .stat-counter {
            font-size: 1.2rem;
            font-weight: 900;
            color: #ffffff;
            font-family: monospace;
        }

        .progress-bg {
            width: 100%;
            height: 8px;
            background-color: #1c1c24;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #0099ff, #00ccff);
            border-radius: 4px;
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 0 10px rgba(0, 204, 255, 0.5);
        }

        body:has(input:checked) .progress-bar {
            background: linear-gradient(90deg, #b30000, #e60000);
            box-shadow: 0 0 15px rgba(230, 0, 0, 0.6);
        }

        .action-box {
            margin-top: 40px;
        }

        .btn-reserve {
            width: 100%;
            background: transparent;
            border: 2px solid #00ccff;
            color: #ffffff;
            padding: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.95rem;
            cursor: pointer;
            clip-path: polygon(6% 0, 100% 0, 94% 100%, 0 100%);
            transition: all 0.3s;
        }

        .btn-reserve:hover {
            background-color: #00ccff;
            color: #060609;
            box-shadow: 0 0 25px rgba(0, 204, 255, 0.5);
            transform: scale(1.02);
        }

        body:has(input:checked) .btn-reserve {
            border-color: #e60000;
        }

        body:has(input:checked) .btn-reserve:hover {
            background-color: #e60000;
            color: #ffffff;
            box-shadow: 0 0 30px rgba(230, 0, 0, 0.6);
            transform: scale(1.02);
        }

        .btn-back {
            display: inline-block;
            color: #71717a;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            margin-bottom: 20px;
            transition: color 0.3s;
        }

        .btn-back:hover {
            color: #e60000;
        }

        @media (max-width: 900px) {
            .configurador-container {
                grid-template-columns: 1fr;
            }

            .bike-display {
                height: 260px;
            }
        }
    </style>
</head>

<body>

    <a href="modelos.php" class="btn-back">← Volver al Catálogo</a>

    <div class="config-header">
        <h1 id="mainTitle">Apex <span>Engine Lab</span></h1>
        <p>Configuración de mapeo electrónico y telemetría de motor en tiempo real.</p>
    </div>

    <!-- SWITCH DE ENTORNO -->
    <div class="mode-selector-container">
        <span class="mode-label street">Street Mode</span>
        <label class="switch">
            <input type="checkbox" id="modeToggle">
            <span class="slider"></span>
        </label>
        <span class="mode-label track">Track Mode</span>
    </div>

    <!-- WORKSPACE -->
    <main class="configurador-container">
        <div class="visual-panel">
            <div class="hud-overlay" id="hudStatus">SYS_STATUS: OPTIMAL_STREET</div>
            <div class="bike-display" id="bikeVisual"></div>
        </div>

        <div class="telemetry-panel">
            <div class="telemetry-title" id="panelTitle">Mapeo de Inyección ECU</div>
            <div class="telemetry-subtitle">Piloto: <?php echo $nombrePiloto; ?></div>

            <div class="stat-group">
                <div class="stat-header">
                    <span>Potencia de Motor</span>
                    <span class="stat-counter" id="hpCounter">0 HP</span>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar" id="hpBar" style="width: 0%;"></div>
                </div>
            </div>

            <div class="stat-group">
                <div class="stat-header">
                    <span>Velocidad Límite Estimada</span>
                    <span class="stat-counter" id="speedCounter">0 KM/H</span>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar" id="speedBar" style="width: 0%;"></div>
                </div>
            </div>

            <div class="stat-group">
                <div class="stat-header">
                    <span>Asistencia Electrónica (TCS)</span>
                    <span class="stat-counter" id="tcsCounter">Nivel 0</span>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar" id="tcsBar" style="width: 0%;"></div>
                </div>
            </div>

            <div class="action-box">
                <button class="btn-reserve" id="actionBtn">Cargando Mapeo...</button>
            </div>
        </div>
    </main>

    <!-- LÓGICA DE DETECCIÓN Y TELEMETRÍA DINÁMICA -->
    <script>
        // 1. Base de datos mapeada con los slugs exactos de modelos.php
        const motosData = {
            r1: {
                nombre: "R1 Phantom",
                imagen: "https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?q=80&w=800&auto=format&fit=crop",
                street: { hp: 145, speed: 258, tcs: "Nivel 4 / Máximo", hpPct: "65%", speedPct: "72%", tcsPct: "90%" },
                track: { hp: 205, speed: 299, tcs: "Nivel 1 / Pro Track", hpPct: "95%", speedPct: "92%", tcsPct: "25%" }
            },
            xzeta: {
                nombre: "X-Zeta 6R",
                imagen: "https://autogazette.de/wp-content/uploads/2023/06/Ninja-ZX-6R.jpg",
                street: { hp: 95, speed: 210, tcs: "Nivel 3 / Seguro", hpPct: "50%", speedPct: "60%", tcsPct: "80%" },
                track: { hp: 130, speed: 265, tcs: "Nivel 1 / Experto", hpPct: "75%", speedPct: "82%", tcsPct: "30%" }
            },
            hyper: {
                nombre: "HyperDuke 900",
                imagen: "https://s7g10.scene7.com/is/image/ktm/MY24%20KTM%201390%20SUPER%20DUKE%20R_EVO-4?wid=1000&dpr=off",
                street: { hp: 110, speed: 225, tcs: "Nivel 4 / Urbano", hpPct: "55%", speedPct: "65%", tcsPct: "85%" },
                track: { hp: 145, speed: 250, tcs: "Nivel 2 / Sport", hpPct: "72%", speedPct: "78%", tcsPct: "45%" }
            },
            strada: {
                nombre: "Strada RR V4",
                imagen: "https://bikereview.com.au/wp-content/uploads/2025/11/BikeReview-CFMOTO-V4-SR-RR-19.jpg",
                street: { hp: 155, speed: 270, tcs: "Nivel 5 / Touring", hpPct: "70%", speedPct: "75%", tcsPct: "95%" },
                track: { hp: 215, speed: 312, tcs: "Nivel 2 / Fast Track", hpPct: "100%", speedPct: "100%", tcsPct: "35%" }
            }
        };

        // 2. Leer los parámetros de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const motoSeleccionada = urlParams.get('moto') || 'r1';

        // Obtener la moto correspondiente
        const motoActual = motosData[motoSeleccionada] || motosData.r1;

        // 3. Inicializar elementos del DOM
        const toggle = document.getElementById('modeToggle');
        const mainTitle = document.getElementById('mainTitle');
        const bikeVisual = document.getElementById('bikeVisual');
        const hudStatus = document.getElementById('hudStatus');
        const panelTitle = document.getElementById('panelTitle');
        const hpCounter = document.getElementById('hpCounter');
        const speedCounter = document.getElementById('speedCounter');
        const tcsCounter = document.getElementById('tcsCounter');
        const actionBtn = document.getElementById('actionBtn');
        const hpBar = document.getElementById('hpBar');
        const speedBar = document.getElementById('speedBar');
        const tcsBar = document.getElementById('tcsBar');

        // Asignar datos de cabecera
        mainTitle.innerHTML = `${motoActual.nombre} <span>Engine Lab</span>`;
        bikeVisual.style.backgroundImage = `url('${motoActual.imagen}')`;

        // 4. Actualización dinámica
        function actualizarTelemetria() {
            const modo = toggle.checked ? 'track' : 'street';
            const datos = motoActual[modo];

            if (modo === 'track') {
                hudStatus.innerText = "SYS_STATUS: TRACK_LIMITLESS_RAW";
                panelTitle.innerText = "Mapeo Racing Desbloqueado";
                actionBtn.innerText = "Inyectar Mapeo Track";
            } else {
                hudStatus.innerText = "SYS_STATUS: OPTIMAL_STREET";
                panelTitle.innerText = "Mapeo de Inyección ECU";
                actionBtn.innerText = "Bloquear Mapeo Street";
            }

            hpCounter.innerText = `${datos.hp} HP`;
            speedCounter.innerText = `${datos.speed} KM/H`;
            tcsCounter.innerText = datos.tcs;

            hpBar.style.width = datos.hpPct;
            speedBar.style.width = datos.speedPct;
            tcsBar.style.width = datos.tcsPct;
        }

        toggle.addEventListener('change', actualizarTelemetria);
        actualizarTelemetria();
    </script>
</body>

</html>