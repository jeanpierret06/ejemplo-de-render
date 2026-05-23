<?php
// 1. BASE DE DATOS LOCAL (Array asociativo)
// Si necesitas agregar o cambiar una moto, solo modificas este bloque.
$catalogo_motos = [
    "r1" => [
        "marca"    => "Apex Racing",
        "nombre"   => "R1 Phantom",
        "categoria"=> "1000cc",
        "potencia" => "205",
        "peso"     => "198",
        "v_max"    => "299",
        "imagen"   => "https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?q=80&w=600&auto=format&fit=crop"
    ],
    "xzeta" => [
        "marca"    => "Veloce Moto",
        "nombre"   => "X-Zeta 6R",
        "categoria"=> "600cc",
        "potencia" => "130",
        "peso"     => "185",
        "v_max"    => "265",
        "imagen"   => "https://autogazette.de/wp-content/uploads/2023/06/Ninja-ZX-6R.jpg"
    ],
    "hyper" => [
        "marca"    => "Kratos Corp",
        "nombre"   => "HyperDuke 900",
        "categoria"=> "naked",
        "potencia" => "145",
        "peso"     => "179",
        "v_max"    => "250",
        "imagen"   => "https://s7g10.scene7.com/is/image/ktm/MY24%20KTM%201390%20SUPER%20DUKE%20R_EVO-4?wid=1000&dpr=off"
    ],
    "strada" => [
        "marca"    => "Chronos Industry",
        "nombre"   => "Strada RR V4",
        "categoria"=> "1000cc",
        "potencia" => "215",
        "peso"     => "195",
        "v_max"    => "312",
        "imagen"   => "https://bikereview.com.au/wp-content/uploads/2025/11/BikeReview-CFMOTO-V4-SR-RR-19.jpg"
    ]
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Moto | Catálogo de Modelos</title>

    <style>
        /* CONFIGURACIÓN GENERAL Y REUTILIZACIÓN DE TEXTURA */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Inter, sans-serif;
        }

        body {
            background-color: #08080c;
            color: #ffffff;
            overflow-x: hidden;
            padding-top: 60px;
            background-image: linear-gradient(rgba(255, 255, 255, 0.01) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.01) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* BOTÓN VOLVER AL INICIO */
        .btn-back-home {
            display: inline-block;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
            color: #71717a;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            transition: color 0.3s, transform 0.3s;
        }

        .btn-back-home:hover {
            color: #e60000;
            transform: translateX(-5px);
        }

        /* BARRA DE FILTROS */
        .filter-container {
            max-width: 1200px;
            margin: 20px auto 20px auto;
            padding: 0 5%;
            text-align: center;
        }

        .filter-title {
            font-size: 2.5rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .filter-title span {
            color: #e60000;
            text-shadow: 0 0 15px rgba(230, 0, 0, 0.4);
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn-filter {
            background-color: #121218;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            cursor: pointer;
            clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-filter:hover,
        .btn-filter.active {
            background-color: #e60000;
            border-color: #e60000;
            box-shadow: 0 0 15px rgba(230, 0, 0, 0.5);
            transform: scale(1.05);
        }

        /* REJILLA DE CATALOGO */
        .catalog-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 5% 100px 5%;
        }

        .grid-catalog {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 40px;
        }

        /* TARJETA DE MODELO AVANZADA */
        .model-card {
            background: linear-gradient(145deg, #121218, #09090d);
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.03);
            position: relative;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), 
                        border-color 0.4s, 
                        box-shadow 0.4s, 
                        opacity 0.4s ease;
            opacity: 1;
        }

        .model-card.hide {
            display: none !important;
            opacity: 0;
        }

        .model-card:hover {
            transform: translateY(-8px);
            border-color: rgba(230, 0, 0, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.7), 0 0 20px rgba(230, 0, 0, 0.1);
        }

        .model-img-box {
            position: relative;
            height: 250px;
            overflow: hidden;
            clip-path: polygon(0 0, 100% 0, 100% 92%, 0 100%);
        }

        .model-img {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .model-card:hover .model-img {
            transform: scale(1.08) rotate(-0.5deg);
        }

        .model-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(230, 0, 0, 0.85);
            backdrop-filter: blur(5px);
            color: white;
            padding: 6px 14px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            clip-path: polygon(15% 0, 100% 0, 85% 100%, 0 100%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* DETALLES DE LA MOTO */
        .model-details {
            padding: 30px;
        }

        .model-brand {
            color: #e60000;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .model-name {
            font-size: 1.8rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        /* CONTENEDOR DE ESPECIFICACIONES */
        .specs-row {
            display: flex;
            justify-content: space-between;
            background-color: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.02);
        }

        .spec-item {
            text-align: center;
            flex: 1;
        }

        .spec-item:not(:last-child) {
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .spec-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: #ffffff;
        }

        .spec-label {
            font-size: 0.7rem;
            color: #71717a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* BOTÓN DE ACCIÓN */
        .btn-specs {
            width: 100%;
            background: transparent;
            border: 2px solid #ffffff;
            color: #ffffff;
            padding: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            cursor: pointer;
            clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%);
            transition: all 0.3s;
        }

        .model-card:hover .btn-specs {
            background-color: #ffffff;
            color: #08080c;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }

        .btn-specs:hover {
            background-color: #e60000 !important;
            border-color: #e60000 !important;
            color: #ffffff !important;
            box-shadow: 0 0 20px rgba(230, 0, 0, 0.6) !important;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .filter-title {
                font-size: 2rem;
            }

            .grid-catalog {
                grid-template-columns: 1fr;
            }

            .btn-filter {
                padding: 10px 20px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>

<body>

    <!-- ENLACE RETORNO A LANDING -->
    <div style="max-width: 1200px; margin: 20px auto 0 auto; width: 100%;">
        <a href="../index.php" class="btn-back-home">← Volver al Inicio</a>
    </div>

    <!-- CONTENEDOR DE FILTROS -->
    <div class="filter-container">
        <h1 class="filter-title">MÁQUINAS DE <span>RENDIMIENTO</span></h1>
        <p style="color: #a0a0aa;">Filtra por cilindrada o estilo y encuentra tu próxima compañera de circuito.</p>

        <div class="filter-buttons">
            <button class="btn-filter active" data-filter="all">Todos los Modelos</button>
            <button class="btn-filter" data-filter="1000cc">Superbike (1000cc+)</button>
            <button class="btn-filter" data-filter="600cc">Supersport (600cc)</button>
            <button class="btn-filter" data-filter="naked">Naked Sport</button>
        </div>
    </div>

    <!-- SECCIÓN DE CATÁLOGO DINÁMICO -->
    <main class="catalog-section">
        <div class="grid-catalog" id="catalogGrid">

            <!-- 2. BUCLE PHP GENERADOR DE TARJETAS -->
            <?php foreach ($catalogo_motos as $slug => $moto): ?>
                <div class="model-card" data-category="<?php echo htmlspecialchars($moto['categoria']); ?>">
                    <div class="model-img-box">
                        <div class="model-img" style="background-image: url('<?php echo htmlspecialchars($moto['imagen']); ?>');"></div>
                        <div class="model-badge">
                            <?php echo htmlspecialchars($moto['categoria'] === 'naked' ? 'Naked Sport' : $moto['categoria']); ?>
                        </div>
                    </div>
                    <div class="model-details">
                        <div class="model-brand"><?php echo htmlspecialchars($moto['marca']); ?></div>
                        <div class="model-name"><?php echo htmlspecialchars($moto['nombre']); ?></div>

                        <div class="specs-row">
                            <div class="spec-item">
                                <div class="spec-value"><?php echo htmlspecialchars($moto['potencia']); ?></div>
                                <div class="spec-label">HP Potencia</div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-value"><?php echo htmlspecialchars($moto['peso']); ?></div>
                                <div class="spec-label">KG Peso</div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-value"><?php echo htmlspecialchars($moto['v_max']); ?></div>
                                <div class="spec-label">Max KM/H</div>
                            </div>
                        </div>

                        <!-- Envía el slug dinámicamente a tu configurador -->
                        <button class="btn-specs" onclick="window.location.href='configurador.php?moto=<?php echo urlencode($slug); ?>'">
                            Explorar Configuración
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

    <!-- INTERACCIÓN JAVASCRIPT (SE MANTIENE INTACTO) -->
    <script>
        const filterButtons = document.querySelectorAll('.btn-filter');
        const modelCards = document.querySelectorAll('.model-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const filterValue = button.getAttribute('data-filter');

                modelCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');

                    if (filterValue === 'all' || filterValue === cardCategory) {
                        card.classList.remove('hide');
                        setTimeout(() => {
                            card.style.opacity = '1';
                        }, 20);
                    } else {
                        card.style.opacity = '0';
                        setTimeout(() => {
                            if (card.style.opacity === '0') {
                                card.classList.add('hide');
                            }
                        }, 400);
                    }
                });
            });
        });
    </script>
</body>

</html>