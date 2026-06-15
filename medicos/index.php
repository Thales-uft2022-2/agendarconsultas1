<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$medicos = $pdo->query("
    SELECT *
    FROM medicos
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Médicos</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="d-flex justify-content-between mb-3">

<h2>Médicos</h2>

<a
href="cadastrar.php"
class="btn btn-primary">

Novo Médico

</a>

</div>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Nome</th>
<th>CRM</th>
<th>Especialidade</th>
<th>Status</th>
<th>Ações</th>

</tr>

</thead>

<tbody>

<?php foreach($medicos as $m): ?>

<tr>

<td><?= $m['id'] ?></td>

<td><?= htmlspecialchars($m['nome']) ?></td>

<td><?= htmlspecialchars($m['crm']) ?></td>

<td><?= htmlspecialchars($m['especialidade']) ?></td>

<td><?= $m['status'] ?></td>

<td>

<a
href="editar.php?id=<?= $m['id'] ?>"
class="btn btn-warning btn-sm">

Editar

</a>

<a
href="alterar-status.php?id=<?= $m['id'] ?>"
class="btn btn-secondary btn-sm">

Status

</a>

<a
href="excluir.php?id=<?= $m['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Excluir médico?')">

Excluir

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</body>
</html>