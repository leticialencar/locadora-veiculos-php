<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once 'aluguel.php';
require_once 'veiculoDAO.php';

class AluguelDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConn();
    }

    public function inserir($nome_cliente, $veiculo_id, $data_retirada, $data_prevista) {
        $stmt = $this->conn->prepare(
            "INSERT INTO alugueis (nome_cliente, veiculo_id, data_retirada, data_prevista, status)
             VALUES (?, ?, ?, ?, 'Aberto')"
        );
        $stmt->execute([$nome_cliente, $veiculo_id, $data_retirada, $data_prevista]);
    }

    public function atualizar($id, $nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $status, $data_real = null) {
        if ($status === 'Finalizado') {
            if (!$data_real) {
                $data_real = date('Y-m-d');
            }

            $veiculoDAO = new VeiculoDAO();
            $veiculo = $veiculoDAO->buscarPorId($veiculo_id);

            $tmp = new Aluguel($nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $data_real, 0, 'Finalizado');
            $valorTotal = $tmp->calcularValorTotal($veiculo, 7, 20);

            $sql = "UPDATE alugueis
                    SET nome_cliente = ?, veiculo_id = ?, data_retirada = ?, data_prevista = ?,
                        data_real = ?, valor_total = ?, status = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $data_real, $valorTotal, $status, $id]);
        } else {
            $sql = "UPDATE alugueis SET nome_cliente = ?, veiculo_id = ?, data_retirada = ?, data_prevista = ?, data_real = NULL, valor_total = NULL, status = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $status, $id]);
        }
    }

    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM alugueis WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function buscar($termo) {
        $sql = "SELECT a.id, a.nome_cliente, a.veiculo_id, a.data_retirada, a.data_prevista, a.data_real, a.valor_total, a.status, v.preco, v.modelo AS nome_veiculo
                FROM alugueis a
                JOIN veiculos v ON a.veiculo_id = v.id
                WHERE a.nome_cliente LIKE :termo OR v.modelo LIKE :termo";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':termo' => "%$termo%"]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $veiculoDAO = new VeiculoDAO();
        $alugueis = [];

        foreach ($resultados as $row) {
            $aluguel = new Aluguel(
                $row['nome_cliente'],
                $row['veiculo_id'],
                $row['data_retirada'],
                $row['data_prevista'],
                $row['data_real'],
                $row['valor_total'],
                $row['status']
            );
            $aluguel->setId($row['id']);

            $veiculo = $veiculoDAO->buscarPorId($row['veiculo_id']);

            if ($aluguel->getStatus() === 'Finalizado' && $aluguel->getDataReal()) {
                $valorCalculado = $aluguel->calcularValorTotal($veiculo, 7, 20);
                $aluguel->setValorTotal($valorCalculado);
            } else {
                $estimativa = $aluguel->calcularValorTotal($veiculo, 7, 20);
                $aluguel->setValorTotal(null);
            }

            $alugueis[] = [
                'id' => $aluguel->getId(),
                'nome_cliente' => $aluguel->getNomeCliente(),
                'veiculo_id' => $aluguel->getVeiculoId(),
                'data_retirada' => $aluguel->getDataRetirada(),
                'data_prevista' => $aluguel->getDataPrevista(),
                'data_real' => $aluguel->getDataReal(),
                'valor_total' => $aluguel->getValorTotal(),
                'estimativa_valor' => $estimativa ?? null,
                'status' => $aluguel->getStatus(),
                'nome_veiculo' => $row['nome_veiculo']
            ];
        }

        return $alugueis;
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM alugueis WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarDevolucao(Aluguel $aluguel, $diasBase = 7, $jurosPorDia = 20.0) {
        $veiculoDAO = new VeiculoDAO();
        $veiculo = $veiculoDAO->buscarPorId($aluguel->getVeiculoId());

        $valorTotal = $aluguel->calcularValorTotal($veiculo, $diasBase, $jurosPorDia);

        $stmt = $this->conn->prepare(
            "UPDATE alugueis
             SET data_real = ?, valor_total = ?, status = ?
             WHERE id = ?"
        );
        $stmt->execute([
            $aluguel->getDataReal(),
            $valorTotal,
            $aluguel->getStatus(),
            $aluguel->getId()
        ]);
    }
}
