<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../controllers/aluguelController.php';

$aluguelController = new AluguelController();

$id = $_GET['id'] ?? null;
if ($id) {
    $aluguel = $aluguelController->buscarPorId($id);
    if ($aluguel && $aluguel['status'] === 'Finalizado') {
        $aluguelController->excluir($id);
        header('Location: index.php');
        exit;
    } else {
        echo "<script>alert('O aluguel não pode ser excluído porque está em aberto.'); window.history.back();</script>";
        exit;
    }
}

header('Location: index.php');
exit;
?>
