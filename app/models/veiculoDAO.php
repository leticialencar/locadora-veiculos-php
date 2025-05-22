<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/veiculo.php';

class VeiculoDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM veiculos");
        $veiculos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $v = new Veiculo(
                $row['modelo'], $row['marca'], $row['preco'], $row['ano'],
                $row['placa'], $row['cor'], $row['situacao']
            );
            $v->setId($row['id']);
            $veiculos[] = $v;
        }

        return $veiculos;
    }

    public function atualizarSituacao($veiculo_id, $novaSituacao) {
        $stmt = $this->conn->prepare("UPDATE veiculos SET situacao = ? WHERE id = ?");
        $stmt->execute([$novaSituacao, $veiculo_id]);
    }

    public function buscarPorId($id) {
    $stmt = $this->conn->prepare("SELECT * FROM veiculos WHERE id = ?");
    $stmt->execute([$id]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados) {
        $veiculo = new Veiculo($dados['modelo'],$dados['marca'],$dados['preco'],$dados['ano'],$dados['placa'],$dados['cor'],$dados['situacao']);
        $veiculo->setId($dados['id']);
        return $veiculo;
    }
    return null;
    }
}
