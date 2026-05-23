<?php
// =========================================================================
// 1. FASE DE LÓGICA, SANITIZACIÓN Y CONTROL DE ARCHIVOS
// =========================================================================

$nombreUsuario = "Invitado";
$imagenAleatoria = "";
$errorServidor = false;
$mensajeError = "";

try {
    // 1. Validar y limpiar la entrada del usuario
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
        $entradaLimpia = trim($_POST["nombre"]);
        if ($entradaLimpia !== "") {
            $nombreUsuario = htmlspecialchars($entradaLimpia, ENT_QUOTES, 'UTF-8');
        }
    }

    // 2. Lógica dinámica: Leer el directorio de imágenes en tiempo real
    $rutaCarpeta = __DIR__ . "/imagenes"; // __DIR__ asegura la ruta absoluta en Render/Railway
    $imagenesValidas = [];

    if (is_dir($rutaCarpeta)) {
        // Escanear la carpeta buscando extensiones de imagen válidas
        $archivos = scandir($rutaCarpeta);
        foreach ($archivos as $archivo) {
            $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $imagenesValidas[] = $archivo;
            }
        }
    }

    // 3. Selección con soporte de respaldo (Fallback)
    if (!empty($imagenesValidas)) {
        $imagenAleatoria = $imagenesValidas[array_rand($imagenesValidas)];
    } else {
        // Si la carpeta está vacía o no existe, usamos una imagen de Unsplash para que no se rompa la app
        $imagenAleatoria = "https://images.unsplash.com/photo-1516259762381-22954d7d3ad2?q=80&w=800&auto=format&fit=crop";
    }

} catch (Exception $e) {
    $errorServidor = true;
    $mensajeError = "Ocurrió un error en el laboratorio de procesamiento.";
}

// =========================================================================
// 2. FASE DE RENDERIZADO (INTERFAZ DE USUARIO)
// =========================================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 1 - Telemetría de Despliegue</title>
    <!-- Bootstrap 5 CDN Limpio -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .img-container img {
            max-width: 100%;
            max-height: 480px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 text-center">

                <?php if ($errorServidor): ?>
                    <!-- Alerta de Error Sanitizada -->
                    <div class="alert alert-danger shadow-sm" role="alert">
                        <h4 class="alert-heading">Servidor Ocupado</h4>
                        <p class="mb-0"><?php echo htmlspecialchars($mensajeError); ?></p>
                    </div>
                <?php else: ?>
                    <!-- Tarjeta de Bienvenida Principal -->
                    <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                        <h2 class="fw-bold mb-4">
                            Bienvenido, <span class="text-primary"><?php echo $nombreUsuario; ?></span>
                        </h2>
                        
                        <div class="img-container mb-3">
                            <?php 
                            // Si es una URL externa (Unsplash) la usa directo, si no, busca en tu carpeta local
                            $srcFinal = (strpos($imagenAleatoria, 'http') === 0) ? $imagenAleatoria : "imagenes/" . $imagenAleatoria;
                            ?>
                            <img src="<?php echo $srcFinal; ?>" alt="Renderizado de imagen aleatoria">
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Navegación por botón (Reemplaza de forma segura el include "index.html") -->
                <div class="mt-4">
                    <a href="index.html" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-semibold">
                        ← Volver al Formulario
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>