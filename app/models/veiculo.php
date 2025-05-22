<?php

class Veiculo {
    private $id, $modelo, $marca, $preco, $ano, $placa, $cor, $situacao;

    public function __construct($modelo, $marca, $preco, $ano, $placa, $cor, $situacao = 'Disponível') {
        $this->modelo = $modelo;
        $this->marca = $marca;
        $this->preco = $preco;
        $this->ano = $ano;
        $this->placa = $placa;
        $this->cor = $cor;
        $this->situacao = $situacao;
    }

    public function getId() { 
        return $this->id; 
    }

    public function setId($id) { 
        $this->id = $id; 
    }

    public function getModelo() { 
        return $this->modelo; 
    }

    public function getMarca() { 
        return $this->marca; 
    }

    public function getPreco() {
        return $this->preco;
    }

    public function getAno() { 
        return $this->ano; 
    }

    public function getPlaca() { 
        return $this->placa; 
    }

    public function getCor() { 
        return $this->cor; 
    }

    public function getSituacao() { 
        return $this->situacao; 
    }

    public function setSituacao($situacao) { 
        $this->situacao = $situacao; 
    }
}
