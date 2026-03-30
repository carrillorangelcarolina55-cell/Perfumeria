<?php
include_once 'config/database.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $database = new Database();
    $db = $database->getConnection();

    $id_perfil = uniqid();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO perfiles (id_perfil, nombre_usuario, contrasena) VALUES (:id_perfil, :username, :contrasena)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_perfil', $id_perfil);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':contrasena', $hashed_password);
    
    if ($stmt->execute()) {
        $mensaje = "¡Usuario registrado exitosamente! <a href='login.php'>Inicia sesión</a>";
    } else {
        $mensaje = "Error al registrar. El usuario ya existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - "Life of Essence"</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">"✨ Life of Essence"</h1>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="registro.php">Registro</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Crear Cuenta</h2>
            
            <?php if ($mensaje): ?>
                <div class="alert"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nombre de usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrarse</button>
            </form>

            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </div>
</body>
</html>