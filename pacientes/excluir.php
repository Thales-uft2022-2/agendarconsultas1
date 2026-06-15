<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$buscar = $pdo->prepare("
    SELECT id
    FROM pacientes
    WHERE id = ?
");

$buscar->execute([$id]);

if ($buscar->rowCount() === 0) {
    header("Location: index.php");
    exit;
}

try {

    $excluir = $pdo->prepare("
        DELETE FROM pacientes
        WHERE id = ?
    ");

    $excluir->execute([$id]);

} catch (PDOException $e) {

    $_SESSION['erro'] = "Não foi possível excluir este paciente.";

}

header("Location: index.php");
exit;