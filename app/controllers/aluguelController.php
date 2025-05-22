<?php
require_once __DIR__ . '/../models/aluguelDAO.php';
require_once __DIR__ . '/../models/veiculoDAO.php';
require_once __DIR__ . '/../models/aluguel.php';

class AluguelController {
    private $aluguelDAO;
    private $veiculoDAO;

    public function __construct() {
        $this->aluguelDAO = new AluguelDAO();
        $this->veiculoDAO = new VeiculoDAO();
    }

    public function cadastrar($nome_cliente, $veiculo_id, $data_retirada, $data_prevista) {
        if ($data_prevista < $data_retirada) {
            echo "<script>alert('A data prevista não pode ser menor que a data de retirada.'); history.back();</script>";
            exit;
        }
        $this->aluguelDAO->inserir($nome_cliente, $veiculo_id, $data_retirada, $data_prevista);
        $this->veiculoDAO->atualizarSituacao($veiculo_id, 'Alugado');
    }

    public function atualizar($id, $nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $status, $data_real = null) {
        if ($data_prevista < $data_retirada) {
            echo "<script>alert('A data prevista não pode ser menor que a data de retirada.'); history.back();</script>";
            exit;
        }

        if ($status == 'Finalizado'){
            if ($data_real < $data_retirada) {
                echo "<script>alert('A data de devolução não pode ser menor que a data de retirada.'); history.back();</script>";
            exit;
        }
        }

        $aluguelAtual   = $this->aluguelDAO->buscarPorId($id);
        $veiculoAntigoId = $aluguelAtual['veiculo_id'];

        if ($veiculoAntigoId != $veiculo_id) {
            $this->veiculoDAO->atualizarSituacao($veiculoAntigoId, 'Disponível');
            $this->veiculoDAO->atualizarSituacao($veiculo_id, 'Alugado');
        }
        if ($status === 'Finalizado') {
            $this->veiculoDAO->atualizarSituacao($veiculo_id, 'Disponível');
        }
        $this->aluguelDAO->atualizar($id, $nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $status, $data_real);
    }

    public function excluir($id) {
        $this->aluguelDAO->excluir($id);
    }

    public function buscar($termo) {
        return $this->aluguelDAO->buscar($termo);      
    }

    public function buscarPorId($id) {
        return $this->aluguelDAO->buscarPorId($id);
    }

    public function registrarDevolucao($id, $data_real) {
        $dados = $this->aluguelDAO->buscarPorId($id);
        
        if (!$dados) return false;

        $aluguel = new Aluguel(
            $dados['nome_cliente'],
            $dados['veiculo_id'],
            $dados['data_retirada'],
            $dados['data_prevista']
        );
        $aluguel->setId($id);
        $aluguel->setDataReal($data_real);
        $aluguel->setStatus('Finalizado');

        $veiculo = $this->veiculoDAO->buscarPorId($dados['veiculo_id']);

        $valorTotal = $aluguel->calcularValorTotal($veiculo);
        $aluguel->setValorTotal($valorTotal);

        $this->aluguelDAO->registrarDevolucao($aluguel);
        $this->veiculoDAO->atualizarSituacao($aluguel->getVeiculoId(), 'Disponível');
        return true;
    }

    public function calcularEstimativa($dataRetirada, $dataPrevista, $precoBase, $diasBase = 7, $jurosPorDia = 20) {
        $inicio = new DateTime($dataRetirada);
        $fim    = new DateTime($dataPrevista);
        $dias   = $inicio->diff($fim)->days + 1;

        if ($dias <= $diasBase) return $precoBase;

        $extra = $dias - $diasBase;
        return $precoBase + ($extra * $jurosPorDia);
    }
}
