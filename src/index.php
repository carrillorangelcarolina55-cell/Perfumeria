<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Obtener perfumes destacados
$query = "SELECT * FROM fragancias ORDER BY clasificacion DESC LIMIT 6";
$stmt = $db->prepare($query);
$stmt->execute();
$perfumes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>"Life of Essence" - Inicio</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">"✨ Life of Essence"</h1>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="perfumes.php">Perfumes</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="registro.php">Registro</a></li>
            </ul>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <h2>Descubre tu fragancia perfecta</h2>
            <p>Explora nuestra colección de perfumes exclusivos</p>
        </div>
    </header>

    <section class="perfumes-destacados">
        <div class="container">
            <h3>Perfumes Destacados</h3>
            <div class="perfumes-grid">
                <?php foreach($perfumes as $perfume): ?>
                <div class="perfume-card">
                    <img src="<?php echo htmlspecialchars($perfume['url_imagen'] ?? '/images/default-perfume.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($perfume['nombre']); ?>">
                    <h4><?php echo htmlspecialchars($perfume['nombre']); ?></h4>
                    <p class="marca"><?php echo htmlspecialchars($perfume['marca']); ?></p>
                    <div class="rating">
                        ⭐ <?php echo number_format($perfume['clasificacion'], 1); ?>
                    </div>
                    <a href="perfume_detalle.php?id=<?php echo $perfume['id_fragancia']; ?>" 
                       class="btn">Ver más</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2026 Perfumería. Todos los derechos reservados.</p>
            <p>
                <a href="https://github.com/tu-usuario/perfumeria" target="_blank">
                    Ver en GitHub 🔗
                </a>
            </p>
        </div>
    </footer>
</body>
</html>