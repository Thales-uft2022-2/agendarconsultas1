<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['tipo'] !== 'admin') {
    header("Location: ../dashboard/");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: registrar.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT *
    FROM usuarios
    WHERE id = ?
");

$sql->execute([$id]);

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: registrar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    /*
        Proteção do Administrador
    */
    if ($usuario['tipo'] === 'admin') {

        $tipo = 'admin';

    } else {

        $tipo = $_POST['tipo'];

    }

    if (!empty($senha)) {

        $hash = password_hash(
            $senha,
            PASSWORD_DEFAULT
        );

        $update = $pdo->prepare("
            UPDATE usuarios
            SET
                nome = ?,
                email = ?,
                tipo = ?,
                senha = ?
            WHERE id = ?
        ");

        $update->execute([
            $nome,
            $email,
            $tipo,
            $hash,
            $id
        ]);

    } else {

        $update = $pdo->prepare("
            UPDATE usuarios
            SET
                nome = ?,
                email = ?,
                tipo = ?
            WHERE id = ?
        ");

        $update->execute([
            $nome,
            $email,
            $tipo,
            $id
        ]);
    }

    header("Location: registrar.php");
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

<title>Editar Usuário</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card shadow">

<div class="card-header bg-warning">

<h3 class="m-0">

Editar Usuário

</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label class="form-label">

Nome

</label>

<input
type="text"
name="nome"
class="form-control"
value="<?= htmlspecialchars($usuario['nome']) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

E-mail

</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($usuario['email']) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Perfil

</label>

<?php if($usuario['tipo'] === 'admin'): ?>

<input
type="text"
class="form-control"
value="Administrador"
readonly>

<input
type="hidden"
name="tipo"
value="admin">

<small class="text-danger">

Administrador protegido.

</small>

<?php else: ?>

<select
name="tipo"
class="form-select">

<option
value="admin"
<?= $usuario['tipo'] == 'admin' ? 'selected' : '' ?>>

Admin

</option>

<option
value="medico"
<?= $usuario['tipo'] == 'medico' ? 'selected' : '' ?>>

Médico

</option>

<option
value="paciente"
<?= $usuario['tipo'] == 'paciente' ? 'selected' : '' ?>>

Paciente

</option>

</select>

<?php endif; ?>

</div>

<div class="mb-4">

<label class="form-label">

Nova Senha

</label>

<input
type="password"
name="senha"
class="form-control">

<small class="text-muted">

Deixe em branco para manter a senha atual.

</small>

</div>

<button
type="submit"
class="btn btn-warning">

Salvar Alterações

</button>

<a
href="registrar.php"
class="btn btn-secondary">

Voltar

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>