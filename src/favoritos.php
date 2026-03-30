<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_SESSION['user_id'];

// Obtener favoritos del usuario
$query = "SELECT f.*, p.nombre as nombre_perfumista, fav.creado_en as fecha_favorito
          FROM favoritos fav
          INNER JOIN fragancias f ON fav.id_fragancia = f.id_fragancia
          LEFT JOIN perfumistas p ON f.id_perfumista = p.id_perfumista
          WHERE fav.id_usuario = :user_id
          ORDER BY fav.creado_en DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$favoritos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos - Life of Essence</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .perfumes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
            padding: 1rem;
        }

        .perfume-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
            position: relative;
        }

        .perfume-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .perfume-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .perfume-card h4 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin: 0.5rem 0;
            font-weight: 600;
        }

        .perfume-card .marca {
            color: #667eea;
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0.5rem 0;
        }

        .perfume-card .perfumista {
            color: #888;
            font-size: 0.9rem;
            margin: 0.5rem 0;
            font-style: italic;
        }

        .perfume-card .rating {
            color: #f39c12;
            font-size: 1.2rem;
            margin: 0.5rem 0;
            font-weight: bold;
        }

        .btn-favorito {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 1rem;
            transition: background 0.3s;
        }

        .btn-favorito:hover {
            background: #c0392b;
        }

        .container h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 2.5rem;
            margin: 2rem 0;
            padding-bottom: 1rem;
            border-bottom: 3px solid #667eea;
        }

        .no-favoritos {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-size: 1.2rem;
        }

        .fecha-favorito {
            color: #999;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">✨ Life of Essence</h1>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="perfumes.php">Perfumes</a></li>
                <li><a href="favoritos.php">Mis Favoritos</a></li>
                <li><a href="perfil.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>💖 Mis Favoritos</h2>
        
        <?php if (empty($favoritos)): ?>
            <div class="no-favoritos">
                <p>😔 No tienes perfumes favoritos aún.</p>
                <p><a href="perfumes.php" class="btn">Explorar catálogo</a></p>
            </div>
        <?php else: ?>
            <div class="perfumes-grid">
                <?php foreach($favoritos as $perfume): ?>
                <div class="perfume-card">
                    <img src="https://via.placeholder.com/250x300/<?php 
                        echo $perfume['genero'] === 'Femenino' ? 'ff69b4' : 
                            ($perfume['genero'] === 'Masculino' ? '4169e1' : '9370db'); 
                    ?>/ffffff?text=<?php echo urlencode(explode(' ', $perfume['nombre'])[0]); ?>" 
                         alt="<?php echo htmlspecialchars($perfume['nombre']); ?>">
                    
                    <h4><?php echo htmlspecialchars($perfume['nombre']); ?></h4>
                    <p class="marca"><?php echo htmlspecialchars($perfume['marca']); ?></p>
                    
                    <?php if ($perfume['nombre_perfumista']): ?>
                    <p class="perfumista">Por: <?php echo htmlspecialchars($perfume['nombre_perfumista']); ?></p>
                    <?php endif; ?>
                    
                    <div class="rating">
                        ⭐ <?php echo number_format($perfume['clasificacion'], 1); ?>
                    </div>
                    
                    <p class="fecha-favorito">Agregado: <?php echo date('d/m/Y', strtotime($perfume['fecha_favorito'])); ?></p>
                    
                    <button class="btn-favorito" onclick="toggleFavorito(<?php echo $perfume['id_fragancia']; ?>)">
                        ❤️ Quitar de favoritos
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2026 Life of Essence. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        function toggleFavorito(idFragancia) {
            fetch('/api/toggle_favorito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_fragancia: idFragancia })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }
    </script>
</body>
</html>