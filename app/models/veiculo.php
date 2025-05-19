<?php

class Veiculo {
    private $id, $modelo, $marca, $ano, $placa, $cor;
    private $situacao = 'Disponível';

    public function getId(){
        return $this->id;
    }

    public function getModelo(){
        return $this->modelo;
    }

    public function setModelo($modelo){
        $this->modelo = $modelo;
    }

    public function getMarca(){
        return $this->marca;
    }

    public function setMarca($marca){
        $this->marca = $marca;
    }

    public function getAno(){
        return $this->ano;
    }

    public function setAno($ano){
        $this->ano = $ano;
    }

    public function getPlaca(){
        return $this->placa;
    }

    public function setPlaca($placa){
        $this->placa = $placa;
    }

    public function getCor(){
        return $this->cor;
    }

    public function setCor(){
        $this->cor = $cor;
    }

    public function getSituacao(){
        return $this->situacao;
    }

    public function setSituacao(){
        $opcoes = ['Disponível' , 'Alugado' , 'Manutenção'];

        if (in_array($situacao, $opcoes)) {
            $this->situacao = $situacao;
        }
        else {
            throw new Exception("Situação inválida");
        }
    }
}