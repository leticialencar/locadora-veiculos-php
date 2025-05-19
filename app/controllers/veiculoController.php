<?php
require_once __DIR__ . '/../models/veiculo.php';
require_once __DIR__ . '/../models/veiculoDAO.php';
require_once __DIR__ . '/../../config/conexao.php';

class VeiculoController {
    private $veiculoDAO;

    public function __construct() {
        $pdo = Database::getConnection();
        $this->veiculoDAO = new VeiculoDAO($pdo);
    }

    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modelo = $_POST['modelo'] ?? null;
            $marca = $_POST['marca'] ?? null;
            $ano = $_POST['ano'] ?? null;
            $placa = $_POST['placa'] ?? null;
            $cor = $_POST['cor'] ?? null;
            $situacao = $_POST['situacao'] ?? 'Disponível';

            try {
                $veiculo = new Veiculo($modelo, $marca, $ano, $placa, $cor, $situacao);
                $this->veiculoDAO->inserir($veiculo);

                header("Location: /veiculos?success=1");
                exit;
            } catch (Exception $e) {
                echo "Erro: " . $e->getMessage();
            }
        }
    }

    public function listar() {
        $veiculos = $this->veiculoDAO->buscarTodos();
        require __DIR__ . '/../views/veiculos/listar.php';
    }
}
