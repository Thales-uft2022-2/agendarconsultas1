<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$medico_id = (int) ($_GET['id'] ?? 0);

if ($medico_id <= 0) {
    header("Location: index.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT *
    FROM medicos
    WHERE id = ?
");

$sql->execute([$medico_id]);

$medico = $sql->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    header("Location: index.php");
    exit;
}

$consultas = $pdo->prepare("
    SELECT
        c.*,
        p.nome AS paciente
    FROM consultas c
    LEFT JOIN pacientes p
        ON p.id = c.paciente_id
    WHERE c.medico_id = ?
    ORDER BY c.data_consulta DESC,
             c.hora_consulta DESC
");

$consultas->execute([$medico_id]);

$lista = $consultas->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Consultas do Médico</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>

Consultas - <?= htmlspecialchars($medico['nome']) ?>

</h3>

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Paciente</th>
<th>Data</th>
<th>Hora</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php if(count($lista) > 0): ?>

<?php foreach($lista as $c): ?>

<tr>

<td><?= $c['id'] ?></td>

<td><?= htmlspecialchars($c['paciente']) ?></td>

<td><?= date('d/m/Y', strtotime($c['data_consulta'])) ?></td>

<td><?= substr($c['hora_consulta'],0,5) ?></td>

<td>

<?php if($c['status'] == 'agendada'): ?>

<span class="badge bg-primary">
Agendada
</span>

<?php elseif($c['status'] == 'confirmada'): ?>

<span class="badge bg-info">
Confirmada
</span>

<?php elseif($c['status'] == 'realizada'): ?>

<span class="badge bg-success">
Realizada
</span>

<?php else: ?>

<span class="badge bg-danger">
Cancelada
</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="5" class="text-center">

Nenhuma consulta encontrada.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

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