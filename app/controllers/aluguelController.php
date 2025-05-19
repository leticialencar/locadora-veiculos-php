<?php
require_once 'app/models/aluguel.php';
require_once 'app/dao/AluguelDAO.php';

class AluguelController {
    private $aluguelDAO;

    public function __construct(PDO $conn) {
        $this->aluguelDAO = new AluguelDAO($conn);
    }

    public function criar($dados) {
        $aluguel = new Aluguel(
            $dados['cliente_id'],
            $dados['veiculo_id'],
            $dados['data_retirada'],
            $dados['data_prevista'],
            $dados['valor_total']
        );

        if ($this->aluguelDAO->criar($aluguel)) {
            echo json_encode(['mensagem' => 'Aluguel criado com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao criar aluguel.']);
        }
    }

    public function listarTodos() {
        $alugueis = $this->aluguelDAO->listarTodos();
        echo json_encode($alugueis);
    }

    public function buscarPorId($id) {
        $aluguel = $this->aluguelDAO->buscarPorId($id);
        if ($aluguel) {
            echo json_encode($aluguel);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Aluguel não encontrado.']);
        }
    }

    public function atualizar($id, $dados) {
        $aluguel = new Aluguel(
            $dados['cliente_id'],
            $dados['veiculo_id'],
            $dados['data_retirada'],
            $dados['data_prevista'],
            $dados['valor_total']
        );

        if ($this->aluguelDAO->atualizar($id, $aluguel)) {
            echo json_encode(['mensagem' => 'Aluguel atualizado com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar aluguel.']);
        }
    }

    // Excluir um aluguel
    public function excluir($id) {
        if ($this->aluguelDAO->excluir($id)) {
            echo json_encode(['mensagem' => 'Aluguel excluído com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao excluir aluguel.']);
        }
    }
}