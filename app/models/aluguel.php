<?php
require_once __DIR__ . '/veiculo.php';

class Aluguel {
    private $id, $nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $data_real, $valor_total, $status;

    public function __construct($nome_cliente, $veiculo_id, $data_retirada, $data_prevista, $data_real = null, $valor_total = 0.0, $status = 'Aberto') {
        $this->nome_cliente = $nome_cliente;
        $this->veiculo_id = $veiculo_id;
        $this->data_retirada = $data_retirada;
        $this->data_prevista = $data_prevista;
        $this->data_real = $data_real;
        $this->valor_total = $valor_total;
        $this->status = $status;
    }

    public function getId() { 
        return $this->id; 
    }
    public function setId($id) { 
        $this->id = $id; 
    }

    public function getNomeCliente() { 
        return $this->nome_cliente; 
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
    public function getDataReal() { 
        return $this->data_real; 
    }
    public function getValorTotal() { 
        return $this->valor_total; 
    }
    public function getStatus() { 
        return $this->status; 
    }

    public function setStatus($status) { 
        $this->status = $status; 
    }
    public function setDataReal($data) { 
        $this->data_real = $data; 
    }
    public function setValorTotal($valor) { 
        $this->valor_total = $valor; 
    }

    public function calcularValorTotal(Veiculo $veiculo, $diasBase = 7, $jurosPorDia = 20.0) {
        if (!$this->data_retirada) {
            return $veiculo->getPreco();
        }
        
        $dataParaCalculo = $this->data_real ?: $this->data_prevista;

        $retirada = new DateTime($this->data_retirada);
        $real = new DateTime($dataParaCalculo);

        $diferenca = $retirada->diff($real);

        $diasUsados = $diferenca->invert ? 0 : $diferenca->days;

        $diasAtraso = max(0, $diasUsados - $diasBase);

        $juros = $diasAtraso * $jurosPorDia;
        return $veiculo->getPreco() + $juros;
    }
}
