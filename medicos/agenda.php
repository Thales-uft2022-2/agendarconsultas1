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

$data = $_GET['data'] ?? date('Y-m-d');

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
    AND c.data_consulta = ?
    ORDER BY c.hora_consulta
");

$consultas->execute([
    $medico_id,
    $data
]);

$agenda = $consultas->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Agenda do Médico</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

Agenda - <?= htmlspecialchars($medico['nome']) ?>

</h3>

</div>

<div class="card-body">

<form method="GET" class="row mb-4">

<input
type="hidden"
name="id"
value="<?= $medico_id ?>">

<div class="col-md-4">

<label>Data</label>

<input
type="date"
name="data"
value="<?= $data ?>"
class="form-control">

</div>

<div class="col-md-2 d-flex align-items-end">

<button
type="submit"
class="btn btn-primary">

Pesquisar

</button>

</div>

</form>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>Hora</th>
<th>Paciente</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php if(count($agenda) > 0): ?>

<?php foreach($agenda as $item): ?>

<tr>

<td>

<?= substr($item['hora_consulta'],0,5) ?>

</td>

<td>

<?= htmlspecialchars($item['paciente']) ?>

</td>

<td>

<?= ucfirst($item['status']) ?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="3" class="text-center">

Nenhuma consulta para esta data.

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