<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../controllers/aluguelController.php';

$aluguelController = new AluguelController();
$veiculoDAO = new VeiculoDAO();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$aluguel = $aluguelController->buscarPorId($id);
if (!$aluguel) {
    echo "Aluguel não encontrado!";
    exit;
}

$veiculos = $veiculoDAO->listar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_real = ($_POST['status'] === 'Finalizado') ? ($_POST['data_real'] ?? null) : null;

    $aluguelController->atualizar(
        $id,
        $_POST['nome_cliente'],
        $_POST['veiculo_id'],
        $_POST['data_retirada'],
        $_POST['data_prevista'],
        $_POST['status'],
        $data_real
    );
    header("Location: index.php?sucesso_edicao=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluguel</title>
    <link rel="stylesheet" href="editaluguel.css">
    <script>
        function toggleDataReal() {
            const status = document.querySelector('select[name="status"]').value;
            const dataRealInput = document.getElementById('data_real');
            const dataRealLabel = document.getElementById('label_data_real');

            if (status === 'Finalizado') {
                dataRealInput.disabled = false;
                dataRealLabel.style.display = 'block';
            } else {
                dataRealInput.disabled = true;
                dataRealInput.value = '';
                dataRealLabel.style.display = 'none';
            }
        }

        window.addEventListener('DOMContentLoaded', toggleDataReal);
    </script>
</head>
<body>

<h1>Editar Aluguel</h1>

<form method="POST">
    <label>Nome do Cliente:
        <input type="text" name="nome_cliente" value="<?= htmlspecialchars($aluguel['nome_cliente']) ?>" required>
    </label><br>

    <label>Veículo:
    <select name="veiculo_id" required>
        <option value="">Selecione um veículo</option>
        <?php foreach ($veiculos as $v): ?>
            <?php
                $veiculoId = $v->getId();
                $modelo = $v->getModelo();
                $placa = $v->getPlaca();
                $situacao = $v->getSituacao();
                $selecionado = ($veiculoId == $aluguel['veiculo_id']) ? 'selected' : '';
            ?>
            <?php if ($situacao === 'Disponível' || $veiculoId == $aluguel['veiculo_id']): ?>
                <option value="<?= $veiculoId ?>" <?= $selecionado ?>>
                    <?= "{$modelo} - {$placa}" ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    </label><br>

    <label>Data Retirada:
        <input type="date" name="data_retirada" value="<?= $aluguel['data_retirada'] ?>" required>
    </label><br>

    <label>Data Prevista:
        <input type="date" name="data_prevista" value="<?= $aluguel['data_prevista'] ?>" required>
    </label><br>

    <label>Status:
        <select name="status" onchange="toggleDataReal()" required>
            <option value="Aberto" <?= $aluguel['status'] == 'Aberto' ? 'selected' : '' ?>>Aberto</option>
            <option value="Finalizado" <?= $aluguel['status'] == 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
        </select>
    </label><br>

    <label id="label_data_real" style="display: none;">Data de Devolução:
        <input type="date" name="data_real" id="data_real" value="<?= $aluguel['data_real'] ?? '' ?>">
    </label><br>

    <button type="submit">Salvar Alterações</button>
</form>

<a href="index.php">Voltar</a>

</body>
</html>
