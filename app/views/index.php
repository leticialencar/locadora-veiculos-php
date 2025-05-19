<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/cliente.php';
require_once __DIR__ . '/../models/clienteDAO.php';
require_once __DIR__ . '/../models/veiculo.php';
require_once __DIR__ . '/../models/veiculoDAO.php';
require_once __DIR__ . '/../models/aluguel.php';
require_once __DIR__ . '/../models/aluguelDAO.php';

$clienteDAO = new ClienteDAO($pdo);
$veiculoDAO = new VeiculoDAO($pdo);
$aluguelDAO = new AluguelDAO($pdo);

$clientes = $clienteDAO->listar();
$veiculos = $veiculoDAO->listar();
$aluguels = $aluguelDAO->listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Locadora</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        h1 {
            margin-top: 40px;
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ddd;
        }
        .section {
            margin-bottom: 50px;
        }
    </style>
</head>
<body>
    <h1>Sistema da Locadora</h1>

    <div class="section">
        <h2>Clientes</h2>
        <?php if (count($clientes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>CNH</th>
                        <th>Validade CNH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= $cliente['id'] ?></td>
                            <td><?= $cliente['nome'] ?></td>
                            <td><?= $cliente['cpf'] ?></td>
                            <td><?= $cliente['cnh'] ?></td>
                            <td><?= $cliente['validade_cnh'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum cliente encontrado.</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Veículos</h2>
        <?php if (count($veiculos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Placa</th>
                        <th>Ano</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($veiculos as $veiculo): ?>
                        <tr>
                            <td><?= $veiculo['id'] ?></td>
                            <td><?= $veiculo['modelo'] ?></td>
                            <td><?= $veiculo['marca'] ?></td>
                            <td><?= $veiculo['placa'] ?></td>
                            <td><?= $veiculo['ano'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum veículo encontrado.</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Aluguéis</h2>
        <?php if (count($aluguels) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Cliente</th>
                        <th>ID Veículo</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($aluguels as $aluguel): ?>
                        <tr>
                            <td><?= $aluguel['id'] ?></td>
                            <td><?= $aluguel['cliente_id'] ?></td>
                            <td><?= $aluguel['veiculo_id'] ?></td>
                            <td><?= $aluguel['data_inicio'] ?></td>
                            <td><?= $aluguel['data_fim'] ?></td>
                            <td><?= $aluguel['valor'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum aluguel encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
