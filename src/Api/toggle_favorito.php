<?php
session_start();
header('Content-Type: application/json');

include_once '../config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$id_fragancia = $data['id_fragancia'] ?? null;

if (!$id_fragancia) {
    echo json_encode(['success' => false, 'message' => 'ID de fragancia no válido']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Verificar si ya es favorito
$check_query = "SELECT id_favorito FROM favoritos WHERE id_usuario = :user_id AND id_fragancia = :id_fragancia";
$check_stmt = $db->prepare($check_query);
$check_stmt->bindParam(':user_id', $user_id);
$check_stmt->bindParam(':id_fragancia', $id_fragancia);
$check_stmt->execute();

if ($check_stmt->rowCount() > 0) {
    // Ya es favorito, lo eliminamos
    $delete_query = "DELETE FROM favoritos WHERE id_usuario = :user_id AND id_fragancia = :id_fragancia";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bindParam(':user_id', $user_id);
    $delete_stmt->bindParam(':id_fragancia', $id_fragancia);
    
    if ($delete_stmt->execute()) {
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar favorito']);
    }
} else {
    // No es favorito, lo agregamos
    $insert_query = "INSERT INTO favoritos (id_usuario, id_fragancia) VALUES (:user_id, :id_fragancia)";
    $insert_stmt = $db->prepare($insert_query);
    $insert_stmt->bindParam(':user_id', $user_id);
    $insert_stmt->bindParam(':id_fragancia', $id_fragancia);
    
    if ($insert_stmt->execute()) {
        echo json_encode(['success' => true, 'action' => 'added']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar favorito']);
    }
}
?>