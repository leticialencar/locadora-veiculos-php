<?php
require_once __DIR__ . '/../models/veiculoDAO.php';
require_once __DIR__ . '/../models/aluguelDAO.php';
require_once __DIR__ . '/../../config/conexao.php';

$conexao = Conexao::getConn();

$veiculoDao = new veiculoDAO($conexao);
$aluguelDao = new aluguelDAO($conexao);

// -------------------
// Processa ações CRUD do Aluguel
// -------------------
$action = $_POST['action'] ?? '';

if ($action === 'add_rental') {
    $clienteId     = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $veiculoId     = filter_input(INPUT_POST, 'veiculo_id', FILTER_VALIDATE_INT);
    $dataRetirada  = filter_input(INPUT_POST, 'data_retirada', FILTER_SANITIZE_STRING);
    $dataPrevista  = filter_input(INPUT_POST, 'data_prevista', FILTER_SANITIZE_STRING);

    if ($clienteId && $veiculoId && $dataRetirada && $dataPrevista) {
        $aluguel = new Aluguel();
        $aluguel->setClienteId($clienteId);
        $aluguel->setVeiculoId($veiculoId);
        $aluguel->setDataRetirada($dataRetirada);
        $aluguel->setDataPrevista($dataPrevista);
        $aluguel->setStatus('ativo');

        $aluguelDao->inserir($aluguel);
        $veiculoDao->atualizarSituacao($veiculoId, 'indisponivel');
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if ($action === 'delete_rental') {
    $aluguelId = filter_input(INPUT_POST, 'aluguel_id', FILTER_VALIDATE_INT);
    if ($aluguelId) {
        $aluguel = $aluguelDao->buscarPorId($aluguelId);
        if ($aluguel) {
            $aluguelDao->excluir($aluguelId);
            $veiculoDao->atualizarSituacao($aluguel['veiculo_id'], 'disponivel');
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$searchTerm = trim($_POST['search_term'] ?? '');
$alugueis = $searchTerm
    ? $aluguelDao->buscarPorClienteOuId($searchTerm)
    : $aluguelDao->listar();

$veiculos            = $veiculoDao->listar();
$veiculosDisponiveis = array_filter($veiculos, fn($v) => strtolower($v['situacao']) === 'disponivel');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Locadora</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Veículos</h2>
<table>
    <tr>
        <th>ID</th><th>Modelo</th><th>Marca</th><th>Placa</th><th>Ano</th><th>Situação</th>
    </tr>
    <?php foreach ($veiculos as $v): ?>
        <tr>
            <td><?= $v['id'] ?></td>
            <td><?= $v['modelo'] ?></td>
            <td><?= $v['marca'] ?></td>
            <td><?= $v['placa'] ?></td>
            <td><?= $v['ano'] ?></td>
            <td><?= $v['situacao'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Alugar Veículo</h2>
<form method="post">
    <input type="hidden" name="action" value="add_rental">
    Cliente:
    <select name="cliente_id" required>
        <option value="">Selecione</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['nome'] ?></option>
        <?php endforeach; ?>
    </select>
    Veículo:
    <select name="veiculo_id" required>
        <option value="">Selecione</option>
        <?php foreach ($veiculosDisponiveis as $vd): ?>
            <option value="<?= $vd['id'] ?>"><?= $vd['modelo'] ?> (<?= $vd['placa'] ?>)</option>
        <?php endforeach; ?>
    </select>
    Retirada: <input type="date" name="data_retirada" required>
    Prevista: <input type="date" name="data_prevista" required>
    <button type="submit">Alugar</button>
</form>

<form method="post">
    <label>Buscar aluguel (ID, nome ou veículo):</label>
    <input type="text" name="search_term" value="<?= htmlspecialchars($searchTerm) ?>">
    <button type="submit">Buscar</button>
</form>

<h2>Aluguéis</h2>
<table>
    <tr>
        <th>ID</th><th>Cliente</th><th>Veículo</th><th>Retirada</th><th>Prevista</th><th>Status</th><th>Ação</th>
    </tr>
    <?php foreach ($alugueis as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= $a['cliente_nome'] ?? 'N/D' ?></td>
            <td><?= $a['veiculo_id'] ?? ($a['modelo'] . ' - ' . $a['placa']) ?></td>
            <td><?= $a['data_retirada'] ?></td>
            <td><?= $a['data_prevista'] ?></td>
            <td><?= $a['status'] ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Excluir aluguel #<?= $a['id'] ?>?');">
                    <input type="hidden" name="action" value="delete_rental">
                    <input type="hidden" name="aluguel_id" value="<?= $a['id'] ?>">
                    <button type="submit">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
