<?php

class Cliente {
    private $id, $nome, $cpf, $cnh, $validade_cnh;

    public function getId() {
        return $this->id;
    }
    public function getNome() {
        return $this->nome;
    }
    public function getCpf() {
        return $this->cpf;
    }
    public function getCnh() {
        return $this->cnh;
    }
    public function getValidadeCnh() {
        return $this->validade_cnh;
    }


    public function setId($id) {
        $this->id = $id;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }
    public function setCnh($cnh) {
        $this->cnh = $cnh;
    }
    public function setValidadeCnh($validade_cnh) {
        $this->validade_cnh = $validade_cnh;
    }

}