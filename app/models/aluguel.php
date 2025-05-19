<?php
class Aluguel {
    private $cliente_id, $veiculo_id, $data_retirada, $data_prevista, $valor_total;

    public function __construct($cliente_id, $veiculo_id, $data_retirada, $data_prevista, $valor_total) {
        $this->cliente_id = $cliente_id;
        $this->veiculo_id = $veiculo_id;
        $this->data_retirada = $data_retirada;
        $this->data_prevista = $data_prevista;
        $this->valor_total = $valor_total;
    }

    public function getClienteId() {
        return $this->cliente_id;
    }

    public function getVeiculoId() {
        return $this->veiculo_id;
    }

    public function getDataRetirada() {
        return $this->data_retirada;
    }

    public function getDataPrevista() {
        return $this->data_prevista;
    }

    public function getValorTotal() {
        return $this->valor_total;
    }

    public function setClienteId($cliente_id) {
        $this->cliente_id = $cliente_id;
    }

    public function setVeiculoId($veiculo_id) {
        $this->veiculo_id = $veiculo_id;
    }

    public function setDataRetirada($data_retirada) {
        $this->data_retirada = $data_retirada;
    }

    public function setDataPrevista($data_prevista) {
        $this->data_prevista = $data_prevista;
    }

    public function setValorTotal($valor_total) {
        $this->valor_total = $valor_total;
    }
}