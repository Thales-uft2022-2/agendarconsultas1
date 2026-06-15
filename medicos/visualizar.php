<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT *
    FROM medicos
    WHERE id = ?
");

$sql->execute([$id]);

$medico = $sql->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Visualizar Médico</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-info text-white">

<h3>

Dados do Médico

</h3>

</div>

<div class="card-body">

<p><strong>ID:</strong> <?= $medico['id'] ?></p>

<p><strong>Nome:</strong> <?= htmlspecialchars($medico['nome']) ?></p>

<p><strong>CRM:</strong> <?= htmlspecialchars($medico['crm']) ?></p>

<p><strong>Especialidade:</strong> <?= htmlspecialchars($medico['especialidade']) ?></p>

<p><strong>Telefone:</strong> <?= htmlspecialchars($medico['telefone']) ?></p>

<p><strong>E-mail:</strong> <?= htmlspecialchars($medico['email']) ?></p>

<p><strong>Status:</strong> <?= htmlspecialchars($medico['status']) ?></p>

<p><strong>Cadastro:</strong> <?= $medico['criado_em'] ?></p>

<a
href="index.php"
class="btn btn-secondary">

Voltar

</a>

</div>

</div>

</div>

</body>
</html>