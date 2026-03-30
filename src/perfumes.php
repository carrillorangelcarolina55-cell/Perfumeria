<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("ERROR: No se pudo conectar a la base de datos");
}

// Obtener todos los perfumes con su perfumista
$query = "SELECT f.*, p.nombre as nombre_perfumista 
          FROM fragancias f 
          LEFT JOIN perfumistas p ON f.id_perfumista = p.id_perfumista 
          ORDER BY f.clasificacion DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$perfumes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Perfumes - Life of Essence</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Estilos específicos para el catálogo */
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

        .perfume-card .descripcion {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 1rem 0;
        }

        .perfume-card .genero {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin: 0.5rem 0;
        }

        .genero.femenino {
            background: #ff69b4;
            color: white;
        }

        .genero.masculino {
            background: #4169e1;
            color: white;
        }

        .genero.unisex {
            background: #9370db;
            color: white;
        }

        .btn-favorito {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.7rem 1.2rem;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 1rem;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-favorito:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-favorito.favorito {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .container h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 2.5rem;
            margin: 2rem 0;
            padding-bottom: 1rem;
            border-bottom: 3px solid #667eea;
        }

        .no-perfumes {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-size: 1.2rem;
        }

        .login-prompt {
            text-align: center;
            padding: 1rem;
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            margin: 1rem 0;
            color: #856404;
        }

        .login-prompt a {
            color: #667eea;
            font-weight: 600;
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="favoritos.php">Mis Favoritos</a></li>
                    <li><a href="perfil.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="registro.php">Registro</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>🌸 Catálogo de Perfumes</h2>
        
        <?php if (empty($perfumes)): ?>
            <div class="no-perfumes">
                <p>😔 No hay perfumes disponibles en este momento.</p>
            </div>
        <?php else: ?>
            <div class="perfumes-grid">
                <?php foreach($perfumes as $perfume): ?>
                <div class="perfume-card">
                    <!-- Imagen con placeholder de colores según género -->
                    <img src="https://via.placeholder.com/250x300/<?php 
                        echo $perfume['genero'] === 'Femenino' ? 'ff69b4' : 
                            ($perfume['genero'] === 'Masculino' ? '4169e1' : '9370db'); 
                    ?>/ffffff?text=<?php echo urlencode(explode(' ', $perfume['nombre'])[0]); ?>" 
                         alt="<?php echo htmlspecialchars($perfume['nombre']); ?>">
                    
                    <h4><?php echo htmlspecialchars($perfume['nombre']); ?></h4>
                    
                    <p class="marca"><?php echo htmlspecialchars($perfume['marca']); ?></p>
                    
                    <span class="genero <?php echo strtolower($perfume['genero']); ?>">
                        <?php echo htmlspecialchars($perfume['genero']); ?>
                    </span>
                    
                    <?php if ($perfume['nombre_perfumista']): ?>
                    <p class="perfumista">Por: <?php echo htmlspecialchars($perfume['nombre_perfumista']); ?></p>
                    <?php endif; ?>
                    
                    <div class="rating">
                        ⭐ <?php echo number_format($perfume['clasificacion'], 1); ?> 
                        <small>(<?php echo $perfume['recuento_revisiones']; ?> reseñas)</small>
                    </div>
                    
                    <?php if ($perfume['descripcion']): ?>
                    <p class="descripcion">
                        <?php echo htmlspecialchars(substr($perfume['descripcion'], 0, 100)) . '...'; ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                        // Verificar si ya es favorito
                        $check_fav_query = "SELECT id_favorito FROM favoritos WHERE id_usuario = :user_id AND id_fragancia = :id_fragancia";
                        $check_fav_stmt = $db->prepare($check_fav_query);
                        $check_fav_stmt->bindParam(':user_id', $_SESSION['user_id']);
                        $check_fav_stmt->bindParam(':id_fragancia', $perfume['id_fragancia']);
                        $check_fav_stmt->execute();
                        $es_favorito = $check_fav_stmt->rowCount() > 0;
                        ?>
                        <button class="btn-favorito <?php echo $es_favorito ? 'favorito' : ''; ?>" 
                                onclick="toggleFavorito(<?php echo $perfume['id_fragancia']; ?>)">
                            <?php echo $es_favorito ? '❤️ En favoritos' : '🤍 Agregar a favoritos'; ?>
                        </button>
                    <?php else: ?>
                        <div class="login-prompt">
                            <a href="login.php">Inicia sesión</a> para guardar favoritos
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2026 Life of Essence. Todos los derechos reservados.</p>
            <p>
                <a href="https://github.com/tu-usuario/perfumeria" target="_blank">
                    Ver en GitHub 🔗
                </a>
            </p>
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