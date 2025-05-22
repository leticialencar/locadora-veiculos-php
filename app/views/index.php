<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../controllers/veiculoController.php';
require_once __DIR__ . '/../controllers/aluguelController.php';

$aluguelController = new AluguelController();
$veiculoDAO        = new VeiculoDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluguelController->cadastrar(
        $_POST['nome_cliente'],
        $_POST['veiculo_id'],
        $_POST['data_retirada'],
        $_POST['data_prevista']
    );
    header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=1");
    exit;
}

$veiculos   = $veiculoDAO->listar();
$termoBusca = $_GET['busca'] ?? '';
$alugueis   = $termoBusca ? $aluguelController->buscar($termoBusca) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Locadora</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (isset($_GET['sucesso'])): ?>
    <p style="color:green;">Aluguel registrado com sucesso!</p>
<?php endif; ?>

<h1>Veículos Cadastrados</h1>
<h3>O valor do aluguel é fixo até 7 dias, após isso é cobrado um adicional de + R$ 20,00 por dia</h3>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th><th>Modelo</th><th>Marca</th><th>Preço</th>
            <th>Ano</th><th>Placa</th><th>Cor</th><th>Situação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($veiculos as $v): ?>
        <tr>
            <td><?= $v->getId() ?></td>
            <td><?= $v->getModelo() ?></td>
            <td><?= $v->getMarca() ?></td>
            <td><?= $v->getPreco() ?></td>
            <td><?= $v->getAno() ?></td>
            <td><?= $v->getPlaca() ?></td>
            <td><?= $v->getCor() ?></td>
            <td><?= $v->getSituacao() ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Alugar Veículo</h2>
<form method="POST">
    <label>Nome do Cliente: <input type="text" name="nome_cliente" required></label><br>
    <label>Veículo:
        <select name="veiculo_id" required>
            <option value="">Selecione um veículo</option>
            <?php foreach ($veiculos as $v): ?>
                <?php if ($v->getSituacao() === 'Disponível'): ?>
                    <option value="<?= $v->getId() ?>"><?= "{$v->getModelo()} - {$v->getPlaca()}" ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Data Retirada: <input type="date" name="data_retirada" required></label><br>
    <label>Data Prevista: <input type="date" name="data_prevista" required></label><br>
    <button type="submit">Confirmar Aluguel</button>
</form>

<h2>Buscar Aluguéis</h2>
<form method="GET">
    <input type="text" name="busca" placeholder="Buscar por cliente ou veículo" value="<?= htmlspecialchars($termoBusca) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if ($alugueis): ?>
<h3>Resultados da Busca</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th><th>Cliente</th><th>Veículo</th><th>Data Retirada</th>
        <th>Data Prevista</th><th>Valor Total</th><th>Valor Estimado</th>
        <th>Data Devolução</th><th>Status</th><th>Ações</th>
    </tr>
    <?php foreach ($alugueis as $a): ?>
    <tr>
        <td><?= $a['id'] ?></td>
        <td><?= htmlspecialchars($a['nome_cliente']) ?></td>
        <td><?= htmlspecialchars($a['nome_veiculo']) ?></td>
        <td><?= $a['data_retirada'] ?></td>
        <td><?= $a['data_prevista'] ?></td>
        <td>
            <?= $a['valor_total'] !== null
                ? 'R$ ' . number_format($a['valor_total'], 2, ',', '.')
                : '-' ?>
        </td>
        <td>
            <?= $a['status'] === 'Aberto'
                ? 'R$ ' . number_format($a['estimativa_valor'], 2, ',', '.')
                : '-' ?>
        </td>
        <td><?= $a['data_real'] ?? '-' ?></td>
        <td><?= $a['status'] ?></td>
        <td>
            <a href="editar.php?id=<?= urlencode($a['id']) ?>">Editar</a> | 
            <a href="excluir.php?id=<?= $a['id'] ?>"
               onclick="return confirm('Tem certeza que deseja excluir este aluguel?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php elseif ($termoBusca): ?>
    <p>Nenhum resultado encontrado para "<?= htmlspecialchars($termoBusca) ?>".</p>
<?php endif; ?>
</body>
</html>
