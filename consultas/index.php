<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$consultas = $pdo->query("
    SELECT
        c.*,
        m.nome AS medico,
        p.nome AS paciente
    FROM consultas c
    INNER JOIN medicos m
        ON m.id = c.medico_id
    INNER JOIN pacientes p
        ON p.id = c.paciente_id
    ORDER BY c.data_consulta DESC,
             c.hora_consulta DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang='pt-BR'>

<head>

<meta charset='UTF-8'>

<meta
name='viewport'
content='width=device-width, initial-scale=1.0'>

<title>Consultas</title>

<link
href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css'
rel='stylesheet'>

</head>

<body>

<div class='container mt-5'>

<div class='d-flex justify-content-between align-items-center mb-4'>

<h2>Consultas</h2>

<div>

<a
href='../dashboard/'
class='btn btn-secondary'>

Dashboard

</a>

<a
href='agendar.php'
class='btn btn-primary'>

Nova Consulta

</a>

</div>

</div>

<table class='table table-bordered table-hover'>

<thead class='table-dark'>

<tr>

<th>ID</th>
<th>Paciente</th>
<th>Médico</th>
<th>Data</th>
<th>Hora</th>
<th>Status</th>
<th>Ações</th>

</tr>

</thead>

<tbody>

<?php foreach($consultas as $c): ?>

<tr>

<td><?= $c['id'] ?></td>

<td><?= htmlspecialchars($c['paciente']) ?></td>

<td><?= htmlspecialchars($c['medico']) ?></td>

<td><?= date('d/m/Y', strtotime($c['data_consulta'])) ?></td>

<td><?= substr($c['hora_consulta'], 0, 5) ?></td>

<td>

<?php if($c['status'] === 'agendada'): ?>

<span class='badge bg-primary'>Agendada</span>

<?php elseif($c['status'] === 'confirmada'): ?>

<span class='badge bg-info'>Confirmada</span>

<?php elseif($c['status'] === 'realizada'): ?>

<span class='badge bg-success'>Realizada</span>

<?php else: ?>

<span class='badge bg-danger'>Cancelada</span>

<?php endif; ?>

</td>

<td>

<a
href='visualizar.php?id=<?= $c['id'] ?>'
class='btn btn-info btn-sm'>

Ver

</a>

<a
href='editar.php?id=<?= $c['id'] ?>'
class='btn btn-warning btn-sm'>

Editar

</a>

<a
href='confirmar.php?id=<?= $c['id'] ?>'
class='btn btn-success btn-sm'>

Confirmar

</a>

<a
href='cancelar.php?id=<?= $c['id'] ?>'
class='btn btn-danger btn-sm'>

Cancelar

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</body>
</html>