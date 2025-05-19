<?php
require_once __DIR__ . '/../models/cliente.php';
require_once __DIR__ . '/../models/clienteDAO.php';
require_once __DIR__ . '/../../config/conexao.php';

$clienteDao = new ClienteDAO(Conexao::getConn());
$clientes = $clienteDao->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: auto;
        }
        th, td {
            border: 1px solid #888;
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background-color: #e0e0e0;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Clientes Cadastrados</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>CPF</th>
        <th>CNH</th>
        <th>Validade CNH</th>
    </tr>

    <?php if (count($clientes) > 0): ?>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente['id']) ?></td>
                <td><?= htmlspecialchars($cliente['nome']) ?></td>
                <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                <td><?= htmlspecialchars($cliente['cnh']) ?></td>
                <td><?= htmlspecialchars($cliente['validade_cnh']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">Nenhum cliente encontrado.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>