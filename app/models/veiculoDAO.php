<?php
require_once 'Veiculo.php';

class VeiculoDAO {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function inserir(Veiculo $veiculo) {
        $sql = "INSERT INTO veiculos (modelo, marca, ano, placa, cor, situacao) 
                VALUES (:modelo, :marca, :ano, :placa, :cor, :situacao)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':modelo', $veiculo->getModelo());
        $stmt->bindValue(':marca', $veiculo->getMarca());
        $stmt->bindValue(':ano', $veiculo->getAno());
        $stmt->bindValue(':placa', $veiculo->getPlaca());
        $stmt->bindValue(':cor', $veiculo->getCor());
        $stmt->bindValue(':situacao', $veiculo->getSituacao());
        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM veiculos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            $veiculo = new Veiculo();
            $this->popularVeiculo($veiculo, $dados);
            return $veiculo;
        }
        return null;
    }

    public function listar() {
        $sql = "SELECT * FROM veiculos";
        $stmt = $this->conn->query($sql);
        $veiculos = [];

        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $veiculo = new Veiculo();
            $this->popularVeiculo($veiculo, $dados);
            $veiculos[] = $veiculo;
        }

        return $veiculos;
    }

    public function atualizar(Veiculo $veiculo) {
        $sql = "UPDATE veiculos SET modelo = :modelo, marca = :marca, ano = :ano, 
                placa = :placa, cor = :cor, situacao = :situacao WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':modelo', $veiculo->getModelo());
        $stmt->bindValue(':marca', $veiculo->getMarca());
        $stmt->bindValue(':ano', $veiculo->getAno());
        $stmt->bindValue(':placa', $veiculo->getPlaca());
        $stmt->bindValue(':cor', $veiculo->getCor());
        $stmt->bindValue(':situacao', $veiculo->getSituacao());
        $stmt->bindValue(':id', $veiculo->getId());
        return $stmt->execute();
    }

    public function excluir($id) {
        $sql = "DELETE FROM veiculos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    private function popularVeiculo(Veiculo $veiculo, array $dados) {
        $veiculo->setModelo($dados['modelo']);
        $veiculo->setMarca($dados['marca']);
        $veiculo->setAno($dados['ano']);
        $veiculo->setPlaca($dados['placa']);
        $veiculo->setCor($dados['cor']);

        if (isset($dados['situacao'])) {
            $reflexao = new ReflectionClass($veiculo);
            $prop = $reflexao->getProperty('situacao');
            $prop->setAccessible(true);
            $prop->setValue($veiculo, $dados['situacao']);
        }

        $reflexao = new ReflectionClass($veiculo);
        $propId = $reflexao->getProperty('id');
        $propId->setAccessible(true);
        $propId->setValue($veiculo, $dados['id']);
    }
}
