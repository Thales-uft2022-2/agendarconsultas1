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
    SELECT
        c.*,
        m.nome AS medico,
        m.especialidade,
        p.nome AS paciente,
        p.telefone
    FROM consultas c
    INNER JOIN medicos m
        ON m.id = c.medico_id
    INNER JOIN pacientes p
        ON p.id = c.paciente_id
    WHERE c.id = ?
");

$sql->execute([$id]);

$consulta = $sql->fetch(PDO::FETCH_ASSOC);

if (!$consulta) {
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

<title>Visualizar Consulta</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-info text-white">

<h3>Detalhes da Consulta</h3>

</div>

<div class="card-body">

<p><strong>Paciente:</strong>

<?= htmlspecialchars($consulta['paciente']) ?>

</p>

<p><strong>Telefone:</strong>

<?= htmlspecialchars($consulta['telefone']) ?>

</p>

<p><strong>Médico:</strong>

<?= htmlspecialchars($consulta['medico']) ?>

</p>

<p><strong>Especialidade:</strong>

<?= htmlspecialchars($consulta['especialidade']) ?>

</p>

<p><strong>Data:</strong>

<?= date('d/m/Y', strtotime($consulta['data_consulta'])) ?>

</p>

<p><strong>Hora:</strong>

<?= substr($consulta['hora_consulta'], 0, 5) ?>

</p>

<p><strong>Status:</strong>

<?= ucfirst($consulta['status']) ?>

</p>

<p><strong>Observações:</strong></p>

<div class="border rounded p-3 bg-light">

<?= nl2br(htmlspecialchars($consulta['observacoes'] ?: 'Nenhuma observação.')) ?>

</div>

<a
href="index.php"
class="btn btn-secondary mt-4">

Voltar

</a>

</div>

</div>

</div>

</body>
</html>