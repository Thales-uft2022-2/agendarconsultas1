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
    FROM pacientes
    WHERE id = ?
");

$sql->execute([$id]);

$paciente = $sql->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
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

<title>Visualizar Paciente</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-info text-white">

<h3>

Dados do Paciente

</h3>

</div>

<div class="card-body">

<p><strong>ID:</strong> <?= $paciente['id'] ?></p>

<p><strong>Nome:</strong> <?= htmlspecialchars($paciente['nome']) ?></p>

<p><strong>CPF:</strong> <?= htmlspecialchars($paciente['cpf']) ?></p>

<p><strong>Data de Nascimento:</strong>

<?= !empty($paciente['data_nascimento'])
    ? date('d/m/Y', strtotime($paciente['data_nascimento']))
    : '-' ?>

</p>

<p><strong>Telefone:</strong> <?= htmlspecialchars($paciente['telefone']) ?></p>

<p><strong>E-mail:</strong> <?= htmlspecialchars($paciente['email']) ?></p>

<p><strong>Endereço:</strong> <?= nl2br(htmlspecialchars($paciente['endereco'])) ?></p>

<p><strong>Status:</strong> <?= ucfirst($paciente['status']) ?></p>

<p><strong>Cadastro:</strong>

<?= date('d/m/Y H:i', strtotime($paciente['criado_em'])) ?>

</p>

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