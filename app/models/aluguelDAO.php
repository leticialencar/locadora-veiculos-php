<?php
require_once 'aluguel.php';

class AluguelDAO {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }


    public function criar(Aluguel $aluguel) {
        $sql = "INSERT INTO alugueis (cliente_id, veiculo_id, data_retirada, data_prevista, valor_total)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $aluguel->getClienteId(),
            $aluguel->getVeiculoId(),
            $aluguel->getDataRetirada(),
            $aluguel->getDataPrevista(),
            $aluguel->getValorTotal()
        ]);
    }


    public function listarTodos() {
        $stmt = $this->conn->query("SELECT * FROM alugueis");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM alugueis WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function atualizar($id, Aluguel $aluguel) {
        $sql = "UPDATE alugueis 
                SET cliente_id = ?, veiculo_id = ?, data_retirada = ?, data_prevista = ?, valor_total = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $aluguel->getClienteId(),
            $aluguel->getVeiculoId(),
            $aluguel->getDataRetirada(),
            $aluguel->getDataPrevista(),
            $aluguel->getValorTotal(),
            $id
        ]);
    }

    
    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM alugueis WHERE id = ?");
        return $stmt->execute([$id]);
    }
}