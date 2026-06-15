<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$pacientes = $pdo->query("
    SELECT *
    FROM pacientes
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Pacientes</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Pacientes</h2>

<div>

<a
href="../dashboard/"
class="btn btn-secondary">

Dashboard

</a>

<a
href="cadastrar.php"
class="btn btn-primary">

Novo Paciente

</a>

</div>

</div>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Nome</th>
<th>CPF</th>
<th>Telefone</th>
<th>Status</th>
<th>Ações</th>

</tr>

</thead>

<tbody>

<?php foreach($pacientes as $p): ?>

<tr>

<td><?= $p['id'] ?></td>

<td><?= htmlspecialchars($p['nome']) ?></td>

<td><?= htmlspecialchars($p['cpf']) ?></td>

<td><?= htmlspecialchars($p['telefone']) ?></td>

<td>

<?php if($p['status'] == 'ativo'): ?>

<span class="badge bg-success">

Ativo

</span>

<?php else: ?>

<span class="badge bg-secondary">

Inativo

</span>

<?php endif; ?>

</td>

<td>

<a
href="visualizar.php?id=<?= $p['id'] ?>"
class="btn btn-info btn-sm">

Ver

</a>

<a
href="editar.php?id=<?= $p['id'] ?>"
class="btn btn-warning btn-sm">

Editar

</a>

<a
href="alterar-status.php?id=<?= $p['id'] ?>"
class="btn btn-secondary btn-sm">

Status

</a>

<a
href="excluir.php?id=<?= $p['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Deseja excluir este paciente?')">

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