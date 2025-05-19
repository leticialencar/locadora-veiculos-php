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


    public function listar() {
        $stmt = $this->conn->query("SELECT * FROM alugueis");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscar($termo = '') {
        $sql = "SELECT a.*, c.nome AS cliente_nome 
                FROM alugueis a 
                JOIN clientes c ON a.cliente_id = c.id 
                WHERE c.nome LIKE :termo 
                OR a.data_retirada LIKE :termo 
                OR a.data_prevista LIKE :termo";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':termo' => "%$termo%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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