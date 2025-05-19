<?php

require_once 'cliente.php';

class ClienteDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar(Cliente $cliente) {
        $sql = "INSERT INTO clientes (nome, cpf, cnh, validade_cnh) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $cliente->getNome(),
            $cliente->getCpf(),
            $cliente->getCnh(),
            $cliente->getValidadeCnh()
        ]);
    }

    public function listar() {
        $sql = "SELECT * FROM clientes";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            $cliente = new Cliente();
            $cliente->setId($dados['id']);
            $cliente->setNome($dados['nome']);
            $cliente->setCpf($dados['cpf']);
            $cliente->setCnh($dados['cnh']);
            $cliente->setValidadeCnh($dados['validade_cnh']);
            return $cliente;
        }
        return null;
    }

    public function atualizar(Cliente $cliente) {
        $sql = "UPDATE clientes SET nome = ?, cpf = ?, cnh = ?, validade_cnh = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $cliente->getNome(),
            $cliente->getCpf(),
            $cliente->getCnh(),
            $cliente->getValidadeCnh(),
            $cliente->getId()
        ]);
    }

    public function excluir($id) {
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}