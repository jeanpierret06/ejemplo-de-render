<?php
// Capturamos el nombre del piloto que viene desde el index.html
$piloto = isset($_POST['nombre']) && !empty(trim($_POST['nombre'])) ? htmlspecialchars($_POST['nombre']) : 'Anuel';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Speed Racer | Nitro Edition</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Inter, sans-serif;
        }

        body {
            background-color: #040406;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-image: radial-gradient(circle at center, #0c0c16 0%, #040406 100%);
        }

        /* HUD SUPERIOR EXPANDIDO */
        .game-header {
            width: 100%;
            max-width: 850px; /* Expandido para verse más grande */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 25px;
            background: rgba(14, 14, 20, 0.9);
            border: 2px solid rgba(230, 0, 0, 0.4);
            border-bottom: none;
            border-radius: 16px 16px 0 0;
            backdrop-filter: blur(12px);
        }

        .pilot-tag {
            font-size: 1.1rem;
            font-weight: 900;
            font-style: italic;
            color: #ff003c;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(255, 0, 60, 0.3);
        }

        .score-box {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        /* CONTENEDOR DE JUEGO CINEMATOGRÁFICO */
        #gameContainer {
            position: relative;
            width: 100%;
            max-width: 850px; /* MUCHO MÁS GRANDE Y ANCHO */
            height: 80vh;
            max-height: 750px;
            border: 2px solid #e60000;
            box-shadow: 0 0 40px rgba(230, 0, 0, 0.35);
            border-radius: 0 0 16px 16px;
            overflow: hidden;
        }

        canvas {
            width: 100%;
            height: 100%;
            display: block;
            background-color: #06060a;
        }

        /* PANTALLAS SUPERPUESTAS */
        .overlay-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(6, 6, 10, 0.92);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
            z-index: 10;
        }

        .hidden {
            display: none !important;
        }

        .overlay-screen h2 {
            font-size: 3rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .text-neon-red {
            color: #ff003c;
            text-shadow: 0 0 20px rgba(255, 0, 60, 0.7);
        }

        .text-neon-cyan {
            color: #00f3ff;
            text-shadow: 0 0 20px rgba(0, 243, 255, 0.7);
        }

        .btn-apex {
            background: #e60000;
            color: #fff;
            border: none;
            padding: 15px 45px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 25px;
            box-shadow: 0 5px 15px rgba(230, 0, 0, 0.4);
        }

        .btn-apex:hover {
            background: #ff1a1a;
            box-shadow: 0 0 30px rgba(230, 0, 0, 0.8);
            transform: scale(1.05) skewX(-2deg);
        }

        .controls-hint {
            color: #52526b;
            font-size: 0.9rem;
            margin-top: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
        }

        /* Barra indicadora visual de poder de Nitro en el HUD */
        .nitro-bar-container {
            width: 140px;
            height: 10px;
            background: #1f1f2e;
            border-radius: 4px;
            overflow: hidden;
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            border: 1px solid rgba(0, 243, 255, 0.3);
        }
        .nitro-bar-fill {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #00bcff, #00f3ff);
            box-shadow: 0 0 8px #00f3ff;
            transition: width 0.1s linear;
        }
    </style>
</head>
<body>

    <!-- HUD SUPERIOR MÁS GRANDE -->
    <div class="game-header">
        <div class="pilot-tag">⚡ PILOTO: <span id="pilotName"><?php echo $piloto; ?></span></div>
        <div class="score-box text-end">
            <span class="text-muted small fw-bold">NITRO MODE:</span>
            <div class="nitro-bar-container"><div id="nitroBar" class="nitro-bar-fill"></div></div>
            <span class="text-muted small">KM:</span> <span id="liveScore" class="text-warning">0</span>
        </div>
    </div>

    <!-- ÁREA DE JUEGO TOTALMENTE AMPLIADA -->
    <div id="gameContainer">
        <canvas id="raceCanvas"></canvas>

        <!-- Pantalla de Inicio -->
        <div id="startScreen" class="overlay-screen">
            <h2 class="text-white mb-3">OVERDRIVE ACTIVADO</h2>
            <p class="text-muted px-5" style="max-width: 600px;">Controla la Superbike modificada. Recoge los núcleos de energía cian para activar el <span class="text-neon-cyan fw-bold">MODO NITRO</span>: serás invulnerable y destruirás cualquier coche a tu paso.</p>
            <button class="btn-apex" onclick="startGame()">Encender Motores</button>
            <div class="controls-hint">Mueve el mouse lateralmente o usa las flechas ◄ ►</div>
        </div>

        <!-- Pantalla de Game Over -->
        <div id="gameOverScreen" class="overlay-screen hidden">
            <h2 class="text-neon-red mb-2">¡IMPACTO CRÍTICO!</h2>
            <p class="text-muted small mb-4">Tu motocicleta ha sufrido daños estructurales irreparables en la autopista.</p>
            <div class="fs-4 mb-2">Distancia recorrida: <span id="finalScore" class="fw-bold text-white">0</span> KM</div>
            <div class="fs-5 mb-4">Tu Récord Máximo: <span id="highScore" class="fw-bold text-success">0</span> KM</div>
            <button class="btn-apex" onclick="resetGame()">Reiniciar Circuito</button>
            <a href="index.html" class="text-muted d-block mt-4 small text-decoration-none" style="letter-spacing:1px;">← Volver al Menú Principal</a>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('raceCanvas');
        const ctx = canvas.getContext('2d');
        const pilotName = "<?php echo $piloto; ?>";

        function resizeCanvas() {
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = canvas.parentElement.clientHeight;
        }
        resizeCanvas();

        // CONFIGURACIONES CORE
        let gameActive = false;
        let score = 0;
        let speed = 8;
        const maxSpeed = 24; 
        let roadOffset = 0;
        let obstacles = [];
        let powers = []; // Contenedor de items de superpoderes
        let particles = [];
        let popups = []; 
        let lastSpawnTime = 0;
        let lastPowerSpawnTime = 0;

        // SISTEMA DE PODER (NITRO)
        let nitroActive = false;
        let nitroTimeLeft = 0;
        const maxNitroDuration = 200; // fotogramas (~3.5 seg)

        // FÍSICAS REFINADAS DEL JUGADOR
        const player = {
            x: canvas.width / 2,
            y: canvas.height - 140,
            width: 36,  // Moto un poco más grande y detallada
            height: 85,
            targetX: canvas.width / 2,
            easing: 0.20,
            animFrame: 0 // Para la animación de las ráfagas de fuego del escape
        };

        // CONTROLES INTERACTIVOS INTERFAZ
        canvas.addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            player.targetX = e.clientX - rect.left - player.width / 2;
        });

        canvas.addEventListener('touchmove', (e) => {
            const rect = canvas.getBoundingClientRect();
            if(e.touches[0]) {
                player.targetX = e.touches[0].clientX - rect.left - player.width / 2;
            }
        }, { passive: true });

        window.addEventListener('keydown', (e) => {
            if (!gameActive) return;
            if (e.key === 'ArrowLeft') player.targetX -= 70;
            if (e.key === 'ArrowRight') player.targetX += 70;
        });

        // DIBUJAR PISTA ANCHA CINEMATOGRÁFICA
        function drawRoad() {
            ctx.fillStyle = '#050508';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Márgenes de carretera con resplandor cyberpunk
            ctx.fillStyle = nitroActive ? '#00f3ff' : '#e60000';
            ctx.fillRect(40, 0, 6, canvas.height);
            ctx.fillRect(canvas.width - 46, 0, 6, canvas.height);

            roadOffset += speed;
            if (roadOffset >= 90) roadOffset = 0;

            // División de 3 carriles anchos espaciosos
            const laneWidth = (canvas.width - 92) / 3;
            ctx.fillStyle = nitroActive ? 'rgba(0, 243, 255, 0.15)' : 'rgba(255, 255, 255, 0.1)';
            
            for (let i = -1; i < (canvas.height / 90) + 1; i++) {
                const yPos = i * 90 + roadOffset;
                ctx.fillRect(40 + laneWidth, yPos, 3, 50);
                ctx.fillRect(40 + laneWidth * 2, yPos, 3, 50);
            }
        }

        // RENDERIZADO AVANZADO DE LA MOTO (¡MÁS HERMOSA Y DETALLADA!)
        function drawPlayer() {
            player.x += (player.targetX - player.x) * player.easing;
            player.animFrame++;

            // Límites adaptados a la pista ancha
            if (player.x < 48) player.x = 48;
            if (player.x > canvas.width - 48 - player.width) player.x = canvas.width - 48 - player.width;

            ctx.save();
            ctx.translate(player.x + player.width / 2, player.y + player.height / 2);

            const tilt = (player.targetX - player.x) * 0.04;
            ctx.rotate(Math.max(-0.25, Math.min(0.25, tilt)));

            // 1. ANIMACIÓN DE PROPULSORES / FUEGO DE ESCAPE DE NEÓN
            const fireLength = nitroActive ? 45 + Math.sin(player.animFrame * 0.5) * 15 : 20 + Math.sin(player.animFrame * 0.8) * 6;
            const fireGradient = ctx.createLinearGradient(0, player.height/2, 0, player.height/2 + fireLength);
            
            if (nitroActive) {
                fireGradient.addColorStop(0, '#ffffff');
                fireGradient.addColorStop(0.3, '#00f3ff');
                fireGradient.addColorStop(1, 'rgba(0, 243, 255, 0)');
            } else {
                fireGradient.addColorStop(0, '#ffea00');
                fireGradient.addColorStop(0.4, '#ff3300');
                fireGradient.addColorStop(1, 'rgba(255, 45, 0, 0)');
            }

            ctx.fillStyle = fireGradient;
            // Doble escape trasero deportivo
            ctx.fillRect(-10, player.height / 2 - 10, 5, fireLength);
            ctx.fillRect(5, player.height / 2 - 10, 5, fireLength);

            // 2. CUERPO CENTRAL DE LA MOTO PREMIUM
            ctx.fillStyle = '#111116'; // Chasis base interno oscurecido
            ctx.fillRect(-7, -player.height/2 + 5, 14, player.height - 15);

            // Carenado Deportivo Superior (Color de la carrocería)
            ctx.fillStyle = nitroActive ? '#00f3ff' : '#ff003c';
            ctx.beginPath();
            ctx.moveTo(-11, -player.height/2 + 15);
            ctx.lineTo(11, -player.height/2 + 15);
            ctx.lineTo(14, player.height/2 - 20);
            ctx.lineTo(-14, player.height/2 - 20);
            ctx.fill();

            // Alerones aerodinámicos laterales
            ctx.fillStyle = nitroActive ? '#009bb3' : '#a60027';
            ctx.beginPath();
            ctx.moveTo(-11, -20);
            ctx.lineTo(-17, 5);
            ctx.lineTo(-11, 15);
            ctx.fill();
            ctx.beginPath();
            ctx.moveTo(11, -20);
            ctx.lineTo(17, 5);
            ctx.lineTo(11, 15);
            ctx.fill();

            // Parabrisas reflectivo cristalino cian
            ctx.fillStyle = '#00ffff';
            ctx.beginPath();
            ctx.moveTo(-7, -player.height/2 + 25);
            ctx.lineTo(7, -player.height/2 + 25);
            ctx.lineTo(4, -player.height/2 + 37);
            ctx.lineTo(-4, -player.height/2 + 37);
            ctx.fill();

            // Piloto (Mono de carreras premium con hombreras estructuradas)
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(-10, -5, 20, 15); // Hombros
            ctx.beginPath();
            ctx.arc(0, -3, 9, 0, Math.PI * 2); // Casco protector blanco
            ctx.fill();
            ctx.fillStyle = '#050508';
            ctx.fillRect(-6, -7, 12, 5); // Visera de titanio oscuro

            ctx.restore();
        }

        // SISTEMA DE ENEMIGOS (TRÁFICO DINÁMICO)
        function spawnObstacle() {
            const currentTime = Date.now();
            if (currentTime - lastSpawnTime < 900 - (speed * 15)) return;

            const laneWidth = (canvas.width - 92) / 3;
            const randomLane = Math.floor(Math.random() * 3);
            const xPos = 40 + (randomLane * laneWidth) + (laneWidth / 2) - 20;

            const colors = ['#3b82f6', '#f59e0b', '#a855f7', '#10b981', '#6b7280'];
            
            obstacles.push({
                x: xPos, y: -90,
                width: 42, height: 82,
                color: colors[Math.floor(Math.random() * colors.length)],
                currentLane: randomLane,
                isChangingLane: false,
                targetX: xPos,
                laneChangeSpeed: 3.8,
                behaviorTimer: Math.random() * 60 + 30,
                speedModifier: Math.random() * 4,
                missRecorded: false
            });

            lastSpawnTime = currentTime;
        }

        function updateObstacles() {
            const laneWidth = (canvas.width - 92) / 3;

            for (let i = obstacles.length - 1; i >= 0; i--) {
                let obs = obstacles[i];
                obs.y += (speed - obs.speedModifier);

                // Algoritmo de evasión/bloqueo de carriles por IA
                obs.behaviorTimer--;
                if (obs.behaviorTimer <= 0 && !obs.isChangingLane) {
                    obs.behaviorTimer = Math.random() * 100 + 60;
                    let possibleLanes = [];
                    if (obs.currentLane > 0) possibleLanes.push(obs.currentLane - 1);
                    if (obs.currentLane < 2) possibleLanes.push(obs.currentLane + 1);
                    
                    if (possibleLanes.length > 0 && Math.random() > 0.3) {
                        obs.currentLane = possibleLanes[Math.floor(Math.random() * possibleLanes.length)];
                        obs.targetX = 40 + (obs.currentLane * laneWidth) + (laneWidth / 2) - obs.width / 2;
                        obs.isChangingLane = true;
                    }
                }

                if (obs.isChangingLane) {
                    let diff = obs.targetX - obs.x;
                    if (Math.abs(diff) > 2) {
                        obs.x += Math.sign(diff) * obs.laneChangeSpeed;
                    } else {
                        obs.x = obs.targetX;
                        obs.isChangingLane = false;
                    }
                }

                // Dibujar vehículo enemigo detallado
                ctx.fillStyle = obs.color;
                ctx.fillRect(obs.x, obs.y, obs.width, obs.height);
                ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
                ctx.fillRect(obs.x + 4, obs.y + 15, obs.width - 8, 12); // Parabrisas delantero
                ctx.fillRect(obs.x + 5, obs.y + obs.height - 20, obs.width - 10, 10); // Vidrio trasero

                // Faros de Xenón delanteros
                ctx.fillStyle = '#ffffcc';
                ctx.fillRect(obs.x + 4, obs.y, 6, 4);
                ctx.fillRect(obs.x + obs.width - 10, obs.y, 6, 4);

                // Luces de peligro traseras
                ctx.fillStyle = obs.isChangingLane ? '#ff0000' : '#990000';
                ctx.fillRect(obs.x + 3, obs.y + obs.height - 5, 7, 5);
                ctx.fillRect(obs.x + obs.width - 10, obs.y + obs.height - 5, 7, 5);

                // CONTROL DE COLISIONES CON EVALUACIÓN DE PODER NITRO
                if (
                    player.x < obs.x + obs.width &&
                    player.x + player.width > obs.x &&
                    player.y < obs.y + obs.height &&
                    player.y + player.height > obs.y
                ) {
                    if (nitroActive) {
                        // SI TIENES PODER DE NITRO: Destruyes al enemigo al contacto
                        triggerExplosion(obs.x + obs.width/2, obs.y + obs.height/2);
                        obstacles.splice(i, 1);
                        score += 150; // Super bonus por aniquilar el obstáculo
                        popups.push({
                            x: player.x, y: player.y - 25,
                            text: '💥 DESTRUIDO +150 KM 💥', color: '#00f3ff'
                        });
                        continue;
                    } else {
                        // SIN PODER: Muerte instantánea por impacto crítico
                        triggerExplosion(player.x + player.width / 2, player.y + 10);
                        endGame();
                        return;
                    }
                }

                // Near Miss Bonus (Pasar rozando sin chocar)
                let distanceX = Math.abs((player.x + player.width/2) - (obs.x + obs.width/2));
                if (distanceX < 45 && Math.abs(player.y - obs.y) < 50 && !obs.missRecorded && gameActive && !nitroActive) {
                    obs.missRecorded = true;
                    score += 50;
                    popups.push({
                        x: player.x, y: player.y - 20,
                        text: '⚡ ROZE RADICAL +50 ⚡', color: '#00f3ff'
                    });
                }

                if (obs.y > canvas.height) {
                    obstacles.splice(i, 1);
                    score += 5;
                }
            }
        }

        // GENERACIÓN DE ÍTEMS DE PODERES (NÚCLEOS DE NITRO ENERGÍA)
        function spawnPower() {
            const currentTime = Date.now();
            if (currentTime - lastPowerSpawnTime < 6000) return; // Un poder cada 6 segundos mínimo

            const laneWidth = (canvas.width - 92) / 3;
            const randomLane = Math.floor(Math.random() * 3);
            const xPos = 40 + (randomLane * laneWidth) + (laneWidth / 2) - 12;

            powers.push({
                x: xPos,
                y: -50,
                size: 24,
                pulse: 0
            });
            lastPowerSpawnTime = currentTime;
        }

        function updatePowers() {
            for (let i = powers.length - 1; i >= 0; i--) {
                let pow = powers[i];
                pow.y += speed - 2; // Caen un poco más despacio que el flujo de la pista
                pow.pulse += 0.1;

                // Render de la celda de energía con resplandor pulsante
                ctx.save();
                ctx.shadowBlur = 15;
                ctx.shadowColor = '#00f3ff';
                ctx.fillStyle = '#ffffff';
                ctx.beginPath();
                // Dibujo en forma de rombo de energía de plasma cian
                ctx.moveTo(pow.x + pow.size/2, pow.y);
                ctx.lineTo(pow.x + pow.size, pow.y + pow.size/2);
                ctx.lineTo(pow.x + pow.size/2, pow.y + pow.size);
                ctx.lineTo(pow.x, pow.y + pow.size/2);
                ctx.closePath();
                ctx.fill();
                
                // Anillo de energía exterior
                ctx.strokeStyle = '#00f3ff';
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.restore();

                // COLECTA DEL PODER POR EL JUGADOR
                if (
                    player.x < pow.x + pow.size &&
                    player.x + player.width > pow.x &&
                    player.y < pow.y + pow.size &&
                    player.y + player.height > pow.y
                ) {
                    powers.splice(i, 1);
                    activateNitro();
                    continue;
                }

                if (pow.y > canvas.height) powers.splice(i, 1);
            }
        }

        function activateNitro() {
            nitroActive = true;
            nitroTimeLeft = maxNitroDuration;
            popups.push({
                x: player.x, y: player.y - 30,
                text: '🔥 ¡MODO NITRO INMORTAL ACTIVADO! 🔥', color: '#ffff00'
            });
        }

        // CONTROLADORES DE RENDERIZADO COMPLEMENTARIOS
        function drawPopups() {
            for (let i = popups.length - 1; i >= 0; i--) {
                let p = popups[i];
                ctx.fillStyle = p.color || '#00f3ff';
                ctx.font = 'italic bold 14px Segoe UI';
                ctx.fillText(p.text, p.x - 50, p.y);
                p.y -= 1.4;
                if (p.y < 0) popups.splice(i, 1);
            }
        }

        function triggerExplosion(x, y) {
            for(let i=0; i<45; i++) {
                particles.push({
                    x: x, y: y,
                    vx: (Math.random() - 0.5) * 16,
                    vy: (Math.random() - 0.5) * 16,
                    radius: Math.random() * 5 + 2,
                    alpha: 1,
                    color: Math.random() > 0.4 ? '#00f3ff' : '#ff003c'
                });
            }
        }

        function drawParticles() {
            for(let i = particles.length - 1; i >= 0; i--) {
                let p = particles[i];
                ctx.save();
                ctx.globalAlpha = p.alpha;
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI*2);
                ctx.fill();
                ctx.restore();
                
                p.x += p.vx;
                p.y += p.vy;
                p.alpha -= 0.02;
                if(p.alpha <= 0) particles.splice(i, 1);
            }
        }

        // BUCE MOTOR PRINCIPAL (GAME LOOP)
        function gameLoop() {
            if (!gameActive && particles.length === 0) return;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            drawRoad();
            if (gameActive) {
                spawnObstacle();
                spawnPower();
                
                // Control logístico del temporizador del súper poder
                if (nitroActive) {
                    nitroTimeLeft--;
                    speed = maxSpeed; // Velocidad límite en Nitro
                    document.getElementById('nitroBar').style.width = `${(nitroTimeLeft / maxNitroDuration) * 100}%`;
                    if (nitroTimeLeft <= 0) {
                        nitroActive = false;
                        document.getElementById('nitroBar').style.width = '0%';
                    }
                    score += 0.6; // Ganas distancia el doble de rápido en modo de poder
                } else {
                    speed = Math.min(maxSpeed - 4, 9 + Math.floor(score / 250));
                    score += 0.25;
                }
                
                document.getElementById('liveScore').innerText = Math.floor(score);
            }
            
            updateObstacles();
            updatePowers();
            if (gameActive) drawPlayer();
            drawParticles();
            drawPopups();

            requestAnimationFrame(gameLoop);
        }

        function startGame() {
            document.getElementById('startScreen').classList.add('hidden');
            gameActive = true;
            score = 0;
            speed = 8;
            obstacles = [];
            powers = [];
            particles = [];
            popups = [];
            nitroActive = false;
            document.getElementById('nitroBar').style.width = '0%';
            requestAnimationFrame(gameLoop);
        }

        function endGame() {
            gameActive = false;
            document.getElementById('gameOverScreen').classList.remove('hidden');
            
            const finalScore = Math.floor(score);
            document.getElementById('finalScore').innerText = finalScore;

            const recordKey = `apex_max_score_${pilotName}`;
            let currentRecord = localStorage.getItem(recordKey) || 0;
            
            if (finalScore > currentRecord) {
                localStorage.setItem(recordKey, finalScore);
                currentRecord = finalScore;
            }
            document.getElementById('highScore').innerText = currentRecord;
        }

        function resetGame() {
            document.getElementById('gameOverScreen').classList.add('hidden');
            startGame();
        }

        window.addEventListener('resize', resizeCanvas);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>